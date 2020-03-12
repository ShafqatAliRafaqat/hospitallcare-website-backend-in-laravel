<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>

  <!-- URL -->
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
  <meta name="description" content="Transforming the future of healthcare in Pakistan through meticulous attention to patient care while advocating for lower costs and treatment accessibility for all citizens in all parts of the country." />
  <meta name="keywords" content=" clinic, dental, doctor, health, hospital, medical, medical theme, medicine, therapy" />

  <!-- Page Title -->
  <title>@yield('title') Transforming the Healthcare in Pakistan | Hospitallcare.com </title>

  <!-- Favicon and Touch Icons -->
  <link href="{{ asset('inc/images/favicon.ico') }}" rel="shortcut icon" type="image/png">
  <link href="{{ asset('inc/images/apple-touch-icon.png') }}') }}" rel="icon">
  <link href="{{ asset('inc/images/apple-touch-icon-72x72.png') }}') }}" rel="icon" sizes="72x72">
  <link href="{{ asset('inc/images/apple-touch-icon-114x114.png') }}') }}" rel="icon" sizes="114x114">
  <link href="{{ asset('inc/images/apple-touch-icon-144x144.png') }}') }}" rel="icon" sizes="144x144">

  <!-- Stylesheet -->
  <link href="{{ asset('inc/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('inc/css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('inc/css/animate.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('inc/css/css-plugin-collections.css') }}" rel="stylesheet"/>
  <!-- CSS | menuzord megamenu skins -->
  <link id="menuzord-menu-skins" href="{{ asset('inc/css/menuzord-skins/menuzord-boxed.css') }}" rel="stylesheet"/>
  <!-- CSS | Main style file -->
  <link href="{{ asset('inc/css/style-main.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('inc/css/style.css') }}" rel="stylesheet" type="text/css">
  <!-- CSS | Preloader Styles -->
  <link href="{{ asset('inc/css/preloader.css') }}" rel="stylesheet" type="text/css">
  <!-- CSS | Custom Margin Padding Collection -->
  <link href="{{ asset('inc/css/custom-bootstrap-margin-padding.css') }}" rel="stylesheet" type="text/css">
  <!-- CSS | Responsive media queries -->
  <link href="{{ asset('inc/css/responsive.css') }}" rel="stylesheet" type="text/css">
  <!-- CSS | Style css. This is the file where you can place your own custom css code. Just uncomment it and use it. -->
  <!-- <link href="css/style.css" rel="stylesheet" type="text/css"> -->

  <!-- Revolution Slider 5.x CSS settings -->
  <link  href="{{ asset('inc/js/revolution-slider/css/settings.css') }}" rel="stylesheet" type="text/css"/>
  <link  href="{{ asset('inc/js/revolution-slider/css/layers.css') }}" rel="stylesheet" type="text/css"/>
  <link  href="{{ asset('inc/js/revolution-slider/css/navigation.css') }}" rel="stylesheet" type="text/css"/>

  <!-- CSS | Theme Color -->
  <link href="{{ asset('inc/css/colors/theme-skin-blue.css') }}" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="{{ asset('backend/css/custom_hospitall.css')}}">

  <!-- external javascripts -->

  <script src="{{ asset('inc/js/jquery-2.2.4.min.js') }}"></script>
  <script src="{{ asset('inc/js/jquery-ui.min.js') }}"></script>
  <script src="{{ asset('inc/js/bootstrap.min.js') }}"></script>
  <!-- JS | jquery plugin collection for this theme -->
  <script src="{{ asset('inc/js/jquery-plugin-collection.js') }}"></script>

  <!-- Revolution Slider 5.x SCRIPTS -->
  <script src="{{ asset('inc/js/revolution-slider/js/jquery.themepunch.tools.min.js') }}"></script>
  <script src="{{ asset('inc/js/revolution-slider/js/jquery.themepunch.revolution.min.js') }}"></script>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-131686520-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-131686520-1');
    </script>

  </head>
