<section class="py-5">
  <div class="row mb-3">
    <div class="col-xl-3 col-sm-6 py-2">
      <div class="card-total-customers bg-success text-white h-100">
        <div class="card-body-total-customers bg-success">
          <div class="rotate">
            <i class="fa fa-user fa-4x"></i>
          </div>
          <h6 class="text-uppercase text-meriende">Total Leads</h6>
          <h1 class="display-4 text-meriende count">{{ $sum_all_customers }}</h1>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 py-2">
      <div class="card-total-customers text-white bg-danger h-100">
        <div class="card-body-total-customers bg-danger">
          <div class="rotate">
            <i class="fa fa-list fa-4x"></i>
          </div>
          <h6 class="text-uppercase text-meriende">Pending Leads</h6>
          <h1 class="display-4 text-meriende count">{{ $total_leads }}</h1>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 py-2">
      <div class="card-total-customers text-white bg-info h-100">
        <div class="card-body-total-customers bg-info">
          <div class="rotate">
            <i class="far fa-hospital fa-4x"></i>

          </div>
          <h6 class="text-uppercase text-meriende">Centers</h6>
          <h1 class="display-4 text-meriende count">{{ $total_centers }}</h1>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 py-2">
      <div class="card-total-customers text-white bg-warning h-100">
        <div class="card-body-total-customers">
          <div class="rotate">

            <i class="fas fa-stethoscope fa-4x"></i>
          </div>
          <h6 class="text-uppercase text-meriende">Doctors</h6>
          <h1 class="display-4 text-meriende count">{{ $total_doctors }}</h1>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xl-4 col-lg-6 mb-4 mb-xl-0">
      <div class="bg-white shadow roundy p-3 d-flex align-items-center justify-content-between">
        <div class="flex-grow-1 d-flex align-items-center">
          <div class="dot mr-3 bg-violet"></div>
          <div class="text">

            <a class="leads-a" href="{{ route('ColdLeads') }}"><h6 class="mb-0">{{$stats[0]->name}} Leads</h6></a><span class="text-gray">{{ $stats[0]->total }}</span>
          </div>
        </div>
        <div class="icon text-white bg-violet"><i class="fas fa-snowflake"></i></div>
      </div>
    </div>
    <div class="col-xl-4 col-lg-6 mb-4 mb-xl-0">
      <div class="bg-white shadow roundy p-3 d-flex align-items-center justify-content-between">
        <div class="flex-grow-1 d-flex align-items-center">
          <div class="dot mr-3 bg-green"></div>
          <div class="text">
            <a class="leads-a" href="{{ route('WarmLeads') }}"><h6 class="mb-0">{{$stats[1]->name}} Leads</h6></a><span class="text-gray">{{ $stats[1]->total }}</span>
          </div>
        </div>
        <div class="icon text-white bg-green"><i class="fas fa-thermometer-three-quarters"></i></div>
      </div>
    </div>
    <div class="col-xl-4 col-lg-6 mb-4 mb-xl-0">
      <div class="bg-white shadow roundy p-3 d-flex align-items-center justify-content-between">
        <div class="flex-grow-1 d-flex align-items-center">
          <div class="dot mr-3 bg-blue"></div>
          <div class="text">
            <a class="leads-a" href="{{ route('HotLeads') }}"><h6 class="mb-0">{{$stats[2]->name}} Leads</h6></a><span class="text-gray">{{ $stats[2]->total }}</span>
          </div>
        </div>
        <div class="icon text-white bg-blue"><i class="fa fa-fire"></i></div>
      </div>
    </div>
  </div>

  <div class="row pt-4">
    <div class="col-xl-4 col-lg-4 mb-4 mb-xl-0">
      <div class="bg-white shadow roundy p-3 d-flex align-items-center justify-content-between">
        <div class="flex-grow-1 d-flex align-items-center">
          <div class="dot mr-3 bg-red"></div>
          <div class="text">
            <a class="leads-a" href="{{ route('CustomerLeads') }}"><h6 class="mb-0">{{$stats[3]->name}} Leads</h6></a><span class="text-gray">{{ $stats[3]->total }}</span>
          </div>
        </div>
        <div class="icon text-white bg-red"><i class="fas fa-receipt"></i></div>
      </div>
    </div>
    <div class="col-xl-4 col-lg-4 mb-4 mb-xl-0">
      <div class="bg-white shadow roundy p-3 d-flex align-items-center justify-content-between">
        <div class="flex-grow-1 d-flex align-items-center">
          <div class="dot mr-3 bg-red"></div>
          <div class="text">
            <a class="leads-a" href="{{ route('NoContactLeads') }}"><h6 class="mb-0">{{$stats[4]->name}} Leads</h6></a><span class="text-gray">{{ $stats[4]->total }}</span>
          </div>
        </div>
        <div class="icon text-white bg-red"><i class="fas fa-receipt"></i></div>
      </div>
    </div>
    <div class="col-xl-4 col-lg-4 mb-4 mb-xl-0">
      <div class="bg-white shadow roundy p-3 d-flex align-items-center justify-content-between">
        <div class="flex-grow-1 d-flex align-items-center">
          <div class="dot mr-3 bg-red"></div>
          <div class="text">
            <a class="leads-a" href="{{ route('DontCallLeads') }}"><h6 class="mb-0">{{$stats[5]->name}} Leads</h6></a><span class="text-gray">{{ $stats[5]->total }}</span>
          </div>
        </div>
        <div class="icon text-white bg-red"><i class="fas fa-receipt"></i></div>
      </div>
    </div>
  </div>
  <!--/row-->
  {{-- <div class="row">
    <div class="col-md-7">
      <div class="card shadow">
        <div class="card-header bg-transparent">
          <div class="row align-items-center">
            <div class="col">
              <h6 class="text-uppercase text-muted ls-1 mb-1">Total Leads Updated and Created by Patient Coordinators</h6>
              <h2 class="mb-0">Performance</h2>
            </div>
          </div>
        </div>
        <div class="card-body">
          <!-- Chart -->
          <div class="chart">
            <canvas data-owner_name="{{ json_encode($owner_status->pluck('name')) }}" data-owner_counts="{{ json_encode($owner_status->pluck('counts')) }}" data-sum_of_owners="{{$sum_of_owners}}" id="horizontalBar"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-5">
      <div class="row">

        <div class="col-md-12 mb-3">
          <div class="bg-white shadow roundy p-3 d-flex align-items-center justify-content-between">
            <div class="flex-grow-1 d-flex align-items-center">
              <div class="dot mr-3 bg-violet"></div>
              <div class="text">

                <a class="leads-a" href="{{ route('ColdLeads') }}"><h6 class="mb-0">{{$stats[0]->name}} Leads</h6></a><span class="text-gray">{{ $stats[0]->total }}</span>
              </div>
            </div>
            <div class="icon text-white bg-violet"><i class="fas fa-snowflake"></i></div>
          </div>
        </div>

        <div class="col-md-12 mb-3">
          <div class="bg-white shadow roundy p-3 d-flex align-items-center justify-content-between">
            <div class="flex-grow-1 d-flex align-items-center">
              <div class="dot mr-3 bg-green"></div>
              <div class="text">
                <a class="leads-a" href="{{ route('WarmLeads') }}"><h6 class="mb-0">{{$stats[1]->name}} Leads</h6></a><span class="text-gray">{{ $stats[1]->total }}</span>
              </div>
            </div>
            <div class="icon text-white bg-green"><i class="fas fa-thermometer-three-quarters"></i></div>
          </div>
        </div>

        <div class="col-md-12 mb-3">
          <div class="bg-white shadow roundy p-3 d-flex align-items-center justify-content-between">
            <div class="flex-grow-1 d-flex align-items-center">
              <div class="dot mr-3 bg-blue"></div>
              <div class="text">
                <a class="leads-a" href="{{ route('HotLeads') }}"><h6 class="mb-0">{{$stats[2]->name}} Leads</h6></a><span class="text-gray">{{ $stats[2]->total }}</span>
              </div>
            </div>
            <div class="icon text-white bg-blue"><i class="fa fa-fire"></i></div>
          </div>
        </div>

        <div class="col-md-12 mb-3">
          <div class="bg-white shadow roundy p-3 d-flex align-items-center justify-content-between">
            <div class="flex-grow-1 d-flex align-items-center">
              <div class="dot mr-3 bg-red"></div>
              <div class="text">
                <a class="leads-a" href="{{ route('CustomerLeads') }}"><h6 class="mb-0">{{$stats[3]->name}} Leads</h6></a><span class="text-gray">{{ $stats[3]->total }}</span>
              </div>
            </div>
            <div class="icon text-white bg-red"><i class="fas fa-receipt"></i></div>
          </div>
        </div>

        <div class="col-md-12 mb-3">
          <div class="bg-white shadow roundy p-3 d-flex align-items-center justify-content-between">
            <div class="flex-grow-1 d-flex align-items-center">
              <div class="dot mr-3 bg-red"></div>
              <div class="text">
                <a class="leads-a" href="{{ route('NoContactLeads') }}"><h6 class="mb-0">{{$stats[4]->name}} Leads</h6></a><span class="text-gray">{{ $stats[4]->total }}</span>
              </div>
            </div>
            <div class="icon text-white bg-red"><i class="fas fa-receipt"></i></div>
          </div>
        </div>

        <div class="col-md-12">
          <div class="bg-white shadow roundy p-3 d-flex align-items-center justify-content-between">
            <div class="flex-grow-1 d-flex align-items-center">
              <div class="dot mr-3 bg-red"></div>
              <div class="text">
                <a class="leads-a" href="{{ route('DontCallLeads') }}"><h6 class="mb-0">{{$stats[5]->name}} Leads</h6></a><span class="text-gray">{{ $stats[5]->total }}</span>
              </div>
            </div>
            <div class="icon text-white bg-red"><i class="fas fa-receipt"></i></div>
          </div>
        </div>

      </div>
    </div>
  </div> --}}

  <!-- Report Generation on Screen -->
  <div class="form-group pt-3">
    <h5 class="pb-2">Report Generation</h5>
    <form class="form-horizontal" method="post" action="{{ route('generatereport') }}">
      @csrf

      <div class="form-group row">
        <label class="col-md-2 form-control-label">Starting Date</label>
        <div class="col-md-4">
          <input type="date" id="startdate" name="start_date" placeholder="Start Date"
          class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}" value="{{ old('start_date') }}" required>

          @if($errors->has('start_date'))
          <div class="invalid-feedback ml-3">{{ $errors->first('start_date') }}</div>
          @endif
        </div>
        <label class="col-md-2 form-control-label">Ending Date</label>
        <div class="col-md-4">
          <input type="date" id="enddate" name="end_date" placeholder="End Date"
          class="form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}" value="{{ old('end_date') }}" required>

          @if($errors->has('end_date'))
          <div class="invalid-feedback ml-3">{{ $errors->first('end_date') }}</div>
          @endif
        </div>
      </div>
      <div class="row">
        <div class="col-md-2 form-control-label">Select Center</div>
        <div class="col-md-4" >
          <select   name="center_id" class="form-control selectpicker" data-live-search="true">
            <option value="">Select Center</option>
            @foreach($centers as $c)

            <option value="{{ $c->id }}">{{ $c->center_name }}</option>
            @endforeach
          </select>
          @if($errors->has('center_id'))
          <div class="invalid-feedback ml-3">{{ $errors->first('center_id') }}</div>
          @endif
        </div>

        <div class="col-md-2 form-control-label">Select Status</div>
        <div class="col-md-4" >
          <select   name="status_id" class="form-control selectpicker" data-live-search="true">
            <option value="">Select Status</option>
            @foreach($status as $s)
            <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
          </select>
          @if($errors->has('status_id'))
          <div class="invalid-feedback ml-3">{{ $errors->first('status_id') }}</div>
          @endif
        </div>
      </div>
      <div class="form-group row pt-3">
        <div class="col-md-2 form-control-label">Select Owner</div>
        <div class="col-md-4" >
          <select   name="patient_coordinator_id" class="form-control selectpicker" data-live-search="true">
            <option value="">Select Owner</option>
            @foreach($users as $user)
            <option value="{{ $user->user_id }}">{{ $user->name }}</option>
            @endforeach
          </select>
          @if($errors->has('patient_coordinator_id'))
          <div class="invalid-feedback ml-3">{{ $errors->first('patient_coordinator_id') }}</div>
          @endif
        </div>

        <div class="col-md-2 form-control-label">Select Treatment</div>
        <div class="col-md-4" >
          <select   name="treatment_id" class="form-control selectpicker" data-live-search="true">
            <option value="">Select Treatment</option>
            @foreach($treatments as $treatment)
            <option value="{{ $treatment->id }}">{{ $treatment->name }}</option>
            @endforeach
          </select>
          @if($errors->has('treatment_id'))
          <div class="invalid-feedback ml-3">{{ $errors->first('treatment_id') }}</div>
          @endif
        </div>
      </div>

      <div class="form-group row pt-3">
        <div class="col-md-4 offset-sm-4 text-right" >
          <button type="submit" style="width: 100%;" class="btn btn-primary">Generate</button>
        </div>
      </div>
    </form>
  </div>

  <!-- TODAY'S STATS -->
  <div class="row pt-4"> <div class="col-md-12"><h4>Today's Stats</h4></div></div>
  <div class="row mb-4 pt-2">
    <div class="col-lg-6 mb-4 mb-lg-0 pl-lg-0">
      <div class="card mb-3">
        <div class="card-body">
          <div class="row align-items-center flex-row">
            <div class="col-lg-5">
              <h2 class="mb-0 d-flex align-items-center"><span>{{$created}}</span><span class="dot bg-green d-inline-block ml-3"></span></h2><span class="text-muted text-uppercase small">Created Today</span>

              <hr><small class="text-muted">Customers Added Today</small>
              <p><a href="{{ route('CreatedToday') }}">View Details</a></p>
            </div>
            <div class="col-lg-7">
              <form class="form-horizontal" id="chart1" method="post" action="" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{$created}}">
                <input type="hidden" value="{{$updated}}">
                <input type="hidden" value="{{$total_today}}">
              </form>
              <canvas data-created="{{$created}}" data-total="{{$total_today}}" id="todayPieChart1"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 mb-4 mb-lg-0 pl-lg-0">
      <div class="card mb-3">
        <div class="card-body">
          <div class="row align-items-center flex-row">
            <div class="col-lg-5">
              <h2 class="mb-0 d-flex align-items-center"><span>{{$updated}}</span><span class="dot bg-violet d-inline-block ml-3"></span></h2><span class="text-muted text-uppercase small">Updated Today</span>
              <hr><small class="text-muted">Customers Updated Today</small>
              <p><a href="{{ route('UpdatedToday') }}">View Details</a></p>
            </div>
            <div class="col-lg-7">
              <canvas data-updated="{{$updated}}" data-total="{{$total_today}}" id="todayPieChart2"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div><!-- END of Today's Stats -->

  <!-- This Week'S STATS -->
  <div class="row"> <div class="col-md-12"><h4>This Week's Stats</h4></div></div>
  <div class="row mb-4 pt-2">
    <div class="col-lg-6 mb-4 mb-lg-0 pl-lg-0">
      <div class="card mb-3">
        <div class="card-body">
          <div class="row align-items-center flex-row">
            <div class="col-lg-5">
              <h2 class="mb-0 d-flex align-items-center"><span>{{$created_this_week}}</span><span class="dot bg-green d-inline-block ml-3"></span></h2><span class="text-muted text-uppercase small">Created This Week</span>
              <hr><small class="text-muted">Customers Added This Week</small>
              <p><a href="{{ route('CreatedThisWeek') }}">View Details</a></p>

            </div>
            <div class="col-lg-7">
             <canvas data-created_this_week="{{$created_this_week}}" data-total_this_week="{{$total_this_week}}" id="thisWeekPieChart1"></canvas>
           </div>
         </div>
       </div>
     </div>
   </div>

   <div class="col-lg-6 mb-4 mb-lg-0 pl-lg-0">
    <div class="card mb-3">
      <div class="card-body">
        <div class="row align-items-center flex-row">
          <div class="col-lg-5">
            <h2 class="mb-0 d-flex align-items-center"><span>{{$updated_this_week}}</span><span class="dot bg-violet d-inline-block ml-3"></span></h2><span class="text-muted text-uppercase small">Updated This Week</span>
            <hr><small class="text-muted">Customers Updated This Week</small>
            <p><a href="{{ route('UpdatedThisWeek') }}">View Details</a></p>
          </div>
          <div class="col-lg-7">
            <canvas data-updated_this_week="{{$updated_this_week}}" data-total_this_week="{{$total_this_week}}" id="thisWeekPieChart2"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

