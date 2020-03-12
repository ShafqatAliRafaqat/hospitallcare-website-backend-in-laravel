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
                        <div class="form-group row">
                          <label class="col-md-2 form-control-label">Lab</label>
                          <div class="col-md-10">
                          <select name="lab_id1" id="lab1" class="form-control selectpicker" data-live-search="true">
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
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
