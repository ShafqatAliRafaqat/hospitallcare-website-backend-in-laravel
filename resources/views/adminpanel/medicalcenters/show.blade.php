@extends('adminpanel.layout')
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      @include('adminpanel.notification')
        <div class="col-lg-6 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Medical Center<a href="{{ route('medical.edit', $center->id) }}" class="btn btn-sm btn-dark float-right">
                      Edit Center
            </a></h3>
          </div>
          <div class="card-body">
            <div class="media row">
              @if(isset($center->center_image))
                <img src="{{ asset('backend/uploads/centers/'.$center->center_image->picture) }}"
                alt="" class="col-md-5">
              @endif
              <div class="media-body col-md-6">
                <p> <strong>Center Name:</strong> <span>{{ $center->center_name }}</span></p>
                <p> <strong>Assistants's Name:</strong> <span>{{ $center->assistant_name }}</span></p>
                <p> <strong>Assistants's Phone:</strong> <span>{{ $center->assistant_phone }}</span></p>
                <p> <strong>Landline Number:</strong> <span>{{ $center->phone }}</span></p>
                <p> <strong>Center Location:</strong> <span>{{ $center->address }}</span></p>
                <p> <strong>City:</strong> <span>{{ $center->city_name }}</span></p>
                <p> <strong>Focus Area:</strong> <span>{{ $center->focus_area }}</span></p>
                  @php
                    $created_by_id = Auth::user()->find($center->created_by);
                    $updated_by_id = Auth::user()->find($center->updated_by);
                    $deleted_by_id = Auth::user()->find($center->deleted_by);
                  @endphp
                  <div class="row">
                    <div class="col-md-12 text-right" style="color: grey">
                      <span> {{ isset($created_by_id->name)? "Created By: ".$created_by_id->name:"" }}</span><br>
                      <span>{{ isset($updated_by_id->name)? "Updated By: ".$updated_by_id->name:"" }}</span><br>
                      <span>{{ isset($deleted_by_id->name)? "Deleted By: ".$deleted_by_id->name:"" }}</span>
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Treatments Offered</h3>
          </div>
          <div class="card-body">
            <div class="media row">
              <div class="media-body col-md-12">
                <table class="table table-striped table-dark" id="treatments">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Treatments</th>
                      <th scope="col">Costs</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $i=1; @endphp
                  @foreach($center->center_treatment as $t)
                  <tr >
                    <td>{{ $i }}</td>
                    <td>{{ $t->name }}</td>
                    <td>{{ $t->pivot->cost }}</td>
                  </tr>
                  @php $i++; @endphp
                  @endforeach
                  </tbody>
                </table>

              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Partnership Details</h3>
          </div>
          <div class="card-body">
            <div class="media row">
              <div class="media-body col-md-6">
                <p> <strong>Ad Spent:</strong> <span>{{ $center->ad_spent }}</span></p>
                <p> <strong>Revenue Share:</strong> <span>{{ $center->revenue_share }}</span></p>
                <p> <strong>Additional Details:</strong> <span>{!! $center->additional_details !!}</span></p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Partnership Detail's Images &amp; Files</h3>
          </div>
          <div class="card-body">
            <div class="media row mb-3">
                <div class="col-md-2">
                  Files:
                </div>
              @if(isset($center->center_partnership_files))
                @foreach($center->center_partnership_files as $prtnr_file)
                  <a href="#" class="pop_pdf">
                    @php
                    $sprt = explode('.',$prtnr_file->file);
                    @endphp
                    {{ $sprt[1] }}
                    <img id="zoom_mw" data-zoom-image="large/image1.jpg"/ src="{{ asset('backend/uploads/center_partnership_files/'.$prtnr_file->file) }}"
                    alt="" class="col-md-6 mt-1 responsive" max-height="200px" max-width="200px">
                  </a>
              @endforeach
              @endif
            </div>
              <div class="row">
                <div class="col-md-6">
                  <label>Images</label>
                </div>
                <div class="col-md-6">
              @if(isset($center->center_partnership_images))
                @foreach($center->center_partnership_images as $prtnr_image)
                <a href="#" class="pop">
                  <img id="zoom_mw" data-zoom-image="large/image1.jpg"/ src="{{ asset('backend/uploads/center_partnership_images/'.$prtnr_image->picture) }}"
                  alt="" class="col-md-3 mt-1 responsive" max-height="100px" max-width="100px">
                </a>
                @endforeach
              @endif
                </div>
              </div>

          </div>
        </div>
      </div>
        @isset($center->article)
        <div class="col-lg-6 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Article</h3>
          </div>
          <div class="card-body">
            <div class="media row">
              <div class="media-body col-md-6">
                {!! $center->article !!}
              </div>
            </div>
          </div>
        </div>
      </div>
      @endisset
    </div>
  </section>
</div>

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <img src="" class="imagepreview" style="width: 100%;" >
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="pdfmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <embed src="" class="pdfpreview" frameborder="0" width="100%" height="600px">
      </div>
    </div>
  </div>
</div>

@endsection
@section('scripts')
<script>
 $(function() {
    $('.pop').on('click', function() {
      $('.imagepreview').attr('src', $(this).find('img').attr('src'));
      $('#imagemodal').modal('show');
    });
});
</script>
<script>
 $(function() {
    $('.pop_pdf').on('click', function() {
      $('.pdfpreview').attr('src', $(this).find('img').attr('src'));
      $('#pdfmodal').modal('show');
    });
});
</script>
<script>
$(document).ready(function() {
    $('#treatments').DataTable({
        "lengthMenu": [5, 10, 15, 20, 25],
        "pageLength": 5
    });
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
<script src="{{ asset('backend/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/tinymce-config.js') }}" ></script>
@endsection