</div><!-- END of This Week's Stats -->


<!-- Previous Week'S STATS -->
<div class="row"> <div class="col-md-12"><h4>Previous Week's Stats</h4></div></div>
<div class="row mb-4 pt-2">
  <div class="col-lg-6 mb-4 mb-lg-0 pl-lg-0">
    <div class="card mb-3">
      <div class="card-body">
        <div class="row align-items-center flex-row">
          <div class="col-lg-5">
            <h2 class="mb-0 d-flex align-items-center"><span>{{$created_previous_week}}</span><span class="dot bg-green d-inline-block ml-3"></span></h2><span class="text-muted text-uppercase small">Created Previous Week</span>
            <hr><small class="text-muted">Customers Added Previous Week</small>
            <p><a href="{{ route('CreatedPreviousWeek') }}">View Details</a></p>

          </div>
          <div class="col-lg-7">
            <canvas data-created_previous_week="{{$created_previous_week}}" data-total_pre_week="{{$total_previous_week}}" id="preWeekPieChart1"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6 mb-4 mb-lg-0 pl-lg-0">
    <div class="card mb-3">
      <div class="card-body">
        <div class="row align-items-center flex-row">
          <div class="col-lg-5">
            <h2 class="mb-0 d-flex align-items-center"><span>{{$updated_previous_week}}</span><span class="dot bg-violet d-inline-block ml-3"></span></h2><span class="text-muted text-uppercase small">Updated Previous Week</span>
            <hr><small class="text-muted">Customers Updated Previous Week</small>
            <p><a href="{{ route('UpdatedPreviousWeek') }}">View Details</a></p>
          </div>
          <div class="col-lg-7">
            <canvas data-updated_previous_week="{{$updated_previous_week}}" data-total_pre_week="{{$total_previous_week}}" id="preWeekPieChart2"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

