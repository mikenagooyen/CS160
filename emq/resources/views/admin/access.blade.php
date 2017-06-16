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
  <li><a href="{{ url('/admin/management') }}">Admin Management</a></li>
  <li><a href="{{ url('/admin/users') }}">Manage Users</a></li>
  <li class="active">User</li>
</ol>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Update User Access</div>

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
@if (session('alert'))
        <div class="alert alert-danger">
            <ul>
            <li>{{ session('alert') }}</li>
            </ul>
        </div>
@endif
 <form method="POST" action="{{ action('AdminController@updateUserAccess') }}">
    {!! csrf_field() !!}
  <div class="form-group">
                    <div class="row" style="padding: 20px;">
                        <div class="col-md-6">
                        <div class="panel panel-info">
                <div class="panel-heading">User Details</div>

                <div class="panel-body">
                        <div >
                            <center>
                            <label>User ID:&nbsp;</label><a href="" class="btn btn-default">{{ $user->id }}</a><br><br>
                            <label>Display Name:&nbsp;</label><a href="" class="btn btn-default">{{ $user->name }}</a><br><br>
                            <label>E-mail:&nbsp;</label><a href="" class="btn btn-default">{{ $user->email }}</a><br><br>
                            <label>Current Access Level:&nbsp;</label><a href="" class="btn btn-default">{{ $user->access() }}</a><br>
                            </center>
                        </div>
                    </div></div>
                        </div>
                        <div class="col-md-6">
                            <br><br>
                            <label >Select Access Level</label>

                              <select name="access_level" class="form-control">
                                <option value="0" selected="selected">Demote All Status - Access Level 0</option>
                                <option value="1">Customer Service - Access Level 1</option>
                                <option value="2">Products Manager - Access Level 2</option>
                                <option value="3">Admin Manager - Access Level 3</option>
                              </select>
                            <br>
                            <label>Confirm Users E-mail</label>
                            <input type="text" name="email" class="form-control" autocomplete="off" placeholder="Enter users e-mail..." >
                            <br><br>
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-primary">Update</button>&nbsp;&nbsp;&nbsp;<a href="{{ action('AdminController@getAllUsers') }}" class="btn btn-default">Return To Users</a>
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
