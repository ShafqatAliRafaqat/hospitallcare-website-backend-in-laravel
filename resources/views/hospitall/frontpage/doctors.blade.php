<!-- Section: Doctors -->
    <section id="doctors">
      <div class="container">
        <div class="section-title text-center">
          <div class="row">
            <div class="col-md-8 col-md-offset-2">
              <h2 class="text-uppercase mt-0 line-height-1">Our Doctors</h2>
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
              @if ( count($doctors) > 0)
                @foreach($doctors as $d)
                  <div class="item">
                    <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                      <div class="team-thumb">
                        <img class="img-fullwidth" alt="" width="275" height="370" src="{{ asset('backend/uploads/doctors/'.$d->picture) }}">
                        <div class="team-overlay"></div>
                      </div>
                      <div class="team-details bg-silver-light pt-10 pb-10">
                        <h4 class="text-uppercase font-weight-600 m-5">{{$d->name}}</h4>
                        <h6 class="text-theme-colored font-15 font-weight-400 mt-0">{{$d->degree}}</h6>
                        <ul class="styled-icons icon-theme-colored icon-dark icon-circled icon-sm">
                          <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                          <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                          <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                          <li><a href="#"><i class="fa fa-skype"></i></a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                @endforeach
              @endif

              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" alt="" src="http://placehold.it/275x370">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Dr. Smile Jhon</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">Mbbs Doctor</h6>
                    <ul class="styled-icons icon-theme-colored icon-dark icon-circled icon-sm">
                      <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                      <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                      <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                      <li><a href="#"><i class="fa fa-skype"></i></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" alt="" src="http://placehold.it/275x370">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Dr. Sakib Jhon</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">Mbbs Doctor</h6>
                    <ul class="styled-icons icon-theme-colored icon-dark icon-circled icon-sm">
                      <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                      <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                      <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                      <li><a href="#"><i class="fa fa-skype"></i></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
                    <img class="img-fullwidth" alt="" src="http://placehold.it/275x370">
                    <div class="team-overlay"></div>
                  </div>
                  <div class="team-details bg-silver-light pt-10 pb-10">
                    <h4 class="text-uppercase font-weight-600 m-5">Dr. Smile Jhon</h4>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0">Mbbs Doctor</h6>
                    <ul class="styled-icons icon-theme-colored icon-dark icon-circled icon-sm">
                      <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                      <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                      <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                      <li><a href="#"><i class="fa fa-skype"></i></a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
