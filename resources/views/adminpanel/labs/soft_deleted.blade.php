@extends('adminpanel.layout')
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
		            <h6 class="text-uppercase mb-0">Labs <a href="{{ route('labs.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
		          </div>
		          <div class="card-body table-responsive">
		            <table class="table table-striped table-sm card-text" id="labs">
		              <thead class="thead-light">
		                <tr>
		                  <th>#</th>
		                  <th>Lab Name</th>
		                  <th>Assistant's Name</th>
                          <th>Assistant's Phone</th>
                          <th>Location</th>
                          <th>Status</th>
		                  <th>Restore</th>
		                  <th>Delete</th>
		                </tr>
		              </thead>
		              <tbody>
		              	@if( $labs)
		              		@php $no = 1; @endphp
		              		@foreach($labs as $c)
		                    <tr>
		                      <th scope="row">{{$no++}}</th>
		                      <td>
		                      	<a href="{{ route('labs.show',$c->id) }}">
		                      		{{ $c->name }}
		                      	</a>
		                      </td>
		                      <td>{{ $c->assistant_name }}</td>
                              <td>{{ $c->assistant_phone }}</td>
                              <td>{{ $c->address }}</td>
		                      <td>{{ $c->is_active == 1 ? 'Active' : 'Not Active' }}</td>
		                      <td><a class="restore" data-id="{{ $c->id }}" data-toggle="tooltip" title="Restore" href="#"><i class="fas fa-undo"></i></a>
                                <form id="restoreForm{{$c->id}}" method="post" action="{{ route('lab_restore', $c->id) }}">
                                      @csrf @method('post')
                                </form>
                            </td>
                              <td>
                                <a class="delete" data-id="{{ $c->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$c->id}}" method="post" action="{{ route('lab_per_delete', $c->id) }}">
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
<script src="{{ asset('backend/js/sweetalert/sweetalert.js') }}"></script>
<script>
$(document).on('click', '.restore', function(){
    var id = $(this).data('id');
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
        function(){
        setTimeout(function () {
        $('#restoreForm'+id).submit();
        swal("Restored!", "Restored Successfully.", "success");
        }, 2000);
        });
});
</script>
<script>
$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    swal({
        title: "Are you sure?",
        text: "You want to delete lab!",
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
        swal("Deleted!", "Lab has been deleted.", "success");
    }, 2000);
        });
});
</script>
<script>
$(document).ready(function() {
    $('#labs').DataTable();
    setTimeout(function() {
	    $('.alert').fadeOut('slow');
	}, 2000);
});
</script>
@endsection
