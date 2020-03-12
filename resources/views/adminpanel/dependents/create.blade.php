@extends('adminpanel.layout')
@section('title','Create Dependent | HospitALL')
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
            <h3 class="h6 text-uppercase mb-0">Dependent Profile</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" id="customer-form" action="{{ route('dependents.store') }}" enctype="multipart/form-data">
                @csrf
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
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Name <span class="asterisk">*</span></label>
                  <div class="col-md-4">
                        <input type="text" name="name" placeholder="Dependent name"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" required>

                        <input type="text" name="parent_id" hidden value="{{$customer->id}}">

                      @if($errors->has('name'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('name') }}</div>
                      @endif
                  </div>
                  <label class="col-md-2 form-control-label">Relation With Employee<span class="asterisk">*</span></label>
                  <div class="col-md-4">
                    <select name="relation" id="relation" class="form-control">
                      <option value="">Select Relation</option>
                        <option value="Parent">Parent</option>
                        <option value="Sibling">Sibling</option>
                        <option value="Husband">Husband</option>
                        <option value="Wife">Wife</option>
                        <option value="Child">Child</option>
                        <option value="Other">Other</option>
                      </select>
                  </div>
                </div>

                <div class="form-group row">
                        <label class="col-md-2 form-control-label">Follow Up <span class="asterisk">*</span></label>
                        <div class="col-md-4">
                              <input type="date" name="next_contact_date" placeholder="next_contact_date"
                              class="form-control {{ $errors->has('next_contact_date') ? 'is-invalid' : '' }}" value="" required>

                            @if($errors->has('next_contact_date'))
                              <div class="invalid-feedback ml-3">{{ $errors->first('next_contact_date') }}</div>
                            @endif
                        </div>
                  <label class="col-md-2 form-control-label">Phone <span class="asterisk">*</span></label>
                  <div class="col-md-4">
                        <input type="text" name="phone" data-mask="9999-9999999" placeholder="Phone"
                        class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ old('phone') }}">

                      @if($errors->has('phone'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('phone') }}</div>
                      @endif
                  </div>
                </div>
                <div class="form-group row">
                        <div class="col-md-2 form-control-label">Select Status <span class="asterisk">*</span></div>
                          <div class="col-md-4">
                            <select name="status_id" id="" class="form-control" required>
                              <option value="0">Select Status</option>
                              @foreach($status as $s)
                                <option value="{{ $s->id }}" {{ old('status_id') == $s->id ? 'selected': '' }}>{{ $s->name }}</option>
                              @endforeach
                            </select>
                            @if($errors->has('status_id'))
                                <div class="invalid-feedback ml-3">{{ $errors->first('status_id') }}</div>
                            @endif
                          </div>
                        </div>
                  <div class="form-group row">
                        <label class="col-md-2 form-control-label">Marital Status <span class="asterisk">*</span></label>
                        <div class="col-md-2">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input id="unmarried" type="radio" value="0" name="marital_status" class="custom-control-input" {{ old('name') == 0 ? 'checked':'' }} >
                            <label for="unmarried" class="custom-control-label">Unmarried</label>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input id="married" type="radio" value="1" name="marital_status" class="custom-control-input" {{ old('name') == 1 ? 'checked':'' }}>
                            <label for="married" class="custom-control-label">married</label>
                          </div>
                        </div>
                  <label class="col-md-2 form-control-label">Gender <span class="asterisk">*</span></label>
                  <div class="col-md-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="male" type="radio" value="0" name="gender" class="custom-control-input" {{ old('name') == 0 ? 'checked':'' }}>
                      <label for="male" class="custom-control-label">Male</label>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="female" type="radio" value="1" name="gender" class="custom-control-input" {{ old('name') == 1 ? 'checked':'' }}>
                      <label for="female" class="custom-control-label">Female</label>
                    </div>
                  </div>
                </div>
                @include('adminpanel/includes/create_customer')
                @endsection
                @include('adminpanel/includes/create_customer_scripts')
