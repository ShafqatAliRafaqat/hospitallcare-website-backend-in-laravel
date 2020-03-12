@section('scripts')
<script src="{{ asset('backend/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/tinymce-config.js') }}" ></script>
<script src="{{ asset('backend/js/bootstrap-inputmask.min.js') }}"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
<!-- validation -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>
<script>

// dynamic input fields for Allergies Notes
$(document).ready(function(){
  var i=0;
  $('#add_allergies_notes').click(function(){
    i++;
    var html = '';
  html += '<div class="form-group row" id="allergies_notes_'+i+'">';
  html += '<label class="col-md-2 form-control-label">Allergies '+i+'</label>';
  html += '<div class="col-md-8">';
  html += '<input type="text" placeholder="Enter Details" class="form-control" name="allergies_notes[]" id="" />';
  html += '</div>';
  html += '<div class="col-md-2 form-control-label text-center"> <button type="button" name="remove" id="allergies_notes_'+i+'" class="btn btn-danger btn_remove">X</button></div>';
  html += '</div>';
  $('#dynamic_field_allergies_notes').append(html);
  });

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id");
    $('#'+button_id+'').remove();
  });
  
});
// end of dynamic fields for Allergies Notes
</script>
<script>
// dynamic input fields for Risk factor Notes
$(document).ready(function(){
  var i=0;
  $('#add_riskfactor_notes').click(function(){
    i++;
    var html = '';
  html += '<div class="form-group row" id="riskfactor_notes_'+i+'">';
  html += '<label class="col-md-2 form-control-label">Risk Factor '+i+'</label>';
  html += '<div class="col-md-8">';
  html += '<input type="text" placeholder="Enter Details" class="form-control" name="riskfactor_notes[]" id="" />';
  html += '</div>';
  html += '<div class="col-md-2 form-control-label text-center"> <button type="button" name="remove" id="riskfactor_notes_'+i+'" class="btn btn-danger btn_remove">X</button></div>';
  html += '</div>';
  $('#dynamic_field_riskfactor_notes').append(html);
  });

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id");
    $('#'+button_id+'').remove();
  });
});
// end of dynamic fields for Risk factor Notes
</script>
<script>
jQuery.validator.setDefaults({
 debug: false,
 success: "valid"
});
$( "#customer-form" ).validate({
 rules: {
   employee_code: {
     required: function(element) {
       return $("#organization").val() > 0;
     }
   }
 }
});
</script>
<script>

$(document).on('change','#treatment', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getTreatments') }}",
      data: { id : id},
      success: function(response){
        $('#procedure').html(response);
      }
    });
  });
