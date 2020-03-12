@extends('doctorpanel.layout')
@section('title',$doctor->name .'| HospitALL')
@section('content')
@include('adminpanel.notification')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="container emp-profile">
        <div class="row">
            <div class="col-md-4">
                <div class="profile-img">
                    @if(isset($doctor->doctor_image))
                    <img src="{{ asset('backend/uploads/doctors/'.$doctor->doctor_image->picture) }}" alt="">
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="profile-head">
                    <h5>{{$doctor->name}} {{$doctor->last_name}}</h5>
                    <h6>{{$doctor->focus_area}}</h6>
                    <p class="proile-rating">RANKINGS : <span>8/10</span></p>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item nav-at-profile">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">About</a>
                        </li>
                        @php
                        if($doctor_schedules != null){
                        $schedule_count = count($doctor_schedules);
                        for($i = 0; $i < $schedule_count; $i++){
                        $id = $i+1;
                        @endphp
                        <li class="nav-item nav-at-profile">
                          <a class="nav-link" id="profile-tab" data-toggle="tab" href="#schedule{{$id}}" role="tab" aria-controls="schedule{{$id}}" aria-selected="false">Center {{$id}}</a>
                      </li>
                      @php } } @endphp
                      <li class="nav-item nav-at-profile">
                          <a class="nav-link" id="partnership-tab" data-toggle="tab" href="#partnership" role="tab" aria-controls="partnership" aria-selected="false">Partnership</a>
                      </li>
                      <li class="nav-item nav-at-profile">
                        <a class="nav-link" id="qualification-tab" data-toggle="tab" href="#qualification" role="tab" aria-controls="qualification" aria-selected="false">Qualification</a>
                    </li>
                    <li class="nav-item nav-at-profile">
                        <a class="nav-link" id="certification-tab" data-toggle="tab" href="#certification" role="tab" aria-controls="certification" aria-selected="false">Certification</a>
                    </li>
                    <li class="nav-item nav-at-profile">
                      <a class="nav-link" id="notes-tab" data-toggle="tab" href="#notes" role="tab" aria-controls="notes" aria-selected="false">Notes</a>
                  </li>

              </ul>
          </div>
      </div>
      <div class="col-md-2">
        <a href="{{ route('doctor_profile_edit') }}" class="profile-edit-btn a-doctor-btn" >
          Edit Doctor
      </a>
  </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="profile-work">
            <p>Personal Details</p>
            <a href="">{{$doctor->name}}</a><br/>
            <a href="">{{$doctor->last_name}}</a><br/>
            <a href="">{{$doctor->pmdc}}</a><br/>
            @php
            if(isset($doctor->experience)){
            $experience     =   YearsDiff($doctor->experience);
        } else {
        $experience     = Null;
    }
    @endphp
    <a href="">{{$experience}} of Experience</a><br/>
    <a href="">{{$doctor->phone}}</a>
    <p>Assistant's Details</p>
    <a href="">{{$doctor->assistant_name}}</a><br/>
    <a href="">{{$doctor->assistant_phone}}</a><br/>

