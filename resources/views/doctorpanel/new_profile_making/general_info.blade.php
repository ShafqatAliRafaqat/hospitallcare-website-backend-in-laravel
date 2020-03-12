@extends('doctorpanel.new_profile_making.profile_layout')
@section('styles')
<link rel="stylesheet" href="{{ asset('backend/css/fileupload.css') }}">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Doctor Profiling</h3>
          </div>
          <div class="card-body">
            @foreach($errors as $e)
            <h1>{{$e}}</h1>
            @endforeach
            <form class="form-horizontal" method="post" id="doctor-form" action="{{ route('doctor_general_info',$doctor->id) }}" enctype="multipart/form-data">
              @csrf
              @method('put')
              <input type="hidden" name="lat" value="{{$doctor->lat}}" id="lat">
              <input type="hidden" name="lng" value="{{$doctor->lng}}" id="lng">
              <input type="hidden" name="city_name" value="{{$doctor->city_name}}" id="city_name">
              <div class="form-group row">
                <label class="col-md-2 form-control-label">Doctor Name  <span class="asterisk">*</span></label>
                <div class="col-md-4">
                  <input type="text" name="name" placeholder="Doctor First Name"
                  class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{$doctor->name}}" required>
                  @if($errors->has('name'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('name') }}</div>
                  @endif
                </div>
                <label class="col-md-2 form-control-label">Last Name</label>
                <div class="col-md-4">
                  <input type="text" name="last_name" placeholder="Doctor Last Name"
                  class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" value="{{$doctor->last_name}}">
                  @if($errors->has('last_name'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('last_name') }}</div>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-2 form-control-label">Focus Area<span class="asterisk">*</span></label>
                <div class="col-md-4">
                  <input class="form-control" value="{{$doctor->focus_area}}" placeholder="Enter Focus Area Comma Seprated" name="focus_area" required>
                </div>
                @if(!empty($focus_area))
                <div class="invalid-feedback ml-3">{{ $focus_area }}</div>
                @endif
                  <label class="col-md-2 form-control-label">Gender <span class="asterisk">*</span></label>
                  <div class="col-md-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="male" type="radio" value="1" name="gender" class="custom-control-input"
                      {{ $doctor->gender == 1 ? 'checked':'' }} >
                      <label for="male" class="custom-control-label">Male</label>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="female" type="radio" value="0" name="gender" class="custom-control-input" {{ $doctor->gender == 0 ? 'checked':'' }}>
                      <label for="female" class="custom-control-label">Female</label>
                    </div>
                  </div>
              </div>

              <div class="form-group row">
                <label class="col-md-2 form-control-label">PMDC No. <span class="asterisk">*</span></label>
                <div class="col-md-4">
                  <input type="text" name="pmdc" placeholder="PMDC Number"
                  class="form-control {{ $errors->has('pmdc') ? 'is-invalid' : '' }}" value="{{$doctor->pmdc}}" required>
                  @if($errors->has('pmdc'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('pmdc') }}</div>
                  @endif
                </div>
                <label class="col-md-2 form-control-label">Doctor's Phone</label>
                <div class="col-md-4">
                  <input type="text" name="phone" placeholder="Phone" data-mask = "9999-9999999"
                  class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ $doctor->phone }}">
                  @if($errors->has('phone'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('phone') }}</div>
                  @endif
                </div>
              </div>
              <!-- ABOUT -->
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">About Doctor</label>
                  <div class="col-md-4">
                    <textarea class="form-control" name="about" id="" cols="30" rows="4">{{ $doctor->about }}</textarea>
                    @if($errors->has('about'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('about') }}</div>
                    @endif
                  </div>
                  <label class="col-md-2 form-control-label">Email <span class="asterisk">*</span></label>
                  <div class="col-md-4">
                    <input type="email" name="email" placeholder="Email"
                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{$doctor->email}}" required>
                    @if($errors->has('email'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('email') }}</div>
                    @endif
                  </div>
                </div>
              <div class="form-group row">
                <label class="col-md-2 form-control-label">Picture</label>
                <div class="col-md-7 imageupload">
                  <div class="file-tab panel-body">
                    <label class="btn btn-success btn-file">
                      <span>File</span>
                      <!-- The file is stored here. -->
                      <input type="file" name="picture">
                    </label>
                    <button type="button" class="btn btn-danger">Remove</button>
                  </div>
                </div>
                @php
                $picture = (isset($image))?$image->picture:"";
                @endphp
                <div class="col-md-3">
                  <div class="img-wrap">
                    <span class="close">×</span>
                    <input type="hidden" name="picture"  value="{{$picture}}">
                    <img src="{{ asset('backend/uploads/doctors/'.$picture) }}" class="img-fluid" alt="{{ $errors->first('picture') }}" height="100" width="100">
                  </div>
                </div>
              </div>

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Address  <span class="asterisk">*</span></label>
                  <div class="col-md-10 mb-2">
                    <input type="text" name="address" id="address" placeholder="Address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" value="{{ $doctor->address }}" required>
                    @if($errors->has('address'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('address') }}</div>
                    @endif
                  </div>
                  <div class="col-md-10  offset-2 map-height" id="locationField">
                    <div class="pac-card" id="pac-card">
                      <div>
                        <div id="title">
                          Autocomplete search
                        </div>
                        <div id="type-selector" class="pac-controls">
                          <input type="radio" name="type" id="changetype-all" checked="checked">
                          <label for="changetype-all">All</label>

                          <input type="radio" name="type" id="changetype-establishment">
                          <label for="changetype-establishment">Establishments</label>

                          <input type="radio" name="type" id="changetype-address">
                          <label for="changetype-address">Addresses</label>

                          <input type="radio" name="type" id="changetype-geocode">
                          <label for="changetype-geocode">Geocodes</label>
                        </div>
                        <div id="strict-bounds-selector" class="pac-controls">
                          <input type="checkbox" id="use-strict-bounds" value="">
                          <label for="use-strict-bounds">Strict Bounds</label>
                        </div>
                      </div>
                      <div id="pac-container">
                        <input id="pac-input" type="text"
                        placeholder="Enter a location" value="{{ $doctor->address }}">
                      </div>
                    </div>
                    <div id="map"></div>
                    <div id="infowindow-content">
                      <img src="" width="16" height="16" id="place-icon">
                      <span id="place-name"  class="title"></span><br>
                      <span id="place-address"></span>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Update General Info</button>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
