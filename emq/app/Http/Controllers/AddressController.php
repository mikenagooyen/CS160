<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    /**
    * Authenticate User IF not Authenticated
    */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
    * Return the add Address view
    * @return view the add address view
    */
    public function addAddressView(){
        return view('account.add_address');
    }

    /**
    * gets addresses for user
    * @return view returns the address view or add address view
    */
    public function getAddress(){
    	$addresses = Address::where('user_id', Auth::user()->id )->get();

        if(count($addresses) == 0){
            return view('account.add_address');
        }else{
    	   return view('account.address', ['addresses' => $addresses]);
        }
    }

    /**
    * Adds the address from the form
    * @param $request the form request
    * @return view action returns getAddress with statua 
    */
    public function addAddress(Request $request){

        $phone_characters = array("(",")","-"," ");
        if($request['Phone']){
            $request['Phone'] = str_replace($phone_characters, "", $request['Phone']);
        }


         $this->validate($request, [
            'FullName' => 'required|max:255',
            'Address' => 'required|max:255',
            'Address2' => 'max:255',
            'City' => 'required|max:255',
            'State' => 'required|max:255',
            'Country' => 'required|max:255',
            'Zip' => 'required|digits:5',
            'Phone' => 'required|digits:10',
        ]);
        $request['Phone'] = substr_replace( $request['Phone'], '-', -4, 0);
        $request['Phone'] = substr_replace( $request['Phone'], ') ', 3, 0);
        $request['Phone'] = "(".$request['Phone'];

    	$FullName = $request['FullName'];
    	$Address = $request['Address'];
    	$Address2 = $request['Address2'];
    	$City = $request['City'];
    	$State = "CA"; //Manually Set to prevent post parameters injection
    	$Country = "United States"; //Manually Set to prevent post parameters injection
    	$Zip = $request['Zip'];
    	$Phone = $request['Phone'];
    	
        //convert address to gmaps friendly address
        $fullAddress = $Address.",+".$City.",+".$State."+".$Zip;
        $fullAddress=str_replace(" ","+",$fullAddress);
        //using google maps geocode service to validate address
        $jsonp = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".$fullAddress."&key=AIzaSyCz2mUMKWxhHnmrCZoVYiWjwPwu3PUCPYs")); 
        
        $status = $jsonp->status;
        if($status == "ZERO_RESULTS"){
            $alert = "Invalid Address, Please enter full address correctly.";
            return redirect()->action("AddressController@addAddressView")->with('alert',$alert);
        }
        $status = $jsonp->results[0]->geometry->location_type;
        //checking to see that address exists explicitly
        if($status != "ROOFTOP"){
            $alert = "Invalid Address, Please enter full address correctly.";
            return redirect()->action("AddressController@addAddressView")->with('alert',$alert);
        }
        // google maps returns valid if address has minor incorrect permutations (i.e. mispelled words or wrong zip code).
        // variables are reassigned with google maps corrected values.
        foreach ($jsonp->results[0]->address_components as $address_element) {
            switch($address_element->types[0]){
                case "premise": //Don't accept location guess made by google maps.
                    $alert = "Invalid Address, Please enter full address correctly.";
                    return redirect()->action("AddressController@addAddressView")->with('alert',$alert);
                    break;
                case "street_number":
                    $Address = $address_element->long_name;
                    break;
                case "route":
                    $Address .= " ".$address_element->short_name;
                    break;
                case "locality":
                    $City = $address_element->long_name;
                    break;
                case "postal_code":
                    $Zip = $address_element->long_name;
                    break;
            }
        }
        

        $address = new Address;
    	$address->user_id = Auth::user()->id;
    	$address->fullName = $FullName;
    	$address->address = $Address;
    	$address->address2 = $Address2;
    	$address->city = $City;
    	$address->state = $State;
    	$address->country = $Country;
    	$address->zip = $Zip;
    	$address->phone = $Phone;
    	$address->save();
        
    	$status = "Successfully added Address";
    	return redirect()->action("AddressController@getAddress")->with('status',$status);

    }

    /**
    * Removes the address
    * @param $id the address id
    * @return action getAddress with status
    */
    public function removeAddress($id){
    	$address = Address::find($id);
    	if($address){
            if( $address->user_id == Auth::user()->id){
        		$address->delete();
        		$status = "Successfully removed Address";
        		return redirect()->action("AddressController@getAddress")->with('status',$status);
        	}else{
                $status = "Error: Illegal Input Detected.";
                return redirect()->action("AddressController@getAddress")->with('status',$status);
            }
        }
    	else{
    		$status = "Error: Illegal Input Detected.";
    		return redirec()->action("AddressController@getAddress")->with('status',$status);
    	}
    }

}
