@extends('adminpanel.layout')
@section('title', 'Create Doctor | HospitALL')
@section('styles')
<link rel="stylesheet" href="{{ asset('backend/css/fileupload.css') }}">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Create Doctor</h3>
          </div>
          <div class="card-body">
            @foreach($errors as $e)
            <h1>{{$e}}</h1>
            @endforeach
            <form class="form-horizontal" method="post" id="doctor-form" action="{{ route('doctors.store') }}" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="lat" id="lat">
              <input type="hidden" name="lng" id="lng">
              <input type="hidden" name="city_name" id="city_name">
              <div class="form-group row">
                <label class="col-md-2 form-control-label">Add Focus Area  <span class="asterisk">*</span></label>
                <div class="col-md-4">
                  <input class="form-control" placeholder="Enter Focus Area Comma Seprated" name="focus_area">
                </div>
                @if(!empty($focus_area))
                <div class="invalid-feedback ml-3">{{ $focus_area }}</div>
                @endif
                  <label class="col-md-2 form-control-label">Gender <span class="asterisk">*</span></label>
                  <div class="col-md-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="male" type="radio" value="1" name="gender" class="custom-control-input" checked>
                      <label for="male" class="custom-control-label">Male</label>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="female" type="radio" value="0" name="gender" class="custom-control-input">
                      <label for="female" class="custom-control-label">Female</label>
                    </div>
                  </div>
              </div>
              <?php $years = get_years();?>
              <div class="form-group row">
                <label class="col-md-2 form-control-label">Doctor Name  <span class="asterisk">*</span></label>
                <div class="col-md-4">
                  <input type="text" name="name" placeholder="Doctor First Name"
                  class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" required>
                  @if($errors->has('name'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('name') }}</div>
                  @endif
                </div>
                <label class="col-md-2 form-control-label">Last Name</label>
                <div class="col-md-4">
                  <input type="text" name="last_name" placeholder="Doctor Last Name"
                  class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" value="{{ old('last_name') }}">
                  @if($errors->has('last_name'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('last_name') }}</div>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-2 form-control-label">PMDC No.<span class="asterisk-blue">*</span></label>
                <div class="col-md-4">
                  <input type="text" name="pmdc" placeholder="PMDC No."
                  class="form-control {{ $errors->has('pmdc') ? 'is-invalid' : '' }}" value="{{ old('pmdc') }}">

                  @if($errors->has('pmdc'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('pmdc') }}</div>
                  @endif
                </div>

                <label class="col-md-2 form-control-label">Doctor's Phone</label>
                <div class="col-md-4">
                  <input type="text" name="phone" data-mask="9999-9999999" placeholder="Phone"
                  class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ old('phone') }}">

                  @if($errors->has('phone'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('phone') }}</div>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-2 form-control-label">Pictures</label>
                <div class="col-md-10 imageupload">
                  <div class="file-tab panel-body">
                    <label class="btn btn-success btn-file">
                      <span>File</span>
                      <!-- The file is stored here. -->
                      <input type="file" name="picture">
                    </label>
                    <button type="button" class="btn btn-danger">Remove</button>
                  </div>
                </div>
              </div>
              <!-- ABOUT -->
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">About Doctor</label>
                  <div class="col-md-10">
                    <textarea class="form-control" name="about" id="" cols="30" rows="4">{{ old('about')}}</textarea>
                    @if($errors->has('about'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('about') }}</div>
                    @endif
                  </div>
                </div>
              <div class="form-group row">
                <label class="col-md-2 form-control-label">Created By</label>
                <div class="col-md-10">
                  <input type="text" name="created_by" placeholder="{{ Auth::user()->name }}"
                  class="form-control {{ $errors->has('created_by') ? 'is-invalid' : '' }}" value="{{ Auth::user()->name  }}" readonly>
                  @if($errors->has('created_by'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('created_by') }}</div>
                  @endif
                </div>
              </div>
              <hr>
              <ul class="nav nav-tabs customer-nav" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" href="#address-tab" role="tab" data-toggle="tab" >Address *</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="#treatments-tab" role="tab" data-toggle="tab">Centers &amp; Treatments</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="#qualification-tab" role="tab" data-toggle="tab">Qualification</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="#certification-tab" role="tab" data-toggle="tab">Certification</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="#seo-tab" role="tab" data-toggle="tab">SEO Details</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="#partnership-tab" role="tab" data-toggle="tab">Partnership Details</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="#moredetail-tab" role="tab" data-toggle="tab">Add More Details</a>
                </li>
              </ul>
              <!-- Tab panes -->
              <div class="tab-content">
               <div role="tabpanel" class="tab-pane pt-3 in active" id="address-tab">
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Address  <span class="asterisk">*</span></label>
                  <div class="col-md-10 mb-2">
                    <input type="text" name="address" id="address" placeholder="Address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" value="{{ old('address') }}" required>
                    @if($errors->has('address'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('address') }}</div>
                    @endif
                  </div>
                  <div class="col-md-10  offset-2 map-height" id="locationField">
                    <div class="pac-card" id="pac-card">
                      <div>
                        <div id="title">
                          Autocomplete search
                        </div>
                        <div id="type-selector" class="pac-controls">
                          <input type="radio" name="type" id="changetype-all" checked="checked">
                          <label for="changetype-all">All</label>

                          <input type="radio" name="type" id="changetype-establishment">
                          <label for="changetype-establishment">Establishments</label>

                          <input type="radio" name="type" id="changetype-address">
                          <label for="changetype-address">Addresses</label>

                          <input type="radio" name="type" id="changetype-geocode">
                          <label for="changetype-geocode">Geocodes</label>
                        </div>
                        <div id="strict-bounds-selector" class="pac-controls">
                          <input type="checkbox" id="use-strict-bounds" value="">
                          <label for="use-strict-bounds">Strict Bounds</label>
                        </div>
                      </div>
                      <div id="pac-container">
                        <input id="pac-input" type="text"
                        placeholder="Enter a location">
                      </div>
                    </div>
                    <div id="map"></div>
                    <div id="infowindow-content">
                      <img src="" width="16" height="16" id="place-icon">
                      <span id="place-name"  class="title"></span><br>
                      <span id="place-address"></span>
                    </div>
                  </div>

                </div>
              </div>

              <div role="tabpanel" class="tab-pane pt-3 in fade" id="treatments-tab">
                <!-- Schedule 2 -->
                <div class="form-group row"><div class="col-md-12 text-center form-control-label">Centers &amp; Treatments</div></div>
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Center <span class="asterisk-blue">*</span></div>
                  <div class="col-md-10">
                    <select name="centers[]" class="form-control selectpicker" multiple data-live-search="true" required>
                      @foreach($centers as $c)
                      <option value="{{ $c->id }}">{{ $c->center_name }}</option>
                      @endforeach
                    </select>
                    @if($errors->has('centers'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('centers') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Specialization <span class="asterisk">*</span></div>
                  <div class="col-md-10">
                    <select name="specializations[]" id="specializationselect" class="form-control selectpicker" data-live-search="true" multiple required>
                      @foreach($specialities as $s)
                      <option value="{{ $s->id }}">{{ $s->name }}</option>
                      @endforeach
                    </select>
                    @if($errors->has('specializations'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('specializations') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                <div class="col-md-2 form-control-label">Select Procedure <span class="asterisk">*</span></div>
                  <div class="col-md-10">
                    <select name="procedures[]" id="procedure" class="form-control selectpicker" multiple required>
                      <option value="">Select Procedure</option>
                    </select>
                    @if($errors->has('procedures'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('procedures') }}</div>
                    @endif
                  </div>
                </div>
                
              </div><!-- End Of Treatments -->

              <div role="tabpanel" class="tab-pane pt-3 in fade" id="seo-tab">
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Speciality <span class="asterisk-blue">*</span></div>
                  <div class="col-md-10">
                    <select name="meta_speciality" class="form-control selectpicker"  data-live-search="true">
                      <option value="">Select a Specialization</option>
                      @foreach($specialities as $s)
                      <option value="{{ $s->name }}">{{ $s->name }}</option>
                      @endforeach
                    </select>
                    @if($errors->has('meta_speciality'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('meta_speciality') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Meta Title</label>
                  <div class="col-md-10">
                    <input type="text" name="meta_title" placeholder="SEO Meta Title"
                    class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" value="{{ old('meta_title') }}">

                    @if($errors->has('meta_title'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('meta_title') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Meta Description</label>
                  <div class="col-md-10">
                    <input type="text" name="meta_description" placeholder="SEO Meta Description"
                    class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" value="{{ old('meta_description') }}">

                    @if($errors->has('meta_description'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('meta_description') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">URL</label>
                  <div class="col-md-10">
                    <input type="text" name="url" placeholder="Enter URL"
                    class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" value="{{ old('url') }}">
                    @if($errors->has('url'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('url') }}</div>
                    @endif
                  </div>
                </div>
              </div>
              {{-- Qualification Tab Pane --}}
              <div role="tabpanel" class="tab-pane pt-3 in fade" id="qualification-tab">
                <div id="dynamic_field_qualification">
                  <div class="form-group row">
                    <label class="col-md-2 form-control-label">Degree Title <span class="asterisk-blue">*</span></label>
                    <div class="col-md-4">
                      <input type="text"  name="qua_degree[]" id="qua_degree" list="degrees" placeholder="Enter Degree Name"
                      class="form-control {{ $errors->has('qua_degree') ? 'is-invalid' : '' }}" value="{{ old('qua_degree') }}">
                      <datalist id="degrees">
                        @foreach($degrees as $d)
                        <option id="{{ $d->name }}" data-value="{{ $d->name }}">{{ $d->name }}</option>
                        @endforeach
                      </datalist>
                      @if($errors->has('qua_degree'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('qua_degree') }}</div>
                      @endif
                    </div>
                    <label class="col-md-2 form-control-label">University</label>
                    <div class="col-md-4">
                      <input type="text" name="qua_university[]" id="qua_university" list="universities" placeholder="Enter University Name"
                      class="form-control {{ $errors->has('qua_university') ? 'is-invalid' : '' }}" value="{{ old('qua_university') }}">
                      <datalist id="universities">
                        @foreach($universities as $u)
                        <option data-value="{{ $u->name }}">{{ $u->name }}</option>
                        @endforeach
                      </datalist>
                      @if($errors->has('qua_university'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('qua_university') }}</div>
                      @endif
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-2 form-control-label">Country</label>
                    <div class="col-md-4">
                      <select  name="qua_country[]" id="qua_country" class="form-control selectpicker" data-live-search="true">
                        <option value="">Select Country</option>
                        @foreach($countries as $c)
                        <option value="{{ $c->nicename }}">{{ $c->nicename }}</option>
                        @endforeach
                      </select>
                      @if($errors->has('qua_country'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('qua_country') }}</div>
                      @endif
                    </div>
                    <label class="col-md-2 form-control-label">Graduation Year</label>
                    <div class="col-md-4">
                      <select  name="qua_graduation_year[]" class="form-control selectpicker" data-live-search="true">
                        <option value="">Select Year</option>
                        <?php foreach($years as $year) : ?>
                          <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                        <?php endforeach; ?>
                      </select>
                      @if($errors->has('qua_graduation_year'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('qua_graduation_year') }}</div>
                      @endif
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 text-center form-control-label">
                      <button type="button" name="add-qualification" id="add-qualification" class="btn btn-success">Add More Qualification</button>
                    </div>
                    <div class="col-md-4"></div>
                  </div>
                </div>
              </div>
              {{-- End Qualification Tab Pane --}}
              {{-- Certification Tab Pane --}}
              <div role="tabpanel" class="tab-pane pt-3 in fade" id="certification-tab">
                <div id="dynamic_field_certification">
                  <div class="form-group row">
                    <label class="col-md-2 form-control-label">Certification Title <span class="asterisk-blue">*</span></label>
                    <div class="col-md-4">
                      <input type="text" name="cer_degree[]" id="cer_degree" list="degrees" placeholder="Enter Certification Name" class="form-control {{ $errors->has('cer_degree') ? 'is-invalid' : '' }}" value="{{ old('cer_degree') }}">
                      <datalist id="degrees">
                        @foreach($degrees as $d)
                        <option id="{{ $d->name }}" data-value="{{ $d->name }}">{{ $d->name }}</option>
                        @endforeach
                      </datalist>
                      @if($errors->has('cer_degree'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('cer_degree') }}</div>
                      @endif
                    </div>
                    <label class="col-md-2 form-control-label">Institute</label>
                    <div class="col-md-4">
                      <input type="text" name="cer_university[]" id="cer_university" list="universities" placeholder="Enter Institute Name"
                      class="form-control {{ $errors->has('cer_university') ? 'is-invalid' : '' }}" value="{{ old('cer_university') }}">
                      <datalist id="universities">
                        @foreach($universities as $u)
                        <option data-value="{{ $u->name }}">{{ $u->name }}</option>
                        @endforeach
                      </datalist>
                      @if($errors->has('cer_university'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('cer_university') }}</div>
                      @endif
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-2 form-control-label">Country</label>
                    <div class="col-md-4">
                      <select  name="cer_country[]" id="cer_country" class="form-control selectpicker" data-live-search="true">
                        <option value="">Select Country</option>
                        @foreach($countries as $c)
                        <option value="{{ $c->nicename }}">{{ $c->nicename }}</option>
                        @endforeach
                      </select>
                      @if($errors->has('cer_country'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('cer_country') }}</div>
                      @endif
                    </div>
                    <label class="col-md-2 form-control-label">Graduation Year</label>
                    <div class="col-md-4">
                      <select  name="cer_graduation_year[]" class="form-control selectpicker" data-live-search="true">
                        <option value="">Select Year</option>
                        <?php foreach($years as $year) : ?>
                          <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                        <?php endforeach; ?>
                      </select>
                      @if($errors->has('cer_graduation_year'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('cer_graduation_year') }}</div>
                      @endif
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 text-center form-control-label">
                      <button type="button" name="add-certification" id="add-certification" class="btn btn-success">Add More Certification</button>
                    </div>
                    <div class="col-md-4"></div>
                  </div>
                </div>
              </div>
              <!-- End Certification Tab Pane -->
              <div role="tabpanel" class="tab-pane pt-3 in fade" id="partnership-tab">
                <div class="row">
                  <div class="col-md-12">
                    <output id="Filelist2"></output>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Ad-Spent</label>
                  <div class="col-md-4">
                    <input type="number" name="ad_spent" placeholder="Ad Spent"
                    class="form-control {{ $errors->has('ad_spent') ? 'is-invalid' : '' }}" value="{{ old('ad_spent') }}">
                    @if($errors->has('ad_spent'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('ad_spent') }}</div>
                    @endif
                  </div>
                  <label class="col-md-2 form-control-label">Revenue Share</label>
                  <div class="col-md-4">
                    <input type="number" name="revenue_share"  placeholder="Revenue Share"
                    class="form-control {{ $errors->has('revenue_share') ? 'is-invalid' : '' }}" value="{{ old('revenue_share') }}">
                    @if($errors->has('revenue_share'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('revenue_share') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Pictures </label>
                  <div class="col-md-4">
                    <span class="btn btn-success fileinput-button">
                      <span>Select Pictures</span>
                      <input type="file" name="ptnr_picture[]" multiple id="files2" class="form-control {{ $errors->has('ptnr_picture') ? 'is-invalid' : '' }}"><br />
                    </span>

                    @if($errors->has('ptnr_picture'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('ptnr_picture') }}</div>
                    @endif
                  </div>
                  <label class="col-md-2 form-control-label">Files </label>
                  <div class="col-md-4">
                    <input type="file" class="form-control" name="ptnr_files[]" multiple />
                    @if($errors->has('ptnr_files'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('ptnr_files') }}</div>
                    @endif
                  </div>
                </div>



                <!-- NOTES -->
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Additional Details</label>
                  <div class="col-md-10">
                    <textarea placeholder="Enter Details" class="form-control tiny" name="additional_details" id="" cols="30" rows="5">{{ old('additional_details')}}</textarea>
                    @if($errors->has('additional_details'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('additional_details') }}</div>
                    @endif
                  </div>
                </div>
              </div>

              <div role="tabpanel" class="tab-pane pt-3 in fade" id="moredetail-tab">
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Started Practicing</label>
                  <div class="col-md-4">
                    <input type="date" name="experience" placeholder="Experience In Year"
                    class="form-control {{ $errors->has('experience') ? 'is-invalid' : '' }}" value="{{ old('experience') }}">

                    @if($errors->has('experience'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('experience') }}</div>
                    @endif
                  </div>

                  <label class="col-md-2 form-control-label">Email</label>
                  <div class="col-md-4">
                    <input type="email" name="email"  placeholder="Doctor Email"
                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}">

                    @if($errors->has('email'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('email') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Assistant's Name</label>
                  <div class="col-md-4">
                    <input type="text" name="assistant_name" placeholder="Assistant's Name"
                    class="form-control {{ $errors->has('assistant_name') ? 'is-invalid' : '' }}" value="{{ old('assistant_name') }}">

                    @if($errors->has('assistant_name'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('assistant_name') }}</div>
                    @endif
                  </div>

                  <label class="col-md-2 form-control-label">Assistant's Phone</label>
                  <div class="col-md-4">
                    <input type="text" name="assistant_phone" data-mask="9999-9999999" placeholder="Assistant's Phone"
                    class="form-control {{ $errors->has('assistant_phone') ? 'is-invalid' : '' }}" value="{{ old('assistant_phone') }}">

                    @if($errors->has('assistant_phone'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('assistant_phone') }}</div>
                    @endif
                  </div>
                </div>
                <!-- NOTES -->
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Notes</label>
                  <div class="col-md-10">
                    <textarea placeholder="Enter Details" class="form-control tiny" name="notes" id="" cols="30" rows="5">{{ old('notes') }}</textarea>
                    @if($errors->has('notes'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('notes') }}</div>
                    @endif
                  </div>
                </div>
              </div>

            </div>

            <div class="form-group row">
              <label class="col-md-2 form-control-label">Active</label>
              <div class="col-md-4">
                <div class="custom-control custom-checkbox">
                  <input id="is_active" value="1" type="checkbox" name="is_active" class="custom-control-input">
                  <label for="is_active" class="custom-control-label">Check to Active the Doctor</label>
                </div>
              </div>

            <label class="col-md-2 form-control-label">Show on Web</label>
              <div class="col-md-4">
                <div class="custom-control custom-checkbox">
                  <input id="on_web" value="1" type="checkbox" name="on_web" class="custom-control-input">
                  <label for="on_web" class="custom-control-label">Check to Show on Website</label>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 form-control-label">Partner</label>
              <div class="col-md-4">
                <div class="custom-control custom-checkbox">
                  <input id="is_partner" value="1" type="checkbox" name="is_partner" class="custom-control-input">
                  <label for="is_partner" class="custom-control-label">Check to Onboard Doctor</label>
                </div>
              </div>
            </div>
            <hr>
            <div class="form-group row">
              <div class="col-md-12 text-center">
                <button type="submit" id="submit" class="btn btn-primary">Save Doctor</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
</div>
@endsection
@section('scripts')
<script src="{{ asset('backend/js/select2-develop/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/fileupload.js') }}" ></script>
<script src="{{ asset('backend/js/fileupload2.js') }}" ></script>
<script src="{{ asset('backend/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="{{ asset('backend/js/bootstrap-inputmask.min.js') }}"></script>
<script src="{{ asset('backend/js/tinymce-config.js') }}" ></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>
<script src="{{asset('backend/js/bootstrap-imageupload.js')}}"></script>

<script type="text/javascript">
  $(document).on('change','#specializationselect', function(){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var selected = [];
    var specialization_id  = $('#specializationselect option:selected').map(function(){
    return this.value;
    });
    for (var i = specialization_id.length - 1; i >= 0; i--) {
      selected.push(specialization_id[i]);
    }
    if (specialization_id.length > 0) {
    $.ajax({
      type:'post',
      url:"{{ route('getMultipleTreatments') }}",
      data: { specialization_ids : selected},
      success: function(response){
          $('#procedure').html(response);
          $('#procedure').addClass('selectpicker');
          $('#procedure').attr('data-live-search', 'true');
          $('#procedure').attr('multiple','true');
          $('#procedure').selectpicker('refresh');
      }
    });
    } else {
      var html = '<option value=""> Select Treatment</option>';
      $('#procedure').html(html);

    }
  });
</script>

<script type="text/javascript">
 $('form').submit(function(e) {
 var $input = $(this).find('input.auto');
     val = $input.val();
     list = $input.attr('list'),
     match = $('#'+list + ' option').filter(function() {
         return ($(this).val() === val);
     });

  if(match.length > 0) {
      e.preventDefault();

      var area = match.val();
      alert('Navigate to ' + area);

  }
});
</script>
<script>
  var $imageupload = $('.imageupload');
  $imageupload.imageupload();
  $('#doctor-form').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
      e.preventDefault();
      return false;
    }
  });
</script>
{{-- Dynamic field for qualification --}}
<script>
  $(document).ready(function(){
    var i=1;
    $('#add-qualification').click(function(){
      i++;
      var html = '';
      html += '<div class="pt-1" id="qualification-row'+i+'">';
      html += '<hr>';
      html += '<div class="form-group row">';
      html += '<label class="col-md-2 form-control-label">Degree Title <span class="asterisk-blue">*</span></label>';
      html += '<div class="col-md-4">';
      html += '<input type="text" name="qua_degree[]" id="qua_degree" list="degrees" placeholder="Enter Degree Name"class="form-control" value=""><datalist id="degrees"><?php foreach($degrees as $d) : ?><option value="<?php echo $d->name; ?>"><?php echo $d->name; ?></option><?php endforeach; ?></datalist>';
      html += '</div>';
      html += '<label class="col-md-2 form-control-label">University</label>';
      html += '<div class="col-md-4">';
      html += '<input type="text" name="qua_university[]" id="qua_university" list="universities" placeholder="Enter University Name"class="form-control" value=""><datalist id="universities"><?php foreach($universities as $u) : ?><option value="<?php echo $u->name; ?>"><?php echo $u->name; ?></option><?php endforeach; ?></datalist>';
      html += '</div>';
      html += '</div>';
      html += '<div class="form-group row">';
      html += '<label class="col-md-2 form-control-label">Country</label>';
      html += '<div class="col-md-4">';
      html += '<select  name="qua_country[]" class="form-control "> <option>Select Country</option><?php foreach($countries as $c) : ?><option value="<?php echo $c->nicename; ?>"><?php echo $c->nicename; ?></option><?php endforeach; ?></select>';
      html += '</div>';
      html += '<label class="col-md-2 form-control-label">Graduation Year</label>';
      html += '<div class="col-md-4">';
      html += '<select  name="qua_graduation_year[]" class="form-control"> <option value="">Select Year</option><?php foreach($years as $year) : ?><option value="<?php echo $year; ?>"><?php echo $year; ?></option><?php endforeach; ?></select>';
      html += '</div>';
      html += '</div>';
      html += '<div class="row">';
      html += '<div class="col-md-4"></div>';
      html += '<div class="col-md-4 text-center form-control-label">';
      html += '<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button>';
      html += '</div>';
      html += '<div class="col-md-4"></div>';
      html += '</div>';
      html += '</div>';
      $('#dynamic_field_qualification').append(html);
    });

    $(document).on('click', '.btn_remove', function(){
      var button_id = $(this).attr("id");
      $('#qualification-row'+button_id+'').remove();
    });
  });
</script>
{{-- end of Dynamic field for qualification --}}

{{-- Dynamic field for Certification --}}
<script>
  $(document).ready(function(){
    var i=1;
    $('#add-certification').click(function(){
      i++;
      var html = '';
      html += '<div class="pt-1" id="certification-row'+i+'">';
      html += '<hr>';
      html += '<div class="form-group row">';
      html += '<label class="col-md-2 form-control-label">Degree Title <span class="asterisk-blue">*</span></label>';
      html += '<div class="col-md-4">';
      html += '<input type="text" name="cer_degree[]" id="cer_degree" list="degrees" placeholder="Enter Certification Name"class="form-control " value=""><datalist id="degrees"><?php foreach($degrees as $d) : ?><option value="<?php echo $d->name; ?>"><?php echo $d->name; ?></option><?php endforeach; ?></datalist>';
      html += '</div>';
      html += '<label class="col-md-2 form-control-label">University</label>';
      html += '<div class="col-md-4">';
      html += '<input type="text" name="cer_university[]" id="cer_university" list="universities" placeholder="Enter Institution Name"class="form-control " value=""><datalist id="universities"><?php foreach($universities as $u) : ?><option value="<?php echo $u->name; ?>"><?php echo $u->name; ?></option><?php endforeach; ?></datalist>';
      html += '</div>';
      html += '</div>';
      html += '<div class="form-group row">';
      html += '<label class="col-md-2 form-control-label">Country</label>';
      html += '<div class="col-md-4">';
      html += '<select  name="cer_country[]" class="form-control "> <option>Select Country</option><?php foreach($countries as $c) : ?><option value="<?php echo $c->nicename; ?>"><?php echo $c->nicename; ?></option><?php endforeach; ?></select>';
      html += '</div>';
      html += '<label class="col-md-2 form-control-label">Graduation Year</label>';
      html += '<div class="col-md-4">';
      html += '<select  name="cer_graduation_year[]" class="form-control"> <option value="">Select Year</option><?php foreach($years as $year) : ?><option value="<?php echo $year; ?>"><?php echo $year; ?></option><?php endforeach; ?></select>';
      html += '</div>';
      html += '</div>';
      html += '<div class="row">';
      html += '<div class="col-md-4"></div>';
      html += '<div class="col-md-4 text-center form-control-label">';
      html += '<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button>';
      html += '</div>';
      html += '<div class="col-md-4"></div>';
      html += '</div>';
      html += '</div>';
      $('#dynamic_field_certification').append(html);
    });

    $(document).on('click', '.btn_remove', function(){
      var button_id = $(this).attr("id");
      $('#certification-row'+button_id+'').remove();
    });
  });
</script>
{{-- end of Dynamic field for Certification --}}
<script>
  $(document).ready(function(){
    var i=1;
    $('#add-time1').click(function(){
      i++;
      var html = '';
      html += '<div class="form-group row" id="row-time1'+i+'">';
      html += '<div class="col-md-10">';
      html += '<div class="row form-group">';
      html += '<div class="col-md-2 form-control-label">Day From</div>';
      html += '<div class="col-md-4"><?php $days = get_days();?>';
      html += '<select class="form-control " name="day_from1[]">';
      html += ' <?php foreach($days as $day){?><option value="<?php echo $day ?>"><?php echo $day ?></option><?php }?>';
      html += '</select>';
      html += '</div>';
      html += '<div class="col-md-2 form-control-label">Day to</div>';
      html += '<div class="col-md-4">';
      html += '<select class="form-control " name="day_to1[]">';
      html += ' <?php foreach($days as $day){?><option value="<?php echo $day ?>"><?php echo $day ?></option><?php }?>';
      html += '</select>';
      html += '</div>';
      html += '</div>';
      html += '<div class="form-group row">';
      html += '<div class="col-md-2 form-control-label">Time From</div>';
      html += '<div class="col-md-4">';
      html += '<select class="form-control " name="time_from1[]"><?php echo get_times(); ?></select>';
      html += '</div>';
      html += '<div class="col-md-2 form-control-label">Time to</div>';
      html += '<div class="col-md-4">';
      html += '<select class="form-control " name="time_to1[]"><?php echo get_times_to(); ?></select>';
      html += '</div>';
      html += '</div>';
      html += '</div>';
      html += '<div class="align-self-center">';
      html += '<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">Remove Time</button>';
      html += '</div>';
      html += '</div>';
      html += '</div>';

      $('#dynamic_for_add_time1').append(html);
    });

    $(document).on('click', '.btn_remove', function(){
      var button_id = $(this).attr("id");
      $('#row-time1'+button_id+'').remove();
    });
  });
</script>
<script>
  $('#center1').change(function(){
    var i;
    var j = 999;
    for (i = 2; i < j; i++) {
      $('#row'+i).remove();
      $('#row-time1'+i).remove();
      $('.total-tcost1').val(0);
    }
  });
</script>
<script>
  $('#center2').change(function(){
    var i;
    var j = 9999;
    for (i = 1001; i < j; i++) {
      $('#row'+i).remove();
      $('#row-time2'+i).remove();
      $('.total-tcost2').val(0);
    }
  });
</script>
<script>
      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 31.5204, lng: 74.3587},
          zoom: 13
        });
        var card          = document.getElementById('pac-card');
        var input         = document.getElementById('pac-input');
        var types         = document.getElementById('type-selector');
        var strictBounds  = document.getElementById('strict-bounds-selector');

        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

        var autocomplete = new google.maps.places.Autocomplete(input);

        // Bind the map's bounds (viewport) property to the autocomplete object,
        // so that the autocomplete requests use the current map bounds for the
        // bounds option in the request.
        autocomplete.bindTo('bounds', map);

        // Set the data fields to return when the user selects a place.
        autocomplete.setFields(
          ['address_components', 'geometry', 'icon', 'name']);

        var infowindow = new google.maps.InfoWindow();
        var infowindowContent = document.getElementById('infowindow-content');
        infowindow.setContent(infowindowContent);
        var marker = new google.maps.Marker({
          map: map,
          anchorPoint: new google.maps.Point(0, -29)
        });

        autocomplete.addListener('place_changed', function() {
          infowindow.close();
          marker.setVisible(false);
          var place = autocomplete.getPlace();
          if (!place.geometry) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert("No details available for input: '" + place.name + "'");
            return;
          }

          // If the place has a geometry, then present it on a map.
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(14);  // Why 17? Because it looks good.
          }
          marker.setPosition(place.geometry.location);
          marker.setVisible(true);

          var address   = '';
          var locality  = '';
          if (place.address_components) {
            address = [
            (place.address_components[0] && place.address_components[0].short_name || ''),
            (place.address_components[1] && place.address_components[1].short_name || ''),
            (place.address_components[2] && place.address_components[2].short_name || ''),
            (place.address_components[3] && place.address_components[3].short_name || ''),
            (place.address_components[4] && place.address_components[4].short_name || '')
            ].join(' ');
          }

          infowindowContent.children['place-icon'].src = place.icon;
          infowindowContent.children['place-name'].textContent = place.name;
          infowindowContent.children['place-address'].textContent = address;
          infowindow.open(map, marker);

          var place = autocomplete.getPlace();
          // Then do whatever you want with them
          for (var i = 0; i < place.address_components.length; i++) {
            if (place.address_components[i].types[0] == 'locality') {
              locality = place.address_components[i].long_name;
            }
          }
        $("#lat").val(place.geometry.location.lat());
        $("#lng").val(place.geometry.location.lng());
        $("#address").val(address);
        $("#city_name").val(locality);
          // console.log(lat);
          // console.log(lng);
          // console.log(place.name);
          // console.log(place.address_components);
          // console.log(address);
        });

        // Sets a listener on a radio button to change the filter type on Places
        // Autocomplete.
        function setupClickListener(id, types) {
          var radioButton = document.getElementById(id);
          radioButton.addEventListener('click', function() {
            autocomplete.setTypes(types);
          });
        }

        setupClickListener('changetype-all', []);
        setupClickListener('changetype-address', ['address']);
        setupClickListener('changetype-establishment', ['establishment']);
        setupClickListener('changetype-geocode', ['geocode']);

        document.getElementById('use-strict-bounds')
        .addEventListener('click', function() {
          console.log('Checkbox clicked! New state=' + this.checked);
          autocomplete.setOptions({strictBounds: this.checked});
        });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD9RHZgUXffbQmvczfgC8CeNKfm6IYMAJQ&libraries=places&callback=initMap" async defer></script>
    @endsection
