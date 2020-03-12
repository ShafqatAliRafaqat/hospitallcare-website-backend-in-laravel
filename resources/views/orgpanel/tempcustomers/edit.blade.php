@extends('orgpanel.layout')
@section('title','Edit Employee | HospitALL')
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
            <h3 class="h6 text-uppercase mb-0">Edit Employees</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('notuploaded.update', $employee->id) }}" enctype="multipart/form-data">
                @csrf @method('put')
                <input type="hidden" name="status_id" value="{{$no_contact->id}}">
                <input type="hidden" name="notes" value="<p>What:</p>">
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Employee Name <span class="asterisk">*</span></label>
                  <div class="col-md-10">
                        <input type="text" name="name" placeholder="Employee name"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $employee->name }}" required>

                      @if($errors->has('name'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('name') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Email</label>
                  <div class="col-md-4">
                        <input type="text" name="email" placeholder="Email"
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ $employee->email }}">

                      @if($errors->has('email'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('email') }}</div>
                      @endif
                  </div>
                  <label class="col-md-2 form-control-label">Phone <span class="asterisk">*</span></label>
                  <div class="col-md-4">
                        <input type="text" name="phone" data-mask="9999-9999999" placeholder="Phone"
                        class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ $employee->phone }}" required>

                      @if($errors->has('phone'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('phone') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-2 form-control-label" id="organization_id">Organization</div>
                  <div class="col-md-4">
                  <input type="text" name="organization_id" placeholder="{{$org->name}}"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{$org->name}}" readonly>
                  </div>
                  <div class="col-md-2 form-control-label" id= "employee_code">Employee Code <span class="asterisk">*</span></div>
                  <div class="col-md-4">
                  <input type="text" name="employee_code" placeholder="Enter Employee Code"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $employee->employee_code }}" required>
                        @if($errors->has('employee_code'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('employee_code') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Address</label>
                  <div class="col-md-4">
                        <input type="text" name="address" placeholder="address"
                        class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" value="{{ $employee->address }}">

                      @if($errors->has('address'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('address') }}</div>
                      @endif
                  </div>

                  <label class="col-md-2 form-control-label">Gender</label>
                  <div class="col-md-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="male" type="radio" value="0" name="gender" class="custom-control-input"
                      {{ $employee->gender == 0 ? 'checked':'' }}>
                      <label for="male" class="custom-control-label">Male</label>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="female" type="radio" value="1" name="gender" class="custom-control-input" {{ $employee->gender == 1 ? 'checked':'' }}>
                      <label for="female" class="custom-control-label">Female</label>
                    </div>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Marital Status</label>
                  <div class="col-md-1">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="unmarried" type="radio" value="0" name="marital_status" class="custom-control-input" {{ $employee->marital_status == 0 ? 'checked':'' }}>
                      <label for="unmarried" class="custom-control-label">Unmarried</label>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="married" type="radio" value="1" name="marital_status" class="custom-control-input" {{ $employee->marital_status == 1 ? 'checked':'' }}>
                      <label for="married" class="custom-control-label">married</label>
                    </div>
                  </div>

                  <label class="col-md-1 form-control-label">Age</label>
                  <div class="col-md-1">
                        <input type="text" name="age" placeholder="age"
                        class="form-control {{ $errors->has('age') ? 'is-invalid' : '' }}"
                        value="{{ $employee->age }}" >

                      @if($errors->has('age'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('age') }}</div>
                      @endif
                  </div>

                  <label class="col-md-1 form-control-label">Weight</label>
                  <div class="col-md-1">
                        <input type="text" name="weight" placeholder="weight"
                        class="form-control {{ $errors->has('weight') ? 'is-invalid' : '' }}"
                        value="{{ $employee->weight }}">

                      @if($errors->has('weight'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('weight') }}</div>
                      @endif
                  </div>
                  <label class="col-md-1 form-control-label">Height</label>
                  <div class="col-md-1">
                        <input type="text" name="height" placeholder="height"
                        class="form-control {{ $errors->has('height') ? 'is-invalid' : '' }}"
                        value="{{ $employee->height }}">

                      @if($errors->has('height'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('height') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Patient Owner</label>
                  <div class="col-md-10">
                        <input type="text" name="patient_coordinator_id" placeholder="{{ $owner }}"
                        class="form-control {{ $errors->has('patient_coordinator_id') ? 'is-invalid' : '' }}" value="{{ $owner }}" readonly>
                      @if($errors->has('patient_coordinator_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('patient_coordinator_id') }}</div>
                      @endif
                  </div>
                </div>

<!--                 <div class="form-group row">

                  <label class="col-md-2 form-control-label">Notes <span class="asterisk">*</span></label>
                  <div class="col-md-10">
                      <textarea placeholder="Enter Details" class="form-control tiny" name="notes" id="" cols="30" rows="5"><p>What:</p><p>When:</p><p>Why:</p></textarea>
                      @if($errors->has('notes'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('notes') }}</div>
                      @endif
                  </div>
                </div> -->

<!--                 <div class="form-group row">
                  <label class="col-md-2 form-control-label">Follow Up <span class="asterisk">*</span></label>
                  <div class="col-md-4">
                        <input type="date" name="next_contact_date" placeholder="next_contact_date"
                        class="form-control {{ $errors->has('next_contact_date') ? 'is-invalid' : '' }}" value="{{ old('next_contact_date') }}" required>

                      @if($errors->has('next_contact_date'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('next_contact_date') }}</div>
                      @endif
                  </div>
                  <label class="col-md-2 form-control-label">Attachments</label>
                  <div class="col-md-4">
                    <span class="btn btn-success fileinput-button">
                        <span>Select Attachment</span>
                        <input type="file" name="file" id="files" class="form-control {{ $errors->has('picture') ? 'is-invalid' : '' }}"><br />
                    </span>
                    <output id="Filelist"></output>
                      @if($errors->has('picture'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('picture') }}</div>
                      @endif
                  </div>
                </div> -->

                <div class="form-group row">
                  <div class="col-md-10 ml-auto">
                    <button type="submit" class="btn btn-primary">Update Employee</button>
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

  $(document).on('change','#treatment', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getTreatments') }}",
      data: { id : id},
      success: function(response){
        $('#procedure').html(response);
      }
    });
  });

  $(document).on('change','#procedure', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getCenters') }}",
      data: { id : id},
      success: function(response){
        $('#centers').html(response);
      }
    });
  });

  // $(document).on('change','#centers', function(){
  //   $.ajaxSetup({
  //       headers: {
  //           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //       }
  //   });
  //   var id = $(this).val();
  //   $.ajax({
  //     type:'post',
  //     url:"{{ route('getCenters') }}",
  //     data: { id : id},
  //     success: function(response){
  //       $('#treatment').html(response);
  //     }
  //   });
  // });
</script>

<script>
  //TREATMENT # 2 AJAX
  $(document).on('change','#treatment2', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getTreatments') }}",
      data: { id : id},
      success: function(response){
        $('#procedure2').html(response);
      }
    });
  });

  $(document).on('change','#procedure2', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getCenters') }}",
      data: { id : id},
      success: function(response){
        $('#centers2').html(response);
      }
    });
  });
</script>

<script>
  //TREATMENT # 3 AJAX
  $(document).on('change','#treatment3', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getTreatments') }}",
      data: { id : id},
      success: function(response){
        $('#procedure3').html(response);
      }
    });
  });

  $(document).on('change','#procedure3', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getCenters') }}",
      data: { id : id},
      success: function(response){
        $('#centers3').html(response);
      }
    });
  });

  $('#organization_id').on('change',function(){
        if( $(this).val()){
        $("#employee_code").show()
        }
        else{
        $("#employee_code").hide()
        }
    });
</script>
@endsection
