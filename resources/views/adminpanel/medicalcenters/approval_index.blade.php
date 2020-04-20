@extends('adminpanel.layout')
@section('title','Centers | HospitALL')
@section('content')
<?php
use Illuminate\Support\Facades\DB;
?>
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Medical Centers <a href="{{ route('medical.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="centers">
                      <thead class="thead-light">
                        <tr>
                          <th>#</th>
                          <th>Center Name</th>
                          <th>Address</th>
                          <th>Requested By</th>
                          <th>Status</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if( $centers)
                            @php $no = 1; @endphp
                            @foreach($centers as $c)
                            <tr>
                              <th scope="row">{{$no++}}</th>
                              <td>
                                {{-- <a href="{{ route('medical.show',$c->id) }}"> --}}
                                    {{ $c->center_name }}
                                {{-- </a> --}}
                              </td>

                              <td>{{ $c->address }}</td>
                                @if(isset($c->requested_by))
                                @php $doctor_name  = doctorName($c->requested_by); @endphp
                                <td>{{ $doctor_name }}</td>
                                @else
                                <td></td>
                                @endif

                              <td>{{ $c->is_active == 1 ? 'Active' : 'Not Active' }}</td>
                              <td><a href="{{ route('approve_center_edit', $c->id) }}"><i class="fa fa-edit"></i></a></td>
                              <td>
                                <a class="delete" data-id="{{ $c->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$c->id}}" method="post" action="{{ route('medical.destroy', $c->id) }}">
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
    swal({
        title: "Are you sure?",
        text: "You want to delete medical center!",
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
        swal("Deleted!", "Medical center has been deleted.", "success");
    }, 2000);
        });
});
</script>
<script>
$(document).ready(function() {
    $('#centers').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
@endsection