</div><!-- END of Previous Week's Stats -->

<!-- This Month'S STATS -->
<div class="row"> <div class="col-md-12"><h4>This Month's Stats</h4></div></div>
<div class="row mb-4 pt-2">
  <div class="col-lg-6 mb-4 mb-lg-0 pl-lg-0">
    <div class="card mb-3">
      <div class="card-body">
        <div class="row align-items-center flex-row">
          <div class="col-lg-5">
            <h2 class="mb-0 d-flex align-items-center"><span>{{$created_this_month}}</span><span class="dot bg-green d-inline-block ml-3"></span></h2><span class="text-muted text-uppercase small">Created This Month</span>
            <hr><small class="text-muted">Customers Added This Month</small>
            <p><a href="{{ route('CreatedThisMonth') }}">View Details</a></p>

          </div>
          <div class="col-lg-7">
           <canvas data-created_this_month="{{$created_this_month}}" data-total_this_month="{{$total_this_month}}" id="thismonthPieChart1"></canvas>

         </div>
       </div>
     </div>
   </div>
 </div>

 <div class="col-lg-6 mb-4 mb-lg-0 pl-lg-0">
  <div class="card mb-3">
    <div class="card-body">
      <div class="row align-items-center flex-row">
        <div class="col-lg-5">
          <h2 class="mb-0 d-flex align-items-center"><span>{{$updated_this_month}}</span><span class="dot bg-violet d-inline-block ml-3"></span></h2><span class="text-muted text-uppercase small">Updated This Month</span>
          <hr><small class="text-muted">Customers Updated This Month</small>
          <p><a href="{{ route('UpdatedThisMonth') }}">View Details</a></p>
        </div>
        <div class="col-lg-7">
         <canvas data-updated_this_month="{{$updated_this_month}}" data-total_this_month="{{$total_this_month}}" id="thismonthPieChart2"></canvas>
       </div>
     </div>
   </div>
 </div>
