<?php

namespace App\Http\Resources\WebsiteApiResource;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class WebMediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return self::Media($this);
    }

    public static function Media($m){
        $updated_by = DB::table('users')->where('id',$m->updated_by)->select('name')->first();
        $data = [
            'id'              =>  $m->id,
            'title'           =>  $m->title,
            'description'     =>  $m->description,
            'link'            =>  $m->link,
            'meta_title'      =>  $m->meta_title,
            'meta_description'=>  $m->meta_description,
            'meta_tags'       => null,
            'updated_at'      =>  $m->updated_at,
            'updated_by'      =>  isset($updated_by) ? $updated_by->name :'',
          ];

        return $data;
    }
}
