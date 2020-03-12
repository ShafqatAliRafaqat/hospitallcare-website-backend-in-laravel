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
            <h3 class="h6 text-uppercase mb-0">Edit Blog</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('blogs.update',$blog->id) }}" enctype="multipart/form-data">
                @csrf @method('put')
                <div class="form-group row">
                  <div class="col-md-3 form-control-label">Select Category <span class="asterisk">*</span></div>
                  <div class="col-md-9">
                    <select name="category_id" id="" class="form-control selectpicker" data-live-search="true" required>
                      <option value="">Select Category</option>
                      @foreach($categories as $data)
                        <option value="{{ $data->id }}" {{ ($data->id == $blog->category_id)? 'selected':''}}>{{ $data->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Title <span class="asterisk">*</span></label>
                  <div class="col-md-9">
                        <input type="text" name="title" placeholder="Blog Title"
                        class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" value="{{ $blog->title }}" required>

                      @if($errors->has('title'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('title') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row pt-3">
                  <label class="col-md-2 form-control-label">Picture</label>
                  <div class="col-md-6">
                    <span class="btn btn-success fileinput-button">
                        <span>Select Attachment</span>
                        <input type="file" name="picture[]" id="files" multiple class="form-control {{ $errors->has('picture') ? 'is-invalid' : '' }}"><br />
                    </span>
                    <output id="Filelist"></output>
                    @if($errors->has('picture'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('picture') }}</div>
                    @endif
                  </div>
                  <div class="col-md-4" style="display: flex;">
                    @if(count($blog_images)>0)
                      @foreach($blog_images as $image)
                        <div>
                          <div class="img-wrap">
                            <span class="close">×</span>
                            <input type="hidden" name="picture[]"  value="{{$image->picture}}">
                            <img src="{{ asset('backend/uploads/blogs/'.$image->picture) }}" class="img-fluid" alt="{{ $errors->first('treatment_name') }}" height="100" width="100">
                          </div>
                        </div>
                      @endforeach
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Desciption <span class="asterisk">*</span></label>
                  <div class="col-md-9">
                      <textarea placeholder="Enter Details" class="form-control tiny" name="description" id="" cols="30" rows="10">{{ $blog->description }}</textarea>
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
                      class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" value="{{ $blog->meta_title }}">

                    @if($errors->has('meta_title'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_title') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Meta Description</label>
                  <div class="col-md-9">
                      <input type="text" name="meta_description" placeholder="SEO Meta Description"
                      class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" value="{{ $blog->meta_description }}">

                    @if($errors->has('meta_description'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_description') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">URL</label>
                  <div class="col-md-9">
                      <input type="text" name="url" placeholder="SEO URL" 
                      class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" value="{{ $blog->url }}">
                    @if($errors->has('url'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('url') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Active</label>
                  <div class="col-md-9">
                    <div class="custom-control custom-checkbox">
                      <input id="is_active" value="1" type="checkbox" {{ $blog->is_active == 1 ? 'checked':'' }} name="is_active" class="custom-control-input">
                      <label for="is_active" class="custom-control-label">Check to Active the Blog</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label">Position At Page</label>
                        <div class="col-md-2">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input id="bottom" type="radio" value="0" name="position" class="custom-control-input" {{ $blog->position == 0 ? 'checked':'' }}>
                                    <label for="bottom" class="custom-control-label">Bottom</label>
                                  </div>
                                </div>
                                <div class="col-md-2">
                                  <div class="custom-control custom-radio custom-control-inline">
                                    <input id="center" type="radio" value="1" name="position" class="custom-control-input" {{ $blog->position == 1 ? 'checked':'' }}>
                                    <label for="center" class="custom-control-label">Center</label>
                                  </div>
                                </div>
                                <div class="col-md-2">
                                  <div class="custom-control custom-radio custom-control-inline">
                                <input id="top" type="radio" value="2" name="position" class="custom-control-input" {{ $blog->position == 2 ? 'checked':'' }}>
                            <label for="top" class="custom-control-label">Top</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-9 ml-auto">
                    <button type="submit" class="btn btn-primary">Update Blog</button>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>
@endsection

