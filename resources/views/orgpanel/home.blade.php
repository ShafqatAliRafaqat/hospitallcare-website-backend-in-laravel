@extends('orgpanel.layout')
@section('title', 'Home')
@section('content')
<div class="container-fluid px-xl-5">
  @can('view_dashboard')
    @if ( Auth::user()->hasRole('coordinator') )
      @include('adminpanel.cordinator-dashboard')
    @elseif ( Auth::user()->hasRole('organization_hr') )
      @include('adminpanel.admin-dashboard')
    @elseif ( Auth::user()->hasRole('organization_admin'))
      @include('orgpanel.org_panel.organizational-dashboard')
    @else
      <section class="py-5">
        <div class="row">
          <div class="col-xl-3 col-lg-6 mb-4 mb-xl-0">
            <div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
              <div class="flex-grow-1 d-flex align-items-center">
                <div class="dot mr-3 bg-violet"></div>
                <div class="text">
                  <h6 class="mb-0">Data consumed</h6><span class="text-gray">145,14 GB</span>
                </div>
              </div>
              <div class="icon text-white bg-violet"><i class="fas fa-server"></i></div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-6 mb-4 mb-xl-0">
            <div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
              <div class="flex-grow-1 d-flex align-items-center">
                <div class="dot mr-3 bg-green"></div>
                <div class="text">
                  <h6 class="mb-0">Open cases</h6><span class="text-gray">32</span>
                </div>
              </div>
              <div class="icon text-white bg-green"><i class="far fa-clipboard"></i></div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-6 mb-4 mb-xl-0">
            <div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
              <div class="flex-grow-1 d-flex align-items-center">
                <div class="dot mr-3 bg-blue"></div>
                <div class="text">
                  <h6 class="mb-0">Work orders</h6><span class="text-gray">400</span>
                </div>
              </div>
              <div class="icon text-white bg-blue"><i class="fa fa-dolly-flatbed"></i></div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-6 mb-4 mb-xl-0">
            <div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
              <div class="flex-grow-1 d-flex align-items-center">
                <div class="dot mr-3 bg-red"></div>
                <div class="text">
                  <h6 class="mb-0">New invoices</h6><span class="text-gray">123</span>
                </div>
              </div>
              <div class="icon text-white bg-red"><i class="fas fa-receipt"></i></div>
            </div>
          </div>
        </div>
      </section>
      <section>
        <div class="row mb-4">
          <div class="col-lg-7 mb-4 mb-lg-0">
            <div class="card">
              <div class="card-header">
                <h2 class="h6 text-uppercase mb-0">Current server uptime</h2>
              </div>
              <div class="card-body">
                <p class="text-gray">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                <div class="chart-holder">
                  <canvas id="lineChart1" style="max-height: 14rem !important;" class="w-100"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-5 mb-4 mb-lg-0 pl-lg-0">
            <div class="card mb-3">
              <div class="card-body">
                <div class="row align-items-center flex-row">
                  <div class="col-lg-5">
                    <h2 class="mb-0 d-flex align-items-center"><span>86.4</span><span class="dot bg-green d-inline-block ml-3"></span></h2><span class="text-muted text-uppercase small">Work hours</span>
                    <hr><small class="text-muted">Lorem ipsum dolor sit</small>
                  </div>
                  <div class="col-lg-7">
                    <canvas id="pieChartHome1"></canvas>
                  </div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <div class="row align-items-center flex-row">
                  <div class="col-lg-5">
                    <h2 class="mb-0 d-flex align-items-center"><span>1.724</span><span class="dot bg-violet d-inline-block ml-3"></span></h2><span class="text-muted text-uppercase small">Server time</span>
                    <hr><small class="text-muted">Lorem ipsum dolor sit</small>
                  </div>
                  <div class="col-lg-7">
                    <canvas id="pieChartHome2"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    @endif
  @endcan
</div>
@endsection
@section('scripts')
<script src="{{ asset('backend/js/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('backend/js/charts-home.js') }}"></script>
<script>
  $(document).ready(function() {
      $('#customers').DataTable();
  });
</script>
@endsection
