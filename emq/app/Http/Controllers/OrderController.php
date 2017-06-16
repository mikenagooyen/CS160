<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\OrderProductsController;
use App\Cart;
use App\Address;
use App\Payment;
use App\Products; //added
use App\Order; //Import Payment to Controller
use App\OrderPayment; 
use App\OrderAddress; //Import Payment to Controller
use App\Store; //Import Payment to Controller
use App\OrderProducts; //Import Payment to Controller

use Illuminate\Support\Facades\Auth;//Needed to use Auth::
use Illuminate\Support\Facades\DB;//Needed to use DB::

use Carbon\Carbon;
class OrderController extends Controller
{
    /**
    * User must be authenticated before accessing
    *
    */
	 public function __construct()
    {
        $this->middleware('auth');
    }

    public function cartTotal(){
        $cart = Cart::where('user_id', Auth::user()->id )->get();

        $total_price = 0;
        $total_items = 0;
        foreach ($cart as $item){
            $total_items += $item->quantity;
            $total_price += $item->product->price * $item->quantity;
        }



        $total = array("cost" => $total_price,"quantity"=> $total_items);
        return $total;
    }

    /**
    * Stores the user's order into DB
    * @param Request The request received
    * @return view Returns process.complete view
    */
    public function completeOrder(Request $request){

        $this->validate($request, [
            'address' => 'required|integer|exists:address,id',
            'payment' => 'required|integer|exists:payment,id',
        ]);

        $a = Address::find( $request['address'] );
        $p = Payment::find( $request['payment'] );
        if( $a->user_id == Auth::user()->id && $p->user_id == Auth::user()->id){
            $total = OrderController::cartTotal();

            //check if cart is empty, in case they went to previous page after checking out and tried to check out again
            if( $total['quantity'] == 0 ){
                //cart is empty
                $status = "Your Cart is Empty.";
                return redirect()->action('CartController@getCart')->with('status', $status);
            }
            /* start of stuff i added */
            $cart = Cart::where('user_id', Auth::user()->id )->get();
            $inventory_errors = false;
            //needed to use associative array to keep track of cart quantity of THIS instance.
            //to avoid race condition if user tries to check out from two browsers at the same time.
            $item_instance_quantity = array();
            //needed to use arrays to restore product quantity if a race condition occurs.
            $product_id_array = array();
            $cart_quantity_array = array();
            foreach ($cart as $item){
                $item_instance_quantity[$item->id] = $item->quantity;
                array_push($product_id_array, $item->product->id);
                array_push($cart_quantity_array, $item->quantity);
                if( $item->quantity > $item->product->quantity ){
                    $item->quantity = $item->product->quantity;
                    $item->save();
                    $inventory_errors = true;
                }
            }
            if( $inventory_errors ){
                $status = "We apologize for the inconvenience, some cart items have been changed to reflect most recent inventory.";
                return redirect()->action('CartController@getCart')->with('status', $status);
            }
            //process was successfull
            foreach ($cart as $item) {
                if($item->quantity == 0){
                    $item->delete();
                }else{
                    $item->product->quantity = $item->product->quantity - $item->quantity;
                    $item->product->save();
                }
            }

            /* end of stuff i added */
        	$order = new Order;
        	$address_id = $request['address'];
        	$payment_id = $request['payment'];
        	$cost = $total['cost'];
        	OrderController::createOrder($order,Auth::user()->id,$payment_id,$address_id,$cost, $item_instance_quantity);
            //race condition, duplicate order detected with zero inventory.
            if(count($order->products) == 0){
                $order->address->delete();
                $order->payment->delete();
                $order->delete();
                $status = "Your Cart is Empty.";
                //restore product inventory that was removed from race condition.
                for($i=0; $i < sizeof($product_id_array); $i++){
                    $p = Products::find($product_id_array[$i]);
                    $p->quantity = $p->quantity + $cart_quantity_array[$i];
                    $p->save();
                }
                return redirect()->action('CartController@getCart')->with('status', $status);
            }
            return view('process.complete', ['order' => $order]);

            
        }
        $status = "Error: Illegal Input Detected.";
        return redirect()->action('CartController@getCart')->with('status', $status);
    }


