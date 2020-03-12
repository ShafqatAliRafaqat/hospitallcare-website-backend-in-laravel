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
              <h3 class="title text-white">Claims ALL</h3>
              <ol class="breadcrumb mt-10 white">
                <li><a class="text-white" href="/">Home</a></li>
                <li class="active text-theme-colored">Claims Management</li>
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
            <h3 class="text-theme-colored mt-sm-30 mt-0">HospitALL One Stop Solution</h3>
            <p class="font-weight-600">
                HospitALL is a one stop solution for all your health problems. To make sure that what we deliver is nothing short of what we promise, we keep brainstorming and researching to find every nook and corner within the health sector which requires working and needs to be filled.

                Every user has his/her own distinctive needs, and we try to cater them accordingly. We have placed great importance on customer satisfaction, hence, it is our primary goal, and to achieve it, we strive to deliver not just excellence but also the product which is most relevant to the user.
            </p>
          </div>
          <div class="col-md-6">
            <img src="{{ asset('inc/images/ClaimsAll.png') }}" alt="">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <img src="{{ asset('inc/images/claims-emergency.png') }}" alt="">
          </div>
          <div class="col-md-6">
            <h3 class="text-theme-colored mt-sm-30 mt-20">Manage your Medical <br>Claims</h3>
            <p class="font-weight-600">
                HospitALL proudly announces its premium product ‘ClaimsALL’, which is developed for our corporate users. No more dealing with your medical claims by paper or by paying endless visits. Through this product, the user can process the claim by minimal hassle using their phone.

                You can easily submit and manage your medical claims with our innovative application. You will have far greater control over the claims process anytime and anywhere through this innovative product. In addition to this, you will have access to these features at a single tap.
            </p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <h3 class="text-theme-colored mt-sm-30 mt-20">HospitALL Claims Policies</h3>
            <p class="font-weight-600">
                <ul>
                  <li>Information related to the Policies</li>
                  <li>Information regarding the Claims and their status</li>
                  <li>ClaimsALL Emergency Assistance</li>
                </ul>

                Hence, to put it simply, the user will be entitled to a number of benefits; easy processing, less time-consuming, paperless amongst a list of great many others.
            </p>
          </div>
          <div class="col-md-6">
            <img src="{{ asset('inc/images/claims.png') }}" alt="">
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
                    <img class="img-fullwidth" alt="" src="{{ asset('inc/images/health-management.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Health Management</h4>
                    <a href="{{ route('health-management') }}"><h6 class="text-theme-colored font-15 font-weight-400 mt-0">Get More Details</h6></a>
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
                    <img class="img-fullwidth" alt="" src="{{ asset('inc/images/csr.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">CSR</h4>
                    <a href="{{ route('csr') }}"><h6 class="text-theme-colored font-15 font-weight-400 mt-0">Get More Details</h6></a>
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
