@extends('adminpanel.layout')
@section('title','Edit Area | HospitALL')
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      <!-- Form Elements -->
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Edit Area</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('city.update', $area->id) }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Area Name</label>
                  <div class="col-md-9">
                    <input type="text"  name="name" value="{{ $area->name }}" placeholder="Area Name"  class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" required>
                      @if($errors->has('name'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('name') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Slug</label>
                  <div class="col-md-9">
                    <input type="text"  name="slug" value="{{ $area->slug }}" placeholder="Slug"
                        class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" required>
                      @if($errors->has('slug'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('slug') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Active</label>
                  <div class="col-md-9">
                    <div class="custom-control custom-checkbox">
                      <input id="is_active" {{ $area->is_active == 1 ? 'checked' : '' }} value="1" type="checkbox" name="is_active" class="custom-control-input">
                      <label for="is_active" class="custom-control-label">Check to Active</label>
                    </div>
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-10 ml-auto">
                    <button type="submit" class="btn btn-primary">Update</button>
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
@endsection
