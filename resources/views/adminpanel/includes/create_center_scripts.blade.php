@section('scripts')
<script src="{{ asset('backend/js/select2-develop/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/fileupload.js') }}" ></script>
<script src="{{ asset('backend/js/fileupload2.js') }}" ></script>
<script src="{{ asset('backend/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/tinymce-config.js') }}" ></script>
<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="{{ asset('backend/js/bootstrap-inputmask.min.js') }}"></script>
<script src="{{asset('backend/js/bootstrap-imageupload.js')}}"></script>
<script>
  $('#center-form').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
      e.preventDefault();
      return false;
    }
  });
</script>
<script type="text/javascript">
$(document).on('change','#city', function(){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var city_id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getArea') }}",
      data: { city_id : city_id},
      success: function(response){
          $('#area').html(response);
          // $('#area').addClass('selectpicker');
          // $('#area').attr('data-live-search', 'true');
          $('#area').selectpicker('refresh');
      }
    });
  });
</script>
<script>
  var $imageupload = $('.imageupload');
  $imageupload.imageupload();
</script>
<script>
  $(document).ready(function(){
   var i=1;
   $('#add').click(function(){
    i++;
    var html = '';
    html += '<div class="form-group row" id="row'+i+'">';
    html += '<div class="col-md-2 form-control-label">Select Treatment No. '+i+'<span class="asterisk">*</span></div>';
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
