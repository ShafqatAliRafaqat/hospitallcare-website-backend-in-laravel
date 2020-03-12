@extends('adminpanel.layout')
@section('title', 'Status')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Status <a href="{{ route('status.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
                  </div>
                  <div class="card-body table-responsive">
                  <div class="mb-2">
                    <a class="toggle-vis btn btn-success btn-sm text-color" data-column="1">Status Name</a> - 
                    <a class="toggle-vis btn btn-success btn-sm text-color" data-column="2">Active</a> 
                  </div>
                    <table class="table table-striped table-sm card-text" id="status">
                      <thead class="thead-light">
                        <tr>
                          <th>#</th>
                          <th>Status Name</th>
                          <th>Active</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if(count($status) > 0 )
                            @php $no=1 @endphp
                            @foreach($status as $s)
                            <tr>
                              <th scope="row">{{$no++}}</th>
                              <td>{{ $s->name }}</td>
                              <td>{{ $s->active == 1 ? 'Active' : 'Not Active' }}</td>
                              <td><a href="{{ route('status.edit', $s->id) }}"><i class="fa fa-edit"></i></a></td>
                              <td>
                                <a class="delete" data-id="{{ $s->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$s->id}}" method="post" action="{{ route('status.destroy', $s->id) }}">
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
        text: "You want to delete Status!",
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
        swal("Deleted!", "Status has been deleted.", "success");
    }, 2000);
        });
});
</script>
<script>
$(document).ready(function() {
    // $('#status').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
$(document).ready(function() {
    var table = $('#status').DataTable( {} );
 
    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = table.column( $(this).attr('data-column') );

        if(column.visible() == true){
          $(this).addClass('btn-danger');
          $(this).removeClass('btn-success');
        }else{
          $(this).removeClass('btn-danger');
          $(this).addClass('btn-success');
        }


        // Toggle the visibility
        column.visible( ! column.visible() );
    });
});
</script>
@endsection