$(document).on('change','#treatment', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getCenters') }}",
      data: { id : id},
      success: function(response){
        $('#centers').html(response);
      }
    });
  });

  $(document).on('change','#procedure', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id = $(this).val();
    if(id == 0){
         id  = $("#treatment option:selected").val();
    }
    $.ajax({
      type:'post',
      url:"{{ route('getCenters') }}",
      data: { id : id},
      success: function(response){
        $('#centers').html(response);
      }
    });
  });
  $(document).on('change','#centers', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var procedure_id  = $("#procedure option:selected").val();
    if(procedure_id == 0){
         procedure_id  = $("#treatment option:selected").val();
    }
    var center_id     = $("#centers option:selected").val();
    $.ajax({
      type:'post',
      url:"{{ route('getDoctors') }}",
      data: { procedure_id : procedure_id, center_id : center_id},
      success: function(response){
        $('#doctors').html(response);
      }
    });
  });
  $(document).on('change','#doctors', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var procedure_id  = $("#procedure option:selected").val();
    var doctor_id     = $("#doctors option:selected").val();
    var center_id     = $("#centers option:selected").val();
    

    $.ajax({
      type:'post',
      url:"{{ route('getDocCost') }}",
      data: { procedure_id : procedure_id, doctor_id : doctor_id, center_id : center_id},
      success: function(response){
        $('#cost').html(response);
      }
    });
  });
  $(document).on('change','#doctors', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  var center_id     = $("#centers option:selected").val();
  var doctor_id     = $("#doctors option:selected").val();
  $.ajax({
    type:'post',
    url:"{{ route('getDoctorSchedule') }}",
    data: { center_id : center_id, doctor_id : doctor_id},
    success: function(response){
      $('#schedule').html(response);
    }
  });
});
</script>
<script>
$(document).ready(function(){
    var i=1;
    $('#add').click(function(){
        i++;
    var html = '';
  html += '<div class="form-group row" id="row'+i+'">';
  html += '<div class="col-md-12">';
  html += '<div class="form-group row">';
  html += '<div class="col-md-12 text-center form-control-label">Treatment # '+i+'</div>';
  html += '</div>';
  html += '<div class="form-group row">';
  html += '<div class="col-md-2 form-control-label">Select Treatment <span class="asterisk-blue">*</span></div>';
  html += '<div class="col-md-4">';
  html += '<select name="treatment_id[]" id="treatment'+i+'" class="form-control">';
  html += '<option value="">Select Treatment</option> <?php foreach($treatments as $t){ ?> <option value="<?php echo $t->id ?>" ><?php echo $t->name ?></option><?php }?>';
  html += '</select>';
  html += '</div>';
  html += '<div class="col-md-2 form-control-label">Select Procedure <span class="asterisk-blue">*</span></div>';
  html += '<div class="col-md-4">';
  html += '<select name="procedure_id[]" id="procedure'+i+'" class="form-control">';
  html += '<option value="">Select Procedure</option>';
  html += '</select>';
  html += '</div>';
  html += '</div>';
  html += '<div class="form-group row">';
  html += '<div class="col-md-2 form-control-label">Select Center <span class="asterisk-blue">*</span></div>';
  html += '<div class="col-md-4">';
  html += '<select name="hospital_id[]" id="centers'+i+'" class="form-control">';
  html += '<option value="">Select Center</option>';
  html += '</select>';
  html += '</div>';
  html += '<div class="col-md-2 form-control-label">Select Doctor <span class="asterisk-blue">*</span></div>';
  html += '<div class="col-md-4">';
  html += '<select name="doctor_id[]" id="doctors'+i+'" class="form-control">';
  html += '<option value="">Select Doctor</option>';
  html += '</select>';
  html += '</div>';
  html += '</div>';
  html += '<div class="form-group row">';
  html += '<label class="col-md-2 form-control-label">Cost</label>';
  html += '<div class="col-md-4" id="cost'+i+'">';
  html += '<input type="text" name="cost[]" placeholder="Treatment Cost" class="form-control" value="0" required>';
  html += '</div>';
  html += '<label class="col-md-2 form-control-label">Appointment Date</label>';
  html += '<div class="col-md-4">';
  html += '<input type="datetime-local" name="appointment_date[]" class="form-control"><input type="hidden" name="appointment_from[]" value="0">';
  html += '</div>';
  html += '</div>';
  html += '<div class="form-group row">';
  html += '<label class=" col-md-2 form-control-label">Discount %</label>';
  html += '<div class="col-md-4">';
  html += '<input type="number" name="treatment_discount[]" id="treatment_discount'+i+'" class="form-control" value="0" />';
  html += '</div>';
  html += '<div class="offset-md-2 col-md-4">';
  html += '<ul class="list-group schedule-box" id="schedule'+i+'">';
  html += '</ul>';
  html += ' </div>';
  html += ' </div>';
  html += '<div class="form-group row">';
  html += '<label class="col-md-2 form-control-label">Result</label>';
  html += '<div class="col-md-4">';
  html += '<input type="number" name="discounted_tcost[]" readonly id="tresult1'+i+'" class="form-control" value="" />';
  html += ' </div>';
  html += ' </div>';
  html += '<div class="form-group row">';
  html += '<div class="col-md-12 text-center">';
  html += '<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">Remove</button>';
  html += '</div>';
  html += '</div>';
  html += '</div>';
  html += '</div>';
  $('#dynamic_field').append(html);
  $(document).on('change','#treatment'+i+'', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getTreatments') }}",
      data: { id : id},
      success: function(response){
        $('#procedure'+i+'').html(response);
      }
    });
  });
  $(document).on('change','#treatment'+i+'', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getCenters') }}",
      data: { id : id},
      success: function(response){
        $('#centers'+i+'').html(response);
      }
    });
  });

  $(document).on('change','#procedure'+i+'', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id = $(this).val();
    if(id == 0){
         id  = $("#treatment"+i+" option:selected").val();
    }
    $.ajax({
      type:'post',
      url:"{{ route('getCenters') }}",
      data: { id : id},
      success: function(response){
        $('#centers'+i+'').html(response);
      }
    });
  });
  $(document).on('change','#centers'+i+'', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var procedure_id  = $("#procedure"+i+" option:selected").val();
    if(procedure_id == 0){
         procedure_id  = $("#treatment"+i+" option:selected").val();
    }
    var center_id     = $("#centers"+i+" option:selected").val();
    $.ajax({
      type:'post',
      url:"{{ route('getDoctors') }}",
      data: { procedure_id : procedure_id, center_id : center_id},
      success: function(response){
        $('#doctors'+i+'').html(response);
      }
    });
  });
  $(document).on('change','#doctors'+i+'', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var procedure_id  = $("#procedure"+i+" option:selected").val();
    var center_id     = $("#centers"+i+" option:selected").val();
    var doctor_id     = $("#doctors"+i+" option:selected").val();
    
    $.ajax({
      type:'post',
      url:"{{ route('getDocCost') }}",
      data: { procedure_id : procedure_id, doctor_id : doctor_id, center_id : center_id},
      success: function(response){
        $('#cost'+i+'').html(response);
      }
    });
  });
  $(document).on('change','#doctors'+i+'', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  var center_id     = $("#centers"+i+" option:selected").val();
  var doctor_id     = $("#doctors"+i+" option:selected").val();
  $.ajax({
    type:'post',
    url:"{{ route('getDoctorSchedule') }}",
    data: { center_id : center_id, doctor_id : doctor_id},
    success: function(response){
      $('#schedule'+i+'').html(response);
    }
  });
});
  $(document).ready(function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  var center_id     = $("#centers"+i+" option:selected").val();
  var doctor_id     = $("#doctors"+i+" option:selected").val();
  $.ajax({
    type:'post',
    url:"{{ route('getDoctorSchedule') }}",
    data: { center_id : center_id, doctor_id : doctor_id},
    success: function(response){
      $('#schedule'+i+'').html(response);
    }
  });
});
    $(document).on("change keyup blur", "#treatment_discount"+i+"", function() {
      var main          = $("#cost"+i+" input").val();
      var disc = $('#treatment_discount'+i+'').val();
      var dec = (disc / 100).toFixed(2); //its convert 10 into 0.10
      var mult = main * dec; // gives the value for subtract from main value
      var discont = main - mult;
      $('#tresult1'+i+'').val(discont);
    });
  $(document).on('click', '.btn_remove', function(){
        var button_id = $(this).attr("id");
        $('#row'+button_id+'').remove();
  });

  $(document).on('click', '.btn_remove', function(){
        var button_id = $(this).attr("id");
        $('#'+button_id+'').remove();
    });
});
});
</script>
<!-- to edit first treatment -->
<script>
var $i =100;

  $(document).on('change','#treatment_id'+100+'', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getTreatments') }}",
      data: { id : id},
      success: function(response){
        $('#procedure'+100+'').html(response);
      }
    });
  });
  $(document).on('change','#treatment_id'+100+'', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getCenters') }}",
      data: { id : id},
      success: function(response){
        $('#centers'+100+'').html(response);
      }
    });
  });

  $(document).on('change','#procedure'+100+'', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id = $(this).val();
    if(id == 0){
         id  = $("#treatment_id"+100+" option:selected").val();
    }
    $.ajax({
      type:'post',
      url:"{{ route('getCenters') }}",
      data: { id : id},
      success: function(response){
        $('#centers'+100+'').html(response);
      }
    });
  });
