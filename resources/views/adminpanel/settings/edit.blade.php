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
            <h3 class="h6 text-uppercase mb-0">Update Setting</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('settings.update', $setting->id) }}" enctype="multipart/form-data">
                @csrf @method('put')
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Mobile</label>
                  <div class="col-md-9">
                        <input type="text" value="{{ $setting->mobile }}" name="mobile" placeholder="Mobile No"
                        class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}" value="{{ old('mobile') }}" required>

                      @if($errors->has('mobile'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('mobile') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Contact Email</label>
                  <div class="col-md-9">
                        <input type="email" value="{{ $setting->email }}" name="email" placeholder="Email"
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" required>

                      @if($errors->has('email'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('email') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Address</label>
                  <div class="col-md-9">
                        <input type="text" value="{{ $setting->address }}" name="address" placeholder="Address No"
                        class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" value="{{ old('address') }}" required>

                      @if($errors->has('address'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('address') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Facebook</label>
                  <div class="col-md-9">
                        <input type="text" value="{{ $setting->facebook }}" name="facebook" placeholder="Facebook"
                        class="form-control {{ $errors->has('facebook') ? 'is-invalid' : '' }}" value="{{ old('facebook') }}" required>

                      @if($errors->has('facebook'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('facebook') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Twitter</label>
                  <div class="col-md-9">
                        <input type="text" value="{{ $setting->twitter }}" name="twitter" placeholder="Twitter"
                        class="form-control {{ $errors->has('twitter') ? 'is-invalid' : '' }}" value="{{ old('twitter') }}" required>

                      @if($errors->has('twitter'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('twitter') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Skype</label>
                  <div class="col-md-9">
                      <input type="text" value="{{ $setting->skype }}" name="skype" placeholder="Skype" class="form-control {{ $errors->has('skype') ? 'is-invalid' : '' }}"
                      value="{{ old('skype') }}" required>

                      @if($errors->has('skype'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('skype') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Youtube</label>
                  <div class="col-md-9">
                    <input type="text" value="{{ $setting->youtube }}" name="youtube" placeholder="youtube" class="form-control {{ $errors->has('youtube') ? 'is-invalid' : '' }}" value="{{ old('youtube') }}" required>

                    @if($errors->has('youtube'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('youtube') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Instagram</label>
                  <div class="col-md-9">
                        <input type="text" value="{{ $setting->instagram }}" name="instagram" placeholder="instagram"
                        class="form-control {{ $errors->has('instagram') ? 'is-invalid' : '' }}" value="{{ old('instagram') }}" required>

                      @if($errors->has('instagram'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('instagram') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Google Analytics</label>
                  <div class="col-md-9">
                    <textarea name="google_analytics" id="" cols="30" rows="10" class="form-control {{ $errors->has('google_analytics') ? 'is-invalid' : '' }}" placeholder="Google Analytics Code">{{ old('google') }}</textarea>
                    @if($errors->has('google'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('google') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Facebook Pixel</label>
                  <div class="col-md-9">
                    <textarea name="facebook_pixel" id="" cols="30" rows="10" class="form-control {{ $errors->has('facebook_pixel') ? 'is-invalid' : '' }}" placeholder="Facebook Pixel Code">{{ old('facebook_pixel') }}</textarea>
                    @if($errors->has('facebook_pixel'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('facebook_pixel') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-9 ml-auto">
                    <button type="submit" class="btn btn-primary">Update Setting</button>
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
