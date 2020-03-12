@extends('adminpanel.layout')
@section('title', 'organizations | Patients')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Organizations
                    <a href="{{ route('organization.create') }}" class="btn btn-sm btn-dark float-right">Create New</a>
                    </h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="organizations">
                      <thead class="thead-light">
                        <tr>
                          <th class ="text-center">#</th>
                          <th class ="text-center">Name</th>
                          <th class ="text-center">Phone</th>
                          <th class ="text-center">Address</th>
                          <th class ="text-center">Details</th>
                          <th class ="text-center">Edit</th>
                          <th class ="text-center">Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                      @if($organization)
                            @php $no=1 @endphp
                            @foreach($organization as $org)
                            <tr>
                              <th class ="text-center" scope="row">{{$no++}}</th>
                              <td ><a href="{{ route('organization.show', $org->id) }}">{{ $org->name  }}</a></a></td>
                              <td >{{ $org->phone  }}</a></td>
                              <td >{{ $org->address  }}</a></td>
                              <td class ="text-center"><a href="{{ route('organization.show', $org->id) }}"><i class="fa fa-eye"></i></a></td>
                              <td class ="text-center"><a href="{{ route('organization.edit', $org->id) }}"><i class="fa fa-edit"></i></a></td>
                              <td class ="text-center">
                                <a class="delete" data-id="{{ $org->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$org->id}}" method="post" action="{{ route('organization.destroy', $org->id) }}">
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
        text: "You want to delete organization!",
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
        swal("Deleted!", "Organization has been deleted.", "success");
    }, 2000);
        });
});
</script>
<script>
$(document).ready(function() {
    $('#organizations').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
@endsection
