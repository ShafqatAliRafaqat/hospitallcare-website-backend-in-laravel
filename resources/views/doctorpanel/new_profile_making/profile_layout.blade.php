<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <!-- Google fonts - Popppins for copy-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,800">
    <!-- orion icons-->
    <link rel="stylesheet" href="{{ asset('backend/css/orionicons.css') }}">
    <!-- dataTables stylesheet -->
    <link rel="stylesheet" href="{{ asset('backend/js/datatables/css/datatables.min.css') }}">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="{{ asset('backend/css/style.default.css') }}" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="{{ asset('backend/css/custom.css') }}">
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{ asset('backend/img/favicon.ico') }}">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
          <!-- Favicon and Touch Icons -->
  <link href="{{ asset('inc/images/favicon.ico') }}" rel="shortcut icon" type="image/png">
  <link href="{{ asset('inc/images/apple-touch-icon.png') }}') }}" rel="icon">
  <link href="{{ asset('inc/images/apple-touch-icon-72x72.png') }}') }}" rel="icon" sizes="72x72">
  <link href="{{ asset('inc/images/apple-touch-icon-114x114.png') }}') }}" rel="icon" sizes="114x114">
  <link href="{{ asset('inc/images/apple-touch-icon-144x144.png') }}') }}" rel="icon" sizes="144x144">

  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
<link rel="stylesheet" href="{{asset('backend/css/custom.css')}}">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    @yield('styles')
  </head>
  <body>
    <!-- navbar-->
    <header class="header">
      <nav class="navbar navbar-expand-lg px-4 py-2 bg-dark shadow">
        <a href="#" class="sidebar-toggler text-gray-500 mr-4 mr-lg-5 lead a-coral">
          <i class="fas fa-align-left"></i>
        </a>
          <a href="{{route('adminDashboard')}}" class="navbar-brand font-weight-bold text-uppercase text-base a-coral">
          @if (Auth::user()->hasRole('doctor_profile'))
            Profile Completion
          @else
            Procedure Dashboard
          @endif
        </a>
        <ul class="ml-auto d-flex align-items-center list-unstyled mb-0">
          <li class="nav-item dropdown mr-3"><a id="notifications" href="http://example.com" data-toggle="dropdown"
             aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle text-gray-400 px-1"><i class="fa fa-bell"></i><span class="notification-icon"></span></a>
            <div aria-labelledby="notifications" class="dropdown-menu"><a href="#" class="dropdown-item">
                <div class="d-flex align-items-center">
                  <div class="icon icon-sm bg-violet text-white"><i class="fab fa-twitter"></i></div>
                  <div class="text ml-2">
                    <p class="mb-0">You have 2 followers</p>
                  </div>
                </div></a><a href="#" class="dropdown-item">
                <div class="d-flex align-items-center">
                  <div class="icon icon-sm bg-green text-white"><i class="fas fa-envelope"></i></div>
                  <div class="text ml-2">
                    <p class="mb-0">You have 6 new messages</p>
                  </div>
                </div></a><a href="#" class="dropdown-item">
                <div class="d-flex align-items-center">
                  <div class="icon icon-sm bg-blue text-white"><i class="fas fa-upload"></i></div>
                  <div class="text ml-2">
                    <p class="mb-0">Server rebooted</p>
                  </div>
                </div></a><a href="#" class="dropdown-item">
                <div class="d-flex align-items-center">
                  <div class="icon icon-sm bg-violet text-white"><i class="fab fa-twitter"></i></div>
                  <div class="text ml-2">
                    <p class="mb-0">You have 2 followers</p>
                  </div>
                </div></a>
              <div class="dropdown-divider"></div><a href="#" class="dropdown-item text-center"><small class="font-weight-bold headings-font-family text-uppercase">View all notifications</small></a>
            </div>
          </li>
          <li class="nav-item dropdown ml-auto"><a id="userInfo" href="http://example.com" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
            <img src="{{ asset('backend/img/avatar-6.jpg') }}" alt="Jason Doe" style="max-width: 2.5rem;" class="img-fluid rounded-circle shadow"></a>
            <div aria-labelledby="userInfo" class="dropdown-menu"><a href="#" class="dropdown-item"><strong class="d-block text-uppercase headings-font-family">{{Auth::user()->name}}</strong>
              <small>
                @if (Auth::user()->hasRole('admin'))
                  Admin
                @elseif (Auth::user()->hasRole('coordinator'))
                  Coordinator
                @elseif (Auth::user()->hasRole('doctor_admin'))
                  Doctor Admin
                @else
                  Portal Manager
                @endif
              </small>
            </a>
              <div class="dropdown-divider"></div>
              @can('view_clients')
              <a href="#" class="dropdown-item">Settings</a>
              @endcan
              <a href="#" class="dropdown-item">Activity log</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ route('logout') }}"
                 onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>
            </div>
          </li>
        </ul>
      </nav>
    </header>
    <div class="d-flex align-items-stretch">
      <div id="sidebar" class="sidebar py-3 bg-dark">
        <div class="text-gray-400 text-uppercase px-3 px-lg-4 py-4 font-weight-bold small headings-font-family">MAIN</div>
          <ul class="sidebar-menu list-unstyled">
              <li class="sidebar-list-item">
                <a href="{{ route('adminDashboard') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == '' ? 'active' : ''}}"><i class="o-home-1 mr-3 text-gray"></i><span>Profile</span></a>
              </li>
            </ul>
      </div>
      <div class="page-holder w-100 d-flex flex-wrap">

        @yield('content')

        <footer class="footer bg-dark shadow align-self-end py-3 px-xl-5 w-100">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6 text-center text-md-left a-coral">
                <p class="mb-2 mb-md-0">Hospitallcare &copy; 2018-2020</p>
              </div>
              <div class="col-md-6 text-center text-md-right a-coral">
                <p class="mb-0">Developed by <a href="https://hospitallcare.com" class="external text-gray-400">The Tech Team</a></p>
                <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="{{ asset('backend/js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/js/popper.js/umd/popper.min.js') }}"> </script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('backend/js/jquery.cookie/jquery.cookie.js') }}"> </script>
    <script src="{{ asset('backend/js/datatables/js/datatables.min.js') }}"> </script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script src="{{ asset('backend/js/front.js') }}"></script>
    @yield('scripts')
  </body>
</html>
