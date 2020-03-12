@extends('adminpanel.layout')
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      <!-- Form Elements -->
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Create Role</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('center_users.store') }}">
            	@csrf
              	<div class="form-group row">
                  <label class="col-md-3 form-control-label">User Name</label>
                  <div class="col-md-9">
                    	<input type="text" name="name" placeholder="User Name"
                    	class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" required>

                      @if($errors->has('name'))
                    	<div class="invalid-feedback ml-3">{{ $errors->first('name') }}</div>
                    @endif
                  </div>
              	</div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Email</label>
                  <div class="col-md-9">
                      <input type="email" name="email" placeholder="Email"
                      class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" required>

                      @if($errors->has('email'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('email') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Password</label>
                  <div class="col-md-9">
                      <input type="password" name="password" placeholder="******"
                      class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" value="{{ old('password') }}" required>

                      @if($errors->has('password'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('password') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Confirm Password</label>
                  <div class="col-md-9">
                      <input type="password" name="password_confirmation" placeholder="******"
                      class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" value="{{ old('password_confirmation') }}" required>

                      @if($errors->has('password_confirmation'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Attach Role</label>
                  <div class="col-md-9">
                      <select name="user_role"  class="form-control {{ $errors->has('user_role') ? 'is-invalid' : '' }} selectpicker" data-live-search="true" value="{{ old('user_role') }}" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                          <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                      </select>

                      @if($errors->has('user_role'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('user_role') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Center</label>
                  <div class="col-md-9">
                      <select name="medical_center_id"  class="form-control selectpicker" data-live-search="true" required>
                        <option value="">Select Center</option>
                        @foreach($center as $c)
                          <option value="{{ $c->id }}">{{ $c->center_name }}</option>
                        @endforeach
                      </select>

                      @if($errors->has('user_role'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('user_role') }}</div>
                    @endif
                  </div>
                </div>

              	<div class="form-group row">
                  <div class="col-md-9 ml-auto">
                    <button type="submit" class="btn btn-primary">Save User</button>
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
<script src="{{ asset('backend/js/select2-develop/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/fileupload.js') }}" ></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>
@endsection