<body class="has-side-panel side-panel-right fullwidth-page side-push-panel">
  <div class="body-overlay"></div>

  <div id="wrapper" class="clearfix">

    <!-- preloader -->
    <div id="preloader" style="display: none;">
      <div id="spinner">
        <img src="{{ asset('inc/images/preloaders/1.gif') }}" alt="">
      </div>
      <div id="disable-preloader" class="btn btn-default btn-sm">Disable Preloader</div>
    </div>

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
                <img src='{{ asset("inc/images/hospitall.png") }}' alt="">
              </a>

              <ul class="menuzord-menu">
              <li class="{{ Request::segment(1) == 'products' ? 'active' : ''}}">

                <a href="#">Key Procedures</a>
                <div class="megamenu">
                  <div class="megamenu-row">
                    <div class="col3">
                      <ul class="list-unstyled list-dashed">
                        @php $num = 0; @endphp
                        @foreach($menutreatments as $t)
                        @if($num <= 3)
                        <li><a class="a-keypro-font" href="{{ route('procedure-detail', [$t->id,str_slug($t->name,'-')]) }}">{{ $t->name }}</a></li>
                        @php $num++; @endphp
                        @endif
                        @endforeach
                    </ul>

                    </div>
                    <div class="col3">
                      <ul class="list-unstyled list-dashed">
                      @foreach($menutreatments as $k => $t)
                      @php if ($k < 4) continue; @endphp
                      @if($num < 8 && $num > 3)
                        <li><a class="a-keypro-font" href="{{ route('procedure-detail', [$t->id,str_slug($t->name,'-')]) }}">{{ $t->name }}</a></li>
                      @php $num++; @endphp
                      @endif
                      @endforeach
                      </ul>

                    </div>
                    <div class="col3">
                      <ul class="list-unstyled list-dashed">
                      @foreach($menutreatments as $k => $t)
                      @php if ($k < 8) continue; @endphp
                      @if($num < 12 && $num > 7)
                        <li><a class="a-keypro-font" href="{{ route('procedure-detail', [$t->id,str_slug($t->name,'-')]) }}">{{ $t->name }}</a></li>
                      @php $num++; @endphp
                      @endif
                      @endforeach
                      </ul>
                    </div>
<!--                     <div class="col3">
                      <ul class="list-unstyled list-dashed">
                        <li><a href="shortcode-styled-icons.html"> Styled Icons</a></li>
                        <li><a href="shortcode-subscribe.html"> Subscribe</a></li>
                        <li><a href="shortcode-tables.html"> Tables</a></li>

                      </ul>
                    </div> -->
                    <div class="col3">
                      <img src="{{ asset('inc/images/doc-3.png') }}" alt="" class="img-reponsive">
                    </div>
                  </div>
                </div>
              </li>
<!--                 <li class="{{ Request::segment(1) == 'products' ? 'active' : ''}}">
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
                </li> -->
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
    <footer id="footer" class="footer pb-0" data-bg-img="{{ asset('inc/images/footer-bg.png') }}" data-bg-color="#25272e">
      <div class="container pt-90 pb-60">
        <div class="row">
          <div class="col-sm-6 col-md-4">
            <div class="widget dark"> <img alt="" src="{{ asset('inc/images/HospitALL-logo.png') }}">
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
                    <a class="post-thumb" href="#"><img width="80" height="55" src="{{ asset('backend/uploads/articles/'.$article->picture) }}" alt=""></a>
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
                    <a class="post-thumb" href="#"><img width="80" height="55" src="{{ asset('inc/images/banner/'. str_slug($t->name,'-').'.jpg' ) }}" alt=""></a>
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
    <a href="{{ route('contact') }}" class="customfloat"><i class="fa fa-phone my-float"></i></a>
  </div>
<!-- end wrapper -->

<!-- Footer Scripts -->
<!-- JS | Custom script for all pages -->
<script src="{{ asset('inc/js/custom.js') }}"></script>
<script src="{{ asset('inc/js/search.js') }}"></script>

<!-- SLIDER REVOLUTION 5.0 EXTENSIONS
      (Load Extensions only on Local File Systems !
       The following part can be removed on Server for On Demand Loading) -->
<script type="text/javascript" src="{{ asset('inc/js/revolution-slider/js/extensions/revolution.extension.actions.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('inc/js/revolution-slider/js/extensions/revolution.extension.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('inc/js/revolution-slider/js/extensions/revolution.extension.kenburn.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('inc/js/revolution-slider/js/extensions/revolution.extension.layeranimation.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('inc/js/revolution-slider/js/extensions/revolution.extension.migration.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('inc/js/revolution-slider/js/extensions/revolution.extension.navigation.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('inc/js/revolution-slider/js/extensions/revolution.extension.parallax.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('inc/js/revolution-slider/js/extensions/revolution.extension.slideanims.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('inc/js/revolution-slider/js/extensions/revolution.extension.video.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

@yield('scripts')
</body>
</html>

