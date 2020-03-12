@extends('adminpanel.layout')
@section('title','Edit Users | CareALL')
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      <!-- Form Elements -->
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Update Role</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
              @csrf
              @method('put')
              <div class="form-group row">
                <label class="col-md-3 form-control-label">User Name</label>
                <div class="col-md-9">
                  <input type="text" name="name" placeholder="User Name"
                  class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $user->name }}" required>

                  @if($errors->has('name'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('name') }}</div>
                  @endif
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label">Email</label>
                <div class="col-md-9">
                  <input type="text" name="email" value="{{ $user->email }}" placeholder="Treatment Name"
                  class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" disabled>

                  @if($errors->has('email'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('email') }}</div>
                  @endif
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label">Password</label>
                <div class="col-md-9">
                  <input type="password" name="password" placeholder="******"
                  class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" value="{{ old('password') }}" >

                  @if($errors->has('password'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('password') }}</div>
                  @endif
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label">Confirm Password</label>
                <div class="col-md-9">
                  <input type="password" name="password_confirmation" placeholder="******"
                  class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" value="{{ old('password_confirmation') }}">

                  @if($errors->has('password_confirmation'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('password_confirmation') }}</div>
                  @endif
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label">Roles</label>
                <div class="col-md-9">
                  <select name="user_role" class="form-control {{ $errors->has('user_role') ? 'is-invalid' : '' }}" required>
                    <option value="0">Select Role</option>
                    @foreach($roles as $r)
                    <option value="{{ $r->id }}"  {{ $role_id == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                  </select>
                  @if($errors->has('user_role'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('user_role') }}</div>
                  @endif
                  <input type="hidden" value="{{ $role_id }}" name="user_role_id">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label">Organization</label>
                <div class="col-md-9">
                  <select name="organization_id" class="form-control {{ $errors->has('user_role') ? 'is-invalid' : '' }}" required>
                    <option value="0">Select Organization</option>
                    @foreach($organizations as $r)
                    <option value="{{ $r->id }}" {{$user->organization_id == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                  </select>
                  @if($errors->has('user_role'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('user_role') }}</div>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Picture</label>
                <div class="col-md-7 imageupload">
                  <div class="file-tab panel-body">
                    <label class="btn btn-success btn-file">
                      <span>File</span>
                      <!-- The file is stored here. -->
                      <input type="file" name="picture">
                    </label>
                    <button type="button" class="btn btn-danger">Remove</button>
                  </div>
                </div>
                @php
                $picture = (isset($user->UserImage))?$user->UserImage->picture:"";
                @endphp
                <div class="col-md-2">
                  <div class="img-wrap">
                    <span class="close">×</span>
                    <input type="hidden" name="picture"  value="{{$picture}}">
                    <img src="{{ asset('backend/uploads/users/'.$picture) }}" class="img-fluid" alt="{{ $errors->first('picture') }}" height="100" width="100">
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-9 ml-auto">
                  <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
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
@section('scripts')
<script src="{{ asset('backend/js/fileupload.js') }}" ></script>
<script src="{{asset('backend/js/bootstrap-imageupload.js')}}"></script>
<script>
  var $imageupload = $('.imageupload');
  $imageupload.imageupload();
</script>
@endsection
