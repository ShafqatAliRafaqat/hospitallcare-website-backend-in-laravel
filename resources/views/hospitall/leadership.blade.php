@extends('hospitall.master')
@section('content')
  <!-- Start main-content -->
  <div class="main-content">
    <!-- Section: inner-header -->
    <section class="inner-header" data-bg-img="{{ asset('inc/images/leadership.jpg') }}" style="background-size: cover; height: 100%;">
      <div class="container">
        <!-- Section Content -->
        <div class="section-content">
          <div class="row">
            <div class="col-md-12 xs-text-center">
              <h3 class="title">Our Promise</h3>
              <ol class="breadcrumb mt-10">
                <li><a class="" href="/">Home</a></li>
                <li class="active text-theme-colored">Our-promise</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section: about -->
    <section class="mb-20">
      <div class="container pb-0">
        <div class="row">
          <div class="col-md-8">
            <h1 class="text-gray mt-0 mt-sm-30 mb-10">Ayub <span class="text-theme-colored">Ghauri</span></h1>
            <p class="mt-20"><i>Ayub Ghauri</i><br>
            <i>Founder / Chief Executive Officer</i></p>
            <p class="font-weight-600 ">
                Ayub Ghauri is a seasoned business leader that has nearly three decades of domestic and global experience in the fields of electronic media, entrepreneurship, fashion and marketing. Known as a trailblazer, Ayub Ghauri has routinely ventured into untapped markets and has garnered massive amounts of success. It is this innovative trait that has led him to launch an ambitious start up, HospitALL, which aims to revolutionize the digital healthcare space under his stewardship.

                Mr. Ghauri holds a master’s degree in Information Systems from California State University, Northridge. To add to this, he is certified as a coach by RCS, a highly privileged institution in Australia. More recently, he has focused his efforts on working with young people, guiding and mentoring them to ensure that they reach their potential.

                His tireless and selfless efforts have led him to be acknowledged as a Youth Icon by the Youth Revolution Clan on the United Nation’s International Youth Day.

                Ayub Ghauri also serves as an Executive Director at NETSOL and as the Head of NSPIRE-- a technology incubator launched by NETSOL Technologies, Inc.
            </p>
          </div>

          <div class="col-md-4">
            <img src="{{ asset('inc/images/team/ag.png') }}" alt="ayub-ghauri-ceo">
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
              <h2 class="text-uppercase mt-0 line-height-1">Board Of Directors</h2>
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
                    <img class="img-fullwidth" height="300" alt="shahid-burki" src="{{ asset('inc/images/board-of-directors/shahid-burki.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Shahid Burki</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">Board of directors</h6>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" height="300" alt="saira-burki" src="{{ asset('inc/images/board-of-directors/saira-burki.jpg') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Saira Burki</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">Board of directors</h6>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" height="300" alt="shahid-najam" src="{{ asset('inc/images/board-of-directors/shahid-najam.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Shahid Najam</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">Board of directors</h6>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" height="300" alt="murad-baig" src="{{ asset('inc/images/board-of-directors/murad-baig.jpg') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Murad Baig</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">Board of directors</h6>
                  </div>
                </div>
              </div>
            </div>
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
              <h2 class="text-uppercase mt-0 line-height-1">Management Team</h2>
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
                    <img class="img-fullwidth" height="300" alt="" src="{{ asset('inc/images/team/bilal-javed.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Bilal Javed</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">Operational Manager</h6>
                  </div>
                </div>
              </div>

              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" height="300" alt="" src="{{ asset('inc/images/team/zara-mirza.jpg') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Zara Mirza</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">Marketing Head</h6>
                  </div>
                </div>
              </div>

              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" height="300" alt="" src="{{ asset('inc/images/team/rida-chughtai.jpg') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Rida Chughtai</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">Business Development</h6>
                  </div>
                </div>
              </div>

              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" height="300" alt="" src="{{ asset('inc/images/team/Huma-Ejaz.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Huma Ejaz</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">DIRECTOR FINANCE & MARKETS</h6>
                  </div>
                </div>
              </div>

              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" height="300" alt="" src="{{ asset('inc/images/team/hassan.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Hassan Chattha</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">DIRECTOR STRATEGY & BUSINESS</h6>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" height="300" alt="" src="{{ asset('inc/images/team/fatima.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Fatima Zafar</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">BUSINESS DEVELOPMENT OFFICER</h6>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" height="300" alt="" src="{{ asset('inc/images/team/hasiba.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Hasiba Liaquat</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">WEB DESIGNER / DEVELOPER</h6>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" height="300" alt="" src="{{ asset('inc/images/team/Shaleeem.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Shaleem Gill</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">SALES EXECUTIVE</h6>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" height="300" alt="" src="{{ asset('inc/images/team/Shahzaib-khurram.png') }}">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Shahzaib Khurram</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">GRAPHIC DESIGNER</h6>
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
