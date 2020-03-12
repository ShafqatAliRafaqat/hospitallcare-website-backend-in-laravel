@extends('adminpanel.layout')
@section('title','Edit Dependent | HospitALL')
@section('styles')
  <link rel="stylesheet" href="{{ asset('backend/css/fileupload.css') }}">
@endsection
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      @include('adminpanel.notification')
      <!-- Form Elements -->
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Edit Customer Profile</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('dependents.update', $customer->id) }}" enctype="multipart/form-data">
                @csrf @method('put')
                @if($customer->card_id != NULL)
                <div class="form-group row">
                <label class="col-md-2 form-control-label">Card ID <span class="asterisk-blue">*</span></label>
                  <div class="col-md-10">
                    <input type="text" name="card_id" placeholder="Card ID"
                        class="form-control {{ $errors->has('card_id') ? 'is-invalid' : '' }}" value="{{ $customer->card_id }}">
                      @if($errors->has('card_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('card_id') }}</div>
                      @endif
                  </div>
                </div>

                @else
              <div class="form-group row">
              <label class="col-md-4 form-control-label">Does this Customer has a Card ID? </label>

              <div class="col-md-2">

                <label>Yes</label>
              <input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="yesCheck">
              <label>No</label>
            <input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="noCheck" checked="checked">
            </div>
               <div class="col-md-4 offset-md-2" id="ifYes" style="visibility:hidden">
                <input type="text" name="card_id" id='yes' placeholder="Card ID"
                      class="form-control {{ $errors->has('card_id') ? 'is-invalid' : '' }}" value="{{ old('card_id') }}">
                    @if($errors->has('card_id'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('card_id') }}</div>
                    @endif
                </div>
              </div>
                @endif
                <input type="hidden" name="parent_id" value="{{ $customer->parent_id}}">
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Customer Name  <span class="asterisk">*</span></label>
                  <div class="col-md-4">
                        <input type="text" name="name" placeholder="Customer name"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        value="{{ $customer->name }}" required>

                      @if($errors->has('name'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('name') }}</div>
                      @endif
                  </div>
                  <label class="col-md-2 form-control-label">Relation With Employee<span class="asterisk">*</span></label>
                  <div class="col-md-4">
                    <select name="relation" id="relation" class="form-control">
                      <option value="">Select Relation</option>
                        <option value="Parent"  {{ $customer->relation == "Parent" ? 'selected': '' }}>Parent</option>
                        <option value="Sibling" {{ $customer->relation == "Sibling" ? 'selected': '' }}>Sibling</option>
                        <option value="Husband" {{ $customer->relation == "Husband" ? 'selected': '' }}>Husband</option>
                        <option value="Wife"    {{ $customer->relation == "Wife" ? 'selected': '' }}>Wife</option>
                        <option value="Child"   {{ $customer->relation == "Child" ? 'selected': '' }}>Child</option>
                        <option value="Other"   {{ $customer->relation == "Other" ? 'selected': '' }}>Other</option>
                      </select>
                  </div>
                </div>

                <div class="form-group row">
                        <label class="col-md-2 form-control-label">Next Contact Date <span class="asterisk">*</span></label>
                        <div class="col-md-4">
                              <input type="date" name="next_contact_date" placeholder="next_contact_date"
                              class="form-control {{ $errors->has('next_contact_date') ? 'is-invalid' : '' }}"
                              value="{{ $customer->next_contact_date }}" required>

                            @if($errors->has('next_contact_date'))
                              <div class="invalid-feedback ml-3">{{ $errors->first('next_contact_date') }}</div>
                            @endif
                        </div>
                  <label class="col-md-2 form-control-label">Phone  <span class="asterisk">*</span></label>
                  <?php $phone = formatPhone($customer->phone);  ?>
                  <div class="col-md-4">
                        <input type="text" name="phone" data-mask="9999-9999999" placeholder="Phone"
                        class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                        value="{{ $customer->phone }}" >

                      @if($errors->has('phone'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('phone') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                        <label class="col-md-2 form-control-label">Marital Status <span class="asterisk">*</span></label>
                        <div class="col-md-2">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input id="unmarried" type="radio" value="0" name="marital_status" class="custom-control-input" {{ $customer->marital_status == 0 ? 'checked':'' }}>
                            <label for="unmarried" class="custom-control-label">Unmarried</label>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input id="married" type="radio" value="1" name="marital_status" class="custom-control-input" {{ $customer->marital_status == 1 ? 'checked':'' }}>
                            <label for="married" class="custom-control-label">married</label>
                          </div>
                        </div>

                  <label class="col-md-2 form-control-label">Gender  <span class="asterisk">*</span></label>
                  <div class="col-md-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="male" type="radio" value="0" name="gender" class="custom-control-input"
                      {{ $customer->gender == 0 ? 'checked':'' }}>
                      <label for="male" class="custom-control-label">Male</label>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="female" type="radio" value="1" name="gender" class="custom-control-input" {{ $customer->gender == 1 ? 'checked':'' }}>
                      <label for="female" class="custom-control-label">Female</label>
                    </div>
                  </div>
                </div>

                <div class="form-group row">
                 <div class="col-md-2 form-control-label">Select Status</div>
                  <div class="col-md-4">
                    <select name="status_id" id="" class="form-control">
                      <option value="0">Select Status</option>
                      @foreach($status as $s)
                        <option value="{{ $s->id }}" {{ $customer->status_id == $s->id ? 'selected': '' }}>{{ $s->name }}</option>
                      @endforeach
                    </select>
                    @if($errors->has('status_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('status_id') }}</div>
                    @endif
                  </div>
                </div>
                <hr>

                <ul class="nav nav-tabs customer-nav" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link " href="#organization-tab" role="tab" data-toggle="tab">Organization</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link " href="#treatment-tab" role="tab" data-toggle="tab" >Treatments</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#diagnostics-tab1" role="tab" data-toggle="tab">Diagnostics</a>
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
                          <a class="nav-link" href="#riskfactor-tab" role="tab" data-toggle="tab">Risk Factors</a>
                        </li>
                      </ul>

       <!-- Tab panes -->
       <div class="tab-content">
                <div role="tabpanel" class="tab-pane pt-3 in fade" id="organization-tab">
                  <div class="form-group row">
                    <div class="col-md-2 form-control-label" id="organization_id">Select Organization <span class="asterisk-blue">*</span></div>
                    <div class="col-md-4">
                      <select name="organization_id" id="organization" class="form-control">
                        <option value="">Select Organization</option>
                        @if(isset($organization))
                        @foreach($organization as $t)
                          <option value="{{ $t->id }}" {{ $customer->organization_id == $t->id ? 'selected': '' }} >{{ $t->name }}</option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                    <div class="col-md-2 form-control-label" id= "employee_code">Employee Code <span class="asterisk-blue">*</span></div>
                    <div class="col-md-4">
                    <input type="text" name="employee_code" placeholder="Enter Employee Code"
                          class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $customer->employee_code }}">
                    </div>
                </div>
              </div>
                <div role="tabpanel" class="tab-pane fade pt-3 in" id="treatment-tab">

                @include('adminpanel/includes/edit_customer')
                @endsection
                @include('adminpanel/includes/edit_customer_scripts')



