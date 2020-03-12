@extends('adminpanel.layout')
@section('content')
<div class="container-fluid px-xl-5">
   <section class="py-5">
     <div class="row">
      @include('adminpanel.notification')
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <h6 class="text-uppercase mb-0">WebSite Users</h6>
          </div>
          <div class="card-body table-responsive">
              <table class="table table-striped table-sm card-text" id="customer_users">
                <thead class="thead-light">
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Lead From</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
              @if($users)
              @php $no=1 @endphp
              @foreach($users as $user)
              <tr>
                <th scope="row">{{$no++}}</th>
                <td><a href="{{ route('customers.show', $user->customer_id) }}">{{ $user->name }}</a></td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ (isset($user->Role[0]->name))? $user->Role[0]->name:"" }}</td>
                <td> 
                    @if(isset($user->Customer->customer_lead))
                        @if($user->Customer->customer_lead == 2)
                            Website
                        @elseif($user->Customer->customer_lead == 3 )
                            Customer App
                        @endif
                    @endif
                </td>
                <td><a href="{{ route('customers.edit', $user->customer_id) }}"><i class="fa fa-edit mr-2"></i></a>
                    <a class="delete" data-id="{{ $user->customer_id }}" href="#"><i class="fa fa-trash"></i></a>
                    <form id="deleteForm{{$user->customer_id}}" method="post" action="{{ route('customers.destroy', $user->customer_id) }}">
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
        $('#deleteForm'+id).submit();
        swal("Deleted!", "Customer has been deleted.", "success");
    }, 2000);
        });
});
</script>
<script>
    $(document).ready(function() {
        $('#customer_users').DataTable();
        setTimeout(function() {
         $('.alert').fadeOut('slow');
     }, 2000);
    });
</script>
@endsection