$(document).on('change','#centers'+100+'', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var procedure_id  = $("#procedure"+100+" option:selected").val();
    if(procedure_id == 0){
         procedure_id  = $("#treatment_id"+100+" option:selected").val();
    }
    var center_id     = $("#centers"+100+" option:selected").val();
    $.ajax({
      type:'post',
      url:"{{ route('getDoctors') }}",
      data: { procedure_id : procedure_id, center_id : center_id},
      success: function(response){
        $('#doctors'+100+'').html(response);
      }
    });
  $(document).on('change','#doctors'+100+'', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var procedure_id  = $("#procedure"+100+" option:selected").val();
    var center_id     = $("#centers"+100+" option:selected").val();
    var doctor_id     = $("#doctors"+100+" option:selected").val();
    

    $.ajax({
      type:'post',
      url:"{{ route('getDocCost') }}",
      data: { procedure_id : procedure_id, doctor_id : doctor_id, center_id : center_id},
      success: function(response){
        $('#cost'+100+'').html(response);
      }
    });
  });
  $(document).on('change','#doctors'+100+'', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  var center_id     = $("#centers"+100+" option:selected").val();
  var doctor_id     = $("#doctors"+100+" option:selected").val();
  $.ajax({
    type:'post',
    url:"{{ route('getDoctorSchedule') }}",
    data: { center_id : center_id, doctor_id : doctor_id},
    success: function(response){
      $('#schedule'+100+'').html(response);
    }
  });
});
  });
