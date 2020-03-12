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
              <h3 class="title text-white">CSR</h3>
              <ol class="breadcrumb mt-10 white">
                <li><a class="text-white" href="/">Home</a></li>
                <li class="active text-theme-colored">CSR</li>
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
            <img src="{{ asset('inc/images/csr.png') }}" alt="">
          </div>
          <div class="col-md-6">
            <h3 class="text-theme-colored mt-sm-30 mt-0">Contributing to the Welfare of the Society</h3>
            <p class="font-weight-600">
                At HospitALL we intend to function in a sustainable and highly ethical way, and deal with both our social and environmental impacts. HospitALL places a profound emphasis on protecting the nature, and gives a careful consideration to human rights which is the cornerstone of every democratic state. In addition to this, great importance is attached to work for the betterment of not just our communities, but both environment and the society, it is an integral part of.

                We firmly believe in the notion that nothing works one way, and extending favors is a two-way thing. We at HospitALL wants to actively contribute to the welfare of the society, and actively engage in the ‘give and take’ principle.
            </p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <h3 class="text-theme-colored mt-sm-30 mt-20">Demonstrating Integrity & Social Responsibility</h3>
            <p class="font-weight-600">
                In addition to this, we well understand the significance of corporate social responsibility, and the fact that ethical practices is the right way to go about things. We most ardently believe in the incredible importance of being a responsible entity and acting as a counter to all those forces which are hazardous for the health of a society.

                Demonstrating integrity and social responsibility isn’t a legal norm, but is one that is considered very highly at HospitALL. We wish to indulge in practices that harness the true potential of our world and do not exacerbate the long-standing social and environmental issues.
            </p>
          </div>
          <div class="col-md-6">
            <img src="{{ asset('inc/images/csr-2.png') }}" alt="">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <img src="{{ asset('inc/images/csr-3.png') }}" alt="">
          </div>
          <div class="col-md-6">
            <h3 class="text-theme-colored mt-sm-30 mt-0">Uprooting the Health Related Issues</h3>
            <p class="font-weight-600">
                HospitALL intends to uproot the health related issues, so that life can be enjoyed it leads to the eternal bliss. Paramount importance is attached to creating awareness amongst the masses regarding hygiene and health practices. We have successfully undertaken multiple campaigns, not just in the urban centers, but also in the rural areas covering many aspects ranging from fitness, hygiene, reforestation, reduction in mortality rates and so forth.

                We will continue working for the benefit of the community, and keep them safer and healthy!
            </p>
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
                    <img class="img-fullwidth" alt="" src="{{ asset('inc/images/medical-tourism.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Medical Tourism</h4>
                    <a href="{{ route('tourism') }}"><h6 class="text-theme-colored font-15 font-weight-400 mt-0">Get More Details</h6></a>
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
