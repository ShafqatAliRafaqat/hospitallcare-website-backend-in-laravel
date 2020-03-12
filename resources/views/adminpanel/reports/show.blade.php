@extends('adminpanel.layout')
@section('title', 'Customers | Patients')
@section('content')

<div class="container-fluid px-xl-5">
    <section class="py-5">
              <!-- Report Generation on Screen -->
        <div class="form-group pt-3">
        <h5 class="pb-2">Report Generation</h5>
        <form class="form-horizontal" method="post" action="{{ route('generatereport') }}">
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
                  <div class="col-md-2 form-control-label">Select Center</div>
                  <div class="col-md-4" >

                    @if(isset($center_id))
                      <select   name="center_id" class="form-control selectpicker" data-live-search="true">
                        <option value="">Select Center</option>

                        @foreach($centers_db as $c)
                            <option value="{{ $c->id }}" {{$center_id == $c->id ? 'selected' : ''}}>{{ $c->center_name }}</option>
                          @endforeach
                      </select>
                        @if($errors->has('center_id'))
                          <div class="invalid-feedback ml-3">{{ $errors->first('center_id') }}</div>
                        @endif

                    @else
                      <select   name="center_id" class="form-control selectpicker" data-live-search="true">
                        <option value="">Select Center</option>

                        @foreach($centers_db as $c)
                            <option value="{{ $c->id }}">{{ $c->center_name }}</option>
                          @endforeach
                      </select>
                        @if($errors->has('center_id'))
                          <div class="invalid-feedback ml-3">{{ $errors->first('center_id') }}</div>
                        @endif

                    @endif

                  </div>

                  <div class="col-md-2 form-control-label">Select Status</div>
                  <div class="col-md-4" >
                    @if(isset($status_id))
                    <select name="status_id" class="form-control selectpicker" data-live-search="true">
                      <option value="">Select Status</option>
                      @foreach($status_db as $s)
                          <option value="{{ $s->id }}" {{($status_id == $s->id)?'selected':''}}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                      @if($errors->has('status_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('status_id') }}</div>
                      @endif
                    @else
                     <select name="status_id" class="form-control selectpicker" data-live-search="true">
                      <option value="">Select Status</option>
                      @foreach($status_db as $s)
                          <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                      @if($errors->has('status_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('status_id') }}</div>
                      @endif
                    @endif

                  </div>
                </div>
                  <div class="form-group row pt-3">
                  <div class="col-md-2 form-control-label">Select Owner</div>
                  <div class="col-md-4" >

                    @if(isset($patient_coordinator_id))
                    <select   name="patient_coordinator_id" class="form-control selectpicker" data-live-search="true">
                      <option value="">Select Owner</option>
                      @foreach($users_db as $user)
                          <option value="{{ $user->user_id }}" {{ $patient_coordinator_id == $user->user_id ? 'selected': ''}}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                      @if($errors->has('patient_coordinator_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('patient_coordinator_id') }}</div>
                      @endif
                    @else
                    <select   name="patient_coordinator_id" class="form-control selectpicker" data-live-search="true">
                      <option value="">Select Owner</option>
                      @foreach($users_db as $user)
                          <option value="{{ $user->user_id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                      @if($errors->has('patient_coordinator_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('patient_coordinator_id') }}</div>
                      @endif

                    @endif
                  </div>

                  <div class="col-md-2 form-control-label">Select Treatment</div>
                  <div class="col-md-4" >
                    @if(isset($treatment_id))
                    <select   name="treatment_id" class="form-control selectpicker" data-live-search="true">
                      <option value="">Select Treatment</option>
                      @foreach($treatments_db as $treatment)
                          <option value="{{ $treatment->id }}" {{ $treatment_id == $treatment->id ? 'selected': ''}}>{{ $treatment->name }}</option>
                        @endforeach
                    </select>
                      @if($errors->has('treatment_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('treatment_id') }}</div>
                      @endif
                    @else
                    <select   name="treatment_id" class="form-control selectpicker" data-live-search="true">
                      <option value="">Select Treatment</option>
                      @foreach($treatments_db as $treatment)
                          <option value="{{ $treatment->id }}">{{ $treatment->name }}</option>
                        @endforeach
                    </select>
                      @if($errors->has('treatment_id'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('treatment_id') }}</div>
                      @endif

                    @endif
                  </div>
                </div>

                <div class="form-group row pt-2">
                  <div class="col-md-4 offset-4 text-right" >
                    <button type="submit" style="width: 100%;" class="btn btn-primary">Generate</button>
                  </div>
                </div>

          </form>
        </div>
<!--         <div class="row pt-2">
          <div class="col-md-12">
          <p>Starting From {{$start}} to {{$ending}}</p>
          <p><strong>Center:</strong> {{isset($center_name)? $center_name:'Not Selected'}} <strong>Patient Owner:</strong> {{isset($patient_coordinator_name)? $patient_coordinator_name:'Not Selected'}}  <strong>Status:</strong> {{isset($status_name)? $status_name:'Not Selected'}} <strong>Treatment:</strong> {{isset($treatment_name)? $treatment_name:'Not Selected'}} </p>
          </div>
        </div> -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Customers <a href="{{ route('customers.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="customers">
                      <thead class="thead-light">
                        <tr>
                          <th>#</th>
                          <th>Patient Owner</th>
                          <th>Customer Name</th>
                          <th>Procedure</th>
                          <th>Center</th>
                          <th>Doctor</th>
                          <th>Cost</th>
                          <th>Status</th>
                          <th>Notes</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($customers)
                            @php $no=1 @endphp
                            @foreach($customers as $c)

                            <tr>
                              <th scope="row">{{$no++}}</th>
                              <td>{{ $c->patient_coordinator_id }}</a></td>
                              <td><a href="{{ route('customers.show', $c->id) }}">{{ $c->name }}</a></td>
                              <td>
                                {{ $c->treatment}}
                              </td>
                              <td>
                                {{ $c->center}}
                              </td>
                              <td>
                                {{ $c->doctor}}
                              </td>
                              <td>{{$c->cost}}</td>
                              <td>{{ $c->status_id }}</td>
                              <td>{!!$c->notes!!}</td>
                              <td><a href="{{ route('customers.edit', $c->id) }}"><i class="fa fa-edit"></i></a></td>
                              <td>
                                <a class="delete" data-id="{{ $c->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$c->id}}" method="post" action="{{ route('customers.destroy', $c->id) }}">
                                    @csrf @method('delete')
                                </form>
                              </td>
                            </tr>
                            @endforeach
                        @endif
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
<script src="{{ asset('backend/js/sweetalert/sweetalert.js') }}"></script>
<script>
$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    swal({
        title: "Are you sure?",
        text: "You want to delete Customer!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
        },
        function(){
            setTimeout(function () {
        $('#deleteForm'+id).submit();
        swal("Deleted!", "Procedure has been deleted.", "success");
    }, 2000);
        });
});
</script>
<script>
$(document).ready(function() {
    $('#customers').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});

</script>
<script src="{{ asset('backend/js/select2-develop/dist/js/select2.min.js') }}"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>
@endsection
