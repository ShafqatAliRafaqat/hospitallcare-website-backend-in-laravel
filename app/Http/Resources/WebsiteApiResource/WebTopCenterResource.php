<?php

namespace App\Http\Resources\WebsiteApiResource;

use Illuminate\Http\Resources\Json\JsonResource;

class WebTopCenterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return self::Center($this);
    }

    public static function Center($c){
        $map        = "https://www.google.com/maps?saddr&daddr=$c->lat,$c->lng";

        $data = [
            'id'                =>  $c->id,
            'name'              =>  $c->center_name,
            'phone'             =>  $c->phone,
            'focus_area'        =>  $c->focus_area,
            'about'             =>  str_replace('&nbsp;', '', strip_tags($c->additional_details)) ,
            'address'           =>  $c->address,
            'lat'               =>  $c->lat,
            'lng'               =>  $c->lng,
            'assistant_name'    =>  $c->assistant_name,
            'assistant_phone'   =>  $c->assistant_phone,
            'map'               =>  $map,
            'picture'           => (isset($c->center_image))? 'https://support.hospitallcare.com/backend/uploads/centers/'.$c->center_image->picture:null,
        ];

        return $data;
    }
}
