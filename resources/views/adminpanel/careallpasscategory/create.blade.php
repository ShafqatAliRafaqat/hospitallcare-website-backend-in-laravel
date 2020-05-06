@extends('adminpanel.layout')
@section('title', 'Create | CareALL Pass Categories')
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
            <h3 class="h6 text-uppercase mb-0">CareALL Pass Category</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('pass_categories.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Category Name</label>
                  <div class="col-md-10">
                        <input type="text" name="name" placeholder="Category name"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" required>

                      @if($errors->has('name'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('name') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Description</label>
                  <div class="col-md-10">
                    <textarea name="description"  class="form-control"  cols="30" rows="3"></textarea>
                      @if($errors->has('description'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('description') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Amount</label>
                  <div class="col-md-4">
                        <input type="number" name="amount" placeholder="Amount"
                        class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" value="{{ old('amount') }}" required>
                      @if($errors->has('amount'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('amount') }}</div>
                      @endif
                  </div>
                  <label class="col-md-2 form-control-label">Treatments</label>
                  <div class="col-md-4">
                        <input type="number" name="treatments" placeholder="Treatments"
                        class="form-control {{ $errors->has('treatments') ? 'is-invalid' : '' }}" value="{{ old('treatments') }}" required>
                      @if($errors->has('treatments'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('treatments') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                <label class="col-md-2 form-control-label">Diagnostics</label>
                  <div class="col-md-4">
                        <input type="number" name="diagnostics" placeholder="Diagnostics"
                        class="form-control {{ $errors->has('diagnostics') ? 'is-invalid' : '' }}" value="{{ old('diagnostics') }}" required>
                      @if($errors->has('diagnostics'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('diagnostics') }}</div>
                      @endif
                  </div>
                  <label class="col-md-2 form-control-label">Active</label>
                  <div class="col-md-">
                    <div class="custom-control custom-checkbox">
                      <input id="is_active" value="1" type="checkbox" name="is_active" class="custom-control-input">
                      <label for="is_active" class="custom-control-label">Check to Active the Status</label>
                    </div>
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-10 ml-auto">
                    <button type="submit" class="btn btn-primary">Save Category</button>
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
