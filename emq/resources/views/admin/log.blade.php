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
  $http.get("{{asset('/admin/api?data=log')}}").success(function(response){ 
    $scope.log = response.log;  //ajax request to fetch data into $scope.data
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
  <li class="active">Admin Activity Log</li>
</ol>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Admin Activity Log</div>

                <div class="panel-body">
                    

<!-- Stuff I added -->
      <div class="bs-component" ng-controller="listdata">
        <form class="form-inline">
          <div class="form-group">
            <div style="margin: 5px;">
              <label >Search Log</label>

            <input type="text" ng-model="search1" class="form-control" placeholder="Enter search parameters..." size="100">
            </div>
            <div style="margin: 5px;">
            <label >Filter</label>

              <select name="category" ng-model="search2" class="form-control">
                <option value="" selected="selected"></option>
                <option value="Product Update">[Product Update]</option>
                <option value="User Update">[User Update]</option>
              </select>
            </div>
          </div>
        </form>
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th ng-click="sort('user_id')">Admin id
                <span class="glyphicon sort-icon" ng-show="sortKey=='user_id'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
              </th>
              <th ng-click="sort('message')">Log Message
                <span class="glyphicon sort-icon" ng-show="sortKey=='message'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
              </th>
              <th ng-click="sort('id')">Timestamp
                <span class="glyphicon sort-icon" ng-show="sortKey=='id'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr dir-paginate="entry in log|orderBy:sortKey:reverse|filter:search1|filter:search2|itemsPerPage:10">
              
              <td>@{{entry.user_id}}</a></td>
              <td>@{{entry.message}}</td>
              <td>@{{entry.created_at}}</td>
              <!--<td><a href="./orders/@{{ user.id }}">View User Orders</a>-->

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
