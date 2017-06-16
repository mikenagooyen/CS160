<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Products; //Import Products to Controller
use App\Cart; //Import Cart to Controller

use App\Payment;
use App\Address;

use App\Store;

use Illuminate\Support\Facades\Auth;//Needed to use Auth::
use Illuminate\Support\Facades\DB;//Needed to use DB::

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
    * Gets the cart of user
    * @return view returns the cart view
    */
    public function getCart()
    {
        $cart = Cart::where('user_id', Auth::user()->id )->get();
        $total = CartController::cartTotal();
        return view('cart2', ['cart' => $cart, 'total_price' => $total['price'], 'total_quantity' => $total['quantity'] ]);
    }


    /**
    * Checks if cart entry with product exists in users cart.
    * If it exists, increment Cart Quantity
    * Products->isAvailable() returns bool and Decrements Product inventory quantity 
    * by 1.
    * Else make a new cart entry.
    * @param $product_id the id of the product
    * @return action the getCart method with status
    */
    public function addToCart($product_id)
    {
        
        $product = Products::find($product_id);
        $user_id = Auth::user()->id;

        // IF product exists
        if($product){

            // IF product is Available, quantity > 0
            if( $product->isAvailable()){

                // Retrieve User Cart Entry with Product
                $item = Cart::where('user_id', $user_id )->where('product_id', $product_id )->first();

                if($item){
                    // Cart entry with product exists, incrementing quantity
                    if($item->quantity < $product->quantity){
                        $item->increment('quantity');
                        $item->save();
                    }else{
                        $status = "Sorry, This Item is temporarily Out of Stock or Unavailable.";
                        return redirect()->action('CartController@getCart')->with('status', $status);
                    }
                    
                }else{
                    // cart entry doesn't exist. Creating entry
                    $cart = new Cart;
                    $cart->user_id = $user_id;
                    $cart->product_id = $product_id;
                    $cart->quantity = 1;
                    $cart->save();//ALWAYS SAVE CHANGES
                }
                $status = "Successfully Added to Cart.";
                return redirect()->action('CartController@getCart')->with('status', $status);
                //return "Successfully Added to Cart.";
            }
            //return "Sorry, This Item is Temporarily Unavailable.";
            $status = "Sorry, This Item is temporarily Out of Stock or Unavailable.";
            return redirect()->action('CartController@getCart')->with('status', $status);
        }
        //return "Error, This Product Doesn't Exist.";
        $status = "Error: Product Does Not Exist.";
        return redirect()->action('CartController@getCart')->with('status', $status);
    }

    /**
    *   Checks if cart entry with product exists in users cart.
    *   If it exists, decrement cart quantity, increment product inventory quantity.
    *   If Cart quantity is zero, delete table row
    *   @param $product_id the id of the product to be removed
    *   @return action the getCart method with status
    */
    public function removeFromCart($product_id){

        $user_id = Auth::user()->id;
        $cartExist = DB::table('cart')->where('user_id', $user_id )->where('product_id', $product_id )->first();

        if($cartExist){
            $cart = Cart::find( $cartExist->id );

            //If Quantity > 1, decrement
            //Else Delete Cart Table Row
            if( $cart->quantity > 1){
                $cart->decrement('quantity');
            }else{
                $cart->delete();
            }

            //Restore Products quantity +1
            //$product = Products::find($product_id);
            //$product->increment('quantity');
            //$product->save();
            //return "Item Successfully Removed from Cart.";
            $status = "Successfully Removed from Cart.";
            return redirect()->action('CartController@getCart')->with('status', $status);
        }
        //return "Item Not In Cart.";
        $status = "Item Not In Cart.";
        return redirect()->action('CartController@getCart')->with('status', $status);
    }
    public function deleteFromCart($product_id){

        $user_id = Auth::user()->id;
        $cartExist = DB::table('cart')->where('user_id', $user_id )->where('product_id', $product_id )->first();

        if($cartExist){
            $cart = Cart::find( $cartExist->id );

            //Restore Products quantity
            /*
            $product = Products::find($product_id);
            if( $cart->quantity > 1){
                for($i = 1; $i <= $cart->quantity; $i++){
                    $product->increment('quantity');
                }
            }else{
                $product->increment('quantity');
            }*/
            //Delete Cart Table Row
            $cart->delete();
            
            //$product->save();
            //return "Item Successfully Removed from Cart.";
            $status = "Successfully Removed from Cart.";
            return redirect()->action('CartController@getCart')->with('status', $status);
        }
        //return "Item Not In Cart.";
        $status = "Item Not In Cart.";
        return redirect()->action('CartController@getCart')->with('status', $status);
    }

    /* temporarily placed here, will eventually be moved to OrderController */
    public function startProcessOrderForm(){
        $addresses = Address::where('user_id', Auth::user()->id )->get();
        $paymentMethods = Payment::where('user_id', Auth::user()->id )->get();

        $total = CartController::cartTotal();
        if($total['price'] == 0){
            $status = "Your Cart is Empty.";
            return redirect()->action('CartController@getCart')->with('status', $status);
        }else{
            return view('process.start', ['paymentMethods' => $paymentMethods, 
                'addresses' => $addresses, 
                'total_price' => $total['price'], 
                'total_quantity' => $total['quantity'],
                'estimated_tax' => $total['estimated_tax'],
                'estimated_total' => $total['estimated_total'],
                ]);
        }
    }

    public function completeOrder(Request $request){
        $this->validate($request, [
            'address' => 'required|integer|exists:address,id',//Can be hacked, need to validate address belongs to user
            'payment' => 'required|integer|exists:payment,id',//Can be hacked, need to validate payment method belongs to user
            'total' => 'required',
        ]);


        $data = json_encode($request->all());
        return view('process.complete', ['OrderData' => $data]);
    }

    public function cartTotal(){
        $cart = Cart::where('user_id', Auth::user()->id )->get();

        $stores = Store::all();
        $tax_estimate = 0;
        foreach ($stores as $store) {
            $tax_estimate += $store->salesTax;
        }
        $tax_estimate = $tax_estimate / count($stores);

        $total_price = 0;
        $total_items = 0;
        foreach ($cart as $item){
            $total_items += $item->quantity;
            $total_price += $item->product->price * $item->quantity;
        }
        if($total_items > 1){
            $total_items = $total_items." items";
        }else{
            $total_items = $total_items." item";
        }

        $estimated_tax = ( $total_price * ($tax_estimate / 100));
        $estimated_tax = number_format($estimated_tax, 2, '.', '');
        $estimated_total = $total_price + $estimated_tax;

        $total = array("price" => $total_price,"quantity"=> $total_items,"estimated_tax" => $estimated_tax,"estimated_total" => $estimated_total);
        return $total;
    }
}
