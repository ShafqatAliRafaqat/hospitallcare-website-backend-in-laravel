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
                    <h6 class="text-uppercase mb-0">Treatments
                      <a href="{{ route('emp_treatment_history', $customer->id) }}" class="btn btn-sm btn-dark float-right">
                          History
                      </a>
                    </h6>
                  <br>
                    @php
                    $user = Auth::user()->find($customer->patient_coordinator_id);
                    @endphp
                  </div>
                  <div class="card-body table-responsive">

                      <!-- Treaments -->
                      @php
                      $customer_details = TreatmentsAndCenters($customer->id);
                      if($customer_details->center_ids != NULL){
                      $treatment_id = explode(",",$customer_details->treatment_ids);
                      $treatment_name = explode(",",$customer_details->treatment);
                      $center_name = explode(",",$customer_details->center_name);
                      $cost   =explode(",",$customer_details->cost);
                      $len = count($treatment_id);
                      for($i = 0; $i < $len; $i++){
                      $pid = $treatment_id[$i];
                      $Parent_procedure = ParentTreatment($pid);
                      @endphp

                       <div class="row pt-2">
                        <div class="col-md-5">
                          <h5 class="text-center">Treatment # {{$i+1}}</h5>
                        </div>
                        <div class="col-md-7">
                        <ul>
                          <li><b>Treatment:</b> {{ $Parent_procedure->parent_name }}</li>
                          <li><b>Procedure:</b> {{ $treatment_name[$i] }}</li>
                          <li><b>Center:</b> {{ $center_name[$i] }}</li>
                          <li><b>Treatment Cost:</b> {{ $cost[$i] }}</li>
                        </ul>
                        </div>
                      </div>
                                            <hr>
                      @php }} else { @endphp
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
        if($lab != NULL){
          $customer_details   = TreatmentsCentersRelation($customer->id);
          $count_labs         = count($lab);
          $j = 1;
          if($count_labs != NULL){
          for($i=0; $i < $count_labs; $i++){
            $diagnostics  = CustomerLabDiagnostics($customer->id,$lab[$i]);
        @endphp
        <div class="col-lg-6 mb-5 pt-4">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Diagnostics from {{ $diagnostics[0]->lab_name}}</h3>
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
                      <th scope="col">Discount%</th>
                      <th scope="col">Discounted Cost</th>
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
                    <td>{{ $d->discount_per }}%</td>
                    <td>{{ $d->discounted_cost }}</td>
                  </tr>

                    @php $j++; $e++; } @endphp
                  </tbody>
                </table>

              </div>
            </div>
          </div>
          <div class= "card-footer">
            <div class="row">
            <div class="col-md-4 h6 mb-0 text-left">Appointment Date: {{ $diagnostics[0]->appointment_date}}</div>
              <div class="col-md-4 h6 mb-0 ">Original Cost :
              @php
              $sum =0;
              foreach($diagnostics as $d){
                $sum +=$d->cost;
              }
              @endphp
              {{$sum}}
              </div>
              <div class="col-md-4 h6 mb-0">Discounted Cost :
              @php
              $sum =0;
              foreach($diagnostics as $d){
                $sum +=$d->discounted_cost;
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

            <div class="col-lg-12 mt-5">
                <div class="card">
                  <div class="card-header">
                  <h6 class="text-uppercase mb-0">Dependents
                    <form action="{{ route('dependent.create') }}">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{$customer->id}}">
                      <button type="submit" class="btn btn-sm btn-dark float-right">Create New</button>
                      </form>
                    </h6>
                </div>
                  <div class="card-body table-responsive">
                  <table class="table table-striped table-sm card-text" id="customers">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Address</th>
                          <th>Relationship</th>
                          <th>Details</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if(isset($employee))
                            @php $no=1 @endphp
                            @foreach($employee as $c)

                            <tr>
                              <th scope="row">{{$no++}}</th>
                              <td>{{ $c->name }}</td>
                              <td>{{ $c->email }}</td>
                              <td>{{ $c->phone }}</td>
                              <td>{{ $c->address }}</td>
                              <td>{{ $c->relation }}</td>
                              <td><center><a href="{{ route('dependent.show', $c->id) }}"><i class="fa fa-eye"></i></a></center></td>
                              <td><center><a href="{{ route('dependent.edit', $c->id) }}"><i class="fa fa-edit"></i></a></center></td>
                              <td><center>
                                <a class="delete" data-id="{{ $c->id }}" href="#"><i class="fa fa-trash"></i></a></center>
                                <form id="deleteForm{{$c->id}}" method="post" action="{{ route('dependent.destroy', $c->id) }}">
                                    @csrf @method('delete')
                                </form>
                              </td>
                            </tr>
                            @endforeach
                        @endif
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
@section('scripts')
<script src="{{ asset('backend/js/sweetalert/sweetalert.js') }}"></script>
<script>
$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    // alert(id);
    swal({
        title: "Are you sure?",
        text: "You want to delete dependent data!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
        },
        function(){
            setTimeout(function () {
        $('#deleteForm'+id).submit();
        swal("Deleted!", "Dependent has been deleted.", "success");
    }, 2000);
        });
});
</script>
<script>
$(document).ready(function() {
    $('#customers').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});

</script>
@endsection
