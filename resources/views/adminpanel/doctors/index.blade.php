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
              <div class="mb-2"> -
                <a class="toggle-vis clr-green" data-column="1">Doctor Name</a> -
                <a class="toggle-vis clr-green" data-column="2">Focus Area</a> -
                <a class="toggle-vis clr-red" data-column="3">Phone</a> -
                <a class="toggle-vis clr-green" data-column="4">Centers</a> -
                <a class="toggle-vis clr-red" data-column="5">Address</a> -
                <a class="toggle-vis clr-red" data-column="6">Created By</a> -
                <a class="toggle-vis clr-red" data-column="7">Updated By</a> -
                <a class="toggle-vis clr-green" data-column="8">Image</a> -
                <a class="toggle-vis clr-green" data-column="9">Status</a> -
                <a class="toggle-vis clr-green" data-column="10">Partner</a>
              </div>
                <table class="table table-striped table-sm card-text" id="centers">
                  <thead class="thead-light">
                    <tr>
                      <th>#</th>
                      <th>Doctor Name</th>
                      <th>Focus Area</th>
                      <th>Phone</th>
                      <th>Centers</th>
                      <th>Address</th>
                      <th>Created By</th>
                      <th>Updated By</th>
                      <th>Image</th>
                      <th>Status</th>
                      <th>Partner</th>
                      <th>Action</th>
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
                          <td>{{ $d->phone }}</td>

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
                            @endphp
                          <td>
                              @if(isset($str))
                            {{$str}}
                            @endif
                          </td>
                          <td>{{ $d->address }}</td>
                           @php
                              $cid = Auth::user()->find($d->created_by);
                              $uid = Auth::user()->find($d->updated_by);
                          @endphp
                          <td>{{ isset($cid->name)? $cid->name:"" }}</a></td>
                          <td>{{ isset($uid->name)? $uid->name:"" }}</a></td>
                          <td>
                            @if(isset($d->doctor_image->picture))
                            <img src="{{ asset('backend/uploads/doctors/'. $d->doctor_image->picture) }}" width="50" height="50" />
                            @endif
                          </td>
                          <td>{{ $d->is_active == 1 ? 'Active' : 'Not Active' }}</td>
                          <td>{{ $d->is_partner == 1 ? 'Yes' : 'No' }}</td>
                          <td class="text-center" >
                            <div style="display: flex">
                              <a href="{{ route('doctors.edit', $d->id) }}" title="Edit Doctor"><i class="fa fa-edit mr-2"></i></a>
                              <a href="{{ route('ViewSchedules', $d->id) }}" title="Edit Doctor Schedule"><i class="far fa-calendar-alt mr-2"></i></a>
                                <!-- <td class="text-center"> -->
                                  <a class="delete" data-id="{{ $d->id }}" href="#" title="Delete Doctor"><i class="fa fa-trash " style="padding-left:10; color:red;"></i></a>
                                  <!-- <form id="deleteForm{{$d->id}}" method="post" action="{{ route('doctors.destroy', $d->id) }}">
                                      @csrf @method('delete')
                                  </form> -->
                              </div>
                              @php $doctor_views = DoctorViews($d->id); @endphp
                                @if($doctor_views >0)
                              <div class="mt-1 views">
                                  <i class="fa fa-eye" aria-hidden="true"> {{ $doctor_views }}</i>
                              </div>
                              @endif
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

            type: "DELETE",
            url:"{{ route('doctors.destroy',"id") }}",
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
    var table = $('#centers').DataTable( {} );
    //Removing some Columns on Load
    table.columns( [3,5,6,7] ).visible( false );

    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
        // Get the column API object
        var column = table.column( $(this).attr('data-column') );
        if(column.visible() == true){
          $(this).addClass('clr-red');
          $(this).removeClass('clr-green');
        } else{
          $(this).removeClass('clr-red');
          $(this).addClass('clr-green');
        }
        // Toggle the visibility
        column.visible( ! column.visible() );
    });
    setTimeout(function() {
      $('.alert').fadeOut('slow');
  }, 2000);
});
</script>
@endsection
