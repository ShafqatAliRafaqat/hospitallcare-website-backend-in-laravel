<?php
use Carbon\Carbon;
?>
@extends('orgpanel.layout')
@section('title', 'Employee | Claims')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Medical Claims
                      <a href="{{ route('employees.create') }}" class="btn btn-sm btn-dark float-right">Create New</a>
                    </h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="customers">
                      <thead class="thead-light">
                        <tr>
                          <th class="text-center">#</th>
                          <th class="text-center">Title</th>
                          <th class="text-center">Employee Name</th>
                          <th class="text-center">Employee Phone</th>
                          <th class="text-center">Appointment For</th>
                          <th class="text-center">Relation</th>
                          <th class="text-center">Status</th>
                          <th class="text-center">Claim Date</th>
                          @if($pending)
                          <th class="text-center">Edit</th>
                          @endif
                          <th class="text-center">Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($claims)
                            @php $no=1; @endphp
                            @foreach($claims as $c)
                            <tr>
                              <th class="text-center" scope="row">{{$no++}}</th>
                              <td class="text-center">
                                <a class="show a-color a-hover" data-id="{{ $c->claim_id }}">
                                  {{ $c->title }}
                                </a>
                                <form id="showForm{{$c->claim_id}}" method="post" action="{{ route('view_claim', $c->claim_id) }}">
                                    @csrf @method('post')
                                </form>
                                </td>
                              <td class="text-center"> {{ $c->employee_name }}</td>
                              <td class="text-center">{{ $c->employee_phone }}</td>
                              @php if($c->employee_id == $c->d_id){
                              @endphp
                              <td class="text-center">Self</td>
                              <td class="text-center"> - </td>
                              @php } else { @endphp
                              <td class="text-center">{{ $c->d_name }}</td>
                              <td class="text-center">{{ $c->relation }}</td>
                              @php } @endphp
                              @php
                              $status_name      = claimStatusName($c->status);
                              $time             = Carbon::parse($c->claim_date);
                              $fdate            = $time->format('d-m-Y');
                              @endphp
                              <td class="text-center">{{ $status_name }}</td>

                              <td class="text-center">{{ $fdate }}</td>
                              @if($pending)
                              <td class="text-center">
                                <a type="button" class="edit a-hover" data-id="{{ $c->claim_id }}">
                                  <img src="{{ asset('backend/web_imgs/edit2.png') }}" width="24px">
                                </a>
                                <form id="editForm{{$c->claim_id}}" method="post" action="{{ route('edit_claim', $c->claim_id) }}">
                                    @csrf @method('post')
                                </form>
                              </td>
                              @endif
                              <td class="text-center">
                                  <a type="button" class="delete a-hover" data-id="{{ $c->claim_id }}">
                                    <img src="{{ asset('backend/web_imgs/trash1.png') }}">
                                  </a>
                                  <form id="deleteForm{{$c->claim_id}}" method="post" action="{{ route('delete_claim', $c->claim_id) }}">
                                    @csrf @method('post')
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
<script>
$(document).ready(function() {
    $('#customers').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});

</script>
<script src="{{ asset('backend/js/sweetalert/sweetalert.js') }}"></script>
<script>
$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    // alert(id);
    swal({
        title: "Are you sure?",
        text: "You want to delete Employee data!",
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
        swal("Deleted!", "Employee has been deleted.", "success");
    }, 2000);
        });
});
</script>
<script>
  $(document).on('click', '.edit', function(){
    var id = $(this).data('id');
    $('#editForm'+id).submit();
  });
</script>
<script>
$(document).on('click', '.show', function(){
    var id = $(this).data('id');
    $('#showForm'+id).submit();
  });
</script>
@endsection
