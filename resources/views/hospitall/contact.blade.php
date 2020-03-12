@extends('hospitall.master')
@section('title','Contact Us |')
@section('content')
<!-- Start main-content -->
    <div class="main-content">
        <!-- Section: inner-header -->
        <section class="inner-header" data-bg-img="{{ asset('inc/images/contact.jpg') }}" style="background:no-repeat; background-size:cover;">
          <div class="container pt-150 pb-150">
            <!-- Section Content -->
            <div class="section-content">
              <div class="row">
                <div class="col-md-12 text-center">
                  
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Divider: Contact -->
        <section class="divider">
          <div class="container">
            <div class="row pt-30">
              <div class="col-md-4">
                <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="icon-box left media bg-deep p-30 mb-20"> <a class="media-left pull-left" href="#"> <i class="pe-7s-map-2 text-theme-colored"></i></a>
                          <div class="media-body">
                            <h5 class="mt-0">Our Office Location</h5>
                            <p>{{ $setting->address }}</p>
                          </div>
                        </div>
                      </div>
                  <div class="col-xs-12 col-sm-6 col-md-12">
                    <div class="icon-box left media bg-deep p-30 mb-20"> <a class="media-left pull-left" href="#"> <i class="pe-7s-call text-theme-colored"></i></a>
                      <div class="media-body">
                        <h5 class="mt-0">Contact Number</h5>
                        <p>{{ $setting->mobile }}</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-12">
                    <div class="icon-box left media bg-deep p-30 mb-20"> <a class="media-left pull-left" href="#"> <i class="pe-7s-mail text-theme-colored"></i></a>
                      <div class="media-body">
                        <h5 class="mt-0">Email Address</h5>
                        <p>{{ $setting->email }}</p>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
              <div class="col-md-8">
                <h1 class="line-bottom mt-0 mb-30">Get in touch with us</h1>
                <!-- Contact Form -->
                <form id="contact_form" name="contact_form" class="" action="{{ route('contact-form') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Name <small>*</small></label>
                            <input name="name" class="form-control" type="text" placeholder="Enter Name required" required="">
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Number <small>*</small></label>
                            <input name="number" class="form-control required" type="number" placeholder="Enter Number" required="">
                          </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Prefered date to contact<small>*</small></label>
                              <div class="input-group date" id="datetimepicker1">
                                <input type="text" name="date" class="form-control required date-picker">
                                <span class="input-group-addon">
                                  <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                              </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Prefered time to contact<small>*</small></label>
                            <select name="time_slot" class="form-control required" required>
                                <option name="0">Select Time Slot</option>
                                <option value="9AM to 11AM">9AM to 11AM</option>
                                <option value="11AM to 1PM">11AM to 1PM</option>
                                <option value="1PM to 3PM">1PM to 3PM</option>
                                <option value="3PM to 5PM">3PM to 5PM</option>
                                <option value="5PM to 7PM">5PM to 7PM</option>
                            </select>
                          </div>
                        </div>

                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Select your preferred procedures<small>*</small></label>
                            <select name="procedure" class="form-control required">
                                <option name="0">Select Procedure</option>
                                @foreach($menutreatments as $t)
                                    <option value="{{ $t->name }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Email <small>*</small></label>
                            <input name="email" class="form-control required email" type="email" placeholder="Enter Email">
                          </div>
                        </div>
                    </div>

                  <div class="form-group">
                    <input name="form_botcheck" class="form-control" type="hidden" value="" />
                    <button type="submit" class="btn btn-dark btn-theme-colored btn-flat mr-5" data-loading-text="Please wait...">Send your message</button>
                    <button type="reset" class="btn btn-default btn-flat btn-theme-colored">Reset</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </section>
    </div>
@endsection
@section('scripts')
<script type="text/javascript">
$(function () {
    $('.date-picker').datepicker();
});
</script>
@endsection
