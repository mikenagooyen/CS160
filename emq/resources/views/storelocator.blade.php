@extends('layouts.app')

@section('scripts-head')
    <!-- Start of Scripts Added to Head Section -->
    <style>

      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
    <!-- End of Scripts Added to Head Section -->
@endsection



@section('scripts-body')
    <!-- Start of Scripts Added to Body Section -->
    <!-- End of Scripts Added to Body Section -->
@endsection


@section('content')
<div class="container">
  <div class="row">
      <div class="col-md-10 col-md-offset-1">
          <div class="panel panel-default">
              <div class="panel-heading">Store Locator</div>
                  <div id="map" style="width: 100%; height: 550px; position: relative; overflow;"></div>
                  <script>
                    var stores_geocoordinates = [
                      {!! $stores_geocoordinates_array !!}
                    ];

                    var stores_infowindows = [
                      {!! $stores_infowindow_array !!}
                    ];
                    
                    var markers = [];
                    var map;
                    function initMap(){
                      // Create a map and center it on SJSU.
                      map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 9,
                        center: {lat: 37.501622, lng: -121.968116}
                      });
                      var infowindow = new google.maps.InfoWindow;
                      var geocoder = new google.maps.Geocoder;

                      for (var i = 0; i < stores_geocoordinates.length; i++) {
                        addMarkerWithTimeout(stores_geocoordinates[i], i * 100, infowindow, geocoder, i);
                      }

                    }
                    function addMarkerWithTimeout(store, timeout, infowindow, geocoder, index) {
                      window.setTimeout(function() {
                        var location = new google.maps.LatLng(store[0], store[1]);
                        var location_text = stores_infowindows[index];
                        var marker = new google.maps.Marker({
                          position: location,
                          map: map,
                          icon: '{{asset('/images/store.png')}}',
                          animation: google.maps.Animation.DROP
                        });
                        google.maps.event.addListener(marker, 'click', function() {
                          infowindow.setContent(location_text);
                          infowindow.open(map, marker);
                        });
                        markers.push(marker);
                      }, timeout);    
                    }
                  </script>
                  <script async defer
                        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCMM6EkVGKo91UtYnDnC_fGkOActvJqW2c&libraries=places&callback=initMap">
                  </script>

<!-- Stuff I added -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
