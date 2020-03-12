@extends('adminpanel.layout')
@section('content')
<div class="container-fluid px-xl-5">
  	<section class="py-5">
	    <div class="row">
	    	@include('adminpanel.notification')
	      	<div class="col-lg-12">
		        <div class="card">
		          <div class="card-header">
		            <h6 class="text-uppercase mb-0">Users<a href="{{ route('users.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
		          </div>
		          <div class="card-body table-responsive">
		            <table class="table table-striped table-sm card-text" id="treatment">
		              <thead class="thead-light">
		                <tr>
							<th>#</th>
							<th>Name</th>
							<th>Email</th>
							<th>Role</th>
							<th>Organization</th>
							<th>Restore</th>
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
    							<td>{{(isset($user->Organization))? $user->Organization->name:"" }}</td>
		                      <td><a class="restore" data-id="{{ $user->id }}" data-toggle="tooltip" title="Restore" href="#"><i class="fa fa-undo"></i></a>
                                <form id="restoreForm{{$user->id}}" method="post" action="{{ route('user_restore', $user->id) }}">
                                  @csrf @method('post')
                                </form>
                            </td>
		                      <td>
                                <a class="delete" data-id="{{ $user->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$user->id}}" method="post" action="{{ route('user_per_delete', $user->id) }}">
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
        text: "You want to delete User!",
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
