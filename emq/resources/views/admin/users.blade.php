@extends('layouts.app')

@section('scripts-head')
    <!-- Start of Scripts Added to Head Section -->
    <html lang="en" ng-app="angularTable">
    <!-- End of Scripts Added to Head Section -->
@endsection



@section('scripts-body')
    <!-- Start of Scripts Added to Body Section -->
     <script src="{{asset('/lib/angular/angular.js')}}"></script>
    <script src="{{asset('/lib/dirPagination.js')}}"></script>

    <script>
var app = angular.module('angularTable', ['angularUtils.directives.dirPagination']);

app.controller('listdata',function($scope, $http){
  $scope.products = []; //declare an empty array
  $http.get("{{asset('/admin/api?data=users')}}").success(function(response){ 
    $scope.users = response.users;  //ajax request to fetch data into $scope.data
  });
  
  $scope.sort = function(keyname){
    $scope.sortKey = keyname;   //set the sortKey to the param passed
    $scope.reverse = !$scope.reverse; //if true make it false and vice versa
  }
});
    </script>
    <!-- End of Scripts Added to Body Section -->
@endsection

@section('content')
<ol class="breadcrumb">
  <li><a href="{{ url('/admin/management') }}">Admin Management</a></li>
  <li class="active">Manage Users</li>
</ol>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Manage Users</div>

                <div class="panel-body">
                    

<!-- Stuff I added -->
      <div class="bs-component" ng-controller="listdata">
        <form class="form-inline">
          <div class="form-group">

              <label >Search Users</label>

            <input type="text" ng-model="search1" class="form-control" placeholder="Search by name or email..." size="100">

          </div>
        </form>
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th ng-click="sort('id')">Id
                <span class="glyphicon sort-icon" ng-show="sortKey=='id'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
              </th>
              <th ng-click="sort('name')">Display Name
                <span class="glyphicon sort-icon" ng-show="sortKey=='name'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
              </th>
              <th ng-click="sort('email')">E-Mail
                <span class="glyphicon sort-icon" ng-show="sortKey=='email'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
              </th>
              @if(Auth::user()->access() == 3)
              <th ng-click="sort('access')">Access Level
                <span class="glyphicon sort-icon" ng-show="sortKey=='access'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
              </th>
              @endif
              <th >Manage
                <span class="glyphicon sort-icon" ng-show="sortKey=='quantity'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr dir-paginate="user in users|orderBy:sortKey:reverse|filter:search1|itemsPerPage:10">
              
              <td>@{{user.id}}</td>
              <td>@{{user.name}}</a></td>
              <td>@{{user.email}}</td>
              @if(Auth::user()->access() == 3)
              <td>@{{user.access}}</td>
              @endif
              <td><a href="{{url('admin/orders')}}/@{{ user.id }}">View User Orders</a>
                <!-- This access level will be modified so admin lvl 3 can only see this option. -->
                @if(Auth::user()->access() == 3)
                <br><a href="{{url('admin/access')}}/@{{ user.id }}">Manage Access Level</a>
                @endif
              </td>

            </tr>
          </tbody>
        </table> 
        <center><dir-pagination-controls
          max-size="5"
          direction-links="true"
          boundary-links="true" >
        </dir-pagination-controls>
      </div></center>

<!-- Stuff I added -->


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
