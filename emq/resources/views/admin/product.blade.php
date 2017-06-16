@extends('layouts.app')



@section('scripts-head')
    <!-- Start of Scripts Added to Head Section -->
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <!-- End of Scripts Added to Head Section -->
@endsection



@section('scripts-body')
    <!-- Start of Scripts Added to Body Section -->
<script>
  $(function() {
    $('#toggle-one').bootstrapToggle();
  })
</script>
    <!-- End of Scripts Added to Body Section -->
@endsection



@section('content')
<ol class="breadcrumb">
  <li><a href="{{ url('/admin/management') }}">Admin Management</a></li>
  <li><a href="{{ url('/admin/products') }}">Manage Products</a></li>
  <li class="active">Product</li>
</ol>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-danger">
                <div class="panel-heading"><h3><font color="black">{{ stripslashes($product->productName) }}</font></h3></div>

                <div class="panel-body">
                    

<!-- Stuff I added -->
@if (session('status'))
    <div class="alert alert-info">
        {{ session('status') }}
    </div>
@endif
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
 <form method="POST" action="{{ action('AdminController@updateProduct') }}">
    {!! csrf_field() !!}
  <div class="form-group">
                    <div class="row" style="padding: 20px;">

                        <div class="col-md-4" style="text-align: center;">
                            <div><img src="{{asset('product_images/' . $product->image)}}" style="width: 100%;"></div>                 
                        </div>
                        
                        <div class="col-md-8">
                            <h3>Price: <b>$</b> <input type="text"  name="price" value="{{ $product->price }}" style="width: 131px;"></h3>
                            <h3>Quantity: <input type="number" name="quantity" value="{{ $product->quantity }}" style="width: 110px;"></h3>
                            @if( $product->available == 1)
                            <h3>List Item: <input id="toggle-one" type="checkbox" name="available" checked data-toggle="toggle" ></h3>
                            @else
                            <h3>List Item: <input id="toggle-one" type="checkbox" name="available" data-toggle="toggle"></h3>
                            @endif
                            Brand: {{ stripslashes($product->brand) }} |
                            Category: {{ stripslashes($product->category) }} <br>
                            Description:<br> {!! stripslashes($product->description) !!} <br>
                            

                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-primary">Update</button>&nbsp;&nbsp;&nbsp;<a href="{{ action('AdminController@getProducts') }}" class="btn btn-default">Return To Products</a>
                        </div>

                    </div>
  </div>
</form>
<!-- Stuff I added -->


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
