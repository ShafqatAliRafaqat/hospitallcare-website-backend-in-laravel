@extends('hospitall.master')
@section('content')
  <!-- Start main-content -->
  <div class="main-content">

    <!-- Section: inner-header -->
    <section class="inner-header divider parallax layer-overlay overlay-dark-8" data-bg-img="http://placehold.it/1920x1280">
      <div class="container pt-60 pb-60">
        <!-- Section Content -->
        <div class="section-content">
          <div class="row">
            <div class="col-md-12 xs-text-center">
              <h3 class="title text-white">{{$procedure->name}}</h3>
              <ol class="breadcrumb mt-10 white">
                <li><a class="text-white" href="#">Home</a></li>
                <li class="active text-theme-colored">{{$procedure->name}}</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section: Blog -->
    <section>
      <div class="container mt-30 mb-30 pt-30 pb-30">
        <div class="row">
          <div class="col-md-9 pull-right flip sm-pull-none">
            <div class="blog-posts single-post">
              <article class="post clearfix mb-0">
                <div class="entry-header">
                  <div class="post-thumb thumb"> <img src="{{ asset('backend/uploads/procedures/'. $procedure->picture) }}" alt="" class="img-responsive img-fullwidth"> </div>
                </div>
                <div class="entry-content">
                  <div class="entry-meta media no-bg no-border mt-15 pb-20">
                    <div class="entry-date media-left text-center flip bg-theme-colored pt-5 pr-15 pb-5 pl-15">
                      <ul>
                        <li class="font-16 text-white font-weight-600">{{ date('d',strtotime($procedure->updated_at)) }}</li>
                        <li class="font-12 text-white text-uppercase">{{ date('M',strtotime($procedure->updated_at)) }}</li>
                      </ul>
                    </div>
                    <div class="media-body pl-15">
                      <div class="event-content pull-left flip">
                        <h4 class="entry-title text-white text-uppercase m-0"><a href="#">{{$procedure->name}}</a></h4>
                        <span class="mb-10 text-gray-darkgray mr-10 font-13"><i class="fa fa-commenting-o mr-5 text-theme-colored"></i> 214 Comments</span>
                        <span class="mb-10 text-gray-darkgray mr-10 font-13"><i class="fa fa-heart-o mr-5 text-theme-colored"></i> 895 Likes</span>
                      </div>
                    </div>
                  </div>
                  <div>
                      {!! $procedure->article !!}
                  </div>
                  <div class="mt-30 mb-0">
                    <h5 class="pull-left mt-10 mr-20 text-theme-colored">Share:</h5>
                    <ul class="styled-icons icon-circled m-0">
                      <li><a href="#" data-bg-color="#3A5795"><i class="fa fa-facebook text-white"></i></a></li>
                      <li><a href="#" data-bg-color="#55ACEE"><i class="fa fa-twitter text-white"></i></a></li>
                      <li><a href="#" data-bg-color="#A11312"><i class="fa fa-google-plus text-white"></i></a></li>
                    </ul>
                  </div>
                </div>
              </article>
              <div class="tagline p-0 pt-20 mt-5">
                <div class="row">
                  <div class="col-md-8">
                    <div class="tags">
                      <p class="mb-0"><i class="fa fa-tags text-theme-colored"></i> <span>Tags:</span>Eco, Water, Air, Environment</p>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="share text-right">
                      <p><i class="fa fa-share-alt text-theme-colored"></i> Share</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="comment-box">
                <div class="row">
                  <div class="col-sm-12">
                    <h5>Leave a Comment</h5>
                    <div class="row">
                      <form role="form" id="comment-form">
                        <div class="col-sm-6 pt-0 pb-0">
                          <div class="form-group">
                            <input type="text" class="form-control" required name="contact_name" id="contact_name" placeholder="Enter Name">
                          </div>
                          <div class="form-group">
                            <input type="text" required class="form-control" name="contact_email2" id="contact_email2" placeholder="Enter Email">
                          </div>
                          <div class="form-group">
                            <input type="text" placeholder="Enter Website" required class="form-control" name="subject">
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <textarea class="form-control" required name="contact_message2" id="contact_message2"  placeholder="Enter Message" rows="7"></textarea>
                          </div>
                          <div class="form-group">
                            <button type="submit" class="btn btn-dark btn-flat pull-right m-0" data-loading-text="Please wait...">Submit</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="sidebar sidebar-left mt-sm-30">
                @if ( count($procedures) > 0)
                <div class="widget">
                    <h5 class="widget-title line-bottom">Related Procedures</h5>
                    <div class="categories">
                      <ul class="list list-border angle-double-right">
                        @foreach($procedures as $p)
                            <li><a href="{{ route('sub-procedure', [$p->id,str_slug($p->name,'-')] ) }}">{{$p->name}}</a></li>
                        @endforeach
                      </ul>
                    </div>
                </div>
                @endif
              <div class="widget">
                <h5 class="widget-title line-bottom">Popular Treatments</h5>
                <div class="latest-posts">
                  @foreach($treatments as $t)
                  <article class="post media-post clearfix pb-0 mb-10">
                    <a class="post-thumb" href="#"><img src="{{ asset('backend/uploads/treatments/'.$t->picture) }}" width="75" height="75" alt=""></a>
                    <div class="post-right">
                      <h5 class="post-title mt-0"><a href="#">{{$t->name}}</a></h5>
                      <p>{!! str_limit($t->article,30) !!}</p>
                    </div>
                  </article>
                  @endforeach
                </div>
              </div>
              <div class="widget">
                <h5 class="widget-title line-bottom">Photos from Flickr</h5>
                <div id="flickr-feed" class="clearfix">
                  <!-- Flickr Link -->
                  <script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=9&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=52617155@N08">
                  </script>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- end main-content -->
@endsection
