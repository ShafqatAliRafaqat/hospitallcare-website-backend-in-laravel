<?php

namespace App\Http\Resources\WebsiteApiResource;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return self::fetchDoctor($this);
    }

    public static function fetchDoctor($d)
    {
        $map        = "https://www.google.com/maps?saddr&daddr=$d->lat,$d->lng";
        $experience =  YearsDiff($d->experience);
        $data = [
            'id'                =>  $d->id,
            'first_name'        =>  $d->name,
            'last_name'         =>  $d->last_name,
            // 'phone'             =>  $d->phone,
            'focus_area'        =>  $d->focus_area,
            'about'             =>  strip_tags($d->about),
            'experience'        =>  $experience,
            'speciality'        =>  $d->speciality,
            'city_name'         =>  $d->city_name,
            'email'             =>  $d->email,
            'partnership'       =>  $d->is_partner,
            'gender'            =>  ($d->gender == 1 ) ? "Male":"Female",
            'address'           =>  $d->address,
            'lat'               =>  $d->lat,
            'map'               =>  $map,
            'lng'               =>  $d->lng,
            // 'treatments'        =>  (isset($d->treatments)) ? WebTreatmentResource::collection($d->treatments->take(9)) : "",
            // // 'treatments'        =>  isset($d->treatments)? $d->treatments :"",
            'picture'           => (isset($d->doctor_image))? 'https://support.hospitallcare.com/backend/uploads/doctors/'.$d->doctor_image->picture:null,
        ];

        return $data;
    }
}
