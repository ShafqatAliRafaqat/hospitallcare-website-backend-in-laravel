@extends('adminpanel.layout')
@section('title','Edit Whitepaper')
@section('styles')
  <link rel="stylesheet" href="{{ asset('backend/css/fileupload.css') }}">
@endsection
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      <!-- Form Elements -->
  @include('adminpanel.notification')
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Create Whitepaper</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('whitepaper.update', 3) }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Title</label>
                  <div class="col-md-9">
                        <input type="text" name="title" placeholder="Whitepaper Title"
                        class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" value="{{ $whitepaper->title }}" required>

                      @if($errors->has('title'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('title') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Description</label>
                  <div class="col-md-9">
                        <input type="text" name="description" placeholder="Whitepaper Description"
                        class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" value="{{ $whitepaper->description }}" required>
                      @if($errors->has('description'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('description') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">PDF File</label>
                  <div class="col-md-6">
                    <span class="btn btn-success fileinput-button">
                        <span>Select Attachment</span>
                        <input type="file" name="file" id="files" class="form-control {{ $errors->has('file') ? 'is-invalid' : '' }}"><br />
                    </span>
                    <output id="Filelist"></output>
                      @if($errors->has('file'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('file') }}</div>
                      @endif
                  </div>
                  <div class="col-md-3">
                    <a class="btn btn-warning" href="{{ asset('backend/uploads/whitepapers/'.$whitepaper->file) }}">View Previous</a>
                  </div>
                </div>
                <hr>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Meta Title</label>
                  <div class="col-md-9">
                      <input type="text" name="meta_title" placeholder="SEO Meta Title"
                      class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" value="{{ $whitepaper->meta_title }}">

                    @if($errors->has('meta_title'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_title') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Meta Description</label>
                  <div class="col-md-9">
                      <input type="text" name="meta_description" placeholder="SEO Meta Description"
                      class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" value="{{ $whitepaper->meta_description }}">

                    @if($errors->has('meta_description'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_description') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">URL</label>
                  <div class="col-md-9">
                      <input type="text" name="url" placeholder="Enter URL"
                      class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" value="{{ $whitepaper->url }}">
                    @if($errors->has('url'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('url') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Active</label>
                  <div class="col-md-9">
                    <div class="custom-control custom-checkbox">
                      <input id="is_active" value="1" type="checkbox" name="is_active" class="custom-control-input" {{$whitepaper->is_active == 1 ? 'checked' : ''}}>
                      <label for="is_active" class="custom-control-label">Check to Active the Whitepaper</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-9 ml-auto">
                    <button type="submit" class="btn btn-primary">Update Whitepaper</button>
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