$(document).ready(function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  var center_id     = $("#centers"+100+" option:selected").val();
  var doctor_id     = $("#doctors"+100+" option:selected").val();
  $.ajax({
    type:'post',
    url:"{{ route('getDoctorSchedule') }}",
    data: { center_id : center_id, doctor_id : doctor_id},
    success: function(response){
      $('#schedule'+100+'').html(response);
    }
  });
});
$(document).on("change keyup blur", "#treatment_discount"+100+"", function() {
      var main          = $("#cost"+100+" input").val();
      var disc = $('#treatment_discount'+100+'').val();
      var dec = (disc / 100).toFixed(2); //its convert 10 into 0.10
      var mult = main * dec; // gives the value for subtract from main value
      var discont = main - mult;
      $('#tresult1'+100+'').val(discont);
    });
</script>
<!-- to edit second treatment -->
<script>
var $i =101;
  $(document).on('change','#treatment_id'+101+'', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getTreatments') }}",
      data: { id : id},
      success: function(response){
        $('#procedure'+101+'').html(response);
      }
    });
  });
  $(document).on('change','#treatment_id'+101+'', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getCenters') }}",
      data: { id : id},
      success: function(response){
        $('#centers'+101+'').html(response);
      }
    });
  });

  $(document).on('change','#procedure'+101+'', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id = $(this).val();
    if(id == 0){
         id  = $("#treatment_id"+101+" option:selected").val();
    }
    $.ajax({
      type:'post',
      url:"{{ route('getCenters') }}",
      data: { id : id},
      success: function(response){
        $('#centers'+101+'').html(response);
      }
    });
  });
