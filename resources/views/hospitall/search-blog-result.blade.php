@extends('hospitall.master')
@section('content')
  <!-- Start main-content -->
  <div class="main-content">
    <!-- Section: inner-header -->
    <section class="inner-header divider parallax layer-overlay overlay-white-8" data-bg-img="{{ asset('inc/images/background.jpg') }}">
      <div class="container pt-60 pb-60">
        <!-- Section Content -->
        <div class="section-content">
          <div class="row">
            <div class="col-md-12 text-center">
              <h2 class="title">{{ ucfirst($slug) }} Search Result</h3>
              <ol class="breadcrumb text-center text-black mt-10">
                <li><a href="/">Home</a></li>
                <li class="active text-theme-colored"><h1>Articles</h1></li>
              </ol>
                <form action="{{ route('search-blogs-post') }}" method="post" class="form-horizantal">
                  @csrf
                  <div class="form-group row">
                      <div class="col-md-8">
                          <input type="text" value="{{ $slug }}" placeholder="Search Articles" name="term" class="form-control p-15" id="tags">
                      </div>
                      <div class="col-md-4">
                          <input type="submit" class="form-control btn-theme-colored p-15 m-sm-10" value="Search">
                      </div>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section: event calendar -->
    <section>
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            @if(count($blogs) > 0)
            @foreach($blogs as $b)
            <div class="upcoming-events bg-white-f3 mb-20">
              <div class="row">
                <div class="col-sm-4 pr-0 pr-sm-15">
                  <div class="thumb p-15">
                    <img class="img-fullwidth" src="{{ asset('backend/uploads/articles/'.$b->picture) }}" alt="{{str_slug($b->title)}}">
                  </div>
                </div>
                <div class="col-sm-4 pl-0 pl-sm-15">
                  <div class="event-details p-15 mt-20">
                    <h4 class="mt-0 text-uppercase font-weight-500">{{$b->title}}</h4>
                    <p>{!! str_limit($b->article,100) !!}</p>
                    <a href="{{ route('article', [$b->id,str_slug($b->title)]) }}" class="btn btn-flat btn-dark btn-theme-colored btn-sm mt-10">Details <i class="fa fa-angle-double-right"></i></a>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="event-count p-15 mt-15">
                    <ul class="event-date list-inline font-16 text-uppercase mt-10 mb-20">
                      <li class="p-15 mr-5 bg-lightest">{{ date('M', strtotime($b->created_at)) }}</li>
                      <li class="p-15 pl-20 pr-20 mr-5 bg-lightest"> {{ date('d', strtotime($b->created_at)) }}</li>
                      <li class="p-15 bg-lightest">{{ date('Y', strtotime($b->created_at)) }}</li>
                    </ul>
<!--                     <ul>
                      <li class="mb-10"><a href="#"><i class="fa fa-clock-o mr-5"></i> at 5.00 pm - 7.30 pm</a></li>
                      <li><a href="#"><i class="fa fa-map-marker mr-5"></i> 25 Newyork City.</a></li>
                    </ul> -->
                  </div>
                </div>
              </div>
            </div>
            @endforeach
            <div class="row">
              <div class="col-sm-12">
                <nav>
                 {!! $pagination !!}
                </nav>
              </div>
            </div>
            @else
            <h2>No Treatment Match Your Criteria</h2>
            @endif
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- end main-content -->
@endsection
@section('scripts')
<script>
  $( function() {
    $( "#tags" ).autocomplete({
      source: "{{ route('search-blogs') }}"
    });
  } );
  </script>
@endsection
