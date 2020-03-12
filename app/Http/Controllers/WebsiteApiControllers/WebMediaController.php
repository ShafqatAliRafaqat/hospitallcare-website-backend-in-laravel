<?php

namespace App\Http\Controllers\WebsiteApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WebsiteApiResource\WebMediaResource;
use Illuminate\Support\Facades\DB;

class WebMediaController extends Controller
{
    public function AllVlogs(){
        $vlogs = DB::table('vlogs')->select('id','title','description','link','meta_title','meta_description','url','updated_at','updated_by')->where('is_active',1)->orderByDesc('position')->paginate(6);
        return WebMediaResource::collection($vlogs);
    }
    
    public function Vlog($id){
        $vlog = DB::table('vlogs')->where('id',$id)->select('id','title','description','link','meta_title','meta_description','url','updated_at','updated_by')->first();
        $recent_vlogs = DB::table('vlogs')->where('id','!=',$id)->select('id','title','link','updated_at')->orderbyDesc('updated_at')->where('is_active',1)->get()->take(6);
        return WebMediaResource::make($vlog)->additional(['meta'=>[
            'recent_vlogs'      => $recent_vlogs,
    ]]); 
    }

    public function AllVideos(){
        $videos = DB::table('media')->select('id','title','description','link','meta_title','meta_description','url','updated_at','updated_by')->where('is_active',1)->orderByDesc('position')->paginate(6);
        return WebMediaResource::collection($videos);
    }
    
    public function Video($id){
        $video = DB::table('media')->where('id',$id)->select('id','title','description','link','meta_title','meta_description','url','updated_at','updated_by')->first();
        $recent_videos = DB::table('media')->where('id','!=',$id)->select('id','title','link','updated_at')->orderbyDesc('updated_at')->where('is_active',1)->get()->take(6);
        return WebMediaResource::make($video)->additional(['meta'=>[
            'recent_videos'      => $recent_videos,
    ]]);
    }
}
