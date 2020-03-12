<!DOCTYPE html>
<html ⚡>
  <head>
  <meta charset="utf-8">
  <script async src="https://cdn.ampproject.org/v0.js"></script>
  <link rel="canonical" href="https://ampbyexample.com/introduction/hello_world/">
  <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
  <style amp-custom>
    .bg-theme-colored-top-bar {
    background-color: #27B56D;
    }
    .header-top {
      padding: 5px 0;
    }

    ul,li,a {
      margin: 0px;
      padding: 0px;
      list-style: none;
    }
  </style>
  <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
  <!-- URL -->
  <meta name="description" content="Hospitall" />
  <meta name="keywords" content=" clinic, dental, doctor, health, hospital, medical, medical theme, medicine, therapy" />
  <!-- Page Title -->
  <title>@yield('title')</title>

  <!-- Favicon and Touch Icons -->
  <link href="{{ asset('inc/images/favicon.ico') }}" rel="shortcut icon" type="image/png">
  <link href="{{ asset('inc/images/apple-touch-icon.png') }}') }}" rel="icon">
  <link href="{{ asset('inc/images/apple-touch-icon-72x72.png') }}') }}" rel="icon" sizes="72x72">
  <link href="{{ asset('inc/images/apple-touch-icon-114x114.png') }}') }}" rel="icon" sizes="114x114">
  <link href="{{ asset('inc/images/apple-touch-icon-144x144.png') }}') }}" rel="icon" sizes="144x144">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  </head>
