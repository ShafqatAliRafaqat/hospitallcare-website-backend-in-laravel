@extends('adminpanel.layout')
@section('title','Update Lab | HospitALL')
@section('styles')
  <link rel="stylesheet" href="{{ asset('backend/js/select2-develop/dist/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/fileupload.css') }}">
@endsection
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      @include('adminpanel.notification')
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Lab</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" id="lab-form" method="post" action="{{ route('labs.update', $labs->id) }}" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="lat" id="lat" value="{{ $labs->lat }}">
              <input type="hidden" name="lng" id="lng" value="{{ $labs->lng }}">
             <?php $i = 0 ;?>
              @foreach($labs->diagnostic as $diagnostic)
              <div id="dynamic_field">
                <div class="form-group row" id="treatment{{$i+1}}">
                  <div class="col-md-2 form-control-label">Select Diagnostic  <span class="asterisk">*</span></div>
                  <div class="col-md-3">
                  <select name="diagnostic_id[]" id="diagnostic" class="form-control selectpicker">
                        @if (count($diagnostics) > 0)
                          @foreach ($diagnostics as $t)
                            <option value="{{ $t->id }}"
                              {{ ($t->id == $diagnostic->id) ? 'selected' : ''}}
                              >{{ $t->name }}</option>
                          @endforeach
                        @endif
                      </select>
                  </div>
                  <div class="col-md-2 form-control-label">Cost  <span class="asterisk">*</span></div>
                  <div class="col-md-3">
                    <input type="number" name="cost[]" value="{{$diagnostic->pivot->cost}}" class="form-control name_list" required/>
                  </div>
                  <?php if ($i == 0){?>
                    <div class="col-md-2 form-control-label">
                      <button type="button" name="add" id="add{{$i}}" class="btn btn-success">Add More</button>
                    </div>
                  <?php }else{ ?>
                    <div class="col-md-2 form-control-label">
                        <button type="button" name="remove" id="treatment{{$i+1}}" class="btn btn-danger btn_remove">X</button>
                    </div>
                  <?php }?>

                </div>
                </div>
                <?php $i++?>
                @endforeach

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Lab Name  <span class="asterisk">*</span></label>
                  <div class="col-md-10">
                      <input type="text" name="name" placeholder="Center Name" id="lab_name"
                      class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $labs->name }}" required>

                    @if($errors->has('name'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('name') }}</div>
                      @endif
                  </div>
                </div>
                  <div class="form-group row">
                  <label class="col-md-2 form-control-label">Assistant's Name</label>
                  <div class="col-md-4">
                        <input type="text" name="assistant_name" placeholder="Assistant's Name"
                        class="form-control {{ $errors->has('assistant_name') ? 'is-invalid' : '' }}" value="{{ $labs->assistant_name }}">

                      @if($errors->has('assistant_name'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('assistant_name') }}</div>
                      @endif
                  </div>

                  <label class="col-md-2 form-control-label">Assistant's Phone</label>
                  <div class="col-md-4">
                        <input type="text" name="assistant_phone" data-mask="9999-9999999" placeholder="Assistant's Phone"
                        class="form-control {{ $errors->has('assistant_phone') ? 'is-invalid' : '' }}" value="{{ $labs->assistant_phone }}">

                      @if($errors->has('assistant_phone'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('assistant_phone') }}</div>
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
                        <img src="{{ asset('backend/uploads/labs/'.$picture) }}" class="img-fluid" alt="{{ $errors->first('picture') }}" height="100" width="100">
                    </div>
                  </div>
                </div>
                  <ul class="nav nav-tabs customer-nav" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#address-tab" role="tab" data-toggle="tab" >Address</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#notes-tab" role="tab" data-toggle="tab" >Notes</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#seo-tab" role="tab" data-toggle="tab" >SEO Details</a>
                    </li>
                  </ul>

                  <!-- Tab panes -->
                <div class="tab-content">
               <div role="tabpanel" class="tab-pane pt-3 in active" id="address-tab">
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Address  <span class="asterisk">*</span></label>
                    <div class="col-md-10 mb-2">
                      <input type="text" name="address" id="address" placeholder="Address" value="{{ $labs->address }}" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"  required>
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
                            <input id="pac-input" type="text" placeholder="Enter a location" value="{{ $labs->address}}">
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
             </div>
              <div role="tabpanel" class="tab-pane pt-3 in fade" id="notes-tab">
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Notes</label>
                  <div class="col-md-10">
                      <textarea placeholder="Enter Details" class="form-control tiny {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="" cols="30" rows="5">{{ old('address') }}</textarea>
                      @if($errors->has('notes'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('notes') }}</div>
                      @endif
                  </div>
                </div>
              </div>
              <div role="tabpanel" class="tab-pane pt-3 in fade" id="seo-tab">
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Meta Title</label>
                  <div class="col-md-10">
                      <input type="text" name="meta_title" placeholder="SEO Meta Title"
                      class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" value="{{ $labs->meta_title }}">

                    @if($errors->has('meta_title'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_title') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Meta Description</label>
                  <div class="col-md-10">
                      <input type="text" name="meta_description" placeholder="SEO Meta Description"
                      class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" value="{{ $labs->meta_description }}">

                    @if($errors->has('meta_description'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_description') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">URL</label>
                  <div class="col-md-10">
                      <input type="text" name="url" placeholder="Enter URL"
                      class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" value="{{ $labs->url }}">
                    @if($errors->has('url'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('url') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Meta Heading</label>
                  <div class="col-md-10">
                      <input type="text" name="meta_heading" placeholder="Enter Meta Heading"
                      class="form-control {{ $errors->has('meta_heading') ? 'is-invalid' : '' }}" value="{{ $labs->meta_heading }}">
                    @if($errors->has('meta_heading'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_heading') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Image Alt</label>
                  <div class="col-md-10">
                      <input type="text" name="image_alt" placeholder="Enter Image Alt"
                      class="form-control {{ $errors->has('image_alt') ? 'is-invalid' : '' }}" value="{{ $labs->image_alt }}">
                    @if($errors->has('image_alt'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('image_alt') }}</div>
                    @endif
                  </div>
                </div>
                </div>
           </div><!-- End of Tabs -->
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Active</label>
                  <div class="col-md-10">
                    <div class="custom-control custom-checkbox">
                      <input id="is_active" value="1" type="checkbox" name="is_active" class="custom-control-input"
                      {{ $labs->is_active == 1 ? 'checked':'' }}>
                      <label for="is_active" class="custom-control-label">Check to Active the Center</label>
                    </div>
                  </div>
                </div>
                <hr>

                <div class="form-group row">
                  <div class="col-md-9 ml-auto">
                    <button type="submit" class="btn btn-primary">Update Lab</button>
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
<script>
  $('#lab-form').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) {
    e.preventDefault();
    return false;
  }
});
</script>
<script src="{{ asset('backend/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/tinymce-config.js') }}" ></script>
<script src="{{ asset('backend/js/fileupload.js') }}" ></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>
<script src="{{asset('backend/js/bootstrap-imageupload.js')}}"></script>
<script src="{{ asset('backend/js/bootstrap-inputmask.min.js') }}"></script>
<script>
  var $imageupload = $('.imageupload');
  $imageupload.imageupload();
</script>
<script>
$(document).ready(function(){
  var i=1;
  $('#add0').click(function(){
  i++;
  var html = '';
  html += '<div class="form-group row" id="row'+i+'">';
  html += '<div class="col-md-2 form-control-label">Select Diagnostic <span class="asterisk">*</span></div>';
  html += '<div class="col-md-3">';
  html += '<td><select name="diagnostic_id[]" id="diagnostic" class="form-control" data-live-search="true">   <option value="0">Select Diagnostic</option>  <?php if (count($diagnostics) > 0){     foreach ($diagnostics as $t){    ?>   <option value="<?php echo $t->id ?>"><?php echo $t->name ?></option>    <?php } }  ?></select></td>';
  html += '</div>';
  html += '<div class="col-md-2 form-control-label">Cost  <span class="asterisk">*</span></div>';
  html += '<div class="col-md-3">';
  html += '<input type="number" name="cost[]" placeholder="Enter treatment Cost" class="form-control name_list" required />';
  html += '</div>';
  html += '<div class="col-md-2 form-control-label"> <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div>';
  html += '</div>';
  $('#dynamic_field').append(html);
  });

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id");
    $('#row'+button_id+'').remove();
  });

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id");
    $('#'+button_id+'').remove();
  });

});
$('select[name*="diagnostic"]').change(function() {
    var selectedOptions = $('select option:selected');
    $('select option').removeAttr('disabled');
    selectedOptions.each(function() {
        var value = this.value;
        if (value !== ''){
        var id = $(this).parent('select[name*="diagnostic"]').attr('id');
        var options = $('select:not(#' + id + ') option[value=' + value + ']');
        options.attr('disabled', 'true');
        }
    });
});
</script>
<script>
    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
    function initMap() {
      var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: <?php echo $labs->lat; ?>, lng: <?php echo $labs->lng; ?>},
        zoom: 15
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
        position: new google.maps.LatLng(<?php echo $labs->lat; ?>, <?php echo $labs->lng; ?>),
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

        var address = '';
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
        //Passing values to inputs for lat,lng,address etc
        $("#lat").val(place.geometry.location.lat());
        $("#lng").val(place.geometry.location.lng());
        $("#address").val(address);
        $("#lab_name").val(place.name);
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

