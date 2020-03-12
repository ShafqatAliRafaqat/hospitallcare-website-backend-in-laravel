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
		            <h6 class="text-uppercase mb-0">Blog Categories <a href="{{ route('blogcategory.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
		          </div>
		          <div class="card-body table-responsive">
		            <table class="table table-striped table-sm card-text" id="blogcategory_hub">
		              <thead class="thead-light">
		                <tr>
                        <th>#</th>
                          <th>Title</th>
                          <th>Description</th>
                          <th>Created By</th>
                          <th>Updated By</th>
                          <th>Deleted By</th>
		                  <th>Restore</th>
		                  <th>Delete</th>
		                </tr>
		              </thead>
		              <tbody>
		              	@if($blog_categories)
		              		@php $no = 1; @endphp
		              		@foreach($blog_categories as $data)
		                    <tr id ="row_id_{{$data->id}}">
                                <th scope="row">{{$no++}}</th>
                                <td>{{ $data->title }}</td>
                                <td>{{ str_limit(strip_tags($data->description),50)}}</td>
                                    @php
                                        $cid = Auth::user()->find($data->created_by);
                                        $uid = Auth::user()->find($data->updated_by);
                                        $did = Auth::user()->find($data->deleted_by);
                                    @endphp
                                <td>{{ isset($cid->name)? $cid->name:"" }}</a></td>
                                <td>{{ isset($uid->name)? $uid->name:"" }}</a></td>
                                <td>{{ isset($did->name)? $did->name:"" }}</a></td>
		                        <td><a class="restore" data-id ="{{$data->id}}" data-toggle="tooltip" title="Restore" href="#"><i class="fa fa-undo"></i></a></td>
		                        <td><a class="delete" data-id ="{{$data->id}}" href="#"><i class="fa fa-trash"></i></a></td>
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
        text: "You want to Restore Data!",
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
            url:"{{ route('blogcategory_restore',"id") }}",
            data: { id : id},
            success: function () {
              setTimeout(function () {
                  swal("Restore!", "Data has been Restore.", "success");
                }, 2000);
                $("#row_id_" + id).remove();
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
        text: "You want to delete Data!",
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
            url:"{{ route('blogcategory_per_delete',"id") }}",
            data: { id : id},
            success: function () {
              setTimeout(function () {
                  swal("Deleted!", "Data has been deleted.", "success");
                }, 2000);
                $("#row_id_" + id).remove();
            },
        })
        }
		);
});
</script>
<script>
$(document).ready(function() {
    $('#blogcategory_hub').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
@endsection