</div>

</div><!-- END of This Month's Stats -->


<!-- This Years'S STATS -->
<div class="row"> <div class="col-md-12"><h4>This Years's Stats</h4></div></div>
<div class="row mb-4 pt-2">
  <div class="col-lg-6 mb-4 mb-lg-0 pl-lg-0">
    <div class="card mb-3">
      <div class="card-body">
        <div class="row align-items-center flex-row">
          <div class="col-lg-5">
            <h2 class="mb-0 d-flex align-items-center"><span>{{$created_this_year}}</span><span class="dot bg-green d-inline-block ml-3"></span></h2><span class="text-muted text-uppercase small">Created This Year</span>
            <hr><small class="text-muted">Customers Added This Year</small>
            <p><a href="{{ route('CreatedThisYear') }}">View Details</a></p>

          </div>
          <div class="col-lg-7">
            <canvas data-created_this_year="{{$created_this_year}}" data-total_this_year="{{$total_this_year}}" id="thisyearPieChart1"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6 mb-4 mb-lg-0 pl-lg-0">
    <div class="card mb-3">
      <div class="card-body">
        <div class="row align-items-center flex-row">
          <div class="col-lg-5">
            <h2 class="mb-0 d-flex align-items-center"><span>{{$updated_this_year}}</span><span class="dot bg-violet d-inline-block ml-3"></span></h2><span class="text-muted text-uppercase small">Updated This Year</span>
            <hr><small class="text-muted">Customers Updated This Year</small>
            <p><a href="{{ route('UpdatedThisYear') }}">View Details</a></p>
          </div>
          <div class="col-lg-7">
            <canvas data-updated_this_year="{{$updated_this_year}}" data-total_this_year="{{$total_this_year}}" id="thisyearPieChart2"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

