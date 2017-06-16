<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
/*
Route::get('/', function () {
    return view('welcome2');
});*/


Route::auth();

Route::get('/home', 'HomeController@index');

Route::get('/shop', 'ProductsController@shopPublicIndex');

Route::get('product/{id}', 'ProductsController@getProduct');

/* API */
Route::get('api', 'APIController@main');


//testing
Route::get('/edit', function () {
	if(Auth::guest()){
		return view('welcome2');
	}else{
		return view('account.edit');
	}
});

//testing
//Route::post('/change', ['uses' => 'ProductsController@changeName']);
Route::post('/change', 'HomeController@updateAccountDetails');
Route::post('/addpayment', 'PaymentController@addPaymentMethod');
Route::post('/addaddress', 'AddressController@addAddress');


Route::get('/addtocart/{product_id}', 'CartController@addToCart');
Route::get('/removefromcart/{product_id}', 'CartController@removeFromCart');
Route::get('/deletefromcart/{product_id}', 'CartController@deleteFromCart');


Route::get('/cart', 'CartController@getCart');



//Route::get('/account/address/add', function () {
//    return view('account.add_address');
//});


Route::get('/account', 'HomeController@getAccount');

Route::get('/account/address', 'AddressController@getAddress');
Route::get('/account/address/add', 'AddressController@addAddressView');
Route::get('/account/address/delete/{id}', 'AddressController@removeAddress');



Route::get('/account/payment', 'PaymentController@getPaymentMethods');
Route::get('/account/payment/add', 'PaymentController@addPaymentView');

Route::get('/account/payment/delete/{id}', 'PaymentController@deletePaymentMethod');

/* temporary, will eventually reference to OrderController@startProcessOrderForm */
Route::get('/process/start', 'CartController@startProcessOrderForm');

Route::post('/process/complete', 'OrderController@completeOrder');
Route::get('/storelocator', 'APIController@storeLocatorView');

Route::get('/account/orders', 'OrderController@returnOrderHistory');
Route::get('/account/tracking/{id}', 'OrderController@returnOrderTracking');

Route::get('/welcome', 'ProductsController@welcomePageIndex');
Route::get('/', 'ProductsController@welcomePageIndex');

Route::post('/review/post', 'ReviewController@leaveReview');

Route::group(['middleware' => 'App\Http\Middleware\AuthMiddleware'], function()
{
    Route::get('/admin/management', 'AdminController@getAdminAccount');
    Route::get('/admin', function () {
        return redirect('/admin/management');
    });
    Route::get('/admin/users', 'AdminController@getAllUsers');
    Route::get('/admin/log', 'AdminController@getLog');
    Route::get('/admin/orders/{id}', 'AdminController@manageUserOrder');
    Route::get('/admin/stores','AdminController@getStores');
    Route::get('/admin/products','AdminController@getProducts');
    Route::get('/admin/product/{id}','AdminController@getProduct');

    Route::post('/admin/product/update', 'AdminController@updateProduct');

    Route::get('/admin/access/{id}','AdminController@userAccessView');
    Route::post('/admin/access/update', 'AdminController@updateUserAccess');

    /* API */
    Route::get('/admin/api', 'AdminController@apiController');
});


//final route evaluation, junk URL redirect
Route::get('/{junk_url}', function () {
    return redirect('/');
});
Route::get('/{junk_url}/{more_junk_url}', function () {
    return redirect('/');
});