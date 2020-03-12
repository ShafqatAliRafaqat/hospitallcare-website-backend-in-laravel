@extends('adminpanel.layout')
@section('title', 'Leads | Patients')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')

            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Leads From {{ $info }}
                      <a href="{{ route('customers.create') }}" class="btn btn-sm btn-dark float-right">Create New</a>
                    </h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="customers">
                      <thead class="thead-light">
                        <tr>
                          <th>#</th>
                          <th>Customer Name</th>
                          <th>User ID</th>
                          <th>Treatment</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Address</th>
                          <th>Created On</th>
                          <th>Edit</th>
                          <th>Delete</th>
                          <!-- <th>Status</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        @if($temp_customers)
                            @php $no=1 @endphp
                            @foreach($temp_customers as $c)

                            <tr id='user_id_{{ $c->id }}'>
                              <th scope="row">{{$no++}}</th>
                              <td>{{ $c['name']}}</td>
                              <td>{{$c['employee_code']}}</td>
                              <td>{{$c['treatment']}}</td>
                              <td>{{$c['email']}}</td>
                              <td>{{$c['phone']}}</td>
                              <td>{{$c['address']}}</td>
                              <td>{{ isset($c['created_at']) ? date('d-m-Y h:i A',strtotime($c['created_at'])) : '' }}</td>
                              <td>
                                <center><a href="{{route('edit_leads',$c->id)}}"><i class="fa fa-edit" style="padding-left:10"></i></a></center>
                              </td>
                              <td>
                               <div>
                              <a href="javascript:void(0)" class="delete" id="delete-customer" data-id="{{ $c->id }}" href="#"><i class="fa fa-trash"></i></a>
                              </div>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id = $(this).data('id');
    swal({
        title: "Are you sure?",
        text: "You want to delete lead!",
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
            url:"{{ route('destroy-leads') }}",
            data: { id : id},

            success: function () {
              setTimeout(function () {
                  swal("Deleted!", "Customer lead has been deleted.", "success");
                }, 2000);
                $("#user_id_" + id).remove();
            },
        })
        }
        );
});
</script>
<script>
$(document).ready(function() {
    $('#customers').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
@endsection
