@extends('adminpanel.layout')
@section('title', 'Edit Doctor | HospitALL')
@section('styles')
<link rel="stylesheet" href="{{ asset('backend/js/select2-develop/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/fileupload.css') }}">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      @include('adminpanel.notification')
      <div class="col-lg-12 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Edit Doctor</h3>
          </div>
          <div class="card-body">
            <form class="form-horizontal" id="doctor-form" name="edit-doctor" method="post" action="{{ route('doctors.update', $doctor->id) }}" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              @include('adminpanel/includes/doctor/edit_doctor')
              @endsection
              @include('adminpanel/includes/doctor/edit_doctor_scripts')
