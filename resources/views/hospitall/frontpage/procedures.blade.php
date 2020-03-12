    <!-- Section: Departments -->
    <section data-bg-img="{{ asset('inc/images/pattern/p4.png') }}">
      <div class="container">
        <div class="section-title text-center">
          <div class="row">
            <div class="col-md-8 col-md-offset-2">
              <h2 class="text-uppercase mt-0 line-height-1">Our Procedures</h2>
              <div class="title-icon">
                <img class="mb-10" src="{{ asset('inc/images/title-icon.png') }}" alt="">
              </div>
              <p>We are providing help in following procedure around <br> Pakistan</p>
            </div>
          </div>
        </div>
        <div class="section-centent">
          <div class="row">
            <div class="col-md-12">
              <div class="services-tab border-10px bg-white" id="procedures">
                <ul class="nav nav-tabs">
                  @if( count($treatments) > 0 )
                  @php $no = 1 @endphp
                  @foreach($treatments as $t)
                  <li class="{{$no == 1 ? 'active':''}}"><a href="#{{ str_slug($t->name,'-') }}" data-toggle="tab">
                  <img src="{{asset('inc/fonts/orthopedic-'.$no.'.png')}}" class="responsive"> <br>
                    <span>{{$t->name}}</span></a></li>
                  @php $no++ @endphp
                  @endforeach
                  @endif
                </ul>
                <div class="tab-content">
                  @if( count($treatments) > 0 )
                  @php $no = 1 @endphp
                  @foreach($treatments as $t)
                    <div class="tab-pane fade in {{$no == 1 ? 'active':''}}" id="{{ str_slug($t->name,'-') }}">
                      <div class="row">
                        <div class="col-md-7">
                          <div class="service-content ml-20 ml-sm-0">
                            <h2 class="title mt-0">{{ $t->name }}</h2>
                            <p class="lead">{!! str_limit($t->article,500) !!}</p>
                            <a class="btn btn-lg btn-dark btn-theme-colored" href="{{ route('procedure-detail',[$t->id,str_slug($t->name)] ) }}">View Details</a>
                          </div>
                        </div>
                        <div class="col-md-5">
                          <div class="thumb">
                        </div>
                        </div>
                      </div>
                    </div>
                  @php $no++ @endphp
                  @endforeach
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
