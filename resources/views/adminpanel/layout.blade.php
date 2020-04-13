<?php
use Illuminate\Support\Facades\DB;
?>
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
    <!-- Animate css stylesheet -->
    <link rel="stylesheet" href="{{ asset('backend/css/animate.css') }}">
    <!-- Sweet Alert stylesheet -->
    <link rel="stylesheet" href="{{ asset('backend/js/sweetalert/sweetalert.css') }}">
    <!-- stylesheet for Image upload -->
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap-imageupload.css') }}">
    <!-- dataTables stylesheet -->
    <link rel="stylesheet" href="{{ asset('backend/js/datatables/css/datatables.min.css') }}">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="{{ asset('backend/css/style.default.css') }}" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="{{ asset('backend/css/custom.css') }}">
    <!-- Custom stylesheet - for Date Range picker -->
    <link rel="stylesheet" href="{{ asset('backend/css/daterangepicker.css') }}">
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
    <header class="header header-font">
      <nav class="navbar navbar-expand-lg px-4 py-2 bg-dark shadow">
        <a href="#" class="sidebar-toggler text-gray-500 mr-4 mr-lg-5 lead a-coral "><i class="fas fa-align-left"></i></a><a href="{{route('adminDashboard')}}" class="navbar-brand font-weight-bold text-uppercase text-base a-coral">
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
            @php
              $user_id            =   Auth::user()->id;
              $picture_name       =   DB::table('users_images')->where('user_id',$user_id)->select('picture')->first();
            @endphp
            @if(isset($picture_name))
            <img src="{{ asset('backend/uploads/users/' . $picture_name->picture) }}" alt="Jason Doe" style="max-width: 2.5rem;" class="img-fluid rounded-circle shadow">
            @else
            <img src="{{ asset('backend/img/avatar-6.jpg') }}" alt="Jason Doe" style="max-width: 2.5rem;" class="img-fluid rounded-circle shadow">
            @endif
          </a>
            <div aria-labelledby="userInfo" class="dropdown-menu"><a href="#" class="dropdown-item"><strong class="d-block text-uppercase headings-font-family">{{Auth::user()->name}}</strong>
              <small>
              @if (Auth::user()->hasRole('admin'))
              Admin
              @elseif (Auth::user()->hasRole('coordinator'))
              Coordinator
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
      <div id="sidebar" class="sidebar py-3 bg-dark sidebar-font">
        <div class="text-gray-400 text-uppercase px-3 px-lg-4 py-4 font-weight-bold small headings-font-family">MAIN</div>
          <ul class="sidebar-menu list-unstyled">
              <li class="sidebar-list-item">
                <a href="{{ route('adminDashboard') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == '' ? 'active' : ''}}"><i class="o-home-1 mr-3 text-gray"></i><span>Home</span></a>
              </li>

              @can('view_treatment')
              <li class="sidebar-list-item">
                <a href="#" data-toggle="collapse" data-target="#view_treatment" aria-expanded="false"
                aria-controls="view_treatment" class="sidebar-link sidebar-text-bright">
                <i class="fab fa-creative-commons-sampling-plus mr-3 text-gray"></i><span>Treatments</span></a>
                <div id="view_treatment" class="collapse {{ ( Request::segment(2) == 'treatment' OR Request::segment(2) == 'procedure' )  ? 'show' : ''}}">
                  <ul class="sidebar-menu list-unstyled border-left border-sidebar border-thick">
                    <li class="sidebar-list-item">
                      <a href="{{ route('treatment.index') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'treatment' ? 'active' : ''}}">Treatments</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('procedure.index') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'procedure' ? 'active' : ''}}">Procedures</a></li>
                  </ul>
                </div>
              </li>
              @endcan
            @can('view_treatment')
            <li class="sidebar-list-item">
              <a href="{{ route('diagnostics.index') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'diagnostics' ? 'active' : ''}}"><i class="fas fa-vial  mr-3 text-gray"></i>
                <span>Diagnostics</span>
              </a>
            </li>
            @endcan
              @can('view_medical_centers')
              <li class="sidebar-list-item">
                <a href="{{ route('labs.index') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'labs' ? 'active' : ''}}"><i class="fa fa-flask  mr-3 text-gray"></i>
                  <span>Labs</span>
                </a>
              </li>
              @endcan
              @can('view_medical_centers')
              <li class="sidebar-list-item">
                  <a href="#" data-toggle="collapse" data-target="#view_pending_center" aria-expanded="false"
                  aria-controls="view_pending_center" class="sidebar-link sidebar-text-bright">
                  <i class="fas fa-stethoscope mr-3 text-gray"></i><span>Centers</span></a>
                  <div id="view_pending_center" class="collapse {{ ( Request::segment(2) == 'medical' OR Request::segment(2) == 'temporary_centers' )  ? 'show' : ''}}">
                    <ul class="sidebar-menu list-unstyled border-left border-sidebar border-thick">
                      <li class="sidebar-list-item">
                        <a href="{{ route('medical.index') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'medical' ? 'active' : ''}}"> <span>All Centers</span></a></li>
                      <li class="sidebar-list-item">
                        <a href="{{ route('temp_centers') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'temporary_centers' ? 'active' : ''}}">pending Centers</a></li>
                    </ul>
                  </div>
                </li>

                <li class="sidebar-list-item">
                    <a href="#" data-toggle="collapse" data-target="#view_pending_doctor" aria-expanded="false"
                    aria-controls="view_pending_doctor" class="sidebar-link sidebar-text-bright">
                    <i class="fa fa-user-md mr-3 text-gray"></i><span>Doctors</span></a>
                    <div id="view_pending_doctor" class="collapse {{ ( Request::segment(2) == 'doctors' OR Request::segment(2) == 'temporary_doctors' OR Request::segment(2) == 'doctor_schedules' )  ? 'show' : ''}}">
                      <ul class="sidebar-menu list-unstyled border-left border-sidebar border-thick">
                        <li class="sidebar-list-item">
                          <a href="{{ route('doctors.index') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{  (Request::segment(2) == 'doctors' OR Request::segment(2) == 'doctor_schedules') ? 'active' : ''}}"> <span>All Doctors</span></a>
                        </li>
                        <li class="sidebar-list-item">
                          <a href="{{ route('temp_doctors') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'temporary_doctors' ? 'active' : ''}}">pending Doctors</a>
                        </li>
                      </ul>
                    </div>
                  </li>
              @endcan
