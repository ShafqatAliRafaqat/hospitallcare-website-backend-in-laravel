@extends('adminpanel.layout')
@section('title', 'Packages')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Packages <a href="{{ route('packages.create') }}" class="btn btn-sm btn-dark float-right">Create New Package</a></h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="packages">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Center Name</th>
                          <th>Procedure Name</th>
                          <th>Package Name</th>
                          <th>Price</th>
                          <th>Article</th>
                          <th>Picture</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($packages)
                            @php $no=1 @endphp
                            @foreach($packages as $package)
                            <tr>
                              <th scope="row">{{$no++}}</th>
                              <td>{{ $package->center_name }}</td>
                              <td>{{ $package->procedure }}</td>
                              <td>{{ $package->package_name }}</td>
                              <td>{{ $package->price }}</td>
                              <td>{!! str_limit($package->article,50) !!}</td>
                              <td>
                                <img src="{{ asset('backend/uploads/packages/'. $package->picture) }}" width="50" height="50" />
                              </td>
                              <td><a href="{{ route('packages.edit', $package->id) }}"><i class="fa fa-edit"></i></a></td>
                              <td>
                                <a class="delete" data-id="{{ $package->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$package->id}}" method="post" action="{{ route('packages.destroy', $package->id) }}">
                                    @csrf @method('delete')
                                </form>
                              </td>
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
<script>
$(document).ready(function() {
    $('#packages').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});

$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    $('#deleteForm'+id).submit();
});
</script>
@endsection
