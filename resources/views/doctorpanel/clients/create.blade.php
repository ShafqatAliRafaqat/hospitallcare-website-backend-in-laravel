@extends('doctorpanel.layout')
@section('title','Create Client | HospitALL')
@section('styles')
  <link rel="stylesheet" href="{{ asset('backend/css/fileupload.css') }}">
<!--   <link rel="stylesheet" href="{{ asset('backend/js/masking/inputmask.js') }}">
  <link rel="stylesheet" href="{{ asset('backend/js/mask/jquery.mask.js') }}"> -->
@endsection
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      @include('adminpanel.notification')
      <!-- Form Elements -->
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Create Client</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('doctorclients.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="notes" value="<p>What:</p>">
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Patient Name <span class="asterisk">*</span></label>
                  <div class="col-md-10">
                        <input type="text" name="name" placeholder="Patient name"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" required>

                      @if($errors->has('name'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('name') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Email</label>
                  <div class="col-md-4">
                        <input type="text" name="email" placeholder="Email"
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}">

                      @if($errors->has('email'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('email') }}</div>
                      @endif
                  </div>
                  <label class="col-md-2 form-control-label">Phone <span class="asterisk">*</span></label>
                  <div class="col-md-4">
                        <input type="text" name="phone" data-mask="9999-9999999" placeholder="Phone"
                        class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ old('phone') }}" required>

                      @if($errors->has('phone'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('phone') }}</div>
                      @endif
                  </div>
                </div>
                <ul class="nav nav-tabs customer-nav mb-3" role="tablist">
                        <li class="nav-item">
                                <a class="nav-link active" href="#moredetail-tab" role="tab" data-toggle="tab">Details</a>
                        </li>
                        <li class="nav-item">
                                <a class="nav-link" href="#allergies-tab" role="tab" data-toggle="tab">Allergies</a>
                        </li>
                        <li class="nav-item">
                                <a class="nav-link" href="#riskfactor-tab" role="tab" data-toggle="tab">Risk Factor</a>
                        </li>

                      </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane pt-3 in active" id="moredetail-tab">
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Address</label>
                  <div class="col-md-4">
                    <input type="text" name="address" placeholder="address" id="autocomplete" onFocus="geolocate()"
                      class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" value="{{ old('address') }}">
                    <input type="text" id="locality" class="city" hidden name="city" value=""/>
                      @if($errors->has('address'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('address') }}</div>
                      @endif
                  </div>
                  <label class="col-md-2 form-control-label">Blood Group</label>
                  <div class="col-md-4">
                    <select name="blood_group_id" id="" class="form-control selectpicker">
                        <option value="">Select Blood Group</option>
                        @foreach($blood_groups as $b)
                            <option value="{{ $b->id }}" {{ old('blood_group_id') == $b->id ? 'selected': '' }}>{{ $b->name }}</option>
                        @endforeach
                        </select>
                        @if($errors->has('blood_group_id'))
                            <div class="invalid-feedback ml-3">{{ $errors->first('blood_group_id') }}</div>
                        @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Marital Status <span class="asterisk">*</span></label>
                  <div class="col-md-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="unmarried" type="radio" value="0" name="marital_status" class="custom-control-input" {{ old('name') == 0 ? 'checked':'' }} >
                      <label for="unmarried" class="custom-control-label">Unmarried</label>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="married" type="radio" value="1" name="marital_status" class="custom-control-input" {{ old('name') == 1 ? 'checked':'' }}>
                      <label for="married" class="custom-control-label">Married</label>
                    </div>
                  </div>
                  <label class="col-md-2 form-control-label">Gender <span class="asterisk">*</span></label>
                  <div class="col-md-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="male" type="radio" value="0" name="gender" class="custom-control-input" {{ old('name') == 0 ? 'checked':'' }}>
                      <label for="male" class="custom-control-label">Male</label>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="female" type="radio" value="1" name="gender" class="custom-control-input" {{ old('name') == 1 ? 'checked':'' }}>
                      <label for="female" class="custom-control-label">Female</label>
                    </div>
                  </div>
                 
                </div>
                <div class="row form-group">
                <label class="col-md-2 form-control-label">Age</label>
                  <div class="col-md-2">
                        <input type="text" name="age" placeholder="age"
                        class="form-control {{ $errors->has('age') ? 'is-invalid' : '' }}" value="{{ old('age') }}">

                      @if($errors->has('age'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('age') }}</div>
                      @endif
                  </div>

                  <label class="col-md-1 form-control-label">Weight</label>
                  <div class="col-md-2">
                        <input type="text" name="weight" placeholder="weight"
                        class="form-control {{ $errors->has('weight') ? 'is-invalid' : '' }}" value="{{ old('weight') }}">

                      @if($errors->has('weight'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('weight') }}</div>
                      @endif
                  </div>
                  <label class="col-md-2 form-control-label">Height</label>
                  <div class="col-md-2">
                        <input type="text" name="height" placeholder="height"
                        class="form-control {{ $errors->has('height') ? 'is-invalid' : '' }}" value="{{ old('height') }}">

                      @if($errors->has('height'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('height') }}</div>
                      @endif
                  </div>
                </div>
                </div>
                <div role="tabpanel" class="tab-pane fade pt-3 in" id="allergies-tab">
            <div id="dynamic_field_allergies_notes">
                <div class="form-group row">
                <label class="col-md-2 form-control-label">Allergies</label>
                  <div class="col-md-8">
                  <input type="text" placeholder="Enter Details" class="form-control" name="allergies_notes[]" id="" />
                    @if($errors->has('allergies_notes'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('allergies_notes') }}</div>
                    @endif
                  </div>
                  <div class="col-md-2 form-control-label">
                    <button type="button" name="add_allergies_notes" id="add_allergies_notes" class="btn btn-success">Add More</button>
                  </div>
                </div>
                </div>  
            </div>
            <div role="tabpanel" class="tab-pane fade pt-3 in" id="riskfactor-tab">
            <div id="dynamic_field_riskfactor_notes">
                <div class="form-group row">
                <label class="col-md-2 form-control-label">Risk Factor</label>
                  <div class="col-md-8">
                  <input type="text" placeholder="Enter Details" class="form-control" name="riskfactor_notes[]" id="" />
                    @if($errors->has('riskfactor_notes'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('riskfactor_notes') }}</div>
                    @endif
                  </div>
                  <div class="col-md-2 form-control-label">
                    <button type="button" name="add_riskfactor_notes" id="add_riskfactor_notes" class="btn btn-success">Add More</button>
                  </div>
                </div>
                </div>   
            </div>
            <hr>
        </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Notes </label>
                  <div class="col-md-10">
                      <textarea placeholder="Enter Details" class="form-control" name="doctor_notes" placeholder="Enter Notes" cols="3" rows="2"></textarea>
                      @if($errors->has('notes'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('notes') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-10 ml-auto">
                    <button type="submit" class="btn btn-primary">Save Patient Profile</button>
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
<script src="{{ asset('backend/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/tinymce-config.js') }}" ></script>
<script src="{{ asset('backend/js/fileupload.js') }}" ></script>
<script src="{{ asset('backend/js/bootstrap-inputmask.min.js') }}"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>

<script>

// dynamic input fields for Allergies Notes
$(document).ready(function(){
  var i=0;
  $('#add_allergies_notes').click(function(){
    i++;
    var html = '';
  html += '<div class="form-group row" id="allergies_notes_'+i+'">';
  html += '<label class="col-md-2 form-control-label">Allergies '+i+'</label>';
  html += '<div class="col-md-8">';
  html += '<input type="text" placeholder="Enter Details" class="form-control" name="allergies_notes[]" id="" />';
  html += '</div>';
  html += '<div class="col-md-2 form-control-label"> <button type="button" name="remove" id="allergies_notes_'+i+'" class="btn btn-danger btn_remove">X</button></div>';
  html += '</div>';
  $('#dynamic_field_allergies_notes').append(html);
  });

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id");
    $('#'+button_id+'').remove();
  });
});
// end of dynamic fields for Allergies Notes
</script>
<script>
// dynamic input fields for Risk factor Notes
$(document).ready(function(){
  var i=0;
  $('#add_riskfactor_notes').click(function(){
    i++;
    var html = '';
  html += '<div class="form-group row" id="riskfactor_notes_'+i+'">';
  html += '<label class="col-md-2 form-control-label">Risk Factor '+i+'</label>';
  html += '<div class="col-md-8">';
  html += '<input type="text" placeholder="Enter Details" class="form-control" name="riskfactor_notes[]" id="" />';
  html += '</div>';
  html += '<div class="col-md-2 form-control-label"> <button type="button" name="remove" id="riskfactor_notes_'+i+'" class="btn btn-danger btn_remove">X</button></div>';
  html += '</div>';
  $('#dynamic_field_riskfactor_notes').append(html);
  });

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id");
    $('#'+button_id+'').remove();
  });
});
// end of dynamic fields for Risk factor Notes
</script>
<script>
var placeSearch, autocomplete;

