<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CenterDoctorApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return self::Center_Doctor($this);
    }

    public static function Center_Doctor($c){
        
        $data = [
            'id'            =>  $c->id,
            'center_id'     =>  $c->center_id,
            'doctor_id'     =>  $c->doctor_id,            
        ];

        return $data;
    }
}