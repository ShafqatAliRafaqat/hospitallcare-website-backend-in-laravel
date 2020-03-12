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
            <form class="form-horizontal" method="post" action="{{ route('roles.store') }}">
            	@csrf
              	<div class="form-group row">
                  <label class="col-md-3 form-control-label">Role Name</label>
                  <div class="col-md-9">
                    	<input type="text" name="role_name" placeholder="Role Name"
                    	class="form-control {{ $errors->has('role_name') ? 'is-invalid' : '' }}" value="{{ old('role_name') }}" required>

                      @if($errors->has('role_name'))
                    	<div class="invalid-feedback ml-3">{{ $errors->first('role_name') }}</div>
                    @endif
                  </div>
              	</div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Role Description</label>
                  <div class="col-md-9">
                      <input type="text" name="role_description" placeholder="Role Description"
                      class="form-control {{ $errors->has('role_description') ? 'is-invalid' : '' }}" value="{{ old('role_description') }}" required>

                      @if($errors->has('role_description'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('role_description') }}</div>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3" for="">Assign Permissions</label>
                  <div class="col-md-9">
                    <div class="row">
                    @foreach($permissions as $p)
                      <div class="col-3">
                        <input id="permision{{$p->id}}" type="checkbox" name="permission[]" value="{{ $p->id }}">
                        <label for="permision{{$p->id}}">{{ $p->name }}</label> <br>
                      </div>
                    @endforeach  
                    </div>
                  </div>
                </div>

              	<div class="form-group row">
                  <div class="col-md-9 ml-auto">
                    <button type="submit" class="btn btn-primary">Save Role</button>
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
