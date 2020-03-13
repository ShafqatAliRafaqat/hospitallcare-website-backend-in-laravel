<?php

namespace App\Http\Resources\WebsiteApiResource;

use Illuminate\Http\Resources\Json\JsonResource;

class WebTreatmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return self::Treatment($this);
    }

    public static function Treatment($t){
        if(isset($t->treatment_image)){
            if(count($t->treatment_image)>0){
                $picture = 'http://test.hospitallcare.com/backend/uploads/treatments/'.$t->treatment_image[0]->picture;
            }else{
                $picture = null;
            }
        }else{
            $picture = null;
        }
        if(isset($t->article)){
            $article    =   str_replace('&nbsp;', ' ', strip_tags($t->article));
            $article    =   str_replace('&amp;', 'and', strip_tags($article));
            $article    =   str_replace('&rsquo;', "'", strip_tags($article));
        }
        $data = [
            'id'                =>  $t->id,
            'name'              =>  $t->name,
            'focus_area'        =>  (isset($t->focus_area))?$t->focus_area:"",
            'about'             =>  substr(str_replace('&nbsp;', '', strip_tags($t->article)), 0,50),
            'article'           =>  isset($article)? strip_tags($article):'',
            'article_heading'   =>  isset($t->article_heading)? $t->article_heading:'',
            'cost'              =>  (isset($t->pivot))? $t->pivot->cost : null,
            'picture'           =>  $picture,
        ];

        return $data;
    }
}
