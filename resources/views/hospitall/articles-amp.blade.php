@extends('hospitall.master')
@section('content')
  <!-- Start main-content -->
  <div class="main-content">
    <!-- Section: inner-header -->
    <section class="inner-header divider parallax layer-overlay overlay-white-8" data-bg-img="{{ asset('inc/images/home/banner-4.png') }}">
      <div class="container pt-100 pb-100">
        <!-- Section Content -->
        <div class="section-content">
          <div class="row">
            <div class="col-md-12 text-center">
              <h2 class="title">Articles</h3>
              <ol class="breadcrumb text-center text-black mt-10">
                <li><a href="/">Home</a></li>
                <li class="active text-theme-colored">Articles</li>
              </ol>
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
            @if(count($articles)>0)
            @foreach($articles as $a)
            <div class="upcoming-events bg-white-f3 mb-20">
              <div class="row">
                <div class="col-sm-4 pr-0 pr-sm-15">
                  <div class="thumb p-15">
                    <img class="img-fullwidth" src="{{ asset('backend/uploads/articles/'.$a->picture) }}" alt="{{str_slug($a->title)}}">
                  </div>
                </div>
                <div class="col-sm-4 pl-0 pl-sm-15">
                  <div class="event-details p-15 mt-20">
                    <h4 class="mt-0 text-uppercase font-weight-500">{{$a->title}}</h4>
                    <p>{!! str_limit($a->article,100) !!}</p>
                    <a href="{{ route('procedure-detail', [$a->id,str_slug($a->title)]) }}" class="btn btn-flat btn-dark btn-theme-colored btn-sm mt-10">Details <i class="fa fa-angle-double-right"></i></a>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="event-count p-15 mt-15">
                    <ul class="event-date list-inline font-16 text-uppercase mt-10 mb-20">
                      <li class="p-15 mr-5 bg-lightest">{{date('M', strtotime($a->created_at))}}</li>
                      <li class="p-15 pl-20 pr-20 mr-5 bg-lightest">{{date('d', strtotime($a->created_at))}}</li>
                      <li class="p-15 bg-lightest">{{date('Y', strtotime($a->created_at))}}</li>
                    </ul>
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
            @endif
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- end main-content -->
@endsection
