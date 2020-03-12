@extends('adminpanel.layout')
@section('content')
<div class="container-fluid px-xl-5">
  <section class="py-5">
    <div class="row">
      @include('adminpanel.notification')

    <div class="col-lg-6 mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="h6 text-uppercase mb-0">Lab</h3>
          </div>
          <div class="card-body">
            <div class="media row">
              <div class="media-body col-md-12">
                <p> <strong>Lab Name:</strong> <span>{{ $labs->name }}</span></p>
                <p> <strong>Assistants's Name:</strong> <span>{{ $labs->assistant_name }}</span></p>
                <p> <strong>Assistants's Phone:</strong> <span>{{ $labs->assistant_phone }}</span></p>
                <p> <strong>labs Location:</strong> <span>{{ $labs->address }}</span></p>
                  @php
                    $created_by_id = Auth::user()->find($labs->created_by);
                    $updated_by_id = Auth::user()->find($labs->updated_by);
                    $deleted_by_id = Auth::user()->find($labs->deleted_by);
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
            <h3 class="h6 text-uppercase mb-0">Diagnostic</h3>
          </div>
          <div class="card-body">
            <div class="media row">
              <div class="media-body col-md-12">
                <table class="table table-striped table-dark" id="diagnostics">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Diagnostic</th>
                      <th scope="col">Costs</th>
                    </tr>
                  </thead>
                  <tbody>
                      @if($labs->diagnostic)
                    @php $i=1; @endphp
                  @foreach($labs->diagnostic as $t)
                  <tr >
                    <td>{{ $i }}</td>
                    <td>{{ $t->name }}</td>
                    <td>{{ $t->pivot->cost }}</td>
                  </tr>
                  @php $i++; @endphp
                  @endforeach
                  @endif
                  </tbody>
                </table>

              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div class="row">
      <div class="col-lg-6 mb-5">
        <div class="card">
            <div class="card-header">
              <h3 class="h6 text-uppercase mb-0">Notes</h3>
            </div>
            <div class="card-body">
              <div class="media row">
                <div class="media-body col-md-12">
               {{$labs->notes}}
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
<script src="{{ asset('backend/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/tinymce-config.js') }}" ></script>
<script>
  $(document).ready(function() {
    $('#diagnostics').dataTable( {
        "lengthMenu": [ [5, 10, 15, -1], [5, 10, 15, "All"] ],
        "pageLength": 5
        });
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
@endsection

