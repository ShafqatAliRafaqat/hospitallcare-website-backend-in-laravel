<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return self::Treatment($this);
    }

    public static function Treatment($c){
        $images = (isset($c->treatment_image))?$c->treatment_image:null;
        $picture = null; 
        if ($images) {

            $i = 1;
            foreach ($images as $image) {
                $picture['picture'.$i] ='http://test.hospitallcare.com/backend/uploads/treatments/'.$image->picture;
            $i++;
            }
         }

         $data = [
            'id'            =>  $c->id,
            'name'          =>  $c->name,
            'message'       =>  (isset($c->message))?$c->message:null,
            'link_description'=>  (isset($c->link_description))?$c->link_description:null,
            'headline'      =>  (isset($c->headline))?$c->headline:null,
            'payload_en'      =>  (isset($c->payload_en))?$c->payload_en:null,
            'payload_ur'      =>  (isset($c->payload_ur))?$c->payload_ur:null,
            'landing_page_url'      =>  (isset($c->landing_page_url))?$c->landing_page_url:null,
            'status'         =>  ($c->is_active == 1)?"Active":"Not Active",
            'images'       =>  $picture,     
               
        ];
        return $data;
    }
}
