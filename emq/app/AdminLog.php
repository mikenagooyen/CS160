<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_log';



    public function user(){
    	return $this->hasOne('App\User','id','user_id');
    }
}
