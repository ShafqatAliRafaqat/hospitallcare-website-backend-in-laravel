<section class="py-5">
    <!-- Report Generation on Screen -->
        <div class="form-group pt-3">
        <h5 class="pb-2">Report Generation</h5>
        <form class="form-horizontal" method="post" action="{{ route('clientsreport') }}">
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
              <div class="col-md-2 form-control-label">Select Status</div>
              <div class="col-md-4" >
                <select   name="status_id" class="form-control ">
                  <option value="">Select Status</option>
                  @foreach($status as $s)
                      <option value="{{ $s->id }}">
                        @if($s->id == '1')
                        Informed
                        @elseif($s->id == '2')
                        Booked Appointment
                        @elseif($s->id == '3')
                        Got Appointment
                        @elseif($s->id == '4')
                        Got Treatment
                        @endif
                    </option>
                    @endforeach
                </select>
                  @if($errors->has('status_id'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('status_id') }}</div>
                  @endif
              </div>

                <div class="col-md-4 offset-2 text-right" >
                <button type="submit" style="width: 100%;" class="btn btn-primary">Generate</button>
              </div>
            </div>
          </form>
        </div>
    <div class="row">
        <!-- TODAY'S STATS -->
        <div class="row pt-4">
            <div class="col-md-12">
                <h4>Today's Stats</h4></div>
        </div>
        <div class="row mb-12 pt-2">
            <div class="col-lg-12 mb-12 mb-lg-0 pl-lg-0">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center flex-row">
                            <div class="col-lg-12">
                                <canvas width="400" height="100"
                                data-contact="{{$todayNoContact}}"
                                data-customer="{{$todayCustomer}}"
                                data-hot="{{$todayHot}}"
                                data-warm="{{$todayWarm}}"
                                data-cold="{{$todayCold}}"
                                data-total="{{$total_today}}"
                                id="todayPieChart">
                            </canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- END of Today's Stats -->
        <!-- This Week'S STATS -->
        <div class="row">
            <div class="col-md-12">
                <h4>This Week's Stats</h4></div>
        </div>
            <div class="col-lg-12 mb-12 mb-lg-0 pl-lg-0">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center flex-row">
                            <div class="col-lg-12">
                                <canvas width="400" height="100"
                                    data-contact="{{$thisWeekNoContact}}"
                                    data-customer=" {{$thisWeekCustomer}}"
                                    data-hot     =" {{$thisWeekHot}}"
                                    data-warm    = "{{$thisWeekWarm}}"
                                    data-cold    =" {{$thisWeekCold}}"
                                    data-total="{{$total_this_week}}"
                                    id="thisWeekPieChart">
                                </canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- END of This Week's Stats -->

        <!-- Previous Week'S STATS -->
        <div class="row">
            <div class="col-md-12">
                <h4>Previous Week's Stats</h4></div>
        </div>
            <div class="col-lg-12 mb-12 mb-lg-0 pl-lg-0">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center flex-row">
                            <div class="col-lg-12">
                                <canvas width="400" height="100"
                                    data-contact="{{$previousWeekNoContact}}"
                                    data-customer=" {{$previousWeekCustomer}}"
                                    data-hot     =" {{$previousWeekHot}}"
                                    data-warm    = "{{$previousWeekWarm}}"
                                    data-cold    =" {{$previousWeekCold}}"
                                    data-total="{{$total_previous_week}}"
                                    id="preWeekPieChart">
                                </canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- END of Previous Week's Stats -->

        <!-- This Month'S STATS -->
        <div class="row">
            <div class="col-md-12">
                <h4>This Month's Stats</h4></div>
        </div>
            <div class="col-lg-12 mb-12 mb-lg-0 pl-lg-0">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center flex-row">
                            <div class="col-lg-12">
                                <canvas width="400" height="100"
                                    data-contact="{{$thisMonthNoContact}}"
                                    data-customer=" {{$thisMonthCustomer}}"
                                    data-hot     =" {{$thisMonthHot}}"
                                    data-warm    = "{{$thisMonthWarm}}"
                                    data-cold    =" {{$thisMonthCold}}"
                                    data-total="{{$total_this_month}}"
                                    id="thismonthPieChart">
                                </canvas>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- END of This Month's Stats -->

        <!-- This Years'S STATS -->
        <div class="row">
            <div class="col-md-12">
                <h4>This Years's Stats</h4></div>
        </div>
            <div class="col-lg-12 mb-12 mb-lg-0 pl-lg-0">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center flex-row">
                            <div class="col-lg-12">
                                    <canvas  width="400" height="100"
                                    data-contact="{{$thisYearNoContact}}"
                                    data-customer=" {{$thisYearCustomer}}"
                                    data-hot     =" {{$thisYearHot}}"
                                    data-warm    = "{{$thisYearWarm}}"
                                    data-cold    =" {{$thisYearCold}}"
                                    data-total="{{$total_this_year}}"
                                    id="thisyearPieChart">
                                    </canvas>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- END of This Year's Stats -->
    </div>
