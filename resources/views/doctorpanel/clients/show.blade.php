@extends('doctorpanel.layout')
@section('title', 'Client | Patients')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
@endsection
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">

        <div class="row">
            @include('adminpanel.notification')
                        <div class="col-lg-5">
                <div class="card">
                  <div class="card-header">
                      @if($customer->deleted_at == null && $customer->doctor_id == Auth::user()->doctor_id)
                    <h6 class="text-uppercase mb-0">Customer Profile<a href="{{ route('doctorclients.edit', $customer->id) }}" class="btn btn-sm btn-dark float-right">
                      Edit Customer
                    </a>
                    </h6>
                    @endif
                  <br>
                    @php
                    $user = Auth::user()->find($customer->patient_coordinator_id);
                    @endphp
                    <span><strong>Patient Owner:</strong></span><span> {{isset($user->name)?$user->name:""}}</span>
                  </div>
                  <div class="card-body table-responsive">

                      <div class="row">
                        <div class="col-md-12">
                          <ul>
                            <li><b>Card ID:</b> {{ $customer->card_id }}</li>
                            <li><b>Customer Name:</b> {{ $customer->name }}</li>
                            <li><b>Phone:</b> {{ $customer->phone }}</li>
                            <li><b>Blood Group:</b> {{ isset($blood_group->name) ? $blood_group->name : NULL}}</li>
                            <li><b>Age:</b> {{ $customer->age }}</li>
                            <li><b>Weight:</b> {{ $customer->weight }}</li>
                            <li><b>Height:</b> {{ $customer->height }}</li>
                            <li><b>Gender: </b>{{ $customer->gender == 0 ? "Male" : "Female" }}</li>
                            <li><b>Marital Status: </b>{{ $customer->marital_status == 0 ? 'Unmarried' : 'Married' }}</li>
                            <li><b>Last Contact Date:</b> {{ date('d-m-Y', strtotime($customer->updated_at)) }}</li>
                            <li><b>Next Contact Date:</b>
                            @if($customer->next_contact_date==NULL)
                            Not Updated
                            @else
                            {{ date('d-m-Y',strtotime($customer->next_contact_date)) }}
                            @endif
                          </li>


                          </ul>
                        </div>

                      </div>
                  </div>
                </div>
            </div>

      <div class="col-lg-7 tab-profile">
        <nav>
          <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Treatments</a>
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Doctor Notes</a>
            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Allergies</a>
            <a class="nav-item nav-link" id="nav-about-tab" data-toggle="tab" href="#nav-about" role="tab" aria-controls="nav-about" aria-selected="false">Risk Factors</a>
          </div>
        </nav>
        <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
          <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">
                    <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#AddTreatmentModal">Add Treatment</button>
                    <a href="{{ route('customer_treatment_history', $customer->id) }}" class="btn btn-sm btn-dark float-right">
                            History
                        </a>
                    </h6>
                  </div>
                  <div class="card-body table-responsive">
                      <!-- Treaments -->
                      @php
                        $customer_details   = TreatmentsCentersRelation($customer->id);
                        if($customer_details->center->count() != NULL){
                        $i = 0;
                        foreach($customer_details->treatments as $treatment){
                        $treatment_status = $treatment->pivot->status;
                        $parent_treatment   = ParentTreatment($treatment->id);
                        if(($treatment_status == 0 || $treatment_status == 2) && ($treatment->pivot->appointment_date != null)){
                      @endphp
                       <div class="row pt-2" id="close_treatment{{$i}}">
                        <div class="col-md-5">
                          <h5 class="text-center mb-2">Treatment # {{$i+1}} </h5>
                              <h5 class="text-center">
                              <!-- Ongoing appointment conditions -->
                              @if($treatment->pivot->status == 0 || $treatment->pivot->status == 2)
                              <button type="button" class="btn btn-sm btn-success mb-2 nextAppointmentModal" data-id="{{$treatment->pivot->id}}" data-toggle="modal" data-target="#nextAppointmentModal{{$treatment->pivot->id}}">
                                Next Appointment
                              </button>
                        <!-- Start of next appointment modal -->
                          <div class="modal" id="nextAppointmentModal{{$treatment->pivot->id}}">
                              <div class="modal-dialog modal-lg">
                                  <div class="modal-content">
                                      <!-- Modal Header -->
                                      <div class="modal-header">
                                          <h4 class="modal-title">Add Next Appointment</h4>
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                      </div>
                                      <!-- Modal body -->
                                      <div class="modal-body">
                                          <form method="post" action="{{ route('editAppointment',$customer->id) }}">
                                              @csrf @method('post')
                                                    <div class="form-group row">
                                                      <div class="col-md-3 form-control-label">Select Center <span class="asterisk">*</span></div>
                                                      <div class="col-md-7">
                                                        <select name="hospital_id" id="centers{{$treatment->pivot->id}}" class="form-control" required >
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
                                                        <select name="treatments_id" id="procedure{{$treatment->pivot->id}}" class="form-control" required>
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
                                              <input type="hidden" name="customer_procedures_id" value="{{$treatment->pivot->id}}">
                                              <input type="hidden" name="customer_id" value="{{$customer->id}}">
                                              <input type="hidden" name="doctor_id" value="{{Auth::user()->doctor_id}}">
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

                              <br>
                              @php
                                $appointment_date = $treatment->pivot->appointment_date;
                                $today = Carbon\Carbon::now()->todatetimestring();
                              @endphp
                                @if($today <= $appointment_date)
                                <button type="button" class="btn btn-sm btn-danger delete" data-id="{{$treatment->pivot->id}}">
                                      Cancel Appointment
                                </button>
                                <form id="deleteForm{{$treatment->pivot->id}}" method="post" action="{{ route('cancel-customer-appointment',$customer->id) }}">
                                  @csrf @method('post')
                                  <input type="hidden" name="customer_procedures_id" value="{{$treatment->pivot->id}}">
                                </form>
                                @endif
                              @endif
                              <!-- End of Ongoing appointment conditions -->
                            <h5>
                        </div>
                        <div class="col-md-7">
                        <ul>
                          <li><b>Treatment:</b> {{ $parent_treatment->parent_name }}</li>
                          <li><b>Procedure:</b> {{ $treatment->name}}</li>
                          <li><b>Center:</b> {{ $customer_details->center[$i]->center_name }}</li>
                          <li><b>Doctor:</b> {{ isset($customer_details->doctor[$i])?$customer_details->doctor[$i]->name:"" }}</li>
                          <!-- <li><b>Treatment Cost:</b> {{ $treatment->pivot->cost }}</li>
                          <li><b>Discount Percentage:</b> {{ $treatment->pivot->discount_per }}%</li>
                          <li><b>Discounted Cost:</b> {{ $treatment->pivot->discounted_cost }}</li> -->
                          <li><b>Appointment Date:</b> {{ $treatment->pivot->appointment_date }}</li>
                        </ul>
                        </div>
                      </div>
                      <hr>
                      @php $i++;}} } else { @endphp
                      <div class="row pt-2">
                        <div class="col-md-12">
                          <h5 class="text-center">No Treatment Selected</h5>
                        </div>
                      </div>
                      @php } @endphp
                  </div>
                </div>
          </div>
          <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div class="card">
              <div class="card-header">
                <h6 class="text-uppercase mb-0">
                  <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#AddDoctorNotesModal">Add Doctor Notes</button>
                </h6>
              </div>
              <div class="card-body table-responsive">
              @if(count($doctor_notes)>0)
                  @foreach($doctor_notes as $doctor_note)
                  <li>{{ $doctor_note->notes }}</li>
                  @endforeach
                  @else
                  {!!  'Nothing to Display' !!}
                @endif
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
            <div class="card">
              <div class="card-header">
                <h6 class="text-uppercase mb-0">
                  <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#AddAllergyNotesModal">Add Allergy</button>
                </h6>
              </div>
              <div class="card-body table-responsive">
                  @if(count($allergy_notes)>0)
                  @foreach($allergy_notes as $allergy)
                  <li>{{ $allergy->notes }}</li>
                  @endforeach
                  @else
                  {!!  'Nothing to Display' !!}
                  @endif
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase ">
                      <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#AddRiskFactorNotesModal">Add Risk Factor</button>
                    </h6>
                  </div>
                  <div class="card-body table-responsive">
                  @if(count($risk_factor_notes)>0)
                      @foreach($risk_factor_notes as $risk)
                      <li>{{ $risk->notes }}</li>
                      @endforeach
                      @else
                      {!!  'Nothing to Display' !!}
                      @endif
                  </div>
                </div>
          </div>
        </div>
    </div>
              <!-- Modal to add new appointments  -->
              <div class="modal fade" id="AddTreatmentModal" tabindex="-1" role="dialog" aria-labelledby="AddTreatmentModal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="AddTreatmentModal">Add Appointment Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form method="post" action="{{ route('appointmentCreatedByDoctor',$customer->id) }}">
                     @csrf @method('post')
                    <div class="modal-body">
                      <div class="form-group row">
                        <div class="col-md-2">
                          <label class="form-control-label">Select Center <span class="asterisk">*</span></label>
                        </div>
                        <div class="col-md-4">
                          <select name="center_id" id="centers" class="form-control selectpicker" required>
                            <option value="">Select Center</option>
                            @foreach($centers as $c)
                              <option value="{{ $c->id }}" {{ old('center_id') == $c->id ? 'selected': '' }}>{{ $c->center_name }}</option>
                            @endforeach
                          </select>
                          @if($errors->has('center_id'))
                              <div class="invalid-feedback ml-3">{{ $errors->first('center_id') }}</div>
                          @endif
                        </div>
                        <div class="col-md-2">
                          <label class="form-control-label">Select Procedure <span class="asterisk">*</span></label>
                        </div>
                        <div class="col-md-4">
                        <select name="procedure_id" id="procedure" class="form-control" required>
                          <option value="">Select Procedure</option>
                        </select>
                        @if($errors->has('procedure_id'))
                            <div class="invalid-feedback ml-3">{{ $errors->first('procedure_id') }}</div>
                        @endif
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-md-2">
                          <label class="form-control-label">Appointment Date <span class="asterisk">*</span></label>
                        </div>
                        <div class="col-md-10">
                        <input type="datetime-local" name="appointment_date" placeholder="appointment_date" min='<?php echo Carbon\Carbon::now()->format('Y-m-d\TH:i:s');?>'
                              class="form-control {{ $errors->has('appointment_date') ? 'is-invalid' : '' }}" value="" required>
                              <input type="hidden" name="appointment_from" value="3">
                            @if($errors->has('appointment_date'))
                              <div class="invalid-feedback ml-3">{{ $errors->first('appointment_date') }}</div>
                            @endif
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Update changes</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- End of Modal to add new appointments  -->
            <!-- Modal to add new Doctors notes  -->
              <div class="modal fade" id="AddDoctorNotesModal" tabindex="-1" role="dialog" aria-labelledby="AddDoctorNotesModal" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="AddDoctorNotesModal">Add Notes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form method="post" action="{{ route('addCustomerDoctorNotes',$customer->id) }}">
                     @csrf @method('post')
                    <div class="modal-body">
                      <div class="form-group row">
                        <div class="col-2">
                          <label class="form-control-label">Notes <span class="asterisk">*</span></label>
                        </div>
                        <div class="col-10">
                          <textarea name="doctor_notes" class="form-control" id="doctor_notes" cols="60" rows="3" required></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Add Notes</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- End of Modal to add new Doctor notes  -->
            <!-- Modal to add new allergies notes  -->
              <div class="modal fade" id="AddAllergyNotesModal" tabindex="-1" role="dialog" aria-labelledby="AddAllergyNotesModal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="AddAllergyNotesModal">Add Notes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form method="post" action="{{ route('addCustomerAllergiesNotes',$customer->id) }}">
                     @csrf @method('post')
                    <div class="modal-body">
                      <div id="dynamic_field_allergies_notes">
                      <div class="form-group row">
                      <label class="col-md-2 form-control-label">Allergies</label>
                        <div class="col-md-7">
                        <input type="text" placeholder="Enter Details" class="form-control" name="allergies_notes[]" id="" required/>
                          @if($errors->has('allergies_notes'))
                          <div class="invalid-feedback ml-3">{{ $errors->first('allergies_notes') }}</div>
                          @endif
                        </div>
                        <div class="col-md-2 form-control-label">
                          <button type="button" name="add_allergies_notes" id="add_allergies_notes" class="btn btn-success">Add More</button>
                        </div>
                      </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Add Notes</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- End of Modal to add new Allergies notes  -->
            <!-- Modal to add new Risk factor notes  -->
              <div class="modal fade" id="AddRiskFactorNotesModal" tabindex="-1" role="dialog" aria-labelledby="AddRiskFactorNotesModal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="AddRiskFactorNotesModal">Add Notes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form method="post" action="{{ route('addCustomerRiskFactorNotes',$customer->id) }}">
                     @csrf @method('post')
                    <div class="modal-body">
                      <div id="dynamic_field_riskfactor_notes">
                        <div class="form-group row">
                        <label class="col-md-2 form-control-label">Risk Factor</label>
                          <div class="col-md-7">
                          <input type="text" placeholder="Enter Details" class="form-control" name="riskfactor_notes[]" id="" required/>
                            @if($errors->has('riskfactor_notes'))
                            <div class="invalid-feedback ml-3">{{ $errors->first('riskfactor_notes') }}</div>
                            @endif
                          </div>
                          <div class="col-md-2 form-control-label">
                            <button type="button" name="add_riskfactor_notes" id="add_riskfactor_notes" class="btn btn-success">Add More</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Add Notes</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- End of Modal to add new risk factor notes  -->
        </div>
    </section>
