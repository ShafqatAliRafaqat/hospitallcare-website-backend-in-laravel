<?php

namespace App\Http\Resources\CustomerApiResource;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerDoctorResource extends JsonResource
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
        $doctor_image           =   doctorImage($d->id);
        
        $today_date = date('d-m-Y');
        if(isset($d->doctor_schedules)){
            $schedules = $d->doctor_schedules;
            foreach($schedules as $s){
                $time_from  = isset($s->time_from)?$s->time_from:0;
                $time_to    = isset($s->time_to)?$s->time_to:0;
                $is_primary1= $s->is_primary;
                
                $day_from   = isset($s->day_from)?$s->day_from:'Monday';
                $day_form   = explode(',',$day_from);

                $day_to     = isset($s->day_to)?$s->day_to:"Sunday";
                $day_to     = explode(',',$day_to);
                $i = 0;
                if($is_primary1){
                    $is_primary = 1;
                    foreach($day_form as $df){
                        $df = getDay($df)->format('d-m-Y');     
                        $df = strtotime($df);     
                        $dt = getDay($day_to[$i])->format('d-m-Y');
                        $dt = strtotime($dt);
                        $today_date = strtotime($today_date);
                        
                        if($df <= $today_date && $today_date <= $dt) {
                            $available = 1;
                        }
                        $i++;     
                    }
                    $center_name  = centerName($s->center_id);
                }else{
                    foreach($day_form as $df){
                        $df = getDay($df)->format('d-m-Y');     
                        $df = strtotime($df);     
                        $dt = getDay($day_to[$i])->format('d-m-Y');
                        $dt = strtotime($dt);
                        $today_date = strtotime($today_date);
                        
                        if($df <= $today_date && $dt >= $today_date) {
                            $available = 1;
                        }
                        $i++;     
                    }
                    $center_name = centerName($s->center_id);
                }
                
               
            }
        }else{
            $center_name =[];
        }
        $gender         =   ($d->gender == 1 ) ? 'Male.png':'Female.png';
            $data = [
            'id'                =>  $d->id,
            'first_name'        =>  $d->name,
            'last_name'         =>  $d->last_name,
            'focus_area'        =>  (isset($d->focus_area)) ? $d->focus_area : '',
            'city_name'         =>  (isset($d->city_name)) ? $d->city_name : '',
            'experience'        =>  $experience,
            'partnership'        =>  $d->is_partner,
            'gender'            =>  ($d->gender == 1 ) ? "Male":"Female",
            'available'         =>  (isset($available)) ? $available : 0,
            'is_primary'         =>  (isset($is_primary)) ? $is_primary : 0,
            'center_name'       =>  (isset($center_name)) ? $center_name : '',
            // 'centers'           =>  (isset($d->doctor_schedules)) ? $d->doctor_schedules:"",
            'picture'           => (isset($doctor_image))? 'http://test.hospitallcare.com/backend/uploads/doctors/'.$doctor_image->picture:('http://test.hospitallcare.com/backend/web_imgs/'.$gender),
        ];

        return $data;
    }
}
