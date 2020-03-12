@extends('doctorpanel.layout')
@section('title', 'Client | Patients')
@section('styles')
  <link rel="stylesheet" href="{{ asset('backend/css/fileupload.css') }}">
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

@endsection
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      @include('adminpanel.notification')
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">{{ $customer->name}}'s Documents</h3>
          </div>
          <div class="card-body">
            @if($customer->deleted_at == null)
              <form class="form-horizontal" method="POST" action="{{ route('Upload_Customer_files', $customer->id) }}" enctype="multipart/form-data">
              @csrf
              @method('POST')
                  <div class="form-group row">
                      <label class="col-md-2 form-control-label">Title </label>
                      <div class="col-md-4">
                        <input class="form-control" name="title" value="{{ old('title') }}" required>
                      </div>
                      @if($errors->has('title'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('title') }}</div>
                      @endif
                      <label class="col-md-2 form-control-label">Description</label>
                      <div class="col-md-4">
                        <textarea class="form-control" name="description" required>{{old('description')}}</textarea>
                      </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <div class="row">
                      <label class="col-md-4 form-control-label">Pictures </label>
                      <div class="col-md-8 mb-20">
                        <span class="btn btn-success fileinput-button">
                          <span>Select Pictures</span>
                          <input type="file" name="pictures[]" multiple id="files2" class="form-control {{ $errors->has('files') ? 'is-invalid' : '' }}"><br />
                        </span>

                        @if($errors->has('files'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('files') }}</div>
                        @endif
                      </div>
                      <label class="col-md-4 form-control-label mt-3">New Uploads</label>
                      <div class="col-md-8">
                        <output id="Filelist2"></output>
                      </div>
                      </div>
                    </div>

                    <!-- Right Side Section for Files -->
                      <div class="col-md-6">
                        <div class="row">
                        <label class="col-md-4 form-control-label">Files </label>
                        <div class="col-md-8 mb-20">
                          <input type="file" class="form-control" name="files[]" multiple />
                          @if($errors->has('files'))
                          <div class="invalid-feedback ml-3">{{ $errors->first('files') }}</div>
                          @endif
                        </div>

                        <label class="col-md-4 form-control-label">Type</label>
                        <div class="col-md-8">
                          <select  name="type" class="form-control selectpicker" required>
                            <option value="">Select Type</option>
                            <option   value="P"   {{ "P" === old('type') ? 'selected' : '' }}>Prescription</option>
                            <option   value="L"   {{ "L" === old('type') ? 'selected' : '' }}>Lab Reports</option>
                            <option   value="R"   {{ "R" === old('type') ? 'selected' : '' }}>Radiology</option>
                            <option   value="O"   {{ "O" === old('type') ? 'selected' : '' }}>Other</option>
                          </select>
                        </div>
                        </div>
                      </div>

                  </div>
                  <div class="row mb-20">
                    <div class="col-md-12 text-center">
                      <button class="btn btn-sm btn-dark" style="width: 15%">Upload</button>
                    </div>
                  </div>
                </form>
                  @endif
                <ul class="nav nav-tabs customer-nav" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" href="#prescriptions-tab" role="tab" data-toggle="tab" >Prescriptions</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " href="#lab-tab" role="tab" data-toggle="tab">Lab Reports</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " href="#radiology-tab" role="tab" data-toggle="tab">Radiology</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " href="#other-tab" role="tab" data-toggle="tab">Other Documents</a>
                  </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                 <div role="tabpanel" class="tab-pane pt-3 in active" id="prescriptions-tab">
                    <ul class="nav nav-tabs files-nav" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" href="#pimages-tab" role="tab" data-toggle="tab" >Images</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link " href="#pfiles-tab" role="tab" data-toggle="tab">Files</a>
                      </li>
                    </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                 <div role="tabpanel" class="tab-pane pt-3 in active" id="pimages-tab">
                  <div class="row">
                    <label class="col-md-12 form-control-label">Uploads</label>
                      @if(isset($data))
                      @foreach($data as $image)
                      @php $p = 0; @endphp
                      @if($image->type == "P" && $image->file_type == "image")
                        <div class="col-md-4">
                          <div class="thumbnail">
                            <a href="#" class="pop">
                              <img class="img-responsive image-documents center" src="{{ asset('backend/uploads/customer_documents/'.$image->slug) }}" alt="{{$image->description}}">
                              <div class="caption">
                                <h6 class="h6-thumbnail">{{$image->title}}</h6>
                                @php  $date           =   Carbon\Carbon::parse($image->created_at);
                                      $created_at     =   $date->format('jS F Y'); @endphp
                                <p>{{$image->description}}</p>
                                <span class="span-thumbnail">{{$created_at}}</span>
                              </div>
                            </a>
                          </div>
                        </div>
                      @endif
                      @endforeach
                      @endif
                  </div>
                </div>
                 <div role="tabpanel" class="tab-pane pt-3" id="pfiles-tab">
                    @if(isset($data))
                    <div class="card-body table-responsive">
                      <table class="table table-striped table-dark table-sm card-text" id="prescriptions">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>File Type</th>
                            <th>Created By</th>
                            <th>Created On</th>
                          </tr>
                        </thead>
                        <tbody>
                              @php $no=1 @endphp
                              @foreach($data as $file)
                              @if($file->type == "P" && $file->file_type != "image")
                              <tr>
                                <th scope="row">{{$no++}}</th>
                                <td> <a target="_blank" href="https://support.hospitallcare.com/backend/uploads/customer_documents/{{$file->slug}}">{{ $file->title }}</a></td>
                                <td>{{ $file->description }}</td>
                                <td>{{ $file->file_type }}</td>
                                @php
                                if($file->created_by != NULL){
                                  $doctor_name = doctorName($file->created_by);
                                } else {
                                  $doctor_name  = 'Self';
                                }
                                @endphp
                                <td>{{ $doctor_name }}</td>
                                <td>{{ date('d-m-Y',strtotime($file->created_at)) }}</td>
                              </tr>
                              @endif
                              @endforeach
                        </tbody>
                      </table>
                    </div>
                    @endif
                 </div>
              </div>
                 </div><!-- End of Prescription tab -->

                   <div role="tabpanel" class="tab-pane pt-3" id="lab-tab">
                   <ul class="nav nav-tabs files-nav" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" href="#limages-tab" role="tab" data-toggle="tab" >Images</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link " href="#lfiles-tab" role="tab" data-toggle="tab">Files</a>
                      </li>
                    </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                 <div role="tabpanel" class="tab-pane pt-3 in active" id="limages-tab">
                  <div class="row ">
                    <label class="col-md-12 form-control-label">Uploads</label>

                      @if(isset($data))
                      @foreach($data as $image)
                      @if($image->type == "L" && $image->file_type == "image")
                        <div class="col-md-4">
                          <div class="thumbnail">
                            <a href="#" class="pop">
                              <img class="img-responsive image-documents center" src="{{ asset('backend/uploads/customer_documents/'.$image->slug) }}" alt="{{$image->description}}">
                              <div class="caption">
                                <h6 class="h6-thumbnail">{{$image->title}}</h6>
                                @php  $date           =   Carbon\Carbon::parse($image->created_at);
                                      $created_at          =   $date->format('jS F Y'); @endphp
                                <span class="span-thumbnail">{{$created_at}}</span>
                              </div>
                            </a>
                          </div>
                        </div>
                      @endif
                      @endforeach
                      @endif


                  </div>
                </div>
                 <div role="tabpanel" class="tab-pane pt-3" id="lfiles-tab">
                    @if(isset($data))
                    <div class="card-body table-responsive">
                      <table class="table table-striped table-dark table-sm card-text" id="lab">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>File Type</th>
                            <th>Created By</th>
                            <th>Created On</th>
                          </tr>
                        </thead>
                        <tbody>
                              @php $no=1 @endphp
                              @foreach($data as $file)
                              @if($file->type == "L" && $file->file_type != "image")
                              <tr>
                                <th scope="row">{{$no++}}</th>
                                <td> <a target="_blank" href="https://support.hospitallcare.com/backend/uploads/customer_documents/{{$file->slug}}">{{ $file->title }}</a></td>
                                <td>{{ $file->description }}</td>
                                <td>{{ $file->file_type }}</td>
                                @php
                                if($file->created_by != NULL){
                                  $doctor_name = doctorName($file->created_by);
                                } else {
                                  $doctor_name  = 'Self';
                                }
                                @endphp
                                <td>{{ $doctor_name }}</td>
                                <td>{{ date('d-m-Y',strtotime($file->created_at)) }}</td>
                              </tr>
                              @endif
                              @endforeach
                        </tbody>
                      </table>
                    </div>
                    @endif
                 </div>
              </div>
                   </div><!--End of Lab Tab -->

                   <div role="tabpanel" class="tab-pane pt-3" id="radiology-tab">
                   <ul class="nav nav-tabs files-nav" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" href="#rimages-tab" role="tab" data-toggle="tab" >Images</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link " href="#rfiles-tab" role="tab" data-toggle="tab">Files</a>
                      </li>
                    </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                 <div role="tabpanel" class="tab-pane pt-3 in active" id="rimages-tab">
                  <div class="row mt-3">
                    <label class="col-md-12 form-control-label">Uploads</label>

                      @if(isset($data))
                      @foreach($data as $image)
                      @if($image->type == "R" && $image->file_type == "image")
                        <div class="col-md-4">
                          <div class="thumbnail">
                            <a href="#" class="pop">
                              <img class="img-responsive image-documents center" src="{{ asset('backend/uploads/customer_documents/'.$image->slug) }}" alt="{{$image->description}}">
                              <div class="caption">
                                <h6 class="h6-thumbnail">{{$image->title}}</h6>
                                @php  $date           =   Carbon\Carbon::parse($image->created_at);
                                      $created_at          =   $date->format('jS F Y'); @endphp
                                <span class="span-thumbnail">{{$created_at}}</span>
                              </div>
                            </a>
                          </div>
                        </div>
                      @endif
                      @endforeach
                      @endif


                  </div>
                </div>
                 <div role="tabpanel" class="tab-pane pt-3" id="rfiles-tab">
                    @if(isset($data))
                    <div class="card-body table-responsive">
                      <table class="table table-striped table-dark table-sm card-text" id="radiology">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>File Type</th>
                            <th>Created By</th>
                            <th>Created On</th>
                          </tr>
                        </thead>
                        <tbody>
                              @php $no=1 @endphp
                              @foreach($data as $file)
                              @if($file->type == "R" && $file->file_type != "image")
                              <tr>
                                <th scope="row">{{$no++}}</th>
                                <td> <a target="_blank" href="https://support.hospitallcare.com/backend/uploads/customer_documents/{{$file->slug}}">{{ $file->title }}</a></td>
                                <td>{{ $file->description }}</td>
                                <td>{{ $file->file_type }}</td>
                                @php
                                if($file->created_by != NULL){
                                  $doctor_name = doctorName($file->created_by);
                                } else {
                                  $doctor_name  = 'Self';
                                }
                                @endphp
                                <td>{{ $doctor_name }}</td>
                                <td>{{ date('d-m-Y',strtotime($file->created_at)) }}</td>
                              </tr>
                              @endif
                              @endforeach
                        </tbody>
                      </table>
                    </div>
                    @endif
                   </div>
                </div>
                   </div><!--End of Lab Tab -->

                  <div role="tabpanel" class="tab-pane pt-3" id="other-tab">
                    <ul class="nav nav-tabs files-nav" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" href="#oimages-tab" role="tab" data-toggle="tab" >Images</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link " href="#ofiles-tab" role="tab" data-toggle="tab">Files</a>
                      </li>
                    </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                 <div role="tabpanel" class="tab-pane pt-3 in active" id="oimages-tab">
                  <div class="row mt-3">
                    <label class="col-md-12 form-control-label">Uploads</label>

                      @if(isset($data))
                      @foreach($data as $image)
                      @if($image->type == "O" && $image->file_type == "image")
                        <div class="col-md-4">
                          <div class="thumbnail">
                            <a href="#" class="pop">
                              <img class="img-responsive image-documents center" src="{{ asset('backend/uploads/customer_documents/'.$image->slug) }}" alt="{{$image->description}}">
                              <div class="caption">
                                <h6 class="h6-thumbnail">{{$image->title}}</h6>
                                @php  $date           =   Carbon\Carbon::parse($image->created_at);
                                      $created_at          =   $date->format('jS F Y'); @endphp
                                <span class="span-thumbnail">{{$created_at}}</span>
                              </div>
                            </a>
                          </div>
                        </div>
                      @endif
                      @endforeach
                      @endif
                  </div>
                </div>
                 <div role="tabpanel" class="tab-pane pt-3" id="ofiles-tab">
                    @if(isset($data))
                    <div class="card-body table-responsive">
                      <table class="table table-striped table-dark table-sm card-text" id="other">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>File Type</th>
                            <th>Created By</th>
                            <th>Created On</th>
                          </tr>
                        </thead>
                        <tbody>
                              @php $no=1 @endphp
                              @foreach($data as $file)
                              @if($file->type == "O" && $file->file_type != "image")
                              <tr>
                                <th scope="row">{{$no++}}</th>
                                <td> <a target="_blank" href="https://support.hospitallcare.com/backend/uploads/customer_documents/{{$file->slug}}">{{ $file->title }}</a></td>
                                <td>{{ $file->description }}</td>
                                <td>{{ $file->file_type }}</td>
                                @php
                                if($file->created_by != NULL){
                                  $doctor_name = doctorName($file->created_by);
                                } else {
                                  $doctor_name  = 'Self';
                                }
                                @endphp
                                <td>{{ $doctor_name }}</td>
                                <td>{{ date('d-m-Y',strtotime($file->created_at)) }}</td>
                              </tr>
                              @endif
                              @endforeach
                        </tbody>
                      </table>
                    </div>
                    @endif
                   </div>
                </div>
                   </div><!--End of Lab Tab -->

                </div><!--End of All Tabs -->
              </div>
        </div>
      </div>
    </div>
  </section>
</div>
<!-- Image Modal -->
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

@endsection
@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
  <script src="{{ asset('backend/js/fileupload.js') }}" ></script>
  <script src="{{ asset('backend/js/fileupload2.js') }}" ></script>
  <script src="{{asset('backend/js/bootstrap-imageupload.js')}}"></script>
  <script>
    $(function() {
     $('.pop').on('click', function() {
       $('.imagepreview').attr('src', $(this).find('img').attr('src'));
       $('#imagemodal').modal('show');
     });
   });
 </script>
 <script>
  var $imageupload = $('.imageupload');
  $imageupload.imageupload();
</script>
<script>
$(document).ready(function() {
    $('#prescriptions').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
<script>
$(document).ready(function() {
    $('#lab').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
<script>
$(document).ready(function() {
    $('#radiology').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
<script>
$(document).ready(function() {
    $('#other').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
@endsection
