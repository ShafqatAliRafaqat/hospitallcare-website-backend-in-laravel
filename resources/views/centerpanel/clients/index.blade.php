@extends('centerpanel.layout')
@section('title', 'Clients | Patients')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('centerpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Clients
                      <!-- <a href="{{ route('customers.create') }}" class="btn btn-sm btn-dark float-right">Create New</a> -->
                    </h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="customers">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Patient Owner</th>
                          <th>Customer Name</th>
                          <th>Procedure</th>
<!--                           <th>Center</th> -->
                          <!-- <th>Status</th> -->
                          <th>Cost</th>
                          <th>Appointment Date</th>
                          <!-- <th>Action</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        @if($clients)

                            @php $no=1 @endphp
                            @foreach($clients as $c)
                            @if($c != null)
                            <tr>
                              <th scope="row">{{$no++}}</th>
                              @php
                              $id = Auth::user()->find($c->patient_coordinator_id);
                              @endphp
                              <td>{{ isset($id->name)? $id->name:"" }}</a></td>
                              <td> {{ $c->name }}</td>
                              <td>{{ (isset($c->treatments[0]->name))? $c->treatments[0]->name : ""}}</td>
                              <td>{{(isset ($c->treatments[0]->pivot->cost))? $c->treatments[0]->pivot->cost:"" }}</td>
                              <td>{{ (isset ($c->treatments[0]->pivot->appointment_date))? $c->treatments[0]->pivot->appointment_date:"" }}</td>
                            </tr>
                            @endif
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
@endsection
