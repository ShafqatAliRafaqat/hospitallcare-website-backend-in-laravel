@extends('adminpanel.layout')
@section('content')

<div class="container pt-5">
    <div class="form-group">
         <form class="form-horizontal" method="post" action="{{ route('exportbystatus') }}">
        @csrf

                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Starting Date</label>
                  <div class="col-md-4">
                        <input type="date" id="startdate" name="start_date" placeholder="Start Date"
                        class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}" value="{{ old('start_date') }}" required>

                      @if($errors->has('start_date'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('start_date') }}</div>
                      @endif
                  </div>
                <label class="col-md-2 form-control-label">Ending Date</label>
                  <div class="col-md-4">
                        <input type="date" id="enddate" name="end_date" placeholder="End Date"
                        class="form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}" value="{{ old('end_date') }}" required>

                      @if($errors->has('end_date'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('end_date') }}</div>
                      @endif
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-9 ml-auto">
                    <button type="submit" class="btn btn-primary">Export</button>
                  </div>
                </div>


         </form>
    </div>
</div>
@endsection

                <script>
  $(document).ready(function(){

    $("#startdate").datepicker({
        todayBtn:  1,
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#enddate').datepicker('setStartDate', minDate);
    });

    $("#enddate").datepicker()
        .on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#startdate').datepicker('setEndDate', minDate);
        });

});
</script>
