@extends('adminpanel.layout')
@section('title','Centers | HospitALL')
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
                      <div class="mb-2"> -
                        <a class="toggle-vis clr-green" data-column="1">Center Name</a> -
                        <a class="toggle-vis clr-red" data-column="2">Treatment Name</a> -
                        <a class="toggle-vis clr-green" data-column="3">Focus Area</a> -
                        <a class="toggle-vis clr-green" data-column="4">Address</a> -
                        <a class="toggle-vis clr-red" data-column="5">Created By</a> -
                        <a class="toggle-vis clr-red" data-column="6">Updated By</a> -
                        <a class="toggle-vis clr-green" data-column="7">Image</a> -
                        <a class="toggle-vis clr-green" data-column="8">Status</a>
                      </div>
		            <table class="table table-striped table-lg card-text d" id="centers"  width="100%">
		              <thead class="thead-light">
		                <tr>
		                  <th width= "4%">#</th>
		                  <th>Center Name</th>
		                  <th width= "33%">Treatment Name</th>
		                  <th>Focus Area</th>
                          <th>Address</th>
                          <th>Created By</th>
		                  <th>Updated By</th>
		                  <th>Image</th>
		                  <th>Status</th>
		                  <th>Action</th>
		                </tr>
		              </thead>
		              <tbody>
		              	@if( $centers)
		              		@php $no = 1; @endphp
		              		@foreach($centers as $c)
		                    <tr>
		                      <th width= "4%" scope="row">{{$no++}}</th>
		                      <td>
		                      	<a href="{{ route('medical.show',$c->id) }}">
		                      		{{ $c->center_name }}
		                      	</a>
		                      </td>
		                      <td width= "33%" class="text-justify">
								  @php
									  $med_treat = medical_treatments($c->id);
									$center_treatments =   substr($med_treat->treatment,0,500)
								  @endphp
								{{ $med_treat->treatment }}

		                      </td>
		                      <td>{{ $c->focus_area }}</td>
		                      <td>{{ $c->address }}</td>
                                @php
                                    $id = Auth::user()->find($c->created_by);
                                @endphp
                              <td>{{ isset($id->name)? $id->name:"" }}</a></td>
                                @php
                                    $id = Auth::user()->find($c->updated_by);
                                @endphp
                              <td>{{ isset($id->name)? $id->name:"" }}</a></td>
		                      <td>
	                      	    @php
                                  $images = explode(',',$c->images);
                                  @endphp
                                  @if($c->images)
	                      	        @foreach($images as $img)
	                      	            <img src="{{ asset('backend/uploads/centers/'.$img) }}" width="50" height="50" />
                                    @endforeach
                                @endif
		                      </td>
		                      <td>{{ $c->is_active == 1 ? 'Active' : 'Not Active' }}</td>
		                      <td><a href="{{ route('medical.edit', $c->id) }}"><i class="fa fa-edit mr-2"></i></a>
                                <a class="delete" data-id="{{ $c->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$c->id}}" method="post" action="{{ route('medical.destroy', $c->id) }}">
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
    var table = $('#centers').DataTable( {} );
    //Removing some Columns on Load
    table.columns( [2,5,6] ).visible( false );
    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
        // Get the column API object
        var column = table.column( $(this).attr('data-column') );
        if(column.visible() == true){
          $(this).addClass('clr-red');
          $(this).removeClass('clr-green');
        } else{
          $(this).removeClass('clr-red');
          $(this).addClass('clr-green');
        }
        // Toggle the visibility
        column.visible( ! column.visible() );
    });
    setTimeout(function() {
      $('.alert').fadeOut('slow');
  }, 2000);
});
</script>
@endsection
