<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /*
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';


    public function products(){
    	//Relation (Foreign Object, Foreign ID, Local ID)
    	return $this->hasMany('App\OrderProducts','order_id','id');
    }

    public function payment(){

    	return $this->hasOne('App\OrderPayment','id','orderpayment_id');
    }

    public function address(){
    	return $this->hasOne('App\OrderAddress','id','orderaddress_id');
    }

    public function store(){
    	return $this->hasOne('App\Store','id','store_id');
    }


}
