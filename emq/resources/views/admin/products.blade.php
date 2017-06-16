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
  $http.get("{{asset('/admin/api?data=products')}}").success(function(response){ 
    $scope.products = response.products;  //ajax request to fetch data into $scope.data
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
  <li class="active">Manage Products</li>
</ol>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Manage Products</div>

                <div class="panel-body">

<!-- Stuff I added -->
                  
      <div class="bs-component" ng-controller="listdata">
        <form class="form-inline">
          <div class="form-group">
           <div style="margin: 5px;">
             
              <label >Search</label>
              <input type="text" ng-model="search2" class="form-control" placeholder="EMQ" size="85">
            </div>
            <div style="margin: 5px;">
            <label >Category</label>

              <select name="category" ng-model="search1" class="form-control">
                <option value="" selected="selected">All Products</option>
                <option value="Office Electronics">Office Electronics</option>
                <option value="Computer Parts & Components">Computer Parts & Components</option>
                <option value="PC & Accessories">PC & Accessories</option>
                <option value="Camera & Video">Camera & Video</option>
                <option value="TVs & Accessories">TVs & Accessories</option>
                <option value="Bluetooth & Wireless Speakers">Bluetooth & Wireless Speakers</option>
                <option value="Video Games">Video Games</option>
              </select>
            </div>
          </div>
        </form>
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th >
                <span class="glyphicon sort-icon" ng-show="sortKey=='productName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
              </th>
              <th ng-click="sort('id')">Id
                <span class="glyphicon sort-icon" ng-show="sortKey=='id'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
              </th>
              <th ng-click="sort('productName')">Product
                <span class="glyphicon sort-icon" ng-show="sortKey=='productName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
              </th>
              <th ng-click="sort('price')">Price
                <span class="glyphicon sort-icon" ng-show="sortKey=='price'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
              </th>
              <th ng-click="sort('quantity')">Quantity
                <span class="glyphicon sort-icon" ng-show="sortKey=='quantity'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
              </th>
              <th ng-click="sort('available')">Listed
                <span class="glyphicon sort-icon" ng-show="sortKey=='available'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
              </th>
              <th ng-click="sort('category')">Category
                <span class="glyphicon sort-icon" ng-show="sortKey=='category'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
              </th>
              <th ng-click="sort('brand')">Brand
                <span class="glyphicon sort-icon" ng-show="sortKey=='brand'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr dir-paginate="product in products|orderBy:sortKey:reverse|filter:search1|filter:search2|itemsPerPage:10">
      
              <td><img src="{{asset('product_images')}}/@{{product.image}}" style="width: 100px;"></td>
              <td>@{{product.id}}</td>
              <td><a href="{{url('admin/product')}}/@{{ product.id }}">@{{product.productName}}</a></td>
              <td>$@{{product.price}}</td>
              <td>@{{product.quantity}}</td>
              <td>@{{product.available}}</td>
              <td>@{{product.category}}</td>
              <td>@{{product.brand}}</td>

            </tr>
          </tbody>
        </table> 
        <center><dir-pagination-controls
          max-size="5"
          direction-links="true"
          boundary-links="true" >
        </dir-pagination-controls>
      </div></center>

                  <!--</div>-->



<!-- Stuff I added -->


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
