@extends('layouts.app')


@section('scripts-head')
    <!-- Start of Scripts Added to Head Section -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <!-- End of Scripts Added to Head Section -->
@endsection

@section('scripts-body')
    <!-- Start of Scripts Added to Body Section -->
<script>
   $(function(){
     $(".submitBtn").click(function () {
       $('#loading').show();
       $('#submit-control').html("&nbsp;&nbsp;&nbsp;<input type=\"button\" class=\"btn btn-primary\"  value=\"Processing Order...\">");
       $('#Process-Order').submit();
     });
   });
</script>
    <!-- End of Scripts Added to Body Section -->
@endsection


@section('content')


<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

                @if (session('status'))
                    <div class="alert alert-info">
                        {{ session('status') }}
                    </div>
                @endif
                
            <!-- START OF REQUESTED DETAILS -->
            @if( count($paymentMethods) == 0 || count($addresses) == 0)
            <div class="alert alert-danger">
                Please add the requested details to process your order.
            </div>
            <div class="panel panel-default">

                <div class="panel-heading">Account Management</div>
                <div class="panel-body">
                   <div class="row" style="width: 100%; text-align: center;">
                        @if( count($addresses) == 0 )
                        <div class="col-md-6">
                            <b>Please Enter a Shipping Address</b>
                            <div class="panel panel-warning" style="margin: 10px;">
                                <div class="panel-heading">Address Book</div>
                                <div class="panel-body">
                                    <div><img src="http://image.flaticon.com/icons/svg/232/232508.svg" alt="AddressBook" style="width:180px; height:180px; margin: 5px;"></div>
                                    <div><a href="{{ action('AddressController@getAddress') }}" class="btn btn-default">Manage Addresses</a></div>                                    
                                </div>
                            </div>                            
                        </div>
                        @endif
                        @if( count($paymentMethods) == 0 )
                        <div class="col-md-6">
                            <b>Please Enter a Payment Method</b>
                            <div class="panel panel-warning" style="margin: 10px;">
                                <div class="panel-heading">Payment Methods</div>
                                <div class="panel-body">
                                    <div><img src="http://image.flaticon.com/icons/svg/235/235805.svg" alt="Payment" style="width:180px; height:180px; margin: 5px;"></div>
                                    <div><a href="{{ action('PaymentController@getPaymentMethods') }}" class="btn btn-default">Manage Payment Methods</a></div>                
                                </div>
                            </div>                            
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- END OF REQUESTED DETAILS -->
            @else
            <!-- START OF PAYMENT PROCESS -->
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
 <form method="POST" id="Process-Order" action="{{ action('OrderController@completeOrder') }}">
    {!! csrf_field() !!}
  <div class="form-group">          

 <div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
        Select Shipping Address</a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse in">
      <div class="panel-body">
                    <div class="row">
                        @foreach($addresses as $address)
                            <div class="col-md-4">
                            <div class="radio">
                              <label><input type="radio" name="address" value="{{$address->id}}">Select Address</label>
                            </div>
                            <address>
                            <strong>Name: {{ $address->fullName }} </strong><br>
                            {{ $address->address }}, {{ $address->address2 }} <br>
                            {{ $address->city }}, {{ $address->state }} {{ $address->zip }} <br>
                            {{ $address->country }} <br>
                            Phone: {{ $address->phone }} <br>
                            </address>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="btn btn-primary">Use this shipping address</a>
                        </div>
                    </div>
      </div>

    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-parent="#accordion" href="#collapse2">
        Select Payment Method</a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse">
      <div class="panel-body">
                    <div class="row">
                        @foreach($paymentMethods as $item)
                        <div class="col-md-4">
                        <div class="radio">
                          <label><input type="radio" name="payment" value="{{$item->id}}">Select Payment Method</label>
                        </div>
                        <strong>Cardholder Name: {{ $item->nameOnCard }}</strong><br>
                        Creditcard Number: ****{{ $item->lastFour }} <br>
                        Exp: {{ $item->expMonth }}/{{ $item->expYear }} <br>
                        </div>
                        @endforeach
                    </div>
                    <div class="row"></br>
                        <div class="col-md-4">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3" class="btn btn-primary">Use this payment method</a>
                        </div>
                    </div>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-parent="#accordion" href="#collapse3">
        Complete Order</a>
      </h4>
    </div>
    <div id="collapse3" class="panel-collapse collapse">
      <div class="panel-body">
        <strong>Order Summary</strong><br>
        {{ $total_quantity }}: ${{ $total_price }}<br>
        Shipping & handling: $0.00<br>
        Total before tax: ${{ $total_price }}<br>
        Estimated tax to be collected: ${{ $estimated_tax }}<br>
        <br>
        Estimated Total: ${{ $estimated_total }}<br>

        <br>
        <img id="loading" src="{{asset('/images/loading.gif')}}" hidden>
        <div id="submit-control" style="display:inline;">
            <input type="button" class="btn btn-primary submitBtn" value="Complete Order">
        </div>
        </div>
    </div>
  </div>
</div> 

</div>
</form>

            @endif
            <!-- END OF PAYMENT PROCESS -->
        </div>
    </div>
</div>
@endsection
