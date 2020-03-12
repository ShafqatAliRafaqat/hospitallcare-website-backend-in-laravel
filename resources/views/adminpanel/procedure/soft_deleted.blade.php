@extends('adminpanel.layout')
@section('content')
<div class="container-fluid px-xl-5">
  	<section class="py-5">
	    <div class="row">
	    	<div class="col-md-8 offset-md-2">
	    		@if( session()->has('success') )
			      	<div class="alert alert-success alert-dismissible fade show" role="alert">
						{{ session()->get('success') }}
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					    	<span aria-hidden="true">&times;</span>
					  	</button>
					</div>
		      	@endif
		      	@if( session()->has('error') )
			      	<div class="alert alert-danger alert-dismissible fade show" role="alert">
						{{ session()->get('error') }}
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					    	<span aria-hidden="true">&times;</span>
					  	</button>
					</div>
		      	@endif
	    	</div>
	      	<div class="col-lg-12">
		        <div class="card">
		          <div class="card-header">
		            <h6 class="text-uppercase mb-0">Procedures <a href="{{ route('procedure.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
		          </div>
		          <div class="card-body table-responsive">
		            <table class="table table-striped table-sm card-text" id="treatment">
		              <thead class="thead-light">
		                <tr>
		                  <th>#</th>
		                  <th>Procedure Name</th>
		                  <th>Picture</th>
		                  <th>Article</th>
		                  <th>Status</th>
		                  <th>Show in Menu</th>
		                  <th>Restore</th>
		                  <th>Delete</th>
		                </tr>
		              </thead>
		              <tbody>
		              	@if($procedures)
		              		@php $no = 1; @endphp
		              		@foreach($procedures as $procedure)
		                    <tr id ="treatment_id_{{$procedure->id}}">
		                      <th scope="row">{{ $no++ }}</th>
		                      <td>{{ $procedure->name }}</td>
		                      <td>
						     @if($procedure->treatment_image != null)
								@foreach($procedure->treatment_image as $image)
	                      		   <img src="{{ asset('backend/uploads/treatments/'. $image->picture) }}" width="50" height="50" />
								@endforeach
							@endif
		                      </td>
		                      <td>{!! str_limit($procedure->article,50) !!}</td>
		                      <td>{{ $procedure->is_active == 1 ? 'Active' : 'Not Active' }}</td>
		                      <td>{{ $procedure->show_in_menu == 1 ? 'Shown' : 'Not Shown' }}</td>
		                      <td><a class="restore" data-id ="{{$procedure->id}}" data-toggle="tooltip" title="Restore" href="#"><i class="fa fa-undo"></i></a></td>
                                <form id="restoreForm{{$procedure->id}}" method="post" action="{{ route('procedure_restore', $procedure->id) }}">
                                  @csrf @method('post')
                                </form>
		                      <td>
		                      	<a class="delete" data-id ="{{$procedure->id}}" href="#"><i class="fa fa-trash"></i></a>
		                      	<form id="deleteForm{{$procedure->id}}" method="post" action="{{ route('procedure_per_delete', $procedure->id) }}">
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
	$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
    swal({
        title: "Are you sure?",
        text: "You want to Restore Procedure!",
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
            url:"{{ route('procedure_restore',"id") }}",
            data: { id : id},
            success: function () {
              setTimeout(function () {
                  swal("Restore!", "Procedure has been Restore.", "success");
                }, 2000);
                $("#treatment_id_" + id).remove();
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
        text: "You want to delete Procedure!",
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
            url:"{{ route('procedure_per_delete',"id") }}",
            data: { id : id},
            success: function () {
              setTimeout(function () {
                  swal("Deleted!", "Procedure has been deleted.", "success");
                }, 2000);
                $("#treatment_id_" + id).remove();
            },
        })
        }
		);
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
