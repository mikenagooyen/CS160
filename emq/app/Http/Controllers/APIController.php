<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Products; //Import Products to Controller
use App\Store; //Import Cart to Controller
use Illuminate\Support\Facades\Auth;//Needed to use Auth::
use Illuminate\Support\Facades\DB;//Needed to use DB::

class APIController extends Controller
{

    public function main(Request $request) {
        switch($request->data) {

    		/*
			*	Returns Products in JSON encoded format
			*/
            case "products":
                $products = Products::all();
                return response()->json(['products' => $products]);

			/*
			*	Returns NULL as no data path was referenced
			*/
            default:
                return "no data specified";
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
    
    public function storeLocatorView(){
        $stores = Store::all();

        $stores_infowindow_array = "";
        $stores_geocoordinates_array ="";
        foreach ($stores as $store) {
            $stores_infowindow_array .= "\"" .APIController::address2html( $store->name, $store ) ."\",\r\n";
            $stores_geocoordinates_array .= "[" .$store->lat .", ". $store->lng ."],\r\n";
        }

        return view('storelocator', ['stores_infowindow_array' => $stores_infowindow_array, 'stores_geocoordinates_array' => $stores_geocoordinates_array]);
    }
}
