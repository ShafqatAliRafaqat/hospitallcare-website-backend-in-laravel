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
		            <h6 class="text-uppercase mb-0">Medical Centers <a href="{{ route('medical.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
		          </div>
		          <div class="card-body table-responsive">
		            <table class="table table-striped table-sm card-text" id="centers">
		              <thead class="thead-light">
		                <tr>
		                  <th>#</th>
		                  <th>Center Name</th>
		                  <th>Treatment Name</th>
		                  <th>Focus Area</th>
		                  <th>Location</th>
		                  <th>Image</th>
		                  <th>Status</th>
		                  <th>Restore</th>
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
		                      	<a href="{{ route('medical.show',$c->id) }}">
		                      		{{ $c->center_name }}
		                      	</a>
		                      </td>
		                      <td>
		                      	@php $med_treat = medical_treatments($c->id);@endphp
								{{ $med_treat->treatment}}

		                      </td>
		                      <td>{{ $c->focus_area }}</td>
		                      <td>{{ $c->address }}</td>
		                      <td>
		                      	@php
		                      		$images = explode(',',$c->images);

		                      	@endphp
		                      	@foreach($images as $img)
		                      	<img src="{{ asset('backend/uploads/centers/'.$img) }}" width="50" height="50" />
		                      	@endforeach
		                      </td>
		                      <td>{{ $c->is_active == 1 ? 'Active' : 'Not Active' }}</td>
		                      <td><a class="restore" data-id="{{ $c->id }}" data-toggle="tooltip" title="Restore" href="#"><i class="fas fa-undo"></i></a>
                                <form id="restoreForm{{$c->id}}" method="post" action="{{ route('center_restore', $c->id) }}">
                                      @csrf @method('post')
                                </form>
                              </td>
                              <td>
                                <a class="delete" data-id="{{ $c->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$c->id}}" method="post" action="{{ route('center_per_delete', $c->id) }}">
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
