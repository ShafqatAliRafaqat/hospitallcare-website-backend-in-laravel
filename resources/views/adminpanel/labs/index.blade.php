@extends('adminpanel.layout')
@section('title','Labs | HospitALL')
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
                          <th>Address</th>
                          <th>Created By</th>
                          <th>Updated By</th>
                          <th>Status</th>
		                  <th>Edit</th>
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
                            @php
                              $cid = Auth::user()->find($c->created_by);
                              $uid = Auth::user()->find($c->updated_by);
                          @endphp
                          <td>{{ isset($cid->name)? $cid->name:"" }}</a></td>
                          <td>{{ isset($uid->name)? $uid->name:"" }}</a></td>
		                      <td>{{ $c->is_active == 1 ? 'Active' : 'Not Active' }}</td>
		                      <td><a href="{{ route('labs.edit', $c->id) }}"><i class="fa fa-edit"></i></a></td>
                              <td>
                                <a class="delete" data-id="{{ $c->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$c->id}}" method="post" action="{{ route('labs.destroy', $c->id) }}">
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
