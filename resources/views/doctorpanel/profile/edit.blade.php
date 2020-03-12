@extends('doctorpanel.layout')
@section('title', 'Clients | Patients')
@section('styles')
<link rel="stylesheet" href="{{ asset('backend/js/select2-develop/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/fileupload.css') }}">
@endsection
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      @include('doctorpanel.notification')
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Edit Doctor</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" id="doctor-form" method="post" action="{{ route('save_doctor_profile', $doctor->id) }}" enctype="multipart/form-data">
              @csrf
              @method('post')
              <input type="hidden" name="lat" id="lat" value="{{ $doctor->lat}}">
              <input type="hidden" name="lng" id="lng" value="{{ $doctor->lng}}">
              <input type="hidden" name="city_name" id="city_name" value="{{ $doctor->city_name}}">
              <div class="form-group row">
                <label class="col-md-2 form-control-label">Focus Areas  <span class="asterisk">*</span></label>
                <div class="col-md-10">
                  <input type="text" name="focus_area" id="" class="form-control" value="{{ $doctor->focus_area }}" />
                </div>
                @if(!empty($focus_area))
                <div class="invalid-feedback ml-3">{{ $focus_area }}</div>
                @endif
              </div>
              <?php $years = get_years();?>
              <div class="form-group row">
                <label class="col-md-2 form-control-label">Doctor Name  <span class="asterisk">*</span></label>
                <div class="col-md-4">
                  <input type="text" name="name" placeholder="Doctor First Name"
                  class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $doctor->name }}" required>

                  @if($errors->has('name'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('name') }}</div>
                  @endif
                </div>
                <label class="col-md-2 form-control-label">Last Name </label>
                <div class="col-md-4">
                  <input type="text" name="last_name" placeholder="Doctor Last Name"
                  class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" value="{{ $doctor->last_name }}" >

                  @if($errors->has('last_name'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('last_name') }}</div>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-2 form-control-label">PMDC No.<span class="asterisk-blue">*</span></label>
                <div class="col-md-4">
                  <input type="text" name="pmdc" placeholder="PMDC No."
                  class="form-control {{ $errors->has('pmdc') ? 'is-invalid' : '' }}" value="{{ $doctor->pmdc }}">

                  @if($errors->has('pmdc'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('pmdc') }}</div>
                  @endif
                </div>
                <label class="col-md-2 form-control-label">Doctor's Phone</label>
                <div class="col-md-4">
                  <input type="text" name="phone" placeholder="Phone" data-mask = "9999-9999999"
                  class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ $doctor->phone }}">

                  @if($errors->has('phone'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('phone') }}</div>
                  @endif
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-2 form-control-label">Picture</label>
                <div class="col-md-6">
                  <span class="btn btn-success fileinput-button">
                    <span>Select Attachment</span>
                    <input type="file" name="picture" id="files"  class="form-control {{ $errors->has('picture') ? 'is-invalid' : '' }}"><br />
                  </span>
                  <output id="Filelist"></output>
                  @if($errors->has('picture'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('picture') }}</div>
                  @endif
                </div>
                <div class="col-md-2">
                  @if(isset($doctor->doctor_image->picture))
                  @php
                  $picture = $doctor->doctor_image->picture;
                  @endphp
                  <img src="{{ asset('backend/uploads/doctors/'.$picture) }}" class="img-fluid" alt="{{ $errors->first('picture') }}" height="100" width="100">
                  @endif
                </div>
              </div>
              <ul class="nav nav-tabs customer-nav" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" href="#address-tab" role="tab" data-toggle="tab">Address</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="#moredetail-tab" role="tab" data-toggle="tab">Add More Details</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="#qualification-tab" role="tab" data-toggle="tab">Qualification</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="#certification-tab" role="tab" data-toggle="tab">Certification</a>
                </li>
              </ul>

              <!-- Tab panes -->
              <div class="tab-content">
                <div role="tabpanel" class="tab-pane pt-3 in active" id="address-tab">
                  <div class="form-group row">
                    <label class="col-md-2 form-control-label">Address  <span class="asterisk">*</span></label>
                    <div class="col-md-10 mb-2">
                      <input type="text" name="address" id="address" placeholder="Address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" value="{{ $doctor->address }}" required>
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
                          placeholder="Enter a location" value="{{ $doctor->address }}">
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
                <div role="tabpanel" class="tab-pane pt-3 in fade" id="moredetail-tab">
                  <div class="form-group row">
                    <label class="col-md-2 form-control-label">Started Practicing</label>
                    <div class="col-md-4">
                      <input type="date" name="experience" placeholder="Experience In Year"
                      class="form-control {{ $errors->has('experience') ? 'is-invalid' : '' }}" value="{{ $doctor->experience }}" >

                      @if($errors->has('experience'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('experience') }}</div>
                      @endif
                    </div>

                    <label class="col-md-2 form-control-label">Doctor's Email</label>
                    <div class="col-md-4">
                      <input type="email" name="email" placeholder="Doctor Email"
                      class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ $doctor->email }}">

                      @if($errors->has('email'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('email') }}</div>
                      @endif
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-2 form-control-label">Assistant's Name</label>
                    <div class="col-md-4">
                      <input type="text" name="assistant_name" placeholder="Assistant's Name"
                      class="form-control {{ $errors->has('assistant_name') ? 'is-invalid' : '' }}" value="{{ $doctor->assistant_name }}">

                      @if($errors->has('assistant_name'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('assistant_name') }}</div>
                      @endif
                    </div>

                    <label class="col-md-2 form-control-label">Assistant's Phone</label>
                    <div class="col-md-4">
                      <input type="text" name="assistant_phone" data-mask="9999-9999999" placeholder="Assistant's Phone"
                      class="form-control {{ $errors->has('assistant_phone') ? 'is-invalid' : '' }}" value="{{ $doctor->assistant_phone }}">

                      @if($errors->has('assistant_phone'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('assistant_phone') }}</div>
                      @endif
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-2 form-control-label">Notes</label>
                    <div class="col-md-10">
                      <textarea placeholder="Enter Details" class="form-control" name="about" id="" cols="30" rows="5">{{ $doctor->about }}</textarea>
                      @if($errors->has('about'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('about') }}</div>
                      @endif
                    </div>
                  </div>
                </div>
                {{-- Qualification Tab Pane --}}
                <div role="tabpanel" class="tab-pane pt-3 in fade" id="qualification-tab">
                  @if(count($doctor_qualification)>0)
                  <?php $q=0; ?>
                  @foreach($doctor_qualification as $qualification)
                  <div id="dynamic_field_qualification">
                    <div class="pt-3" id="qualification{{$q+1}}">
                      <div class="form-group row">
                        <label class="col-md-2 form-control-label">Degree Title <span class="asterisk-blue">*</span></label>
                        <div class="col-md-4">
                          <input type="text"  name="qua_degree[]" id="qua_degree" list="degrees" placeholder="Enter Degree Name"
                          class="form-control" value="{{ $qualification->degree }}">
                          <datalist id="degrees">
                            @foreach($degrees as $d)
                            <option data-value="{{ $d->name }}" {{ $d->name == $qualification->degree ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                          </datalist>
                          <!-- @if($errors->has('qua_degree'))
                          <div class="invalid-feedback ml-3">{{ $errors->first('qua_degree') }}</div>
                          @endif -->
                        </div>

                    <label class="col-md-2 form-control-label">University</label>
                    <div class="col-md-4">
                      <input type="text" name="qua_university[]" id="qua_university" list="universities" placeholder="Enter University Name"
                      class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" value="{{$qualification->university }}">
                      <datalist id="universities">
                        @foreach($universities as $u)
                        <option data-value="{{ $u->name }}"  {{ $u->name == $qualification->university ? 'selected' : '' }}>{{ $u->name }}</option>
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
                            @foreach($countries as $t)
                            <option value="{{ $t->nicename }}" {{ $t->nicename == $qualification->country ? 'selected' : '' }}>{{ $t->nicename }}</option>
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
                              <option value="<?php echo $year; ?>"{{ $year == $qualification->graduation_year ? 'selected': '' }}><?php echo $year; ?></option>
                            <?php endforeach; ?>
                          </select>
                          @if($errors->has('qua_graduation_year'))
                          <div class="invalid-feedback ml-3">{{ $errors->first('qua_graduation_year') }}</div>
                          @endif
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4"></div>
                        <?php if ($q == 0){?>
                          <div class="col-md-4 text-center form-control-label">
                            <button type="button" name="add-qualification" id="add-qualification" class="btn btn-success">Add More Qualification</button>
                          </div>
                        <?php }else{ ?>
                          <div class="col-md-4 text-center form-control-label">
                            <button type="button" name="remove-qualification" id="qualification{{$q+1}}" class="btn btn-danger btn_remove">X</button>
                          </div>
                        <?php }?>
                        <div class="col-md-4"></div>
                      </div>
                    </div>
                  </div>
                  <?php $q++ ?>
                  @endforeach
                  @endif
                  @if(count($doctor_qualification ) ==0 )
                  <div id="dynamic_field_qualification">
                  <div class="form-group row">
                    <label class="col-md-2 form-control-label">Degree Title <span class="asterisk-blue">*</span></label>
                    <div class="col-md-4">
                      <input type="text"  name="qua_degree[]" id="qua_degree" list="degrees" placeholder="Enter Degree Name"
                      class="form-control" value="{{ old('qua_degree') }}">
                      <datalist id="degrees">
                        @foreach($degrees as $d)
                        <option id="{{ $d->name }}" data-value="{{ $d->name }}">{{ $d->name }}</option>
                        @endforeach
                      </datalist>
                      <!-- @if($errors->has('qua_degree'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('qua_degree') }}</div>
                      @endif -->
                    </div>
                    <label class="col-md-2 form-control-label">University</label>
                    <div class="col-md-4">
                      <input type="text" name="qua_university[]" id="qua_university" list="universities" placeholder="Enter University Name"
                      class="form-control" value="{{ old('qua_university') }}">
                      <datalist id="universities">
                        @foreach($universities as $u)
                        <option data-value="{{ $u->name }}">{{ $u->name }}</option>
                        @endforeach
                      </datalist>
                      <!-- @if($errors->has('qua_university'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('qua_university') }}</div>
                      @endif -->
                    </div>
                  </div>
                    <div class="form-group row">

                      <label class="col-md-2 form-control-label">Country</label>
                      <div class="col-md-4">
                        <select  name="qua_country[]" id="qua_country" class="form-control selectpicker" data-live-search="true">
                          <option value="">Select Country</option>
                          @foreach($countries as $t)
                          <option value="{{ $t->nicename }}">{{ $t->nicename }}</option>
                          @endforeach
                        </select>
                        <!-- @if($errors->has('qua_country'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('qua_country') }}</div>
                        @endif -->
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
                  @endif
                </div>
                {{-- End Qualification Tab Pane --}}
                {{-- Certification Tab Pane --}}
                <div role="tabpanel" class="tab-pane pt-3 in fade" id="certification-tab">
                  @if(count($doctor_certification)>0)
                  <?php $c=0?>
                  @foreach($doctor_certification as $certification)
                  <div id="dynamic_field_certification">
                    <div class="pt-3" id="certification{{$c+1}}">
                      <div class="form-group row">
                        <label class="col-md-2 form-control-label">Certification Title <span class="asterisk-blue">*</span></label>
                        <div class="col-md-4">
                          <input type="text"  name="cer_degree[]" id="cer_degree" list="degrees" placeholder="Enter Certification Name"
                          class="form-control" value="{{ $certification->title }}">
                          <datalist id="degrees">
                            @foreach($degrees as $d)
                            <option data-value="{{ $d->name }}" {{ $d->name == $certification->title ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                          </datalist>
                         <!--  @if($errors->has('cer_degree'))
                          <div class="invalid-feedback ml-3">{{ $errors->first('cer_degree') }}</div>
                          @endif -->
                        </div>
                    <label class="col-md-2 form-control-label">Institution</label>
                    <div class="col-md-4">
                      <input type="text" name="cer_university[]" id="cer_university" list="universities" placeholder="Enter University Name"
                      class="form-control " value="{{$certification->university }}">
                      <datalist id="universities">
                        @foreach($universities as $u)
                        <option data-value="{{ $u->name }}"  {{ $u->name == $certification->university ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                      </datalist>
                      <!-- @if($errors->has('qua_university'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('qua_university') }}</div>
                      @endif -->
                    </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-md-2 form-control-label">Country</label>
                        <div class="col-md-4">
                            <select  name="cer_country[]" id="cer_country" class="form-control selectpicker" data-live-search="true">
                              <option value="">Select Country</option>
                              @foreach($countries as $t)
                              <option value="{{ $t->nicename }}" {{ $certification->country == $t->nicename ? 'selected':'' }}>{{ $t->nicename }}</option>
                              @endforeach
                            </select>
                         <!--  @if($errors->has('cer_country'))
                          <div class="invalid-feedback ml-3">{{ $errors->first('cer_country') }}</div>
                          @endif -->
                        </div>

                        <label class="col-md-2 form-control-label">Graduation Year</label>
                        <div class="col-md-4">
                          <select  name="cer_graduation_year[]" class="form-control selectpicker" data-live-search="true">
                            <option value="">Select Year</option>
                            <?php foreach($years as $year) : ?>
                              <option value="<?php echo $year; ?>"{{ $year == $certification->year ? 'selected': '' }}><?php echo $year; ?></option>
                            <?php endforeach; ?>
                          </select>
                        <!--   @if($errors->has('cer_graduation_year'))
                          <div class="invalid-feedback ml-3">{{ $errors->first('cer_graduation_year') }}</div>
                          @endif -->
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4"></div>
                        <?php if ($c == 0){?>
                          <div class="col-md-4 text-center form-control-label">
                            <button type="button" name="add-certification" id="add-certification" class="btn btn-success">Add More certification</button>
                          </div>
                        <?php }else{ ?>
                          <div class="col-md-4 text-center form-control-label">
                            <button type="button" name="remove-certification" id="certification{{$c+1}}" class="btn btn-danger btn_remove">X</button>
                          </div>
                        <?php }?>
                        <div class="col-md-4"></div>
                      </div>
                    </div>
                  </div>
                  <?php $c++; ?>
                  @endforeach
                  @endif
                  @if(count($doctor_certification)==0)

                  <div id="dynamic_field_certification">
                    <div class="form-group row">
                      <label class="col-md-2 form-control-label">Certification Title <span class="asterisk-blue">*</span></label>
                    <div class="col-md-4">
                      <input type="text" name="cer_degree[]" id="cer_degree" list="degrees" placeholder="Enter Certification Name" class="form-control " value="{{ old('cer_degree') }}">
                      <datalist id="degrees">
                        @foreach($degrees as $d)
                        <option id="{{ $d->name }}" data-value="{{ $d->name }}">{{ $d->name }}</option>
                        @endforeach
                      </datalist>
                      <!-- @if($errors->has('cer_degree'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('cer_degree') }}</div>
                      @endif -->
                    </div>

                      <label class="col-md-2 form-control-label">University</label>
                    <div class="col-md-4">
                      <input type="text" name="cer_university[]" id="cer_university" list="universities" placeholder="Enter Institute Name"
                      class="form-control" value="{{ old('cer_university') }}">
                      <datalist id="universities">
                        @foreach($universities as $u)
                        <option data-value="{{ $u->name }}">{{ $u->name }}</option>
                        @endforeach
                      </datalist>
                      <!-- @if($errors->has('cer_university'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('cer_university') }}</div>
                      @endif -->
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
                        <!-- @if($errors->has('cer_graduation_year'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('cer_graduation_year') }}</div>
                        @endif -->
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
                  @endif
                </div>
                {{-- End Certification Tab Pane --}}

              </div>
              <hr>
              <div class="form-group row">
                <div class="col-md-12 text-center">
                  <button type="submit" class="btn btn-primary">Update</button>
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
<script src="{{ asset('backend/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/tinymce-config.js') }}" ></script>
<script src="{{ asset('backend/js/fileupload.js') }}" ></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>
<script src="{{ asset('backend/js/bootstrap-inputmask.min.js') }}"></script>
<script>
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
      html += '<input type="text" name="qua_degree[]" id="qua_degree" list="degrees" placeholder="Enter Degree Name"class="form-control {{ $errors->has('degrees') ? 'is-invalid' : '' }}" value="{{ old('degrees') }}"><datalist id="degrees"><?php foreach($degrees as $d) : ?><option value="<?php echo $d->name; ?>"><?php echo $d->name; ?></option><?php endforeach; ?></datalist>';
      html += '</div>';
      html += '<label class="col-md-2 form-control-label">University</label>';
      html += '<div class="col-md-4">';
      html += '<input type="text" name="qua_university[]" id="qua_university" list="universities" placeholder="Enter University Name"class="form-control {{ $errors->has('qua_university') ? 'is-invalid' : '' }}" value="{{ old('qua_university') }}"><datalist id="universities"><?php foreach($universities as $u) : ?><option value="<?php echo $u->name; ?>"><?php echo $u->name; ?></option><?php endforeach; ?></datalist>';
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
      html += '<input type="text" name="cer_degree[]" id="cer_degree" list="degrees" placeholder="Enter Certification Name"class="form-control {{ $errors->has('cer_degree') ? 'is-invalid' : '' }}" value="{{ old('cer_degree') }}"><datalist id="degrees"><?php foreach($degrees as $d) : ?><option value="<?php echo $d->name; ?>"><?php echo $d->name; ?></option><?php endforeach; ?></datalist>';
      html += '</div>';
      html += '<label class="col-md-2 form-control-label">University</label>';
      html += '<div class="col-md-4">';
      html += '<input type="text" name="cer_university[]" id="cer_university" list="universities" placeholder="Enter Institution Name"class="form-control {{ $errors->has('cer_university') ? 'is-invalid' : '' }}" value="{{ old('cer_university') }}"><datalist id="universities"><?php foreach($universities as $u) : ?><option value="<?php echo $u->name; ?>"><?php echo $u->name; ?></option><?php endforeach; ?></datalist>';
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
            // This example requires the Places library. Include the libraries=places
            // parameter when you first load the API. For example:
            // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
            function initMap() {
              var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: <?php echo $doctor->lat; ?>, lng: <?php echo $doctor->lng; ?>},
                zoom: 15
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
                position: new google.maps.LatLng(<?php echo $doctor->lat; ?>, <?php echo $doctor->lng; ?>),
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
                // console.log(lat);
                // console.log(lng);
                // console.log(place.name);
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

