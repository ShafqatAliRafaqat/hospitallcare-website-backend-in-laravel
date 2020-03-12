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

    @yield('content')

    <a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
    <a href="{{ url('https://hospitallcare.com/contact/') }}" class="customfloat"><i class="fa fa-phone my-float"></i></a>
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

