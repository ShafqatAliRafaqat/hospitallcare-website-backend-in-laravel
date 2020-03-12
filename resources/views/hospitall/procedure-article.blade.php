@extends('hospitall.master')
@section('content')
  <!-- Start main-content -->
  <div class="main-content">
    <!-- Section: inner-header -->
    <section class="inner-header">
      <img src="{{ asset('inc/images/banner/'. str_slug($treatment->name,'-').'.jpg' ) }}" class="img-responsive">
     <div class="img-overlay">
      <button class="btn btn-md btn-book">Book an Appointment</button>
    </div>
    </section>

    <!-- Section: Blog -->
    <section>
      <div class="container mt-30 mb-30 pt-30 pb-30">
        <div class="row">
          <div class="col-md-9 pull-right flip sm-pull-none">
            <div class="blog-posts single-post">
              <article class="post clearfix mb-0">
               <!--  <div class="entry-header">
                  <div class="post-thumb thumb"> <img src="{{ asset('backend/uploads/treatments/'. $treatment->picture) }}" alt="" class="img-responsive img-fullwidth"> </div>
                </div> -->
                <div class="entry-content">
                  <div class="entry-meta media no-bg no-border mt-15 pb-20">
                    <div class="entry-date media-left text-center flip bg-theme-colored pt-5 pr-15 pb-5 pl-15">
                      <ul>
                        <li class="font-16 text-white font-weight-600">{{ date('d',strtotime($treatment->created_at)) }}</li>
                        <li class="font-12 text-white text-uppercase">{{ date('M',strtotime($treatment->created_at)) }}</li>
                      </ul>
                    </div>
                    <div class="media-body pl-15">
                      <div class="event-content pull-left flip">
                        <h1 class="entry-title text-white text-uppercase m-0"><a href="#">{{$treatment->name}}</a></h1>
                       <!--  <span class="mb-10 text-gray-darkgray mr-10 font-13"><i class="fa fa-commenting-o mr-5 text-theme-colored"></i> 214 Comments</span>
                        <span class="mb-10 text-gray-darkgray mr-10 font-13"><i class="fa fa-heart-o mr-5 text-theme-colored"></i> 895 Likes</span> -->
                      </div>
                    </div>
                  </div>
                  <div>
                      {!! $treatment->article !!}
                  </div>
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
                      <p class="mb-0"><i class="fa fa-tags text-theme-colored"></i> <span>Tags:</span>{{$treatment->url}}</p>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="share text-right">
                      <!-- <p><i class="fa fa-share-alt text-theme-colored"></i> Share</p> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="sidebar sidebar-left mt-sm-30">
                  <div class="widget">
                    <h5 class="widget-title line-bottom">Procedures</h5>
                    <div class="categories">
                      <ul class="list list-border angle-double-right">
                        @foreach($treatments as $t)
                            <li><a href="{{ route('procedure-detail', [$t->id,str_slug($t->name,'-')] ) }}">{{$t->name}}</a></li>
                        @endforeach
                      </ul>
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
@section('scripts')
<script>
  var popupMeta = {
    width: 400,
    height: 400
  }
  $(document).on('click', '.social-share', function(event){
      event.preventDefault();
      var vPosition = Math.floor(($(window).width() - popupMeta.width) / 2),
          hPosition = Math.floor(($(window).height() - popupMeta.height) / 2);

      var url = $(this).attr('href');
      var popup = window.open(url, 'Social Share',
          'width='+popupMeta.width+',height='+popupMeta.height+
          ',left='+vPosition+',top='+hPosition+
          ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');

      if (popup) {
          popup.focus();
          return false;
      }
  });
</script>
@endsection