<!--               @can('view_packages')
              <li class="sidebar-list-item">
                <a href="{{ route('packages.index') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'packages' ? 'active' : ''}}"><i class="o-survey-1 mr-3 text-gray"></i><span>Packages</span></a>
              </li>
              @endcan -->

              <!-- @can('view_video')
              <li class="sidebar-list-item">
                <a href="{{ route('videos.index') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'videos' ? 'active' : ''}}"><i class="o-paperwork-1 mr-3 text-gray"></i><span>Video</span></a>
              </li>
              @endcan -->

<!--               @can('view_whitepapers')
              <li class="sidebar-list-item">
                <a href="{{ route('whitepaper.index') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'whitepaper' ? 'active' : ''}}"><i class="o-stack-1 mr-3 text-gray"></i><span>Whitepapers</span></a>
              </li>
              @endcan
              @can('view_testimonials')
              <li class="sidebar-list-item">
                <a href="{{ route('testimonials.index') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'testimonials' ? 'active' : ''}}"><i class="o-stack-1 mr-3 text-gray"></i><span>Testimonials</span></a>
              </li>
              @endcan
              @can('view_faq')
              <li class="sidebar-list-item">
                <a href="{{ route('faqs.index') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'faqs' ? 'active' : ''}}"><i class="o-stack-1 mr-3 text-gray"></i><span>FAQ's</span></a>
              </li>
              @endcan -->
<!--               @can('view_invoices')
                <li class="sidebar-list-item">
                  <a href="{{ route('faqs.index') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'invoices' ? 'active' : ''}}"><i class="o-stack-1 mr-3 text-gray"></i><span>Invoices</span></a>
                </li>
              @endcan
              @can('view_sales_target')
                <li class="sidebar-list-item">
                  <a href="{{ route('faqs.index') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'invoices' ? 'active' : ''}}"><i class="o-stack-1 mr-3 text-gray"></i><span>Sales Target</span></a>
                </li>
              @endcan -->

