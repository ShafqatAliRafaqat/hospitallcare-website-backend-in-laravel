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
    <!-- Sweet Alert stylesheet -->
    <link rel="stylesheet" href="{{ asset('backend/js/sweetalert/sweetalert.css') }}">
    <!-- dataTables stylesheet -->
    <link rel="stylesheet" href="{{ asset('backend/js/datatables/css/datatables.min.css') }}">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="{{ asset('backend/css/style.default.css') }}" id="theme-stylesheet">
    <!-- Custom stylesheet - for Date Range picker -->
    <link rel="stylesheet" href="{{ asset('backend/css/daterangepicker.css') }}">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kalam|Merienda|Orbitron|Philosopher&display=swap" rel="stylesheet">
    @yield('styles')
  </head>
  <body>
    <!-- navbar-->
    <header class="header">
      <nav class="navbar navbar-expand-lg px-4 py-2 bg-dark shadow">
        <a href="#" class="sidebar-toggler text-gray-500 mr-4 mr-lg-5 lead a-coral">
          HospitALL Care
        <i class="fas fa-align-left"></i>
        </a>
          <a href="{{route('adminDashboard')}}" class="navbar-brand font-weight-bold text-uppercase text-base a-coral">
          @if (Auth::user()->hasRole('admin'))
            Procedures Dashboard
          @elseif (Auth::user()->hasRole('servaid'))
            Servaid HospitALL Dashboard
          @elseif (Auth::user()->hasRole('coordinator'))
            CRM Dashboard
          @elseif (Auth::user()->hasRole('organization_admin'))
            Organization's Dashboard
          @else
            Procedure Dashboard
          @endif
        </a>
        <ul class="ml-auto d-flex align-items-center list-unstyled mb-0">
          <!-- <li class="nav-item">
            <form id="searchForm" class="ml-auto d-none d-lg-block">
              <div class="form-group position-relative mb-0">
                <button type="submit" style="top: -3px; left: 0;" class="position-absolute bg-white border-0 p-0"><i class="o-search-magnify-1 text-gray text-lg"></i></button>
                <input type="search" placeholder="Search ..." class="form-control form-control-sm border-0 no-shadow pl-4">
              </div>
            </form>
          </li> -->
          <!-- <li class="nav-item dropdown mr-3"><a id="notifications" href="http://example.com" data-toggle="dropdown"
             aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle text-gray-400 px-1"><i class="fa fa-bell"></i><span class="notification-icon"></span></a>
            <div aria-labelledby="notifications" class="dropdown-menu"><a href="#" class="dropdown-item">

              <div class="dropdown-divider"></div><a href="#" class="dropdown-item text-center"><small class="font-weight-bold headings-font-family text-uppercase">View all notifications</small></a>
            </div>
          </li> -->
          <li class="nav-item dropdown ml-auto"><a id="userInfo" href="http://example.com" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
            <img src="{{ asset('backend/img/avatar-6.jpg') }}" alt="Jason Doe" style="max-width: 2.5rem;" class="img-fluid rounded-circle shadow"></a>
            <div aria-labelledby="userInfo" class="dropdown-menu"><a href="#" class="dropdown-item"><strong class="d-block text-uppercase headings-font-family">{{Auth::user()->name}}</strong>
              <small>
              @if (Auth::user()->hasRole('admin'))
              Admin
              @elseif (Auth::user()->hasRole('coordinator'))
              Coordinator
              @elseif (Auth::user()->hasRole('organization_admin'))
              Organizational Admin
              @else
              Portal Manager
              @endif
            </small></a>
              <div class="dropdown-divider"></div><a href="{{ route('settings.index') }}" class="dropdown-item">Settings</a><a href="#" class="dropdown-item">Activity log       </a>
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
                <a href="{{ route('adminDashboard') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == '' ? 'active' : ''}}"><i class="o-home-1 mr-3 text-gray"></i><span>Home</span></a>
              </li>
            @can('view_employee')
            <li class="sidebar-list-item">
                <a href="#" data-toggle="collapse" data-target="#employees" aria-expanded="false"
                aria-controls="employee_status" class="sidebar-link sidebar-text-bright">
                <i class="fa fa-users mr-3 text-gray"></i><span>Employees</span></a>
                <div id="employees" class="collapse {{ ( Request::routeIs('active-employees') OR Request::routeIs('pending-employees') ) ? 'show' : ''}}">
                  <ul class="sidebar-menu list-unstyled border-left border-sidebar border-thick">
                      <li class="sidebar-list-item">
                        <a href="{{ route('active-employees') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'active-employees' ? 'active' : ''}}"> <span>Active</span></a></li>
                      <li class="sidebar-list-item">
                        <a href="{{ route('pending-employees') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'pending-employees' ? 'active' : ''}}">Pending</a></li>
                  </ul>
                </div>
            </li>
            <!-- <li class="sidebar-list-item">
              <a href="{{ route('employees.index') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'employees' ? 'active' : ''}}">
                <span>
                Employees
              </span>
              </a>
            </li> -->
            @endcan
            @can('employee_status')
              <li class="sidebar-list-item">
                <a href="#" data-toggle="collapse" data-target="#employee_status" aria-expanded="false"
                aria-controls="employee_status" class="sidebar-link sidebar-text-bright">
                <i class="o-wireframe-1 mr-3 text-gray"></i><span>Employees Status</span></a>
                <div id="employee_status" class="collapse {{ ( Request::routeIs('employee-informed') OR Request::routeIs('employee-got-appointment') OR Request::routeIs('employee-took-appointment') OR Request::routeIs('employee-took-treatment') OR Request::routeIs('employee-no-contact') ) ? 'show' : ''}}">
                  <ul class="sidebar-menu list-unstyled border-left border-sidebar border-thick">
                    <li class="sidebar-list-item"><a href="{{ route('employee-informed') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::routeIs('employee-informed') ? 'active' : '' }}">Informed</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('employee-got-appointment') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::routeIs('employee-got-appointment') ? 'active' : '' }}">Booked Appointment</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('employee-took-appointment') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::routeIs('employee-took-appointment') ? 'active' : '' }}">Got Appointment</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('employee-took-treatment') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::routeIs('employee-took-treatment') ? 'active' : '' }}">Got Treatment</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('employee-no-contact') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::routeIs('employee-no-contact') ? 'active' : '' }}">No Contact</a></li>
                  </ul>
                </div>
              </li>
              @endcan
              @can('employee_status')
              <li class="sidebar-list-item">
                <a href="#" data-toggle="collapse" data-target="#employee_diagnostic" aria-expanded="false"
                aria-controls="employee_diagnostic" class="sidebar-link sidebar-text-bright">
                <i class="fas fa-vial  mr-3 text-gray"></i><span>Diagnostic</span></a>
                <div id="employee_diagnostic" class="collapse {{ ( Request::routeIs('today-diagnostic') OR Request::routeIs('upcoming-diagnostic') OR Request::routeIs('previous-diagnostic') ) ? 'show' : ''}}">
                  <ul class="sidebar-menu list-unstyled border-left border-sidebar border-thick">
                    <li class="sidebar-list-item"><a href="{{ route('today-diagnostic') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::routeIs('today-diagnostic') ? 'active' : '' }}">Today Diagnostic</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('upcoming-diagnostic') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::routeIs('upcoming-diagnostic') ? 'active' : '' }}">Upcoming Diagnostic</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('previous-diagnostic') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::routeIs('previous-diagnostic') ? 'active' : '' }}">Previous Diagnostic</a></li>
                   </ul>
                </div>
              </li>
              @endcan
            @can('view_employee')
            <li class="sidebar-list-item">
                <a href="#" data-toggle="collapse" data-target="#medical_claim" aria-expanded="false"
                aria-controls="medical_claim" class="sidebar-link sidebar-text-bright">
                <i class="fas fa-tasks mr-3 text-gray"></i><span>Medical Claims</span></a>
                <div id="medical_claim" class="collapse {{ ( Request::routeIs('pending_claims') OR Request::routeIs('all_claims') OR Request::routeIs('edit_claim')) ? 'show' : ''}}">
                  <ul class="sidebar-menu list-unstyled border-left border-sidebar border-thick">
                      <li class="sidebar-list-item">
                        <a href="{{ route('pending_claims') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'pending_claims' ? 'active' : ''}}"> <span>Pending Claims</span></a></li>
                      <li class="sidebar-list-item">
                        <a href="{{ route('all_claims') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'all_claims' ? 'active' : ''}}">All Claims</a></li>
                  </ul>
                </div>
            </li>
            @endcan
            @can('import_employee')
            <li class="sidebar-list-item">
              <a href="{{ route('importEmployee') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'importemp' ? 'active' : ''}}"><i class="fa fa-file-import mr-3 text-gray"></i>
                <span>Import Employees</span>
              </a>
            </li>
            @endcan
            <li class="sidebar-list-item">
              <a href="{{ route('notuploaded.index') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'notuploaded' ? 'active' : ''}}"><i class="fa fa-exclamation-circle mr-3 text-gray"></i>
                <span>Pending Employees</span>
              </a>
            </li>
            @can('view_employee')
            <li class="sidebar-list-item">
                <a href="#" data-toggle="collapse" data-target="#deleted_claims" aria-expanded="false"
                aria-controls="deleted_claims" class="sidebar-link sidebar-text-bright">
                <i class="fa fa-trash-alt mr-3 text-gray"></i><span>Deleted Data</span></a>
                <div id="deleted_claims" class="collapse {{ Request::routeIs('show_deleted_claim') ? 'show' : ''}}">
                  <ul class="sidebar-menu list-unstyled border-left border-sidebar border-thick">
                      <li class="sidebar-list-item">
                        <a href="{{ route('show_deleted_claim') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'delete_claim' ? 'active' : ''}}"> <span>Claims</span></a>
                      </li>
                  </ul>
                </div>
            </li>
            @endcan
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
    <script src="{{ asset('backend/js/daterangepicker.js') }}"> </script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script src="{{ asset('backend/js/front.js') }}"></script>
    @yield('scripts')
  </body>
</html>
