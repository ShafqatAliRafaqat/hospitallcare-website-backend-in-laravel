<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CenterTreatmenteApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return self::Center_Treatment($this);
    }

    public static function Center_Treatment($c){
        
        $data = [
            'id'            =>  $c->id,
            'center_id'     =>  $c->med_centers_id,
            'treatments_id' =>  $c->treatments_id,            
        ];

        return $data;
    }
}