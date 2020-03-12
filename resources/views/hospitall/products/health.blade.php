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
              <h3 class="title text-white">Health Management</h3>
              <ol class="breadcrumb mt-10 white">
                <li><a class="text-white" href="/">Home</a></li>
                <li class="active text-theme-colored">Health Management</li>
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
            <img src="{{ asset('inc/images/health-management.png') }}" alt="">
          </div>
          <div class="col-md-6">
            <h3 class="text-theme-colored mt-sm-30 mt-0">Access to an Array of Medical Services</h3>
            <p class="font-weight-600">
                The purpose of our mobile application is to cater timely to the user’s medical emergencies and provide them the access to an array of medical services.

                Through this application, the process of prescribing medicines, along with the viewing of multiple reports becomes easy and convenient. The entire medical history of the user will be stored and gathered in one place. Hence, there will exist no need to safe keep the medical documents.
            </p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <h3 class="text-theme-colored mt-sm-30 mt-20">Medical Help - Just a Tap Away!</h3>
            <p class="font-weight-600">
                It will also enable the user to track and monitor their health reports and symptoms. Moreover, the patients can quickly search and find a doctor catering to any medical condition, at local, national and global level. They can then easily book an appointment through mobile application.

                It will also facilitate the audio/video chat with the medical experts within a matter of seconds. Medical help is just a tap away!

                Before the advent of the era of digital healthcare, patients solely relied on abundant printed material for the purpose of keeping and referencing their records. Now with the accessibility of the mobile application, the user will have immediate access to their medical documents which can be easily updated. The impact of the revolutionary digital healthcare on medical systems and the lives of both the doctors and patients is growing with each passing day!
            </p>
          </div>
          <div class="col-md-6">
            <img src="{{ asset('inc/images/health-management-2.png') }}" alt="">
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
