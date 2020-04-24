@extends('adminpanel.layout')
@section('title','Edit Procedure | HospitALL')
@section('styles')
  <link rel="stylesheet" href="{{ asset('backend/css/fileupload.css') }}">
@endsection
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      <!-- Form Elements -->
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Update Procedure</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('procedure.update', $procedure->id) }}"
              enctype="multipart/form-data">
              @csrf @method('put')
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Select Treatment <span class="asterisk">*</span></label>
                  <div class="col-md-10">
                    <select name="treatment_id" id="" class="form-control {{ $errors->has('treatment_id') ? 'is-invalid' : '' }}">
                      <option value="">Select Treatment</option>
                      @foreach($treatments as $treatment)
                      <option value="{{ $treatment->id }}"
                        {{ ($procedure->parent_id == $treatment->id) ? 'selected' : '' }}>
                        {{ $treatment->name }}
                      </option>
                      @endforeach
                    </select>
                    @if($errors->has('treatment_id'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('treatment_id') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Procedure Name <span class="asterisk">*</span></label>
                  <div class="col-md-10">
                      <input type="text" name="name" list="procedures-ajax-list" id="procedure_input" placeholder="Procedure Name"
                      class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $procedure->name }}" required>

                      @if($errors->has('name'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('name') }}</div>
                      @endif
                    <datalist id="procedures-ajax-list">
                    </datalist>
                  </div>
                </div>
                <ul class="nav nav-tabs customer-nav" role="tablist">
                        <li class="nav-item">
                                <a class="nav-link " href="#seo-tab" role="tab" data-toggle="tab" >SEO Details</a>
                              </li>
                    <li class="nav-item">
                            <a class="nav-link " href="#ads-tab" role="tab" data-toggle="tab">Ads Details</a>
                    </li>
                  </ul>

                  <!-- Tab panes -->
                  <div class="tab-content">
                        <div role="tabpanel" class="tab-pane pt-3 in fade" id="seo-tab">
                                <div class="form-group row">
                                        <label class="col-md-3 form-control-label">Meta Title</label>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ $procedure->meta_title }}" name="meta_title" placeholder="SEO Meta Title"
                                            class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" value="{{ old('meta_title') }}">

                                          @if($errors->has('meta_title'))
                                            <div class="invalid-feedback ml-3">{{ $errors->first('meta_title') }}</div>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group row">
                                        <label class="col-md-3 form-control-label">Meta Description</label>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ $procedure->meta_description }}" name="meta_description" placeholder="SEO Meta Description"
                                            class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" value="{{ old('meta_description') }}">

                                          @if($errors->has('meta_description'))
                                            <div class="invalid-feedback ml-3">{{ $errors->first('meta_description') }}</div>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group row">
                                        <label class="col-md-3 form-control-label">URL</label>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ $procedure->url }}" name="url" placeholder="SEO Meta Description"
                                            class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" value="{{ old('url') }}">
                                          @if($errors->has('url'))
                                            <div class="invalid-feedback ml-3">{{ $errors->first('url') }}</div>
                                          @endif
                                        </div>
                                      </div>
                                      <div class="form-group row">
                                        <label class="col-md-3 form-control-label">Meta Heading</label>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ $procedure->meta_heading }}" name="meta_heading" placeholder="SEO Meta Heading"
                                            class="form-control {{ $errors->has('meta_heading') ? 'is-invalid' : '' }}" value="{{ old('meta_heading') }}">
                                          @if($errors->has('meta_heading'))
                                            <div class="invalid-feedback ml-3">{{ $errors->first('meta_heading') }}</div>
                                          @endif
                                        </div>
                                      </div>
                        </div>
                        <div role="tabpanel" class="tab-pane pt-3 in fade" id="ads-tab">
                                <div class="form-group row">
                                        <label class="col-md-2 form-control-label">Landing Page URL </label>
                                        <div class="col-md-10">
                                            <input type="text" name="landing_page_url" placeholder="Landing Page URL"
                                            class="form-control {{ $errors->has('landing_page_url') ? 'is-invalid' : '' }}" value="{{ $procedure->landing_page_url }}" >
                                            @if($errors->has('landing_page_url'))
                                            <div class="invalid-feedback ml-3">{{ $errors->first('landing_page_url') }}</div>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group row">
                                        <label class="col-md-2 form-control-label">Payload English </label>
                                        <div class="col-md-4">
                                            <input type="text" name="payload_en" placeholder="Payload English"
                                            class="form-control {{ $errors->has('payload_en') ? 'is-invalid' : '' }}" value="{{ $procedure->payload_en }}" >
                                            @if($errors->has('payload_en'))
                                            <div class="invalid-feedback ml-3">{{ $errors->first('payload_en') }}</div>
                                          @endif
                                        </div>
                                       <label class="col-md-2 form-control-label">Payload Urdu </label>
                                        <div class="col-md-4">
                                            <input type="text" name="payload_ur" placeholder="Payload Urdu"
                                            class="form-control {{ $errors->has('payload_ur') ? 'is-invalid' : '' }}" value="{{ $procedure->payload_ur }}" >
                                            @if($errors->has('payload_ur'))
                                            <div class="invalid-feedback ml-3">{{ $errors->first('payload_ur') }}</div>
                                          @endif
                                        </div>
                                      </div>
                                      <div class="form-group row">
                                        <label class="col-md-2 form-control-label">Head Line </label>
                                        <div class="col-md-10">
                                          <textarea class="form-control {{ $errors->has('headline') ? 'is-invalid' : '' }}"  maxlength="30" id="textarea1" cols="90" rows="1"  placeholder="Enter headline " name="headline">{{ $procedure->headline }}</textarea>
                                        </div>
                                        @if(!empty($headline))
                                            <div class="invalid-feedback ml-3">{{ $headline }}</div>
                                        @endif
                                      </div>
                                      <div class="form-group row">
                                        <label class="col-md-2 form-control-label">Description Link </label>
                                        <div class="col-md-10">
                                          <textarea class="form-control {{ $errors->has('link_description') ? 'is-invalid' : '' }}"  maxlength="30" id="textarea2" cols="90" rows="1"  placeholder="Enter description link" name="link_description">{{$procedure->link_description}}</textarea>
                                        </div>
                                        @if(!empty($link_description))
                                            <div class="invalid-feedback ml-3">{{ $link_description }}</div>
                                        @endif
                                      </div>
                                      <div class="form-group row">
                                        <label class="col-md-2 form-control-label">Message</label>
                                        <div class="col-md-10">
                                          <textarea class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}"  maxlength="225" id="textarea3" cols="90" rows="4"  placeholder="Enter message" name="message">{{ $procedure->message }}</textarea>
                                        </div>
                                        @if(!empty($message))
                                            <div class="invalid-feedback ml-3">{{ $message }}</div>
                                        @endif
                                      </div>
                        </div>
                  </div>

                <div class="form-group row pt-3">
                  <label class="col-md-2 form-control-label">Picture</label>
                  <div class="col-md-6">
                    <span class="btn btn-success fileinput-button">
                        <span>Select Attachment</span>
                        <input type="file" name="picture[]" id="files" multiple class="form-control {{ $errors->has('picture') ? 'is-invalid' : '' }}"><br />
                    </span>
                    <output id="Filelist"></output>
                    @if($errors->has('picture'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('picture') }}</div>
                    @endif
                  </div>
                  <div class="col-md-4" style="display: flex;">
                    @if($procedure->treatment_image)
                      @foreach($procedure->treatment_image as $image)
                        <div>
                          <div class="img-wrap">
                            <span class="close">×</span>
                            <input type="hidden" name="picture[]"  value="{{$image->picture}}">
                            <img src="{{ asset('backend/uploads/treatments/'.$image->picture) }}" class="img-fluid" alt="{{ $errors->first('treatment_name') }}" height="100" width="100">
                          </div>
                        </div>
                      @endforeach
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 form-control-label">Article Heading</label>
                    <div class="col-md-10">
                      <input type="text" value="{{ $procedure->article_heading }}" name="article_heading" placeholder="Article Heading"
                        class="form-control {{ $errors->has('article_heading') ? 'is-invalid' : '' }}" value="{{ old('article_heading') }}">
                      @if($errors->has('article_heading'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('article_heading') }}</div>
                      @endif
                    </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Article </label>
                  <div class="col-md-10">
                    <textarea placeholder="Enter Details" class="form-control tiny" name="article" id="" cols="30" rows="10">{{ $procedure->article }}</textarea>
                    @if($errors->has('article'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('article') }}</div>
                    @endif
                  </div>
                </div>

                <hr>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Active</label>
                  <div class="col-md-10">
                    <div class="custom-control custom-checkbox">

                      <input id="is_active" value="1" type="checkbox" name="is_active"
                      class="custom-control-input" {{ $procedure->is_active == 1 ? 'checked':'' }}>
                      <label for="is_active" class="custom-control-label">Check to Active the procedure</label>
                    </div>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Show In Menu</label>
                  <div class="col-md-10">
                    <div class="custom-control custom-checkbox">
                      <input id="show_in_menu" {{ $procedure->show_in_menu == 1 ? 'checked':'' }} value="1" type="checkbox" name="show_in_menu" class="custom-control-input">
                      <label for="show_in_menu" class="custom-control-label">Check to show in menu</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-9 ml-auto">
                    <a href="{{ route('procedure.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update changes</button>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-maxlength/1.7.0/bootstrap-maxlength.min.js"></script>
