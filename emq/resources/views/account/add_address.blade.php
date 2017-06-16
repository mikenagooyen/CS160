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
       $('#submit-control').html("&nbsp;&nbsp;&nbsp;<input type=\"button\" class=\"btn btn-primary\"  value=\"Verifying...\">");
       $('#Add-Address').submit();
     });
   });
</script>
    <!-- End of Scripts Added to Body Section -->
@endsection

@section('content')
<ol class="breadcrumb">
  <li><a href="{{ url('/account') }}">Account Management</a></li>
  <li><a href="{{ url('/account/address') }}">Address Book</a></li>
  <li class="active">Add New Address</li>
</ol>

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Address Management</div>

                <div class="panel-body">
                    

<!-- Stuff I added -->
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (session('alert'))
        <div class="alert alert-danger">
            <ul>
            <li>{{ session('alert') }}</li>
            </ul>
        </div>
@endif
 <form method="POST" id="Add-Address" action="{{ action('AddressController@addAddress') }}">
 	{!! csrf_field() !!}
  <div class="form-group">
    <label for="FullName">Full Name:</label>
    <input type="text" class="form-control" id="FullName" name="FullName" placeholder="Enter Full Name" >

    <label for="Address">Address</label>
    <input type="text" class="form-control" id="Address" name="Address" placeholder="Enter Number and Street" >

    <label for="Address2">Address Line 2</label>
    <input type="text" class="form-control" id="Address2" name="Address2" placeholder="Apt., Unit #, etc." >

    <div class="row">
        <div class="col-xs-3">
            <label for="City">City</label>
            <input type="text" class="form-control" id="City" name="City" placeholder="Enter City" >
        </div>
        <div class="col-xs-3">
            <label for="State">State</label>
            <input type="text" class="form-control" id="State" name="State" value ="California" readonly="readonly">
        </div>
        <div class="col-xs-3">
            <label for="Zip">Zip</label>
            <input type="text" class="form-control" id="Zip" name="Zip" placeholder="Enter Zipcode" >
        </div>
    </div>
    
    <label for="Country">Country</label>
    <input type="text" class="form-control" id="Country" name="Country" value ="United States" readonly="readonly">

    <label for="Phone">Phone</label>
    <input type="tel" class="form-control" id="Phone" name="Phone" placeholder="Enter Phone Number" >
    <span id="helpBlock" class="help-block"><em>Ex: 123-456-7890</em></span>
  </div>
  
    <img id="loading" src="{{asset('/images/loading.gif')}}" hidden>
    <div id="submit-control" style="display:inline;">
        <input type="button" class="btn btn-primary submitBtn" value="Submit">
    </div>
</form>

<!-- Stuff I added -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
