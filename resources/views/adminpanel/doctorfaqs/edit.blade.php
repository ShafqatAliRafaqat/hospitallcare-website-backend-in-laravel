@extends('adminpanel.layout')
@section('title','Edit FAQ | HospitALL')
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      <!-- Form Elements -->
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Edit FAQ</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('update_edit', $faq->id) }}" enctype="multipart/form-data">
                @csrf @method('POST')

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Question</label>
                  <div class="col-md-9">
                        <textarea type="text" name="question" value="{{ $faq->question }}" placeholder="Question"
                        class="form-control {{ $errors->has('question') ? 'is-invalid' : '' }}" required cols="30" rows="5">{{ $faq->question }}</textarea>
                      @if($errors->has('question'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('question') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Answer</label>
                  <div class="col-md-9">
                        <textarea type="text" name="answer" value="{{ $faq->answer }}" placeholder="Answer"
                        class="form-control tiny {{ $errors->has('answer') ? 'is-invalid' : '' }}" required cols="30" rows="5">{{ $faq->answer }}</textarea>
                      @if($errors->has('answer'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('answer') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Active</label>
                  <div class="col-md-9">
                    <div class="custom-control custom-checkbox">
                      <input id="is_active" {{ $faq->is_active == 1 ? 'checked' : '' }} value="1" type="checkbox" name="is_active" class="custom-control-input">
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