<!--             @can('view_servaid_orders')
            <li class="sidebar-list-item">
              <a href="{{ route('servaid-orders') }}" class="sidebar-link sidebar-text-bright"><i class="o-stack-1 mr-3 text-gray"></i>
                <span>Servaid Orders</span>
              </a>
            </li>
            @endcan -->

            @can('view_customers')
              <li class="sidebar-list-item">
                <a href="#" data-toggle="collapse" data-target="#view_customers" aria-expanded="false"
                aria-controls="view_customers" class="sidebar-link sidebar-text-bright">
                <i class="fas fa-users mr-3 text-gray"></i><span>Customers</span></a>
                <div id="view_customers" class="collapse {{ ( Request::segment(2) == 'corporate' OR Request::segment(2) == 'customers' OR Request::segment(2) == 'general' OR Request::segment(2) == 'canceled_appointments' OR Request::segment(2) == 'no_show_customers' OR Request::segment(2) == 'CardHolders' OR Request::segment(2) == 'ongoing_procedures' OR Request::segment(2) == 'treatment_history' OR Request::segment(2) == 'customers-n p')  ? 'show' : ''}}">
                  <ul class="sidebar-menu list-unstyled border-left border-sidebar border-thick">
                    <li class="sidebar-list-item">
                      <a href="{{ route('customers.index') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ (Request::segment(2) == 'customers' OR Request::segment(2) == 'treatment_history' OR Request::segment(2) == 'customers-np') ? 'active' : ''}}">All</a></li>
                    <li class="sidebar-list-item">
                      <a href="{{ route('general_customers') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'general' ? 'active' : ''}}">General</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('corporate_customers') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'corporate' ? 'active' : ''}}">Corporate</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('no_show_customers') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'no_show_customers' ? 'active' : ''}}">No Show Customers</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('canceled_appointments') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'canceled_appointments' ? 'active' : ''}}">Canceled by Doctor</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('ongoing_procedures') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'ongoing_procedures' ? 'active' : ''}}">Ongoing Procedures</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('indexCardHolders') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'CardHolders' ? 'active' : ''}}">Card Holders</a></li>

                  </ul>
                </div>
              </li>
            @endcan
            @can('view_customers')
            <li class="sidebar-list-item">
                <a href="#" data-toggle="collapse" data-target="#view_leads" aria-expanded="false"
                aria-controls="view_leads" class="sidebar-link sidebar-text-bright">
                <i class="fas fa-exclamation-circle mr-3 text-gray"></i><span>Pending Leads</span></a>
                <div id="view_leads" class="collapse {{ ( Request::segment(2) == 'temporary_leads' OR Request::segment(2) == 'webleads' )  ? 'show' : ''}}">
                  <ul class="sidebar-menu list-unstyled border-left border-sidebar border-thick">
                    <li class="sidebar-list-item">
                      <a href="{{ route('temp_leads') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'temporary_leads' ? 'active' : ''}}"> <span>Facebook Leads</span></a></li>
                    <li class="sidebar-list-item">
                      <a href="{{ route('webleads.index') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'webleads' ? 'active' : ''}}">Website Leads</a></li>
                  </ul>
                </div>
              </li>
            @endcan
            @can('view_customers')
            <li class="sidebar-list-item">
                <a href="#" data-toggle="collapse" data-target="#view_pending_appointments" aria-expanded="false"
                aria-controls="view_pending_appointments" class="sidebar-link sidebar-text-bright">
                <i class="fas fa-hourglass-half   mr-3 text-gray"></i></i><span>Pending Appointments</span></a>
                <div id="view_pending_appointments" class="collapse {{ ( Request::segment(2) == 'pendingappointments' OR Request::segment(2) == 'pendingdiagnostics' )  ? 'show' : ''}}">
                  <ul class="sidebar-menu list-unstyled border-left border-sidebar border-thick">
                    <li class="sidebar-list-item">
                      <a href="{{ route('pendingappointments.index') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'pendingappointments' ? 'active' : ''}}"> <span>Treatments</span></a></li>
                    <li class="sidebar-list-item">
                      <a href="{{ route('pendingdiagnostics.index') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'pendingdiagnostics' ? 'active' : ''}}">Diagnostics</a></li>
                  </ul>
                </div>
              </li>
            @endcan

