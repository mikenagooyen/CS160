@extends('layouts.app')


@section('scripts-head')
    <!-- Start of Scripts Added to Head Section -->

    <!-- End of Scripts Added to Head Section -->
@endsection

@section('scripts-body')
    <!-- Start of Scripts Added to Body Section -->

    <!-- End of Scripts Added to Body Section -->
@endsection


@section('content')
<ol class="breadcrumb">
  <li><a href="{{ url('/account') }}">Account Management</a></li>
  <li class="active">Payment Methods</li>
</ol>

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

                @if (session('status'))
                    <div class="alert alert-info">
                        {{ session('status') }}
                    </div>
                @endif

            <div class="panel panel-default">

                <div class="panel-heading">Payment Methods</div>

                <div class="panel-body">
                    <div class="container-fluid">
                    <div class="row">
                        @foreach($paymentMethods as $item)

                        <div class="col-md-4">
                        Cardholder Name: {{ $item->nameOnCard }} <br>
                        Creditcard Number: ****{{ $item->lastFour }} <br>
                        Exp: {{ $item->expMonth }}/{{ $item->expYear }} <br>
                        <a href="{{ action('PaymentController@deletePaymentMethod', ['id' => $item->id]) }}" class="btn btn-danger">delete</a>
                        </div>

                        @endforeach
                    </div>
                    </div>
                <br>
                <a href="{{ action('PaymentController@addPaymentView') }}" class="btn btn-primary">Add New Payment Method</a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
