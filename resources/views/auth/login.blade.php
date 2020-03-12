@extends('hospitall.login-master')
@section('content')
<!-- Start main-content -->
  <div class="main-content">
    <!-- Section: home -->
    <section id="home" class="divider fullscreen bg-lighter">
      <div class="display-table">
        <div class="display-table-cell">
          <div class="container">
            <div class="row">
              <div class="col-md-4 col-md-push-4">
                <div class="text-center mb-60"><a href="#" class=""><img alt="" src="{{ asset('inc/images/hospitall.png') }}" style="width: 50%;"></a></div>

                <div class="login-card">
                <h4 class="text-theme-colored mt-0 pt-5"> Login</h4>
                <form name="login-form" method="post" class="clearfix" action="{{ route('login') }}">
                    @csrf

                    <div class="row">
                        <div class="form-group col-md-12">
                          <label for="form_username_email">Email/Phone</label>
                          <input id="form_username_email" type="text" name="email" class="login-input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback alert-danger" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                          <label for="form_password">Password</label>
                          <input id="form_password" type="password" name="password" class="login-input form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" >
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="checkbox pull-left mt-15">
                        <label for="remember">
                            <input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                            Remember me
                        </label>
                    </div>
                  <div class="form-group pull-right mt-10">
                    <button type="submit" class="btn btn-dark btn-sm">Login</button>
                  </div>
                  <div class="clear text-center pt-10">
                  </div>
                </form>
                <span class="text-center" style="font-size: 14px;">Only a life lived for others is a life worthwhile!!!</span>
              </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- end main-content -->
@endsection