</div>
</div>
<div class="col-md-8">
    <div class="tab-content profile-tab" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <div class="row">
            <div class="col-md-6">
                <label>First Name</label>
            </div>
            <div class="col-md-6">
                <p>{{$doctor->name}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label>Last Name</label>
            </div>
            <div class="col-md-6">
                <p>{{$doctor->last_name}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label>Email</label>
            </div>
            <div class="col-md-6">
                <p>{{$doctor->email}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label>Experience</label>
            </div>
            <div class="col-md-6">
                <p>{{$experience}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label>Doctor's Phone</label>
            </div>
            <div class="col-md-6">
                <p>{{$doctor->phone}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label>Address</label>
            </div>
            <div class="col-md-6">
                <p>{{$doctor->address}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label>Assistant's Name</label>
            </div>
            <div class="col-md-6">
                <p>{{$doctor->assistant_name}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label>Assistant's Phone</label>
            </div>
            <div class="col-md-6">
                <p>{{$doctor->assistant_phone}}</p>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="partnership" role="tabpanel" aria-labelledby="partnership-tab">
      <div class="row">
        <div class="col-md-6">
            <label>Ad Spent</label>
        </div>
        <div class="col-md-6">
            <p>{{$doctor->ad_spent}}/-</p>
        </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <label>Revenue Spent</label>
    </div>
    <div class="col-md-6">
        <p>{{$doctor->revenue_share}}/-</p>
    </div>
</div>
<div class="row">
  <div class="col-md-6">
    <label>Files</label>
</div>
<div class="col-md-6">
  @if(isset($doctor->doctor_partnership_files))
  @foreach($doctor->doctor_partnership_files as $prtnr_file)
  <a href="#" class="pop_pdf">
      @php
      $sprt = explode('.',$prtnr_file->file);
      @endphp
      {{ $sprt[1] }}
      <img id="zoom_mw" data-zoom-image="large/image1.jpg"/ src="{{ asset('backend/uploads/doctor_partnership_files/'.$prtnr_file->file) }}" alt="" class="col-md-6 mt-1 responsive" max-height="200px" max-width="200px">
  </a>
  <br>
  @endforeach
  @endif
</div>
</div>

<div class="row">
  <div class="col-md-6">
    <label>Images</label>
</div>
<div class="col-md-6">
    @if(isset($doctor->doctor_partnership_images))
    @foreach($doctor->doctor_partnership_images as $prtnr_image)
    <a href="#" class="pop">
        <img id="zoom_mw" data-zoom-image="large/image1.jpg"/ src="{{ asset('backend/uploads/doctor_partnership_images/'.$prtnr_image->picture) }}"
        alt="" class="col-md-3 mt-1 responsive" max-height="100px" max-width="100px">
    </a>
    @endforeach
    @endif
</div>
</div>

<div class="row">
    <div class="col-md-6">
        <label>Additional Details:</label>
    </div>
</div>
<div class="row">
    <div class="col-md-9 offset-2 text-justify">
        <label>{!! $doctor->additional_details !!}</label>
    </div>
</div>
</div>
<div class="tab-pane fade" id="qualification" role="tabpanel" aria-labelledby="qualification-tab">
    <?php if(isset($doctor_qualification)){
        foreach($doctor_qualification as $qualification){
            ?>
            <div class="row">
                <div class="col-md-6">
                    <label>Degree</label>
                </div>
                <div class="col-md-6">
                    <p>{{$qualification->degree}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>University</label>
                </div>
                <div class="col-md-6">
                    <p>{{$qualification->university}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>Country</label>
                </div>
                <div class="col-md-6">
                    <p>{{$qualification->country}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>Year</label>
                </div>
                <div class="col-md-6">
                    <p>{{$qualification->graduation_year}}</p>
                </div>
            </div>
            <hr>
        <?php } }?>
    </div>
    <div class="tab-pane fade" id="certification" role="tabpanel" aria-labelledby="certification-tab">
        <?php if(isset($doctor_certification)){
            foreach($doctor_certification as $certification){
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <label>Title</label>
                    </div>
                    <div class="col-md-6">
                        <p>{{$certification->title}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>University</label>
                    </div>
                    <div class="col-md-6">
                        <p>{{$certification->university}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>Country</label>
                    </div>
                    <div class="col-md-6">
                        <p>{{$certification->country}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>Year</label>
                    </div>
                    <div class="col-md-6">
                        <p>{{$certification->year}}</p>
                    </div>
                </div>
                <hr>
            <?php } }?>
        </div>
        <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
          <div class="row">
            <div class="col-md-6">
                <label>Notes:</label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9 offset-2 text-justify">
                <label>{!! $doctor->notes !!}</label>
            </div>
        </div>
    </div>
    @php
    if($doctor_schedules != null){
    $schedule_count = count($doctor_schedules);
    for($i = 0; $i < $schedule_count; $i++){
    $id                       = $i+1;
    $center_name              = isset($doctor_schedules[$i])? $doctor_schedules[$i]->center_name: '';
    $consultation_fee         = isset($doctor_schedules[$i])? $doctor_schedules[$i]->fare: '';
    $discounted_fee           = isset($doctor_schedules[$i])? $doctor_schedules[$i]->discount: '';
    $appointment_duration     = isset($doctor_schedules[$i])? $doctor_schedules[$i]->appointment_duration: '';
    $is_primary               = isset($doctor_schedules[$i])? $doctor_schedules[$i]->is_primary: '';
    @endphp
    <div class="tab-pane fade" id="schedule{{$id}}" role="tabpanel" aria-labelledby="profile-tab">
        <div class="row">
          <div class="col-md-6">
              <label style="color: red">@if($is_primary == 1)
                Primary Location
            @endif</label>
        </div>
    </div>
    <div class="row">
      <div class="col-md-6">
          <label>Center</label>
      </div>
      <div class="col-md-6">
          <p>{{$center_name}}</p>
      </div>
  </div>
  <div class="row">
      <div class="col-md-6">
          <label>Consultation Fee</label>
      </div>
      <div class="col-md-6">
          <p>Rs. {{$consultation_fee}}/-</p>
      </div>
  </div>
  <div class="row">
      <div class="col-md-6">
          <label>Discounted Fee</label>
      </div>
      <div class="col-md-6">
          <p>Rs. {{$discounted_fee}}/-</p>
      </div>
  </div>
  <div class="row">
      <div class="col-md-6">
          <label>Appointment Duration</label>
      </div>
      <div class="col-md-6">
          <p>{{$appointment_duration}} Minutes</p>
      </div>
  </div>
  @php
  $day_from         =   $doctor_schedules[$i]->day_from;
  $day_to           =   $doctor_schedules[$i]->day_to;
  $time_from        =   $doctor_schedules[$i]->time_from;
  $time_to          =   $doctor_schedules[$i]->time_to;

  $day_from         =   explode(",",$day_from);
  $day_to           =   explode(",",$day_to);
  $time_from        =   explode(",",$time_from);
  $time_to          =   explode(",",$time_to);
  $j = 0;
  foreach($day_from as $df){

  @endphp
  <div class="row">
      <div class="col-md-6">
          <label>Schedule {{$j+1}} </label>
      </div>
      <div class="col-md-6">
          <p>{{$df}}-{{$day_to[$j]}} FROM {{$time_from[$j]}}-{{$time_to[$j]}}</p>
      </div>
  </div>
  @php $j++; } @endphp
</div>
@php } }@endphp
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
@endsection
@section('scripts')
<script src="{{ asset('backend/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/tinymce-config.js') }}" ></script>
<script>
 $(function() {
    $('.pop').on('click', function() {
      $('.imagepreview').attr('src', $(this).find('img').attr('src'));
      $('#imagemodal').modal('show');
  });
});
</script>
<script>
 $(function() {
    $('.pop_pdf').on('click', function() {
      $('.pdfpreview').attr('src', $(this).find('img').attr('src'));
      $('#pdfmodal').modal('show');
  });
});
</script>
<script>
  $(document).ready(function() {
    $('#treatments1').dataTable( {
        "lengthMenu": [ [5, 10, 15, -1], [5, 10, 15, "All"] ],
        "pageLength": 5
    });
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
<script>
  $(document).ready(function() {
    $('#treatments2').dataTable( {
        "lengthMenu": [ [5, 10, 15, -1], [5, 10, 15, "All"] ],
        "pageLength": 5
    });
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
@endsection

