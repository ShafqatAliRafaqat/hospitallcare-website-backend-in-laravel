@extends('adminpanel.layout')
@section('content')
<div class="container-fluid px-xl-5">
  	<section class="py-5">
	    <div class="row">
	    	@include('adminpanel.notification')
	      	<div class="col-lg-12">
		        <div class="card">
		          <div class="card-header">
		            <h6 class="text-uppercase mb-0">Treatments <a href="{{ route('treatment.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
		          </div>
		          <div class="card-body table-responsive">
		            <table class="table table-striped table-sm card-text" id="treatment">
		              <thead class="thead-light">
		                <tr>
                            <th>#</th>
                            <th>Treatment Name</th>
                            <th>Picture</th>
                            <th>Article</th>
                            <th>Status</th>
                            <th>Show In Menu</th>
                            <th>Created By</th>
                            <th>Updated By</th>
                            <th>Edit</th>
                            <th>Delete</th>
		                </tr>
		              </thead>
		              <tbody>
		              	@if($treatments)
		              		@php $no=1 @endphp
		              		@foreach($treatments as $treatment)
		                    <tr id="treatment_id_{{$treatment->id}}">
		                      <th scope="row">{{$no++}}</th>
		                      <td>{{ $treatment->name }}</td>
		                      <td>
                                @if($treatment->treatment_image != null)
                                    @foreach($treatment->treatment_image as $image)
                                        <img src="{{ asset('backend/uploads/treatments/'. $image->picture) }}" width="50" height="50" />
                                    @endforeach
                                @endif
							 </td>
		                      <td>{!! str_limit($treatment->article,50) !!}</td>
		                      <td>{{ $treatment->is_active == 1 ? 'Active' : 'Not Active' }}</td>
		                      <td>{{ $treatment->show_in_menu == 1 ? 'Shown' : 'Not Shown' }}</td>
                             @php
                              $cid = Auth::user()->find($treatment->created_by);
                              $uid = Auth::user()->find($treatment->updated_by);
                            @endphp
                              <td>{{ isset($cid->name)? $cid->name:"" }}</a></td>
                              <td>{{ isset($uid->name)? $uid->name:"" }}</a></td>
		                      <td><a href="{{ route('treatment.edit', $treatment->id) }}"><i class="fa fa-edit"></i></a></td>
		                      <td>
		                      	<a class="delete" data-id="{{ $treatment->id }}" href="#"><i class="fa fa-trash"></i></a>
		                      
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
	$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
    swal({
        title: "Are you sure?",
        text: "You want to delete treatment!",
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
 
            type: "DELETE",
            url:"{{ route('treatment.destroy',"id") }}",
            data: { id : id},
            success: function () {
              setTimeout(function () {
                  swal("Deleted!", "Treatment has been deleted.", "success");
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
