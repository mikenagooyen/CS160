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
  <li class="active">Address Book</li>
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

                <div class="panel-heading">Address Book</div>

                <div class="panel-body">
                    <div class="container-fluid">
                    <div class="row">
                        @foreach($addresses as $address)

                                <div class="col-md-4">
                                <address>
                                <strong>Name: {{ $address->fullName }} </strong><br>
                                {{ $address->address }}, {{ $address->address2 }} <br>
                                {{ $address->city }}, {{ $address->state }} {{ $address->zip }} <br>
                                {{ $address->country }} <br>
                                Phone: {{ $address->phone }} <br>
                                </address>
                                <a href="{{ action('AddressController@removeAddress', ['id' => $address->id]) }}" class="btn btn-danger">delete</a>
                                </div>

                        @endforeach
                    </div>
                    </div>
                    <br>
                    
                    <a href="{{ action('AddressController@addAddressView') }}" class="btn btn-primary">Add New Address</a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