$(document).on('change','#centers'+101+'', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var procedure_id  = $("#procedure"+101+" option:selected").val();
    if(procedure_id == 0){
         procedure_id  = $("#treatment_id"+101+" option:selected").val();
    }
    var center_id     = $("#centers"+101+" option:selected").val();
    $.ajax({
      type:'post',
      url:"{{ route('getDoctors') }}",
      data: { procedure_id : procedure_id, center_id : center_id},
      success: function(response){
        $('#doctors'+101+'').html(response);
      }
    });
  });
  $(document).on('change','#doctors'+101+'', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var procedure_id  = $("#procedure"+101+" option:selected").val();
    var center_id     = $("#centers"+101+" option:selected").val();
    var doctor_id     = $("#doctors"+101+" option:selected").val();
    

    $.ajax({
      type:'post',
      url:"{{ route('getDocCost') }}",
      data: { procedure_id : procedure_id, doctor_id : doctor_id, center_id : center_id},
      success: function(response){
        $('#cost'+101+'').html(response);
      }
    });
  });
  $(document).on('change','#doctors'+101+'', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  var center_id     = $("#centers"+101+" option:selected").val();
  var doctor_id     = $("#doctors"+101+" option:selected").val();
  $.ajax({
    type:'post',
    url:"{{ route('getDoctorSchedule') }}",
    data: { center_id : center_id, doctor_id : doctor_id},
    success: function(response){
      $('#schedule'+101+'').html(response);
    }
  });
});
$(document).ready(function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  var center_id     = $("#centers"+101+" option:selected").val();
  var doctor_id     = $("#doctors"+101+" option:selected").val();
  $.ajax({
    type:'post',
    url:"{{ route('getDoctorSchedule') }}",
    data: { center_id : center_id, doctor_id : doctor_id},
    success: function(response){
      $('#schedule'+101+'').html(response);
    }
  });
});
$(document).on("change keyup blur", "#treatment_discount"+101+"", function() {
      var main          = $("#cost"+101+" input").val();
      var disc = $('#treatment_discount'+101+'').val();
      var dec = (disc / 100).toFixed(2); //its convert 10 into 0.10
      var mult = main * dec; // gives the value for subtract from main value
      var discont = main - mult;
      $('#tresult1'+101+'').val(discont);
    });
</script>
<!-- to edid third treatment -->
<script>
var $i =102;

  $(document).on('change','#treatment_id'+102+'', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getTreatments') }}",
      data: { id : id},
      success: function(response){
        $('#procedure'+102+'').html(response);
      }
    });
  });
  $(document).on('change','#treatment_id'+102+'', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getCenters') }}",
      data: { id : id},
      success: function(response){
        $('#centers'+102+'').html(response);
      }
    });
  });

  $(document).on('change','#procedure'+102+'', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id = $(this).val();
    if(id == 0){
         id  = $("#treatment_id"+102+" option:selected").val();
    }
    $.ajax({
      type:'post',
      url:"{{ route('getCenters') }}",
      data: { id : id},
      success: function(response){
        $('#centers'+102+'').html(response);
      }
    });
  });
  $(document).on('change','#centers'+102+'', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var procedure_id  = $("#procedure"+102+" option:selected").val();
    if(procedure_id == 0){
         procedure_id  = $("#treatment_id"+102+" option:selected").val();
    }
    var center_id     = $("#centers"+102+" option:selected").val();
    console.log(procedure_id,center_id);
    $.ajax({
      type:'post',
      url:"{{ route('getDoctors') }}",
      data: { procedure_id : procedure_id, center_id : center_id},
      success: function(response){
        $('#doctors'+102+'').html(response);
      }
    });
  });
  $(document).on('change','#doctors'+102+'', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var procedure_id  = $("#procedure"+102+" option:selected").val();
    var center_id     = $("#centers"+102+" option:selected").val();
    var doctor_id     = $("#doctors"+102+" option:selected").val();
    $.ajax({
      type:'post',
      url:"{{ route('getDocCost') }}",
      data: { procedure_id : procedure_id, doctor_id : doctor_id, center_id : center_id},
      success: function(response){
        $('#cost'+102+'').html(response);
      }
    });
  });
$(document).on('change','#doctors'+102+'', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  var center_id     = $("#centers"+102+" option:selected").val();
  var doctor_id     = $("#doctors"+102+" option:selected").val();
  $.ajax({
    type:'post',
    url:"{{ route('getDoctorSchedule') }}",
    data: { center_id : center_id, doctor_id : doctor_id},
    success: function(response){
      $('#schedule'+102+'').html(response);
    }
  });
});
$(document).ready(function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  var center_id     = $("#centers"+102+" option:selected").val();
  var doctor_id     = $("#doctors"+102+" option:selected").val();
  $.ajax({
    type:'post',
    url:"{{ route('getDoctorSchedule') }}",
    data: { center_id : center_id, doctor_id : doctor_id},
    success: function(response){
      $('#schedule'+102+'').html(response);
    }
  });
});
$(document).on("change keyup blur", "#treatment_discount"+102+"", function() {
      var main          = $("#cost"+102+" input").val();
      var disc = $('#treatment_discount'+102+'').val();
      var dec = (disc / 100).toFixed(2); //its convert 10 into 0.10
      var mult = main * dec; // gives the value for subtract from main value
      var discont = main - mult;
      $('#tresult1'+102+'').val(discont);
    });
