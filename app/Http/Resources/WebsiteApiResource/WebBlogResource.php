<?php

namespace App\Http\Resources\WebsiteApiResource;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class WebBlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return self::Blog($this);
    }

    public static function Blog($b){
        $updated_by = DB::table('users')->where('id',$b->updated_by)->select('name')->first();
        $data = [
            'id'                =>  $b->id,
            'title'             =>  $b->title,
            'description'       =>  $b->description,
            'meta_title'        =>  $b->meta_title,
            'meta_description'  =>  $b->meta_description,
            'meta_tags'         => null,
            'picture'           =>  $b->picture,
            'updated_at'        =>  $b->updated_at,
            'updated_by'        =>  isset($updated_by) ? $updated_by->name :'',
        ];

        return $data;
    }
}