<script type="text/javascript">
      $('#textarea1').maxlength({
            alwaysShow: true,
            threshold: 10,
            warningClass: "label label-success",
            limitReachedClass: "label label-danger",
            separator: '/',
            preText: '',
            postText: '',
            validate: true,
            placement: 'bottom-right'
      });
  </script>
  <script type="text/javascript">
      $('#textarea2').maxlength({
            alwaysShow: true,
            threshold: 10,
            warningClass: "label label-success",
            limitReachedClass: "label label-danger",
            separator: '/',
            preText: '',
            postText: '',
            validate: true,
            placement: 'bottom-right'
      });
  </script>
  <script type="text/javascript">
      $('#textarea3').maxlength({
            alwaysShow: true,
            threshold: 10,
            warningClass: "label label-success",
            limitReachedClass: "label label-danger",
            separator: '/',
            preText: '',
            postText: '',
            validate: true,
            placement: 'bottom-right'
      });
  </script>
  <script>
  $(document).on('keyup','#procedure_input', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
    var procedure_name  = $("#procedure_input").val();
    $.ajax({
      type:'post',
      url:"{{ route('procedure-live-search') }}",
      data: { procedure_name : procedure_name},
      success: function(response){
        $('#procedures-ajax-list').html(response);
      }
    });
  });
  </script>
@endsection
