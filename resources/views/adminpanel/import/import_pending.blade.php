@extends('adminpanel.layout')
@section('title','Import Pending Leads')
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
            <h3 class="h6 text-uppercase mb-0">Import Customers</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('pending-import') }}" enctype="multipart/form-data">
              @csrf
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Excel File</label>
                  <div class="col-md-9">
                    <span class="btn btn-success fileinput-button">
                        <span>Select Pending Lead's Excel File</span>
                        <input type="file" name="file" id="files" class="form-control {{ $errors->has('file') ? 'is-invalid' : '' }}"><br />
                    </span>
                    <output id="Filelist"></output>
                      @if($errors->has('file'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('file') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-9 ml-auto">
                    <button type="submit" class="btn btn-primary">Import</button>
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
@endsection
