<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Review;

use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    //

	public function getReviews($product_id){
		return view('/');
	}
	public static function reviewCount($product_id){
		return count(Review::where('product_id',$product_id)->get());
	}

    public static function calculateAverage($product_id){
    	$reviews = Review::where('product_id',$product_id)->get();
    	if(count($reviews) == 0){
    		return 0;
    	}
    	else{
    		$total = 0.0;
    		foreach ($reviews as $review) {
    			$total += $review->rating;
    		}

    		return round( ($total/count($reviews)) , 1);
    	}
    }


    public function leaveReview(Request $request){
         $this->validate($request, [
            'product_id' => 'required|integer|exists:products,id',
            'review' => 'required|max:255',
            'rating' => 'required|integer|between:1,5',
        ]);
        $old_review = Review::where('product_id',$request['product_id'])->where('user_id',Auth::user()->id)->first();
        if($old_review){
            $old_review->delete();
        }
        $review = new Review;
        $review->rating = $request['rating'];
        $review->product_id = $request['product_id'];
        $review->user_id = Auth::user()->id;
        $review->review = $request['review'];
        $review->save();
        $status = "Your Review Has Been Submitted.";
        return redirect()->action('ProductsController@getProduct', ['id' => $request['product_id'] ])->with('status', $status);
    }
    /**
    *   Gets Top 5 reviews based on how helpful they were
    *
    **/
    public static function getPopularReviews($product_id){
        $reviews = Review::where('product_id', $product_id)
        ->orderBy('helpful','desc')
        ->take(5)
        ->get();
        return $reviews;
    }

    /**
    *   Algorithm that relates stars to numerical values
    * @param the average numerical rating (float)
    * @return the html code for stars
    */
    public static function createStars($rating){
        $htmlString = "";
        for ($i=0; $i < 5 ; $i++) { 
           
            if($rating-$i >=1.00){
                $htmlString .="<i class=\"fa fa-star fa-1x reviewStar\"></i>";
            }
            elseif($rating-$i <= 0.00){
                $htmlString .="<i class=\"fa fa-star-o fa-1x reviewStar\"></i>";
            }
            else{
                $htmlString .="<i class=\"fa fa-star-half-o fa-1x reviewStar\"></i>";
                }
            
        }
        return $htmlString;
    }
}
