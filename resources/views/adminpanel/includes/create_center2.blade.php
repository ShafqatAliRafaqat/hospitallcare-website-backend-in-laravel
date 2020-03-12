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
<div class="form-group row">
  <label class="col-md-2 form-control-label">Active</label>
  <div class="col-md-10">
    <div class="custom-control custom-checkbox">
      <input id="is_active" value="1" type="checkbox" name="is_active" class="custom-control-input">
      <label for="is_active" class="custom-control-label">Check to Active the Center</label>
    </div>
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
<ul class="nav nav-tabs customer-nav" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" href="#address-tab" role="tab" data-toggle="tab" >Address</a>
  </li>
  <li class="nav-item">
    <a class="nav-link " href="#seo-tab" role="tab" data-toggle="tab" >SEO Details</a>
  </li>
  <li class="nav-item">
    <a class="nav-link " href="#partnership-tab" role="tab" data-toggle="tab">Partnership Details</a>
  </li>
</ul>
<!-- Tab panes -->
<div class="tab-content">
