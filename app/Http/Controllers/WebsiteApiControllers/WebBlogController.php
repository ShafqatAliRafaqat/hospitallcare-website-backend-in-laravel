<?php

namespace App\Http\Controllers\WebsiteApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WebsiteApiResource\DoctorProfileResource;
use App\Http\Resources\WebsiteApiResource\WebBlogResource;
use App\Http\Resources\WebsiteApiResource\WebDoctorResource;
use App\Models\Admin\Doctor;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\TempNotes;
use Carbon\Carbon;

class WebBlogController extends Controller
{
    //get list of all blogs
    public function AllBlogs(){
        $blogs = DB::table('blogs as b')
                    ->join('blog_images as bi','bi.blog_id','b.id')
                    ->orderByDesc('position')
                    ->where('b.is_active',1)
                    ->select('b.id','b.title','b.description','b.meta_title','b.meta_description','b.url','bi.picture','b.updated_at','b.updated_by')
                    ->paginate(6);
        $recent_blogs = DB::table('blogs as b')
                    ->join('blog_images as bi','bi.blog_id','b.id')
                    ->orderByDesc('updated_at')
                    ->where('b.is_active',1)
                    ->select('b.id','b.title','bi.picture','b.updated_at')
                    ->take(6)
                    ->get();
        $blog_categories = DB::table('blog_category as bc')
                    ->join('blogs as b','bc.id','b.category_id')
                    ->where('b.is_active',1)
                    ->select('bc.name','b.category_id',DB::raw('COUNT(b.category_id) as total_blogs'))
                    ->groupBy('bc.id')
                    ->get();
        return WebBlogResource::collection($blogs)->additional(['meta'=>[
                    'recent_blogs'      => $recent_blogs,
                    'blog_categories'   => $blog_categories,
            ]]);      
    }
    // get single blog detail 
    public function blog($id){
        $blog   = DB::table('blogs as b')
                    ->join('blog_images as bi','bi.blog_id','b.id')
                    ->where('b.id',$id)
                    ->select('b.id','b.title','b.description','b.meta_title','b.meta_description','b.url','bi.picture','b.updated_at','b.updated_by')
                    ->first();
        $recent_blogs = DB::table('blogs as b')
                    ->join('blog_images as bi','bi.blog_id','b.id')
                    ->orderByDesc('updated_at')
                    ->where('b.is_active',1)
                    ->select('b.id','b.title','bi.picture','b.updated_at')
                    ->take(6)
                    ->get();
        $blog_categories = DB::table('blog_category as bc')
                    ->join('blogs as b','bc.id','b.category_id')
                    ->where('b.is_active',1)
                    ->select('bc.name','b.category_id',DB::raw('COUNT(b.category_id) as total_blogs'))
                    ->groupBy('bc.id')
                    ->get();
        return WebBlogResource::make($blog)->additional(['meta'=>[
                        'recent_blogs'      => $recent_blogs,
                        'blog_categories'   => $blog_categories,
                ]]); 
    }
    //get list of blogs accouding to blog category 
    public function BlogCategory($id){
        $category   = DB::table('blog_category')->where('id',$id)->select('id','name','description')->first();
        $blogs      = DB::table('blogs as b')
                    ->join('blog_images as bi','bi.blog_id','b.id')
                    ->where('b.category_id',$id)
                    ->orderByDesc('position')
                    ->select('b.id','b.title','b.description','b.meta_title','b.meta_description','b.url','bi.picture','b.updated_at','b.updated_by')
                    ->paginate(6);
        $recent_blogs = DB::table('blogs as b')
                    ->join('blog_images as bi','bi.blog_id','b.id')
                    ->orderByDesc('updated_at')
                    ->where('b.is_active',1)
                    ->select('b.id','b.title','bi.picture','b.updated_at')
                    ->take(6)
                    ->get();
        $blog_categories = DB::table('blog_category as bc')
                    ->join('blogs as b','bc.id','b.category_id')
                    ->where('b.is_active',1)
                    ->select('bc.name','b.category_id',DB::raw('COUNT(b.category_id) as total_blogs'))
                    ->groupBy('bc.id')
                    ->get();
        return WebBlogResource::collection($blogs)->additional(['meta'=>[
                        'recent_blogs'      => $recent_blogs,
                        'blog_categories'   => $blog_categories,
                        'category'          => $category,
                ]]); 
    }
}
