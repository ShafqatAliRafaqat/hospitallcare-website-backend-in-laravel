@extends('doctorpanel.layout')
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
                      <a href="{{ route('doctorclients.create') }}" class="btn btn-sm btn-dark float-right">Create New</a>
                    </h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="customers">
                      <thead>
                        <tr>
                          <th>#</th>
                          <!-- <th>Patient Owner</th> -->
                          <th>Customer Name</th>
                          <th>Phone</th>
                          <th>Procedure</th>
                          <th>Center</th>
                          <!-- <th>Status</th> -->
                          <th>Appointment Date</th>
                          <th>Upload Documents</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($clients)

                            @php $no=1 @endphp
                            @foreach($clients as $c)
                            @if($c != null)
                            <tr>
                              <th scope="row">{{$no++}}</th>
                              <!-- @php
                              $id = Auth::user()->find($c->patient_coordinator_id);
                              @endphp
                              <td>{{ isset($id->name)? $id->name:"" }}</a></td> -->
                              <td><a href="{{ route('doctorclients.show', $c->id) }}">{{ $c->name }}</a></td>
                              <td> {{ $c->phone }}</td>
                              <td>
                                @php $med_treat = indexTreatmentsCenters($c->id);@endphp
                                {{ $med_treat->treatment}}
                              </td>
                              <td>
                                @php $med_center = indexTreatmentsCenters($c->id);@endphp
                                {{ $med_center->center_name}}
                              </td>
                              <!-- <td>{{(isset ($c->costs))? $c->costs:"" }}</td> -->
                              <td>
                                @php $appointment_date = indexTreatmentsCenters($c->id);@endphp
                                {{ $appointment_date->appointment_date}}
                              </td>
                              <td><a href="{{ route('documents_upload', $c->id) }}"><i class="fas fa-upload"></i></a></td>

                              <td><a href="{{ route('doctorclients.show', $c->id) }}"><i class="fa fa-eye pr-1"></i></a>
                                <a href="{{ route('doctorclients.edit', $c->id) }}"><i class="fa fa-edit pr-1"></i></a>
                                <a class="delete" data-id="{{ $c->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$c->id}}" method="post" action="{{ route('doctorclients.destroy', $c->id) }}">
                                    @csrf @method('delete')
                                </form>
                              </td>
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
<script src="{{ asset('backend/js/sweetalert/sweetalert.js') }}"></script>
<script>
$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    swal({
        title: "Are you sure?",
        text: "You want to delete customer data!",
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
        swal("Deleted!", "Customer has been deleted.", "success");
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
