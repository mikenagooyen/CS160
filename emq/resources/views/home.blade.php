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
          $('.recommended-slick').slick({
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
          $('.history-slick').slick({
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
          $('.similar-slick').slick({
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
                <div class="panel-heading">Home</div>

                <div class="panel-body">
<!-- Stuff I added -->
                  
                  <h3 style="width: 100%;">Recommended for You!</h3>

                  <div class="slide-wrapper" style="text-align: center">
                        <div class="recommended-slick" style="width: 90%; margin: 0px auto;">

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
                                        
                                        <img src="{{asset('/product_images/' . $current_product->image)}}" alt="..." style="max-height: 100px; max-width: 100%; margin: 5px auto;">                             
                                      </div>
                                      <div>
                                        <label>${{ $current_product->price}}</label><br>
                                        <a href="./product/{{ $current_product->id }}" class="btn btn-default">View Item</a>
                                      </div>
                                    </div>
                                </div>                            
                            </div>

                          @endfor
                          
                        </div>
                                     
                  </div>

                  <h3 style="width: 100%;">Continue where you left off!</h3>

                  <div class="slide-wrapper" style="text-align: center">
                        <div class="history-slick" style="width: 90%; margin: 0px auto;">

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
                                        <img src="{{asset('/product_images/' . $current_product->image)}}" alt="..." style="max-height: 100px; max-width: 100%; margin: 5px auto;">                              
                                      </div>
                                      <div>
                                        <label>${{ $current_product->price}}</label><br>
                                        <a href="./product/{{ $current_product->id }}" class="btn btn-default">View Item</a>
                                      </div>
                                    </div>
                                </div>                            
                            </div>

                          @endfor
                          
                        </div>
                                     
                  </div>

                  <h3 style="width: 100%;">Similar Items based on your Browsing History</h3>

                  <div class="slide-wrapper" style="text-align: center">
                        <div class="similar-slick" style="width: 90%; margin: 0px auto;">

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
                                        <img src="{{asset('/product_images/' . $current_product->image)}}" alt="..." style="max-height: 100px; max-width: 100%; margin: 5px auto;">                            
                                      </div>
                                      <div>
                                        <label>${{ $current_product->price}}</label><br>
                                        <a href="./product/{{ $current_product->id }}" class="btn btn-default">View Item</a>
                                      </div>
                                    </div>
                                </div>                            
                            </div>

                          @endfor
                          
                        </div>
                                     
                  </div>

<!-- Stuff I added -->


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
