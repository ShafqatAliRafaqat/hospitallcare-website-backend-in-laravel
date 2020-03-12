<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorTreatmenteApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return self::Doctor_Treatment($this);
    }

    public static function Doctor_Treatment($c){
        
        $data = [
            'id'            =>  $c->id,
            'treatment_id'  =>  $c->treatment_id,
            'doctor_id'     =>  $c->doctor_id,            
            'schedule_id'   =>  $c->schedule_id,            
        ];

        return $data;
    }
}