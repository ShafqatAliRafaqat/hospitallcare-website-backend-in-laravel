@extends('adminpanel.layout')
@section('title','Create Center | HospitALL')
@section('styles')
<link rel="stylesheet" href="{{ asset('backend/css/fileupload.css') }}">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      <!-- Form Elements -->
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Create Medical Center</h3>
          </div>
          <div class="card-body">
            @foreach($errors as $e)
            <h1>{{$e}}</h1>
            @endforeach
            <form class="form-horizontal" id="center-form" method="post" action="{{ route('medical.store') }}" enctype="multipart/form-data">
              @csrf

              @include('adminpanel.includes.center.create_center')

              <div class="form-group row">
                <label class="col-md-2 form-control-label">Center Name <span class="asterisk">*</span></label>
                <div class="col-md-10">
                  <input type="text" name="center_name" id="center_name" placeholder="Center Name"
                  class="form-control {{ $errors->has('center_name') ? 'is-invalid' : '' }}" value="{{ old('center_name') }}" required>

                  @if($errors->has('center_name'))
                  <div class="invalid-feedback ml-3">{{ $errors->first('center_name') }}</div>
                  @endif
                </div>
              </div>


              @include('adminpanel.includes.center.create_center2')

              <div role="tabpanel" class="tab-pane pt-3 in active" id="address-tab">
                <div class="form-group row">
                  <div class="col-md-2 form-control-label">Select City <span class="asterisk">*</span></div>
                  <div class="col-md-4">
                    <select name="city_id" id="city" class="form-control selectpicker" data-live-search="true" required>
                      <option value="">Select City</option>
                      @foreach($cities as $c)
                      <option value="{{ $c->id }}">{{ $c->name }}</option>
                      @endforeach
                    </select>
                    @if($errors->has('city'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('city') }}</div>
                    @endif
                  </div>
                  <div class="col-md-2 form-control-label">Select Area <span class="asterisk">*</span></div>
                  <div class="col-md-4">
                    <select name="area_id" id="area" class="form-control selectpicker"  data-live-search="true" required>
                      <option value="">Select Area</option>
                    </select>
                    @if($errors->has('area'))
                        <div class="invalid-feedback ml-3">{{ $errors->first('area') }}</div>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">Address  <span class="asterisk">*</span></label>
                  <div class="col-md-10 mb-2">
                    <input type="text" name="address" id="address" placeholder="Address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"  required>
                    @if($errors->has('address'))
                    <div class="invalid-feedback ml-3">{{ $errors->first('address') }}</div>
                    @endif
                  </div>
                  <div class="col-md-10  offset-2 map-height" id="locationField">
                    <div class="pac-card" id="pac-card">
                      <div>
                        <div id="title">
                          Autocomplete search
                        </div>
                        <div id="type-selector" class="pac-controls">
                          <input type="radio" name="type" id="changetype-all" checked="checked">
                          <label for="changetype-all">All</label>

                          <input type="radio" name="type" id="changetype-establishment">
                          <label for="changetype-establishment">Establishments</label>

                          <input type="radio" name="type" id="changetype-address">
                          <label for="changetype-address">Addresses</label>

                          <input type="radio" name="type" id="changetype-geocode">
                          <label for="changetype-geocode">Geocodes</label>
                        </div>
                        <div id="strict-bounds-selector" class="pac-controls">
                          <input type="checkbox" id="use-strict-bounds" value="">
                          <label for="use-strict-bounds">Strict Bounds</label>
                        </div>
                      </div>
                      <div id="pac-container">
                        <input id="pac-input" type="text"
                        placeholder="Enter a location">
                      </div>
                    </div>
                    <div id="map"></div>
                    <div id="infowindow-content">
                      <img src="" width="16" height="16" id="place-icon">
                      <span id="place-name"  class="title"></span><br>
                      <span id="place-address"></span>
                    </div>
                  </div>

                </div>
              </div>

              @include('adminpanel.includes.center.create_center3')
              @endsection
              @include('adminpanel.includes.center.create_center_scripts')
