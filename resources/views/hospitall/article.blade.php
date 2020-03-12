@extends('hospitall.master')
@section('title',$article->meta_title)
@section('content')
  <!-- Start main-content -->
  <div class="main-content">
    <!-- Section: inner-header -->
    <section class="inner-header divider parallax layer-overlay" data-bg-img="{{ asset('inc/images/home/banner-4.png') }}">
      <div class="container pt-150 pb-150">

      </div>
    </section>

    <!-- Section: Blog -->
    <section>
      <div class="container mt-30 mb-30 pt-30 pb-30">
        <div class="row">
          <div class="col-md-3">
            <div class="sidebar sidebar-left mt-sm-30">
              <div class="widget">
                <h5 class="widget-title line-bottom">blogs</h5>
                <div class="latest-posts">
                    @foreach($articles as $a)
                    <article class="post media-post clearfix pb-0 mb-10">
                        <a class="post-thumb" href="{{ route('article', [$a->id,str_slug($a->title)]) }}">
                            <img width="50" height="50" src="{{ asset('backend/uploads/articles/'.strtolower($a->picture) ) }}" alt="">
                        </a>
                        <div class="post-right">
                          <a class="post-thumb" href="{{ route('article', [$a->id,str_slug($a->title)]) }}">
                          <h5 class="post-title mt-0">{{ $a->title }}</h5>
                          <p>{!! str_limit($a->article,'25') !!}</p>
                          </a>
                        </div>
                    </article>
                    @endforeach
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-9">
            <div class="blog-posts single-post">
              <article class="post clearfix mb-0">
                <div class="entry-header">
                  <div class="post-thumb thumb"> <img src="{{ asset('backend/uploads/articles/'.$article->picture) }}" alt="" class="img-responsive img-fullwidth"> </div>
                </div>
                <div class="entry-content">
                  <div class="entry-meta media no-bg no-border mt-15 pb-20">
                    <div class="entry-date media-left text-center flip bg-theme-colored pt-5 pr-15 pb-5 pl-15">
                      <ul>
                        <li class="font-16 text-white font-weight-600">{{ date('d',strtotime($article->created_at)) }}</li>
                        <li class="font-12 text-white text-uppercase">{{ date('M',strtotime($article->created_at)) }}</li>
                      </ul>
                    </div>
                    <div class="media-body pl-15">
                      <div class="event-content pull-left flip">
                        <h1 class="entry-title text-white font-24px text-uppercase m-0"><a href="#">{{ $article->title }}</a></h1>
                        <!-- <span class="mb-10 text-gray-darkgray mr-10 font-13"><i class="fa fa-commenting-o mr-5 text-theme-colored"></i> 214 Comments</span>
                        <span class="mb-10 text-gray-darkgray mr-10 font-13"><i class="fa fa-heart-o mr-5 text-theme-colored"></i> 895 Likes</span> -->
                      </div>
                    </div>
                  </div>
                  <p class="mb-15">{!! $article->article !!}</p>
                  <div class="mt-30 mb-0">
                    <h5 class="pull-left mt-10 mr-20 text-theme-colored">Share:</h5>
                    <ul class="styled-icons icon-circled m-0">
                      <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}" data-bg-color="#3A5795" class="social-share"><i class="fa fa-facebook text-white"></i></a></li>
                      <li><a href="https://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}" data-bg-color="#55ACEE" class="social-share"><i class="fa fa-twitter text-white"></i></a></li>
                      <li><a href="https://plus.google.com/share?url={{ urlencode(Request::fullUrl()) }}" data-bg-color="#A11312" class="social-share"><i class="fa fa-google-plus text-white"></i></a></li>
                    </ul>
                  </div>
                </div>
              </article>
              <div class="tagline p-0 pt-20 mt-5">
                <div class="row">
                  <div class="col-md-8">
                    <div class="tags">
                      <p class="mb-0"><i class="fa fa-tags text-theme-colored"></i> <span>Tags:</span>{{ $article->url
                       }}</p>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="share text-right">
                      <p><i class="fa fa-share-alt text-theme-colored"></i> Share</p>
                    </div>
                  </div>
                </div>
              </div>
                <div class="clearfix"></div>
            </div>
          </div>

        </div>
      </div>
    </section>
  </div>
  <!-- end main-content -->
@endsection
