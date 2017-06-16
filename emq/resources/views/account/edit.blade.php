@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
  <li><a href="{{ url('/account') }}">Account Management</a></li>
  <li class="active">Edit Account Details</li>
</ol>

<div class="container">
    <div class="row">
        <!--<div class="col-md-10 col-md-offset-1">-->
        <div class="col-md-2"></div>
        <div class="col-md-8">

                    
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
@if (session('success'))
        <div class="alert alert-info">
            <li>{{ session('success') }}</li>
        </div>
@endif
<!-- Stuff I added -->
 <form method="POST" action="{{ action('HomeController@updateAccountDetails') }}">
 	{!! csrf_field() !!}
  <div class="form-group">


<div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
        Account Details</a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse in">
      <div class="panel-body">
        <div style="margin-left: 20px;">
        <h4>Display Name: {{Auth::user()->name }}</h4>
        <h4>E-mail: {{Auth::user()->email }}</h4>
        <h4>Member Since: {{Auth::user()->created_at->format('F j, Y') }}</h4>
        </div>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
        Change Password</a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse">
      <div class="panel-body">
        <label>Enter Current Password:</label><input type="password" class="form-control" name="currentPassword" placeholder="Enter your current password here..."></br>
        <br>
        <label>Enter New Password:</label><input type="password" class="form-control" name="newPassword" placeholder="Enter your new password here..."></br>
        <label>Confirm New Password:</label><input type="password" class="form-control"  name="confirmNewPassword" placeholder="Make sure they match!"></br>
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
        Change E-mail</a>
      </h4>
    </div>
    <div id="collapse3" class="panel-collapse collapse">
      <div class="panel-body">
        <label>Enter New E-mail:</label><input type="email" class="form-control" name="email" placeholder="Enter your new e-mail here..."></br>
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">
        Change Display Name</a>
      </h4>
    </div>
    <div id="collapse4" class="panel-collapse collapse">
      <div class="panel-body">
        <label>Enter New Display Name:</label><input type="text" class="form-control" name="displayName" placeholder="Enter your new display name here..."></br>
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </div>
  </div>
</div>

  </div>
  

</form>

<!-- Stuff I added -->

    </div>
</div>
@endsection
