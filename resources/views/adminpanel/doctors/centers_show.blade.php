@extends('adminpanel.layout')
@section('title', 'Schedule | HospitALL')
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
            <h6 class="text-uppercase mb-0">Centers of {{$doctor->name}} </h6>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-striped table-sm card-text" id="centers">
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>Center Name</th>
                  <th>Focus Area</th>
                  <th>Address</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>
                @if( $center_list)
                @php $no = 1; @endphp
                @foreach($center_list as $d)
                <tr>
                  <th scope="row">{{$no++}}</th>
                  <td>
                    <a href="{{ route('medical.show',$d->center_id) }}">
                      {{ $d->center_name }}
                    </a>
                  </td>
                  <td>{{ $d->focus_area }}</td>
                  <td>{{ $d->address }}</td>
                  <td><a href="#" class="edit" data-id="{{ $d->center_id }}"><i class="fa fa-edit"></i></a>
                    <form id="editForm{{$d->center_id}}" method="post" action="{{ route('doctor_schedule_edit', $d->center_id) }}">
                      <input type="hidden" name="doctor_id" value="{{ $d->id }}">
                      @csrf @method('post')
                    </form>
                  </td>
                  <td>
                    <a class="delete" data-id="{{ $d->center_id }}" href="#"><i class="fa fa-trash" style="padding-left:10; color:red;"></i></a>
                    <form id="deleteForm{{$d->center_id}}" method="post" action="{{ route('doctors_centers_destroy', $d->center_id) }}">
                      @csrf @method('post')
                      <input type="hidden" name="doctor_id" value="{{ $d->id }}">
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
      text: "You want to delete doctor!",
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
        swal("Deleted!", "Doctor has been deleted.", "success");
      }, 2000);
    });
  });
</script>
<script>
  $(document).on('click', '.edit', function(){
    var id = $(this).data('id');
    setTimeout(function () {
      $('#editForm'+id).submit();
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