</section>
<section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
  $(document).ready(function(){
    // ------------------------------------------------------- //
    // Pie Chart For Current Day
    // ------------------------------------------------------ //
    var PIECHART = $('#todayPieChart');
    var Customer = $('#todayPieChart').data().customer;
    var Hot      = $('#todayPieChart').data().hot;
    var Warm     = $('#todayPieChart').data().warm;
    var Cold     = $('#todayPieChart').data().cold;
    var total    = $('#todayPieChart').data().total;
    var myPieChart = new Chart(PIECHART, {

        type: 'bar',
        options: {
            cutoutPercentage: 90,
            legend: {
                display: true,
            },
            scales: {
                    yAxes: [{
                            display: true,
                            ticks: {
                                suggestedMin: 0,
                                userCallback: function(label, index, labels) {
                                        // when the floored value is the same as the value we have a whole number
                                        if (Math.floor(label) === label) {
                                            return label;
                                        }

                                    },
                                }
                        }]
                },
        },
        data: {
            labels: [
                "Total",
                "Informed",
                "Booked Appointment",
                "Got Appointment",
                "Got Treatment",
            ],
            datasets: [{
                label: "Today Stats",
                data: [total,Cold, Warm,Hot,Customer],
                backgroundColor: [
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(223, 153, 202, 0.6)',
                    'rgba(124, 242, 156, 0.6)',
                    'rgba(76, 132, 255, 0.6)',
                    'rgba(240, 64, 76, 0.6)',
                    
                    ],
                borderColor: [
                    'rgba(153, 102, 255, 1)',
                    'rgba(223, 153, 202, 1)',
                    'rgba(124, 242, 156, 1)',
                    'rgba(76, 132, 255, 1)',
                    'rgba(240, 64, 76, 1)',
                    ],
                borderWidth: 1,
                animation: true,
            }],
        }
    });
  // ------------------------------------------------------- //
    // Pie Chart For Previous Week-appointment
    // ------------------------------------------------------ //
    var PIECHART  = $('#preWeekPieChart');
    var Customer  = $('#preWeekPieChart').data().customer;
    var Hot       = $('#preWeekPieChart').data().hot;
    var Warm      = $('#preWeekPieChart').data().warm;
    var Cold      = $('#preWeekPieChart').data().cold;
    var total     = $('#preWeekPieChart').data().total;
    var myPieChart = new Chart(PIECHART, {

        type: 'bar',
        options: {
            cutoutPercentage: 90,
            legend: {
                display: true
            },
            scales: {
                    yAxes: [{
                            display: true,
                            ticks: {
                                suggestedMin: 0,
                                userCallback: function(label, index, labels) {
                                        // when the floored value is the same as the value we have a whole number
                                        if (Math.floor(label) === label) {
                                            return label;
                                        }

                                    },
                                }
                        }]
                },
        },
        data: {
            labels: [
                "Total",
                "Informed",
                "Booked Appointment",
                "Got Appointment",
                "Got Treatment",
            ],
            datasets: [{
                label: "Previous Week Stats",
                data: [total,Cold, Warm,Hot,Customer],
                backgroundColor: [
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(223, 153, 202, 0.6)',
                    'rgba(124, 242, 156, 0.6)',
                    'rgba(76, 132, 255, 0.6)',
                    'rgba(240, 64, 76, 0.6)',
                    
                    ],
                borderColor: [
                    'rgba(153, 102, 255, 1)',
                    'rgba(223, 153, 202, 1)',
                    'rgba(124, 242, 156, 1)',
                    'rgba(76, 132, 255, 1)',
                    'rgba(240, 64, 76, 1)',
                    ],
                borderWidth: 1,
                animation: true,
            }]
        }
    });
  // ------------------------------------------------------- //
    // Pie Chart For This Week-appointment
    // ------------------------------------------------------ //
    var PIECHART  = $('#thisWeekPieChart');
    var Customer  = $('#thisWeekPieChart').data().customer;
    var Hot       = $('#thisWeekPieChart').data().hot;
    var Warm      = $('#thisWeekPieChart').data().warm;
    var Cold      = $('#thisWeekPieChart').data().cold;
    var total     = $('#thisWeekPieChart').data().total;
    var myPieChart = new Chart(PIECHART, {

        type: 'bar',
        options: {
            cutoutPercentage: 90,
            legend: {
                display: true
            },
            scales: {
                    yAxes: [{
                            display: true,
                            ticks: {
                                suggestedMin: 0,
                                userCallback: function(label, index, labels) {
                                        // when the floored value is the same as the value we have a whole number
                                        if (Math.floor(label) === label) {
                                            return label;
                                        }

                                    },
                                }
                        }]
                },
        },
        data: {
            labels: [
                "Total",
                "Informed",
                "Booked Appointment",
                "Got Appointment",
                "Got Treatment",
            ],
            datasets: [{
                label: "This Week Stats",
                data: [total,Cold, Warm,Hot,Customer],
                backgroundColor: [
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(223, 153, 202, 0.6)',
                    'rgba(124, 242, 156, 0.6)',
                    'rgba(76, 132, 255, 0.6)',
                    'rgba(240, 64, 76, 0.6)',
                    
                    ],
                borderColor: [
                    'rgba(153, 102, 255, 1)',
                    'rgba(223, 153, 202, 1)',
                    'rgba(124, 242, 156, 1)',
                    'rgba(76, 132, 255, 1)',
                    'rgba(240, 64, 76, 1)',
                    ],
                borderWidth: 1,
                animation: true,
            }]
        }
    });
  // ------------------------------------------------------- //
    // Pie Chart For This Month-appointment
    // ------------------------------------------------------ //
    var PIECHART  = $('#thismonthPieChart');
    var Customer  = $('#thismonthPieChart').data().customer;
    var Hot       = $('#thismonthPieChart').data().hot;
    var Warm      = $('#thismonthPieChart').data().warm;
    var Cold      = $('#thismonthPieChart').data().cold;
    var total     = $('#thismonthPieChart').data().total;
    var myPieChart = new Chart(PIECHART, {

        type: 'bar',
        options: {
            cutoutPercentage: 90,
            legend: {
                display: true
            },
            scales: {
                    yAxes: [{
                            display: true,
                            ticks: {
                                suggestedMin: 0,
                                userCallback: function(label, index, labels) {
                                        // when the floored value is the same as the value we have a whole number
                                        if (Math.floor(label) === label) {
                                            return label;
                                        }

                                    },
                                }
                        }]
                },
        },
        data: {
            labels: [
                "Total",
                "Informed",
                "Booked Appointment",
                "Got Appointment",
                "Got Treatment",
            ],
            datasets: [{
                label: "This Month Stats",
                data: [total,Cold, Warm,Hot,Customer],
                backgroundColor: [
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(223, 153, 202, 0.6)',
                    'rgba(124, 242, 156, 0.6)',
                    'rgba(76, 132, 255, 0.6)',
                    'rgba(240, 64, 76, 0.6)',
                    
                    ],
                borderColor: [
                    'rgba(153, 102, 255, 1)',
                    'rgba(223, 153, 202, 1)',
                    'rgba(124, 242, 156, 1)',
                    'rgba(76, 132, 255, 1)',
                    'rgba(240, 64, 76, 1)',
                    ],
                borderWidth: 1,
                animation: true,
            }]
        }
    });

  // ------------------------------------------------------- //
    // Pie Chart For This Month-appointment
    // ------------------------------------------------------ //
    var PIECHART  = $('#thisyearPieChart');
    var Customer  = $('#thisyearPieChart').data().customer;
    var Hot       = $('#thisyearPieChart').data().hot;
    var Warm      = $('#thisyearPieChart').data().warm;
    var Cold      = $('#thisyearPieChart').data().cold;
    var total     = $('#thisyearPieChart').data().total;
    var myPieChart = new Chart(PIECHART, {

        type: 'bar',
        options: {
            cutoutPercentage: 90,
            resize: true,
            maintainAspectRatio:true,
            responsive:true,
            redraw: true,
            legend: {
                display: true
            },
            scales: {
                    yAxes: [{
                            display: true,
                            ticks: {
                                suggestedMin: 0,
                                // fixedStepSize: 1,
                                // precision:0,
                                userCallback: function(label, index, labels) {
                                        // when the floored value is the same as the value we have a whole number
                                        if (Math.floor(label) === label) {
                                            return label;
                                        }

                                    },
                                }
                        }]
                },
        },
        data: {
            labels: [
                "Total",
                "Informed",
                "Booked Appointment",
                "Got Appointment",
                "Got Treatment",
            ],
            datasets: [{
                label: "This Year Stats",
                data: [total,Cold, Warm,Hot,Customer],
                backgroundColor: [
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(223, 153, 202, 0.6)',
                    'rgba(124, 242, 156, 0.6)',
                    'rgba(76, 132, 255, 0.6)',
                    'rgba(240, 64, 76, 0.6)',
                    
                    ],
                borderColor: [
                    'rgba(153, 102, 255, 1)',
                    'rgba(223, 153, 202, 1)',
                    'rgba(124, 242, 156, 1)',
                    'rgba(76, 132, 255, 1)',
                    'rgba(240, 64, 76, 1)',
                    ],
                borderWidth: 1,
                animation: true,
            }]
        }
    });

});
</script>
</section>
