    <!-- Section: blog -->
    <section id="blog">
      <div class="container">
        <div class="section-title text-center">
          <div class="row">
            <div class="col-md-8 col-md-offset-2">
              <h2 class="text-uppercase mt-0 line-height-1">Blogs</h2>
              <div class="title-icon">
                <img class="mb-10" src="{{ asset('inc/images/title-icon.png') }}" alt="">
              </div>
            </div>
          </div>
        </div>
        <div class="section-content">
          <div class="row">
            <div class="col-md-12">
              <div class="owl-carousel-3col">
                @foreach($articles as $article)
                <div class="item">
                  <article class="post clearfix bg-lighter">
                    <div class="entry-header">
                      <div class="post-thumb thumb">
                        <img src="{{ asset('backend/uploads/articles/'.'540x370-'.$article->picture) }}" alt="" class="img-responsive img-fullwidth" width="370" height="278">
                      </div>
                      
                    </div>
                    <div class="entry-content p-15 pt-10 pb-10">
                      <div class="entry-meta media no-bg no-border mt-0 mb-10">
                        <div class="media-body pl-0">
                          <div class="event-content pull-left flip">
                            <h4 class="entry-title text-white text-uppercase font-weight-600 m-0 mt-5"><a href="{{ route('article',[$article->id,str_slug($article->title, '-')]) }}">{{$article->title}}</a></h4>
                            <!-- <span class="mb-10 text-gray-darkgray mr-10 font-13"><i class="fa fa-commenting-o mr-5 text-theme-colored"></i> 214 Comments</span>
                            <span class="mb-10 text-gray-darkgray mr-10 font-13"><i class="fa fa-heart-o mr-5 text-theme-colored"></i> 895 Likes</span> -->
                          </div>
                        </div>
                      </div>
                      <p class="mt-5">{!! str_limit($article->article,'50') !!}<a class="text-theme-color-2 font-12 ml-5"
                        href="{{ route('article',[$article->id,str_slug($article->title, '-')]) }}"> View Details</a></p>
                    </div>
                  </article>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
