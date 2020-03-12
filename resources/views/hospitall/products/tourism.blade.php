@extends('hospitall.master')
@section('content')
  <!-- Start main-content -->
  <div class="main-content">
    <!-- Section: inner-header -->
    <section class="inner-header divider parallax layer-overlay overlay-dark-8" data-bg-img="http://placehold.it/1920x1280">
      <div class="container pt-60 pb-60">
        <!-- Section Content -->
        <div class="section-content">
          <div class="row">
            <div class="col-md-12 xs-text-center">
              <h3 class="title text-white">Medical Tourism</h3>
              <ol class="breadcrumb mt-10 white">
                <li><a class="text-white" href="/">Home</a></li>
                <li class="active text-theme-colored">Medical Tourism</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section: about -->
    <section class="">
      <div class="container pb-0">
        <div class="row">
          <div class="col-md-6">
            <img src="{{ asset('inc/images/medical-tourism.png') }}" alt="">
          </div>
          <div class="col-md-6">
            <h3 class="text-theme-colored mt-sm-30 mt-0">Catering the Users Across the Globe</h3>
            <p class="font-weight-600">
                Medical tourism is a trend which is stretching and covering wider spans and regions now. Pakistan through its reformatory policies is creating space to make all such medical facilities inclusive.

                HospitALL proudly announces medical tourism as a service it is offering to its users. Through this service, it will be catering the users across the globe since we believe in providing relief to all. In order to avail this service, the user will be travelling to Pakistan for obtaining the premium health services which will not be exorbitantly high in cost and will fall within their affordability.
            </p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <h3 class="text-theme-colored mt-sm-30 mt-20">Instrumental for the Economy of Pakistan</h3>
            <p class="font-weight-600">
                This will also help in closing the gaps currently present and promoting the interconnectedness in all social, economic and political realms, and will prove to be quite instrumental for the economy of Pakistan.

                The unemployment rate will decrease, the quality of healthcare will improve, and there will be increased investment in the health sector. Most importantly, the health channels will expand and improve.

                Hence, at HospitALL we also welcome all those who are traveling to obtain high quality of medical facilities, and are seeking access to better healthcare and affordability.
            </p>
          </div>
          <div class="col-md-6">
            <img src="{{ asset('inc/images/medical-tourism-2.png') }}" alt="">
          </div>
        </div>
      </div>
    </section>

    <!-- Section: team -->
    <section id="team">
      <div class="container">
        <div class="section-title text-center">
          <div class="row">
            <div class="col-md-8 col-md-offset-2">
              <h2 class="text-uppercase mt-0 line-height-1">Our Products</h2>
              <div class="title-icon">
                <img class="mb-10" src="{{ asset('inc/images/title-icon.png') }}" alt="">
              </div>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rem autem<br> voluptatem obcaecati!</p>
            </div>
          </div>
        </div>
        <div class="row mtli-row-clearfix">
          <div class="col-md-12">
            <div class="owl-carousel-4col">
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" alt="" src="{{ asset('inc/images/claims.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Claims Management</h4>
                    <a href="{{ route('claims-management') }}"><h6 class="text-theme-colored font-15 font-weight-400 mt-0">Get More Details</h6></a>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" alt="" src="{{ asset('inc/images/concierge.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Concierge Card</h4>
                    <a href="{{ route('concierge') }}"><h6 class="text-theme-colored font-15 font-weight-400 mt-0">Get More Details</h6></a>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" alt="" src="{{ asset('inc/images/csr.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">CSR</h4>
                    <a href="{{ route('csr') }}"><h6 class="text-theme-colored font-15 font-weight-400 mt-0">Get More Details</h6></a>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" alt="" src="{{ asset('inc/images/health-management.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Health Management</h4>
                    <a href="{{ route('health-management') }}"><h6 class="text-theme-colored font-15 font-weight-400 mt-0">Get More Details</h6></a>
                  </div>
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