</div><!-- END of This Year's Stats -->
</section>
<section>
  <script src="{{ asset('backend/js/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('backend/js/charts-home.js') }}"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script>
    $(document).ready(function(){
      $('.count').each(function () {
        $(this).prop('Counter',0).animate({
          Counter: $(this).text()
        }, {
          duration: 1500,
          easing: 'swing',
          step: function (now) {
            $(this).text(Math.ceil(now));
          }
        });
      });
    });
  </script>
  <script>

    $(document).ready(function(){
      var horizontalBar = $('#horizontalBar');
      var owner_name = $('#horizontalBar').data().owner_name;
      var owner_counts = $('#horizontalBar').data().owner_counts;
      var sum_of_owners = $('#horizontalBar').data().sum_of_owners;
      console.log(owner_name,owner_counts,sum_of_owners);
      var horizontal_Bar = new Chart(horizontalBar, {
        "type": "horizontalBar",
        "data": {
          "labels": owner_name,
          "datasets": [{
            "label": "Leads Today",
            "data": owner_counts,
            "fill": false,
            "backgroundColor": ["rgba(255, 99, 132, 0.2)", "rgba(255, 159, 64, 0.2)",
            "rgba(255, 205, 86, 0.2)", "rgba(75, 192, 192, 0.2)", "rgba(54, 162, 235, 0.2)",
            "rgba(153, 102, 255, 0.2)", "rgba(201, 203, 207, 0.2)"
            ],
            "borderColor": ["rgb(255, 99, 132)", "rgb(255, 159, 64)", "rgb(255, 205, 86)",
            "rgb(75, 192, 192)", "rgb(54, 162, 235)", "rgb(153, 102, 255)", "rgb(201, 203, 207)"
            ],
            "borderWidth": 1
          }]
        },
        "options": {
          "scales": {
            "xAxes": [{
              "ticks": {
                "beginAtZero": true
              }
            }]
          }
        }
      });
    });
  </script>
  <script>
    $(document).ready(function(){
    // ------------------------------------------------------- //
    // Pie Chart For Current Day
    // ------------------------------------------------------ //
    var PIECHART = $('#todayPieChart1');
    var created_today = $('#todayPieChart1').data().created;
    var total_today = $('#todayPieChart1').data().total;

    var myPieChart = new Chart(PIECHART, {

      type: 'doughnut',
      options: {
        cutoutPercentage: 90,
        legend: {
          display: true
        }
      },
      data: {
        labels: [
        "Created",
        "Total",
        ],
        datasets: [{
          data: [created_today, total_today],
          borderWidth: [0, 0],
          backgroundColor: [
          '#7CF29C ',
          "#3490dc",

          ],
          hoverBackgroundColor: [
          '#7CF29C',
          "#3490dc",

          ]
        }]
      }
    });

   // ------------------------------------------------------- //
    // Pie Chart For Current Day-Updated Today
    // ------------------------------------------------------ //
    var PIECHART = $('#todayPieChart2');
    var updated_today = $('#todayPieChart2').data().updated;
    var total_today = $('#todayPieChart2').data().total;

    var myPieChart = new Chart(PIECHART, {

      type: 'doughnut',
      options: {
        cutoutPercentage: 90,
        legend: {
          display: true
        }
      },
      data: {
        labels: [
        "Updated",
        "Total",
        ],
        datasets: [{
          data: [updated_today, total_today],
          borderWidth: [0, 0],
          backgroundColor: [
          '#DF99CA',
          "#3490dc",

          ],
          hoverBackgroundColor: [
          '#DF99CA',
          "#3490dc",

          ]
        }]
      }
    });

  // ------------------------------------------------------- //
    // Pie Chart For Previous Week-Created
    // ------------------------------------------------------ //
    var PIECHART = $('#preWeekPieChart1');
    var created_previous_week = $('#preWeekPieChart1').data().created_previous_week;
    var total_pre_week = $('#preWeekPieChart1').data().total_pre_week;

    var myPieChart = new Chart(PIECHART, {

      type: 'doughnut',
      options: {
        cutoutPercentage: 90,
        legend: {
          display: true
        }
      },
      data: {
        labels: [
        "Created",
        "Total",
        ],
        datasets: [{
          data: [created_previous_week, total_pre_week],
          borderWidth: [0, 0],
          backgroundColor: [
          '#7CF29C ',
          "#3490dc",

          ],
          hoverBackgroundColor: [
          '#7CF29C ',
          "#3490dc",

          ]
        }]
      }
    });

  // ------------------------------------------------------- //
    // Pie Chart For Previous Week-Updated
    // ------------------------------------------------------ //
    var PIECHART = $('#preWeekPieChart2');
    var updated_previous_week = $('#preWeekPieChart2').data().updated_previous_week;
    var total_pre_week = $('#preWeekPieChart2').data().total_pre_week;

    var myPieChart = new Chart(PIECHART, {

      type: 'doughnut',
      options: {
        cutoutPercentage: 90,
        legend: {
          display: true
        }
      },
      data: {
        labels: [
        "Updated",
        "Total",
        ],
        datasets: [{
          data: [updated_previous_week, total_pre_week],
          borderWidth: [0, 0],
          backgroundColor: [
          '#DF99CA',
          "#3490dc",

          ],
          hoverBackgroundColor: [
          '#DF99CA',
          "#3490dc",

          ]
        }]
      }
    });


  // ------------------------------------------------------- //
    // Pie Chart For This Week-Created
    // ------------------------------------------------------ //
    var PIECHART = $('#thisWeekPieChart1');
    var created_this_week = $('#thisWeekPieChart1').data().created_this_week;
    var total_this_week = $('#thisWeekPieChart1').data().total_this_week;

    var myPieChart = new Chart(PIECHART, {

      type: 'doughnut',
      options: {
        cutoutPercentage: 90,
        legend: {
          display: true
        }
      },
      data: {
        labels: [
        "Created",
        "Total",
        ],
        datasets: [{
          data: [created_this_week, total_this_week],
          borderWidth: [0, 0],
          backgroundColor: [
          '#7CF29C ',
          "#3490dc",

          ],
          hoverBackgroundColor: [
          '#7CF29C ',
          "#3490dc",

          ]
        }]
      }
    });

  // ------------------------------------------------------- //
    // Pie Chart For Previous Week-Updated
    // ------------------------------------------------------ //
    var PIECHART = $('#thisWeekPieChart2');
    var updated_this_week = $('#thisWeekPieChart2').data().updated_this_week;
    var total_this_week = $('#thisWeekPieChart2').data().total_this_week;
    var myPieChart = new Chart(PIECHART, {

      type: 'doughnut',
      options: {
        cutoutPercentage: 90,
        legend: {
          display: true
        }
      },
      data: {
        labels: [
        "Updated",
        "Total",
        ],
        datasets: [{
          data: [updated_this_week, total_this_week],
          borderWidth: [0, 0],
          backgroundColor: [
          '#DF99CA',
          "#3490dc",

          ],
          hoverBackgroundColor: [
          '#DF99CA',
          "#3490dc",

          ]
        }]
      }
    });

  // ------------------------------------------------------- //
    // Pie Chart For This Month-Created
    // ------------------------------------------------------ //
    var PIECHART = $('#thismonthPieChart1');
    var created_this_month = $('#thismonthPieChart1').data().created_this_month;
    var total_this_month = $('#thismonthPieChart1').data().total_this_month;

    var myPieChart = new Chart(PIECHART, {

      type: 'doughnut',
      options: {
        cutoutPercentage: 90,
        legend: {
          display: true
        }
      },
      data: {
        labels: [
        "Created",
        "Total",
        ],
        datasets: [{
          data: [created_this_month, total_this_month],
          borderWidth: [0, 0],
          backgroundColor: [
          '#7CF29C ',
          "#3490dc",

          ],
          hoverBackgroundColor: [
          '#7CF29C ',
          "#3490dc",

          ]
        }]
      }
    });

  // ------------------------------------------------------- //
    // Pie Chart For This Month-Updated
    // ------------------------------------------------------ //
    var PIECHART = $('#thismonthPieChart2');
    var updated_this_month = $('#thismonthPieChart2').data().updated_this_month;
    var total_this_month = $('#thismonthPieChart2').data().total_this_month;
    var myPieChart = new Chart(PIECHART, {

      type: 'doughnut',
      options: {
        cutoutPercentage: 90,
        legend: {
          display: true
        }
      },
      data: {
        labels: [
        "Updated",
        "Total",
        ],
        datasets: [{
          data: [updated_this_month, total_this_month],
          borderWidth: [0, 0],
          backgroundColor: [
          '#DF99CA',
          "#3490dc",

          ],
          hoverBackgroundColor: [
          '#DF99CA',
          "#3490dc",

          ]
        }]
      }
    });

  // ------------------------------------------------------- //
    // Pie Chart For This Month-Created
    // ------------------------------------------------------ //
    var PIECHART = $('#thisyearPieChart1');
    var created_this_year = $('#thisyearPieChart1').data().created_this_year;
    var total_this_year = $('#thisyearPieChart1').data().total_this_year;

    var myPieChart = new Chart(PIECHART, {

      type: 'doughnut',
      options: {
        cutoutPercentage: 90,
        legend: {
          display: true
        }
      },
      data: {
        labels: [
        "Created",
        "Total",
        ],
        datasets: [{
          data: [created_this_year, total_this_year],
          borderWidth: [0, 0],
          backgroundColor: [
          '#7CF29C ',
          "#3490dc",

          ],
          hoverBackgroundColor: [
          "#7CF29C",
          "#3490dc",

          ]
        }]
      }
    });

  // ------------------------------------------------------- //
    // Pie Chart For This Month-Updated
    // ------------------------------------------------------ //
    var PIECHART = $('#thisyearPieChart2');
    var updated_this_year = $('#thisyearPieChart2').data().updated_this_year;
    var total_this_year = $('#thisyearPieChart2').data().total_this_year;
    var myPieChart = new Chart(PIECHART, {

      type: 'doughnut',
      options: {
        cutoutPercentage: 90,
        legend: {
          display: true
        }
      },
      data: {
        labels: [
        "Updated",
        "Total",
        ],
        datasets: [{
          data: [updated_this_year, total_this_year],
          borderWidth: [0, 0],
          backgroundColor: [
          '#DF99CA',
          "#3490dc",

          ],
          hoverBackgroundColor: [
          "#DF99CA",
          "#3490dc",

          ]
        }]
      }
    });

  });
</script>

</section>
@section('scripts')
<script src="{{ asset('backend/js/select2-develop/dist/js/select2.min.js') }}"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>
@endsection
