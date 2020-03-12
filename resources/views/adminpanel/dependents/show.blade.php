@extends('adminpanel.layout')
@section('title', 'Customers | Patients')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-7">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Treatments </h6>
                  </div>
                  <div class="card-body table-responsive">
                      <!-- Treaments -->
                      @php
                        $customer_details   = TreatmentsCentersRelation($customer->id);
                        if($customer_details->center->count() != NULL){
                        $i = 0;
                        foreach($customer_details->treatments as $treatment){
                        $parent_treatment   = ParentTreatment($treatment->id);
                      @endphp
                       <div class="row pt-2">
                        <div class="col-md-5">
                          <h5 class="text-center mb-2">Treatment # {{$i+1}}</h5>
                          @if($treatment->pivot->discount_per)
                          <h6 class="text-center mb-3">Discount: {{$treatment->pivot->discount_per}}%</h6>
                          @endif
                        </div>
                        <div class="col-md-7">
                        <ul>
                          <li><b>Treatment:</b> {{ $parent_treatment->parent_name }}</li>
                          <li><b>Procedure:</b> {{ $treatment->name}}</li>
                          <li><b>Center:</b> {{ $customer_details->center[$i]->center_name }}</li>
                          <li><b>Doctor:</b> {{ isset($customer_details->doctor[$i])?$customer_details->doctor[$i]->name:"" }}</li>
                          <li><b> Cost:</b> {{ $treatment->pivot->cost }}</li>
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

            <div class="col-lg-5">
                <div class="card">
                  <div class="card-header">
                    @if($customer->deleted_at == null)
                    <h6 class="text-uppercase mb-0">Customer Profile<a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-dark float-right">
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
                            <li><b>Customer Status:</b> {{ $customer->status }}</li>
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
                <table class="table table-sm card-text table-striped table-dark" id="dependent{{$i+1}}">
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
              <div class="col-md-8 h6 mb-0 text-left">Appointment Date: {{ $diagnostics[0]->appointment_date}}</div>
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



            <div class="col-lg-12 mt-5">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Notes</h6>
                  </div>
                  <div class="card-body table-responsive">
                      {!! $customer->notes !!}
                  </div>
                </div>
            </div>
            @if(isset($display) != null)
              <div class="col-lg-12 mt-5">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Dependents
                      <a href="{{ route('depend', $customer->id) }}" class="btn btn-sm btn-dark float-right">Create New</a>
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
                          <th>Relation with employee</th>
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
                              <td><center><a href="{{ route('dependents.show', $c->id) }}"><i class="fa fa-eye"></i></a></center></td>
                              <td><center><a href="{{ route('dependents.edit', $c->id) }}"><i class="fa fa-edit"></i></a></center></td>
                              <td><center>
                                <a class="delete" data-id="{{ $c->id }}" href="#"><i class="fa fa-trash"></i></a></center>
                                <form id="deleteForm{{$c->id}}" method="post" action="{{ route('dependents.destroy', $c->id) }}">
                                    @csrf @method('delete')
                                </form>
                              </td>
                            </tr>
                            @endforeach
                        @endif
                      </tbody>
                    </table>
                  </div>
                  @endif
                </div>

            </div>

        </div>
    </section>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    $('#dependent1').dataTable( {
      "lengthMenu": [ [5, 10, 15, -1], [5, 10, 15, "All"] ],
        "pageLength": 5
        });
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
$(document).ready(function() {
    $('#dependent2').dataTable( {
      "lengthMenu": [ [5, 10, 15, -1], [5, 10, 15, "All"] ],
        "pageLength": 5
        });
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
$(document).ready(function() {
    $('#customer').DataTable();
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
@endsection
