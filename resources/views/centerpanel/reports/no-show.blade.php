@extends('centerpanel.layout')
@section('title', 'Customers | Patients')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
      <!-- Report Generation on Screen -->
        <div class="form-group pt-3">
        <h5 class="pb-2">Report Generation</h5>
        <form class="form-horizontal" method="post" action="{{ route('clientsreport') }}">
        @csrf
              <div class="form-group row">
                  <label class="col-md-2 form-control-label">Starting Date</label>
                  <div class="col-md-4">
                        <input type="date" id="startdate" name="start_date" placeholder="Start Date"
                        class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}" value="{{ $start }}" required>

                      @if($errors->has('start_date'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('start_date') }}</div>
                      @endif
                  </div>
                <label class="col-md-2 form-control-label">Ending Date</label>
                  <div class="col-md-4">
                        <input type="date" id="enddate" name="end_date" placeholder="End Date"
                        class="form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}" value="{{ $ending }}" required>

                      @if($errors->has('end_date'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('end_date') }}</div>
                      @endif
                  </div>
                </div>
            <div class="row">
              <div class="col-md-2 form-control-label">Select Status</div>
              <div class="col-md-4" >
                <select name="status_id" class="form-control ">
                  <option value="">Select Status</option>
                  @foreach($status_db as $s)
                      <option value="{{ $s->id }}">
                        @if($s->id == '1')
                        Informed
                        @elseif($s->id == '2')
                        Booked Appointment
                        @elseif($s->id == '3')
                        Got Appointment
                        @elseif($s->id == '4')
                        Got Treatment
                        @endif
                    </option>
                    @endforeach
                </select>
                  @if($errors->has('status_id'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('status_id') }}</div>
                  @endif
              </div>

                <div class="col-md-4 offset-2 text-right" >
                <button type="submit" style="width: 100%;" class="btn btn-primary">Generate</button>
              </div>
            </div>
          </form>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Customers <a href="{{ route('customers.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="customers">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Patient Owner</th>
<!--                           <th>Customer ID</th> -->
                          <th>Customer Name</th>
                          <th>Procedure</th>

                          <th>Center</th>
                          <th>Cost</th>
                          <th>Status</th>
                          <th>Notes</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>

                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    $('#customers').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});

$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    if ( confirm('Are you sure to delete?') ){
      $('#deleteForm'+id).submit();
    }else {

    }
});
</script>
@endsection
