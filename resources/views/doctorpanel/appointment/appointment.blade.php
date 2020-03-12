@extends('doctorpanel.layout')
@section('title', 'Clients | Patients')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('doctorpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0"> {{$message}}
                      <!-- <a href="{{ route('customers.create') }}" class="btn btn-sm btn-dark float-right">Create New</a> -->
                    </h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="customers">
                      <thead class="thead-light">
                        <tr>
                          <th>#</th>
                          <th>Patient Owner</th>
                          <th>Customer Name</th>
                          <th>Procedure</th>
                          <th>Center</th>
                          <!-- <th>Status</th> -->
                          <!-- <th>Cost</th> -->
                          <th>Appointment Date</th>
                          <th>Appointment Time</th>
                          @if($message == "Today Appointments" || $message == "Upcoming Appointments" )
                          <th>Cancel Appointment</th>
                          @endif
                          @if($message != "Upcoming Appointments")
                          <th>Add Appointment</th>
                          @endif
                        </tr>
                      </thead>
                      <tbody>
                        @if($clients)
                        {{-- @dd($clients); --}}
                            @php $no=1; @endphp
                            @foreach($clients as $c)
                            <tr>
                              <th scope="row">{{$no}}</th>
                              @php
                              $id = Auth::user()->find($c->patient_coordinator_id);
                              @endphp
                              <td>{{ (isset($id->name))? $id->name:"" }}</a></td>
                              <td><a href="{{ route('doctorclients.show', $c->id) }}">{{ $c->name }}</a></td>
                              <td>
                                {{ (isset($c->treatment_name))? $c->treatment_name : ""}}
                              </td>
                              <td>
                                {{ (isset($c->center_name))? $c->center_name : ""}}
                              </td>
                              @php
                                if(isset ($c->appointment_date)){
                                  $appointment_date   = Carbon\Carbon::parse($c->appointment_date);
                                  $fdate          =   $appointment_date->format('jS F Y');
                                  $time           =   $appointment_date->format('h:i A');
                                }
                              @endphp
                              <td>{{ (isset ($fdate))? $fdate:"" }}</td>
                              <td>{{ (isset ($time))? $time:"" }}</td>
                              @if($message == "Today Appointments" || $message == "Upcoming Appointments" )
                              <td>
                                <div>
                                    <div class="text-center">
                                      <a type="button" class="delete a-hover" data-id="{{ $c->id }}">
                                        <img src="{{ asset('backend/web_imgs/cross.png') }}">
                                      </a>
                                    </div>
                                </div>
                                <form id="updateForm{{$c->id}}" method="post" action="{{ route('cancel-customer-appointment',$c->id) }}">
                                  @csrf @method('post')
                                  <input type="hidden" name="customer_procedures_id" value="{{$c->customer_procedures_id}}">
                                </form>
                             </td>
                              @endif
                              @if($message != "Upcoming Appointments")
                              <td>
                                <div>
                                  <div class="text-center">
                                    <a class="modaljavascript a-hover"  data-id="{{$no}}" data-toggle="modal" data-target="#myModal{{$no}}">
                                      <img src="{{ asset('backend/web_imgs/plus.png') }}">
                                    </a>
                                  </div>
                                </div>
                            </td>
                            @endif
                            </tr>
                                        <!-- The Modal -->
                            <div class="modal" id="myModal{{$no}}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Add Next Appointment</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <form method="post" action="{{ route('editAppointment',$c->id) }}">
                                                @csrf @method('post')
                                                      <div class="form-group row">
                                                        <div class="col-md-3 form-control-label">Select Center <span class="asterisk">*</span></div>
                                                        <div class="col-md-7">
                                                          <select name="hospital_id" id="centers{{$no}}" class="form-control" required >
                                                            <option value="">Select Center</option>
                                                            @if($center_id != null)
                                                              @foreach($center_id as $c_id)
                                                                @php $center_name = centerName($c_id);@endphp
                                                                <option value="{{ $c_id }}" >{{$center_name}}</option>
                                                              @endforeach
                                                            @endif
                                                          </select>
                                                        </div>
                                                      </div>
                                                      <div class="form-group row">
                                                        <div class="col-md-3 form-control-label">Select Procedure <span class="asterisk">*</span></div>
                                                        <div class="col-md-7">
                                                          <select name="treatments_id" id="procedure{{$no}}" class="form-control" required>
                                                            <option value="">Select Procedure</option>
                                                          </select>
                                                        </div>
                                                      </div>
                                                  <div class="form-group row">
                                                    <label class="col-md-3 form-control-label">Appointment Date <span class="asterisk">*</span></label>
                                                    <div class="col-md-7">
                                                      <input type="datetime-local" name="appointment_date" placeholder="appointment_date" class="form-control" min='<?php echo Carbon\Carbon::now()->format('Y-m-d\TH:i:s');?>' required>
                                                    </div>
                                                  </div>
                                                <input type="hidden" name="customer_procedures_id" value="{{$c->customer_procedures_id}}">
                                                <input type="hidden" name="customer_id" value="{{$c->id}}">
                                                <!-- <input type="hidden" name="treatments_id" value="{{$c->treatments_id}}"> -->
                                                <input type="hidden" name="doctor_id" value="{{$c->doctor_id}}">

                                        </div>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Next Appointment</button>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                            {{-- End of Model --}}
                            @php $no++; @endphp
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
$( document ).ready(function() {
    $('.modaljavascript').on('click', function(){
      var i = $(this).data('id');
      $(document).on('change','#centers'+i+'', function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var center_id     = $('#centers'+i+'   option:selected').val();
        var doctor_id     = '<?php echo (Auth::user()->doctor_id);?>';
        $.ajax({
          type:'post',
          url:"{{ route('getCenterDoctorTreatments') }}",
          data: { center_id : center_id, doctor_id : doctor_id},
          success: function(response){
            $('#procedure'+i+'').html(response);

          }
        });
      });
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
$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    swal({
        title: "Are you sure?",
        text: "You want to delete appointment",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonClass: "btn-primary",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
        },
        function(){
            setTimeout(function () {
              $('#updateForm'+id).submit();
        swal("Deleted!", "Appointment has been deleted.", "success");
    }, 2000);
        });
});

</script>
@endsection
