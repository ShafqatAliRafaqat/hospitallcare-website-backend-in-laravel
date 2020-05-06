@extends('adminpanel.layout')
@section('title', 'CareALL Pass Categories')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">CareALL Pass Categories <a href="{{ route('pass_categories.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
                  </div>
                  <div class="card-body table-responsive">
                  <div class="mb-2">
                    <a class="toggle-vis btn btn-success btn-sm text-color" data-column="1">Category Name</a> -
                    <a class="toggle-vis btn btn-success btn-sm text-color" data-column="2">Amount</a> -
                    <a class="toggle-vis btn btn-success btn-sm text-color" data-column="3">Treatments</a> -
                    <a class="toggle-vis btn btn-success btn-sm text-color" data-column="4">Diagnostics</a> -
                    <a class="toggle-vis btn btn-success btn-sm text-color" data-column="5">Active</a>
                  </div>
                    <table class="table table-striped table-sm card-text" id="status">
                      <thead class="thead-light">
                        <tr>
                          <th>#</th>
                          <th>Category Name</th>
                          <th>Amount</th>
                          <th>Treatments</th>
                          <th>Diagnostics</th>
                          <th>Active</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if(count($categories) > 0 )
                            @php $no=1 @endphp
                            @foreach($categories as $c)
                            <tr>
                              <th scope="row">{{$no++}}</th>
                              <td>{{ $c->name }}</td>
                              <td>{{ $c->amount }}</td>
                              <td>{{ $c->treatments }}</td>
                              <td>{{ $c->diagnostics }}</td>
                              <td>{{ $c->is_active == 1 ? 'Active' : 'Not Active' }}</td>
                              <td><a href="{{ route('pass_categories.edit', $c->id) }}"><i class="fa fa-edit"></i></a></td>
                              <td>
                                <a class="delete" data-id="{{ $c->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$c->id}}" method="post" action="{{ route('pass_categories.destroy', $c->id) }}">
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
        text: "You want to delete Category!",
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
        swal("Deleted!", "Category has been deleted.", "success");
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
