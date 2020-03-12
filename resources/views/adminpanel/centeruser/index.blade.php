@extends('adminpanel.layout')
@section('content')
<div class="container-fluid px-xl-5">
  	<section class="py-5">
	    <div class="row">
	    	@include('adminpanel.notification')
	      	<div class="col-lg-12">
		        <div class="card">
		          <div class="card-header">
		            <h6 class="text-uppercase mb-0">Center Users<a href="{{ route('center_users.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
		          </div>
		          <div class="card-body table-responsive">
		            <table class="table table-striped table-sm card-text" id="treatment">
		              <thead class="thead-light">
		                <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Center</th>
                            <th>Edit</th>
                            <th>Delete</th>
		                </tr>
		              </thead>
		              <tbody>
										@if($users)
		              		@php $no=1 @endphp
		              		@foreach($users as $user)
		                    <tr>
		                      <th scope="row">{{$no++}}</th>
		                      <td>{{ $user->name }}</td>
													<td>{{ $user->email }}</td>
													<td>{{ (isset($user->Role[0]->name))? $user->Role[0]->name:"" }}</td>
													<td>{{ (isset($user->Center->center_name))? $user->Center->center_name:"" }}</td>
		                      <td><a href="{{ route('center_users.edit', $user->id) }}"><i class="fa fa-edit"></i></a></td>
		                      <td>
                                <a class="delete" data-id="{{ $user->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$user->id}}" method="post" action="{{ route('center_users.destroy', $user->id) }}">
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
        text: "You want to delete center user!",
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
        swal("Deleted!", "User has been deleted.", "success");
    }, 2000);
        });
});
</script>
<script>
$(document).ready(function() {
    $('#treatment').DataTable();
    setTimeout(function() {
	    $('.alert').fadeOut('slow');
	}, 2000);
});
</script>
@endsection
