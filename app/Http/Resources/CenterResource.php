<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CenterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return self::Center($this);
    }

    public static function Center($c){
        
        $data = [
            'id'            =>  $c->id,
            'center_name'   =>  $c->center_name,
            'picture'       =>  (isset($c->center_image))?'https://support.hospitallcare.com/backend/uploads/centers/'.$c->center_image->picture:null,
            'focus_area'    =>  (isset($c->focus_area))?$c->focus_area:null,
            'address'       =>  (isset($c->address))?$c->address:null,
            'notes'         =>  (isset($c->notes))?$c->notes:null,
            'status'         =>  ($c->is_active == 1)?"Active":"Not Active",
            'Treatment'     =>  TreatmentResource::collection($c->whenLoaded('center_treatment')),
        ];

        return $data;
    }
}
