@extends('adminpanel.layout')
@section('title', 'Videos')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Videos <a href="{{ route('videos.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="videos">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Title</th>
                          <th>Description</th>
                          <th>Picture</th>
                          <th>Category</th>
                          <th>Source</th>
                          <th>Status</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($videos)
                            @php $no=1 @endphp
                            @foreach($videos as $video)
                            <tr>
                              <th scope="row">{{$no++}}</th>
                              <td>{{ $video->title }}</td>
                              <td>{!! str_limit($video->article,50) !!}</td>
                              <td>
                                <img src="{{ asset('backend/uploads/videos/'. $video->picture) }}" width="50" height="50" />
                              </td>
                              <td>{{ $video->category }}</td>
                              <td>{{ $video->source }}</td>
                              <td>{{ $video->is_active == 1 ? 'Active' : 'Not Active' }}</td>
                              <td><a href="{{ route('videos.edit', $video->id) }}"><i class="fa fa-edit"></i></a></td>
                              <td>
                                <a class="delete" data-id="{{ $video->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$video->id}}" method="post" action="{{ route('videos.destroy', $video->id) }}">
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
    $('#videos').DataTable();
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
