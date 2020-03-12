<?php

namespace App\Http\Resources\DoctorApiResource;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return self::notification($this);
    }
    public static function notification($n)
    {
    $created = (isset($n->created_at)?$n->created_at:'');
    $date           = Carbon::parse($created);                             // Appointment date
    $date           = $date->diffForHumans();
        $data = [
            'id'        => (isset($n->id)?$n->id:''),
            'title'     => (isset($n->title)?$n->title:''),
            'body'      => (isset($n->body)?$n->body:''),
            'created_at'=> $date
        ];
        return $data;
    }
}
