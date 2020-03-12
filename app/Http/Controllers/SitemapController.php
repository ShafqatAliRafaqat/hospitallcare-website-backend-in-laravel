<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    public function treatment_sitemap()
    {
        $sitemap = App::make("sitemap");
        $treatments = DB::table('treatments')->orderBy('created_at', 'desc')->get();
        foreach ($treatments as $treatment)
        {
            $slug = URL::to('/procedure/'.$treatment->id.'/'.str_slug($treatment->name,'-'));
            $images_url = URL('/').'/backend/uploads/treatments/'.$treatment->picture;
            $images = array();
            $images[] = array(
                'url' => $images_url,
                'title' => $treatment->name,
                'caption' => $treatment->name
            );
            $sitemap->add($slug, $treatment->updated_at, 0.1, 'monthly',$images);
        }
        // generate your sitemap (format, filename)
        $sitemap->store('xml', 'mysitemap');
    }

    public function blog_sitemap()
    {
        $sitemap_tags = App::make("sitemap");
        // add items
        $articles = DB::table('articles')->get();
        foreach ($articles as $article)
        {
            $slug = URL::to('/blogs/'.$article->id.'/'.str_slug($article->title,'-'));
            $sitemap_tags->add($slug, $article->updated_at, '0.5', 'weekly');
        }
    }

    public function indexed_sitemap()
    {
        // create sitemap
    $sitemap_posts = App::make("sitemap");
    // add items
    $treatments = DB::table('treatments')->orderBy('created_at', 'desc')->get();
    foreach ($treatments as $treatment)
    {
        $slug = URL::to('/procedure/'.$treatment->id.'/'.str_slug($treatment->name,'-'));

        $sitemap_posts->add($slug, $treatment->updated_at, '0.1', 'weekly');
    }
    // create file sitemap-procedure.xml in your public folder (format, filename)
    $sitemap_posts->store('xml','sitemap-procedure');
    // create sitemap
    $sitemap_tags = App::make("sitemap");
    // add items
    $articles = DB::table('articles')->get();
    foreach ($articles as $article)
    {
        $slug = URL::to('/blogs/'.$article->id.'/'.str_slug($article->title,'-'));
        $sitemap_tags->add($slug, $article->updated_at, '0.5', 'weekly');
    }
    // create file sitemap-blogs.xml in your public folder (format, filename)
    $sitemap_tags->store('xml','sitemap-blogs');
    // create sitemap index
    $sitemap = App::make ("sitemap");
    // add sitemaps (loc, lastmod (optional))
    $sitemap->addSitemap(URL::to('sitemap-procedure.xml'));
    $sitemap->addSitemap(URL::to('sitemap-blogs.xml'));
    // create file sitemap.xml in your public folder (format, filename)
    $sitemap->store('sitemapindex','sitemap');
    }
}
