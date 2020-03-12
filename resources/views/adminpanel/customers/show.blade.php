@extends('adminpanel.layout')
@section('title', 'Customers | Patients')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
                        <div class="col-lg-5">
                <div class="card">
                  <div class="card-header">
                      @if($customer->deleted_at == null)
                    <h6 class="text-uppercase mb-0">Customer Profile<a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-dark float-right">
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
                            <li><b>Customer Status:</b> {{ $customer->status }}</li>
                            <li><b>Customer Name:</b> {{ $customer->name }}</li>
                            <li><b>Phone:</b> {{ $customer->phone }}</li>
                            <li><b>Address:</b> {{ $customer->address }}</li>
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
                            <div class="text-center">
                              <button class="btn btn-sm btn-success mr-2" data-toggle="modal" data-target="#AddTreatmentModal">Add Treatment</button>
                              <button class="btn btn-sm btn-success " data-toggle="modal" data-target="#AddDiagnosticModal">Add Diagnostic</button>
                            </div>
                        </div>

                      </div>
                  </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Treatments                        <a href="{{ route('treatment_history', $customer->id) }}" class="btn btn-sm btn-dark float-right">
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

                        $parent_treatment   = ParentTreatment($treatment->id);
                      @endphp
                       <div class="row pt-2" id="close_treatment{{$i}}">
                        <div class="col-md-5">
                          <h5 class="text-center mb-2">Treatment # {{$i+1}} </h5>
                          @if($treatment->pivot->discount_per)
                          <h6 class="text-center mb-3">Discount: {{$treatment->pivot->discount_per}}%</h6>
                          @endif
                          @if($treatment->pivot->status != 0)
                          <h6 class="text-center mb-3 " style="color: red">{{($treatment->pivot->status == 1)? "Canceled by the doctor" :(($treatment->pivot->status == 2)? "Onging Treatment" :(($treatment->pivot->status == 4)? "Pending Appointment":""))}}</h6>
                          @endif
                              <h5 class="text-center">

                              <!-- Ongoing appointment conditions -->
                              @if($treatment->pivot->status == 0 || $treatment->pivot->status == 2)
                              <button data-id="{{$treatment->pivot->id}}" type="submit" class="btn btn-sm btn-success treatmenthistory">
                                Close Treatment
                              </button>
                              <br>
                              <button data-id="{{$treatment->pivot->id}}"  data-cost="{{$treatment->pivot->cost}}" class="btn btn-sm btn-success mt-2 nextTreatment">
                                Next Appointment
                              </button>
                              @endif
                              <!-- End of Ongoing appointment conditions -->

                              <!-- pending appointment conditions -->
                              @if($treatment->pivot->status == 4 || $treatment->pivot->status == 1)
                              <div id="pending_appointment_{{$treatment->pivot->id}}">
                                <button data-id="{{$i+1}}" type="submit" class="btn btn-sm btn-success modaljavascript"  data-toggle="modal" data-target="#exampleModal_{{$i+1}}">
                                  <i class="fa fa-check" aria-hidden="true"></i>
                                </button>
                                <br>
                                <button data-id="{{$treatment->pivot->id}}" data-rowId ="{{$i+1}}" class="btn btn-sm btn-danger mt-2 delete_pending_appointment">
                                  <i class="fa fa-trash-alt" aria-hidden="true"></i>
                                </button>
                                <form id="deleteForm{{$treatment->pivot->id}}" method="post" action="{{ route('pendingappointments.destroy',$treatment->pivot->id) }}">
                                    @csrf @method('delete')
                                </form>
                              </div>
                              <!-- Modal to approve pending appointments  -->

                              <div class="modal fade" data-id="{{$i+1}}" id="exampleModal_{{$i+1}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Edit Appointment Details</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <form method="post" action="{{ route('update_pending_appointment',$treatment->pivot->id) }}">
                                       @csrf @method('post')
                                      <div class="modal-body" id="dynamic_field">
                                        <div class="form-group row">
                                          <div class="col-md-2">
                                            <label class="form-control-label">Select Treatment <span class="asterisk">*</span></label>
                                          </div>
                                          <div class="col-md-4">
                                          <select name="treatment_id" id="treatment{{$i+1}}" class="form-control" required>
                                            <option value="">Select Treatment</option>
                                            @foreach($treatments as $t)
                                            @if($treatment->parent_id == null)
                                              <option value="{{ $t->id }}" {{ $treatment->id == $t->id ? 'selected': '' }}>{{ $t->name }}</option>
                                            @endif
                                            @if($treatment->parent_id != null)
                                              <option value="{{ $t->id }}" {{ $treatment->parent_id == $t->id ? 'selected': '' }}>{{ $t->name }}</option>
                                            @endif
                                              @endforeach
                                          </select>
                                          </div>
                                          <div class="col-md-2">
                                            <label class="form-control-label">Select Procedure <span class="asterisk">*</span></label>
                                          </div>
                                          <div class="col-md-4">
                                            <select name="procedure_id"  id="procedure{{$i+1}}" class="form-control" required>
                                              <option value="0">Consultation</option>
                                              @foreach($procedures as $p)
                                                <option value="{{ $p->id }}" {{ $treatment->id == $p->id ? 'selected': '' }}>{{ $p->name }}</option>
                                              @endforeach
                                            </select>
                                          </div>
                                        </div>
                                        <div class="form-group row">
                                          <div class="col-md-2">
                                            <label class="form-control-label">Select Center<span class="asterisk">*</span></label>
                                          </div>
                                          <div class="col-md-4">
                                            <select name="hospital_id" id="centers{{$i+1}}" class="form-control " data-live-search="true" required >
                                              <option value="">Select Center</option>

                                              @foreach($centers as $c)
                                                <option value="{{ $c->id }}" {{ $treatment->pivot->hospital_id == $c->id ? 'selected' : '' }}>{{ $c->center_name }}</option>
                                              @endforeach
                                            </select>
                                          </div>
                                          <div class="col-md-2">
                                            <label class="form-control-label">Select Doctor<span class="asterisk">*</span></label>
                                          </div>
                                          <div class="col-md-4">
                                            <select name="doctor_id" id="doctors{{$i+1}}" class="form-control " data-live-search="true" required>
                                              <option value="">Select Doctor</option>
                                              @foreach($doctors as $d)
                                              <option value="{{ $d->id }}" {{ $treatment->pivot->doctor_id == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                                              @endforeach
                                            </select>
                                          </div>
                                        </div>
                                        <div class="form-group row">
                                          <div class="offset-md-2 col-md-8">
                                            <ul class="list-group schedule-box" id="schedule{{$i+1}}">
                                            </ul>
                                          </div>
                                        </div>
                                        <div class="form-group row">
                                          <div class="col-md-2">
                                            <label class="form-control-label">Appointment Date <span class="asterisk">*</span></label>
                                          </div>
                                          @php
                                            $appointment_date      = AppointmentTimeConvert($treatment->pivot->appointment_date);
                                            $appointment_from      = $treatment->pivot->appointment_from;
                                            $cost                  = TreatmentCost($treatment->pivot->doctor_id, $treatment->pivot->hospital_id);

                                          @endphp
                                          <div class="col-md-10">
                                            <input type="datetime-local" name="appointment_date" placeholder="appointment_date" class="form-control " value="{{ (isset($appointment_date) ? $appointment_date : '') }}" required>
                                            <input type="hidden" name="appointment_from" value="{{ (isset($appointment_from) ? $appointment_from : 0) }}">
                                            <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                          </div>
                                        </div>
                                        <div class="form-group row">
                                          <div class="col-md-2" >
                                            <label class="form-control-label">Cost <span class="asterisk">*</span></label>
                                          </div>
                                          <div class="col-md-4" id="cost{{$i+1}}">
                                            <input type="text" name="cost[]" placeholder="Enter treatment cost" class="form-control" value="{{isset($cost->fare)?$cost->fare:0 }}" required>
                                          </div>
                                          <div class="col-md-2">
                                            <label class="form-control-label">Discount %</label>
                                          </div>
                                          <div class="col-md-4">
                                            <input type="number" name="treatment_discount" id="treatment_discount{{$i+1}}" class="form-control" value="0" />
                                          </div>
                                        </div>
                                        <div class="form-group row">
                                          <label class="col-md-2 form-control-label">Result</label>
                                          <div class="col-md-4">
                                          <input type="number" name="discounted_cost" readonly id="tresult1{{$i+1}}" class="form-control" value="0" />
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
                              <!-- End of Modal to approve pending appointments  -->
                              @endif
                              <!-- End of pending Appointment conditions -->
                            <h5>
                            <form action="{{ route('treatment_to_history', $customer->id) }}" method="POST" id="deleteTreatmentForm{{$treatment->pivot->id}}">
                            @csrf
                            <input type="hidden" name="treatment_id"      value="{{$treatment->id}}">
                            <input type="hidden" name="center_id"         value="{{$treatment->pivot->hospital_id}}">
                            <input type="hidden" name="doctor_id"         value="{{$treatment->pivot->doctor_id}}">
                            <input type="hidden" name="cost"              value="{{ $treatment->pivot->cost }}">
                            <input type="hidden" name="discount_per"      value="{{ $treatment->pivot->discount_per }}">
                            <input type="hidden" name="discounted_cost"   value="{{ $treatment->pivot->discounted_cost }}">
                            <input type="hidden" name="appointment_date"  value="{{ $treatment->pivot->appointment_date }}">
                            <input type="hidden" name="appointment_from"  value="{{ $treatment->pivot->appointment_from }}">
                          </form>
                        </div>
                        <div class="col-md-7">
                        <ul>
                          <li><b>Treatment:</b> {{ $parent_treatment->parent_name }}</li>
                          <li><b>Procedure:</b> {{ $treatment->name}}</li>
                          <li><b>Center:</b> {{ $customer_details->center[$i]->center_name }}</li>
                          <li><b>Doctor:</b> {{ isset($customer_details->doctor[$i])?$customer_details->doctor[$i]->name:"" }}</li>
                          <li><b>Treatment Cost:</b> {{ $treatment->pivot->cost }}</li>
                          <li><b>Discount Percentage:</b> {{ $treatment->pivot->discount_per }}%</li>
                          <li><b>Discounted Cost:</b> {{ $treatment->pivot->discounted_cost }}</li>
                          <li><b>Appointment Date:</b> {{ $treatment->pivot->appointment_date }}</li>
                        </ul>
                        </div>
                      </div>
                      <hr>
                      @php $i++;}} else { @endphp
                      <div class="row pt-2">
                        <div class="col-md-12">
                          <h5 class="text-center">No Treatment Selected</h5>
                        </div>
                      </div>
                      @php } @endphp
                  </div>
                </div>
            </div>

        <?php
        if($lab != NULL){
          $customer_details   = TreatmentsCentersRelation($customer->id);
          $count_labs         = count($lab);
          $j = 1;
          if($count_labs != NULL){
          $k  = 0;
          for($i=0; $i < $count_labs; $i++){
            $diagnostics_all          = CustomerLabDiagnostics($customer->id,$lab[$i]);
            foreach($diagnostics_all as $db){
              $diagnostics            = CustomerLabDiagnosticsDetails($db->bundle_id);
        ?>
        <div class="col-lg-6 mb-5 pt-4">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-2">Diagnostics from {{ $diagnostics[$i]->lab_name}}</h3>
              <button data-id="{{$k+100}}" type="submit" class="btn btn-sm btn-success close_treatment diagnostichistory float-left">
                Close Diagnostic
              </button>


                <!-- <span>Discount: <strong>{{ $diagnostics[0]->discount_per}}</strong></span> -->
                <form action="{{ route('diagnostic_to_history', $customer->id) }}" method="POST" id="deleteDiagnosticForm{{$k+100}}">
                @csrf
                @foreach($diagnostics as $d)
                <input type="hidden" name="delete_id[]" value="{{$d->id}}">
                <input type="hidden" name="bundle_id" value="{{$d->bundle_id}}">
                @endforeach
              </form>
          </div>
          <div class="card-body">
            <div class="media row">
              <div class="media-body col-md-12">
                <table class="table table-striped table-dark" id="diagnostics{{$k+1}}">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Diagnostics</th>
                      <th scope="col">Costs</th>
                      <th scope="col">Discount %</th>
                      <th scope="col">Discounted Cost</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                    $e = 1;
                    foreach($diagnostics as $d){
                    @endphp
                  <tr >
                    <td>{{ $e }}</td>
                    <td>{{ $d->name}}</td>
                    <td>{{ $d->cost }}</td>
                    <td>{{ $d->discount_per }}%</td>
                    <td>{{ $d->discounted_cost }}</td>
                  </tr>

                    @php $j++; $e++; } @endphp
                  </tbody>
                </table>

              </div>
            </div>
          </div>
          <div class= "card-footer">
            <div class="row">
            <div class="col-md-4 h6 mb-0 text-left">Appointment Date: {{ $diagnostics[0]->appointment_date}}</div>
              <div class="col-md-4 h6 mb-0">Original Cost:
              @php
              $sum =0;
              foreach($diagnostics as $d){
                $sum +=$d->cost;
              }
              @endphp
              {{$sum}}
              @if($diagnostics[0]->home_sampling == 1)
              Home Sampling
              @endif
              </div>
              <div class="col-md-4 h6 mb-0">Discounted Cost:
              @php
              $sum =0;
              foreach($diagnostics as $d){
                if($d->discounted_cost != null){
                    $sum +=$d->discounted_cost;
                }
              }
              @endphp
              {{$sum}}
              </div>
            </div>
          </div>
        </div>
      </div>
          @php $k++; } } } }
           else { @endphp
            <div class="col-md-12 pt-5">
                <h5 class="text-center" style="margin-left: 22px;">No Diagnostic Selected</h5>
            </div>
            @php } @endphp
            <div class="col-md-6 mt-5">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Notes
                      <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#editNotesModal">Edit Notes</button>
                    </h6>
                  </div>
                  <div class="card-body table-responsive">
                      {!! $customer->notes !!}
                  </div>
                </div>
            </div>
            <div class="col-md-6 mt-5">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Doctor Notes</h6>
                  </div>
                  <div class="card-body table-responsive">
                      {!! isset($doctor_notes->notes) ? $doctor_notes->notes : 'Nothing to Display' !!}
                  </div>
                </div>
            </div>
            <div class="col-md-6 mt-5">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Allergies
                      <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#editAllergyNotesModal">Edit Allergies</button>
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
            <div class="col-md-6 mt-5">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Risk Factor
                      <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#editRiskFactorNotesModal">Edit Risk Factor</button>
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
              <div class="col-lg-12 mt-5">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Dependents
                      <a href="{{ route('depend', $customer->id) }}" class="btn btn-sm btn-dark float-right">Create New</a>
                    </h6>
                  </div>
                  <div class="card-body table-responsive">
                  <table class="table table-striped table-sm card-text" id="customers">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Address</th>
                          <th>Relation with employee</th>
                          <th>Details</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if(isset($employee))
                            @php $no=1 @endphp
                            @foreach($employee as $c)

                            <tr>
                              <th scope="row">{{$no++}}</th>

                              <td>{{ $c->name }}</td>
                              <td>{{ $c->email }}</td>
                              <td>{{ $c->phone }}</td>
                              <td>{{ $c->address }}</td>
                              <td>{{ $c->relation }}</td>
                              <td><center><a href="{{ route('dependents.show', $c->id) }}"><i class="fa fa-eye"></i></a></center></td>
                              <td><center><a href="{{ route('dependents.edit', $c->id) }}"><i class="fa fa-edit"></i></a></center></td>
                              <td><center>
                                <a class="delete" data-id="{{ $c->id }}" href="#"><i class="fa fa-trash"></i></a></center>
                                <form id="deleteForm{{$c->id}}" method="post" action="{{ route('dependents.destroy', $c->id) }}">
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
              <form method="POST" action="{{ route('createNewAppointment',$customer->id) }}">
              @csrf @method('post')
                <div class="modal-body">
                  <div class="form-group row">
                    <div class="col-md-2">
                      <label class="form-control-label">Select Treatment <span class="asterisk">*</span></label>
                    </div>
                    <div class="col-md-4">
                      <select name="treatment_id" id="treatment" class="form-control" required>
                        <option value="">Select Treatment</option>
                        @foreach($treatments as $t)
                          <option value="{{ $t->id }}" {{ old('treatment_id') == $t->id ? 'selected': '' }}>{{ $t->name }}</option>
                        @endforeach
                      </select>
                      @if($errors->has('treatment_id'))
                          <div class="invalid-feedback ml-3">{{ $errors->first('treatment_id') }}</div>
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
                      <label class="form-control-label">Select Center<span class="asterisk">*</span></label>
                    </div>
                    <div class="col-md-4">
                    <select name="hospital_id" id="centers" class="form-control" required>
                      <option value="">Select Center</option>
                    </select>
                    @if($errors->has('hospital_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('hospital_id') }}</div>
                    @endif
                    </div>
                    <div class="col-md-2">
                      <label class="form-control-label">Select Doctor<span class="asterisk">*</span></label>
                    </div>
                    <div class="col-md-4">
                    <select name="doctor_id" id="doctors" class="form-control" required>
                      <option value="">Select Doctor</option>
                    </select>
                    @if($errors->has('doctor_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('doctor_id') }}</div>
                    @endif
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="offset-md-2 col-md-8">
                      <ul class="list-group schedule-box" id="schedule">
                      </ul>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-2">
                      <label class="form-control-label">Appointment Date <span class="asterisk">*</span></label>
                    </div>

                    <div class="col-md-10">
                    <input type="datetime-local" name="appointment_date" placeholder="appointment_date"
                          class="form-control {{ $errors->has('appointment_date') ? 'is-invalid' : '' }}" value="" required>
                          <input type="hidden" name="appointment_from" value="0">
                        @if($errors->has('appointment_date'))
                          <div class="invalid-feedback ml-3">{{ $errors->first('appointment_date') }}</div>
                        @endif
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-2" >
                      <label class="form-control-label">Cost <span class="asterisk">*</span></label>
                    </div>
                    <div class="col-md-4" id="cost">
                          <input type="number" name="cost[]" placeholder="Treatment Cost"
                          class="form-control {{ $errors->has('cost') ? 'is-invalid' : '' }}" value="0" required>

                        @if($errors->has('cost'))
                          <div class="invalid-feedback ml-3">{{ $errors->first('cost') }}</div>
                        @endif
                    </div>
                    <div class="col-md-2">
                      <label class="form-control-label">Discount %</label>
                    </div>
                    <div class="col-md-4">
                    <input type="number" name="treatment_discount" id="treatment_discount" class="form-control" value="0" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-2 form-control-label">Result</label>
                    <div class="col-md-4">
                    <input type="number" name="discounted_cost" readonly id="tresult1" class="form-control" value="0" />

                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Create Appointment</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- End of Modal to add new appointments  -->

      <!-- Modal to add new appointments  -->
      <div class="modal fade" id="AddDiagnosticModal" tabindex="-1" role="dialog" aria-labelledby="AddDiagnosticModal" aria-hidden="true">
        <div class="modal-dialog modal-lg"style="max-width: 1078px !important;" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="AddDiagnosticModal">Add Appointment Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="POST" action="{{ route('NewDiagnosticAppointment',$customer->id) }}">
          @csrf @method('post')
            <div class="modal-body" >
                <!-- Diagnostics # 1 -->
                <div class="form-group row">
                  <div class="col-md-12 text-center form-control-label">Diagnostics</div>
                </div>
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Lab <span class="asterisk-blue">*</span></div>
                  <div class="col-md-10">
                    <select name="lab_id" id="lab_id" class="form-control">
                      <option value="">Select Lab</option>
                      @foreach($labs as $l)
                        <option value="{{ $l->id }}" {{ old('diagnostic_id') == $l->id ? 'selected': '' }}>{{ $l->name }}</option>
                      @endforeach
                    </select>
                    @if($errors->has('lab_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('lab_id') }}</div>
                    @endif
                  </div>

                </div>

                <div id="dynamic_field_diagnostic">
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Diagnostic <span class="asterisk-blue">*</span></div>
                  <div class="col-md-3">
                    <select name="diagnostic_id[]" id="diagnostic" class="form-control">
                      <option value="">Select Diagnostic</option>
                    </select>
                     @if($errors->has('diagnostic_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('diagnostic_id') }}</div>
                    @endif
                  </div>
                  <label class="col-md-2 form-control-label">Diagnostic Cost</label>
                  <div class="col-md-3" id="diagnostics_cost">
                    <input type="number" id="dcost" name="diagnostics_cost[]" placeholder="Diagnostic Cost"
                        class="form-control qty1 {{ $errors->has('diagnostics_cost') ? 'is-invalid' : '' }}" value="0" required>
                        <input type="hidden" name="diagnostics_appointment_from[]" value="0">
                      @if($errors->has('diagnostics_cost'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('diagnostics_cost') }}</div>
                      @endif
                  </div>
                  <div class="col-md-2 text-center">
                    <button type="button" name="add_d" id="add_d" class="btn btn-success btn-sm">Add More</button>
                  </div>
                </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Appointment Date</label>
                  <div class="col-md-3">
                    <input type="datetime-local" name="diagnostic_appointment_date" placeholder="appointment_date"
                        class="form-control {{ $errors->has('diagnostic_appointment_date') ? 'is-invalid' : '' }}" value="">
                      @if($errors->has('diagnostic_appointment_date'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('diagnostic_appointment_date') }}</div>
                      @endif
                  </div>
                  <label class="col-md-2 form-control-label">Total Cost</label>
                  <div class="col-md-3">
                  <input type="number" readonly class="total form-control" value="" id="total-cost1" />
                  </div>
                  <div class="col-md-2 text-center">
                   <button type="button" name="sum-btn" id="sum-btn" class="btn btn-info btn-sm">Sum</button>
                  </div>
                </div>
                <div class="form-group row">
                <label class="col-md-2 form-control-label">Home Sampling</label>
                  <div class="col-md-3">
                    <div class="custom-control custom-checkbox">
                      <input id="home_sampling" value="1" type="checkbox" name="home_sampling" class="custom-control-input">
                      <label for="home_sampling" class="custom-control-label">Home Sample</label>
                    </div>
                  </div>
                  <label class="col-md-2 form-control-label">Discount %</label>
                  <div class="col-md-3">
                  <input type="number" name="discount" id="chDiscount" class="form-control" value="0" />
                  </div>
                </div>
                <div class="form-group row">

                  <label class=" offset-md-5 col-md-2 form-control-label">Result</label>
                  <div class="col-md-3">
                  <input type="number" readonly id="result1" class="form-control" value="" />
                  </div>

                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Create Appointment</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- End of Modal to add new appointments  -->
        <!-- Modal to add new Doctors notes  -->
        <div class="modal fade" id="editNotesModal" tabindex="-1" role="dialog" aria-labelledby="editNotesModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editNotesModal">Edit Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="post" action="{{ route('editCustomerNotes',$customer->id) }}">
                 @csrf @method('post')
                <div class="modal-body">
                  <div class="container-fluid">
                  <div class="form-group row">
                    <label class="col-md-1 form-control-label">Notes</label>
                    <div class="col-md-11">
                        <textarea placeholder="Enter Details" class="form-control tiny" name="notes" id="" cols="30" rows="5">{{ isset($customer->notes)?$customer->notes:"What :" }}</textarea>
                        @if($errors->has('notes'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('notes') }}</div>
                        @endif
                    </div>
                  </div>
                </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Update Notes</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- End of Modal to add new Doctor notes  -->

        <!-- Modal to add new allergies notes  -->
          <div class="modal fade" id="editAllergyNotesModal" tabindex="-1" role="dialog" aria-labelledby="editAllergyNotesModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editAllergyNotesModal">Add Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="post" action="{{ route('editCustomerAllergiesNotes',$customer->id) }}">
                 @csrf @method('post')
                <div class="modal-body">
                <div class="container-fluid">
                      <?php $i = 0 ?>
                      <?php $j = 0 ?>
                    @if(count($allergy_notes)>0)
                    @foreach($allergy_notes as $allergy)
                    <div id="dynamic_field_allergies_notes">
                            <div class="form-group row"  id="allergies{{$i+1}}">
                            <label class="col-md-2 form-control-label">Allergies</label>
                              <div class="col-md-8">
                              <input type="text" placeholder="Enter Details" class="form-control" name="allergies_notes[]" value="{{$allergy->notes}}" required/>
                                @if($errors->has('allergies_notes'))
                                <div class="invalid-feedback ml-3">{{ $errors->first('allergies_notes') }}</div>
                                @endif
                              </div>
                              <?php if ($i == 0){?>
                                <div class="col-md-2 form-control-label text-center">
                                  <button type="button" name="add" id="add_allergies_notes" class="btn btn-success">Add More</button>
                                </div>
                              <?php }else{ ?>
                                <div class="col-md-2 form-control-label text-center">
                                    <button type="button" name="remove" id="allergies{{$i+1}}" class="btn btn-danger btn_remove text-center">X</button>
                                </div>
                              <?php }?>
                            </div>
                            </div>
                            <?php $i++?>
                            @endforeach
                    @else
                    <div id="dynamic_field_allergies_notes">
                            <div class="form-group row">
                            <label class="col-md-2 form-control-label">Allergies</label>
                              <div class="col-md-8">
                              <input type="text" placeholder="Enter Details" class="form-control" name="allergies_notes[]" id="" required />
                                @if($errors->has('allergies_notes'))
                                <div class="invalid-feedback ml-3">{{ $errors->first('allergies_notes') }}</div>
                                @endif
                              </div>
                              <div class="col-md-2 form-control-label text-center">
                                <button type="button" name="add_allergies_notes" id="add_allergies_notes" class="btn btn-success">Add More</button>
                              </div>
                            </div>
                            </div>
                    @endif
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
          <div class="modal fade" id="editRiskFactorNotesModal" tabindex="-1" role="dialog" aria-labelledby="editRiskFactorNotesModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editRiskFactorNotesModal">Add Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="post" action="{{ route('editCustomerRiskFactorNotes',$customer->id) }}">
                 @csrf @method('post')
                <div class="modal-body">
                <div class="container-fluid">
                    <?php $i = 0 ?>
                    <?php $j = 0 ?>
                  @if(count($risk_factor_notes)>0)
                  @foreach($risk_factor_notes as $riskfactors)
                  <div id="dynamic_field_riskfactor_notes">
                          <div class="form-group row"  id="riskfactor{{$i+1}}">
                          <label class="col-md-2 form-control-label">Risk Factor</label>
                            <div class="col-md-8">
                            <input type="text" placeholder="Enter Details" class="form-control" name="riskfactor_notes[]" value="{{$riskfactors->notes}}" required/>
                              @if($errors->has('riskfactor_notes'))
                              <div class="invalid-feedback ml-3">{{ $errors->first('riskfactor_notes') }}</div>
                              @endif
                            </div>
                            <?php if ($i == 0){?>
                              <div class="col-md-2 form-control-label text-center">
                                <button type="button" name="add" id="add_riskfactor_notes" class="btn btn-success">Add More</button>
                              </div>
                            <?php }else{ ?>
                              <div class="col-md-2 form-control-label text-center">
                                  <button type="button" name="remove" id="riskfactor{{$i+1}}" class="btn btn-danger btn_remove text-center">X</button>
                              </div>
                            <?php }?>
                          </div>
                          </div>
                          <?php $i++?>
                          @endforeach
                      @else
                      <div id="dynamic_field_riskfactor_notes">
                          <div class="form-group row">
                          <label class="col-md-2 form-control-label">Risk Factor</label>
                            <div class="col-md-8">
                            <input type="text" placeholder="Enter Details" class="form-control" name="riskfactor_notes[]" id="" required/>
                              @if($errors->has('riskfactor_notes'))
                              <div class="invalid-feedback ml-3">{{ $errors->first('riskfactor_notes') }}</div>
                              @endif
                            </div>
                            <div class="col-md-2 form-control-label  text-center">
                              <button type="button" name="add_riskfactor_notes" id="add_riskfactor_notes" class="btn btn-success">Add More</button>
                            </div>
                        </div>
                      </div>
                  @endif
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
<script src="{{ asset('backend/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/tinymce-config.js') }}" ></script>
<script src="{{ asset('backend/js/bootstrap-inputmask.min.js') }}"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
<!-- validation -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>
<script>

// dynamic input fields for Allergies Notes
$(document).ready(function(){
  var i=0;
  $('#add_allergies_notes').click(function(){
    i++;
    var html = '';
  html += '<div class="form-group row" id="allergies_notes_'+i+'">';
  html += '<label class="col-md-2 form-control-label">Allergies '+i+'</label>';
  html += '<div class="col-md-8">';
  html += '<input type="text" placeholder="Enter Details" class="form-control" name="allergies_notes[]" id="" required/>';
  html += '</div>';
  html += '<div class="col-md-2 form-control-label text-center"> <button type="button" name="remove" id="allergies_notes_'+i+'" class="btn btn-danger btn_remove">X</button></div>';
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
  html += '<div class="col-md-8">';
  html += '<input type="text" placeholder="Enter Details" class="form-control" name="riskfactor_notes[]" id="" required/>';
  html += '</div>';
  html += '<div class="col-md-2 form-control-label text-center"> <button type="button" name="remove" id="riskfactor_notes_'+i+'" class="btn btn-danger btn_remove">X</button></div>';
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
<script>
$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    swal({
        title: "Are you sure?",
        text: "You want to delete dependent",
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
        swal("Deleted!", "Dependent has been deleted.", "success");
    }, 2000);
        });
});
$(document).on('click', '.diagnostichistory', function(){
    var id = $(this).data('id');
    console.log("id ---->",id);
    swal({
        title: "Are you sure?",
        text: "You want to move it to History",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-primary",
        cancelButtonClass: "btn-danger",
        confirmButtonText: "Yes, Move into history",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
        },
        function(){
            setTimeout(function () {
        $('#deleteDiagnosticForm'+id).submit();
        swal("Moved!", "Diagnostic move to Customer History.", "success");
    }, 2000);
        });
});
$(document).on('click', '.treatmenthistory', function(){
    var id = $(this).data('id');
    swal({
        title: "Are you sure?",
        text: "You want to move it to History",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-primary",
        cancelButtonClass: "btn-danger",
        confirmButtonText: "Yes, Move into history",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
        },
        function(){
            setTimeout(function () {
        $('#deleteTreatmentForm'+id).submit();
        swal("Moved!", "Treatment move to Customer History.", "success");
    }, 2000);
        });
});
</script>

<script>
  $(document).on('click', '.nextTreatment', function(){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).data('id');
    var cost = $(this).data('cost');
    swal({
          title: "Enter new appointment date",
          type: "input",
          showCancelButton: true,
          closeOnConfirm: false,
          cancelButtonClass: "btn-danger",
          animation: "slide-from-bottom",
          inputType: "datetime-local",
          inputName: "appointment_date"
        },
        function(inputValue){
            if (inputValue === false) return false;
            if (inputValue === "") {
                  swal.showInputError("You need to write something!");
                return false;
            }
            var appointment_date = inputValue;
            swal({
                title: "Enter cost of treatment",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                cancelButtonClass: "btn-danger",
                animation: "slide-from-top",
                inputName: "cost",
                inputType: "number",
                inputValue: cost ,
            },
            function(inputValue){
                if (inputValue === false) return false;
                if (inputValue === "") {
                  swal.showInputError("You need to write something!");
                    return false;
                }
                var cost = inputValue;

                $.ajax({
                  type:'post',
                  url:"{{ route('nextAppointment') }}",
                  data: { id : id , appointment_date: appointment_date, cost : cost},
                  success: function(response){
                    swal({title: "Next Appointment!", text: "Next appointment has been scheduled", type: "success"},
                    function(){
                        location.reload();
                    });
                  }
                });
            });
        });
});
</script>

<script>
// delete pending appointments

$(document).on('click', '.delete_pending_appointment', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id = $(this).data('id');
    swal({
        title: "Are you sure?",
        text: "You want to delete Appointment!",
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
// end of pending appointments
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
    //for all the tables having id of diagnostics. Dynamically!!!
    $("table[id^=diagnostics]").DataTable({
        "lengthMenu": [5, 10, 15, 20, 25],
        "pageLength": 5
    });
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
<!-- <script>
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
  $(document).ready(function() {
    $('#diagnostics3').DataTable({
      "lengthMenu": [5, 10, 15, 20, 25],
        "pageLength": 5
    });
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script> -->
<!-- <script>
  $(document).ready(function() {
    $('#diagnostics4').DataTable({
      "lengthMenu": [5, 10, 15, 20, 25],
        "pageLength": 5
    });
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script> -->

<script>                                                                                // scripts for appointments to update

  $( document ).ready(function() {
    $('.modaljavascript').on('click', function(){
      var i = $(this).data('id');

    $(document).on('change','#treatment'+i+'', function(){
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        var id = $(this).val();
        $.ajax({
          type:'post',
          url:"{{ route('getTreatments') }}",
          data: { id : id},
          success: function(response){
            $('#procedure'+i+'').html(response);
          }
        });
      });
    $(document).on('change','#treatment'+i+'', function(){
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        var id = $(this).val();
        $.ajax({
          type:'post',
          url:"{{ route('getCenters') }}",
          data: { id : id},
          success: function(response){
            $('#centers'+i+'').html(response);
          }
        });
      });

      $(document).on('change','#procedure'+i+'', function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var id = $(this).val();
        if(id == 0){
            id  = $('#treatment'+i+' option:selected').val();
        }
        $.ajax({
          type:'post',
          url:"{{ route('getCenters') }}",
          data: { id : id},
          success: function(response){
            $('#centers'+i+'').html(response);
          }
        });
      });
      $(document).on('change','#centers'+i+'', function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var procedure_id  = $('#procedure'+i+' option:selected').val();
        if(procedure_id == 0){
            procedure_id  = $('#treatment'+i+' option:selected').val();
        }
        var center_id     = $('#centers'+i+'   option:selected').val();
        $.ajax({
          type:'post',
          url:"{{ route('getDoctors') }}",
          data: { procedure_id : procedure_id, center_id : center_id},
          success: function(response){
            $('#doctors'+i+'').html(response);
          }
        });
      });
      $(document).on('change','#doctors'+i+'', function(){
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      var center_id     = $('#centers'+i+' option:selected').val();
      var doctor_id     = $('#doctors'+i+' option:selected').val();
      $.ajax({
        type:'post',
        url:"{{ route('getDoctorSchedule') }}",
        data: { center_id : center_id, doctor_id : doctor_id},
        success: function(response){
          $('#schedule'+i+'').html(response);
        }
      });
    });
    $(document).on('change','#doctors'+i+'', function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var procedure_id  = $('#procedure'+i+' option:selected').val();
        if(procedure_id == 0){
            procedure_id  = $('#treatment'+i+' option:selected').val();
        }
        var doctor_id     = $('#doctors'+i+'   option:selected').val();
        var center_id     = $('#centers'+i+'   option:selected').val();


        $.ajax({
          type:'post',
          url:"{{ route('getDocCost') }}",
          data: { procedure_id : procedure_id, doctor_id : doctor_id, center_id : center_id},
          success: function(response){
            $('#cost'+i+'').html(response);
          }
        });
      });
      $(document).on("change keyup blur", "#treatment_discount"+i+"", function() {
      var main = $("#cost"+i+" input").val();
      var disc = $('#treatment_discount'+i+'').val();
      var dec = (disc / 100).toFixed(2); //its convert 10 into 0.10
      var mult = main * dec; // gives the value for subtract from main value
      var discont = main - mult;
      $('#tresult1'+i+'').val(discont);
    });
  });
});
                                                                                                          // end of scripts of already treatment update
</script>
<script>                                                                                                  // Scripts to add new appointment of customer

$(document).on('change','#treatment', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getTreatments') }}",
      data: { id : id},
      success: function(response){
        $('#procedure').html(response);
      }
    });
  });
$(document).on('change','#treatment', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getCenters') }}",
      data: { id : id},
      success: function(response){
        $('#centers').html(response);
      }
    });
  });

  $(document).on('change','#procedure', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id = $(this).val();
    if(id == 0){
         id  = $("#treatment option:selected").val();
    }
    $.ajax({
      type:'post',
      url:"{{ route('getCenters') }}",
      data: { id : id},
      success: function(response){
        $('#centers').html(response);
      }
    });
  });
  $(document).on('change','#centers', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var procedure_id  = $("#procedure option:selected").val();
    if(procedure_id == 0){
         procedure_id  = $("#treatment option:selected").val();
    }
    var center_id     = $("#centers option:selected").val();
    $.ajax({
      type:'post',
      url:"{{ route('getDoctors') }}",
      data: { procedure_id : procedure_id, center_id : center_id},
      success: function(response){
        $('#doctors').html(response);
      }
    });
  });
  $(document).on('change','#doctors', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var procedure_id  = $("#procedure option:selected").val();
    var doctor_id     = $("#doctors option:selected").val();
    var center_id     = $("#centers option:selected").val();


    $.ajax({
      type:'post',
      url:"{{ route('getDocCost') }}",
      data: { procedure_id : procedure_id, doctor_id : doctor_id, center_id : center_id},
      success: function(response){
        $('#cost').html(response);
      }
    });
  });
  $(document).on('change','#doctors', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  var center_id     = $("#centers option:selected").val();
  var doctor_id     = $("#doctors option:selected").val();
  $.ajax({
    type:'post',
    url:"{{ route('getDoctorSchedule') }}",
    data: { center_id : center_id, doctor_id : doctor_id},
    success: function(response){
      $('#schedule').html(response);
    }
  });
});
$(document).on("change keyup blur", "#treatment_discount", function() {
      var main          = $("#cost input").val();
      var disc = $('#treatment_discount').val();
      var dec = (disc / 100).toFixed(2); //its convert 10 into 0.10
      var mult = main * dec; // gives the value for subtract from main value
      var discont = main - mult;
      $('#tresult1').val(discont);
    });
                                                                                                    // end of scripts to add new appointment for customer
</script>
<script>
  $('#lab_id').change(function(){
    var i;
    var j = 99;
    for (i = 2; i < j; i++) {
    $('#row_d'+i).remove();
    $('.qty1').val(0);
    }
  });
</script>
<!-- Diagnostic # 1 Script -->
<script>
$(document).on('change','#lab_id', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $("#lab_id option:selected").val();
    console.log(id);
    $.ajax({
      type:'post',
      url:"{{ route('getDiagnostics') }}",
      data: { id : id},
      success: function(response){
        $('#diagnostic').html(response);
      }
    });
  });

