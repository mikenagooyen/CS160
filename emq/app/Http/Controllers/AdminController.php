<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;//Needed to use Auth::
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Order;
use App\Store;
use App\Products;
use App\Admin;
use App\AdminLog;

use Carbon\Carbon;

class AdminController extends Controller
{
    
    //
	public function __construct()
    {
        $this->middleware('auth');
    }

    /**
    *   Returns the admin management view as long as user is admin
    *   @return view either redirect home or return admin management
    */
    public function getAdminAccount()
    {
        // if(Auth::check()){
        //     echo "User is authed";
        //     if(Auth::user()->id == 1){
        //         return view('admin.management');
        //     }
        //     else{
        //         redirect('/home');
        //     }
        // }
        // else{
        //     echo "User is not authed";
        //     redirect('/home');
        // }
    	return view('admin.management');
    	
    	
    }

    /** 
    *   Returns a view that displays all of the users in the application
    *   @return view user view
    */
    public function getAllUsers()
    {
        $users = User::all();
        return view('admin.users', ['users' => $users]);
    }

    public function apiController(Request $request) {
        switch($request->data) {

            /*
            *   Returns Data in JSON encoded format
            */
            case "users":
                $users = User::get();
                if(Auth::user()->access() == 3){
                    foreach ($users as $user) {
                        $user['access'] = $user->access();
                    }
                }
                return response()->json(['users' => $users]);
                
            case "log":
                if(Auth::user()->access() == 3){
                    $admin_log = AdminLog::orderBy('id', 'DESC')->get();
                    
                    /*foreach ($admin_log as $entry) {
                        $admin_log['admin_email'] = $entry->user->email;
                    }*/
                    return response()->json(['log' => $admin_log]);
                }   
                return "access denied";

            case "products":
                if(Auth::user()->access() >= 2){
                    $products = Products::get(['id','productName','quantity','brand','image','price','available','category']);
                    return response()->json(['products' => $products]);
                }
                return "access denied";
            /*
            *   Returns NULL as no data path was referenced
            */
            default:
                return "no data specified";
        }
    }

    public function userAccessView($id){
        if( Auth::user()->access() == 3 ){
            $user = User::find($id);
            if($user){
                if(Auth::user()->access() == 3){
                    return view('admin.access', ['user' => $user]);
                }
            }
        }
        return redirect('/');
    }

    public function updateUserAccess(Request $request){
        if( Auth::user()->access() == 3 ){
             $this->validate($request, [
                'user_id' => 'required|integer|exists:users,id',
                'email' => 'required|max:255',
                'access_level' => 'required|integer|between:0,3',
            ]);
            $user = User::find($request['user_id']);
            if($user){
                if($user->email == $request['email']){

                    if($request['user_id'] == 1){
                        $alert = "Request Denied.";
                        return redirect()->action('AdminController@userAccessView', ['user' => $user])->with('alert', $alert);
                    }

                    if($user->access() > 0){//Admin object already exists
                        if( $request['access_level'] > 0 ){
                            $admin = Admin::where('user_id', $user->id )->first();
                            $admin->user_id = $request['user_id'];
                            $admin->role = $request['access_level'];
                            $admin->save();
                        }elseif($request['access_level'] == 0){
                            $admin = Admin::where('user_id', $user->id )->first();
                            $admin->delete();
                        }
                    }else{//Admin object does not exist
                        if( $request['access_level'] > 0 ){
                            $admin = new Admin;
                            $admin->user_id = $request['user_id'];
                            $admin->role = $request['access_level'];
                            $admin->save();
                        }
                    }

                    $log = new AdminLog;
                    $log->user_id = Auth::user()->id;
                    $log->message = "[User Update]: user_id: ".$user->id. ", e-mail: ".$user->email.", access_level: ".$request['access_level'];
                    $log->save();

                    $status = "User (".$user->name.") has been successfully updated.";
                    return redirect()->action('AdminController@userAccessView', ['user' => $user])->with('status', $status);
                }
                    $alert = "Emails did not match.";
                    return redirect()->action('AdminController@userAccessView', ['user' => $user])->with('alert', $alert);
            }

        }
        return redirect('/');
    }

    public function getUser($id)
    {
        $user = User::find($id);
        //return view('admin.admin');
    }

    /*
    *
    */
    public function searchUser($request)
    {
        $searchTerm = $request['searchTerm'];

        $user = Auth::user();
        if($request['searchBy'] == 0){
            $users = User::where('name',$user->name)->get();
        }
        //By email
        elseif ($request['searchBy'] == 1) {
            
        }
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


    /**
    *   Returns the manage user order view
    *   @param $id The id of the user
    *   @return return the view
    */
    public function manageUserOrder($id)
    {
        //First check ALL orders to see if they arrived.
        $orders = Order::where('user_id', $id)->orderBy('id', 'DESC')->get();
        $now = Carbon::now();
        foreach ($orders as $order) {
            AdminController::updateOrderIfDelivered( $now, $order );
        }
        //safe to paginate now.
        $orders = Order::where('user_id', $id )->orderBy('id', 'DESC')->paginate(4);

        return view('admin.orders', ['orders' => $orders]);
    }

    public function changeUserEmail($id)
    {
        
    }

    /**
    *   Returns the view with all of the stores listed
    *   @return view store view
    */
    public function getStores()
    {
        $stores = Store::all();
        return view('admin.stores', ['stores' => $stores]);
    }

    public function getProducts()
    {
        if(Auth::user()->access() >= 2){
            return view('admin.products');
        }
        return redirect('/');
    }

    public function getLog()
    {
        if(Auth::user()->access() >= 3){
            return view('admin.log');
        }
        return redirect('/');
    }

    public function getProduct($id)
    {   
        if(Auth::user()->access() >= 2){
            $product = Products::find($id);
            return view('admin.product', ['product' => $product]);
        }
        return redirect('/');
    }


    public function updateProduct(Request $request){
        // Still need to implement proper validation
         $this->validate($request, [
            'product_id' => 'required|integer|exists:products,id',
            'price' => 'required|numeric|min:1.00',
            'quantity' => 'required|integer|min:0',
        ]);

        //Still need to check product id exists
        //Price is properly formatted
        //Quantity is a positive integer

        //note: available toggle does not show in request if NOT selected
        if($request['available']){
            $request['available'] = "1"; //true
        }else{
            $request['available'] = "0"; //false
        } 

        $log_message = "";
        //Update Here
        $product = Products::find( $request['product_id'] );
        $new_price_formatted = number_format($request['price'], 2, '.', '');

        if($product->price != $new_price_formatted){
            $log_message .= "price_update: ".$new_price_formatted;
        }
        $product->price = $new_price_formatted;

        
        if($product->quantity != $request['quantity']){
            if($log_message){ $log_message.=", ";}
            $log_message .= "quantity_update: ".$request['quantity'];
        }
        $product->quantity = $request['quantity'];

        if($product->available != $request['available']){
            if($log_message){ $log_message.=", ";}
            $log_message .= "listed_update: ".$request['available'];
        }
        $product->available = $request['available'];
        $product->save();



        $log = new AdminLog;
        $log->user_id = Auth::user()->id;
        $log->message = "[Product Update]: product_id: ".$product->id. ", ".$log_message;
        $log->save();

         //Return to product view.

        $status = "Product has been successfully updated.";
        return redirect()->action('AdminController@getProduct', ['id' => $request['product_id']])->with('status', $status);
     }

}