    /**
    * creates the order and saves it into database
    *@param $order The order object
    *@param $user_id Id of the user
    *@param $payment_id Id of the payment
    *@param $address_id Id of the address
    *@param $cost The cost of the order
    *
    */
    public function createOrder($order,$user_id,$payment_id,$address_id,$cost, &$item_instance_quantity){
        /* Returns associate array of nearest store with store_id and delivery_time */
        $nearest_store = OrderController::returnStoreAndDeliveryTime( $address_id );

    	$order->user_id = $user_id;
    	$order->store_id = $nearest_store['store_id'];//$store->id;
        $order->delivery_time = $nearest_store['delivery_time'];
        $order->delivered = false;
        $store = Store::find( $nearest_store['store_id'] );
    	// $order->orderaddress = $address_id;
    	// $order->orderpayment = $payment_id;
    	$order->orderpayment_id = $this->createOrderPayment($payment_id);
    	
    	$order->orderaddress_id = $this->createOrderAddress($address_id);
    	
    	$order->cost = $cost;
        $tax = ($cost * ($store->salesTax / 100));
        $order->tax = number_format($tax, 2, '.', '');

        $order->total = $order->cost + $order->tax;
    	$order->save();
    	$this->migrateCartToOrderHistory($order->id, $item_instance_quantity);

    }

    /**
    * Creates the OrderPayment object and saves to database
    * @param $id the id of the payment
    * @return returns the id of the new OrderPayment 
    */
    public function createOrderPayment($id){
    	$orderPayment = new OrderPayment;
    	$userPayment = Payment::find($id);
    	$orderPayment->nameOnCard = $userPayment->nameOnCard;
    	$orderPayment->lastFour = $userPayment->lastFour;
    	$orderPayment->expMonth = $userPayment->expMonth;
    	$orderPayment->expYear = $userPayment->expYear;
    	$orderPayment->save();
    	return $orderPayment->id;
    }

    /**
    * Creates the OrderAddress object and saves to database
    * @param $id the id of the address
    * @return returns the id of the new OrderAddress
    */
    public function createOrderAddress($id){
    	$orderAddress = new orderAddress;
    	$userAddress = Address::find($id);
    	$orderAddress->fullName = $userAddress->fullName;
    	$orderAddress->address = $userAddress->address;
    	$orderAddress->address2 = $userAddress->address2;
    	$orderAddress->city = $userAddress->city;
    	$orderAddress->state = $userAddress->state;
    	$orderAddress->zip = $userAddress->zip;
    	$orderAddress->country = $userAddress->country;
    	$orderAddress->phone = $userAddress->phone;
    	$orderAddress->save();
    	return $orderAddress->id;
    }

    /**
    * Returns all of the orders for user
    * @return view returns account.orders view
    */
    public function returnOrderHistory(){
        //First check ALL orders to see if they arrived.
        $orders = Order::where('user_id', Auth::user()->id )->orderBy('id', 'DESC')->get();
        $now = Carbon::now();
        foreach ($orders as $order) {
            OrderController::updateOrderIfDelivered( $now, $order );
        }
        //safe to paginate now.
        $orders = Order::where('user_id', Auth::user()->id )->orderBy('id', 'DESC')->paginate(4);
        return view('account.orders', ['orders' => $orders]);
    }

    public function updateOrderIfDelivered( $now, $order ){
        if( $order->delivered == false ){
            //$now= Carbon::now(); //current time
            $current_delivery_time = $now->diffInSeconds($order->created_at);
            if( $current_delivery_time > $order->delivery_time ){
                /* then order has already been delivered */
                /* generate delivered_at timestamp */
                $delivered_at = $order->created_at->addSeconds( $order->delivery_time )->format('l, F jS Y @ h:i A');
                $order->delivered_at = $delivered_at;
                $order->delivered = true;
                $order->save();
            }
        }
    }

    public function address2html( $name, $obj ){
        $addressHtml = "<center><b>".$name."</b><br>".$obj->address;
        if( $obj->address2 != ""){
            $addressHtml .= ", ".$obj->address2;
        }
        $addressHtml .= "<br>".$obj->city .", ".$obj->state ." ".$obj->zip."<br>Phone: ".$obj->phone."</center>";
        return $addressHtml;
    }

