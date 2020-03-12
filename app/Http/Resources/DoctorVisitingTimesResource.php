<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorVisitingTimesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return self::Doctor_Visiting_Times($this);
    }

    public static function Doctor_Visiting_Times($c){
        $time_explode   =   explode(",", $c->time_from);
        foreach ($time_explode as $tFrom) {
            $time       =  Carbon::parse($tFrom);
            $time_from[]  =  $time->format('h:i A');
        }
        $time_from      =   implode(",", $time_from);

        $time_explode   =   explode(",", $c->time_to);
        foreach ($time_explode as $tTo) {
            $time       =  Carbon::parse($tTo);
            $time_to[]  =  $time->format('h:i A');;
        }
        $time_to      =   implode(",", $time_to);
        $data = [
            'is_primary'        =>  $c->is_primary,
            'center_id'         =>  $c->center_id,
            'schedule_id'       =>  $c->schedule_id,
            'time_from'         =>  $time_from,
            'time_to'           =>  $time_to,
            'day_from'          =>  $c->day_from,
            'day_to'            =>  $c->day_to,
        ];
        return $data;
    }
}
