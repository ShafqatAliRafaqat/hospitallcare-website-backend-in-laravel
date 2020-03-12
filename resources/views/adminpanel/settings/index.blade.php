@extends('adminpanel.layout')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Settings
                      @if($settings)
                      <a href="{{ route('settings.edit', $settings->id) }}" class="btn btn-sm btn-dark float-right">Edit</a>
                      @else
                      <a href="{{ route('settings.create') }}" class="btn btn-sm btn-dark float-right">Create</a>
                      @endif
                    </h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="settings">
                      <thead class="thead-light">
                        <tr>
                          <th>Mobile</th>
                          <th>Address</th>
                          <th>Email</th>
                          <th>Facebook</th>
                          <th>Twitter</th>
                          <th>Skype</th>
                          <th>Youtube</th>
                          <th>Instagram</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($settings)
                            @php $no=1 @endphp
                            <tr>
                              <td>{{ $settings->mobile }}</td>
                              <td>{{ $settings->address }}</td>
                              <td>{{ $settings->email }}</td>
                              <td>{{ $settings->facebook }}</td>
                              <td>{{ $settings->twitter }}</td>
                              <td>{{ $settings->skype }}</td>
                              <td>{{ $settings->youtube }}</td>
                              <td>{{ $settings->instagram }}</td>
                            </tr>
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
<script>
$(document).ready(function() {
    $('#settings').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
@endsection


