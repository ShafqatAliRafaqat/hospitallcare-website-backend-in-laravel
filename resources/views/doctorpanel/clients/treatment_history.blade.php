@extends('doctorpanel.layout')
@section('title', 'Customers | Patients')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
                        <div class="col-lg-5">
                <div class="card">
                  <div class="card-header">
                    @if($customer->deleted_at == null && $customer->doctor_id == Auth::user()->doctor_id)
                    <h6 class="text-uppercase mb-0">Customer Profile<a href="{{ route('doctorclients.edit', $customer->id) }}" class="btn btn-sm btn-dark float-right">
                      Edit Customer
                    </a>
                  </h6>
                  @endif
                  <br>
                    @php
                    $user = Auth::user()->find($customer->patient_coordinator_id);
                    @endphp
                    <span><strong>Patient Owner:</strong></span><span> {{isset($user->name)?$user->name:""}}</span>
                  </div>
                  <div class="card-body table-responsive">
                      <div class="row">
                        <div class="col-md-12">
                          <ul>
                            <li><b>Card ID:</b> {{ $customer->card_id }}</li>
                            <li><b>Customer Name:</b> {{ $customer->name }}</li>
                            <li><b>Phone:</b> {{ $customer->phone }}</li>
                            <li><b>Age:</b> {{ $customer->age }}</li>
                            <li><b>Weight:</b> {{ $customer->weight }}</li>
                            <li><b>Height:</b> {{ $customer->height }}</li>
                            <li><b>Gender: </b>{{ $customer->gender == 0 ? "Male" : "Female" }}</li>
                            <li><b>Marital Status: </b>{{ $customer->marital_status == 0 ? 'Unmarried' : 'Married' }}</li>
                            <li><b>Last Contact Date:</b> {{ date('d-m-Y', strtotime($customer->updated_at)) }}</li>
                            <li><b>Next Contact Date:</b>
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
                          <!-- <li><b>Treatment Cost:</b> {{ $treatment->pivot->cost }}/-</li>
                          <li><b>Discount Percentage:</b> {{ $treatment->pivot->discount_per }}%</li>
                          <li><b>Discounted Cost:</b> {{ $treatment->pivot->discounted_cost }}/-</li> -->
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
