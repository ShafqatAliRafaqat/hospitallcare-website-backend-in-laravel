@extends('adminpanel.layout')
@section('title', 'CareALL Pass Deleted')
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
                    <table class="table table-striped table-sm card-text" id="status">
                      <thead class="thead-light">
                        <tr>
                          <th>#</th>
                          <th>Category Name</th>
                          <th>Amount</th>
                          <th>Treatments</th>
                          <th>Diagnostics</th>
                          <th>Active</th>
                          <th>Restore</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if(count($category) > 0 )
                            @php $no=1 @endphp
                            @foreach($category as $c)
                            <tr>
                              <th scope="row">{{$no++}}</th>
                              <td>{{ $c->name }}</td>
                              <td>{{ $c->amount }}</td>
                              <td>{{ $c->treatments }}</td>
                              <td>{{ $c->diagnostics }}</td>
                              <td>{{ $c->is_active == 1 ? 'Active' : 'Not Active' }}</td>
                              <td>
                                <a class="restore" data-id="{{ $c->id }}" data-toggle="tooltip" title="Restore" href="#"><i class="fa fa-undo"></i></a>
                                <form id="restoreForm{{$c->id}}" method="post" action="{{ route('pass_categories_restore', $c->id) }}">
                                  @csrf @method('post')
                                </form>
                              </td>
                              <td>
                                <a class="delete" data-id="{{ $c->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$c->id}}" method="post" action="{{ route('pass_categories_per_delete', $c->id) }}">
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
        text: "You won't be able to revert this!",
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
    $('#status').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
@endsection