</script>
<!-- to edit Fourth treatment -->
<script>
var $i =103;
  $(document).on('change','#treatment_id'+103+'', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getTreatments') }}",
      data: { id : id},
      success: function(response){
        $('#procedure'+103+'').html(response);
      }
    });
  });
  $(document).on('change','#treatment_id'+103+'', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).val();
    $.ajax({
      type:'post',
      url:"{{ route('getCenters') }}",
      data: { id : id},
      success: function(response){
        $('#centers'+103+'').html(response);
      }
    });
  });

  $(document).on('change','#procedure'+103+'', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id = $(this).val();
    if(id == 0){
         id  = $("#treatment_id"+103+" option:selected").val();
    }
    $.ajax({
      type:'post',
      url:"{{ route('getCenters') }}",
      data: { id : id},
      success: function(response){
        $('#centers'+103+'').html(response);
      }
    });
  });
$(document).on('change','#centers'+103+'', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var procedure_id  = $("#procedure"+103+" option:selected").val();
    if(procedure_id == 0){
         procedure_id  = $("#treatment_id"+103+" option:selected").val();
    }
    var center_id     = $("#centers"+103+" option:selected").val();
    $.ajax({
      type:'post',
      url:"{{ route('getDoctors') }}",
      data: { procedure_id : procedure_id, center_id : center_id},
      success: function(response){
        $('#doctors'+103+'').html(response);
      }
    });
  });
  $(document).on('change','#doctors'+103+'', function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var procedure_id  = $("#procedure"+103+" option:selected").val();
    var center_id     = $("#centers"+103+" option:selected").val();
    var doctor_id     = $("#doctors"+103+" option:selected").val();
    $.ajax({
      type:'post',
      url:"{{ route('getDocCost') }}",
      data: { procedure_id : procedure_id, doctor_id : doctor_id, center_id : center_id},
      success: function(response){
        $('#cost'+103+'').html(response);
      }
    });
  });
$(document).on('change','#doctors'+103+'', function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  var center_id     = $("#centers"+103+" option:selected").val();
  var doctor_id     = $("#doctors"+103+" option:selected").val();
  $.ajax({
    type:'post',
    url:"{{ route('getDoctorSchedule') }}",
    data: { center_id : center_id, doctor_id : doctor_id},
    success: function(response){
      $('#schedule'+103+'').html(response);
    }
  });
});
$(document).ready(function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  var center_id     = $("#centers"+103+" option:selected").val();
  var doctor_id     = $("#doctors"+103+" option:selected").val();
  $.ajax({
    type:'post',
    url:"{{ route('getDoctorSchedule') }}",
    data: { center_id : center_id, doctor_id : doctor_id},
    success: function(response){
      $('#schedule'+103+'').html(response);
    }
  });
});
$(document).on("change keyup blur", "#treatment_discount"+103+"", function() {
      var main          = $("#cost"+103+" input").val();
      var disc = $('#treatment_discount'+103+'').val();
      var dec = (disc / 100).toFixed(2); //its convert 10 into 0.10
      var mult = main * dec; // gives the value for subtract from main value
      var discont = main - mult;
      $('#tresult1'+103+'').val(discont);
    });
</script>

<!-- Diagnostics Script -->

