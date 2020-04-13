@extends('adminpanel.layout')
@section('title','Edit Diagnostics | HospitALL')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
          @include('adminpanel.notification')
          <!-- Form Elements -->
          <div class="col-lg-12 mb-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="h6 text-uppercase mb-0">Edit Diagnostics</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('UpdateDiagnosticAppointment',$bundles[0]->bundle_id) }}" method="POST" >
                        @csrf @method('post')
                    <div class="form-group row">
                      <label class="col-md-2 form-control-label">Lab</label>
                      <div class="col-md-10">
                          <select name="lab_id" id="lab1" class="form-control selectpicker" data-live-search="true">
                              <option value="">Select Diagnostic</option>
                              @foreach($labs as $l)
                              <option value="{{ $l->id }}" {{ $bundles[0]->lab_id == $l->id ? 'selected': '' }}>{{ $l->name }}</option>
                              @endforeach
                          </select>
                          @if($errors->has('lab1'))
                          <div class="invalid-feedback ml-3">{{ $errors->first('lab1') }}</div>
                          @endif
                      </div>
                  </div>
                  <?php $i = 0 ;?>
                  @foreach($bundles as $diagnostic)
                  <div id="diagnostic_dynamic_field">
                    <div class="form-group row" id="diagnostic{{$i+1}}">
                        <div class="col-md-2 form-control-label" id="diagnosticlabel">Select Diagnostic  <span class="asterisk">*</span></div>
                        <div class="col-md-3" id = "selectdiagnostic">
                          <select name="diagnostic_id[]" id="diagnostic0" class="form-control selectpicker">
                              @foreach ($diagnostics as $d)
                                  <option value="{{ $d->id }}"
                                      {{ ($d->id == $diagnostic->diagnostic_id) ? 'selected' : ''}}
                                      >{{ $d->name }}
                                  </option>
                              @endforeach
                          </select>
                        </div>
                        <div class="col-md-2 form-control-label" id="costlabel">Cost  <span class="asterisk">*</span></div>
                        <div class="col-md-3">
                            <input type="number" name="diagnostics_cost[]"  id ="diagnostics_cost" value="{{$diagnostic->discounted_cost}}" class="form-control name_list qty1" required/>
                            <input type="hidden" name="diagnostics_appointment_from[]" value="{{$diagnostic->appointment_from}}" />
                        </div>

                        <?php if ($i == 0){?>
                            <div class="col-md-2 form-control-label">
                              <button type="button" name="add" id="add{{$i}}" class="btn btn-success btn-sm">Add Diagnostic</button>
                          </div>
                      <?php }else{ ?>
                        <div class="col-md-2 form-control-label text-center">
                            <button type="button" name="remove" id="diagnostic{{$i+1}}" class="btn btn-danger btn_remove btn-sm">X</button>
                        </div>
                    <?php }?>
                </div>
            </div>
            <?php $i++?>
            @endforeach
            <div class="form-group row">
                @php
                    $diagnostic_appointment_date      = AppointmentTimeConvert($bundles[0]->appointment_date);
                @endphp
              <label class="col-md-2 form-control-label">Appointment Date</label>
              <div class="col-md-3">
                    <input type="datetime-local" name="diagnostic_appointment_date" placeholder="appointment_date"
                    class="form-control {{ $errors->has('diagnostic_appointment_date') ? 'is-invalid' : '' }}" value="{{ (isset($diagnostic_appointment_date) ? $diagnostic_appointment_date : '') }}">
                  @if($errors->has('diagnostic_appointment_date'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('diagnostic_appointment_date') }}</div>
                  @endif
              </div>
              <label class="col-md-2 form-control-label">Total Cost</label>
              <div class="col-md-3">
                <input type="number" readonly class="total form-control" value="" id="total-cost1" />
              </div>
              <div class="col-md-2 text-center">
                <button type="button" name="sum-btn" id="sum-btn" class="btn btn-info btn-sm">Sum</button>
              </div>
            </div>

            <div class="form-group row">
                @if(isset($bundles[0]->discount_per))
                    <label class="offset-md-3 col-md-2 form-control-label" style="color:tomato;"><h6><b>Already {{$bundles[0]->discount_per}}% Given</b></h6></label>
                @endif
              <label class="col-md-2 form-control-label">Discount %</label>
              <div class="col-md-3">
              <input type="number" name="discount" id="chdiscount" class="form-control" value="0" />
              </div>
            </div>

                <div class="form-group row">
                <label class="col-md-2 form-control-label">Home Sampling</label>
                  <div class="col-md-3">
                    <div class="custom-control custom-checkbox">
                      <input id="home_sampling" value="1" type="checkbox" name="home_sampling" class="custom-control-input" {{ isset($bundles)? (($bundles[0]->home_sampling == 1)? 'Checked' : ""):""}}>
                      <label for="home_sampling" class="custom-control-label">Home Sample</label>
                    </div>
                  </div>
                  <label class="col-md-2 form-control-label">Result</label>
                  <div class="col-md-3">
                    <input type="number" readonly id="result1" class="form-control" value="" />
                  </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-success  btn-md">Update</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
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
html += '<select name="diagnostic_id[]" id="diagnostic'+i+'" class="form-control"><option value="">Select Diagnostic</option> </select>';
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
  <script>
        $(document).on("change keyup blur", "#chdiscount", function() {
            var main = $('#total-cost1').val();
            var disc = $('#chdiscount').val();
            var dec = (disc / 100).toFixed(2); //it converts 10 into 0.10
            var mult = main * dec; // gives the value for subtract from main value
            var discont = main - mult;
            $('#result1').val(discont);
        });
    </script>
@endsection
