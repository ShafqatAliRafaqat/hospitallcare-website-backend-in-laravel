@extends('adminpanel.layout')
@section('title', 'Blog Categories')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Blog Categories <a href="{{ route('blogcategory.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="blog_categories">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Description</th>
                          <th>Created By</th>
                          <th>Updated By</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($blog_categories)
                            @php $no=1 @endphp
                            @foreach($blog_categories as $data)
                            <tr>
                              <th scope="row">{{$no++}}</th>
                              <td>{{ $data->name }}</td>
                              <td> {{ str_limit(strip_tags($data->description),50)}}</td>
                              @php
                              		$cid = Auth::user()->find($data->created_by);
                              		$uid = Auth::user()->find($data->updated_by);
                            	@endphp
                              <td>{{ isset($cid->name)? $cid->name:"" }}</a></td>
                              <td>{{ isset($uid->name)? $uid->name:"" }}</a></td>
                              <td><a href="{{ route('blogcategory.edit', $data->id) }}"><i class="fa fa-edit"></i></a></td>
                              <td>
                                <a class="delete" data-id="{{ $data->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$data->id}}" method="post" action="{{ route('blogcategory.destroy', $data->id) }}">
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
    $('#blog_categories').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
<script src="{{ asset('backend/js/sweetalert/sweetalert.js') }}"></script>
<script>
$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    swal({
        title: "Are you sure?",
        text: "You want to delete data!",
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
        swal("Deleted!", "Data has been deleted.", "success");
    }, 2000);
        });
});
</script>
@endsection
