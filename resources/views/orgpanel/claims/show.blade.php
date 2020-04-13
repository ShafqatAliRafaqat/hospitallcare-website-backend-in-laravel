<?php
use Carbon\Carbon;
?>
@extends('orgpanel.layout')
@section('title', 'Employee | Claim')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card" style="min-height: 520px;">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Medical Claim
                    </h6>
                  </div>
                  <div class="card-body table-responsive">
              <div class="row">
                <div class="col-lg-4">
                  <h5>Claim Details
                    @if($patient->claim_status == 0 || $patient->claim_status == 3)
                      <a type="button" class="edit a-hover float-right" data-id="{{ $patient->claim_id }}">
                        <img src="{{ asset('backend/web_imgs/edit2.png') }}" width="24px">
                      </a>
                    @endif
                </h5>

                  <form id="editForm{{$patient->claim_id}}" method="post" action="{{ route('edit_claim', $patient->claim_id) }}">
                      @csrf @method('post')
                  </form>
                </div>
                <div class="col-lg-8 tab-profile">
                    <nav>
                      <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-overview-tab" data-toggle="tab" href="#nav-overview" role="tab" aria-controls="nav-overview" aria-selected="true">Overview</a>
                        <a class="nav-item nav-link" id="nav-employe-details-tab" data-toggle="tab" href="#nav-employe-details" role="tab" aria-controls="nav-employe-details" aria-selected="false">Employee Details</a>
                        <a class="nav-item nav-link" id="nav-action-tab" data-toggle="tab" href="#nav-action" role="tab" aria-controls="nav-action" aria-selected="false">Status and Attachments</a>
                      </div>
                    </nav>
                    <!-- Start of Overview Tab -->
                    <div class="tab-content py-3 px-3 px-sm-0 tab-design" id="nav-tabContent">
                      <div class="tab-pane fade show active" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab">
                        <h6>General</h6>
                        <div class="claim-details">
                          <ul class="list-inline claim-ul">
                            <li><div class="row mt-4">
                              <div class="col-md-4"><span class="claim-title-span">Claim For</span></div>
                              <div class="col-md-8"><span class="claim-text-span">{{ $patient->name }}</span></div></div>
                            </li>
                            <li><div class="row mt-4">
                              <div class="col-md-4"><span class="claim-title-span">Relationship</span></div>
                              <div class="col-md-8"><span class="claim-text-span">@php if($patient->relation == null){ echo "Self"; } else { echo $patient->relation; }  @endphp</span></div></div>
                            </li>
                            <li><div class="row mt-4">
                              <div class="col-md-4"><span class="claim-title-span">Claim Title</span></div>
                              <div class="col-md-8"><span class="claim-text-span">{{ $patient->claim_title }}</span></div></div>
                            </li>
                            <li><div class="row mt-4">
                              <div class="col-md-4"><span class="claim-title-span">Category</span></div>
                              <div class="col-md-8"><span class="claim-text-span">{{ $patient->category }}</span></div></div>
                            </li>
                            @if($type == 'treatments')
                            <li><div class="row mt-4">
                              <div class="col-md-4"><span class="claim-title-span">Doctor</span></div>
                              <div class="col-md-8"><span class="claim-text-span">{{ $patient->doctor_name }}</span></div></div>
                            </li>
                            @endif
                            <li><div class="row mt-4">
                              <div class="col-md-4"><span class="claim-title-span">Center</span></div>
                              <div class="col-md-8"><span class="claim-text-span">{{ $patient->center_name }}</span></div></div>
                            </li>
                            <li><div class="row mt-4">
                              <div class="col-md-4"><span class="claim-title-span">Total Amount</span></div>
                              <div class="col-md-8"><span class="claim-text-span">Rs. {{ $patient->total_amount }}</span></div></div>
                            </li>
                            @php
                              $date           =   Carbon::parse($patient->treatment_date);
                              $fdate          =   $date->format('jS F Y');
                              $time           =   $date->format('h:i A');
                            @endphp
                            <li><div class="row mt-4">
                              <div class="col-md-4"><span class="claim-title-span">Treatment Date</span></div>
                              <div class="col-md-8"><span class="claim-text-span">{{ $fdate }} at {{ $time }}</span></div></div>
                            </li>
                            @php
                              $date           =   Carbon::parse($patient->claim_date);
                              $fdate          =   $date->format('jS F Y');
                              $time           =   $date->format('h:i A');
                            @endphp
                            <li><div class="row mt-4">
                              <div class="col-md-4"><span class="claim-title-span">Claim Date</span></div>
                              <div class="col-md-8"><span class="claim-text-span">{{ $fdate }} at {{ $time }}</span></div></div>
                            </li>
                            <li><div class="row mt-4">
                              <div class="col-md-4"><span class="claim-title-span">Claim Status</span></div>
                              <div class="col-md-8"><span class="claim-text-span">@php echo claimStatusName($patient->claim_status);  @endphp</span></div></div>
                            </li>
                            <li><div class="row mt-4">
                              <div class="col-md-4"><span class="claim-title-span">Source</span></div>
                              <div class="col-md-8">
                                <span class="claim-text-span">
                                  @if($type == 'custom')
                                  Not Verified
                                  @else
                                  Verified
                                  @endif
                                </span>
                              </div></div>
                            </li>

                          </ul>
                        </div>
                      </div>
                      <!-- End of Overview Tab -->
                      <!-- Start of Employee Details Tab -->
                      <div class="tab-pane fade" id="nav-employe-details" role="tabpanel" aria-labelledby="nav-employe-details-tab">
                        <h6>Employee Details</h6>
                        <div class="claim-details">
                          <ul class="list-inline claim-ul">
                            <li><div class="row mt-4">
                              <div class="col-md-4"><span class="claim-title-span">Name</span></div>
                              <div class="col-md-8"><span class="claim-text-span">{{ $employee_details['name'] }}</span></div></div>
                            </li>
                            <li><div class="row mt-4">
                              <div class="col-md-4"><span class="claim-title-span">Employee Code</span></div>
                              <div class="col-md-8"><span class="claim-text-span">{{$employee_details['employee_code']}}</span></div></div>
                            </li>
                            <li><div class="row mt-4">
                              <div class="col-md-4"><span class="claim-title-span">Email</span></div>
                              <div class="col-md-8"><span class="claim-text-span">{{$employee_details['email']}}</span></div></div>
                            </li>
                            <li><div class="row mt-4">
                              <div class="col-md-4"><span class="claim-title-span">Contact #</span></div>
                              <div class="col-md-8"><span class="claim-text-span">{{$employee_details['phone']}}</span></div></div>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <!-- End of Employee Details Tab -->
                      <!-- Start of ActionTab -->
                      <div class="tab-pane fade" id="nav-action" role="tabpanel" aria-labelledby="nav-action-tab">
                        <h6>Status</h6>
                        <form method="POST" action="{{ route('update_claim', $patient->claim_id) }}" enctype="multipart/form-data">
                          @csrf @method('post')
                          <div class="claim-details">
                            @if(isset($patient->doctor_fee))
                            <div class="row mt-2">
                              <div class="col-md-4"><span class="claim-title-span">Doctor Fee</span></div>
                              <div class="col-md-6"><span class="claim-text-span float-right">Rs. {{ $patient->doctor_fee }}</span></div>
                            </div>
                            @endif
                            @if(isset($patient->diagnostic_fee))
                            <div class="row mt-2">
                              <div class="col-md-4"><span class="claim-title-span">Diagnostics Fee</span></div>
                              <div class="col-md-6"><span class="claim-text-span float-right">Rs. {{ $patient->diagnostic_fee }}</span></div>
                            </div>
                            @endif
                            @if(isset($patient->medicine_fee))
                            <div class="row mt-2">
                              <div class="col-md-4"><span class="claim-title-span">Medicines</span></div>
                              <div class="col-md-6"><span class="claim-text-span float-right">Rs. {{ $patient->medicine_fee }}</span></div>
                            </div>
                            @endif
                            @if(isset($patient->other_fee))
                            <div class="row mt-2">
                              <div class="col-md-4"><span class="claim-title-span">Other Hospitallization Charges</span></div>
                              <div class="col-md-6"><span class="claim-text-span float-right">Rs. {{ $patient->other_fee }}</span></div>
                            </div>
                            @endif
                            <div class="row">
                              <div class="col-md-4"><span class="claim-title-span" ></span></div>
                              <div class="col-md-6  offset-md-2"><hr style="margin: 7px;"></div>
                            </div>
                            <div class="row">
                              <div class="col-md-4"><span class="claim-title-span" >Total Amount</span></div>
                              <div class="col-md-6"><span class="claim-text-span float-right">Rs. {{ $patient->total_amount }}</span></div>
                            </div>
                            <hr style="margin: 7px;">
                            <div class="row mt-4">
                              <div class="col-md-4"><span class="claim-title-span">Internal Comments</span></div>
                              <div class="col-md-8 text-justify"><span class="claim-text-span">{{$patient->internal_comment}}</span></div>
                            </div>
                            <div class="row mt-4">
                              <div class="col-md-4"><span class="claim-title-span">Comments</span></div>
                              <div class="col-md-8 text-justify"><span class="claim-text-span">{{$patient->claim_comment}}</span></div>
                            </div>
                            <hr>
                            <h5>Attachments</h5>
                            @if(count($invoices)>0)
                            <h6>Invoices</h6>
                            <div class="row">
                              @foreach($invoices as $invoice)
                              <div class="mdb-lightbox col-md-1">
                                <figure>
                                  <a href="http://localhost:8000/backend/uploads/customer_invoices/{{$invoice->image}}" data-size="1600x1067">
                                    <img alt="picture" src="{{ asset('backend/uploads/customer_invoices/'.$invoice->image) }}" class="img-fluid">
                                  </a>
                                </figure>
                              </div>
                              @endforeach
                            </div>
                            @endif
                            @if(count($customer_claim_documents)>0)
                            <h6>Other Documents</h6>
                            <div class="row">
                              @foreach($customer_claim_documents as $document)
                              <div class="mdb-lightbox col-md-1">
                                <figure>
                                  <a href="http://localhost:8000/backend/uploads/customer_claim_documents/{{$document->image}}" data-size="1600x1067">
                                    <img alt="picture" src="{{ asset('backend/uploads/customer_claim_documents/'.$document->image) }}" class="img-fluid">
                                  </a>
                                </figure>
                              </div>
                              @endforeach
                            </div>
                            @endif
                          </div>
                        </form>
                      </div>
                      <!-- End of Action Tab -->
                    </div>
                </div>
            </div>
                  </div>
                </div>
            </div>
        </div>

    </section>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
  // MDB Lightbox Init
$(function () {
$("#mdb-lightbox-ui").load("mdb-addons/mdb-lightbox-ui.html");
});
</script>
<script>
  $(document).on('click', '.edit', function(){
    var id = $(this).data('id');
    $('#editForm'+id).submit();
  });
</script>
@endsection
