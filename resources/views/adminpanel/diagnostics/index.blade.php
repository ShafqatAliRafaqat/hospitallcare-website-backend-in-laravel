@extends('adminpanel.layout')
@section('title','Diagnostics | HospitALL')
@section('content')
<div class="container-fluid px-xl-5">
  	<section class="py-5">
	    <div class="row">
	    	@include('adminpanel.notification')
	      	<div class="col-lg-12">
		        <div class="card">
		          <div class="card-header">
		            <h6 class="text-uppercase mb-0">Diagnostics <a href="{{ route('diagnostics.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
		          </div>
		          <div class="card-body table-responsive">
		            <table class="table table-striped table-sm card-text" id="diagnostic">
		              <thead class="thead-light">
		                <tr>
		                  <th>#</th>
		                  <th>Diagnostics Name</th>
		                  <th>Description</th>
  		                  <th>Status</th>
  		                  <th>Common</th>
                          <th>Created By</th>
                          <th>Updated By</th>
		                  <th>Edit</th>
		                  <th>Delete</th>
		                </tr>
		              </thead>
		              <tbody>
		              	@if($diagnostics)
		              		@php $no=1 @endphp
		              		@foreach($diagnostics as $diagnostic)
		                    <tr>
		                      <th scope="row">{{$no++}}</th>
		                      <td>{{ $diagnostic->name }}</td>
		                      <td>{!! str_limit($diagnostic->description,50) !!}</td>
		                      <td>{{ $diagnostic->is_active == 1 ? 'Active' : 'Not Active' }}</td>
		                      <td>{{ $diagnostic->is_common == 1 ? 'Yes' : 'No' }}</td>
                            @php
                              $cid = Auth::user()->find($diagnostic->created_by);
                              $uid = Auth::user()->find($diagnostic->updated_by);
                            @endphp
                              <td>{{ isset($cid->name)? $cid->name:"" }}</a></td>
                              <td>{{ isset($uid->name)? $uid->name:"" }}</a></td>
		                      <td><a href="{{ route('diagnostics.edit', $diagnostic->id) }}"><i class="fa fa-edit"></i></a></td>
		                      <td>
		                      	<a class="delete" data-id="{{ $diagnostic->id }}" href="#"><i class="fa fa-trash"></i></a>
		                      	<form id="deleteForm{{$diagnostic->id}}" method="post" action="{{ route('diagnostics.destroy', $diagnostic->id) }}">
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
        text: "You want to delete Diagnostic!",
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
        swal("Deleted!", "Diagnostic has been deleted.", "success");
    }, 2000);
        });
});
</script>
<script>
$(document).ready(function() {
    $('#diagnostic').DataTable();
    setTimeout(function() {
	    $('.alert').fadeOut('slow');
	}, 2000);
});
</script>
@endsection