</div>
@endsection
@section('scripts')
<script src="{{ asset('backend/js/sweetalert/sweetalert.js') }}"></script>
<!-- Latest compiled and minified JavaScript -->
<!-- validation -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
function myFunction() {
  var x = document.getElementById("myLocalDate").min;
  document.getElementById("demo").innerHTML = x;
}
</script>
<script>

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
        $('#deleteForm'+id).submit();
        swal("Deleted!", "Appointment has been deleted.", "success");
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
<script>
  $(document).ready(function() {
    $('#diagnostics1').DataTable({
        "lengthMenu": [5, 10, 15, 20, 25],
        "pageLength": 5
    });
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
<script>
  $(document).ready(function() {
    $('#diagnostics2').DataTable({
      "lengthMenu": [5, 10, 15, 20, 25],
        "pageLength": 5
    });
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
<script>
  // Create new appointment
   $(document).on('change','#centers', function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var center_id     = $('#centers   option:selected').val();
        var doctor_id     = '<?php echo (Auth::user()->doctor_id);?>';
        $.ajax({
          type:'post',
          url:"{{ route('getCenterDoctorTreatments') }}",
          data: { center_id : center_id, doctor_id : doctor_id},
          success: function(response){
            $('#procedure').html(response);

          }
        });
      });
</script>
<script>
// next appointment scripte
$( document ).ready(function() {
    $('.nextAppointmentModal').on('click', function(){
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

// dynamic input fields for Allergies Notes
$(document).ready(function(){
  var i=0;
  $('#add_allergies_notes').click(function(){
    i++;
    var html = '';
  html += '<div class="form-group row" id="allergies_notes_'+i+'">';
  html += '<label class="col-md-2 form-control-label">Allergies '+i+'</label>';
  html += '<div class="col-md-7">';
  html += '<input type="text" placeholder="Enter Details" class="form-control" name="allergies_notes[]" id="" />';
  html += '</div>';
  html += '<div class="col-md-2 form-control-label"> <button type="button" name="remove" id="allergies_notes_'+i+'" class="btn btn-danger btn_remove">X</button></div>';
  html += '</div>';
  $('#dynamic_field_allergies_notes').append(html);
  });

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id");
    $('#'+button_id+'').remove();
  });
});
// end of dynamic fields for Allergies Notes
</script>
<script>
// dynamic input fields for Risk factor Notes
$(document).ready(function(){
  var i=0;
  $('#add_riskfactor_notes').click(function(){
    i++;
    var html = '';
  html += '<div class="form-group row" id="riskfactor_notes_'+i+'">';
  html += '<label class="col-md-2 form-control-label">Risk Factor '+i+'</label>';
  html += '<div class="col-md-7">';
  html += '<input type="text" placeholder="Enter Details" class="form-control" name="riskfactor_notes[]" id="" />';
  html += '</div>';
  html += '<div class="col-md-2 form-control-label"> <button type="button" name="remove" id="riskfactor_notes_'+i+'" class="btn btn-danger btn_remove">X</button></div>';
  html += '</div>';
  $('#dynamic_field_riskfactor_notes').append(html);
  });

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id");
    $('#'+button_id+'').remove();
  });
});
// end of dynamic fields for Risk factor Notes
</script>
@endsection
