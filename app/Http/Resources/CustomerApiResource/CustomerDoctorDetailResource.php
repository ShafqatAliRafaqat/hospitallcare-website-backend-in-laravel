<?php

namespace App\Http\Resources\CustomerApiResource;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerDoctorDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return self::Doctor($this);
    }

    public static function Doctor($d){
        $experience             =   YearsDiff($d->experience);
        $doctor_image           =   doctorImage($d->id);
        $gender         =   ($d->gender == 1 ) ? 'Male.png':'Female.png';
            $data = [
            'id'                =>  $d->id,
            'first_name'        =>  $d->name,
            'last_name'         =>  $d->last_name,
            'focus_area'        =>  $d->focus_area,
            'city_name'         =>  $d->city_name,
            'experience'        =>  $experience,
            'partnership'        =>  $d->is_partner,
            'gender'            =>  ($d->gender == 1 ) ? "Male":"Female",
            // 'centers'           =>  (isset($d->doctor_schedules)) ? $d->doctor_schedules:"",
            'picture'           => (isset($doctor_image))? 'https://support.hospitallcare.com/backend/uploads/doctors/'.$doctor_image->picture:('https://support.hospitallcare.com/backend/web_imgs/'.$gender),
        ];

        return $data;
    }
}
