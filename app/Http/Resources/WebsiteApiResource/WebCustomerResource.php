<?php

namespace App\Http\Resources\WebsiteApiResource;

use Illuminate\Http\Resources\Json\JsonResource;

class WebCustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return self::Customer($this);
    }

    public static function Customer($c){
        $data = [
            'id'                =>  $c->id,
            'name'              =>  $c->name,
            'address'           =>  $c->address,
            'age'               =>  $c->age,
            'blood_group_id'    =>  $c->blood_group_id,
            'email'             =>  $c->email,
            'gender'            =>  $c->gender,
            'height'            =>  $c->height,
            'marital_status'    =>  $c->marital_status,
            'phone'             =>  $c->phone,
            'weight'            =>  $c->weight,
        ];
        return $data;
    }
}
