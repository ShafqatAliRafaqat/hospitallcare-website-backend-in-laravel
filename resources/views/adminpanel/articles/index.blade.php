@extends('adminpanel.layout')
@section('title', 'Articles')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Articles <a href="{{ route('articles.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="doctors">
                      <thead class="thead-light">
                        <tr>
                          <th>#</th>
                          <th>Title</th>
                          <th>Description</th>
                          <th>Picture</th>
                          <th>Category</th>
                          <th>Status</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($articles)
                            @php $no=1 @endphp
                            @foreach($articles as $article)
                            <tr>
                              <th scope="row">{{$no++}}</th>
                              <td>{{ $article->title }}</td>
                              <td>{!! str_limit($article->article,50) !!}</td>
                              <td>
                                <img src="{{ asset('backend/uploads/articles/'. $article->picture) }}" width="50" height="50" />
                              </td>
                              <td>{{ $article->category }}</td>
                              <td>{{ $article->is_active == 1 ? 'Active' : 'Not Active' }}</td>
                              <td><a href="{{ route('articles.edit', $article->id) }}"><i class="fa fa-edit"></i></a></td>
                              <td>
                                <a class="delete" data-id="{{ $article->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$article->id}}" method="post" action="{{ route('articles.destroy', $article->id) }}">
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
    $('#doctors').DataTable();

    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});

$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    if ( confirm('Are you sure to delete?') ){
      $('#deleteForm'+id).submit();
    }else {

    }
});
</script>
@endsection
