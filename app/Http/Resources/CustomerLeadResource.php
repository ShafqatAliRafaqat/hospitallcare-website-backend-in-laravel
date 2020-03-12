<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Admin\Status;
class CustomerLeadResource extends JsonResource
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
        $customer_status = Status::where('id', $c->status_id)->first();

        $data = [
            'id'                => $c->id,
            'name'              => $c->name,
            'status'           => (isset($c->status_id))?$customer_status->name:null,
        ];
        return $data;
    }
}
