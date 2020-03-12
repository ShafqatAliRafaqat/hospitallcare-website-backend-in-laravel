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
            <h3 class="h6 text-uppercase mb-0">Update Faq</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('faqs.update', $faq->id) }}"
              enctype="multipart/form-data">
                @csrf @method('put')
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Select Center</label>
                  <div class="col-md-9">
                    <select name="treatment_id" id="" class="form-control {{ $errors->has('treatment_id') ? 'is-invalid' : '' }}">
                      <option value="">Select Center</option>
                      @foreach($treatments as $t)
                      <option value="{{ $t->id }}"
                        {{ ($faq->treatment_id == $t->id) ? 'selected' : '' }}>
                        {{ $t->center_name }}
                      </option>
                      @endforeach
                    </select>
                    @if($errors->has('treatment_id'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('treatment_id') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Title</label>
                  <div class="col-md-9">
                        <input type="text" name="title" placeholder="Title"
                        class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" value="{{ $faq->title }}" required>

                      @if($errors->has('title'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('title') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Picture</label>
                  <div class="col-md-6">
                      <span class="btn btn-success fileinput-button">
                        <span>Select Attachment</span>
                        <input type="file" name="picture" id="files" class="form-control {{ $errors->has('picture') ? 'is-invalid' : '' }}"><br />
                    </span>
                    <output id="Filelist"></output>
                      @if($errors->has('picture'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('picture') }}</div>
                      @endif
                  </div>
                  <div class="col-md-3">
                    <img src="{{ asset('backend/uploads/faqs/'.$faq->picture) }}" class="img-fluid" alt="{{ $errors->first('picture') }}" height="100" width="100">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Article</label>
                  <div class="col-md-9">
                      <textarea placeholder="Enter Details" class="form-control tiny" name="article" id="" cols="30" rows="10">{!! $faq->article !!}</textarea>
                      @if($errors->has('article'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('article') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Active</label>
                  <div class="col-md-9">
                    <div class="custom-control custom-checkbox">
                      <input id="is_active" value="1" type="checkbox" name="is_active"
                      class="custom-control-input" {{ $faq->is_active == 1 ? 'checked':'' }}>
                      <label for="is_active" class="custom-control-label">Check to Active the procedure</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-9 ml-auto">
                    <a href="{{ route('articles.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update changes</button>
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
