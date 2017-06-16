@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
  <li><a href="{{ url('/admin/management') }}">Admin Management</a></li>
  <li class="active">View Stores</li>
</ol>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">View Stores</div>

                <div class="panel-body">
                    

<!-- Stuff I added -->
                    <div class="row" style="width: 100%; text-align: center;">
                        @foreach($stores as $store)
                        <div class="col-md-6">
                            <div class="panel panel-warning" style="margin: 10px;">
                                <div class="panel-heading" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                {{ stripslashes($store->name) }}
                                </div>
                                <div class="panel-body">
                                  <div style="margin: 5px auto;">
                                    <div>
                                      {{ $store-> address}}
                                    </div>
                                    <div>
                                      {{ $store->city}}, {{ $store->state}} {{ $store->zip}}
                                    </div>
                                    <div>
                                      Phone: {{ $store->phone}}
                                    </div>
                                  </div>
                                  <div>
                                    
                                  </div>
                                </div>
                            </div>                            
                        </div>
                      @endforeach           
                    </div>

<!-- Stuff I added -->


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
