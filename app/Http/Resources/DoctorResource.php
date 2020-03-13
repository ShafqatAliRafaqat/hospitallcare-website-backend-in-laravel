<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Treatment;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return self::Doctor($this);
    }

    public static function Doctor($c){
        $data = [
            'id'        	=> $c->id,
            'first_name'    => $c->name,
            'last_name'     => $c->last_name,            
            'email'      	=> $c->email,
            'picture'       =>  (isset($c->doctor_image))? 'http://test.hospitallcare.com/backend/uploads/doctor/'.$c->doctor_image->picture:null,
            'degree'     	=> $c->degree,
            'focus_area'    => (isset($c->focus_area))?$c->focus_area:null,
            'address'       => (isset($c->address))?$c->address:null,
            'notes'    		=> (isset($c->notes))?$c->notes:null,
            'status'        =>  ($c->is_active == 1)?"Active":"Not Active",
            'Treatment' 	=>TreatmentResource::collection($c->whenLoaded('treatments')),

        ];
        return $data;
    }
}
