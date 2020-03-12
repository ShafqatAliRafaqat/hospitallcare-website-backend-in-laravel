@extends('hospitall.master')
@section('content')
  <!-- Start main-content -->
  <div class="main-content">
    <!-- Section: inner-header -->
    <section class="inner-header" data-bg-img="{{ asset('inc/images/leadership.jpg') }}" style="background-size: cover; height: 100%;">
      <div class="container">
        <!-- Section Content -->
        <div class="section-content pb-100 pt-100">
          
        </div>
      </div>
    </section>

    <!-- Section: about -->
    <section class="">
      <div class="container pb-0">
        <div class="row">
          <div class="col-md-6">
            <p class="font-weight-600">
                <h1>Ayub Ghauri</h1> is a seasoned business leader that has nearly three decades of domestic and global experience in the fields of electronic media, entrepreneurship, fashion and marketing. Known as a trailblazer, Ayub Ghauri has routinely ventured into untapped markets and has garnered massive amounts of success. It is this innovative trait that has led him to launch an ambitious start up, HospitALL, which aims to revolutionize the digital healthcare space under his stewardship. 
Mr. Ghauri holds a master’s degree in Information Systems from California State University, Northridge. To add to this, he is certified as a coach by RCS, a highly privileged institution in Australia. More recently, he has focused his efforts on working with young people, guiding and mentoring them to ensure that they reach their potential. 
His tireless and selfless efforts have led him to be acknowledged as a Youth Icon by the Youth Revolution Clan on the United Nation’s International Youth Day.
Ayub Ghauri also serves as an Executive Director at NETSOL and as the Head of NSPIRE-- a technology incubator launched by NETSOL Technologies, Inc.

            </p>
            <p class="mt-20"><i>Ayub Ghauri</i><br>
            <i>Founder / Chief Executive Officer</i></p>
          </div>
          <div class="col-md-6">
            <img src="{{ asset('inc/images/team/ag.png') }}" alt="">
          </div>
        </div>
      </div>
    </section>
    
    <!-- Section: Board of Directors -->
    <section id="team">
      <div class="container">
        <div class="section-title text-center">
          <div class="row">
            <div class="col-md-8 col-md-offset-2">
              <h2 class="text-uppercase mt-0 line-height-1">Board of Directors</h2>
              <div class="title-icon">
                <img class="mb-10" src="{{ asset('inc/images/title-icon.png') }}" alt="">
              </div>
              
            </div>
          </div>
        </div>
        <div class="row mtli-row-clearfix">
          <div class="col-md-12">
            <div class="owl-carousel-4col">
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" height="300" alt="" src="{{ asset('inc/images/bod/shahid-burki.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Shahid Burki</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">Board of Director</h6>
                  </div>
                </div>
              </div>
              
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" height="300" alt="" src="{{ asset('inc/images/bod/shahid-najam.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Shahid Najam</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">Board of Director</h6>
                  </div>
                </div>
              </div>
              
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" height="300" alt="" src="{{ asset('inc/images/bod/saira-burki.jpg') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Saira Burki</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">Board of Director</h6>
                  </div>
                </div>
              </div>
              
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" height="300" alt="" src="{{ asset('inc/images/bod/murad-baig.jpg') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Murad Baig</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">Board of Director</h6>
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
