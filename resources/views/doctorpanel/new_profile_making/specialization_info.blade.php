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
            <form class="form-horizontal" method="post" id="doctor-form" action="{{ route('doctor_specialization_update') }}" enctype="multipart/form-data">
              @csrf
              @method('post')
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Specialization <span class="asterisk">*</span></div>
                  <div class="col-md-10">
                    <select name="specializations[]" id="specializationselect" class="form-control selectpicker" data-live-search="true" multiple required>
                      @foreach($specializations as $s)
                      <option value="{{ $s->id }}">{{ $s->name }}</option>
                      @endforeach
                    </select>
                    @if($errors->has('specializations'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('specializations') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                <div class="col-md-2 form-control-label">Select Procedure <span class="asterisk">*</span></div>
                  <div class="col-md-10">
                    <select name="procedures[]" id="procedure" class="form-control selectpicker" multiple required>
                      <option value="">Select Procedure</option>
                    </select>
                    @if($errors->has('procedures'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('procedures') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Center <span class="asterisk">*</span></div>
                  <div class="col-md-10">
                    <select name="centers[]" id="centers" class="form-control selectpicker" data-live-search="true" multiple required>
                      @foreach($centers as $c)
                      <option value="{{ $c->id }}">{{ $c->center_name }}</option>
                      @endforeach
                    </select>
                    @if($errors->has('centers'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('centers') }}</div>
                    @endif
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

@endsection
