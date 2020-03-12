@extends('adminpanel.layout')
@section('title', 'Whitepapers')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Whitepapers<a href="{{ route('whitepaper.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
                  </div>
                  <div class="card-body table-responsive">
             <table class="table table-striped table-sm card-text" id="whitepaper">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Title</th>
                          <th>Description</th>
                          <th>Status</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($whitepapers)
                            @php $no=1 @endphp
                            @foreach($whitepapers as $whitepaper)
                            <tr>
                              <th scope="row">{{$no++}}</th>
                              <td>{{ $whitepaper->title }}</td>
                              <td>{!! str_limit($whitepaper->description,50) !!}</td>
                              <td>{{ $whitepaper->is_active == 1 ? 'Active' : 'Not Active' }}</td>
                              <td><a href="{{ route('whitepaper.edit', $whitepaper->id) }}"><i class="fa fa-edit"></i></a></td>
                              <td>
                                <a class="delete" data-id="{{ $whitepaper->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$whitepaper->id}}" method="post" action="{{ route('whitepaper.destroy', $whitepaper->id) }}">
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
    $('#whitepaper').DataTable();

    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});

$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    if ( confirm('Are you sure, You want to delete?') ){
      $('#deleteForm'+id).submit();
    }else {

    }
});
</script>
@endsection
