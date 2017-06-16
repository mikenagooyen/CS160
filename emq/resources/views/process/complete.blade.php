@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Order Details</div>

                <div class="panel-body">

<!-- Stuff I added -->
                <div class="row text-center">
                <h2>Your Order Has Been Completed Successfully!</h2><br>
                </div>

                <div class="container-fluid">
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
                                <a href="../product/{{ $item->product->id }}" >{{ stripslashes($item->product->productName) }}</a>
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
                <div class="row text-right" style="margin-right: 60px">
                    <b>Subtotal:</b> ${{ $order->cost }}<br>
                    <b>Tax:</b> ${{ $order->tax }}<br>
                    <b>Shipping:</b> $0.00<br>
                    <b><big>Total: ${{ $order->total }}</big></b>
                </div>
                <!-- end of display cost totals -->
            </div><!--end of products row-->

            <div class="row text-center">
                <a href="{{ action('OrderController@returnOrderTracking', ['id' => $order->id]) }}" class="btn btn-primary">Track Order</a>&nbsp;&nbsp;&nbsp;<a href="{{ action('ProductsController@shopPublicIndex') }}" class="btn btn-default">Continue Shopping</a>
            </div>
<!-- Stuff I added -->
            </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
