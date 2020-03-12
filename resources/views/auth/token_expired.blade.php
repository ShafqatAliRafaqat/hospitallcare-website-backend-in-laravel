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
                <div class="mb-60 text-center">
                <h3 class="text-theme-colored mt-0 pt-5 ">Your Code has been Expired with a Disease!</h3>
                <h5 ><a style="color: #27b56d !important;" href="{{ route('adminDashboard') }}">Try Getting another one.</a></h5>
                </div>
                <h5 class="text-center" style="color: grey; font-size: 12px; font-weight: 600;">Only a life lived for others is a life worthwhile!!!</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- end main-content -->
@endsection
