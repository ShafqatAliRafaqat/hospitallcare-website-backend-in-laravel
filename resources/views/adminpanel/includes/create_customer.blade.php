                <ul class="nav nav-tabs customer-nav" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link " href="#organization-tab" role="tab" data-toggle="tab">Organization</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link " href="#treatment-tab" role="tab" data-toggle="tab" >Treatments</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#diagnostics-tab1" role="tab" data-toggle="tab">Diagnostics # 1</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#diagnostics-tab2" role="tab" data-toggle="tab">Diagnostics # 2</a>
                        </li>
                        <li class="nav-item">
                                <a class="nav-link active" href="#moredetail-tab" role="tab" data-toggle="tab">More Detail</a>
                        </li>
                        <li class="nav-item">
                                <a class="nav-link" href="#doctornotes-tab" role="tab" data-toggle="tab">Doctor Notes</a>
                        </li>
                        <li class="nav-item">
                                <a class="nav-link" href="#allergies-tab" role="tab" data-toggle="tab">Allergies</a>
                        </li>
                        <li class="nav-item">
                                <a class="nav-link" href="#riskfactor-tab" role="tab" data-toggle="tab">Risk Factor</a>
                        </li>

                      </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                      <div role="tabpanel" class="tab-pane pt-3 in " id="organization-tab">
                        <div class="form-group row">
                                <div class="col-md-2 form-control-label" id="organization_id">Select Organization <span class="asterisk-blue">*</span></div>
                                <div class="col-md-4">
                                  <select name="organization_id" id="organization" class="form-control">
                                    <option value="">Select Organization</option>
                                    @if(isset($organization))
                                    @foreach($organization as $t)
                                      <option value="{{ $t->id }}" >{{ $t->name }}</option>
                                    @endforeach
                                    @endif
                                  </select>
                                </div>
                                <div class="col-md-2 form-control-label" id= "employee_code">Employee Code <span class="asterisk-blue">*</span></div>
                                <div class="col-md-4">
                                <input type="text" name="employee_code" placeholder="Enter Employee Code"
                                      class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('employee_code') }}">
                                </div>
                              </div>
                            </div>
                        <div role="tabpanel" class="tab-pane fade pt-3 in" id="treatment-tab">
                <!-- Treatment # 1 -->
                <div id="dynamic_field">
                <div class="form-group row">
                  <div class="col-md-12 text-center form-control-label">Treatment # 1</div>
                </div>
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Treatment <span class="asterisk-blue">*</span></div>
                  <div class="col-md-4">
                    <select name="treatment_id[]" id="treatment" class="form-control">
                      <option value="">Select Treatment</option>
                      @foreach($treatments as $t)
                        <option value="{{ $t->id }}" {{ old('treatment_id') == $t->id ? 'selected': '' }}>{{ $t->name }}</option>
                      @endforeach
                    </select>
                     @if($errors->has('treatment_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('treatment_id') }}</div>
                    @endif
                  </div>
                  <div class="col-md-2 form-control-label">Select Procedure <span class="asterisk-blue">*</span></div>
                  <div class="col-md-4">
                    <select name="procedure_id[]" id="procedure" class="form-control">
                      <option value="">Select Procedure</option>
                    </select>
                    @if($errors->has('procedure_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('procedure_id') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Center <span class="asterisk-blue">*</span></div>
                  <div class="col-md-4">
                    <select name="hospital_id[]" id="centers" class="form-control">
                      <option value="">Select Center</option>
                    </select>
                    @if($errors->has('hospital_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('hospital_id') }}</div>
                    @endif
                  </div>
                  <div class="col-md-2 form-control-label">Select Doctor <span class="asterisk-blue">*</span></div>
                  <div class="col-md-4">
                    <select name="doctor_id[]" id="doctors" class="form-control">
                      <option value="">Select Doctor</option>
                    </select>
                    @if($errors->has('doctor_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('doctor_id') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Cost</label>
                  <div class="col-md-4" id="cost">
                        <input type="number" name="cost[]" placeholder="Treatment Cost"
                        class="form-control {{ $errors->has('cost') ? 'is-invalid' : '' }}" value="0" required>

                      @if($errors->has('cost'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('cost') }}</div>
                      @endif
                  </div>

                  <label class="col-md-2 form-control-label">Appointment Date</label>
                  <div class="col-md-4">
                        <input type="datetime-local" name="appointment_date[]" placeholder="appointment_date"
                        class="form-control {{ $errors->has('appointment_date') ? 'is-invalid' : '' }}" value="">
                        <input type="hidden" name="appointment_from[]" value="0">
                      @if($errors->has('appointment_date'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('appointment_date') }}</div>
                      @endif
                  </div>
                </div>

                  <div class="form-group row">

                  <label class=" col-md-2 form-control-label">Discount %</label>
                  <div class="col-md-4">
                  <input type="number" name="treatment_discount[]" id="treatment_discount1" class="form-control" value="0" />
                  </div>
                  <div class="offset-md-2 col-md-4">
                  <ul class="list-group schedule-box" id="schedule">
                  </ul>
                  </div>
                </div>
                <div class="form-group row">

                  <label class="col-md-2 form-control-label">Result</label>
                  <div class="col-md-4">
                  <input type="number" name="discounted_tcost[]" readonly id="tresult1" class="form-control" value="" />
                  </div>

                </div>
                <div class="form-group row">
                  <div class="col-md-12 text-center">
                    <button type="button" name="add" id="add" class="btn btn-success">Add More Treatment</button>
                  </div>
                </div>
              </div>
                <!-- Treatment # 1 END-->
                <hr>
                </div>
                <div role="tabpanel" class="tab-pane pt-3 fade" id="diagnostics-tab1">
                <!-- Diagnostics # 1 -->

                <div class="form-group row">
                  <div class="col-md-12 text-center form-control-label">Diagnostic # 1</div>
                </div>
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Lab <span class="asterisk-blue">*</span></div>
                  <div class="col-md-10">
                    <select name="lab_id1" id="lab_id1" class="form-control">
                      <option value="">Select Lab</option>
                      @foreach($lab as $l)
                        <option value="{{ $l->id }}" {{ old('diagnostic_id1') == $l->id ? 'selected': '' }}>{{ $l->name }}</option>
                      @endforeach
                    </select>
                    @if($errors->has('lab_id1'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('lab_id1') }}</div>
                    @endif
                  </div>

                </div>

                <div id="dynamic_field_diagnostic">
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Diagnostic <span class="asterisk-blue">*</span></div>
                  <div class="col-md-3">
                    <select name="diagnostic_id1[]" id="diagnostic" class="form-control">
                      <option value="">Select Diagnostic</option>
                    </select>
                     @if($errors->has('diagnostic_id1'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('diagnostic_id1') }}</div>
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
                    <button type="button" name="add_d" id="add_d" class="btn btn-success">Add More Diagnostic</button>
                  </div>
                </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Appointment Date</label>
                  <div class="col-md-3">
                    <input type="datetime-local" name="diagnostic_appointment_date1" placeholder="appointment_date"
                        class="form-control {{ $errors->has('diagnostic_appointment_date1') ? 'is-invalid' : '' }}" value="">
                      @if($errors->has('diagnostic_appointment_date1'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('diagnostic_appointment_date1') }}</div>
                      @endif
                  </div>
                  <label class="col-md-2 form-control-label">Total Cost</label>
                  <div class="col-md-3">
                  <input type="number" readonly class="total form-control" value="" id="total-cost1" />
                  </div>
                  <div class="col-md-2 text-center">
                   <button type="button" name="sum-btn" id="sum-btn" class="btn btn-info">Sum</button>
                  </div>
                </div>
                <div class="form-group row">
                <label class="col-md-2 form-control-label">Home Sampling</label>
                  <div class="col-md-3">
                    <div class="custom-control custom-checkbox">
                      <input id="home_sampling" value="1" type="checkbox" name="home_sampling1" class="custom-control-input">
                      <label for="home_sampling" class="custom-control-label">Home Sample</label>
                    </div>
                  </div>
                  <label class="col-md-2 form-control-label">Discount %</label>
                  <div class="col-md-3">
                  <input type="number" name="discount1" id="chDiscount1" class="form-control" value="0" />
                  </div>
                </div>
                <div class="form-group row">

                  <label class=" offset-md-5 col-md-2 form-control-label">Result</label>
                  <div class="col-md-3">
                  <input type="number" readonly id="result1" class="form-control" value="" />
                  </div>

                </div>

              <hr>
            </div>
                <!-- Diagnostics # 1 END-->

                <div role="tabpanel" class="tab-pane pt-3 fade" id="diagnostics-tab2">
               <!-- Diagnostics # 2 -->
                <div class="form-group row">
                  <div class="col-md-12 text-center form-control-label">Diagnostic # 2</div>
                </div>
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Lab <span class="asterisk-blue">*</span></div>
                  <div class="col-md-10">
                    <select name="lab_id2" id="lab_id2" class="form-control">
                      <option value="">Select Lab</option>
                      @foreach($lab as $l)
                        <option value="{{ $l->id }}" {{ old('diagnostic_id') == $l->id ? 'selected': '' }}>{{ $l->name }}</option>
                      @endforeach
                    </select>
                    @if($errors->has('lab_id2'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('lab_id2') }}</div>
                    @endif
                  </div>

                </div>

                <div id="dynamic_field_diagnostic2">
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Diagnostic <span class="asterisk-blue">*</span></div>
                  <div class="col-md-3">
                    <select name="diagnostic_id2[]" id="diagnostic200" class="form-control">
                      <option value="">Select Diagnostic</option>
                    </select>
                     @if($errors->has('diagnostic_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('diagnostic_id') }}</div>
                    @endif
                  </div>
                  <label class="col-md-2 form-control-label">Diagnostic Cost</label>
                  <div class="col-md-3" id="diagnostics_cost200">
                    <input type="number" name="diagnostics_cost2[]" placeholder="Diagnostic Cost"
                        class="form-control qty2 {{ $errors->has('diagnostics_cost200') ? 'is-invalid' : '' }}" value="0" required>
                        <input type="hidden" name="diagnostics_appointment_from2[]" value="0">

                      @if($errors->has('diagnostics_cost200'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('diagnostics_cost200') }}</div>
                      @endif
                  </div>
                  <div class="col-md-2 text-center">
                    <button type="button" name="add_d2" id="add_d2" class="btn btn-success">Add More Diagnostic</button>
                  </div>
                </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Appointment Date</label>
                  <div class="col-md-3">
                    <input type="datetime-local" name="diagnostic_appointment_date2" placeholder="appointment_date"
                        class="form-control {{ $errors->has('diagnostic_appointment_date2') ? 'is-invalid' : '' }}" value="">
                    @if($errors->has('diagnostic_appointment_date2'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('diagnostic_appointment_date2') }}</div>
                    @endif
                  </div>
                  <label class="col-md-2 form-control-label">Total Cost</label>
                  <div class="col-md-3">
                  <input type="number" readonly class="total2 form-control" value="" id="total-cost2" />
                  </div>
                  <div class="col-md-2 text-center">
                   <button type="button" name="sum-btn2" id="sum-btn2" class="btn btn-info">Sum</button>
                  </div>
                </div>
                <div class="form-group row">
                <label class="col-md-2 form-control-label">Home Sampling</label>
                  <div class="col-md-3">
                    <div class="custom-control custom-checkbox">
                      <input id="home_sampling2" value="1" type="checkbox" name="home_sampling2" class="custom-control-input">
                      <label for="home_sampling2" class="custom-control-label">Home Sample</label>
                    </div>
                  </div>
                  <label class="col-md-2 form-control-label">Discount %</label>
                  <div class="col-md-3">
                  <input type="number" name="discount2" id="chDiscount2" class="form-control" value="0" />
                  </div>

                </div>
                <div class="form-group row">

                  <label class=" offset-md-5 col-md-2 form-control-label">Result</label>
                  <div class="col-md-3">
                  <input type="number" readonly id="result2" class="form-control" value="" />
                  </div>
                </div>

              <hr>
            </div>

            <div role="tabpanel" class="tab-pane active pt-3 in" id="moredetail-tab">
              <div class="form-group row">
              <label class="col-md-2 form-control-label">Email</label>
              <div class="col-md-4">
                    <input type="email" name="email" placeholder="Email"
                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ (isset($customer)?$customer->email:null) }}">

                  @if($errors->has('email'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('email') }}</div>
                  @endif
              </div>
              <label class="col-md-2 form-control-label">Address</label>
              <div class="col-md-4">
                    <input type="text" name="address" placeholder="address" id="autocomplete" onFocus="geolocate()"
                    class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" value="{{ old('address') }}">
                    <input type="text" id="locality" class="city" hidden name="city" value=""/>
                  @if($errors->has('address'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('address') }}</div>
                  @endif
              </div>
          </div>
          <div class="form-group row">
            <label class="col-md-2 form-control-label">Age</label>
                <div class="col-md-4">
                  <input type="number" name="age" placeholder="age"
                  class="form-control {{ $errors->has('age') ? 'is-invalid' : '' }}" value="{{ old('age') }}">

                    @if($errors->has('age'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('age') }}</div>
                    @endif
                  </div>

                  <label class="col-md-2 form-control-label">Weight</label>
                  <div class="col-md-4">
                        <input type="number" name="weight" placeholder="weight"
                        class="form-control {{ $errors->has('weight') ? 'is-invalid' : '' }}" value="{{ old('weight') }}">

                      @if($errors->has('weight'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('weight') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Height</label>
                    <div class="col-md-4">
                      <input type="number" step="0.01" name="height" placeholder="height"
                          class="form-control {{ $errors->has('height') ? 'is-invalid' : '' }}" value="{{ old('height') }}">
                        @if($errors->has('height'))
                          <div class="invalid-feedback ml-3">{{ $errors->first('height') }}</div>
                        @endif
                    </div>

                    <label class="col-md-2 form-control-label">Blood Group</label>
                    <div class="col-md-4">
                    <select name="blood_group_id" id="" class="form-control">
                      <option value="">Select Blood Group</option>
                      @foreach($blood_groups as $b)
                        <option value="{{ $b->id }}" {{ old('blood_group_id') == $b->id ? 'selected': '' }}>{{ $b->name }}</option>
                      @endforeach
                    </select>
                    @if($errors->has('blood_group_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('blood_group_id') }}</div>
                    @endif
                  </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade pt-3 in" id="doctornotes-tab">
              <div class="form-group row">
                <label class="col-md-2 form-control-label">Doctor Notes</label>
                <div class="col-md-10">
                    <textarea placeholder="Enter Details" class="form-control" name="doctor_notes" id="" cols="30" rows="5" readonly></textarea>
                    @if($errors->has('doctor_notes'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('doctor_notes') }}</div>
                    @endif
                </div>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane fade pt-3 in" id="allergies-tab">
            <div id="dynamic_field_allergies_notes">
                <div class="form-group row">
                <label class="col-md-2 form-control-label">Allergies</label>
                  <div class="col-md-8">
                  <input type="text" placeholder="Enter Details" class="form-control" name="allergies_notes[]" id="" />
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
            <div role="tabpanel" class="tab-pane fade pt-3 in" id="riskfactor-tab">
            <div id="dynamic_field_riskfactor_notes">
                <div class="form-group row">
                <label class="col-md-2 form-control-label">Risk Factor</label>
                  <div class="col-md-8">
                  <input type="text" placeholder="Enter Details" class="form-control" name="riskfactor_notes[]" id="" />
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

          <hr>
        </div>
                <!-- Diagnostics # 2 END-->

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Patient Owner</label>
                  <div class="col-md-10">
                        <input type="text" name="patient_coordinator_id" placeholder="{{ Auth::user()->name }}"
                        class="form-control {{ $errors->has('patient_coordinator_id') ? 'is-invalid' : '' }}" value="{{ Auth::user()->name  }}" readonly>
                      @if($errors->has('patient_coordinator_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('patient_coordinator_id') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Notes <span class="asterisk">*</span></label>
                  <div class="col-md-10">
                      <textarea placeholder="Enter Details" class="form-control tiny" name="notes" id="" cols="30" rows="5">
                          <p>What:</p>
                          @if(isset($customer_notes))
                          @foreach($customer_notes as $notes)
                          {{$notes->notes}}
                          @endforeach
                      @endif
                        </textarea>
                      @if($errors->has('notes'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('notes') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-10 ml-auto">
                    <button type="submit" class="btn btn-primary">Save Customer Profile</button>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
