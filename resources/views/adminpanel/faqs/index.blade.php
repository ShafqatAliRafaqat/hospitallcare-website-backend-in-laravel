@extends('adminpanel.layout')
@section('title', 'Faq\'s')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">FAQ's <a href="{{ route('faqs.create') }}" class="btn btn-sm btn-dark float-right">Create New</a></h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="doctors">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Title</th>
                          <th>Treatment</th>
                          <th>Description</th>
                          <th>Picture</th>
                          <th>Status</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($faqs)
                            @php $no=1 @endphp
                            @foreach($faqs as $faq)
                            <tr>
                              <th scope="row">{{$no++}}</th>
                              <td>{{ $faq->title }}</td>
                              <td>{!! str_limit($faq->article,50) !!}</td>
                              <td>
                                <img src="{{ asset('backend/uploads/faqs/'. $faq->picture) }}" width="50" height="50" />
                              </td>
                              <td>{{ $faq->category }}</td>
                              <td>{{ $faq->is_active == 1 ? 'Active' : 'Not Active' }}</td>
                              <td><a href="{{ route('faqs.edit', $faq->id) }}"><i class="fa fa-edit"></i></a></td>
                              <td>
                                <a class="delete" data-id="{{ $faq->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$faq->id}}" method="post" action="{{ route('faqs.destroy', $faq->id) }}">
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
