@extends('adminpanel.layout')
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
            <h3 class="h6 text-uppercase mb-0">Create Video</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('videos.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                  <div class="col-md-3 form-control-label">Select Center</div>
                  <div class="col-md-9">
                    <select name="center_id" id="" class="form-control">
                      <option value="0">Select Center</option>
                      @foreach($centers as $center)
                        <option value="{{ $center->id }}">{{ $center->center_name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Title</label>
                  <div class="col-md-9">
                        <input type="text" name="title" placeholder="Video Title"
                        class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" value="{{ old('title') }}" required>

                      @if($errors->has('title'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('title') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Video Link</label>
                  <div class="col-md-9">
                        <input type="text" name="link" placeholder="Video link"
                        class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}" value="{{ old('link') }}" required>

                      @if($errors->has('link'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('link') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Picture</label>
                  <div class="col-md-9">
                    <span class="btn btn-success fileinput-button">
                        <span>Select Attachment</span>
                        <input type="file" name="picture" id="files" class="form-control {{ $errors->has('picture') ? 'is-invalid' : '' }}"><br />
                    </span>
                    <output id="Filelist"></output>
                      @if($errors->has('picture'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('picture') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Category</label>
                  <div class="col-md-9">
                        <input type="text" name="category" placeholder="Category"
                        class="form-control {{ $errors->has('category') ? 'is-invalid' : '' }}" value="{{ old('category') }}" required>

                      @if($errors->has('category'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('category') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Source</label>
                  <div class="col-md-9">
                        <input type="text" name="source" placeholder="Source"
                        class="form-control {{ $errors->has('source') ? 'is-invalid' : '' }}" value="{{ old('source') }}" required>

                      @if($errors->has('source'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('source') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Video Desciption</label>
                  <div class="col-md-9">
                      <textarea placeholder="Enter Details" class="form-control tiny" name="article" id="" cols="30" rows="10">{{ old('article') }}</textarea>
                      @if($errors->has('article'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('article') }}</div>
                      @endif
                  </div>
                </div>
                <hr>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Meta Title</label>
                  <div class="col-md-9">
                      <input type="text" name="meta_title" placeholder="SEO Meta Title"
                      class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" value="{{ old('meta_title') }}">

                    @if($errors->has('meta_title'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_title') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Meta Description</label>
                  <div class="col-md-9">
                      <input type="text" name="meta_description" placeholder="SEO Meta Description"
                      class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" value="{{ old('meta_description') }}">

                    @if($errors->has('meta_description'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_description') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">URL</label>
                  <div class="col-md-9">
                      <input type="text" name="url" placeholder="Enter URL"
                      class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" value="{{ old('url') }}">
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
                      <label for="is_active" class="custom-control-label">Check to Active the Article</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-9 ml-auto">
                    <button type="submit" class="btn btn-primary">Save Article</button>
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
