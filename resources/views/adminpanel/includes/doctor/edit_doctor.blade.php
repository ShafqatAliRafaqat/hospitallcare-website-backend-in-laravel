             <input type="hidden" name="lat" id="lat" value="{{ $doctor->lat}}">
              <input type="hidden" name="lng" id="lng" value="{{ $doctor->lng}}">
              <input type="hidden" name="city_name" id="city_name" value="{{ $doctor->city_name}}">
              <div class="form-group row">
                <label class="col-md-2 form-control-label">Focus Areas  <span class="asterisk">*</span></label>
                <div class="col-md-4">
                  <input type="text" name="focus_area" id="" class="form-control" value="{{ $doctor->focus_area }}" />
                </div>
                @if(!empty($focus_area))
                <div class="invalid-feedback ml-3">{{ $focus_area }}</div>
                @endif

                  <label class="col-md-2 form-control-label">Gender <span class="asterisk">*</span></label>
                  <div class="col-md-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="male" type="radio" value="1" name="gender" class="custom-control-input"
                      {{ $doctor->gender == 1 ? 'checked':'' }}>
                      <label for="male" class="custom-control-label">Male</label>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input id="female" type="radio" value="0" name="gender" class="custom-control-input" {{ $doctor->gender == 0 ? 'checked':'' }}>
                      <label for="female" class="custom-control-label">Female</label>
                    </div>
                  </div>
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
                <div class="col-md-7 imageupload">
                  <div class="file-tab panel-body">
                    <label class="btn btn-success btn-file">
                      <span>File</span>
                      <!-- The file is stored here. -->
                      <input type="file" name="picture">
                    </label>
                    <button type="button" class="btn btn-danger">Remove</button>
                  </div>
                </div>
                @php
                $picture = (isset($image))?$image->picture:"";
                @endphp
                <div class="col-md-3">
                  <div class="img-wrap">
                    <span class="close">×</span>
                    <input type="hidden" name="picture"  value="{{$picture}}">
                    <img src="{{ asset('backend/uploads/doctors/'.$picture) }}" class="img-fluid" alt="{{ $errors->first('picture') }}" height="100" width="100">
                  </div>
                </div>
              </div>
              <!-- ABOUT -->
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">About Doctor</label>
                  <div class="col-md-10">
                    <textarea class="form-control" name="about" id="" cols="30" rows="4">{{ $doctor->about }}</textarea>
                    @if($errors->has('about'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('about') }}</div>
                    @endif
                  </div>
                </div>
              <div class="form-group row">
                <label class="col-md-2 form-control-label">Updated By</label>
                <div class="col-md-10">
                  <input type="text" name="updated_by" placeholder="{{ Auth::user()->name }}"
                  class="form-control {{ $errors->has('updated_by') ? 'is-invalid' : '' }}" value="{{ Auth::user()->name  }}" readonly>
                  @if($errors->has('updated_by'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('updated_by') }}</div>
                  @endif
                </div>
              </div>
              <ul class="nav nav-tabs customer-nav" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" href="#address-tab" role="tab" data-toggle="tab" >Address</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="#treatments-tab" role="tab" data-toggle="tab">Centers &amp; Treatments</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="#partnership-tab" role="tab" data-toggle="tab">Partnership Details</a>
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
                  <a class="nav-link " href="#moredetail-tab" role="tab" data-toggle="tab">Add More Details</a>
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
              <div role="tabpanel" class="tab-pane pt-3 in fade" id="treatments-tab">
                <!-- Schedule 2 -->
                <div class="form-group row"><div class="col-md-12 text-center form-control-label">Centers &amp; Treatments</div></div>
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Center <span class="asterisk-blue">*</span></div>
                  <div class="col-md-10">
                    <select name="centers[]"  class="form-control selectpicker" multiple data-live-search="true" required>
                      @foreach($centers as $c)
                      <option value="{{ $c->id }}"
                        @if(isset($old_centers))
                        @foreach($old_centers as $old_ids)
                        {{ ($c->id == $old_ids) ? 'selected' : ''}}
                        @endforeach
                        @endif>
                        {{ $c->center_name }}
                      </option>
                      @endforeach
                    </select>
                    @if($errors->has('centers'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('centers') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Speciality <span class="asterisk-blue">*</span></div>
                  <div class="col-md-10">
                    <select name="specializations[]" id="specializationselect" class="form-control selectpicker" multiple="multiple" data-live-search="true" required>
                      @if($old_speciality != NULL)
                      @foreach($specialities as $s)
                      <option value="{{ $s->id }}"
                        @foreach($old_speciality as $old_sp)
                        {{ ($s->id == $old_sp->id) ? 'selected' : ''}}
                        @endforeach
                        >{{ $s->name }}
                      </option>
                        @endforeach
                        @else
                          @foreach($specialities as $s)
                          <option value="{{ $s->id }}">{{ $s->name }}</option>
                          @endforeach
                        @endif
                      </select>
                      @if($errors->has('specializations'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('specializations') }}</div>
                      @endif
                    </div>
                  </div>
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Treatment <span class="asterisk-blue">*</span></div>
                  <div class="col-md-10">
                    <select name="procedures[]" id="procedure" class="form-control selectpicker" multiple="multiple" data-live-search="true" required>
                      @if($old_treatment != NULL)
                      @foreach($treatments as $t)
                      <option value="{{ $t->id }}"
                        @foreach($old_treatment as $old_ids)
                        {{ ($t->id == $old_ids->id) ? 'selected' : ''}}
                        @endforeach
                        >{{ $t->name }}
                      </option>
                        @endforeach
                        @else
                          @foreach($treatments as $t)
                          <option value="{{ $t->id }}">{{ $t->name }}</option>
                          @endforeach
                        @endif
                      </select>
                      @if($errors->has('treatments'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('treatments') }}</div>
                      @endif
                    </div>
                  </div>
                </div><!-- End Of Treatments -->

                <div role="tabpanel" class="tab-pane pt-3 in fade" id="seo-tab">
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Speciality <span class="asterisk-blue">*</span></div>
                  <div class="col-md-10">
                    <select name="meta_speciality" class="form-control selectpicker" data-live-search="true">
                      @if($doctor->speciality == NULL)
                      <option value="">Select a Speciality</option>
                      @endif
                      @foreach($specialities as $s)
                      <option value="{{ $s->name }}"  {{ ($s->name == $doctor->speciality) ? 'selected' : ''}}>{{ $s->name }}</option>
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
                      class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" value="{{ $doctor->meta_title }}">

                      @if($errors->has('meta_title'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_title') }}</div>
                      @endif
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-2 form-control-label">Meta Description</label>
                    <div class="col-md-10">
                      <input type="text" name="meta_description" placeholder="SEO Meta Description"
                      class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" value="{{ $doctor->meta_description }}">

                      @if($errors->has('meta_description'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_description') }}</div>
                      @endif
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-2 form-control-label">URL</label>
                    <div class="col-md-10">
                      <input type="text" name="url" placeholder="Enter URL"
                      class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" value="{{ $doctor->url }}">
                      @if($errors->has('url'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('url') }}</div>
                      @endif
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-2 form-control-label">Meta Heading</label>
                    <div class="col-md-10">
                      <input type="text" name="meta_heading" placeholder="Enter Heading"
                      class="form-control {{ $errors->has('meta_heading') ? 'is-invalid' : '' }}" value="{{ $doctor->meta_heading }}">
                      @if($errors->has('meta_heading'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('meta_heading') }}</div>
                      @endif
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-2 form-control-label">Image Alt</label>
                    <div class="col-md-10">
                      <input type="text" name="image_alt" placeholder="Enter Image Alt"
                      class="form-control {{ $errors->has('image_alt') ? 'is-invalid' : '' }}" value="{{ $doctor->image_alt }}">
                      @if($errors->has('image_alt'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('image_alt') }}</div>
                      @endif
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
                      <textarea placeholder="Enter Details" class="form-control tiny" name="notes" id="" cols="30" rows="5">{{ $doctor->notes }}</textarea>
                      @if($errors->has('notes'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('notes') }}</div>
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
                <div role="tabpanel" class="tab-pane pt-3 in fade" id="partnership-tab">
                  <div class="row pb-2">
                    <label class="col-md-1 form-control-label">New Uploads</label>
                    <div class="col-md-3">
                      <output id="Filelist2"></output>
                    </div>
                    <label class="col-md-1 form-control-label">Already Uploads</label>
                    <div class="col-md-3"style="display: -webkit-box;">
                      @if($ptnr_images)
                      @foreach($ptnr_images as $image)
                      <div>
                        <div class="img-wrap">
                          <span class="close">×</span>
                          <input type="hidden" name="old_ptnr_picture[]"  value="{{$image->picture}}">
                          <a href="#" class="pop">
                            <img id="zoom_mw" data-zoom-image="large/image1.jpg"/ src="{{ asset('backend/uploads/doctor_partnership_images/'.$image->picture) }}" class="img-fluid" alt="{{ $errors->first('ptnr_picture') }}" height="100" width="100">
                          </a>
                        </div>
                      </div>
                      @endforeach
                      @endif
                    </div>
                    <label class="col-md-1 form-control-label">Files</label>
                    <div class="col-md-3">
                      @if($ptnr_files)
                      @foreach($ptnr_files as $files)
                      <div>
                        <div class="file-wrap">
                          <span class="close">×</span>
                          <a href="#" class="pop_pdf">
                            @php
                            $sprt = explode('.',$files->file);
                            @endphp
                            {{ $sprt[1] }}
                            <img id="zoom_mw" data-zoom-image="large/image1.jpg"/ src="{{ asset('backend/uploads/doctor_partnership_files/'.$files->file) }}"
                            alt="" class="col-md-6 mt-1 responsive" max-height="200px" max-width="200px">
                          </a>
                          <input type="hidden" name="old_ptnr_files[]"  value="{{$files->file}}">
                        </div>
                      </div>
                      @endforeach
                      @endif
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-2 form-control-label">Ad-Spent</label>
                    <div class="col-md-4">
                      <input type="number" name="ad_spent" placeholder="Ad Spent"
                      class="form-control {{ $errors->has('ad_spent') ? 'is-invalid' : '' }}" value="{{ $doctor->ad_spent }}">
                      @if($errors->has('ad_spent'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('ad_spent') }}</div>
                      @endif
                    </div>
                    <label class="col-md-2 form-control-label">Revenue Share</label>
                    <div class="col-md-4">
                      <input type="number" name="revenue_share"  placeholder="Revenue Share"
                      class="form-control {{ $errors->has('revenue_share') ? 'is-invalid' : '' }}" value="{{ $doctor->revenue_share }}">
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
                      <textarea placeholder="Enter Details" class="form-control tiny" name="additional_details" id="" cols="30" rows="5">{{ $doctor->additional_details }}</textarea>
                      @if($errors->has('additional_details'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('additional_details') }}</div>
                      @endif
                    </div>
                  </div>
                </div>
                <hr>
              </div>
              <div class="form-group row">
                <label class="col-md-2 form-control-label">Active</label>
                <div class="col-md-4">
                  <div class="custom-control custom-checkbox">
                    <input id="is_active" value="1" type="checkbox" name="is_active" class="custom-control-input"
                    {{ $doctor->is_active == 1 ? 'checked':'' }}>
                    <label for="is_active" class="custom-control-label">Check to Active the Doctor</label>
                  </div>
                </div>
              <label class="col-md-2 form-control-label">Show on Web</label>
                <div class="col-md-4">
                  <div class="custom-control custom-checkbox">
                    <input id="on_web" value="1" type="checkbox" name="on_web" class="custom-control-input"
                    {{ $doctor->on_web == 1 ? 'checked':'' }}>
                    <label for="on_web" class="custom-control-label">Check to Show on Website</label>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-2 form-control-label">Partner</label>
                <div class="col-md-4">
                  <div class="custom-control custom-checkbox">
                    <input id="is_partner" value="1" type="checkbox" name="is_partner" class="custom-control-input"
                    {{ $doctor->is_partner == 1 ? 'checked':'' }}>
                    <label for="is_partner" class="custom-control-label">Check to Onboard Doctor</label>
                  </div>
                </div>
                <label class="col-md-2 form-control-label">Online Consultation</label>
                <div class="col-md-4">
                  <div class="custom-control custom-checkbox">
                    <input id="online_consultation" value="1" type="checkbox" name="online_consultation" class="custom-control-input"
                    {{ $doctor->online_consultation == 1 ? 'checked':'' }}>
                    <label for="online_consultation" class="custom-control-label">Check for Online Consultation</label>
                  </div>
                </div>
              </div>
              <hr>

              <div class="form-group row">
                <div class="col-md-12 text-center">
                  <button type="submit" class="btn btn-primary">Update Doctor</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <img src="" class="imagepreview" style="width: 100%;" >
      </div>
    </div>
  </div>
</div>
<!-- PDF model -->
<div class="modal fade" id="pdfmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <embed src="" class="pdfpreview" frameborder="0" width="100%" height="600px">
        </div>
      </div>
    </div>
  </div>
  <!-- end of PDF models -->
