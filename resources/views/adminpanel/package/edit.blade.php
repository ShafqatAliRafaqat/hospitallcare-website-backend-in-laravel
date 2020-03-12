@extends('adminpanel.layout')
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
            <h3 class="h6 text-uppercase mb-0">Update Package</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('packages.update', $package->id) }}"
              enctype="multipart/form-data">
                @csrf @method('put')
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Select Center</label>
                  <div class="col-md-9">
                    <select name="center_id" id="" class="form-control {{ $errors->has('center_id') ? 'is-invalid' : '' }}">
                      <option value="">Select Center</option>
                      @foreach($centers as $center)
                      <option value="{{ $center->id }}"
                        {{ ($package->center_id == $center->id) ? 'selected' : '' }}>
                        {{ $center->center_name }}
                      </option>
                      @endforeach
                    </select>
                    @if($errors->has('center_id'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('center_id') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Select Procedures</label>
                  <div class="col-md-9">
                    <select name="procedure_id" id="" class="form-control {{ $errors->has('procedure_id') ? 'is-invalid' : '' }}">
                      <option value="">Select Center</option>
                      @foreach($procedures as $procedure)
                      <option value="{{ $procedure->id }}"
                        {{ ($package->procedure_id == $procedure->id) ? 'selected' : '' }}>
                        {{ $procedure->name }}
                      </option>
                      @endforeach
                    </select>
                    @if($errors->has('procedure_id'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('procedure_id') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Package Name</label>
                  <div class="col-md-9">
                        <input type="text" name="name" placeholder="Name"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $package->package_name }}" required>

                      @if($errors->has('name'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('name') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Price</label>
                  <div class="col-md-4">
                        <input type="text" name="price" placeholder="Package Price"
                        class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" value="{{ $package->price }}" required>

                      @if($errors->has('price'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('price') }}</div>
                      @endif
                  </div>
                  <div class="col-md-5">
                    <div class="custom-control custom-checkbox">
                      <input id="show_price" value="1" type="checkbox" name="show_price" class="custom-control-input" {{ $package->show_price == 1 ? 'checked':'' }}>
                      <label for="show_price" class="custom-control-label">Check to Show Price</label>
                    </div>
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
                    <img src="{{ asset('backend/uploads/packages/'.$package->picture) }}" class="img-fluid" alt="{{ $errors->first('picture') }}" height="100" width="100">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Article</label>
                  <div class="col-md-9">
                      <textarea placeholder="Enter Details" class="form-control tiny" name="article" id="" cols="30" rows="10">{!! $package->article !!}</textarea>
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
                      class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" value="{{ $package->meta_title }}">

                    @if($errors->has('meta_title'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_title') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Meta Description</label>
                  <div class="col-md-9">
                      <input type="text" name="meta_description" placeholder="SEO Meta Description"
                      class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" value="{{ $package->meta_description }}">

                    @if($errors->has('meta_description'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_description') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">URL</label>
                  <div class="col-md-9">
                      <input type="text" name="url" placeholder="Enter URL"
                      class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" value="{{ $package->url }}">
                    @if($errors->has('url'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('url') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-9 ml-auto">
                    <a href="{{ route('packages.index') }}" class="btn btn-secondary">Cancel</a>
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
