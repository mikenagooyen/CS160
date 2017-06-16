<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;//Needed to use Auth::
use Illuminate\Support\Facades\DB;//Needed to use DB::

use App\Cart; //Import Cart to Controller
use App\OrderProducts;

class OrderProductsController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth');
    }

    public function migrateCartToOrderHistory( $order_id ){

        $cart = Cart::where('user_id', Auth::user()->id )->get();

        foreach ($cart as $item){
	    	$order_product = new OrderProducts;
	    	$order_product->order_id = $order_id;
	    	$order_product->product_id = $item->id;
	    	$order_product->price = $item->price;
	    	$order_product->quantity = $item->quantity;
	    	$order_product->save(); //save item into order history
	    	$item->delete(); //delete item from cart
        }

    }

    
}
