@extends('adminpanel.layout')
@section('content')
<div class="container-fluid px-xl-5">
  	<section class="py-5">
	    <div class="row">
	    	@include('adminpanel.notification')
	      	<div class="col-lg-12">
		        <div class="card">
		          <div class="card-header">
		            <h6 class="text-uppercase mb-0">Permissions<a href="{{ route('permissions.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
		          </div>
		          <div class="card-body table-responsive">
		            <table class="table table-striped table-sm card-text" id="treatment">
		              <thead class="thead-light">
		                <tr>
		                  <th>#</th>
		                  <th>Permission Name</th>
		                  <th>Description</th>
		                  @can('edit_permission')
		                  <th>Edit</th>
		                  @endcan
		                  <th>Delete</th>
		                </tr>
		              </thead>
		              <tbody>
		              	@if($permissions)
		              		@php $no=1 @endphp
		              		@foreach($permissions as $p)
		                    <tr>
		                      <th scope="row">{{$no++}}</th>
		                      <td>{{ $p->name }}</td>
		                      <td>{{ $p->description }}</td>
		                      @can('edit_permission')
		                      <td><a href="{{ route('permissions.edit', $p->id) }}"><i class="fa fa-edit"></i></a></td>
		                      @endcan
		                      <td>
		                      	<a id="delete" data-id="{{ $p->id }}" href="#"><i class="fa fa-trash"></i></a>
		                      	<form id="deleteForm" method="post" action="{{ route('permissions.destroy', $p->id) }}">
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
	if ( confirm('Are you sure to delete?') ){
      $('#deleteForm'+id).submit();
    }else {

    }
});
</script>
@endsection
