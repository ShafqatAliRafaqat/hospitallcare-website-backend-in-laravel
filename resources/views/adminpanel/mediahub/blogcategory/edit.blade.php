@extends('adminpanel.layout')
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      <!-- Form Elements -->
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Edit Blog Category</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('blogcategory.update', $blog_category->id) }}" enctype="multipart/form-data">
                @csrf @method('put')
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Name <span class="asterisk">*</span></label>
                  <div class="col-md-9">
                        <input type="text" name="name" placeholder="Name"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $blog_category->name }}" required>

                      @if($errors->has('name'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('name') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Desciption <span class="asterisk">*</span></label>
                  <div class="col-md-9">
                      <textarea placeholder="Enter Details" class="form-control tiny" name="description" id="" cols="30" rows="10" >{{ $blog_category->description }}</textarea>
                      @if($errors->has('description'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('description') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-9 ml-auto">
                    <button type="submit" class="btn btn-primary">Update Category</button>
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
