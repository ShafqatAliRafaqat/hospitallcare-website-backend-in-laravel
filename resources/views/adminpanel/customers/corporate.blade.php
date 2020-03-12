@extends('adminpanel.layout')
@section('title', 'Customers | Patients')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Customers
                      <a href="{{ route('customers.create') }}" class="btn btn-sm btn-dark float-right">Create New</a>
                    </h6>
                  </div>
                  <div class="card-body table-responsive">
                    <div class="columns_list"> -
                      <a class="toggle-vis clr-green mb-2" data-column="1">Patient Owner</a> -
                      <a class="toggle-vis clr-green mb-2" data-column="2">Customer Name</a> -
                      <a class="toggle-vis clr-green mb-2" data-column="3">Phone Number</a> -
                      <a class="toggle-vis clr-green mb-2" data-column="4">Organization</a> -
                      <a class="toggle-vis clr-red mb-2" data-column="5">Employee Code</a> -
                      <a class="toggle-vis clr-red mb-2" data-column="6">Procedure</a> -
                      <a class="toggle-vis clr-green mb-2" data-column="7">Center</a> -
                      <a class="toggle-vis clr-green mb-2" data-column="8">Status</a> -
                      <a class="toggle-vis clr-red mb-2" data-column="9">Created On</a> -
                      <a class="toggle-vis clr-red mb-2" data-column="10">Last Contacted Date</a><br> -
                      <a class="toggle-vis clr-green mb-2" data-column="11">FollowUp Date</a> -
                      <a class="toggle-vis clr-green mb-2" data-column="12">Action</a>
                    </div>
                    <table class="table table-striped table-sm card-text" id="customers">
                      <thead class="thead-light">
                        <tr>
                          <th>#</th>
                          <th>Patient Owner</th>
                          <th>Customer Name</th>
                          <th>Phone Number</th>
                          <th>Organization</th>
                          <th>Employee Code</th>
                          <th>Procedure</th>
                          <th>Center</th>
                          <th>Status</th>
                          <th>Created On</th>
                          <th>Last Contacted Date</th>
                          <th>FollowUp Date</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($customers)
                            @php $no=1 @endphp
                            @foreach($customers as $c)

                            <tr>
                              <th scope="row">{{$no++}}</th>
                              @php
                              $id = Auth::user()->find($c->patient_coordinator_id);
                              @endphp
                              <td>{{ isset($id->name)? $id->name:"" }}</a></td>
                              <td><a href="{{ route('customers.show', $c->id) }}">{{ $c->name }}</a></td>
                              <td>{{ $c->phone }}</td>
                              <td>{{ $c->organization_name }}</td>
                              <td>{{ (isset($c->employee_code))?$c->employee_code:"" }}</td>
                              <td>
                                @php $med_treat = indexTreatmentsCenters($c->id);@endphp
                                {{ $med_treat->treatment}}
                              </td>
                              <td>
                                @php $med_center = indexTreatmentsCenters($c->id);@endphp
                                {{ $med_center->center_name}}
                              </td>
                              <td class="{{$c->status == 'Customer' ? 'row-yellow' : ''}}">{{ $c->status }}</td>
                              <td>{{ date('d-m-Y',strtotime($c->created_at)) }}</td>
                              <td>{{ date('d-m-Y',strtotime($c->updated_at)) }}</td>
                              <?php
                              $date_facturation = \Carbon\Carbon::parse($c->next_contact_date. ' +1 day');
                              $date_facturation2 = \Carbon\Carbon::parse($c->next_contact_date);
                              $today = \Carbon\Carbon::today();
                              ?>
                              <!-- {{ $date_facturation->isPast() ? 'row-red' : '' || $date_facturation2 == $today ? 'row-green' : ''}} -->
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
                              <td>
                              <div style="display: -webkit-box;" >
                              <div style="padding-right: 10px;">
                                  <a href="{{ route('customers.edit', $c->id) }}"><i class="fa fa-edit" style="padding-left:10"></i></a>
                              </div>
                              <div>
                              <a class="delete" data-id="{{ $c->id }}" href="#"><i class="fa fa-trash" style="padding-left:10; color:red;"></i></a>
                              </div>
                              </div>
                              <form id="deleteForm{{$c->id}}" method="post" action="{{ route('customers.destroy', $c->id) }}">
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
    var table = $('#customers').DataTable( {} );
    //Removing some Columns on Load
    table.columns( [5,6,9,10] ).visible( false );

    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
        // Get the column API object
        var column = table.column( $(this).attr('data-column') );
        if(column.visible() == true){
          $(this).addClass('clr-red');
          $(this).removeClass('clr-green');
        } else{
          $(this).removeClass('clr-red');
          $(this).addClass('clr-green');
        }
        // Toggle the visibility
        column.visible( ! column.visible() );
    });
    setTimeout(function() {
      $('.alert').fadeOut('slow');
  }, 2000);
});
</script>
@endsection
