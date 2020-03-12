@extends('adminpanel.layout')
@section('title', 'Schedule | HospitALL')
@section('styles')
<link rel="stylesheet" href="{{ asset('backend/js/select2-develop/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/fileupload.css') }}">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      @include('doctorpanel.notification')
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0"> {{$center->center_name}}</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('doctor_schedule_update', $center->id) }}" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
              <!-- Edit Fare and Discounts -->
              <div class="form-group row"><div class="col-md-12 text-center form-control-label">Fare and Discounts</div></div>
              <div class="form-group row">
                <div class="col-md-2 form-control-label">Fare</div>
                <input type="number" name="fare" class="col-md-3 form-control" placeholder="Fare" value="{{ $center_schedule[0]->fare }}">
                <div class="col-md-2 form-control-label">Discounted Fare</div>
                <input type="number" name="discount" class="col-md-3 form-control" placeholder="Discounted Fare" value="{{ $center_schedule[0]->discount }}">

              </div>
              <div class="form-group row">
                <div class="col-md-2 form-control-label">Appointment Duration (Mins)</div>
                <input type="number" name="appointment_duration" class="col-md-3 form-control" placeholder="Appointment Duration" value="{{ $center_schedule[0]->appointment_duration }}">
                @php
                $toggle = isset($center_schedule[0]) ? $center_schedule[0]->is_primary : NULL;
                @endphp
                  <div class="col-md-2 form-control-label">Primary Loc:</div>
                  <input type="checkbox" class="col-md-3 text-center" style="width: 100%;" name="is_primary" data-toggle="toggle" {{ $toggle == 1 ? 'checked':'' }} data-onstyle="dark" data-offstyle="light" data-style="border">
              </div>

              <!-- Edit Center Schedule -->
              <div class="form-group row"><div class="col-md-12 text-center form-control-label">Schedules</div></div>

              <?php $j = 0; ?>
              @foreach($center_schedule as $s)
              <div id="dynamic_for_add_time1">
                <div class="form-group row" id="add_time_for1{{$j+1}}">
                  <div class="col-md-10">
                    <div class="row form-group">
                      <div class="col-md-2 form-control-label">Day From</div>
                      <div class="col-md-4">
                        @php $days = get_days(); @endphp
                        <select class="form-control " name=" day_from[]">

                          @foreach($days as $day)
                          <option value="{{$day}}" {{ ($day == $s->day_from) ? 'selected' : ''}}>{{$day}}</option>
                          @endforeach
                        </select>
                        @if($errors->has(' day_from'))
                        <div class="invalid-feedback ml-3">{{ $errors->first(' day_from') }}</div>
                        @endif
                      </div>
                      <div class="col-md-2 form-control-label">Day to</div>
                      <div class="col-md-4">
                        @php $days = get_days(); @endphp
                        <select class="form-control " name=" day_to[]">

                          @foreach($days as $day)
                          <option value="{{$day}}" {{ ($day == $s->day_to) ? 'selected' : ''}}>{{$day}}</option>
                          @endforeach
                        </select>
                        @if($errors->has(' day_to'))
                        <div class="invalid-feedback ml-3">{{ $errors->first(' day_to') }}</div>
                        @endif
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-md-2 form-control-label">Time From</div>
                      <div class="col-md-4">
                        <!-- <select class="form-control " name=" time_from[]"></select> -->
                        <input type="time" name="time_from[]" class="form-control" value="{{$s->time_from}}">
                        @if($errors->has(' time_from'))
                        <div class="invalid-feedback ml-3">{{ $errors->first(' time_from') }}</div>
                        @endif
                      </div>
                      <div class="col-md-2 form-control-label">Time to</div>
                      <div class="col-md-4">
                        <input type="time" name="time_to[]" class="form-control" value="{{$s->time_to}}">
                        @if($errors->has('time_to'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('time_to') }}</div>
                        @endif
                      </div>
                    </div>
                  </div>
                  <?php if ($j == 0) {?>
                    <div class="align-self-center">
                      <button type="button" name="add" id="add-time1" class="btn btn-info">Add More Time</button>
                    </div>
                  <?php } else {?>
                    <div class="align-self-center">
                      <button type="button" name="remove" id="add_time_for1{{$j+1}}" class="btn btn-danger btn_remove_time1">Remove Time</button>
                    </div>
                  <?php }?>
                </div>
              </div>
              <?php $j++;?>
              @endforeach
              <div class="form-group row">
                <div class="col-md-12 text-center">
                  <button type="submit" class="btn btn-primary">Update Doctor</button>
                </div>
              </div>
              <hr>
              <!--END of-When There is One Schedule Added Already -->
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
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>
<script src="{{ asset('backend/js/bootstrap-inputmask.min.js') }}"></script>
<script type="text/javascript">
  $(function() {
    $('#datetimepicker1').datetimepicker({
      pickDate: false
    });
  });
