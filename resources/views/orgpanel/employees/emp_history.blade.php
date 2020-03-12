@extends('orgpanel.layout')
@section('title', 'Employee | Patients')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('orgpanel.notification')
            <div class="col-lg-5">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Employee <a href="{{ route('employees.edit', $customer->id) }}" class="btn btn-sm btn-dark float-right">
                      Edit Employee
                    </a>
                  </h6>
                  <br>
                    @php
                    $user = Auth::user()->find($customer->patient_coordinator_id);
                    @endphp
                    <span><strong>Patient Owner:</strong></span><span> {{isset($user->name)?$user->name:""}}</span>
                  </div>
                  <div class="card-body table-responsive">

                      <div class="row">
                        <div class="col-md-12">
                        <ul class="fa-ul fa-ul-font">
                            <li><i class="fa-li fas fa-id-badge"></i><b>Employee Name:</b> {{ $customer->name }}</li>
                            <li><i class="fa-li fa fa-phone"></i><b>Phone:</b> {{ $customer->phone }}</li>
                            <li><i class="fa-li fas fa-user-clock"></i><b>Age:</b> {{ $customer->age }}</li>
                            <li><i class="fa-li fas fa-weight"></i><b>Weight:</b> {{ $customer->weight }}</li>
                            <li><i class="fa-li fas fa-ruler-vertical"></i><b>Height:</b> {{ $customer->height }}</li>

                            <li><i class="fa-li fas fa-venus-mars"></i><b>Gender: </b>{{ $customer->gender == 0 ? "Male" : "Female" }}</li>
                            <li><i class="fa-li fas fa-circle-notch"></i><b>Marital Status: </b>{{ $customer->marital_status == 0 ? 'Unmarried' : 'Married' }}</li>
                            <li><i class="fa-li far fa-calendar-check"></i><b>Last Contact Date:</b> {{ date('d-m-Y', strtotime($customer->updated_at)) }}</li>
                            <li><i class="fa-li far fa-calendar-alt"></i><b>Next Contact Date:</b>
                            @if($customer->next_contact_date==NULL)
                            Not Updated
                            @else
                            {{ date('d-m-Y',strtotime($customer->next_contact_date)) }}

                          @endif
                        </li>
                    </ul>
                        </div>

                      </div>
                  </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Customer History
                        <a href="{{ URL::previous() }}" class="btn btn-sm btn-dark float-right">
                            Back
                        </a>
                    </h6>
                  </div>
                  <div class="card-body table-responsive">
                      <!-- Treaments -->
                      @php

                        $customer_details   = CustomerHistory($customer->id);
                        if($customer_details->center_history->count() != NULL){
                        $i = 0;
                        foreach($customer_details->treatments_history as $treatment){
                        $parent_treatment   = ParentTreatment($treatment->id);
                      @endphp
                       <div class="row pt-2">
                        <div class="col-md-5">
                          <h5 class="text-center mb-5">Treatment # {{$i+1}} </h5>
                          <h5>
                        </div>
                        <div class="col-md-7">
                        <ul>

                          <li><b>Treatment:</b> {{ $parent_treatment->parent_name }}</li>
                          <li><b>Procedure:</b> {{ $treatment->name}}</li>
                          <li><b>Center:</b> {{ $customer_details->center_history[$i]->center_name }}</li>
                          <li><b>Doctor:</b> {{ isset($customer_details->doctor_history[$i])?$customer_details->doctor_history[$i]->name:"" }}</li>
                          <li><b>Treatment Cost:</b> {{ $treatment->pivot->cost }}</li>
                          <li><b>Appointment Date:</b> {{ $treatment->pivot->appointment_date }}</li>
                        </ul>
                        </div>
                      </div>
                      <hr>
                      @php $i++;}} else { @endphp
                      <div class="row pt-2">
                        <div class="col-md-12">
                          <h5 class="text-center">No Treatment Selected</h5>
                        </div>
                      </div>
                      @php } @endphp
                  </div>
                </div>
            </div>



          @php
          if ($customer_details->labs) {
          foreach ($customer_details->labs_history as $lab) {
          $array[]  =  $lab->id;
          }
          if (isset($array)) {
            $lab        = array_values(array_unique($array));
          } else {
            $lab = NULL;
          }
        }
        if($lab != NULL){
          $count_labs         = count($lab);
          $j = 1;
          if($count_labs != NULL){
          for($i=0; $i < $count_labs; $i++){
            $diagnostics  = CustomerLabHistory($customer->id,$lab[$i]);
        @endphp
        <div class="col-lg-6 mb-5 pt-4">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Diagnostics from {{ $diagnostics[0]->lab_name}}</h3>
            <!-- <span>Discount: <strong>{{ $diagnostics[0]->discount}}</strong></span> -->
          </div>
          <div class="card-body">
            <div class="media row">
              <div class="media-body col-md-12">
                <table class="table table-striped table-dark" id="diagnostics{{$i+1}}">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Diagnostics</th>
                      <th scope="col">Costs</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                    $e = 1;
                    foreach($diagnostics as $d){
                    @endphp
                  <tr >
                    <td>{{ $e }}</td>
                    <td>{{ $d->name}}</td>
                    <td>{{ $d->cost }}</td>
                  </tr>

                    @php $j++; $e++; } @endphp
                  </tbody>
                </table>

              </div>
            </div>
          </div>
          <div class= "card-footer">
            <div class="row">
            <div class="col-md-8 h6 mb-0 text-left">Last Appointment Date: {{ $diagnostics[0]->appointment_date}}</div>
              <div class="col-md-4 h6 mb-0 text-right">Total Cost :
              @php
              $sum =0;
              foreach($diagnostics as $d){
                $sum +=$d->cost;
              }
              @endphp
              {{$sum}}
              </div>
            </div>
          </div>
        </div>
      </div>
          @php }} } else { @endphp
            <div class="row pt-5">
              <div class="col-md-12">
                <h5 class="text-center" style="margin-left: 22px;">No Diagnostic Selected</h5>
              </div>
            </div>
            @php } @endphp

        </div>
    </section>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    $('#customers').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});

$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    if ( confirm('Are you sure to delete?') ){
      $('#deleteForm'+id).submit();
    }else {

    }
});

</script>
<script>
  $(document).ready(function() {
    $('#diagnostics1').DataTable({
        "lengthMenu": [5, 10, 15, 20, 25],
        "pageLength": 5
    });
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
<script>
  $(document).ready(function() {
    $('#diagnostics2').DataTable({
      "lengthMenu": [5, 10, 15, 20, 25],
        "pageLength": 5
    });
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
<!-- <script>
  $(document).on('click', '.close_treatment', function(){

    $('#close_treatment0').remove();
  });
</script> -->
@endsection
