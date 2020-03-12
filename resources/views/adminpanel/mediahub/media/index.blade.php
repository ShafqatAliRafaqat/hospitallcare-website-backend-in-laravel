@extends('adminpanel.layout')
@section('title', 'Media')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Media <a href="{{ route('media.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="media">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Title</th>
                          <th>Description</th>
                          <th>Link</th>
                          <th>Status</th>
                          <th>Meta Title</th>
                          <th>Created By</th>
                          <th>Updated By</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($media)
                            @php $no=1 @endphp
                            @foreach($media as $data)
                            <tr>
                              <th scope="row">{{$no++}}</th>
                              <td>{{ $data->title }}</td>
                              <td>{{ str_limit(strip_tags($data->description),50)}}</td>
                              <td>{{ $data->link}}</td>
                              <td>{{ $data->is_active == 1 ? 'Active' : 'Not Active' }}</td>
                              <td>{{ $data->meta_title}}</td>
                                @php
                              		$cid = Auth::user()->find($data->created_by);
                              		$uid = Auth::user()->find($data->updated_by);
                            	@endphp
                              <td>{{ isset($cid->name)? $cid->name:"" }}</a></td>
                              <td>{{ isset($uid->name)? $uid->name:"" }}</a></td>
                              <td><a href="{{ route('media.edit', $data->id) }}"><i class="fa fa-edit"></i></a></td>
                              <td>
                                <a class="delete" data-id="{{ $data->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$data->id}}" method="post" action="{{ route('media.destroy', $data->id) }}">
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
<script>
$(document).ready(function() {
    $('#media').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>

@endsection
