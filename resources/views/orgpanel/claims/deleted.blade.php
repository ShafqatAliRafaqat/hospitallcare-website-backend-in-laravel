@extends('orgpanel.layout')
@section('title', 'Employee | Claims')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Deleted Medical Claims </h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="customers">
                      <thead class="thead-light">
                        <tr>
                          <th class="text-center">#</th>
                          <th class="text-center">Employee Name</th>
                          <th class="text-center">Employee Phone</th>
                          <th class="text-center">Appointment For</th>
                          <th class="text-center">Relation</th>
                          <th class="text-center">Title</th>
                          <th class="text-center">Status</th>
                          <th class="text-center">Restore</th>
                          <th class="text-center">Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($claims)
                            @php $no=1; @endphp
                            @foreach($claims as $c)
                            <tr>
                              <th class="text-center" scope="row">{{$no++}}</th>
                              <td class="text-center">{{ $c->employee_name }}</td>
                              <td class="text-center">{{ $c->employee_phone }}</td>
                              @php if($c->employee_id == $c->d_id){
                              @endphp
                              <td class="text-center">Self</td>
                              <td class="text-center"> - </td>
                              @php } else { @endphp
                              <td class="text-center">{{ $c->d_name }}</td>
                              <td class="text-center">{{ $c->relation }}</td>
                              @php } @endphp
                              <td class="text-center">{{ $c->title }}</td>
                              @php $status_name     = claimStatusName($c->status); @endphp
                              <td class="text-center">{{ $status_name }}</td>
                              <td class="text-center">
                                <a type="button" class="restore a-hover" data-id="{{ $c->claim_id }}">
                                  <img src="{{ asset('backend/web_imgs/refresh.png') }}" width="24px">
                                </a>
                                <form id="restoreForm{{$c->claim_id}}" method="post" action="{{ route('restore_claim', $c->claim_id) }}">
                                    @csrf @method('post')
                                </form>
                              </td>
                              <td class="text-center">
                                  <a type="button" class="delete a-hover" data-id="{{ $c->claim_id }}">
                                    <img src="{{ asset('backend/web_imgs/trash1.png') }}">
                                  </a>
                                  <form id="deleteForm{{$c->claim_id}}" method="post" action="{{ route('force_delete_claim', $c->claim_id) }}">
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
<script>
$(document).ready(function() {
    $('#customers').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});

</script>
<script src="{{ asset('backend/js/sweetalert/sweetalert.js') }}"></script>
<script>
$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    // alert(id);
    swal({
        title: "Are you sure?",
        text: "You want to delete Employee data!",
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
        swal("Deleted!", "Employee has been deleted.", "success");
    }, 2000);
        });
});
</script>
<script>
$(document).on('click', '.restore', function(){
    var id = $(this).data('id');
    // alert(id);
    swal({
        title: "Are you sure?",
        text: "You want to Restore this Claim?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        confirmButtonText: "Yes, Restore it!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
        },
        function(){
            setTimeout(function () {
        $('#restoreForm'+id).submit();
        swal("Restored!", "Claim has been Restored.", "success");
    }, 2000);
        });
});
</script>
@endsection
