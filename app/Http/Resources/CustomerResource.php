<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request) {
        return self::Customer($this);
    }

    public static function Customer($c){

        $data = [
            'id'                => $c->id,
            'name'              => $c->name,
            'email'             => $c->email,
            'phone'             => $c->phone,
            'address'           => (isset($c->address))?$c->address:null,
            'gender'            => (isset($c->gender))?$c->gender:null,
            'marital_status'    => (isset($c->marital_status))?$c->marital_status:null,
            'age'               => (isset($c->age))?$c->age:null,
            'weight'            => (isset($c->weight))?$c->weight:null,
            'height'            => (isset($c->height))?$c->height:null,
            'treatment'         => (isset($c->treatment))?$c->treatment:null,
            'notes'             => (isset($c->notes))?$c->notes:null,
            // 'created_at'        => (isset($c->created_at))?$c->created_at->diffForHumans():null,
            // 'updated_at'        => (isset($c->updated_at))?$c->updated_at->diffForHumans():null,
        ];
        return $data;
    }
}
