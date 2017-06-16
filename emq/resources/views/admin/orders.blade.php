@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
  <li><a href="{{ url('/admin/management') }}">Admin Management</a></li>
  <li><a href="{{ url('/admin/users') }}">Users</a></li>
  <li class="active">Order History</li>
</ol>

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Order History</div>

                <div class="panel-body">
                    
<div class="container-fluid">
<!-- Stuff I added -->
@foreach($orders as $order)

    <div class="panel-default">
        <div class="panel-body">
            <div class="row"> <!-- address and payment row -->
                
                <h4>Order Date: {{ $order->created_at->format('l, F jS Y @ h:i A') }}</h4>
                <div class="col-md-4">
                <h4>Shipping From:</h4>
                <address>
                <strong>Store: {{ $order->store->name }} </strong><br>
                {{ $order->store->address }}, {{ $order->store->address2 }} <br>
                {{ $order->store->city }}, {{ $order->store->state }} {{ $order->store->zip }} <br>
                {{ $order->store->country }} <br>
                Phone: {{ $order->store->phone }} <br>
                </address>
                </div>

                <div class="col-md-4">
                <h4>Shipping To:</h4>
                <address>
                <strong>Name: {{ $order->address->fullName }} </strong><br>
                {{ $order->address->address }}, {{ $order->address->address2 }} <br>
                {{ $order->address->city }}, {{ $order->address->state }} {{ $order->address->zip }} <br>
                {{ $order->address->country }} <br>
                Phone: {{ $order->address->phone }} <br>
                </address>
                </div>

                <div class="col-md-4">
                <h4>Payment Method:</h4>
                <strong>Cardholder Name: {{ $order->payment->nameOnCard }}</strong><br>
                Creditcard Number: ****{{ $order->payment->lastFour }} <br>
                Exp: {{ $order->payment->expMonth }}/{{ $order->payment->expYear }} <br>
                </div>
            </div><!--end of address and payment row-->

            <div class="row"><!-- products row -->
                <h4>Products Ordered:</h4><br>
                <!-- start of display ordered products -->
                @foreach($order->products as $item)
                    <div class="row" style="padding: 20px;">
                            <div class="col-md-2" style="text-align: center;">                            
                                <div><img src="{{asset('product_images/' . $item->product->image)}}" style="width: 100%;"></div>
                            </div>
                            <div class="col-md-6" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                <a href="{{asset('product/' . $item->product->id)}}" >{{ stripslashes($item->product->productName) }}</a>
                            </div>
                            <div class="col-md-2" style="text-align: center;">
                                Quantity: {{ $item->quantity }}
                            </div>
                            <div class="col-md-2" style="">
                                @if($item->quantity <= 1)
                                ${{$item->product->price}}
                                @else
                                ${{($item->product->price)}} x {{ $item->quantity }}
                                @endif
                            </div>
                    </div>
                @endforeach
                <!-- end of display ordered products -->

                <!-- start of display cost totals -->
                <div class="row text-right" style="margin-right: 50px">
                    <b>Subtotal:</b> ${{ $order->cost }}<br>
                    <b>Tax:</b> ${{ $order->tax }}<br>
                    <b>Shipping:</b> $0.00<br>
                    <b><big>Total: ${{ $order->total }}</big></b>
                </div>
                <!-- end of display cost totals -->
            </div><!--end of products row-->

            <!--Track Order button-->
            <div class="row text-center">
                @if($order->delivered == false)
                <h4>Currently Out for Delivery</h4>
                @else
                <h4>Successfully Delivered On: {{ $order->delivered_at }}</h4>
                @endif
            </div>
            <!--End of Track Order button-->

        </div>
    </div>
    <hr>
@endforeach
<center>{{ $orders->links() }}</center>
@if( count($orders) == 0)
<center><h3>No Order History Available.</h3>
@endif
<!-- Stuff I added -->
</div></div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