<script>
$('#lab1').change(function(){

var i;
var j = 999;
for (i = 2; i < j; i++) {

$('#row'+i).remove();
$('#diagnostic'+i).remove();
$('#diagnosticlabel').remove();
$('#diagnostic0').remove();
$('#costlabel').remove();
$('#diagnostics_cost').remove();
$('#selectdiagnostic').remove();
$('.qty1').val('0');
}

});
</script>
<script>
$(document).ready(function(){
var i=1;
var j=i+1;
$('#add0').click(function(){
var j=i+1;
  $.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
var id = $("#lab1").val();
$.ajax({
  type:'post',
  url:"{{ route('getDiagnostics') }}",
  data: { id : id},
  success: function(response){
    $('#diagnostic'+i).html(response);
  }
});
$(document).on('change','#diagnostic'+j, function(){
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var diagnostic_id  = $("#diagnostic"+j+" option:selected").val();
var lab_id     = $("#lab1 option:selected").val();
$.ajax({
  type:'post',
  url:"{{ route('getDiagnosticCost') }}",
  data: { diagnostic_id : diagnostic_id, lab_id : lab_id},
  success: function(response){
    $('#diagnostics_cost'+i+'').html(response);
  }
});
});
i++;
var html = '';
html += '<div class="form-group row" id="row'+i+'">';
html += '<div class="col-md-2 form-control-label">Select Diagnostic <span class="asterisk">*</span></div>';
html += '<div class="col-md-3">';
html += '<select name="diagnostic_id1[]" id="diagnostic'+i+'" class="form-control"><option value="">Select Diagnostic</option> </select>';
html += '</div>';
html += '<div class="col-md-2 form-control-label">Cost  <span class="asterisk">*</span></div>';
html += '<div class="col-md-3" id="diagnostics_cost'+i+'">';
html += '<input type="number" name="diagnostics_cost[]" placeholder="Enter diagnostic Cost" class="form-control name_list" required /> <input type="hidden" name="diagnostic_appointment_from[]" value="0">';
html += '</div>';
html += '<div class="col-md-2 form-control-label text-center"> <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div>';
html += '</div>';
$('#diagnostic_dynamic_field').append(html);
});

$(document).on('click', '.btn_remove', function(){
var button_id = $(this).attr("id");
$('#row'+button_id+'').remove();
});

$(document).on('click', '.btn_remove', function(){
var button_id = $(this).attr("id");
$('#'+button_id+'').remove();
});
});
$( document ).ready(function() {
  var sum = 0;
      $(".qty1").each(function(){
          sum += +$(this).val();
      });

      $(".total").val(sum);
});
</script>

<!-- Lab and diagnostic for Schedule 2 -->
<script>
$('#lab2').change(function(){

var i;
var j = 2000;
for (i = 1002; i < j; i++) {

$('#row'+i).remove();
$('#diagnostic'+i).remove();
$('#diagnosticlabel1000').remove();
$('#costlabel1000').remove();
$('.qty2').remove();
$('#selectdiagnostic1000').remove();
$('.qty2').val('0');
}
});
</script>
<script>
$(document).ready(function(){
var i=1001;
var j=i+1;
$('#add1000').click(function(){
var j=i+1;
  $.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
var id = $("#lab2").val();
$.ajax({
  type:'post',
  url:"{{ route('getDiagnostics') }}",
  data: { id : id},
  success: function(response){
    $('#diagnostic'+i).html(response);
  }
});
$(document).on('change','#diagnostic'+j, function(){
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var diagnostic_id  = $("#diagnostic"+j+" option:selected").val();
var lab_id     = $("#lab2 option:selected").val();
$.ajax({
  type:'post',
  url:"{{ route('getDiagnosticCost2') }}",
  data: { diagnostic_id :diagnostic_id, lab_id : lab_id},
  success: function(response){
    $('#diagnostics_cost200'+i+'').html(response);
  }
});
});
i++;
var html = '';
html += '<div class="form-group row" id="row'+i+'">';
html += '<div class="col-md-2 form-control-label">Select Diagnostic <span class="asterisk">*</span></div>';
html += '<div class="col-md-3">';
html += '<select name="diagnostic_id2[]" id="diagnostic'+i+'" class="form-control"><option value="">Select Diagnostic</option> </select>';
html += '</div>';
html += '<div class="col-md-2 form-control-label">Cost  <span class="asterisk">*</span></div>';
html += '<div class="col-md-3" id="diagnostics_cost200'+i+'">';
html += '<input type="number" name="diagnostics_cost2[]" placeholder="Enter Diagnostic Cost" class="form-control name_list" required /><input type="hidden" name="diagnostic_appointment_from2[]" value="0">';
html += '</div>';
html += '<div class="col-md-2 form-control-label text-center"> <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div>';
html += '</div>';
$('#diagnostic_dynamic_field1000').append(html);
});

$(document).on('click', '.btn_remove', function(){
var button_id = $(this).attr("id");
$('#row'+button_id+'').remove();
});
$(document).on('click', '.btn_remove', function(){
var button_id = $(this).attr("id");
$('#'+button_id+'').remove();
});
});
$( document ).ready(function() {
  var sum = 0;
      $(".qty2").each(function(){
          sum += +$(this).val();
      });

      $(".total2").val(sum);
});
</script>
<script>

  $(document).on("click", "#sum-btn", function() {
      var sum = 0;
      $(".qty1").each(function(){
          sum += +$(this).val();
      });

      $(".total").val(sum);
  });
  $(document).on("click", "#sum-btn2", function() {
      var sum = 0;
      $(".qty2").each(function(){
          sum += +$(this).val();
      });

      $(".total2").val(sum);
  });
  </script>
