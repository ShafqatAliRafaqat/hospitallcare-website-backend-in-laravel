<?php $years = get_years();?>
@section('scripts')
<script type="text/javascript">
$(document).on('change','#city', function(){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    // var city_id = $('#city option:selected');
    var city_id = $(this).val();
    console.log(city_id);
    $.ajax({
      type:'post',
      url:"{{ route('getArea') }}",
      data: { city_id : city_id},
      success: function(response){
          $('#area').html(response);
          $('#area').selectpicker('refresh');

      }
    });
  });
</script>
<script type="text/javascript">
  $(document).on('change','#specializationselect', function(){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var selected = [];
    var specialization_id  = $('#specializationselect option:selected').map(function(){
    return this.value;
    });
    for (var i = specialization_id.length - 1; i >= 0; i--) {
      selected.push(specialization_id[i]);
    }
    if (specialization_id.length > 0) {
    $.ajax({
      type:'post',
      url:"{{ route('getMultipleTreatments') }}",
      data: { specialization_ids : selected},
      success: function(response){
          $('#procedure').html(response);
          $('#procedure').addClass('selectpicker');
          $('#procedure').attr('data-live-search', 'true');
          $('#procedure').attr('multiple','true');
          $('#procedure').selectpicker('refresh');
      }
    });
    } else {
      var html = '<option value=""> Select Treatment</option>';
      $('#procedure').html(html);

    }
  });
