<?php

namespace App\Http\Resources\WebsiteApiResource;

use Illuminate\Http\Resources\Json\JsonResource;

class WebDoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return self::Doctor($this);
    }

    public static function Doctor($d){
        $experience             =   YearsDiff($d->experience);
        if (isset($d->doctor_schedules[0]->lat)) {
            foreach ($d->doctor_schedules as $ds) {
                if ( $ds->is_primary == 1 ) {
                    $d->lat = $ds->lat;
                    $d->lng = $ds->lng;
                    break;
                } else {
                    $d->lat = $ds->lat;
                    $d->lng = $ds->lng;
                }
            }
        }
        $map                    =   "https://www.google.com/maps?saddr&daddr=$d->lat,$d->lng";
        $doctor_certification   =   doctorCertification($d->id);
        $doctor_qualification   =   doctorQualification($d->id);
        $doctor_image           =   doctorImage($d->id);
            $data = [
            'id'                =>  $d->id,
            'first_name'        =>  $d->name,
            'last_name'         =>  $d->last_name,
            'phone'             =>  $d->phone,
            'focus_area'        =>  $d->focus_area,
            'city_name'         =>  $d->city_name,
            'about'             =>  substr(str_replace('&nbsp;', '', strip_tags($d->about)), 0,50),
            'experience'        =>  isset($experience)? $experience:"",
            'gender'            =>  ($d->gender == 1 ) ? "Male":"Female",
            'partnership'       =>  $d->is_partner,
            'email'             =>  $d->email,
            'address'           =>  $d->address,
            'lat'               =>  $d->lat,
            'lng'               =>  $d->lng,
            'map'               =>  $map,
            'centers'           =>  (isset($d->doctor_schedules)) ? $d->doctor_schedules:"",
            'treatments'        =>  (isset($d->treatments)) ? WebTreatmentResource::collection($d->treatments->take(9)) : "",
            'picture'           => (isset($doctor_image))? 'http://test.hospitallcare.com/backend/uploads/doctors/'.$doctor_image->picture:null,
            'doctor_qualification'   =>  (isset($doctor_qualification)) ? $doctor_qualification :"",
            'doctor_certification'   =>  (isset($doctor_certification)) ? $doctor_certification:"",

        ];

        return $data;
    }
}
