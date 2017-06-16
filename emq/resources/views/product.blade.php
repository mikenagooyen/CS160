@extends('layouts.app')



@section('scripts-head')
    <!-- Start of Scripts Added to Head Section -->
<style>
.modal.modal-wide .modal-dialog {
  width: 70%;
}
.modal-wide .modal-body {
  overflow-y: auto;
}

.reviewStar{
    color: orange;
}
</style>
    <!-- End of Scripts Added to Head Section -->
@endsection



@section('scripts-body')
    <!-- Start of Scripts Added to Body Section -->

    <!-- End of Scripts Added to Body Section -->
@endsection



@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (session('status'))
        <div class="alert alert-info">
            
            {{ session('status') }}
            
        </div>
@endif

            <div class="panel panel-warning">
                <div class="panel-heading"><h3>{{ stripslashes($data['product']->productName) }}</h3></div>

                <div class="panel-body">
                    

<!-- Stuff I added -->
                    <div class="row" style="padding: 20px;">

                        <div class="col-md-4" style="text-align: center;">
                            <div data-toggle="modal" data-target="#myModal"><img src="{{asset('product_images/' . $data['product']->image)}}" style="width: 100%;"></div>
                            <br>
                            @if( $data['product']->available)
                            <a href="{{ action('CartController@addToCart', ['id' => $data['product']->id]) }}" class="btn btn-primary">Add to Cart</a><br> 
                            <p>{{ $data['product']->quantity }} left in stock</p>  
                            @else
                            <br>
                            <button type="button" class="btn btn-danger">Item Not Available</button><br>
                            @endif

                            @if($data['rating']==0)
                            {!! $data['stars'] !!}
                            <a href="#reviews" data-toggle="collapse" data-target="#reviews"><p>No reviews</p></a>
                            @else
                            {!! $data['stars'] !!}
                            {{$data['rating']}}
                            <a href="#reviews" data-toggle="collapse" data-target="#reviews"><p>
                            {{ $data['count'] }} Reviews</p></a>
                            @endif
                        </div>

                        <div class="col-md-8">
                            <!-- Product ID: {{ $data['product']->id }} <br> Displayed For Debugging - Remove Later -->
                            <h3>Price: ${{ $data['product']->price }}</h3>
                            Brand: {{ stripslashes($data['product']->brand) }} |
                            Category: {{ stripslashes($data['product']->category) }} <br>
                            Description:<br> {!! stripslashes($data['product']->description) !!} <br>
                            
                            
                        </div>
                    </div>
                    <div class="row" style="padding: 20px;">
                        <div class="col-md-3"></div>
                        <div id="reviews" class="col-md-6 collapse">
                        @if( Auth::user() )
                        <center><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2">Leave a Review</button></center><br>
                        @endif
                        @if( count($data['product']->reviews))
                        <table class="table panel panel-default table-striped table-hover">
                          <thead>
                            <tr>
                              <th ><center>Reviews</center></th>
                          </tr>
                      </thead>
                      <tbody>
                        @foreach( $reviews as $review)
                        <tr><td>
                        {!! $review->getStars() !!}
                        <br><i>{{ $review->review }}</i>
                        <br>By <b>{{ $review->user->name }}</b> on @if($review->created_at){{ $review->created_at->format('F j, Y') }}@endif</b><br>
                        </td></tr>
                        @endforeach
                            </tbody>
                        </table>
                        @endif

                        </div>
                        <div class="col-md-3"></div>
                    </div>

@if(Auth::user())
<!-- Modal -->
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
     <form method="POST" action="{{ action('ReviewController@leaveReview') }}">
    {!! csrf_field() !!}
  <div class="form-group">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Your Product Review</h4>
      </div>
      <div class="modal-body">

        <center>
        <select name="rating" class="form-control">
            <option value="1" selected="selected">&#9733;&#9734;&#9734;&#9734;&#9734;</option>
            <option value="2">&#9733;&#9733;&#9734;&#9734;&#9734;</option>
            <option value="3">&#9733;&#9733;&#9733;&#9734;&#9734;</option>
            <option value="4">&#9733;&#9733;&#9733;&#9733;&#9734;</option>
            <option value="5">&#9733;&#9733;&#9733;&#9733;&#9733;</option>
        </select>
        <textarea name="review" rows="5" cols="50" class="form-control" autocomplete="off" placeholder="Write your review here..."></textarea>
        <center>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="product_id" value="{{ $data['product']->id }}">
        <button type="submit" class="btn btn-primary">Submit</button><button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</form>

  </div>
</div>
@endif


<!-- Modal -->
<div id="myModal" class="modal modal-wide fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ stripslashes($data['product']->productName) }}</h4>
      </div>
      <div class="modal-body">
        <img src="{{asset('product_images/' . $data['product']->image)}}" style="width: 100%;">
      </div>
      <div class="modal-footer">
            @if( $data['product']->available)
    <a href="{{ action('CartController@addToCart', ['id' => $data['product']->id]) }}" class="btn btn-primary">Add to Cart</a>
    @endif
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- End of Modal -->

<!-- Stuff I added -->


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
