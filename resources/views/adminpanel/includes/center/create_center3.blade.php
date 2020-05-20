
<div role="tabpanel" class="tab-pane pt-3 in fade" id="seo-tab">
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
      <input type="text" name="url" placeholder="Enter URL like: City/hospital-name/area"
      class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" value="{{ old('url') }}" required>
      @if($errors->has('url'))
      <div class="invalid-feedback ml-3">{{ $errors->first('url') }}</div>
      @endif
    </div>
  </div>
    <div class="form-group row">
    <label class="col-md-2 form-control-label">Meta Heading</label>
    <div class="col-md-10">
        <input type="text" name="meta_heading" placeholder="Enter Meta Heading"
        class="form-control {{ $errors->has('meta_heading') ? 'is-invalid' : '' }}" value="{{ old('meta_heading') }}">
      @if($errors->has('meta_heading'))
        <div class="invalid-feedback ml-3">{{ $errors->first('meta_heading') }}</div>
      @endif
    </div>
  </div>
  <div class="form-group row">
    <label class="col-md-2 form-control-label">Image Alt</label>
    <div class="col-md-10">
        <input type="text" name="image_alt" placeholder="Enter Image Alt"
        class="form-control {{ $errors->has('image_alt') ? 'is-invalid' : '' }}" value="{{ old('image_alt') }}">
      @if($errors->has('image_alt'))
        <div class="invalid-feedback ml-3">{{ $errors->first('image_alt') }}</div>
      @endif
    </div>
  </div>
  <div class="form-group row">
    <label class="col-md-2 form-control-label">Article</label>
    <div class="col-md-10">
      <textarea placeholder="Enter Details" class="form-control tiny" name="article" id="" cols="30" rows="5">{{ old('article')}}</textarea>
      @if($errors->has('article'))
      <div class="invalid-feedback ml-3">{{ $errors->first('article') }}</div>
      @endif
    </div>
  </div>
</div>
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
</div>

<div class="form-group row">
  <div class="col-md-10 ml-auto">
    <button type="submit" class="btn btn-primary">Save Center</button>
  </div>
</div>
</form>
</div>
</div>
</div>
</div>
</section>
</div>

