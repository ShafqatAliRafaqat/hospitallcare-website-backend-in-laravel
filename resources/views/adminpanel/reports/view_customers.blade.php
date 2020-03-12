@extends('adminpanel.layout')
@section('title', 'Customers | Patients')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row pt-2">
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Customers <a href="{{ route('customers.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
                    <br>
                    <p>{{ $info }}</p>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="customers">
                      <thead class="thead-light">
                        <tr>
                          <th>#</th>
                          <th>Patient Owner</th>
<!--                           <th>Customer ID</th> -->
                          <th>Customer Name</th>
                          <th>Procedure</th>

                          <th>Center</th>
                          <th>Cost</th>
                          <th>Status</th>
                          <th>Last Contacted Date</th>
                          <th>Follow Up Date</th>
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
                              <td>
                                @foreach($c->treatments as $treatment)
                                {{ $treatment->name}},
                                @endforeach
                              </td>
                            <td>
                                @foreach($c->center as $center)
                                {{ $center->center_name}},
                                @endforeach
                              </td>
                            <td>@foreach($c->treatments as $treatment)
                                {{ $treatment->pivot->cost }},
                                @endforeach
                            </td>
                              @php $status_name = statusName($c->status_id); @endphp
                            <td class="{{$status_name == 'Customer' ? 'row-yellow' : ''}}">{{ $status_name }}</td>
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
                              <td><a href="{{ route('customers.edit', $c->id) }}"><i class="fa fa-edit pr-2"></i></a>
                                <a class="delete" data-id="{{ $c->id }}" href="#"><i class="fa fa-trash"></i></a>
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
        text: "You want to delete Customer!",
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
        swal("Deleted!", "Procedure has been deleted.", "success");
    }, 2000);
        });
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
