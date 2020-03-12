@extends('adminpanel.layout')
@section('title', 'Organizations | Patients')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Organization <a href="{{ route('organization.edit', $organizations->id) }}" class="btn btn-sm btn-dark float-right">Edit Organization</a></h6><br>
                  </div>
                  <div class="card-body table-responsive">

                      <div class="row">
                        <div class="col-md-6">
                          <ul>
                            <li><b>Organization Name:</b> {{ $organizations->name }}</li>
                            <li><b>Organization Phone:</b> {{ $organizations->phone }}</li>
                            <li><b>Organization Address: </b> {{ $organizations->address }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                                <ul>
                                  <li><b>Number of Users:</b> {{ count($users) }}</li>
                                  <li><b>Number of Active Employess:</b> {{ count($active_employees) }}</li>
                                  <li><b>Number of Pending Employess:</b> {{ count($pending_employees) }}</li>
                                  </ul>
                              </div>
                      </div>

                      <!-- Treaments -->
                  </div>
                </div>
            </div>
        </div>
            <hr>
            <ul class="nav nav-tabs customer-nav" role="tablist">
              <li class="nav-item">
                <a class="nav-link" href="#users-tab" role="tab" data-toggle="tab">Users</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#active-tab" role="tab" data-toggle="tab">Active Employees</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#pending-tab" role="tab" data-toggle="tab">Pending Employees</a>
              </li>
          </ul>
          <hr>
          <div class="tab-content">
              <div role="tabpanel" class="tab-pane pt-3 in active" id="users-tab">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-header">
                        <h6 class="text-uppercase mb-0">Users<a href="{{ route('users.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
                      </div>
                      <div class="card-body table-responsive">
                        <table class="table table-striped table-sm card-text" id="treatment">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Role</th>
                              <th>Organization</th>
                              <th>Edit</th>
                              <th>Delete</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($users)
                              @php $no=1 @endphp
                              @foreach($users as $user)
                                <tr>
                                  <th scope="row">{{$no++}}</th>
                                  <td>{{ $user->name }}</td>
                                  <td>{{ $user->email }}</td>
                                  <td>{{ (isset($user->Role[0]))?$user->Role[0]->name:"" }}</td>
                                  <td>{{ $organizations->name }}</td>
                                  <td><a href="{{ route('users.edit', $user->id) }}"><i class="fa fa-edit"></i></a></td>
                                  <td>
                                    <a id="deleteUser" data-id ="{{$user->id}}" href="#"><i class="fa fa-trash"></i></a>
                                    <form id="deleteUserForms{{$user->id}}" method="post" action="{{ route('users.destroy', $user->id) }}">
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
              </div>
                <div role="tabpanel" class="tab-pane pt-3 in fade" id="active-tab">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="card">
                        <div class="card-header">
                          <h6 class="text-uppercase mb-0">Active Organization Employees
                            <a href="{{ route('customers.create') }}" class="btn btn-sm btn-dark float-right">Create New</a>
                          </h6>
                        </div>
                        <div class="card-body table-responsive">
                          <table class="table table-striped table-sm card-text" id="active_employees">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Employee Code</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                              @if($active_employees)
                                  @php $no=1 @endphp
                                  @foreach($active_employees as $c)

                                  <tr>
                                    <th scope="row">{{$no++}}</th>

                                    <td>{{ $c->name }}</td>
                                    <td>{{ $c->email }}</td>
                                    <td>{{ $c->employee_code }}</td>
                                    <td>{{ $c->phone }}</td>
                                    <td>{{ $c->address }}</td>
                                    <td class="text-center" >
                                      <div style="display: flex">
                                        <a href="{{ route('customers.show', $c->id) }}"  title="Customer Details"><i class="fa fa-eye mr-2"></i></a>
                                        <a href="{{ route('customers.edit', $c->id) }}"  title="Edit Customer"><i class="fa fa-edit mr-2"></i></a>
                                        <a class="delete" data-id="{{ $c->id }}" href="#"  title="Delete Customer"><i class="fa fa-trash"></i></a>
                                        <form id="deleteForms{{$c->id}}" method="post" action="{{ route('customers.destroy', $c->id) }}">
                                            @csrf @method('delete')
                                        </form>
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
                </div>
                <div role="tabpanel" class="tab-pane pt-3 in fade" id="pending-tab">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="card">
                        <div class="card-header">
                          <h6 class="text-uppercase mb-0">Pending Organization Employees
                            <a href="{{ route('customers.create') }}" class="btn btn-sm btn-dark float-right">Create New</a>
                          </h6>
                        </div>
                        <div class="card-body table-responsive">
                          <table class="table table-striped table-sm card-text" id="pending_employees">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Employee Code</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th class="text-center">Approve/Reject</th>
                                <th>Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                              @if($pending_employees)
                                  @php $no=1 @endphp
                                  @foreach($pending_employees as $c)

                                  <tr id="pending_customer_{{$c->id}}">
                                    <th scope="row">{{$no++}}</th>

                                    <td>{{ $c->name }}</td>
                                    <td>{{ $c->email }}</td>
                                    <td>{{ $c->employee_code }}</td>
                                    <td>{{ $c->phone }}</td>
                                    <td>{{ $c->address }}</td>
                                    <td class="text-center" >
                                      <div class="text-center">
                                        <a id="approved" data-id="{{ $c->id }}" href="#"  title="Approve Employee Request"><i class="fa fa-check mr-3" aria-hidden="true"></i></a>
                                        <a id="rejected" data-id="{{ $c->id }}" href="#"  title="Reject Employee Request" ><i class="fa fa-ban mr-2" aria-hidden="true" style="color:red"></i></a>
                                        </div>
                                    </td>
                                    <td class="text-center" >
                                      <div style="display: flex">
                                        <a href="{{ route('customers.show', $c->id) }}"  title="Customer Details"><i class="fa fa-eye mr-2"></i></a>
                                        <a href="{{ route('customers.edit', $c->id) }}"  title="Edit Customer"><i class="fa fa-edit mr-2"></i></a>
                                        <a class="delete" data-id="{{ $c->id }}" href="#"  title="Delete Customer"><i class="fa fa-trash"></i></a>
                                        <form id="deleteForms{{$c->id}}" method="post" action="{{ route('customers.destroy', $c->id) }}">
                                            @csrf @method('delete')
                                        </form>
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
              </div>
          </div>

    </section>
</div>
@endsection
@section('scripts')
<script src="{{ asset('backend/js/sweetalert/sweetalert.js') }}"></script>
<script>
$(document).on('click', '#approved', function(){
    var id = $(this).data('id');
    console.log(id);
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
    swal({
        title: "Are you sure?",
        text: "You want to Approve Request!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-primary",
        confirmButtonText: "Yes, Approve it!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
        },
        function (isConfirm) {
        if (!isConfirm) return;
          $.ajax({
 
            type: "POST",
            url:"{{ route('approve_pending_customer') }}",
            data: { id : id},
            success: function () {
              setTimeout(function () {
                swal("Approved!", "Customer Pending Request has been Accepted", "success");
                }, 2000);
                $("#pending_customer_" + id).remove();
            },
        })
        }
        );
});
</script>
<script>
$(document).on('click', '#rejected', function(){
    var id = $(this).data('id');
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
    swal({
        title: "Are you sure?",
        text: "You want to Delete Request!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, Delete it!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
        },
        function (isConfirm) {
        if (!isConfirm) return;
          $.ajax({
 
            type: "POST",
            url:"{{ route('reject_pending_customer') }}",
            data: { id : id},
            success: function () {
              setTimeout(function () {
                swal("Rejected!", "Customer pending Request has been Rejected", "success");
                }, 2000);
                $("#pending_customer_" + id).remove();
            },
        })
        }
        );
});
</script>
<script>
$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    swal({
        title: "Are you sure?",
        text: "You want to delete customer data!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
        },
        function(){
            setTimeout(function () {
        $('#deleteForms'+id).submit();
        swal("Deleted!", "Customer has been deleted.", "success");
    }, 2000);
        });
});
</script>
<script>
$(document).on('click', '#deleteUser', function(){
    var id = $(this).data('id');
    swal({
        title: "Are you sure?",
        text: "You want to delete User data!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
        },
        function(){
            setTimeout(function () {
        $('#deleteUserForms'+id).submit();
        swal("Deleted!", "User has been deleted.", "success");
    }, 2000);
        });
});
</script>
<script>
$(document).ready(function() {
    $('#active_employees').DataTable();
    setTimeout(function() {
	    $('.alert').fadeOut('slow');
	}, 2000);
});
$(document).ready(function() {
    $('#pending_employees').DataTable();
    setTimeout(function() {
	    $('.alert').fadeOut('slow');
	}, 2000);
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
