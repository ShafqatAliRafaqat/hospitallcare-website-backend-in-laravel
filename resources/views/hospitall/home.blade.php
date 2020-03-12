@extends('hospitall.master')
@section('content')
<!-- Start main-content -->
  <div class="main-content">
    @include('hospitall.frontpage.slider')
    <!-- Section: home-boxes -->
    <section class="">
      <div class="container-fluid pt-0 pb-0">
        <div class="row equal-height-inner home-boxes mt-sm-20" data-margin-top="-80px" data-bg-img="{{ asset('inc/images/pattern/p4.png') }}">
        <div class="row">
          <div class="col-md-8 col-md-offset-2">
              <div class="section-content">
                  <ul class="nav nav-tabs">
                      <li class="active"><a data-toggle="tab" href="#treatment">Procedures</a></li>
                      <li><a data-toggle="tab" href="#articles">Blogs</a></li>
                  </ul>
                  <div class="tab-content">
                      <div id="treatment" class="tab-pane fade in active">
                          <form action="{{ route('search-procedures-post') }}" method="post" class="form-horizantal">
                              @csrf
                              <div class="form-group row">
                                  <div class="col-md-8">
                                      <input type="text" placeholder="Search Treatments" name="term" class="form-control p-15" id="procedures">
                                  </div>
                                  <div class="col-md-4">
                                      <input type="submit" class="form-control btn-theme-colored p-15 mt-sm-10" value="Search">
                                  </div>
                              </div>
                          </form>
                      </div>
                      <div id="articles" class="tab-pane fade">
                          <form action="{{ route('search-blogs-post') }}" method="post" class="form-horizantal">
                            @csrf
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <input type="text" placeholder="Search Articles" name="term" class="form-control p-15" id="blogs">
                                </div>
                                <div class="col-md-4">
                                    <input type="submit" class="form-control btn-theme-colored p-15 m-sm-10" value="Search">
                                </div>
                            </div>
                          </form>
                      </div>
                  </div>
                </div>
              </div>
          </div>
        </div>
      </div>
    </section>
          <div class="bg_color_1">
            <div class="container margin_120_95">
                <div class="main_title">
                    <h2>Most Viewed doctors</h2>
                    <p>Usu habeo equidem sanctus no. Suas summo id sed, erat erant oporteat cu pri.</p>
                </div>
                <div id="reccomended" class="owl-carousel owl-theme">
                    <div class="item">
                        <a href="detail-page.html">
                            <div class="views"><i class="icon-eye-7"></i>140</div>
                            <div class="title">
                                <h4>Dr. Julia Holmes<em>Pediatrician - Cardiologist</em></h4>
                            </div><img src="http://via.placeholder.com/350x500.jpg" alt="">
                        </a>
                    </div>
                    <div class="item">
                        <a href="detail-page.html">
                            <div class="views"><i class="icon-eye-7"></i>120</div>
                            <div class="title">
                                <h4>Dr. Julia Holmes<em>Pediatrician</em></h4>
                            </div><img src="http://via.placeholder.com/350x500.jpg" alt="">
                        </a>
                    </div>
                    <div class="item">
                        <a href="detail-page.html">
                            <div class="views"><i class="icon-eye-7"></i>115</div>
                            <div class="title">
                                <h4>Dr. Julia Holmes<em>Pediatrician</em></h4>
                            </div><img src="http://via.placeholder.com/350x500.jpg" alt="">
                        </a>
                    </div>
                    <div class="item">
                        <a href="detail-page.html">
                            <div class="views"><i class="icon-eye-7"></i>98</div>
                            <div class="title">
                                <h4>Dr. Julia Holmes<em>Pediatrician</em></h4>
                            </div><img src="http://via.placeholder.com/350x500.jpg" alt="">
                        </a>
                    </div>
                    <div class="item">
                        <a href="detail-page.html">
                            <div class="views"><i class="icon-eye-7"></i>98</div>
                            <div class="title">
                                <h4>Dr. Julia Holmes<em>Pediatrician</em></h4>
                            </div><img src="http://via.placeholder.com/350x500.jpg" alt="">
                        </a>
                    </div>
                </div>
            </div>
    </div>
    <!-- Section: about -->
    <section class="">
      <div class="container pb-0">
        <div class="row">
          <div class="col-md-12">
            <h1 class="text-gray mt-0 mt-sm-30 mb-0">Welcome To <span class="text-theme-colored mt-0">HospitALL</span></h1>
            <p class="font-weight-600 mt-20 text-center">We are drivers of innovation who aim to transform the future of healthcare in Pakistan. We are problem solvers, we are solutions providers, we are teachers and we are leaders. We understand the complex ecosystem of healthcare in developing Pakistan and have a team of seasoned professionals who will lead the charge and create a healthcare system which is beneficial for all players in the game. HospitALL is the future of healthcare. Creating an atmosphere of care, cure and support.</p>


            <a href="{{ route('promise') }}" class="btn btn-default btn-theme-colored mt-15 mb-15 mb-sm-30">Read more</a>
          </div>
        </div>
      </div>
    </section>
    @include('hospitall.frontpage.procedures')
    <!-- Divider: Funfact -->
    <section class="divider parallax layer-overlay overlay-theme-colored-9"
      data-bg-img="http://placehold.it/1920x1280" data-parallax-ratio="0.7">
      <div class="container pt-60 pb-60">
        <div class="row">
          <div class="col-xs-12 col-sm-6 col-md-3 mb-md-50">
            <div class="funfact text-center">
              <i class="pe-7s-smile mt-5 text-white"></i>
              <h2 data-animation-duration="2000" data-value="1754" class="animate-number text-white font-42 font-weight-500">0</h2>
              <h5 class="text-white text-uppercase font-weight-600">Happy Patients</h5>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-3 mb-md-50">
            <div class="funfact text-center">
              <i class="pe-7s-rocket mt-5 text-white"></i>
              <h2 data-animation-duration="2000" data-value="675" class="animate-number text-white font-42 font-weight-500">0</h2>
              <h5 class="text-white text-uppercase font-weight-600">Our Services</h5>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-3 mb-md-50">
            <div class="funfact text-center">
              <i class="pe-7s-add-user mt-5 text-white"></i>
              <h2 data-animation-duration="2000" data-value="248" class="animate-number text-white font-42 font-weight-500">0</h2>
              <h5 class="text-white text-uppercase font-weight-600">Our Doctors</h5>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-3 mb-md-50">
            <div class="funfact text-center">
              <i class="pe-7s-global mt-5 text-white"></i>
              <h2 data-animation-duration="2000" data-value="24" class="animate-number text-white font-42 font-weight-500">0</h2>
              <h5 class="text-white text-uppercase font-weight-600">Service Points</h5>
            </div>
          </div>
        </div>
      </div>
    </section>

    @include('hospitall.frontpage.blogs')
  </div>
  <!-- end main-content -->
@endsection
@section('scripts')
  <script>
    $(document).on('click', '#procedure-btn', function(){
      $('html, body').animate({
        scrollTop: $('#procedures').offset().top
      }, 500);
    });
  </script>
@endsection
