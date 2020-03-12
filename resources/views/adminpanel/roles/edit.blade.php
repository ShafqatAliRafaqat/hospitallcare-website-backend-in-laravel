@extends('adminpanel.layout')
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      @include('adminpanel.notification')
      <!-- Form Elements -->
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Update Role</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('roles.update', $role->id) }}">
            	@csrf
              @method('put')
              	<div class="form-group row">
                  <label class="col-md-3 form-control-label">Role Name</label>
                  <div class="col-md-9">
                    	<input type="text" name="role_name" placeholder="Treatment Name"
                    	class="form-control {{ $errors->has('role_name') ? 'is-invalid' : '' }}" value="{{ $role->name }}" required>

                      @if($errors->has('role_name'))
                    	<div class="invalid-feedback ml-3">{{ $errors->first('role_name') }}</div>
                      @endif
                  </div>
              	</div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Role Description</label>
                  <div class="col-md-9">
                      <input type="text" name="role_description" placeholder="Treatment Name"
                      class="form-control {{ $errors->has('role_description') ? 'is-invalid' : '' }}" value="{{ $role->description }}" required>

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
                        <input id="permision{{$p->id}}" type="checkbox" name="permission[]"
                          value="{{ $p->id }}"    {{ in_array($p->id, $data) ? 'checked':'' }}>
                        <label for="permision{{$p->id}}">{{ $p->name }}</label> <br>
                      </div>
                      @endforeach
                    </div>
                      
                    @if($errors->has('permission'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('permission') }}</div>
                    @endif
                  </div>
                </div>

              	<div class="form-group row">
                  <div class="col-md-9 ml-auto">
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update changes</button>
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
