<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Products; //Import Products to Controller

use Illuminate\Support\Facades\Auth;//Needed to use Auth::
use Illuminate\Support\Facades\Hash;//Needed to use Auth::

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Products::all();
        return view('home', ['products' => $products]);
    }

    public function getAccount()
    {
        return view('account.account');
    }

    public function updateAccountDetails(Request $request){
        /*  Change Password
        *   check if Change Password fields are not blank.
        */  
        if($request['currentPassword'] != "" || $request['newPassword'] != "" || $request['confirmNewPassword'] != ""){
             $this->validate($request, [
                'currentPassword' => 'required|min:6',
                'newPassword' => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!.?,$#@%]).*$/|same:confirmNewPassword',
                'confirmNewPassword' => 'required|min:6',
            ]);



            //Check if current password matches account password
            // Hash::check( new_plain_text_password , current_hashed_password )
            if( !Hash::check($request['currentPassword'] ,Auth::user()->password) ){
                $status = "Your current password did not match your account password.";
                return redirect('edit')->with('alert', $status);
            }
            $user = Auth::user();
            $user->password = Hash::make($request['newPassword']);
            $user->save();
            $status = "Your Password has been updated.";
            return redirect('edit')->with('success', $status);
        }
        /*  Change E-mail
        *   Check if Change E-mail field is not blank.
        */
        if( $request['email'] != ""){
             $this->validate($request, [
                'email' => 'required|email|max:255|unique:users',
            ]);
            $user = Auth::user();
            $user->email = $request['email'];
            $user->save();
            $status = "Your E-mail has been updated.";
            return redirect('edit')->with('success', $status);
        }
        /*  Change Name
        *   Check if Change Name field is not blank.
        */
        if( $request['displayName'] != ""){
            $request['displayName'] = trim($request['displayName']);
             $this->validate($request, [
                'displayName' => "required|regex:/^([a-zA-Z]+[.']{0,1}\s{0,1})*$/|max:35",
            ]);
            $user = Auth::user();
            $user->name = $request['displayName'];
            $user->save();
            $status = "Your Display Name has been updated.";
            return redirect('edit')->with('success', $status);
        }
        $status = "No Input Detected.";
        return redirect('edit')->with('alert', $status);
    }
}