<!--               @can('view_customers')
                <li class="sidebar-list-item">
                  <a href="{{ route('pendingappointments.index') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'pendingappointments' ? 'active' : ''}}"><i class="fas fa-hourglass-half   mr-3 text-gray"></i>
                    <span>Pending Appointments</span>
                  </a>
                </li>
              @endcan
              @can('view_customers')
                <li class="sidebar-list-item">
                  <a href="{{ route('pendingdiagnostics.index') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'pendingdiagnostics' ? 'active' : ''}}"><i class="fas fa-hourglass-half   mr-3 text-gray"></i>
                    <span>Pending Diagnostics</span>
                  </a>
                </li>
              @endcan -->
            @can('organizations')
            <li class="sidebar-list-item">
              <a href="{{ route('organization.index') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'organization' ? 'active' : ''}}"><i class="fa fa-industry  mr-3 text-gray"></i>
                <span>Organizations</span>
              </a>
            </li>
            @endcan
              @can('media_hub')
              <li class="sidebar-list-item">
                <a href="#" data-toggle="collapse" data-target="#view_mediahub" aria-expanded="false"
                aria-controls="view_mediahub" class="sidebar-link sidebar-text-bright">
                <i class="fa fa-file-video mr-3 text-gray " aria-hidden="true"></i><span>Media Hub</span></a>
                <div id="view_mediahub" class="collapse {{ ( Request::segment(2) == 'blogs' OR Request::segment(2) == 'blogcategory' OR Request::segment(2) == 'media' OR Request::segment(2) == 'vlogs')  ? 'show' : ''}}">
                  <ul class="sidebar-menu list-unstyled border-left border-sidebar border-thick">
                    <li class="sidebar-list-item"><a href="{{ route('blogcategory.index') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'blogcategory' ? 'active' : ''}}">Blog Category</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('blogs.index') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'blogs' ? 'active' : ''}}">Blogs</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('media.index') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'media' ? 'active' : ''}}">Media</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('vlogs.index') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'vlogs' ? 'active' : ''}}">Vlogs</a></li>
                  </ul>
                </div>
              </li>
              @endcan
            @can('media_hub')
            <li class="sidebar-list-item">
                <a href="#" data-toggle="collapse" data-target="#seo_import" aria-expanded="false"
                aria-controls="seo_import" class="sidebar-link sidebar-text-bright">
                <i class="fab fa-searchengin mr-3 text-gray"></i><span>SEO Imports</span></a>
                <div id="seo_import" class="collapse {{ ( Request::segment(2) == 'seo-doctor' OR Request::segment(2) == 'seo-treatment' OR Request::segment(2) == 'seo-center')  ? 'show' : ''}}">
                  <ul class="sidebar-menu list-unstyled border-left border-sidebar border-thick">
                    <li class="sidebar-list-item"><a href="{{ route('seo-doctor') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'seo-doctor' ? 'active' : ''}}">Doctor</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('seo-treatment') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'seo-treatment' ? 'active' : ''}}">Treatment</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('seo-center') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'seo-center' ? 'active' : ''}}">Center</a></li>
                  </ul>
                </div>
              </li>
            @endcan
            @can('view_status')
            <li class="sidebar-list-item">
              <a href="{{ route('status.index') }}" class="sidebar-link sidebar-text-bright {{ Request::segment(2) == 'status' ? 'active' : ''}}"><i class="fas fa-toggle-off mr-3 text-gray"></i>
                <span>Status</span>
              </a>
            </li>
            @endcan
          @can('import_customers')
            <li class="sidebar-list-item">
                <a href="#" data-toggle="collapse" data-target="#import_leads" aria-expanded="false"
                aria-controls="import_leads" class="sidebar-link sidebar-text-bright">
                <i class="fas fa-file-import  mr-3 text-gray"></i><span>Import Leads</span></a>
                <div id="import_leads" class="collapse {{ ( Request::segment(2) == 'import' OR Request::segment(2) == 'importpending' )  ? 'show' : ''}}">
                  <ul class="sidebar-menu list-unstyled border-left border-sidebar border-thick">
                    <li class="sidebar-list-item">
                      <a href="{{ route('importPending') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'importpending' ? 'active' : ''}}"> <span>Pending Leads</span></a></li>
                    <li class="sidebar-list-item">
                      <a href="{{ route('importData') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'import' ? 'active' : ''}}">Customers</a></li>
                  </ul>
                </div>
              </li>
            @endcan
            @can('view_user_management')
              <li class="sidebar-list-item">
                <a href="#" data-toggle="collapse" data-target="#users" aria-expanded="false"
                aria-controls="users" class="sidebar-link sidebar-text-bright">
                <i class="o-wireframe-1 mr-3 text-gray"></i><span>User Management</span></a>
                <div id="users" class="collapse {{ ( Request::segment(2) == 'roles' OR Request::segment(2) == 'users' OR Request::segment(2) == 'permissions' OR Request::segment(2) == 'center_users' OR Request::segment(2) == 'doctor_users' OR Request::segment(2) == 'web_users' ) ? 'show' : ''}}">
                  <ul class="sidebar-menu list-unstyled border-left border-sidebar border-thick">
                    <li class="sidebar-list-item"><a href="{{ route('roles.index') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'roles' ? 'active' : ''}}">Roles</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('permissions.index') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'permissions' ? 'active' : ''}}">Permissions</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('users.index') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'users' ? 'active' : ''}}">Users</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('center_users.index') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'center_users' ? 'active' : ''}}">Centers</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('doctor_users.index') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'doctor_users' ? 'active' : ''}}">Doctors</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('web_users') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'web_users' ? 'active' : ''}}">Web Users</a></li>
                  </ul>
                </div>
              </li>
              @endcan
              @can('view_user_management')
              <li class="sidebar-list-item">
                <a href="#" data-toggle="collapse" data-target="#delete" aria-expanded="false"
                aria-controls="delete" class="sidebar-link sidebar-text-bright">
                <i class="fa fa-trash-alt mr-3 text-gray"></i><span>Deleted Data</span></a>
                <div id="delete" class="collapse {{ ( Request::segment(2) == 'show_status' OR Request::segment(2) == 'show_customer' OR Request::segment(2) == 'show_lab' OR Request::segment(2) == 'show_diagnostic'
                                                   OR Request::segment(2) == 'show_user' OR Request::segment(2) == 'show_center' OR Request::segment(2) == 'show_treatment' OR Request::segment(2) == 'show_procedure'
                                                   OR Request::segment(2) == 'show_doctor' OR Request::segment(2) == 'show_organization' OR Request::segment(2) == 'blogcategory_show_deleted' OR Request::segment(2) == 'blog_show_deleted'
                                                   OR Request::segment(2) == 'media_show_deleted' OR Request::segment(2) == 'vlog_show_deleted' ) ? 'show' : ''}}">
                  <ul class="sidebar-menu list-unstyled border-left border-sidebar border-thick">
                    <li class="sidebar-list-item"><a href="{{ route('status_show_deleted') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'show_status' ? 'active' : ''}}">Status</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('customer_show_deleted') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'show_customer' ? 'active' : ''}}">Customers</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('treatment_show_deleted') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'show_treatment' ? 'active' : ''}}">Treatments</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('procedure_show_deleted') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'show_procedure' ? 'active' : ''}}">Procedures</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('doctor_show_deleted') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'show_doctor' ? 'active' : ''}}">Doctors</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('center_show_deleted') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'show_center' ? 'active' : ''}}">Centers</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('lab_show_deleted') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'show_lab' ? 'active' : ''}}">Labs</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('diagnostic_show_deleted') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'show_diagnostic' ? 'active' : ''}}">Diagnostics</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('user_show_deleted') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'show_user' ? 'active' : ''}}">Users</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('organization_show_deleted') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'show_organization' ? 'active' : ''}}">Organization</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('blogcategory_show_deleted') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'blogcategory_show_deleted' ? 'active' : ''}}">Blog Category</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('blog_show_deleted') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'blog_show_deleted' ? 'active' : ''}}">Blogs</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('media_show_deleted') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'media_show_deleted' ? 'active' : ''}}">Media</a></li>
                    <li class="sidebar-list-item"><a href="{{ route('vlog_show_deleted') }}" class="sidebar-link sidebar-text-bright pl-lg-5 {{ Request::segment(2) == 'vlog_show_deleted' ? 'active' : ''}}">Vlogs</a></li>
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
    <script src="{{ asset('backend/js/daterangepicker.js') }}"> </script>
    <script src="{{ asset('backend/js/jquery.cookie/jquery.cookie.js') }}"> </script>
    <script src="{{ asset('backend/js/datatables/js/datatables.min.js') }}"> </script>
    <script src="{{ asset('backend/js/daterangepicker.js') }}"> </script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script src="{{ asset('backend/js/front.js') }}"></script>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBv_Pb3XYIUpJ9J7L6hmXhHQ8DaqoMN2UI&libraries=places&callback=initAutocomplete"async defer></script> -->
    @yield('scripts')
  </body>
</html>