var componentForm = {
  // street_number: 'short_name',
  // route: 'long_name',
  locality: 'long_name',
  // administrative_area_level_1: 'short_name',
  // country: 'long_name',
  // postal_code: 'short_name'
};

function initAutocomplete() {
  // Create the autocomplete object, restricting the search predictions to
  // geographical location types.
  autocomplete = new google.maps.places.Autocomplete(
      document.getElementById('autocomplete'), {types: ['geocode']});

  // Avoid paying for data that you don't need by restricting the set of
  // place fields that are returned to just the address components.
  autocomplete.setFields(['address_component']);

  // When the user selects an address from the drop-down, populate the
  // address fields in the form.
  autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
  // Get the place details from the autocomplete object.
  var place = autocomplete.getPlace();

  for (var component in componentForm) {
    document.getElementById(component).value = '';
  }

  // Get each component of the address from the place details,
  // and then fill-in the corresponding field on the form.
  for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
    if (componentForm[addressType]) {
      var val = place.address_components[i][componentForm[addressType]];
      document.getElementById(addressType).value = val;
    }
  }
}

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      var circle = new google.maps.Circle(
          {center: geolocation, radius: position.coords.accuracy});
      autocomplete.setBounds(circle.getBounds());
    });
  }
}
// $(".city").val(locality);
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD9RHZgUXffbQmvczfgC8CeNKfm6IYMAJQ&libraries=places&callback=initAutocomplete" async defer></script>
@endsection
