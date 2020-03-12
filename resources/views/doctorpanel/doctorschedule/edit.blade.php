@extends('doctorpanel.layout')
@section('title', 'Edit Doctor | HospitALL')
@section('styles')
<link rel="stylesheet" href="{{ asset('backend/js/select2-develop/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/fileupload.css') }}">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      @include('doctorpanel.notification')
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0"> {{$center->center_name}}</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('doctorschedule.update', $center->id) }}" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <!-- Edit Fare and Discounts -->
              <div class="form-group row"><div class="col-md-12 text-center form-control-label">Fee and Discounts</div></div>
              <div class="form-group row">
                <div class="col-md-2 form-control-label">Fee</div>
                <input type="number" name="fare" class="col-md-3 form-control" placeholder="Fare" value="{{ $center_schedule[0]->fare }}">
                <div class="col-md-2 form-control-label">Discounted Fee</div>
                <input type="number" name="discount" class="col-md-3 form-control" placeholder="Discounted Fee" value="{{ $center_schedule[0]->discount }}">

              </div>
              <div class="form-group row">
                <div class="col-md-2 form-control-label">Appointment Duration (Mins)</div>
                <input type="number" name="appointment_duration" class="col-md-3 form-control" placeholder="Appointment Duration" value="{{ $center_schedule[0]->appointment_duration }}">
                @php
                $toggle = isset($center_schedule[0]) ? $center_schedule[0]->is_primary : NULL;
                @endphp
                  <div class="col-md-2 form-control-label">Primary Loc:</div>
                  <input type="checkbox" class="col-md-3 text-center" style="width: 100%;" name="is_primary" data-toggle="toggle" {{ $toggle == 1 ? 'checked':'' }} data-onstyle="dark" data-offstyle="light" data-style="border">
              </div>

                 <!-- Edit Center Schedule -->
                 <hr>
                <div class="form-group row"><div class="col-md-12 text-center form-control-label">Schedule</div></div>

                <?php $j = 0; ?>
                @foreach($center_schedule as $s)
                <div id="dynamic_for_add_time1">
              <div class="form-group row" id="add_time_for1{{$j+1}}">
                <div class="col-md-10">
                  <div class="row form-group">
                  <div class="col-md-2 form-control-label">Day From</div>
                  <div class="col-md-4">
                  @php $days = get_days(); @endphp
                    <select class="form-control " name=" day_from[]">

                    @foreach($days as $day)
                    <option value="{{$day}}" {{ ($day == $s->day_from) ? 'selected' : ''}}>{{$day}}</option>
                    @endforeach
                    </select>
                    @if($errors->has(' day_from'))
                      <div class="invalid-feedback ml-3">{{ $errors->first(' day_from') }}</div>
                    @endif
                  </div>
                  <div class="col-md-2 form-control-label">Day to</div>
                  <div class="col-md-4">
                  @php $days = get_days(); @endphp
                    <select class="form-control " name=" day_to[]">

                    @foreach($days as $day)
                    <option value="{{$day}}" {{ ($day == $s->day_to) ? 'selected' : ''}}>{{$day}}</option>
                    @endforeach
                    </select>
                  @if($errors->has(' day_to'))
                      <div class="invalid-feedback ml-3">{{ $errors->first(' day_to') }}</div>
                    @endif
                  </div>
                  </div>

                  <div class="form-group row">
                  <div class="col-md-2 form-control-label">Time From</div>
                  <div class="col-md-4">
                    <select class="form-control " name=" time_from[]"><?php echo get_times(); ?></select>
                    @if($errors->has(' time_from'))
                      <div class="invalid-feedback ml-3">{{ $errors->first(' time_from') }}</div>
                    @endif
                  </div>

                  <div class="col-md-2 form-control-label">Time to</div>
                  <div class="col-md-4">
                    <select class="form-control " name="time_to[]"><?php echo get_times_to(); ?></select>
                    @if($errors->has('time_to'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('time_to') }}</div>
                    @endif
                  </div>
                  </div>
                  </div>
                <?php if ($j == 0) {?>
                  <div class="align-self-center">
                      <button type="button" name="add" id="add-time1" class="btn btn-info">Add More Time</button>
                    </div>
                  <?php } else {?>
                    <div class="align-self-center">
                        <button type="button" name="remove" id="add_time_for1{{$j+1}}" class="btn btn-danger btn_remove_time1">Remove Time</button>
                    </div>
                  <?php }?>
              </div>
              </div>
              <?php $j++;?>
                  @endforeach
                  <div class="form-group row">
                  <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Update Doctor</button>
                  </div>
                </div>
                <hr>
                <!--END of-When There is One Schedule Added Already -->
            </form>
  </div>
    </div>
    </div>
    </div>
  </section>
