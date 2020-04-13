@extends('orgpanel.layout')
@section('title', 'Employees | Patients')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                  <?php  
                  if(isset($title)){
                    $title = $title;
                  }else{
                    $title ='All';
                  }
                  ?>
                    <h6 class="text-uppercase mb-0">{{$title}} Employees
                      <a href="{{ route('employees.create') }}" class="btn btn-sm btn-dark float-right">Create New</a>
                    </h6>
                  </div>
                  <div class="columns_list mt-4"> -
                      <a class="toggle-vis clr-red " data-column="1">Patient Owner</a> -
                      <a class="toggle-vis clr-green " data-column="2">Employee Name</a> -
                      <a class="toggle-vis clr-green " data-column="3">Employee Code</a> -
                      <a class="toggle-vis clr-green " data-column="4">Phone Number</a> -
                      <a class="toggle-vis clr-green " data-column="5">Email</a> -
                      <a class="toggle-vis clr-red " data-column="6">Procedure</a> -
                      <a class="toggle-vis clr-red " data-column="7">Center</a> -
                      <a class="toggle-vis clr-red " data-column="8">Last Contacted Date</a> -
                      <a class="toggle-vis clr-red " data-column="9">Follow Up Date</a> -
@if($title == 'Pending')<a class="toggle-vis clr-green " data-column="10">Approval/Reject</a> - @endif
                      <a class="toggle-vis clr-green " data-column="11">Action</a>
                    </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped  card-text" id="customers">
                      <thead>
                        <tr class="text-center">
                          <th>#</th>
                          <th>Patient Owner</th>
                          <th>Employee Name</th>
                          <th>Employee Code</th>
                          <th>Phone</th>
                          <th>email</th>
                          <th>Procedure</th>

                          <th>Center</th>
                          <th>Last Contacted Date</th>
                          <th>Follow Up Date</th>
@if($title == 'Pending')  <th>Approval/Reject</th> @endif
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody class="text-center">
                        @if($employees)
                            @php $no=1 @endphp
                            @foreach($employees as $c)

                            <tr id="pending_customer_{{$c->id}}">
                              <th scope="row">{{$no++}}</th>
                              @php
                              $id = Auth::user()->find($c->patient_coordinator_id);
                              @endphp
                              <td>{{ isset($id->name)? $id->name:"" }}</a></td>
                              <td>{{ $c->name }}</td>
                              <td>{{$c->employee_code}}</td>
                              <td>{{$c->phone}}</td>
                              <td>{{$c->email}}</td>
                              <td>
                                @php $med_treat = indexTreatmentsCenters($c->id);@endphp
                                {{ $med_treat->treatment}}
                              </td>
                              <td>
                                @php $med_center = indexTreatmentsCenters($c->id);@endphp
                                {{ $med_center->center_name}}
                              </td>
                              <td>{{ date('d-m-Y',strtotime($c->updated_at)) }}</td>
                              <?php
                              $date_facturation = \Carbon\Carbon::parse($c->next_contact_date. ' +1 day');
                              $date_facturation2 = \Carbon\Carbon::parse($c->next_contact_date);
                              $today = \Carbon\Carbon::today();
                              ?>
                              {{-- <!-- {{ $date_facturation->isPast() ? 'row-red' : '' || $date_facturation2 == $today ? 'row-green' : ''}} --> --}}
                              <td class="
                              <?php if($date_facturation->isPast()){ ?>
                              row-red
                              <?php }else if($date_facturation2 == $today){?>
                              row-green
                              <?php }?>
                              ">

                            @if($c->next_contact_date==NULL)
                            Not Updated
                            @else
                            {{ date('d-m-Y',strtotime($c->next_contact_date)) }}

                          @endif

                              </td>
                              @if($title == 'Pending')
                                <td>
                                  <div>
                                    <a id="approved" data-id="{{ $c->id }}" href="#"  title="Approve Employee Request"><i class="fa fa-check mr-3" aria-hidden="true"></i></a>
                                    <a id="rejected" data-id="{{ $c->id }}" href="#"  title="Reject Employee Request" ><i class="fa fa-ban mr-2" aria-hidden="true" style="color:red"></i></a>
                                  </div>
                                </td>
                              @endif
                              <td><a href="{{ route('employees.show', $c->id) }}"><i class="fa fa-eye pr-1"></i></a>
                                <a href="{{ route('employees.edit', $c->id) }}"><i class="fa fa-edit pr-1"></i></a>
                                <a class="delete" data-id="{{ $c->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$c->id}}" method="post" action="{{ route('employees.destroy', $c->id) }}">
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
                swal("Approved!", "Employee Pending Request has been Accepted", "success");
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
                swal("Rejected!", "Employee pending Request has been Rejected", "success");
                }, 2000);
                $("#pending_customer_" + id).remove();
            },
        })
        }
        );
});
</script>
<script>
$(document).ready(function() {
    var table = $('#customers').DataTable( {
      "lengthMenu": [15],
      "pageLength": 15,
      "paging": true
    } );
    //Removing some Columns on Load
    table.columns( [1,6,7,8,9] ).visible( false );
    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
        // Get the column API object
        var column = table.column( $(this).attr('data-column') );
        console.log("column",$(this));
        if(column.visible() == true){
          $(this).addClass('clr-red');
          $(this).removeClass('clr-green');
          // $(this).removeAttr('checked');
        } else{
          $(this).removeClass('clr-red');
          $(this).addClass('clr-green');
          // $(this).prop('checked', true);
        }
        // Toggle the visibility
        column.visible( ! column.visible() );
        table.columns.adjust().draw( false );
    });
    setTimeout(function() {
      $('.alert').fadeOut('slow');
  }, 2000);
});
</script>
@endsection
