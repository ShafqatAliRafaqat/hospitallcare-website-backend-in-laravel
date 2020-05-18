@extends('adminpanel.layout')
@section('title', 'Cities')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header"><h6>List of Cities</h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="status">
                      <thead class="thead-light">
                        <tr>
                          <th>#</th>
                          <th>City Name</th>
                          <th>Active</th>
                          <th>Edit/Add Areas</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if(count($cities) > 0 )
                            @php $no=1 @endphp
                            @foreach($cities as $c)
                            <tr>
                              <th scope="row">{{$no++}}</th>
                              <td>{{ $c->name }}</td>
                              <td>{{ $c->is_active == 1 ? 'Active' : 'Not Active' }}</td>
                              <td><a href="{{ route('city.edit', $c->id) }}"><i class="far fa-plus-square"></i></a></td>
                            </tr>
                            @endforeach
                        @endif
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('scripts')
<script src="{{ asset('backend/js/sweetalert/sweetalert.js') }}"></script>
<script>
$(document).ready(function() {
    $('#status').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
@endsection
