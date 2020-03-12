@extends('adminpanel.layout')
@section('title', 'Doctors | HospitALL')
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
                <h6 class="text-uppercase mb-0">Doctors <a href="{{ route('doctors.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
              </div>
              <div class="card-body table-responsive">
                <table class="table table-striped table-sm card-text" id="centers">
                  <thead class="thead-light">
                    <tr>
                      <th>#</th>
                      <th>Doctor Name</th>
                      <th>Focus Area</th>
                      <th>Centers</th>
                      <th>Location</th>
                      <th>Image</th>
                      <th>Status</th>
                      <th>Restore</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if( $doctors)
                      @php $no = 1; @endphp
                      @foreach($doctors as $d)
                      <tr id="doctor_id_{{$d->id}}">
                          <th scope="row">{{$no++}}</th>
                          <td>
                            <a href="{{ route('doctors.show',$d->id) }}">
                              {{ $d->name }}
                            </a>
                          </td>
                          <td>{{ $d->focus_area }}</td>

                          @php
                              $centers  = $d->centers;
                              $count    = count($d->centers);
                              for($k = 0; $k < $count; $k++){
                                $center_names[]   = $d->centers[$k]->center_name;
                              }
                              if($count > 0){
                                $arr  = array_values(array_unique($center_names));
                                unset($center_names);
                                $str = implode(",", $arr);
                              }
                              $str = (isset($str)? $str :'');
                            @endphp
                          <td>
                            {{$str}}
                          </td>
                          <td>{{ $d->address }}</td>
                          <td>
                            @if(isset($d->doctor_image->picture))
                            <img src="{{ asset('backend/uploads/doctors/'. $d->doctor_image->picture) }}" width="50" height="50" />
                            @endif
                          </td>
                          <td>{{ $d->is_active == 1 ? 'Active' : 'Not Active' }}</td>
                          <td><a class="restore" data-id="{{ $d->id }}" data-toggle="tooltip" title="Restore" href="#"><i class="fas fa-undo"></i></a>
                          
                          </td>
                          <td>
                            <a class="delete" data-id="{{ $d->id }}" href="#"><i class="fa fa-trash" style="padding-left:10; color:red;"></i></a>
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
$(document).on('click', '.restore', function(){
    var id = $(this).data('id');
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
    swal({
        title: "Are you sure?",
        text: "You want to Restore!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, Restore it!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
        },
        function (isConfirm) {
        if (!isConfirm) return;
          $.ajax({
 
            type: "POST",
            url:"{{ route('doctor_restore',"id") }}",
            data: { id : id},
            success: function () {
              setTimeout(function () {
                swal("Restored!", "Restored Successfully.", "success");
                }, 2000);
                $("#doctor_id_" + id).remove();
            },
        })
        }
        );
});
</script>
<script>
$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
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
        function (isConfirm) {
        if (!isConfirm) return;
          $.ajax({
 
            type: "POST",
            url:"{{ route('doctor_per_delete',"id") }}",
            data: { id : id},
            success: function () {
              setTimeout(function () {
                  swal("Deleted!", "Doctor has been deleted.", "success");
                }, 2000);
                $("#doctor_id_" + id).remove();
            },
        })
        }
        );
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