@section('scripts')
<script src="{{ asset('backend/js/select2-develop/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/fileupload.js') }}" ></script>
<script src="{{ asset('backend/js/fileupload2.js') }}" ></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>
<script src="{{ asset('backend/js/bootstrap-inputmask.min.js') }}"></script>
<script src="{{asset('backend/js/bootstrap-imageupload.js')}}"></script>
<script type="text/javascript">
 $('form').submit(function(e) {
 var $input = $(this).find('input.auto');
     val = $input.val();
     list = $input.attr('list'),
     match = $('#'+list + ' option').filter(function() {
         return ($(this).val() === val);
     });

  if(match.length > 0) {
      e.preventDefault();

      var area = match.val();
      alert('Navigate to ' + area);

  }
});
</script>
<script>
  var $imageupload = $('.imageupload');
  $imageupload.imageupload();
  $('#doctor-form').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
      e.preventDefault();
      return false;
    }
  });
</script>
<script>
      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 31.5204, lng: 74.3587},
          zoom: 13
        });
        var card          = document.getElementById('pac-card');
        var input         = document.getElementById('pac-input');
        var types         = document.getElementById('type-selector');
        var strictBounds  = document.getElementById('strict-bounds-selector');

        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

        var autocomplete = new google.maps.places.Autocomplete(input);

        // Bind the map's bounds (viewport) property to the autocomplete object,
        // so that the autocomplete requests use the current map bounds for the
        // bounds option in the request.
        autocomplete.bindTo('bounds', map);

        // Set the data fields to return when the user selects a place.
        autocomplete.setFields(
          ['address_components', 'geometry', 'icon', 'name']);

        var infowindow = new google.maps.InfoWindow();
        var infowindowContent = document.getElementById('infowindow-content');
        infowindow.setContent(infowindowContent);
        var marker = new google.maps.Marker({
          map: map,
          anchorPoint: new google.maps.Point(0, -29)
        });

        autocomplete.addListener('place_changed', function() {
          infowindow.close();
          marker.setVisible(false);
          var place = autocomplete.getPlace();
          if (!place.geometry) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert("No details available for input: '" + place.name + "'");
            return;
          }

          // If the place has a geometry, then present it on a map.
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(14);  // Why 17? Because it looks good.
          }
          marker.setPosition(place.geometry.location);
          marker.setVisible(true);

          var address   = '';
          var locality  = '';
          if (place.address_components) {
            address = [
            (place.address_components[0] && place.address_components[0].short_name || ''),
            (place.address_components[1] && place.address_components[1].short_name || ''),
            (place.address_components[2] && place.address_components[2].short_name || ''),
            (place.address_components[3] && place.address_components[3].short_name || ''),
            (place.address_components[4] && place.address_components[4].short_name || '')
            ].join(' ');
          }

          infowindowContent.children['place-icon'].src = place.icon;
          infowindowContent.children['place-name'].textContent = place.name;
          infowindowContent.children['place-address'].textContent = address;
          infowindow.open(map, marker);

          var place = autocomplete.getPlace();
          // Then do whatever you want with them
          for (var i = 0; i < place.address_components.length; i++) {
            if (place.address_components[i].types[0] == 'locality') {
              locality = place.address_components[i].long_name;
            }
          }
        $("#lat").val(place.geometry.location.lat());
        $("#lng").val(place.geometry.location.lng());
        $("#address").val(address);
        $("#city_name").val(locality);
          // console.log(lat);
          // console.log(lng);
          // console.log(place.name);
          // console.log(place.address_components);
          // console.log(address);
        });

        // Sets a listener on a radio button to change the filter type on Places
        // Autocomplete.
        function setupClickListener(id, types) {
          var radioButton = document.getElementById(id);
          radioButton.addEventListener('click', function() {
            autocomplete.setTypes(types);
          });
        }

        setupClickListener('changetype-all', []);
        setupClickListener('changetype-address', ['address']);
        setupClickListener('changetype-establishment', ['establishment']);
        setupClickListener('changetype-geocode', ['geocode']);

        document.getElementById('use-strict-bounds')
        .addEventListener('click', function() {
          console.log('Checkbox clicked! New state=' + this.checked);
          autocomplete.setOptions({strictBounds: this.checked});
        });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD9RHZgUXffbQmvczfgC8CeNKfm6IYMAJQ&libraries=places&callback=initMap" async defer></script>
    @endsection
