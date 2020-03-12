<?php

namespace App\Http\Resources\DoctorApiResource;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorCenterDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return self::centers($this);
    }
    public static function centers($c)
    {
        $data = [
                    "center_id"             =>   $c->center_id,
                    "center_name"           =>   $c->center_name,
                    "center_address"        =>   $c->center_address,
                    "is_primary"            =>   ($c->is_primary == 1) ? true : false,
                    "appointment_duration"  =>   ($c->appointment_duration == null) ? false : true,
        ];
        return $data;
    }
}