</script>
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
      html += '<select class="form-control " name=" day_from[]">';
      html += '<?php foreach($days as $day){?><option value="<?php echo $day ?>"><?php echo $day ?></option><?php }?>';
      html += '</select>';
      html += '</div>';
      html += '<div class="col-md-2 form-control-label">Day to</div>';
      html += '<div class="col-md-4">';
      html += '<select class="form-control " name=" day_to[]">';
      html += '<?php foreach($days as $day){?><option value="<?php echo $day ?>"><?php echo $day ?></option><?php }?>';
      html += '</select>';
      html += '</div>';
      html += '</div>';
      html += '<div class="form-group row">';
      html += '<div class="col-md-2 form-control-label">Time From</div>';
      html += '<div class="col-md-4">';
      html += '<input type="time" name="time_from[]" class="form-control">';
      html += '</div>';
      html += '<div class="col-md-2 form-control-label">Time to</div>';
      html += '<div class="col-md-4">';
      html += '<input type="time" name="time_to[]" class="form-control">';
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
    $(document).on('click', '.btn_remove_time1', function(){
      var button_id = $(this).attr("id");
      $('#'+button_id+'').remove();
    });
  });
</script>

<script>
  $(document).ready(function(){
    var k=700;
    $('#add-time0').click(function(){
      k++;
      var html = '';
      html += '<div class="form-group row" id="row-time0'+k+'">';
      html += '<div class="col-md-10">';
      html += '<div class="row form-group">';
      html += '<div class="col-md-2 form-control-label">Day From</div>';
      html += '<div class="col-md-4"><?php $days = get_days();?>';
      html += '<select class="form-control " name=" day_from[]">';
      html += '<?php foreach($days as $day){?><option value="<?php echo $day ?>"><?php echo $day ?></option><?php }?>';
      html += '</select>';
      html += '</div>';
      html += '<div class="col-md-2 form-control-label">Day to</div>';
      html += '<div class="col-md-4">';
      html += '<select class="form-control " name=" day_to[]">';
      html += '<?php foreach($days as $day){?><option value="<?php echo $day ?>"><?php echo $day ?></option><?php }?>';
      html += '</select>';
      html += '</div>';
      html += '</div>';
      html += '<div class="form-group row">';
      html += '<div class="col-md-2 form-control-label">Time From</div>';
      html += '<div class="col-md-4">';
      html += '<select class="form-control " name=" time_from[]"><?php echo get_times(); ?></select>';
      html += '</div>';
      html += '<div class="col-md-2 form-control-label">Time to</div>';
      html += '<div class="col-md-4">';
      html += '<select class="form-control " name="time_to[]"><?php echo get_times_to(); ?></select>';
      html += '</div>';
      html += '</div>';
      html += '</div>';
      html += '<div class="align-self-center">';
      html += '<button type="button" name="remove" id="'+k+'" class="btn btn-danger btn_remove">Remove Time</button>';
      html += '</div>';
      html += '</div>';
      html += '</div>';

      $('#dynamic_for_add_time0').append(html);
    });

    $(document).on('click', '.btn_remove', function(){
      var button_id = $(this).attr("id");
      $('#row-time0'+button_id+'').remove();
    });
    $(document).on('click', '.btn_remove_time0', function(){
      var button_id = $(this).attr("id");
      $('#'+button_id+'').remove();
    });
  });
</script>
@endsection