<body class="fullwidth-page">
  <div id="wrapper" class="clearfix">
    <!-- Header -->
    <header id="header" class="header">
        <div class="header-top bg-theme-colored-top-bar sm-text-center">
        <div class="container">
          <div class="row">
            <div class="col-md-3">
              <div class="widget no-border m-0">
                <ul class="styled-icons icon-dark icon-theme-colored-top-bar icon-sm sm-text-center">
                  <li><a href="{{$setting->facebook}}" target="_blank"><i class="fa fa-facebook"></i></a></li>
                  <li><a href="{{$setting->twitter}}" target="_blank"><i class="fa fa-twitter"></i></a></li>
                  <li><a href="{{$setting->instagram}}" target="_blank"><i class="fa fa-instagram"></i></a></li>
                  <li><a href="{{$setting->youtube}}" target="_blank"><i class="fa fa-youtube"></i></a></li>
                </ul>
              </div>
            </div>
            <div class="col-md-9">
              <div class="widget no-border m-0">
                <ul class="list-inline pull-right flip sm-pull-none sm-text-center mt-5">
                  <li class="m-0 pl-10 pr-10"> <i class="fa fa-phone text-white"></i> <a class="text-white" href="#">{{$setting->mobile}}</a> </li>
                  <li class="m-0 pl-10 pr-10"> <i class="fa fa-envelope-o text-white"></i> <a class="text-white" href="#">{{$setting->email}}</a> </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="header-nav">
        <div class="header-nav-wrapper navbar-scrolltofixed bg-lightest">
          <div class="container">
            <nav id="menuzord-right" class="menuzord blue bg-lightest">
              <a class="menuzord-brand pull-left flip" href="/">
                <amp-img width="1080" height="610" layout="responsive" src='{{ asset("inc/images/hospitall.png") }}' alt=""></amp-img>
              </a>
              <ul class="menuzord-menu">
                <li class="{{ Request::segment(1) == 'products' ? 'active' : ''}}">
                  @if( count($menutreatments) > 0 )
                    <a href="#">Key Procedures</a>
                    <ul class="dropdown">
                        @foreach($menutreatments as $t)
                          <li><a href="{{ route('procedure-detail', [$t->id,str_slug($t->name,'-')]) }}">{{ $t->name }}</a></li>
                        @endforeach
                    </ul>
                  @else
                    <a href="{{ route('all-procedures') }}">Procedures</a>
                  @endif
                </li>
                <li>
                    <a href="#">Customer Success</a>
                    <ul class="dropdown">
                        <li><a href="#">Clients</a></li>
                        <li><a href="#">Case Studies</a></li>
                        <li><a href="#">Testimonials</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">About</a>
                    <ul class="dropdown">
                        <li><a href="{{ route('promise') }}">Company Overview</a></li>
                        <li><a href="{{ route('about') }}">Leadership Team</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">Thinking</a>
                    <ul class="dropdown">
                        <li><a href="{{ route('blogs') }}">Blogs</a></li>
                        <li><a href="#">Whitepapers</a></li>
                    </ul>
                </li>
               <!--  <li class="{{ Request::segment(1) == 'health-shop' ? 'active' : ''}}"><a href="#">Health Shop</a></li> -->

                <li class="{{ Request::segment(1) == 'contact' ? 'active' : ''}}"><a href="{{ route('contact') }}">Contact</a></li>
                @Auth
                  <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                                             onclick="event.preventDefault();
                                                           document.getElementById('logout-form').submit();">
                                              {{ __('Logout') }}
                                          </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                  </li>
                @else

                @endauth
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </header>
    @yield('content')

    <!-- Footer -->
    <footer id="footer" class="footer pb-0" data-bg-amp-img="{{ asset('inc/images/footer-bg.png') }}" data-bg-color="#25272e">
      <div class="container pt-90 pb-60">
        <div class="row">
          <div class="col-sm-6 col-md-4">
            <div class="widget dark"> <amp-img width="1080"
  height="610"
  layout="responsive" alt="" src="{{ asset('inc/images/HospitALL-logo.png') }}"></amp-img>
              <p class="font-12 mt-10 mb-10">We are drivers of innovation who aim to transform the future of healthcare in Pakistan. We are problem solvers, we are solutions providers, we are teachers and we are leaders.</p>
              <a class="btn btn-default btn-transparent btn-xs btn-flat mt-5" href="#">Read more</a>
              <ul class="styled-icons icon-dark icon-theme-colored icon-circled icon-sm mt-20">
                <li><a href="{{ $setting->facebook }}" target="_blank"><i class="fa fa-facebook"></i></a> </li>
                <li><a href="{{ $setting->twitter }}" target="_blank"><i class="fa fa-twitter"></i></a> </li>
                <li><a href="{{ $setting->instagram }}" target="_blank"><i class="fa fa-instagram"></i></a> </li>
                <li><a href="{{ $setting->youtube }}" target="_blank"><i class="fa fa-youtube"></i></a> </li>
              </ul>
            </div>
          </div>
          <div class="col-sm-6 col-md-4">
            <div class="widget dark">
              <h5 class="widget-title line-bottom-theme-colored-2">Blogs</h5>
              <div class="latest-posts">
                @if(count($menuarticles) > 0)
                  @foreach($menuarticles as $article)
                  <article class="post media-post clearfix pb-0 mb-10">
                    <a class="post-thumb" href="#"><amp-img width="80" height="55" src="{{ asset('backend/uploads/articles/'.$article->picture) }}" alt=""></amp-img></a>
                    <div class="post-right">
                      <h5 class="post-title mt-0 mb-5"><a href="{{ route('article', [$article->id,str_slug($article->title)]) }}">{{$article->title}}</a></h5>
                      <p class="post-date mb-0 font-12">{{ date('M d, Y', strtotime($article->created_at) )}}</p>
                    </div>
                  </article>
                  @endforeach
                @endif
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-4">
            <div class="widget dark">
              <h5 class="widget-title line-bottom-theme-colored-2">Procedures</h5>
              <div class="latest-posts">
                @if(count($menutreatments) > 0)
                  @php $i = 0 @endphp
                  @foreach($menutreatments as $t)
                  @php $i++; @endphp
                  <article class="post media-post clearfix pb-0 mb-10">
                    <a class="post-thumb" href="#"><amp-img width="80" height="55" src="{{ asset('backend/uploads/treatments/'.$t->picture) }}" alt=""></amp-img></a>
                    <div class="post-right">
                      <h5 class="post-title mt-0 mb-5"><a href="#">{{$t->name}}</a></h5>
                      <p class="post-date mb-0 font-12">{{ date('M d, Y', strtotime($t->created_at) )}}</p>
                    </div>
                  </article>
                  @if($i == 5)
                      @php break; @endphp
                  @endif
                  @endforeach
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="horizontal-contact-widget mt-30 pt-30 text-center">
              <div class="col-sm-12 col-sm-4">
                <div class="each-widget"> <i class="pe-7s-phone font-36 mb-10"></i>
                  <h5 class="text-white">Call Us</h5>
                  <p>Phone: <a href="#">{{ $setting->mobile }}</a></p>
                </div>
              </div>
              <div class="col-sm-12 col-sm-4 mt-sm-50">
                <div class="each-widget"> <i class="pe-7s-map font-36 mb-10"></i>
                  <h5 class="text-white">Address</h5>
                  <p>{{ $setting->address }}</p>
                </div>
              </div>
              <div class="col-sm-12 col-sm-4 mt-sm-50">
                <div class="each-widget"> <i class="pe-7s-mail font-36 mb-10"></i>
                  <h5 class="text-white">Email</h5>
                  <p><a href="#">{{ $setting->email }}</a></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <ul class="list-inline styled-icons icon-hover-theme-colored icon-gray icon-circled text-center mt-30 mb-10">
              <li><a href="{{ $setting->facebook }}" target="_blank"><i class="fa fa-facebook"></i></a> </li>
              <li><a href="{{ $setting->twitter }}" target="_blank"><i class="fa fa-twitter"></i></a> </li>
              <li><a href="{{ $setting->instagram }}" target="_blank"><i class="fa fa-instagram"></i></a> </li>
              <li><a href="{{ $setting->youtube }}" target="_blank"><i class="fa fa-youtube"></i></a> </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="container-fluid bg-theme-colored-top-bar p-20">
        <div class="row text-center">
          <div class="col-md-12">
            <p class="text-white font-11 m-0">Copyright &copy;2019 HospitALL. All Rights Reserved</p>
          </div>
        </div>
      </div>
    </footer>
    <a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
    <a href="{{ route('contact') }}" class="float">
     <i class="fa fa-phone my-float"></i>
    </a>
  </div>
@yield('scripts')
</body>
</html>

