@extends('adminpanel.layout')
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
            <h3 class="h6 text-uppercase mb-0">Medical Center</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" id="center-form" method="post" action="{{ route('medical.update', $center->id) }}" enctype="multipart/form-data">
              @csrf
              @method('PUT')
                <input type="hidden" name="lat" id="lat" value="{{ $center->lat}}">
                <input type="hidden" name="lng" id="lng" value="{{ $center->lng}}">
              <?php $i = 0 ;?>
              @foreach($center->center_treatment as $center_treatment)
              <div id="dynamic_field">
                <div class="form-group row" id="treatment{{$i+1}}">
                  <div class="col-md-2 form-control-label">Select Treatment  <span class="asterisk">*</span></div>
                  <div class="col-md-3">
                  <select name="treatment_id[]" id="treatment" class="form-control selectpicker">
                        @if (count($treatments) > 0)
                          @foreach ($treatments as $t)
                            <option value="{{ $t->id }}"
                              {{ ($t->id == $center_treatment->id) ? 'selected' : ''}}
                              >{{ $t->name }}</option>
                          @endforeach
                        @endif
                      </select>
                  </div>
                  <div class="col-md-2 form-control-label">Cost  <span class="asterisk">*</span></div>
                  <div class="col-md-3">
                    <input type="number" name="cost[]" value="{{$center_treatment->pivot->cost}}" class="form-control name_list" required/>
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
                  <label class="col-md-2 form-control-label">Focus Areas  <span class="asterisk">*</span></label>
                  <div class="col-md-10">
                    <textarea name="focus_area" id="" class="form-control" cols="30" rows="10">{{ $center->focus_area }}</textarea>
                  </div>
                  @if(!empty($focus_area))
                      <div class="invalid-feedback ml-3">{{ $focus_area }}</div>
                  @endif
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Center Name  <span class="asterisk">*</span></label>
                  <div class="col-md-10">
                      <input type="text" id="center_name" name="center_name" placeholder="Center Name"
                      class="form-control {{ $errors->has('center_name') ? 'is-invalid' : '' }}" value="{{ $center->center_name }}" required>

                    @if($errors->has('center_name'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('center_name') }}</div>
                      @endif
                  </div>
                </div>
                  <div class="form-group row">
                  <label class="col-md-2 form-control-label">Assistant's Name</label>
                  <div class="col-md-4">
                        <input type="text" name="assistant_name" placeholder="Assistant's Name"
                        class="form-control {{ $errors->has('assistant_name') ? 'is-invalid' : '' }}" value="{{ $center->assistant_name }}">

                      @if($errors->has('assistant_name'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('assistant_name') }}</div>
                      @endif
                  </div>

                  <label class="col-md-2 form-control-label">Assistant's Phone</label>
                  <div class="col-md-4">
                        <input type="text" name="assistant_phone" data-mask="9999-9999999" placeholder="Assistant's Phone"
                        class="form-control {{ $errors->has('assistant_phone') ? 'is-invalid' : '' }}" value="{{ $center->assistant_phone }}">

                      @if($errors->has('assistant_phone'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('assistant_phone') }}</div>
                      @endif
                  </div>
                </div>
                  <div class="form-group row">
                  <label class="col-md-2 form-control-label">Landline Number</label>
                  <div class="col-md-4">
                        <input type="text" name="phone" data-mask="(999)-999-999-9?99" placeholder="Phone"
                        class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ $center->phone }}">

                      @if($errors->has('phone'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('phone') }}</div>
                      @endif
                  </div>
                  <label class="col-md-2 form-control-label">Area</label>
                  <div class="col-md-4">
                    <input type="text" name="area" placeholder="Area"
                    class="form-control {{ $errors->has('area') ? 'is-invalid' : '' }}" value="{{ $center->area }}">

                    @if($errors->has('area'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('area') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">City</label>
                  <div class="col-md-4">
                    <?php $cities = getAllCities();?>
                    <select class="form-control selectpicker" data-live-search="true" id="city_name" name="city_name">
                      <option value=""> Select City </option>
                      @if(isset($cities))
                        @foreach ($cities as $ci)
                          <option value="{{$ci->name}}" {{ ($ci->name == $center->city_name) ? "selected":''}}>
                          {{$ci->name}}
                          </option>
                        @endforeach
                      @endif
                    </select>
                    @if($errors->has('city_name'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('city_name') }}</div>
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
                        <img src="{{ asset('backend/uploads/centers/'.$picture) }}" class="img-fluid" alt="{{ $errors->first('picture') }}" height="100" width="100">
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Active</label>
                  <div class="col-md-4">
                    <div class="custom-control custom-checkbox">
                      <input id="is_active" value="1" type="checkbox" name="is_active" class="custom-control-input"
                      {{ $center->is_active == 1 ? 'checked':'' }}>
                      <label for="is_active" class="custom-control-label">Check to Active the Center</label>
                    </div>
                  </div>
                  <label class="col-md-2 form-control-label">Show on Web</label>
                  <div class="col-md-4">
                    <div class="custom-control custom-checkbox">
                      <input id="on_web" value="1" type="checkbox" name="on_web" class="custom-control-input"
                      {{ $center->on_web == 1 ? 'checked':'' }}>
                      <label for="on_web" class="custom-control-label">Check to Show on Website</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Updated By</label>
                  <div class="col-md-10">
                        <input type="text" name="updated_by" placeholder="{{ Auth::user()->name }}"
                        class="form-control {{ $errors->has('updated_by') ? 'is-invalid' : '' }}" value="{{ Auth::user()->name  }}" readonly>
                      @if($errors->has('updated_by'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('updated_by') }}</div>
                      @endif
                  </div>
                </div>
                <ul class="nav nav-tabs customer-nav" role="tablist">
                  <li class="nav-item">
                      <a class="nav-link active" href="#address-tab" role="tab" data-toggle="tab" >Address</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " href="#seo-tab" role="tab" data-toggle="tab" >SEO Details</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " href="#partnership-tab" role="tab" data-toggle="tab">Partnership Details</a>
                  </li>
                </ul>
                <hr>
                <!-- Tab panes -->
                <div class="tab-content">
                <div role="tabpanel" class="tab-pane pt-3 in active" id="address-tab">
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Address  <span class="asterisk">*</span></label>
                    <div class="col-md-10 mb-2">
                      <input type="text" name="address" id="address" placeholder="Address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" value="{{ $center->address }}" required>
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
                                placeholder="Enter a location" value="{{ $center->address }}">
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
                <div role="tabpanel" class="tab-pane pt-3 in fade" id="seo-tab">
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Meta Title</label>
                  <div class="col-md-10">
                      <input type="text" name="meta_title" placeholder="SEO Meta Title"
                      class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" value="{{ $center->meta_title }}">

                    @if($errors->has('meta_title'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_title') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Meta Description</label>
                  <div class="col-md-10">
                      <input type="text" name="meta_description" placeholder="SEO Meta Description"
                      class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" value="{{ $center->meta_description }}">

                    @if($errors->has('meta_description'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_description') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">URL</label>
                  <div class="col-md-10">
                      <input type="text" name="url" placeholder="Enter URL"
                      class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" value="{{ $center->url }}">
                    @if($errors->has('url'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('url') }}</div>
                    @endif
                  </div>
                </div>
                </div>
                <div role="tabpanel" class="tab-pane pt-3 in fade" id="partnership-tab">
                        <div class="row pb-2">
                                <label class="col-md-1 form-control-label">New Uploads</label>
                                <div class="col-md-3">
                                    <output id="Filelist2"></output>
                                </div>
                                <label class="col-md-1 form-control-label">Already Uploads</label>
                                <div class="col-md-3"style="display: -webkit-box;">
                                    @if($ptnr_images)
                                    @foreach($ptnr_images as $image)
                                        <div>
                                        <div class="img-wrap">
                                            <span class="close">×</span>
                                            <input type="hidden" name="old_ptnr_picture[]"  value="{{$image->picture}}">
                                            <a href="#" class="pop">
                                            <img id="zoom_mw" data-zoom-image="large/image1.jpg"/ src="{{ asset('backend/uploads/center_partnership_images/'.$image->picture) }}" class="img-fluid" alt="{{ $errors->first('ptnr_picture') }}" height="100" width="100">
                                          </a>
                                        </div>
                                        </div>
                                        @endforeach
                                        @endif
                                </div>
                                <label class="col-md-1 form-control-label">Files</label>
                                <div class="col-md-3">
                                        @if($ptnr_files)
                                        @foreach($ptnr_files as $files)
                                        <div>
                                            <div class="file-wrap">
                                                <span class="close">×</span>
                                                <a href="#" class="pop_pdf">
                                                        @php
                                                        $sprt = explode('.',$files->file);
                                                        @endphp
                                                        {{ $sprt[1] }}
                                                        <img id="zoom_mw" data-zoom-image="large/image1.jpg"/ src="{{ asset('backend/uploads/center_partnership_files/'.$files->file) }}"
                                                        alt="" class="col-md-6 mt-1 responsive" max-height="200px" max-width="200px">
                                                      </a>
                                                <input type="hidden" name="old_ptnr_files[]"  value="{{$files->file}}">
                                            </div>
                                            </div>
                                            @endforeach
                                            @endif
                                </div>
                        </div>
                 <div class="form-group row">
                          <label class="col-md-2 form-control-label">Ad-Spent</label>
                          <div class="col-md-4">
                            <input type="number" name="ad_spent" placeholder="Ad Spent"
                                class="form-control {{ $errors->has('ad_spent') ? 'is-invalid' : '' }}" value="{{ $center->ad_spent }}">
                              @if($errors->has('ad_spent'))
                                <div class="invalid-feedback ml-3">{{ $errors->first('ad_spent') }}</div>
                              @endif
                          </div>
                          <label class="col-md-2 form-control-label">Revenue Share</label>
                          <div class="col-md-4">
                            <input type="number" name="revenue_share"  placeholder="Revenue Share"
                                class="form-control {{ $errors->has('revenue_share') ? 'is-invalid' : '' }}" value="{{ $center->revenue_share }}">
                              @if($errors->has('revenue_share'))
                                <div class="invalid-feedback ml-3">{{ $errors->first('revenue_share') }}</div>
                              @endif
                          </div>
                        </div>
                        <div class="form-group row">
                                <label class="col-md-2 form-control-label">Pictures </label>
                                <div class="col-md-4">
                                  <span class="btn btn-success fileinput-button">
                                      <span>Select Pictures</span>
                                      <input type="file" name="ptnr_picture[]" multiple id="files2" class="form-control {{ $errors->has('ptnr_picture') ? 'is-invalid' : '' }}"><br />
                                  </span>

                                    @if($errors->has('ptnr_picture'))
                                    <div class="invalid-feedback ml-3">{{ $errors->first('ptnr_picture') }}</div>
                                    @endif
                                </div>
                                <label class="col-md-2 form-control-label">Files </label>
                                <div class="col-md-4">
                                      <input type="file" class="form-control" name="ptnr_files[]" multiple />
                                    @if($errors->has('ptnr_files'))
                                    <div class="invalid-feedback ml-3">{{ $errors->first('ptnr_files') }}</div>
                                    @endif
                                </div>
                            </div>
                        <!-- NOTES -->
                        <div class="form-group row">
                          <label class="col-md-2 form-control-label">Additional Details</label>
                          <div class="col-md-10">
                              <textarea placeholder="Enter Details" class="form-control tiny" name="additional_details" id="" cols="30" rows="5">{{ $center->additional_details }}</textarea>
                              @if($errors->has('additional_details'))
                              <div class="invalid-feedback ml-3">{{ $errors->first('additional_details') }}</div>
                              @endif
                          </div>
                        </div>
                        </div>
                    </div><!-- END OF ALL TABS -->

                <div class="form-group row">
                  <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Update Center</button>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <img src="" class="imagepreview" style="width: 100%;" >
            </div>
          </div>
        </div>
      </div>
<!-- PDF model -->
<div class="modal fade" id="pdfmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <embed src="" class="pdfpreview" frameborder="0" width="100%" height="600px">
            </div>
          </div>
        </div>
      </div>
      <!-- end of PDF models -->
@endsection
@section('scripts')
<script src="{{ asset('backend/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/tinymce-config.js') }}" ></script>
<script src="{{ asset('backend/js/fileupload.js') }}" ></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>
<script src="{{ asset('backend/js/select2-develop/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/fileupload2.js') }}" ></script>
<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="{{ asset('backend/js/bootstrap-inputmask.min.js') }}"></script>
<script src="{{asset('backend/js/bootstrap-imageupload.js')}}"></script>
<script>
        $(function() {
           $('.pop').on('click', function() {
             $('.imagepreview').attr('src', $(this).find('img').attr('src'));
             $('#imagemodal').modal('show');
           });
       });
       </script>
<script>
        $(function() {
           $('.pop_pdf').on('click', function() {
             $('.pdfpreview').attr('src', $(this).find('img').attr('src'));
             $('#pdfmodal').modal('show');
           });
       });
       </script>
<script>
        var $imageupload = $('.imageupload');
        $imageupload.imageupload();
    </script>
<script>
  $('#center-form').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) {
    e.preventDefault();
    return false;
  }
});
</script>
<script>
$(document).ready(function(){
	var i=1;
	$('#add0').click(function(){
		i++;
    var html = '';
  html += '<div class="form-group row" id="row'+i+'">';
  html += '<div class="col-md-2 form-control-label">Select '+i+' Treatment </div>';
  html += '<div class="col-md-3">';
  html += '<td><select name="treatment_id[]" id="treatment" class="form-control" data-live-search="true">   <option value="0">Select Treatment</option>  <?php if (count($treatments) > 0){     foreach ($treatments as $t){    ?>   <option value="<?php echo $t->id ?>"><?php echo $t->name ?></option>    <?php } }  ?></select></td>';
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
$('select[name*="treatment"]').change(function() {
    var selectedOptions = $('select option:selected');
    $('select option').removeAttr('disabled');
    selectedOptions.each(function() {
        var value = this.value;
        if (value !== ''){
        var id = $(this).parent('select[name*="treatment"]').attr('id');
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
        center: {lat: <?php echo $center->lat; ?>, lng: <?php echo $center->lng; ?>},
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
        position: new google.maps.LatLng(<?php echo $center->lat; ?>, <?php echo $center->lng; ?>),
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
        //Passing values to inputs for lat,lng,address etc
        $("#lat").val(place.geometry.location.lat());
        $("#lng").val(place.geometry.location.lng());
        $("#address").val(address);
        $("#center_name").val(place.name);
        $("#city_name").val(locality);

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

