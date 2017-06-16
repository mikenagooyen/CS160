@extends('layouts.app')
@section('scripts-head')
    <!-- Start of Scripts Added to Head Section -->
    <link rel="stylesheet" type="text/css" href="misc/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="misc/slick/slick-theme.css"/>
    <!-- End of Scripts Added to Head Section -->
@endsection
@section('scripts-body')
    <!-- Start of Scripts Added to Body Section -->
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function(){
          $('.deals-slick').slick({
            slidesToShow: 1,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 3000,
            pauseOnHover: false,
            dots: true
          });
          $('.popular-slick').slick({
            slidesToShow: 3,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 3000,
            pauseOnHover: true,
            responsive: [
            {
              breakpoint: 670,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 2
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }]
          });
          $('.top-picks-slick').slick({
            slidesToShow: 3,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 3000,
            pauseOnHover: true,
            responsive: [
            {
              breakpoint: 670,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 2
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }]
          });
        });
    </script>
    <!-- End of Scripts Added to Body Section -->
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome</div>
                <div class="panel-body">
                    <h1 style="width: 100%; text-align: center;">Take a look at these EMQ exclusive deals!</h1>
                    <div class="slide-wrapper" style="text-align: center">
                        <div class="deals-slick" style="width: 90%; margin: 0px auto;">
                          <img src="./deal_images/emq-deal-01.jpg" alt="" style="">
                          <img src="./deal_images/emq-deal-02.jpg" alt="" style="">
                          <img src="./deal_images/emq-deal-03.jpg" alt="" style="">
                          <img src="./deal_images/emq-deal-04.jpg" alt="" style="">
                        </div>                        
                    </div>

                    <h3 style="width: 100%;">Popular Items</h3>
                    <div class="slide-wrapper" style="text-align: center">
                        <div class="popular-slick" style="width: 90%; margin: 0px auto;">
                          
                              @foreach($products as $product)
                                <div class="col-md-4" style="">
                                    <div class="panel panel-warning" style="margin: 10px;">
                                        <div class="panel-heading" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                        {{ stripslashes($product->productName) }}
                                        </div>
                                        <div class="panel-body" style="text-align: center">
                                            <div style="margin: 5px auto;"></div>
                                          <div style="height: 100px;">
                                            <img src="./product_images/{{ $product->image }}" alt="..." style="max-height: 100px; max-width: 100%; margin: 5px auto;">                               
                                          </div>
                                          <div>
                                            <label>${{ $product->price}}</label><br>
                                            <a href="./product/{{ $product->id }}" class="btn btn-default">View Item</a>
                                          </div>
                                        </div>
                                    </div>                            
                                </div>
                            @endforeach
                                                    
                        </div>                        
                    </div>

                    <h3 style="width: 100%;">Our Top-Picks of the day!</h3>
                    <div class="slide-wrapper" style="text-align: center">
                        <div class="top-picks-slick" style="width: 90%; margin: 0px auto;">

                          @for($i = 0; $i < 5; $i++)

                            <?php 
                              $current_product = $products[rand(0, count($products)-1)]
                            ?>

                            <div class="col-md-4">
                                <div class="panel panel-warning" style="margin: 10px;">
                                    <div class="panel-heading" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                    {{ stripslashes($current_product->productName) }}
                                    </div>
                                    <div class="panel-body">
                                      <div style="margin: 5px auto;">
                                        <img src="./product_images/{{ $current_product->image }}" alt="..." style="max-height: 100px; max-width: 100%; margin: 5px auto;">                               
                                      </div>
                                      <div>
                                        <label>${{ $current_product->price }}</label><br>
                                        <a href="./product/{{ $current_product->id }}" class="btn btn-default">View Item</a>
                                      </div>
                                    </div>
                                </div>                            
                            </div>

                          @endfor
                          
                        </div>
                                     
                  </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection