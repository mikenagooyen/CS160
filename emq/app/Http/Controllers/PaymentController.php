<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Payment; //Import Payment to Controller
use Illuminate\Support\Facades\Auth;//Needed to use Auth::
use Illuminate\Support\Facades\DB;//Needed to use DB::

use Carbon\Carbon; //Cleaner Version of Datetime.

class PaymentController extends Controller
{
    /* Authenticate User IF not Authenticated */
    public function __construct(){
        $this->middleware('auth');
    }
    
    /**
    * returns the add payment view
    * @return view the add_payment view
    */

	public function addPaymentView(){
		return view('account.add_payment');
	}
	
    /**
    * get All of the payment methods for user
    * @return view returns page either showing payment methods or page for adding payment
    */
	public function getPaymentMethods()
    {
        $paymentMethods = Payment::where('user_id', Auth::user()->id )->get();
        if(count($paymentMethods) == 0){
            return view('account.add_payment');
        }else{
            return view('account.payment', ['paymentMethods' => $paymentMethods]);
        }
    }

    /**
    * adds payment to db
    * @param $request the form request
    * @return action the paymentmethod page with a status
    */
    public function addPaymentMethod(Request $request){

        $this->validate($request, [
            'fullNameOnCard' => 'required|max:255',
            'cardNumber' => 'required|digits:16',
            'expirationMonth' => 'required|digits_between:1,12',
            'expirationYear' => 'required|integer|between:2016,2025',
            'cardSecurityCode' =>  'required|integer|between:1,9999',
            //'body' => 'required',
        ]);

        $current = Carbon::now();

        // Check if payment method is expired.
        if($request['expirationMonth'] < $current->month && $request['expirationYear'] <= $current->year ){
            $status = "Payment Method is Expired.";
            return redirect()->action('PaymentController@addPaymentView')->with('alert', $status);  
        }

        //if(luhnCheck($request['cardNumber'])){
            /* Convert Card Number to Hash to Check Uniqueness on database with Validate */
            if($request['cardNumber']){
                $lastFour = substr( $request['cardNumber'] , -4);
                $request['cardNumber'] = sha1($request['cardNumber']);
            }

            $cc = Payment::where('cardNumber', $request['cardNumber'] )->first();
            //Check that the user is not entering the same card twice.
            
            if( $cc ){
                if( $cc->user_id == Auth::user()->id){
                    $status = "Duplicate Payment Method Detected.";
                    return redirect()->action('PaymentController@addPaymentView')->with('alert', $status);  
                }
            }

        	$nameOnCard = $request['fullNameOnCard'];
    		$cardNumber = $request['cardNumber'];
    		//$lastFour = substr($cardNumber, -4);
    		$cardHash = $cardNumber;//sha1($cardNumber);
    		$expMonth = $request['expirationMonth'];
    		$expYear = $request['expirationYear'];

            $paymentMethod = new Payment;
            $paymentMethod->user_id = Auth::user()->id;
            $paymentMethod->nameOnCard = $nameOnCard;
            $paymentMethod->cardNumber = $cardHash;
            $paymentMethod->lastFour = $lastFour;
            $paymentMethod->expMonth = $expMonth;
            $paymentMethod->expYear = $expYear;
            $paymentMethod->save();//ALWAYS SAVE CHANGES
    	
    		$status = "Successfully Added New Payment Method.";
            return redirect()->action('PaymentController@getPaymentMethods')->with('status', $status);
        /*}
        else{
            $status = "Invalid Payment Method.";
            return redirect()->action('PaymentController@addPaymentView')->with('alert', $status);
        }*/

    }

    /**
    * Deletes the payment at a given id
    * @param $id the id of the payment
    */
    public function deletePaymentMethod($id){
    	$paymentMethod = Payment::find( $id );

    	if( $paymentMethod ){
            if( $paymentMethod->user_id == Auth::user()->id){
        		$paymentMethod->delete();
        		$status = "Successfully Removed Payment Method.";
                return redirect()->action('PaymentController@getPaymentMethods')->with('status', $status);
            }else{
                $status = "Error: Illegal Input Detected.";
                return redirect()->action('PaymentController@getPaymentMethods')->with('status', $status);
            }
    	}else{
    		$status = "Error: Illegal Input Detected.";
            return redirect()->action('PaymentController@getPaymentMethods')->with('status', $status);
    	}
    }

    /**
    * Checks for valid CC Number
    * @param $number the CC number input
    */
    private function luhnCheck($number){
        $number = preg_replace('/\D/', '', $ccNumber);

         // Set str length and parity
        $number_length = strlen($number);
        $parity = $number_length % 2;

        //Luhn algorithm
        $total = 0;
        for ($i = 0; $i < $number_length; $i++){
            $digit = $number[$i];
            // Multiply alternate digits by two
            if ($i % 2 == $parity) {
                $digit *= 2;
                // If the sum is two digits, add them together (in effect)
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            // Total up the digits
            $total += $digit;
        }  
        return $total % 10 == 0;
    }
}