$(document).on('change','#diagnostic', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var diagnostic_id   = $("#diagnostic option:selected").val();
    var lab_id          = $("#lab_id option:selected").val();
    $.ajax({
      type:'post',
      url:"{{ route('getDiagnosticCost') }}",
      data: { diagnostic_id : diagnostic_id, lab_id : lab_id },
      success: function(response){
        $('#diagnostics_cost').html(response);
      }
    });
  });

$(document).ready(function(){
  var d=1;
  $('#add_d').click(function(){
  d++;
  var html = '';
  html += '<div class="form-group row row_d" id="row_d'+d+'">';
  html += '<div class="col-md-12">';

  html += '<div class="form-group row">';
  html += '<div class="col-md-2 form-control-label">Select Diagnostic <span class="asterisk-blue">*</span></div>';
  html += '<div class="col-md-3">';
  html += '<select name="diagnostic_id[]" id="diagnostic'+d+'" class="form-control">';
  html += '<option value="">Select Diagnostic</option>';
  html += '</select>';
  html += '</div>';

  html += '<label class="col-md-2 form-control-label">Diagnostic Cost</label>';
  html += '<div class="col-md-3" id="diagnostics_cost'+d+'">';
  html += '<input type="number" name="diagnostics_cost[]" placeholder="Diagnostic Cost" class="form-control" value="0" required><input type="hidden" name="diagnostic_appointment_from[]" value="0">';
  html += '</div>';
  html += '<div class="col-md-2 text-center">';
  html += '<button type="button" name="remove" id="'+d+'" class="btn btn-danger btn-sm btn_remove_d">Remove</button>';
  html += '</div>';
  html += '</div>';

  html += '</div>';
  html += '</div>';
  $('#dynamic_field_diagnostic').append(html);

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $("#lab_id option:selected").val();
    $.ajax({
      type:'post',
      url:"{{ route('getDiagnostics') }}",
      data: { id : id},
      success: function(response){
        $('#diagnostic'+d+'').html(response);
      }
    });


$(document).on('change','#diagnostic'+d+'', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var diagnostic_id   = $("#diagnostic"+d+" option:selected").val();
    var lab_id          = $("#lab_id option:selected").val();
    $.ajax({
      type:'post',
      url:"{{ route('getDiagnosticCost') }}",
      data: { diagnostic_id : diagnostic_id, lab_id : lab_id },
      success: function(response){
        $('#diagnostics_cost'+d+'').html(response);
      }
    });
  });

  $(document).on("click", "#sum-btn", function() {
      var sum = 0;
      $(".qty1").each(function(){
          sum += +$(this).val();
      });

      $(".total").val(sum);
  });

  $(document).on('click', '.btn_remove_d', function(){
    var button_id = $(this).attr("id");
    $('#row_d'+button_id+'').remove();
  });

   });
 });
</script>
<!-- End of Diagnostic # 1 Script -->
  <script>
      $(document).on("change keyup blur", "#chDiscount", function() {
          var main = $('#total-cost1').val();
          var disc = $('#chDiscount').val();
          var dec = (disc / 100).toFixed(2); //its convert 10 into 0.10
          var mult = main * dec; // gives the value for subtract from main value
          var discont = main - mult;
          $('#result1').val(discont);
      });
  </script>

@endsection
