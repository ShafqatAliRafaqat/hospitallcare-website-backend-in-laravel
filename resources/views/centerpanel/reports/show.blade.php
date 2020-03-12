@extends('centerpanel.layout')
@section('title', 'Clients | Patients')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
      <!-- Report Generation on Screen -->
        <div class="form-group pt-3">
        <h5 class="pb-2">Report Generation</h5>
        <form class="form-horizontal" method="post" action="{{ route('clientsreport') }}">
        @csrf
              <div class="form-group row">
                  <label class="col-md-2 form-control-label">Starting Date</label>
                  <div class="col-md-4">
                        <input type="date" id="startdate" name="start_date" placeholder="Start Date"
                        class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}" value="{{ $start }}" required>

                      @if($errors->has('start_date'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('start_date') }}</div>
                      @endif
                  </div>
                <label class="col-md-2 form-control-label">Ending Date</label>
                  <div class="col-md-4">
                        <input type="date" id="enddate" name="end_date" placeholder="End Date"
                        class="form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}" value="{{ $ending }}" required>

                      @if($errors->has('end_date'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('end_date') }}</div>
                      @endif
                  </div>
                </div>
            <div class="row">
              <div class="col-md-2 form-control-label">Select Status</div>
              <div class="col-md-4" >
                <select name="status_id" class="form-control ">
                  <option value="">Select Status</option>
                  @foreach($status_db as $s)
                      <option value="{{ $s->id }}">
                        @if($s->id == '1')
                        Informed
                        @elseif($s->id == '2')
                        Booked Appointment
                        @elseif($s->id == '3')
                        Got Appointment
                        @elseif($s->id == '4')
                        Got Treatment
                        @endif
                    </option>
                    @endforeach
                </select>
                  @if($errors->has('status_id'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('status_id') }}</div>
                  @endif
              </div>

                <div class="col-md-4 offset-2 text-right" >
                <button type="submit" style="width: 100%;" class="btn btn-primary">Generate</button>
              </div>
            </div>
          </form>
        </div>
        <div class="row pt-2">
          <div class="col-md-12">
          <p>Starting From {{$start}} to {{$ending}}</p>

            <p>
              <strong>Status:</strong>
              @if(isset($status_id))
                @if($status_id == '1')
                  Informed
                  @elseif($status_id == '2')
                  Booked Appointment
                  @elseif($status_id == '3')
                  Got Appointment
                  @elseif($status_id == '4')
                  Got Treatment
                @endif
                @else
                Not Selected
                @endif
            </p>

            <p></p>
          </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Clients</h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="customers">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Patient Owner</th>
                          <th>Customer Name</th>
                          <th>Procedure</th>
<!--                           <th>Center</th> -->
                          <th>Appointment Date</th>
                          <th>Last Contacted Date</th>
                          <th>FollowUp Date</th>
                          <!-- <th>Action</th> -->
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
                              <td> {{ $c->name }}</td>
<!--                               <td>
                                @php $med_treat = indexTreatmentsCenters($c->id);@endphp
                                {{ $med_treat->treatment}}
                              </td> -->
                              <td>
                                @php $med_center = TreatmentName($c->pivot->treatments_id);@endphp
                                {{ $med_center }}
                              </td>
                              <td>{{ isset($c->pivot->appointment_date)?$c->pivot->appointment_date:"" }}</td>
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
                              {{ date('d-m-Y',strtotime($c->next_contact_date)) }}
                              </td>
<!--                               <td>
                              <div style="display: -webkit-box;" >
                              <div style="padding-right: 10px;">
                                  <a href="{{ route('customers.edit', $c->id) }}"><i class="fa fa-edit" style="padding-left:10"></i></a>
                              </div>
                              <div>
                              <a class="delete" data-id="{{ $c->id }}" href="#"><i class="fa fa-trash"></i></a>
                              </div>
                              </div>
                              <form id="deleteForm{{$c->id}}" method="post" action="{{ route('customers.destroy', $c->id) }}">
                                    @csrf @method('delete')
                                </form>
                              </td> -->
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

$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    if ( confirm('Are you sure to delete?') ){
      $('#deleteForm'+id).submit();
    }else {

    }
});
</script>
@endsection