    public function returnOrderTracking( $id ){
        //add error checking for non-ids
        $order = Order::find( $id );

        if($order){
            /* if Order belongs to Customer */
            if( $order->user_id == Auth::user()->id ){



                if( $order->delivered == false ){

                    $now= Carbon::now(); //current time
                    $current_delivery_time = $now->diffInSeconds($order->created_at);

                    if( $current_delivery_time < $order->delivery_time){
                        $home = $order->address->address.",".$order->address->city.",".$order->address->state." ".$order->address->zip.",".$order->address->country;
                        $store = $order->store->address.",".$order->store->city.",".$order->store->state." ".$order->store->zip.",".$order->store->country;
                        $delivery_estimate = $order->created_at->addSeconds( $order->delivery_time );
                        
                        $homeAddress = OrderController::address2html($order->address->fullName, $order->address);
                        $storeAddress = OrderController::address2html($order->store->name, $order->store);
                        return view('account.tracking', ['customer_address' => $home, 'store_address' => $store, 'current_delivery_time' => $current_delivery_time, 'order' => $order, 'delivery_estimate' => $delivery_estimate->format('l, F jS Y @ h:i A') , 'homeAddressHTML' => $homeAddress, 'storeAddressHTML' => $storeAddress]);
                    }else{
                        return redirect('/account/orders');
                    }
                }
                return redirect('/account/orders');
            }
        }
        
        return redirect('/');
    }


    /**
    * Migrates the items in carts to orderproduct
    * @param $order_id The id of the order
    *
    */
    public function migrateCartToOrderHistory( $order_id , &$item_instance_quantity){

        $cart = Cart::where('user_id', Auth::user()->id )->get();

        foreach ($cart as $item){
	    	$order_product = new OrderProducts;
	    	$order_product->order_id = $order_id;
	    	$order_product->product_id = $item->product->id;
	    	$order_product->price = $item->product->price;
	    	$order_product->quantity = $item_instance_quantity[$item->id];//$item->quantity;
	    	$order_product->save(); //save item into order history
	    	$item->delete(); //delete item from cart
        }

    }


    public function gmapsStringify( $address ){
        $address=str_replace(" ","+",$address);
        return $address;
    }

    public function return_delivery_time($store, $home){
        $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=' .$store. '&destinations='.$home.'&key=AIzaSyCz2mUMKWxhHnmrCZoVYiWjwPwu3PUCPYs&format=json';
        $delivery_json =json_decode(file_get_contents( $url ));
        return $delivery_json->rows[0]->elements[0]->duration->value;
    }

    public function returnStoreAndDeliveryTime( $customer_address_id ){
        /* retrieve customer address object*/
        $customer = Address::find( $customer_address_id );
        /* convert address object to string */
        $customer_address = $customer->address.",".$customer->city.",".$customer->state." ".$customer->zip.",".$customer->country;
        /* stringify customer address for gmaps */
        $customer_address = OrderController::gmapsStringify( $customer_address );

        /* retrieve all stores*/
        $stores = Store::all();

        $fastest_delivery_store = "";
        $fastest_delivery_time = "";
        foreach($stores as $store){
            /* convert store address object to string */
            $store_address = $store->address.",".$store->city.",".$store->state." ".$store->zip.",".$store->country;
            /* stringify store address for gmaps */
            $store_address = OrderController::gmapsStringify( $store_address );

            $delivery_time = OrderController::return_delivery_time($store_address, $customer_address);

            if( $fastest_delivery_time == "" ){ /* initialize fastest to first store */
                $fastest_delivery_store = $store->id;
                $fastest_delivery_time = $delivery_time;
            }elseif( $delivery_time < $fastest_delivery_time){ /* set new store for delivery */
                $fastest_delivery_store = $store->id;
                $fastest_delivery_time = $delivery_time;
            }
        }
        $store = array("store_id" => $fastest_delivery_store,"delivery_time"=> $fastest_delivery_time);
        return $store;
    }


}