<!-- End of 2 Diagnostics-->
<script>
        $(document).on("change keyup blur", "#chDiscount1", function() {
            var main = $('#total-cost1').val();
            var disc = $('#chDiscount1').val();
            var dec = (disc / 100).toFixed(2); //its convert 10 into 0.10
            var mult = main * dec; // gives the value for subtract from main value
            var discont = main - mult;
            $('#result1').val(discont);
        });
    </script>
    <script>
        $(document).on("change keyup blur", "#chDiscount2", function() {
            var main = $('#total-cost2').val();
            var disc = $('#chDiscount2').val();
            var dec = (disc / 100).toFixed(2); //its convert 10 into 0.10
            var mult = main * dec; // gives the value for subtract from main value
            var discont = main - mult;
            $('#result2').val(discont);
        });
    </script>
    <!-- Treatment Discounts -->
<script>
    $(document).on("change keyup blur", "#treatment_discount", function() {
        var main = $('#total-tcost1').val();
        var disc = $('#treatment_discount').val();
        var dec = (disc / 100).toFixed(2); //its convert 10 into 0.10
        var mult = main * dec; // gives the value for subtract from main value
        var discont = main - mult;
        $('#tresult1').val(discont);
    });
  </script>
  <!-- END of Treatment Discounts -->
<script>
    
var placeSearch, autocomplete;

var componentForm = {
  // street_number: 'short_name',
  // route: 'long_name',
  locality: 'long_name',
  // administrative_area_level_1: 'short_name',
  // country: 'long_name',
  // postal_code: 'short_name'
};

function initAutocomplete() {
  // Create the autocomplete object, restricting the search predictions to
  // geographical location types.
  autocomplete = new google.maps.places.Autocomplete(
      document.getElementById('autocomplete'), {types: ['geocode']});

  // Avoid paying for data that you don't need by restricting the set of
  // place fields that are returned to just the address components.
  autocomplete.setFields(['address_component']);

  // When the user selects an address from the drop-down, populate the
  // address fields in the form.
  autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
  // Get the place details from the autocomplete object.
  var place = autocomplete.getPlace();

  for (var component in componentForm) {
    document.getElementById(component).value = '';
  }

  // Get each component of the address from the place details,
  // and then fill-in the corresponding field on the form.
  for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
    if (componentForm[addressType]) {
      var val = place.address_components[i][componentForm[addressType]];
      document.getElementById(addressType).value = val;
    }
  }
}

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      var circle = new google.maps.Circle(
          {center: geolocation, radius: position.coords.accuracy});
      autocomplete.setBounds(circle.getBounds());
    });
  }
}
// $(".city").val(locality);
// console.log('city =>',locality);
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD9RHZgUXffbQmvczfgC8CeNKfm6IYMAJQ&libraries=places&callback=initAutocomplete" async defer></script>
@endsection
