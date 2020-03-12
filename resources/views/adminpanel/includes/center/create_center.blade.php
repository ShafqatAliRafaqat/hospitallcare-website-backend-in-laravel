
              <input type="hidden" name="lat" id="lat">
              <input type="hidden" name="lng" id="lng">
              <input type="hidden" name="city_name" id="city_name">
              <div id="dynamic_field">
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select Treatment  <span class="asterisk">*</span></div>
                  <div class="col-md-3">
                    <select name="treatment_id[]" id="treatment" class="form-control name_list " data-live-search="true">
                     <option value="">Select Treatment</option>
                     @if (count($treatments) > 0)
                       @foreach ($treatments as $t)
                         <option value="{{ $t->id }}">{{ $t->name }}</option>
                       @endforeach
                     @endif
                    </select>
                  </div>
                  <div class="col-md-2 form-control-label">Cost  <span class="asterisk">*</span></div>
                  <div class="col-md-3">
                    <input type="number" name="cost[]" placeholder="Enter treatment Cost" class="form-control name_list" required/>
                  </div>
                  <div class="col-md-2 form-control-label">
                    <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
                  </div>
                </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Add Focus Area  <span class="asterisk">*</span></label>
                  <div class="col-md-10">
                    <textarea class="form-control" cols="90" rows="2" placeholder="Enter Focus Area Comma Seprated" name="focus_area"></textarea>
                  </div>
                  @if(!empty($focus_area))
                      <div class="invalid-feedback ml-3">{{ $focus_area }}</div>
                  @endif
                </div>

