@extends('adminpanel.layout')
@section('title','Edit Diagnostic Test | HospitALL')
@section('styles')
<link rel="stylesheet" href="{{ asset('backend/css/fileupload.css') }}">
@endsection
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      <!-- Form Elements -->
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Edit Diagnostics</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('diagnostics.update', $diagnostics->id) }}" enctype="multipart/form-data">
              @csrf
              @method('PUT')
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Diagnostic Name <span class="asterisk">*</span></label>
                  <div class="col-md-10">
                      <input type="text" name="name" placeholder="Diagnostic Name" list="diagnostics-ajax-list" id="diagnostic_input"
                      class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{$diagnostics->name}}" required>
                      @if($errors->has('name'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('name') }}</div>
                    @endif
                     <datalist id="diagnostics-ajax-list">
                    </datalist>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Description <span class="asterisk">*</span></label>
                  <div class="col-md-10">
                      <input type="text" name="description" placeholder="Description"
                      class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" value="{{$diagnostics->description}}" required>

                      @if($errors->has('description'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('description') }}</div>
                    @endif
                  </div>
                </div>
                <hr>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Active</label>
                  <div class="col-md-10">
                    <div class="custom-control custom-checkbox">
                    <input id="is_active" value="1" type="checkbox" name="is_active"
                      class="custom-control-input" {{ $diagnostics->is_active == 1 ? 'checked':'' }}>
                      <label for="is_active" class="custom-control-label">Check to Active the Diagnostics</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Common</label>
                  <div class="col-md-10">
                    <div class="custom-control custom-checkbox">
                    <input id="is_common" value="1" type="checkbox" name="is_common"
                      class="custom-control-input" {{ $diagnostics->is_common == 1 ? 'checked':'' }}>
                      <label for="is_common" class="custom-control-label">Check to add in Common Diagnostics</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-10 ml-auto">
                    <button type="submit" class="btn btn-primary">Save Diagnostics</button>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-maxlength/1.7.0/bootstrap-maxlength.min.js"></script>
<script>
  $(document).on('keyup','#diagnostic_input', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  var diagnostic_name  = $("#diagnostic_input").val();
  console.log(diagnostic_name);
  $.ajax({
      type:'post',
      url:"{{ route('diagnostic-live-search') }}",
      data: { diagnostic_name : diagnostic_name},
      success: function(response){
        $('#diagnostics-ajax-list').html(response);
      }
    });
  });
  </script>
@endsection
