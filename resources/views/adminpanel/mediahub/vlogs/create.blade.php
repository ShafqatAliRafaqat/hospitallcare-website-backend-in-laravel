@extends('adminpanel.layout')
@section('styles')
  
@endsection
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      <!-- Form Elements -->
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Create Vlog</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('vlogs.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Title <span class="asterisk">*</span></label>
                  <div class="col-md-9">
                        <input type="text" name="title" placeholder="Video Title"
                        class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" value="{{ old('title') }}" required>

                      @if($errors->has('title'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('title') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Video Link <span class="asterisk">*</span></label>
                  <div class="col-md-9">
                        <input type="url" name="link" placeholder="Video link"
                        class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}" value="{{ old('link') }}" required>

                      @if($errors->has('link'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('link') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Video Desciption <span class="asterisk">*</span></label>
                  <div class="col-md-9">
                      <textarea placeholder="Enter Details" class="form-control tiny" name="description" id="" cols="30" rows="10" >{{ old('description') }}</textarea>
                      @if($errors->has('description'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('description') }}</div>
                      @endif
                  </div>
                </div>
                <hr>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Meta Title</label>
                  <div class="col-md-9">
                      <input type="text" name="meta_title" placeholder="SEO Meta Title"
                      class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" value="{{ old('meta_title') }}" required>

                    @if($errors->has('meta_title'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_title') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Meta Description</label>
                  <div class="col-md-9">
                      <input type="text" name="meta_description" placeholder="SEO Meta Description"
                      class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" value="{{ old('meta_description') }}" required>

                    @if($errors->has('meta_description'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_description') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">URL</label>
                  <div class="col-md-9">
                      <input type="text" name="url" placeholder="SEO URL" 
                      class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" value="{{ old('url') }}" required>
                    @if($errors->has('url'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('url') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Active</label>
                  <div class="col-md-9">
                    <div class="custom-control custom-checkbox">
                      <input id="is_active" value="1" type="checkbox" name="is_active" class="custom-control-input">
                      <label for="is_active" class="custom-control-label">Check to Active the Vlog</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label">Position At Home Page</label>
                        <div class="col-md-2">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input id="bottom" type="radio" value="0" name="position" class="custom-control-input" {{ old('position') == 0 ? 'checked':'' }}>
                                    <label for="bottom" class="custom-control-label">Bottom</label>
                                  </div>
                                </div>
                                <div class="col-md-2">
                                  <div class="custom-control custom-radio custom-control-inline">
                                    <input id="center" type="radio" value="1" name="position" class="custom-control-input" {{ old('position') == 1 ? 'checked':'' }}>
                                    <label for="center" class="custom-control-label">Center</label>
                                  </div>
                                </div>
                                <div class="col-md-2">
                                  <div class="custom-control custom-radio custom-control-inline">
                                <input id="top" type="radio" value="2" name="position" class="custom-control-input" {{ old('position') == 2 ? 'checked':'' }}>
                            <label for="top" class="custom-control-label">Top</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-9 ml-auto">
                    <button type="submit" class="btn btn-primary">Save Vlog</button>
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
