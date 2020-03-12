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
              <h3 class="title text-white">Concierge Card Service</h3>
              <ol class="breadcrumb mt-10 white">
                <li><a class="text-white" href="/">Home</a></li>
                <li class="active text-theme-colored">Concierge Card Service</li>
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
            <img src="{{ asset('inc/images/concierge.png') }}" alt="">
          </div>
          <div class="col-md-6">
            <h3 class="text-theme-colored mt-sm-30 mt-0">HospitALL An Array of Health Options</h3>
            <p class="font-weight-600">
                HospitALL offers an array of health options to choose from, according to the need and the affordability of the user. Our endeavors are focused to bring you the best, and is all about fostering health and happiness in the digital age.

                One of the highlights of our services is the ‘Medical Concierge Card’ which is nothing short of a one stop solution for all your healthcare needs. Our unique process enables us to provide you services using cutting edge digital technology.

                Through this card, there are a great many benefits that will be offered to you, providing you the prime health care.
            </p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <h3 class="text-theme-colored mt-sm-30 mt-20">Concierge Card Benefits</h3>
            <p class="font-weight-600">
                This will also help in closing the gaps currently present and promoting the interconnectedness in all social, economic and political realms, and will prove to be quite instrumental for the economy of Pakistan.

                The unemployment rate will decrease, the quality of healthcare will improve, and there will be increased investment in the health sector. Most importantly, the health channels will expand and improve.

                Hence, at HospitALL we also welcome all those who are traveling to obtain high quality of medical facilities, and are seeking access to better healthcare and affordability.
            </p>
          </div>
          <div class="col-md-6">
            <img src="{{ asset('inc/images/concierge-2.png') }}" alt="">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <img src="{{ asset('inc/images/concierge-3.png') }}" alt="">
          </div>
          <div class="col-md-6">
            <h3 class="text-theme-colored mt-sm-30 mt-0">HospitALL Analyzed AI & ML Algorithms</h3>
            <p class="font-weight-600">
                Furthermore, you will be provided mental and physical fitness guidance by our experts who will impart knowledge on you regarding the hidden secrets of a healthy living. You will also be given the global medical second opinion, for all the cropping health issues, especially those which require immediate attention. Along with this, you will be provided Smart wearable for Real-Time monitoring; recording your complete physical exertions.

                In addition to this, your medical history will be analyzed through AI & ML Algorithms, and you will be able to avail the exclusive discounts on lab service.

                Lastly, by being a user of the Medical Concierge Card you will be eligible for baseline tests, twice a year. Along with this, the user will be provided with high-end Post Op / Discharge care. As we earlier mentioned, premium quality and care is our focal objective!
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
                    <img class="img-fullwidth" alt="" src="{{ asset('inc/images/medical-tourism.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Medical tourism</h4>
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
