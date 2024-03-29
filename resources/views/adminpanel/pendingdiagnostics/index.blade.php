@extends('adminpanel.layout')
@section('title','Pending Appointments | HospitALL')
<!-- @section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css">
@endsection -->
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
                <a class="toggle-vis clr-green mb-2" data-column="1">Customer Name</a> -
                <a class="toggle-vis clr-green mb-2" data-column="2">Phone Number</a> -
                <a class="toggle-vis clr-red mb-2" data-column="3">Diagnostics</a> -
                <a class="toggle-vis clr-green mb-2" data-column="4">Lab</a> -
                <a class="toggle-vis clr-red mb-2" data-column="5">Source</a> -
                <a class="toggle-vis clr-green mb-2" data-column="6">Appointment Date</a> -
                <a class="toggle-vis clr-green mb-2" data-column="9">Created On</a> -
                <a class="toggle-vis clr-green mb-2" data-column="10">Edit</a> -
                <a class="toggle-vis clr-green mb-2" data-column="11">Approve</a> -
                <a class="toggle-vis clr-green mb-2" data-column="12">Cancel</a>
              </div>
              <div class="row mt-2 mb-2 " hidden ="true" id="multiselectoraction">
                  <div class="col-12 text-center">
                    <button class="btn btn-danger" id="button">Cancel All</button>
                    <button class="btn btn-success">Approve All</button>
                  </div>
                </div>
                <table class="table table-striped table-sm card-text" id="labs">
                  <thead class="thead-light">
                    <tr>
                      <th>#</th>
                      <th>Customer Name</th>
                      <th>Phone</th>
                      <th>Diagnostics</th>
                      <th>Lab</th>
                      <th>Source</th>
                      <th>Appointment Date</th>
                      <th>Created On</th>
                      <th>Edit</th>
                      <th>Approve</th>
                      <th>Cancel</th>
                    </tr>
                  </thead>
                      <tbody>
                        @if($customers)
                            @php $no=1 @endphp
                            @foreach($customers as $c)

                            <tr>
                              <th id="trtd" data-id="{{$c->cd_id}}" scope="row">{{$no++}}</th>
                              <td><a href="{{ route('customers.show', $c->id) }}">{{ $c->name }}</a></td>
                              <td>{{ $c->phone }}</td>
                              <td>
                                @php
                                $diagnostic_name      =   null;
                                $diagnostic_ids       =   $c->diagnostic_id;
                                $cd_ids               =   $c->cd_id;
                                $cd_id_explode        =   explode(',',$cd_ids);
                                $diagnostics_array    =   explode(',',$diagnostic_ids);
                                foreach($diagnostics_array as $da){
                                  $diagnostic_name[]  =   diagnosticName($da);
                                }
                                $diagnostics_implode    =   implode(',',$diagnostic_name);
                                @endphp
                                {{ $diagnostics_implode }}
                              </td>
                              <td>
                                @php $med_center = labName($c->lab_id);@endphp
                                {{ $med_center}}
                              </td>
                              <td>{{($c->appointment_from == 0) ? 'CareALL' : (($c->appointment_from == 1) ? 'Website' : (($c->appointment_from == 2) ? 'App' :( ($c->appointment_from == 3) ? 'Doctor' : ''))) }}</td>
                              <?php
                              $date_facturation = \Carbon\Carbon::parse($c->appointment_date. ' +1 day');
                              $date_facturation2 = \Carbon\Carbon::parse($c->appointment_date);
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
                              @if($c->appointment_date==NULL)
                                Not Updated
                                @else
                                {{ date('d-m-Y',strtotime($c->appointment_date)) }}
                              @endif
                              </td>
                              <td>{{ date('d-m-Y',strtotime($c->created_at)) }}</td>
                              <td>
                              <di1v style="display: -webkit-box;" >
                              <div style="padding-right: 10px;">
                                <a class="font-18" href="{{ route('customers.edit', $c->id) }}"><i class="fa fa-edit" style="padding-left:10"></i></a>
                              </div>
                              </div>
                              </td>
                              <td>
                                <div>
                                  <a class="approve approve-tick font-18" data-id="{{ $c->cd_id }}" href="#">
                                    <i class="fas fa-check"></i>
                                  </a>
                                </div>
                                  <form id="approveForm{{$cd_id_explode[0]}}" method="POST" action="{{ route('pendingdiagnostics.update',$c->id) }}">
                                    @csrf @method('put')
                                    <input type="hidden" name="diagnostic_ids" value="{{ $cd_ids }}">
                                  </form>
                              </td>
                              <td>
                                <div>
                                  <a class="delete cancel-cross font-18" data-id="{{ $c->cd_id }}" href="#">
                                    <i class="fas fa-times"></i>
                                  </a>
                                </div>
                                <form id="deleteForm{{$cd_id_explode[0]}}" method="POST" action="{{ route('pendingdiagnostics.destroy',$c->cd_id) }}">
                                    @csrf @method('delete')
                                    <input type="hidden" name="diagnostic_ids" value="{{ $cd_ids }}">
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
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script src="{{ asset('backend/js/sweetalert/sweetalert.js') }}"></script>
<script>
$(document).on('click', '.approve', function(){
    var id = $(this).data('id');
    swal({
        title: "Are you sure?",
        text: "You want to Approve this Appointment!",
        type: "warning",
        showCancelButton: true,
        cancelButtonClass: "btn-danger",
        confirmButtonClass : "btn-primary",
        confirmButtonText: "Yes, Approve it!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
        },
        function(){
        setTimeout(function () {
          $('#approveForm'+id).submit();
        swal("Approved!", "Approved Successfully!.", "success");
    }, 2000);
        });
});
</script>
<script>
$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    swal({
        title: "Are you sure?",
        text: "You want to delete diagnostics !",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-primary",
        cancelButtonClass : "btn-danger",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
        },
        function(){
            setTimeout(function () {
        $('#deleteForm'+id).submit();
        swal("Deleted!", "Diagnostics has been deleted.", "success");
    }, 2000);
        });
});
</script>
<script>
$(document).ready(function() {
  var table =  $('#labs').DataTable({});
  table.columns( [3,5] ).visible(false); //Column removed on load
  $('a.toggle-vis').on('click', function(e){
    //stops the system to do the default process. i.e, stops anchor tag to go on link href
    e.preventDefault();
    var column  = table.column($(this).attr('data-column'));
    if (column.visible() == true) {
      $(this).addClass('clr-red');
      $(this).removeClass('clr-green');
    } else {
      $(this).removeClass('clr-red');
      $(this).addClass('clr-green');
    }
  column.visible( ! column.visible());
  });
    setTimeout(function() {
      $('.alert').fadeOut('slow');
  }, 2000);
});
</script>
@endsection
