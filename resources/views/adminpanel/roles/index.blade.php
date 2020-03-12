@extends('adminpanel.layout')
@section('content')
<div class="container-fluid px-xl-5">
  	<section class="py-5">
	    <div class="row">
	    	@include('adminpanel.notification')
	      	<div class="col-lg-12">
		        <div class="card">
		          <div class="card-header">
		            <h6 class="text-uppercase mb-0">Roles<a href="{{ route('roles.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
		          </div>
		          <div class="card-body table-responsive">
		            <table class="table table-striped table-sm card-text" id="treatment">
		              <thead class="thead-light">
		                <tr>
		                  <th>#</th>
		                  <th>Role Name</th>
		                  <th>Description</th>
		                  <th>Edit</th>
		                  <th>Delete</th>
		                </tr>
		              </thead>
		              <tbody>
		              	@if($roles)
		              		@php $no=1 @endphp
		              		@foreach($roles as $role)
		                    <tr>
		                      <th scope="row">{{$no++}}</th>
		                      <td>{{ $role->name }}</td>
		                      <td>{{ $role->description }}</td>
		                      <td><a href="{{ route('roles.edit', $role->id) }}"><i class="fa fa-edit"></i></a></td>
		                      <td>
		                      	<a id="delete" href="#"><i class="fa fa-trash"></i></a>
		                      	<form id="deleteForm" method="post" action="{{ route('roles.destroy', $role->id) }}">
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
<script>
$(document).ready(function() {
    $('#treatment').DataTable();
    setTimeout(function() {
	    $('.alert').fadeOut('slow');
	}, 2000);
});

$(document).on('click', '#delete', function(){
	$('form#deleteForm').submit();
});
</script>
@endsection
