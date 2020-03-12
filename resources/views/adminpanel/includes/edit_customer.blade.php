
                {{-- Treatment --}}
                @php
                $count      =   count($customer->treatments);
                @endphp
                @if($count>0)
                <?php $i=0;?>
                @foreach($customer->treatments as $t)
                <div id="dynamic_field">
                  <div id="treatment{{$i+1}}">
                <div class="form-group row" >
                  <div class="col-md-12 text-center form-control-label"><b>Treatment</b> </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Treatment <span class="asterisk-blue">*</span></div>
                  <div class="col-md-4">
                    <select name="treatment_id[]" id="treatment_id{{$i+100}}" class="form-control">
                      <option value="">Select Treatment</option>
                      @foreach($treatments as $treatment)
                      @if($t->parent_id == null)
                        <option value="{{ $treatment->id }}" {{ $t->id == $treatment->id ? 'selected': '' }}>{{ $treatment->name }}</option>
                      @endif
                      @if($t->parent_id != null)
                        <option value="{{ $treatment->id }}" {{ $t->parent_id == $treatment->id ? 'selected': '' }}>{{ $treatment->name }}</option>
                      @endif
                        @endforeach
                    </select>
                     @if($errors->has('treatment_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('treatment_id') }}</div>
                    @endif
                  </div>
                  <div class="col-md-2 form-control-label">Select Procedure <span class="asterisk-blue">*</span></div>
                  <div class="col-md-4">
                    <select name="procedure_id[]" id="procedure{{$i+100}}" class="form-control">
                      <option value="0">Consultation</option>
                      @foreach($procedures as $p)
                        <option value="{{ $p->id }}" {{ $t->id == $p->id ? 'selected': '' }}>{{ $p->name }}</option>
                      @endforeach
                    </select>
                    @if($errors->has('procedure_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('procedure_id') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Center <span class="asterisk-blue">*</span></div>
                  <div class="col-md-4">
                    <select name="hospital_id[]" id="centers{{$i+100}}" class="form-control">
                      <option value="">Select Center</option>
                      @foreach($centers as $c)
                        <option value="{{ $c->id }}" {{ $t->pivot->hospital_id == $c->id ? 'selected' : '' }}>{{ $c->center_name }}</option>
                      @endforeach
                    </select>
                    @if($errors->has('hospital_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('hospital_id') }}</div>
                    @endif
                  </div>
                  <div class="col-md-2 form-control-label">Select Doctor <span class="asterisk-blue">*</span></div>
                  <div class="col-md-4">
                    <select name="doctor_id[]" id="doctors{{$i+100}}" class="form-control">
                      <option value="">Select Doctor</option>
                      @foreach($doctors as $d)
                      <option value="{{ $d->id }}" {{ $t->pivot->doctor_id == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                      @endforeach
                    </select>
                    @if($errors->has('doctor_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('doctor_id') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Cost</label>
                  <div class="col-md-4" id="cost{{$i+100}}">
                        <input type="text" name="cost[]" placeholder="Treatment Cost"
                        class="form-control {{ $errors->has('$t->pivot->cost') ? 'is-invalid' : '' }}" value="{{ $t->pivot->cost }}">

                      @if($errors->has('cost'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('cost') }}</div>
                      @endif
                  </div>
                  @php
                    $appointment_date      = AppointmentTimeConvert($t->pivot->appointment_date);
                    $appointment_from      = $t->pivot->appointment_from;
                  @endphp
                  <label class="col-md-2 form-control-label">Appointment Date</label>
                  <div class="col-md-4">
                        <input type="datetime-local" name="appointment_date[]" placeholder="appointment_date"
                        class="form-control {{ $errors->has('appointment_date') ? 'is-invalid' : '' }}" value="{{ (isset($appointment_date) ? $appointment_date : '') }}">
                        <input type="hidden" name="appointment_from[]" value="{{ (isset($appointment_from) ? $appointment_from : 0) }}">
                      @if($errors->has('appointment_date'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('appointment_date') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-8">
                        @if($t->pivot->status == 1)
                        <b><p class="text-center" style="color:red;">Doctor has canceled this appointment.</p></b>
                        <b><p class="text-center" style="color:red;"> Enter new appointment date.</p></b>
                        @endif
                        @if($t->pivot->status == 2)
                        <b><p class="text-center" style="color:green;">Ongoing Procedure</p></b>
                        @endif
                        @if($t->pivot->status == 3)
                        <b><p class="text-center" style="color:red;">Customer has canceled this appointment.</p></b>
                        <b><p class="text-center" style="color:red;"> Enter new appointment date.</p></b>
                        @endif
                        @if($t->pivot->status == 4)
                        <b><p class="text-center" style="color:red;">Pending appointment of customer.</p></b>
                        <b><p class="text-center" style="color:red;"> Update appointment date.</p></b>
                        @endif

                    </div>
                  <div class="col-md-4">
                    <ul class="list-group schedule-box" id="schedule{{$i+100}}">
                    </ul>
                  </div>
                </div>
                <div class="form-group row">

                    <label class=" col-md-2 form-control-label">Discount %</label>
                    <div class="col-md-4">
                    <input type="number" name="treatment_discount[]" id="treatment_discount{{$i+100}}" class="form-control" value="0" />
                    </div>
                    <div class="offset-md-2 col-md-4">
                    <ul class="list-group schedule-box" id="schedule">
                    </ul>
                    </div>
                  </div>
                  <div class="form-group row">

                    <label class="col-md-2 form-control-label">Result</label>
                    <div class="col-md-4">
                    <input type="number" name="discounted_tcost[]" readonly id="tresult1{{$i+100}}" class="form-control" value="" />
                    </div>

                  </div>
                <?php if ($i == 0){?>
                    <div class="col-md-12 text-center form-control-label">
                      <button type="button" name="add" id="add" class="btn btn-success">Add More Treatment</button>
                    </div>
                    <hr>

                    <?php }else{ ?>
                    <div class="col-md-12 text-center form-control-label">
                        <button type="button" name="remove" id="treatment{{$i+1}}" class="btn btn-danger btn_remove">Remove</button>
                    </div>
                    <hr>
                  <?php } $i++?>
              </div>
                    </div>
              @endforeach
              @elseif($count==0)
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
                        <input type="text" name="cost[]" placeholder="Treatment Cost"
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
                  <div class="offset-md-8 col-md-4">
                    <ul class="list-group schedule-box" id="schedule">
                    </ul>
                  </div>
                </div>
                <div class="form-group row">

                    <label class=" col-md-2 form-control-label">Discount %</label>
                    <div class="col-md-4">
                    <input type="number" name="treatment_discount[]" id="treatment_discount" class="form-control" value="0" />
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
              @endif
                <!-- Treatment # 1 END-->
              </div>
        <div role="tabpanel" class="tab-pane active pt-3 in" id="moredetail-tab">
            <div class="form-group row">
              <label class="col-md-2 form-control-label">Email</label>
                <div class="col-md-4">
                  <input type="email" name="email" placeholder="Email"
                  class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ $customer->email }}">

                @if($errors->has('email'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('email') }}</div>
                @endif
                  </div>
                  <label class="col-md-2 form-control-label">Address</label>
                  <div class="col-md-4">
                        <input type="text" name="address" placeholder="address" id="autocomplete" onFocus="geolocate()"
                        class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" value="{{ $customer->address }}">
                        <input type="text" id="locality" class="city" hidden name="city" value="{{ $customer->city }}"/>
                      @if($errors->has('address'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('address') }}</div>
                      @endif
                  </div>
              </div>
              <div class="form-group row">
                <label class="col-md-2 form-control-label">Age</label>
                <div class="col-md-4">
                  <input type="number" name="age" placeholder="age" class="form-control {{ $errors->has('age') ? 'is-invalid' : '' }}"
                  value="{{ $customer->age }}" >
                  @if($errors->has('age'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('age') }}</div>
                  @endif
                </div>
                <label class="col-md-2 form-control-label">Weight</label>
                <div class="col-md-4">
                  <input type="number" name="weight" placeholder="weight"
                  class="form-control {{ $errors->has('weight') ? 'is-invalid' : '' }}"
                  value="{{ $customer->weight }}">
                  @if($errors->has('weight'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('weight') }}</div>
                  @endif
            </div>

          </div>
          <div class="form-group row">
            <label class="col-md-2 form-control-label">Height</label>
            <div class="col-md-4">
                  <input type="number" name="height" placeholder="height" step="0.01"
                  class="form-control {{ $errors->has('height') ? 'is-invalid' : '' }}"
                  value="{{ $customer->height }}">

                @if($errors->has('height'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('height') }}</div>
                @endif
            </div>

              <label class="col-md-2 form-control-label">Blood Group</label>
              <div class="col-md-4">
              <select name="blood_group_id" id="" class="form-control">
                <option value="">Select Blood Group</option>
                @foreach($blood_groups as $b)
                  <option value="{{ $b->id }}" {{ $customer->blood_group_id == $b->id ? 'selected': '' }}>{{ $b->name }}</option>
                @endforeach
              </select>
              @if($errors->has('blood_group_id'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('blood_group_id') }}</div>
              @endif
            </div>
          </div>

        </div>
        <div role="tabpanel" class="tab-pane fade pt-3 in" id="doctornotes-tab">
        @if(count($doctor_notes)>0)
        <?php $i=1?>
        @foreach($doctor_notes as $notes)
        <div class="form-group row">
            <label class="col-md-2 form-control-label">Doctor Notes # {{$i++}}</label>
            <div class="col-md-10">
                <textarea readonly placeholder="Enter Details" class="form-control" name="doctor_notes[]" id="" cols="1" rows="5">{{$notes->notes}}</textarea>
                @if($errors->has('doctor_notes'))
                <div class="invalid-feedback ml-3">{{ $errors->first('doctor_notes') }}</div>
                @endif
            </div>
        </div>
        @endforeach
        @endif
        </div>
        <div role="tabpanel" class="tab-pane fade pt-3 in" id="allergies-tab">

          <?php $i = 0 ?>
          <?php $j = 0 ?>
        @if(count($allergies)>0)
        @foreach($allergies as $allergy)
        <div id="dynamic_field_allergies_notes">
                <div class="form-group row"  id="allergies{{$i+1}}">
                <label class="col-md-2 form-control-label">Allergies</label>
                  <div class="col-md-8">
                  <input type="text" placeholder="Enter Details" class="form-control" name="allergies_notes[]" value="{{$allergy->notes}}"/>
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
                  <input type="text" placeholder="Enter Details" class="form-control" name="allergies_notes[]" id="" />
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
        <div role="tabpanel" class="tab-pane fade pt-3 in" id="riskfactor-tab">
        <?php $i = 0 ?>
          <?php $j = 0 ?>
        @if(count($riskfactor)>0)
        @foreach($riskfactor as $riskfactors)
        <div id="dynamic_field_riskfactor_notes">
                <div class="form-group row"  id="riskfactor{{$i+1}}">
                <label class="col-md-2 form-control-label">Risk Factor</label>
                  <div class="col-md-8">
                  <input type="text" placeholder="Enter Details" class="form-control" name="riskfactor_notes[]" value="{{$riskfactors->notes}}"/>
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
                  <input type="text" placeholder="Enter Details" class="form-control" name="riskfactor_notes[]" id="" />
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
        <hr>
        </div>
    <div class="form-group row">
    <div class="col-md-2 form-control-label">Select Owner <span class="asterisk">*</span></div>
    <div class="col-md-10">
      <select name="patient_coordinator_id" class="form-control selectpicker">
        @php
          $id = Auth::user()->find($customer->patient_coordinator_id);
          $id = isset($id)? $id : Auth::user();
        @endphp
        @if(isset($id))
        @foreach($users as $user)
            <option data-value="$user->user_id" value="{{ $user->user_id }}"
              {{ ($user->user_id == $id->id)? 'selected' : ''}}
              >
              {{ ($user->name) }}
            </option>
        @endforeach
        @else
          @foreach($users as $user)
              <option data-value="$user->user_id" value="{{ $user->user_id }}"
                > {{ ($user->name) }}
              </option>
          @endforeach
        @endif
      </select>
        @if($errors->has('patient_coordinator_id'))
          <div class="invalid-feedback ml-3">{{ $errors->first('patient_coordinator_id') }}</div>
        @endif
    </div>
  </div>

        <div class="form-group row">
          <label class="col-md-2 form-control-label">Notes <span class="asterisk">*</span></label>
          <div class="col-md-10">
              <textarea placeholder="Enter Details" class="form-control tiny" name="notes" id="" cols="30" rows="5">{{ $customer->notes }}</textarea>
              @if($errors->has('notes'))
              <div class="invalid-feedback ml-3">{{ $errors->first('notes') }}</div>
              @endif
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-10 ml-auto">
            <button type="submit" class="btn btn-primary">Update Customer Profile</button>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
</section>
</div>
