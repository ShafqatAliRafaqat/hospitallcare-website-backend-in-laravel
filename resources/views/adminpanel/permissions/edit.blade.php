@extends('adminpanel.layout')
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      <!-- Form Elements -->
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Update Permission</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('permissions.update', $permission->id) }}">
            	@csrf
              @method('put')
              	<div class="form-group row">
                  <label class="col-md-3 form-control-label">Permission Name</label>
                  <div class="col-md-9">
                    	<input type="text" name="name" placeholder="Permission Name"
                    	class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $permission->name }}" required>

                      @if($errors->has('name'))
                    	<div class="invalid-feedback ml-3">{{ $errors->first('name') }}</div>
                      @endif
                  </div>
              	</div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Role Description</label>
                  <div class="col-md-9">
                      <input type="text" name="description" placeholder="Description"
                      class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" value="{{ $permission->description }}" required>

                      @if($errors->has('description'))
                      <div class="invalid-feedback ml-3">{{ $errors->first('description') }}</div>
                      @endif
                  </div>
                </div>

              	<div class="form-group row">
                  <div class="col-md-9 ml-auto">
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
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
