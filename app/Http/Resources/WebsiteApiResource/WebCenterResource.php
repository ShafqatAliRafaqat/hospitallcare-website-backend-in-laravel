<?php

namespace App\Http\Resources\WebsiteApiResource;

use Illuminate\Http\Resources\Json\JsonResource;

class WebCenterResource extends JsonResource
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
            'city_name'           =>  $c->city_name,
            'lat'               =>  $c->lat,
            'lng'               =>  $c->lng,
            'assistant_name'    =>  $c->assistant_name,
            'assistant_phone'   =>  $c->assistant_phone,
            'map'               =>  $map,
            // 'doctors'           =>  ($c->doctor) ? WebDoctorResource::collection($c->whenLoaded('doctor')) : "",
            'treatments'        =>  ($c->center_treatment) ? WebTreatmentResource::collection($c->whenLoaded('center_treatment')) : "",
            'picture'           => (isset($c->center_image))? 'http://test.hospitallcare.com/backend/uploads/centers/'.$c->center_image->picture:null,
            'count_doctors'     =>  (isset($c->count_doctor))? $c->count_doctor : null,
        ];

        return $data;
    }
}
