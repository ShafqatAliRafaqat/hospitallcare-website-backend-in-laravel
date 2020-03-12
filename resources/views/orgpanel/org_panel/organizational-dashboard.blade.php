<section class="py-5">
        <!-- Emolyees Status -->
        <div class="row pt-4">
            <div class="col-md-6">
                <a class="leads-a" href="{{ route('active-employees') }}">
                    <h4>Employees</h4>
                </a>
            </div>
            <div class="col-md-6">
                <a class="leads-a" href="{{ route('all_claims') }}">
                    <h4>Claims</h4>
                </a>
            </div>
        </div>
        <div class="row mb-12 pt-2">
            <div class="col-lg-6 mb-12 mb-lg-0 pl-lg-0">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center flex-row">
                            <div class="col-lg-12">
                                <canvas width="550" height="340"
                                    data-total_employess="{{$numberofemployees}}"
                                    data-active_employees="{{$active_employees}}"
                                    data-pending_employees="{{$pending_employees}}"
                                    id="EmployeePieChart">
                                    
                                </canvas>
                                <hr>
                            </div>
                            <div class="col-md-4 mt-3">
                                <span class="org-db-stats-text">Total Employees: </span> <span class="org-db-stats-number">{{$numberofemployees}}</span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <span class="org-db-stats-text">Active Employees: </span> <span class="org-db-stats-number">{{$active_employees}}</span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <span class="org-db-stats-text">Pending Employees : </span> <span class="org-db-stats-number">{{$pending_employees}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-12 mb-lg-0 pl-lg-0">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center flex-row">
                            <div class="col-lg-12">
                                <canvas width="600" height="400"
                                    data-active_claims="{{$active_claims}}"
                                    data-pending_claims="{{$pending_claims}}"
                                    data-decline_claims="{{$decline_claims}}"
                                    data-hold_claims="{{$hold_claims}}"
                                    id="ClaimsPieChart">
                                </canvas>
                            </div>
                            <div class="col-md-3 mt-3">
                                <span class="org-db-stats-text">Active Claims: </span> <span class="org-db-stats-number">{{$active_claims}}</span>
                            </div>
                            <div class="col-md-3 mt-3">
                                <span class="org-db-stats-text">Pending Claims: </span> <span class="org-db-stats-number">{{$pending_claims}}</span>
                            </div>
                            <div class="col-md-3 mt-3">
                                <span class="org-db-stats-text">Decline Claims: </span> <span class="org-db-stats-number">{{$decline_claims}}</span>
                            </div>
                            <div class="col-md-3 mt-3">
                                <span class="org-db-stats-text">On-Hold Claims: </span> <span class="org-db-stats-number">{{$hold_claims}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END of Today's Stats -->
        <!-- TODAY'S STATS -->
        <div class="row pt-4">
            <div class="col-md-6">
                <a class="leads-a" href="{{ route('today-employess') }}">
                    <h4>Treatment Stats</h4>
                </a>
            </div>
            <div class="col-md-6">
                <a class="leads-a" href="{{ route('today-employess') }}">
                    <h4>Diagnostics Stats</h4>
                </a>
            </div>
        </div>
        @php
             if($customer_treatments != null){
                 foreach($customer_treatments as $cd){
                    $t_cost[] =$cd->discounted_cost;
                    $t_original_cost[] = $cd->cost;
                 }
                 $t_total_cost =round(array_sum($t_cost));
                 $t_total_original_cost =round(array_sum($t_original_cost));
                 $t_discounted_cost = $t_total_original_cost - $t_total_cost;
             }else{
                $t_total_cost =0;
                $t_total_original_cost = 0;
                $t_discounted_cost = 0;
             }
        @endphp
        <div class="row mb-12 pt-2">
            <div class="col-lg-6 mb-12 mb-lg-0 pl-lg-0">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center flex-row">
                            <div class="col-lg-12">
                                <canvas width="600" height="400"
                                data-t_total_cost="{{$t_total_cost}}"
                                data-t_total_original_cost="{{$t_total_original_cost}}"
                                data-t_discounted_cost="{{$t_discounted_cost}}"
                                id="todayPieChart">
                            </canvas>
                            </div>
                            <div class="col-md-4 mt-3">
                                <span class="org-db-stats-text">Original Cost: </span> <span class="org-db-stats-number">{{$t_total_original_cost}}/-</span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <span class="org-db-stats-text">Discounted Cost: </span> <span class="org-db-stats-number">{{$t_total_cost}}/-</span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <span class="org-db-stats-text">Saving : </span> <span class="org-db-stats-number">{{$t_discounted_cost}}/-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php
             if($customer_diagnostics != null){

                 foreach($customer_diagnostics as $cd){
                    $d_cost[] =$cd->discounted_cost;
                    $d_original_cost[] = $cd->cost;
                 }
                 $total_cost =round(array_sum($d_cost));
                 $total_original_cost =round(array_sum($d_original_cost));
                 $discounted_cost = $total_original_cost - $total_cost;
             }else{
                $total_cost =0;
                $total_original_cost = 0;
                $discounted_cost = 0;
             }
            @endphp
            <div class="col-lg-6 mb-12 mb-lg-0 pl-lg-0">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center flex-row">
                            <div class="col-lg-12">
                                    <canvas width="600" height="400"
                                    data-employees="{{$numberofemployees}}"
                                    data-total_cost="{{$total_cost}}"
                                    data-total_original_cost="{{$total_original_cost}}"
                                    data-discounted_cost="{{$discounted_cost}}"
                                    id="numberofemployeesPieChart">
                                </canvas>
                                
                            </div>

                        <div class="col-md-4 mt-3">
                            <span class="org-db-stats-text">Employees: </span> <span class="org-db-stats-number">{{$numberofemployees}}</span>
                        </div>
                        <div class="col-md-4 mt-3">
                            <span class="org-db-stats-text">Total Cost: </span> <span class="org-db-stats-number">{{$total_original_cost}}/-</span>
                        </div>
                        <div class="col-md-4 mt-3">
                            <span class="org-db-stats-text">Discount: </span> <span class="org-db-stats-number">{{$discounted_cost}}/-</span>
                        </div>

                    </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END of Today's Stats -->

        <!-- This Week'S STATS -->
        <div class="row pt-4">
            <div class="col-md-6 col-lg-6">
                <a class="leads-a" href="{{ route('this-week-employess') }}">
                    <h4>This Week's Stats</h4>
                </a>
            </div>
            <div class="col-md-6 col-lg-6">
                <a class="leads-a" href="{{ route('previous-week-employess') }}">
                    <h4>Previous Week's Stats</h4>
                </a>
            </div>
        </div>
        <div class ="row">
            <div class="col-lg-6 mb-6 mb-lg-0 pl-lg-0">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center flex-row">
                            <div class="col-lg-12">
                                <canvas width="600" height="400"
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
            <div class="col-lg-6 mb-6 mb-lg-0 pl-lg-0">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center flex-row">
                            <div class="col-lg-12">
                                <canvas width="600" height="400"
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
        </div>
        <!-- END of Previous Week's Stats -->

        <!-- This Month'S STATS -->
        <div class="row pt-4">
            <div class="col-lg-6 col-md-6">
                <a class="leads-a" href="{{ route('this-month-employess') }}">
                    <h4>This Month's Stats</h4>
                </a>
            </div>
            <div class="col-lg-6  col-md-6">
                <a class="leads-a" href="{{ route('this-year-employess') }}">
                    <h4>This Year Stats</h4>
                </a>
            </div>
        </div>
        <div class ="row">
            <div class="col-lg-6 mb-6 mb-lg-0 pl-lg-0">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center flex-row">
                            <div class="col-lg-12">
                                <canvas width="600" height="400"
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
            <div class="col-lg-6 mb-6 mb-lg-0 pl-lg-0">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center flex-row">
                            <div class="col-lg-12">
                                <canvas width="600" height="400"
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
        </div>
        <!-- END of This Year's Stats -->
</section>
<section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
  $(document).ready(function(){
    // ------------------------------------------------------- //
    // Pie Chart For Number of Employees
    // ------------------------------------------------------ //
    var PIECHART            = $('#EmployeePieChart');
    var total_employess     = $('#EmployeePieChart').data().total_employess;
    var active_employees    = $('#EmployeePieChart').data().active_employees;
    var pending_employees   = $('#EmployeePieChart').data().pending_employees;

    var myPieChart = new Chart(PIECHART, {
        type: 'pie',
        options: {
            scaleOverride: true,
            scaleStartValue: 0,
            scaleSteps: 10,
            scaleStepWidth: 1,
            cutoutPercentage: 0,
            legend: {
                display: true,
            },
            
        },
        data: {
            labels: [
                "Active Employees",
                "Pending Employees",
            ],
            datasets: [{
                label: "Total Numbers of Employees",
                data: [active_employees,pending_employees],
                backgroundColor: [
                    'rgba(49, 176, 213, 0.7)',
                    'rgba(56, 193, 114, 0.7)',
                    'rgba(51, 122, 183, 0.7)',
                    'rgba(92, 184, 92, 0.7)',
                    'rgba(217, 83, 79, 0.7)',
                    // 'rgba(153, 102, 255, 0.7)',
                    ],
                    borderColor: [
                    'rgba(49, 176, 213, 1)',
                    'rgba(56, 193, 114, 1)',
                    'rgba(51, 122, 183, 1)',
                    'rgba(92, 184, 92, 1)',
                    'rgba(217, 83, 79, 1)',
                    // 'rgba(153, 102, 255, 1)',
                    ],
                borderWidth: 1,
                animation: true,
            }]
        }
    });
    });
</script>
// <script>
//   $(document).ready(function(){
//     // ------------------------------------------------------- //
//     // Pie Chart For Number of Employees
//     // ------------------------------------------------------ //
//     var PIECHART            = $('#EmployeePieChart');
//     // var total_employess     = $('#EmployeePieChart').data().total_employess;
//     var active_employees    = $('#EmployeePieChart').data().active_employees;
//     var pending_employees   = $('#EmployeePieChart').data().pending_employees;

//     var myPieChart = new Chart(PIECHART, {
//         type: 'pie',
//         options: {
//             scaleOverride: true,
//             scaleStartValue: 0,
//             scaleSteps: 10,
//             scaleStepWidth: 1,
//             cutoutPercentage: 90,
//             legend: {
//                 display: true,
//             },
//             scales: {
//                     yAxes: [{
//                             display: true,
//                             ticks: {
//                                 suggestedMin: 0,
//                                 userCallback: function(label, index, labels) {
//                                         // when the floored value is the same as the value we have a whole number
//                                         if (Math.floor(label) === label) {
//                                             return label;
//                                         }

//                                     },
//                                 }
//                         }]
//                 },
//         },
//         data: {
//             labels: [
//                 "Number of employees",
//                 "Active Employees",
//                 "Pending Employees",
//             ],
//             datasets: [{
//                 label: "Employees Status",
//                 data: [total_employess,active_employees,pending_employees],
//                 backgroundColor: [
//                     'rgba(49, 176, 213, 0.7)',
//                     'rgba(56, 193, 114, 0.7)',
//                     'rgba(51, 122, 183, 0.7)',
//                     'rgba(92, 184, 92, 0.7)',
//                     'rgba(217, 83, 79, 0.7)',
//                     // 'rgba(153, 102, 255, 0.7)',
//                     ],
//                     borderColor: [
//                     'rgba(49, 176, 213, 1)',
//                     'rgba(56, 193, 114, 1)',
//                     'rgba(51, 122, 183, 1)',
//                     'rgba(92, 184, 92, 1)',
//                     'rgba(217, 83, 79, 1)',
//                     // 'rgba(153, 102, 255, 1)',
//                     ],
//                 borderWidth: 1,
//                 animation: true,
//             }]
//         }
//     });
// });
// </script>
<script>
  $(document).ready(function(){
    // ------------------------------------------------------- //
    // Pie Chart For Number of Claims
    // ------------------------------------------------------ //
    var PIECHART         = $('#ClaimsPieChart');
    var active_claims     = $('#ClaimsPieChart').data().active_claims;
    var pending_claims   = $('#ClaimsPieChart').data().pending_claims;
    var decline_claims   = $('#ClaimsPieChart').data().decline_claims;
    var hold_claims      = $('#ClaimsPieChart').data().hold_claims;

    var myPieChart = new Chart(PIECHART, {
        type: 'bar',
        options: {
            scaleOverride: true,
            scaleStartValue: 0,
            scaleSteps: 10,
            scaleStepWidth: 1,
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
                "Active Claims",
                "Pending Claims",
                "Decline Claims",
                "On-Hold Claims",
            ],
            datasets: [{
                label: "Claim Status",
                data: [active_claims,pending_claims,decline_claims,hold_claims],
                backgroundColor: [
                    'rgba(49, 176, 213, 0.7)',
                    'rgba(56, 193, 114, 0.7)',
                    'rgba(51, 122, 183, 0.7)',
                    'rgba(92, 184, 92, 0.7)',
                    'rgba(217, 83, 79, 0.7)',
                    // 'rgba(153, 102, 255, 0.7)',
                    ],
                    borderColor: [
                    'rgba(49, 176, 213, 1)',
                    'rgba(56, 193, 114, 1)',
                    'rgba(51, 122, 183, 1)',
                    'rgba(92, 184, 92, 1)',
                    'rgba(217, 83, 79, 1)',
                    // 'rgba(153, 102, 255, 1)',
                    ],
                borderWidth: 1,
                animation: true,
            }]
        }
    });
});
</script>
<script>
  $(document).ready(function(){
    // ------------------------------------------------------- //
    // Pie Chart For Current Day
    // ------------------------------------------------------ //
    var PIECHART = $('#todayPieChart');
    var t_total_cost= $('#todayPieChart').data().t_total_cost;
    var t_total_original_cost = $('#todayPieChart').data().t_total_original_cost;
    var t_discounted_cost      = $('#todayPieChart').data().t_discounted_cost;

    var myPieChart = new Chart(PIECHART, {
        type: 'pie',
        options: {
            scaleOverride: true,
            scaleStartValue: 0,
            scaleSteps: 10,
            scaleStepWidth: 1,
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
                "Discounted Cost",
                "Saving",
                "Original Cost",
            ],
            datasets: [{
                label: "Treatment Stats",
                data: [t_total_cost,t_discounted_cost,t_total_original_cost],
                backgroundColor: [
                    'rgba(49, 176, 213, 0.7)',
                    'rgba(56, 193, 114, 0.7)',
                    'rgba(51, 122, 183, 0.7)',
                    'rgba(92, 184, 92, 0.7)',
                    'rgba(217, 83, 79, 0.7)',
                    // 'rgba(153, 102, 255, 0.7)',
                    ],
                    borderColor: [
                    'rgba(49, 176, 213, 1)',
                    'rgba(56, 193, 114, 1)',
                    'rgba(51, 122, 183, 1)',
                    'rgba(92, 184, 92, 1)',
                    'rgba(217, 83, 79, 1)',
                    // 'rgba(153, 102, 255, 1)',
                    ],
                borderWidth: 1,
                animation: true,
            }]
        }
    });
  // ------------------------------------------------------- //
    // Pie Chart For Previous Week-appointment
    // ------------------------------------------------------ //
    var PIECHART  = $('#preWeekPieChart');
    var NoContact = $('#preWeekPieChart').data().contact;
    var Customer  = $('#preWeekPieChart').data().customer;
    var Hot       = $('#preWeekPieChart').data().hot;
    var Warm      = $('#preWeekPieChart').data().warm;
    var Cold      = $('#preWeekPieChart').data().cold;
    var total     = $('#preWeekPieChart').data().total;
    var myPieChart = new Chart(PIECHART, {

        type: 'pie',
        options: {
            // // cutoutPercentage: 90,
            // legend: {
            //     display: true,
            // },
            // scales: {
            //         yAxes: [{
            //                 display: true,
            //                 ticks: {
            //                     suggestedMin: 0,
            //                     userCallback: function(label, index, labels) {
            //                             // when the floored value is the same as the value we have a whole number
            //                             if (Math.floor(label) === label) {
            //                                 return label;
            //                             }

            //                         },
            //                     }
            //             }]
            //     },
        },
        data: {
            labels: [
                "Informed",
                "Booked Appointment",
                "Got Appointment",
                "Got Treatment",
                "No Contact",
                // "Total",
            ],
            datasets: [{
                label: "Previous Week Stats",
                data: [Cold,Warm,Hot,Customer,NoContact],
                backgroundColor: [
                    'rgba(49, 176, 213, 0.7)',
                    'rgba(51, 122, 183, 0.7)',
                    'rgba(76, 132, 255, 0.7)',
                    'rgba(92, 184, 92, 0.7)',
                    'rgba(217, 83, 79, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    ],
                    borderColor: [
                    'rgba(49, 176, 213, 1)',
                    'rgba(51, 122, 183, 1)',
                    'rgba(76, 132, 255, 1)',
                    'rgba(92, 184, 92, 1)',
                    'rgba(217, 83, 79, 1)',
                    'rgba(153, 102, 255, 1)',
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
    var NoContact = $('#thisWeekPieChart').data().contact;
    var Customer  = $('#thisWeekPieChart').data().customer;
    var Hot       = $('#thisWeekPieChart').data().hot;
    var Warm      = $('#thisWeekPieChart').data().warm;
    var Cold      = $('#thisWeekPieChart').data().cold;
    var total     = $('#thisWeekPieChart').data().total;
    var myPieChart = new Chart(PIECHART, {

        type: 'pie',
        options: {
            // cutoutPercentage: 90,
            // legend: {
            //     display: true
            // },
            // scales: {
            //         yAxes: [{
            //                 display: true,
            //                 ticks: {
            //                     suggestedMin: 0,
            //                     userCallback: function(label, index, labels) {
            //                             // when the floored value is the same as the value we have a whole number
            //                             if (Math.floor(label) === label) {
            //                                 return label;
            //                             }

            //                         },
            //                     }
            //             }]
            //     },
        },
        data: {
            labels: [
                "Informed",
                "Booked Appointment",
                "Got Appointment",
                "Got Treatment",
                "No Contact",
                // "Total",
                ],
            datasets: [{
                label: "This Week Stats",
                data: [Cold, Warm,Hot,Customer,NoContact],
                backgroundColor: [
                    'rgba(49, 176, 213, 0.7)',
                    'rgba(51, 122, 183, 0.7)',
                    'rgba(76, 132, 255, 0.7)',
                    'rgba(92, 184, 92, 0.7)',
                    'rgba(217, 83, 79, 0.7)',
                    // 'rgba(153, 102, 255, 0.7)',
                    ],
                    borderColor: [
                    'rgba(49, 176, 213, 1)',
                    'rgba(51, 122, 183, 1)',
                    'rgba(76, 132, 255, 1)',
                    'rgba(92, 184, 92, 1)',
                    'rgba(217, 83, 79, 1)',
                    // 'rgba(153, 102, 255, 1)',
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
    var NoContact = $('#thismonthPieChart').data().contact;
    var Customer  = $('#thismonthPieChart').data().customer;
    var Hot       = $('#thismonthPieChart').data().hot;
    var Warm      = $('#thismonthPieChart').data().warm;
    var Cold      = $('#thismonthPieChart').data().cold;
    var total     = $('#thismonthPieChart').data().total;
    var myPieChart = new Chart(PIECHART, {

        type: 'pie',
        options: {
            // cutoutPercentage: 90,
            // legend: {
            //     display: true
            // },
            // scales: {
            //         yAxes: [{
            //                 display: true,
            //                 ticks: {
            //                     suggestedMin: 0,
            //                     userCallback: function(label, index, labels) {
            //                             // when the floored value is the same as the value we have a whole number
            //                             if (Math.floor(label) === label) {
            //                                 return label;
            //                             }

            //                         },
            //                     }
            //             }]
            //     },
        },
        data: {
            labels: [
                "Informed",
                "Booked Appointment",
                "Got Appointment",
                "Got Treatment",
                "No Contact",
                // "Total"
            ],
            datasets: [{
                label: "This Month Stats",
                data: [Cold, Warm,Hot,Customer,NoContact],
                backgroundColor: [
                    'rgba(49, 176, 213, 0.7)',
                    'rgba(51, 122, 183, 0.7)',
                    'rgba(76, 132, 255, 0.7)',
                    'rgba(92, 184, 92, 0.7)',
                    'rgba(217, 83, 79, 0.7)',
                    // 'rgba(153, 102, 255, 0.7)',
                    ],
                    borderColor: [
                    'rgba(49, 176, 213, 1)',
                    'rgba(51, 122, 183, 1)',
                    'rgba(76, 132, 255, 1)',
                    'rgba(92, 184, 92, 1)',
                    'rgba(217, 83, 79, 1)',
                    // 'rgba(153, 102, 255, 1)',
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
    var NoContact = $('#thisyearPieChart').data().contact;
    var Customer  = $('#thisyearPieChart').data().customer;
    var Hot       = $('#thisyearPieChart').data().hot;
    var Warm      = $('#thisyearPieChart').data().warm;
    var Cold      = $('#thisyearPieChart').data().cold;
    var total     = $('#thisyearPieChart').data().total;
    var myPieChart = new Chart(PIECHART, {

        type: 'pie',
        options: {
            // cutoutPercentage: 90,
            // responsive: true
            // legend: {
            //     display: true
            // },
            // scales: {
            //         yAxes: [{
            //                 display: true,
            //                 ticks: {
            //                     suggestedMin: 0,
            //                     userCallback: function(label, index, labels) {
            //                             // when the floored value is the same as the value we have a whole number
            //                             if (Math.floor(label) === label) {
            //                                 return label;
            //                             }

            //                         },
            //                     }
            //             }]
            //     },
        },
        data: {
            labels: [
                "Informed",
                "Booked Appointment",
                "Got Appointment",
                "Got Treatment",
                "No Contact",
                // "Total",
            ],
            datasets: [{
                label: "This Year Stats",
                data: [Cold, Warm,Hot,Customer,NoContact],
                backgroundColor: [
                    'rgba(49, 176, 213, 0.7)',
                    'rgba(51, 122, 183, 0.7)',
                    'rgba(76, 132, 255, 0.7)',
                    'rgba(92, 184, 92, 0.7)',
                    'rgba(217, 83, 79, 0.7)',
                    // 'rgba(153, 102, 255, 0.7)',
                    ],
                    borderColor: [
                    'rgba(49, 176, 213, 1)',
                    'rgba(51, 122, 183, 1)',
                    'rgba(76, 132, 255, 1)',
                    'rgba(92, 184, 92, 1)',
                    'rgba(217, 83, 79, 1)',
                    // 'rgba(153, 102, 255, 1)',
                    ],
                borderWidth: 1,
                animation: true,
            }]
        }
    });

// ------------------------------------------------------- //
    // Pie Chart For This Month-appointment
    // ------------------------------------------------------ //
    var PIECHART            = $('#numberofemployeesPieChart');
    var total_cost          = $('#numberofemployeesPieChart').data().total_cost;
    var total_original_cost = $('#numberofemployeesPieChart').data().total_original_cost;
    var discounted_cost     = $('#numberofemployeesPieChart').data().discounted_cost;
    var myPieChart          = new Chart(PIECHART, {

        type: 'pie',
        options: {
            // cutoutPercentage: 90,
            // responsive: true
            // legend: {
            //     display: true
            // },
            // scales: {
            //         yAxes: [{
            //                 display: true,
            //                 ticks: {
            //                     suggestedMin: 0,
            //                     userCallback: function(label, index, labels) {
            //                             // when the floored value is the same as the value we have a whole number
            //                             if (Math.floor(label) === label) {
            //                                 return label;
            //                             }

            //                         },
            //                     }
            //             }]
            //     },
        },
        data: {
            labels: [
                "Discounted Cost",
                "Saving",
                "Original Cost",
            ],
            datasets: [{
                label: "Diagnostics Stats",
                data: [total_cost,discounted_cost,total_original_cost],
                backgroundColor: [
                    'rgba(49, 176, 213, 0.7)',
                    'rgba(56, 193, 114, 0.7)',
                    'rgba(51, 122, 183, 0.7)',
                    'rgba(92, 184, 92, 0.7)',
                    'rgba(217, 83, 79, 0.7)',
                    // 'rgba(153, 102, 255, 0.7)',
                    ],
                    borderColor: [
                    'rgba(49, 176, 213, 1)',
                    'rgba(56, 193, 114, 1)',
                    'rgba(51, 122, 183, 1)',
                    'rgba(92, 184, 92, 1)',
                    'rgba(217, 83, 79, 1)',
                    // 'rgba(153, 102, 255, 1)',
                    ],
                borderWidth: 1,
                animation: true,
            }]
        }
    });
});
</script>
</section>