</div>
@endsection
@section('scripts')
<script src="{{ asset('backend/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/tinymce-config.js') }}" ></script>
<script src="{{ asset('backend/js/fileupload.js') }}" ></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>
<script src="{{ asset('backend/js/bootstrap-inputmask.min.js') }}"></script>
<script>
$(document).ready(function(){
  var i=1;
  $('#add-time1').click(function(){
    i++;
  var html = '';
  html += '<div class="form-group row" id="row-time1'+i+'">';
  html += '<div class="col-md-10">';
  html += '<div class="row form-group">';
  html += '<div class="col-md-2 form-control-label">Day From</div>';
  html += '<div class="col-md-4"><?php $days = get_days();?>';
  html += '<select class="form-control " name=" day_from[]">';
  html += '<?php foreach($days as $day){?><option value="<?php echo $day ?>"><?php echo $day ?></option><?php }?>';
  html += '</select>';
  html += '</div>';
  html += '<div class="col-md-2 form-control-label">Day to</div>';
  html += '<div class="col-md-4">';
  html += '<select class="form-control " name=" day_to[]">';
  html += '<?php foreach($days as $day){?><option value="<?php echo $day ?>"><?php echo $day ?></option><?php }?>';
  html += '</select>';
  html += '</div>';
  html += '</div>';
  html += '<div class="form-group row">';
  html += '<div class="col-md-2 form-control-label">Time From</div>';
  html += '<div class="col-md-4">';
  html += '<select class="form-control " name=" time_from[]"><?php echo get_times(); ?></select>';
  html += '</div>';
  html += '<div class="col-md-2 form-control-label">Time to</div>';
  html += '<div class="col-md-4">';
  html += '<select class="form-control " name="time_to[]"><?php echo get_times_to(); ?></select>';
  html += '</div>';
  html += '</div>';
  html += '</div>';
  html += '<div class="align-self-center">';
  html += '<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">Remove Time</button>';
  html += '</div>';
  html += '</div>';
  html += '</div>';

  $('#dynamic_for_add_time1').append(html);
  });

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id");
    $('#row-time1'+button_id+'').remove();
  });
  $(document).on('click', '.btn_remove_time1', function(){
    var button_id = $(this).attr("id");
    $('#'+button_id+'').remove();
  });
});
</script>
<script>

var placeSearch, autocomplete;

var componentForm = {
  lng: 'long_name',
  lat: 'long_name',
};

function initAutocomplete() {
  autocomplete = new google.maps.places.Autocomplete(
      document.getElementById('autocomplete'), {types: ['geocode']});
  autocomplete.setFields(['address_component']);
  autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
  // Get the place details from the autocomplete object.
  var place = autocomplete.getPlace();

  for (var component in componentForm) {
    document.getElementById(component).value = '';
    document.getElementById(component).disabled = false;
  }
  for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
    if (componentForm[addressType]) {
      var val = place.address_components[i][componentForm[addressType]];
      document.getElementById(addressType).value = val;
    }
  }
}
function geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      $("#lat").val(geolocation.lat);
      $("#lng").val(geolocation.lng);

      var circle = new google.maps.Circle(
          {center: geolocation, radius: position.coords.accuracy});
      autocomplete.setBounds(circle.getBounds());
    });
  }
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBv_Pb3XYIUpJ9J7L6hmXhHQ8DaqoMN2UI&libraries=places&callback=initAutocomplete" async defer></script>
@endsection

