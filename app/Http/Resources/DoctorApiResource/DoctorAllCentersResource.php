<?php

namespace App\Http\Resources\DoctorApiResource;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorAllCentersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return self::AllCentersList($this);
    }

    public function AllCentersList($c)
    {
        $data = [
            'id'            =>  $c->id,
            'name'          =>  $c->name,
            'address'       =>  $c->address,
            'is_approved'   =>  ($c->is_approved == 1) ? true : false,
        ];
        return $data;
    }
}