</script>

  <script type='text/javascript'>
    $('#multipleSelectTreatments').val(['42', '5']);
  </script>
  <script>
    $('#doctor-form').on('keyup keypress', function(e) {
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
  <script src="{{ asset('backend/js/fileupload2.js') }}" ></script>
  <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
  <!-- Latest compiled and minified JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>

  <!-- (Optional) Latest compiled and minified JavaScript translation files -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>
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
{{-- Dynamic field for qualification --}}

<script>
  $(document).ready(function(){
    var i=1;
    $('#add-qualification').click(function(){
      i++;
      var html = '';
      html += '<div class="pt-1" id="qualification-row'+i+'">';
      html += '<hr>';
      html += '<div class="form-group row">';
      html += '<label class="col-md-2 form-control-label">Degree Title <span class="asterisk-blue">*</span></label>';
      html += '<div class="col-md-4">';
      html += '<input type="text" name="qua_degree[]" id="qua_degree" list="degrees" placeholder="Enter Degree Name"class="form-control {{ $errors->has('degrees') ? 'is-invalid' : '' }}" value="{{ old('degrees') }}"><datalist id="degrees"><?php foreach($degrees as $d) : ?><option value="<?php echo $d->name; ?>"><?php echo $d->name; ?></option><?php endforeach; ?></datalist>';
      html += '</div>';
      html += '<label class="col-md-2 form-control-label">University</label>';
      html += '<div class="col-md-4">';
      html += '<input type="text" name="qua_university[]" id="qua_university" list="universities" placeholder="Enter University Name"class="form-control {{ $errors->has('qua_university') ? 'is-invalid' : '' }}" value="{{ old('qua_university') }}"><datalist id="universities"><?php foreach($universities as $u) : ?><option value="<?php echo $u->name; ?>"><?php echo $u->name; ?></option><?php endforeach; ?></datalist>';
      html += '</div>';
      html += '</div>';
      html += '<div class="form-group row">';
      html += '<label class="col-md-2 form-control-label">Country</label>';
      html += '<div class="col-md-4">';
      html += '<select  name="qua_country[]" class="form-control "> <option>Select Country</option><?php foreach($countries as $c) : ?><option value="<?php echo $c->nicename; ?>"><?php echo $c->nicename; ?></option><?php endforeach; ?></select>';
      html += '</div>';
      html += '<label class="col-md-2 form-control-label">Graduation Year</label>';
      html += '<div class="col-md-4">';
      html += '<select  name="qua_graduation_year[]" class="form-control"> <option value="">Select Year</option><?php foreach($years as $year) : ?><option value="<?php echo $year; ?>"><?php echo $year; ?></option><?php endforeach; ?></select>';
      html += '</div>';
      html += '</div>';
      html += '<div class="row">';
      html += '<div class="col-md-4"></div>';
      html += '<div class="col-md-4 text-center form-control-label">';
      html += '<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button>';
      html += '</div>';
      html += '<div class="col-md-4"></div>';
      html += '</div>';
      html += '</div>';
      $('#dynamic_field_qualification').append(html);
    });

    $(document).on('click', '.btn_remove', function(){
      var button_id = $(this).attr("id");
      $('#qualification-row'+button_id+'').remove();
    });
  });
</script>
{{-- end of Dynamic field for qualification --}}

{{-- Dynamic field for Certification --}}
<script>
  $(document).ready(function(){
    var i=1;
    $('#add-certification').click(function(){
      i++;
      var html = '';
      html += '<div class="pt-1" id="certification-row'+i+'">';
      html += '<hr>';
      html += '<div class="form-group row">';
      html += '<label class="col-md-2 form-control-label">Degree Title <span class="asterisk-blue">*</span></label>';
      html += '<div class="col-md-4">';
      html += '<input type="text" name="cer_degree[]" id="cer_degree" list="degrees" placeholder="Enter Certification Name"class="form-control {{ $errors->has('cer_degree') ? 'is-invalid' : '' }}" value="{{ old('cer_degree') }}"><datalist id="degrees"><?php foreach($degrees as $d) : ?><option value="<?php echo $d->name; ?>"><?php echo $d->name; ?></option><?php endforeach; ?></datalist>';
      html += '</div>';
      html += '<label class="col-md-2 form-control-label">University</label>';
      html += '<div class="col-md-4">';
      html += '<input type="text" name="cer_university[]" id="cer_university" list="universities" placeholder="Enter Institution Name"class="form-control {{ $errors->has('cer_university') ? 'is-invalid' : '' }}" value="{{ old('cer_university') }}"><datalist id="universities"><?php foreach($universities as $u) : ?><option value="<?php echo $u->name; ?>"><?php echo $u->name; ?></option><?php endforeach; ?></datalist>';
      html += '</div>';
      html += '</div>';
      html += '<div class="form-group row">';
      html += '<label class="col-md-2 form-control-label">Country</label>';
      html += '<div class="col-md-4">';
      html += '<select  name="cer_country[]" class="form-control "> <option>Select Country</option><?php foreach($countries as $c) : ?><option value="<?php echo $c->nicename; ?>"><?php echo $c->nicename; ?></option><?php endforeach; ?></select>';
      html += '</div>';
      html += '<label class="col-md-2 form-control-label">Graduation Year</label>';
      html += '<div class="col-md-4">';
      html += '<select  name="cer_graduation_year[]" class="form-control"> <option value="">Select Year</option><?php foreach($years as $year) : ?><option value="<?php echo $year; ?>"><?php echo $year; ?></option><?php endforeach; ?></select>';
      html += '</div>';
      html += '</div>';
      html += '<div class="row">';
      html += '<div class="col-md-4"></div>';
      html += '<div class="col-md-4 text-center form-control-label">';
      html += '<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button>';
      html += '</div>';
      html += '<div class="col-md-4"></div>';
      html += '</div>';
      html += '</div>';
      $('#dynamic_field_certification').append(html);
    });

    $(document).on('click', '.btn_remove', function(){
      var button_id = $(this).attr("id");
      $('#certification-row'+button_id+'').remove();
    });
  });
</script>
{{-- end of Dynamic field for Certification --}}

<script>
  document.getElementById("edit-doctor").onkeypress = function(e) {
    var key = e.charCode || e.keyCode || 0;
    if (key == 13) {
      e.preventDefault();
    }
  }
</script>
<script>
    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
    function initMap() {
      var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: <?php echo $doctor->lat; ?>, lng: <?php echo $doctor->lng; ?>},
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
        position: new google.maps.LatLng(<?php echo $doctor->lat; ?>, <?php echo $doctor->lng; ?>),
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

        // console.log('locality',locality);
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
