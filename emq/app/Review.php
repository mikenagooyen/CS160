<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reviews';


    public function getStars(){
        $htmlString = "";
        for ($i=0; $i < 5 ; $i++) { 
           
            if($this->rating-$i >=1.00){
                $htmlString .="<i class=\"fa fa-star fa-1x reviewStar\"></i>";
            }
            elseif($this->rating-$i <= 0.00){
                $htmlString .="<i class=\"fa fa-star-o fa-1x reviewStar\"></i>";
            }
            else{
                $htmlString .="<i class=\"fa fa-star-half-o fa-1x reviewStar\"></i>";
                }
            
        }
        return $htmlString;
    }

    public function product(){
    	return $this->hasOne('App\Products', 'id', 'product_id');
    }

    public function user(){
    	return $this->hasOne('App\User','id','user_id');
    }
}
