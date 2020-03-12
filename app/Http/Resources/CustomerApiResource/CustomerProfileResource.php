<?php

namespace App\Http\Resources\CustomerApiResource;

use App\Organization;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CustomerProfileResource extends JsonResource
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
        if(isset($c->blood_group_id)){
            $blood_group = DB::table("blood_groups")->where('id',$c->blood_group_id)->select('name')->first();
            $blood_group_name = $blood_group->name;
        }else{
            $blood_group_name= '';
        }
        if(isset($c->organization_id)){
            $organization = Organization::where('id',$c->organization_id)->select('name')->first();
            $organization_name = $organization->name;
        }else{
            $organization_name= '';
        }
        $data = [
            'id'                =>  $c->id,
            'name'              =>  $c->name,
            'address'           =>  isset($c->address)?$c->address:'',
            'age'               =>  isset($c->age)?$c->age:null,
            'date_of_birth'     =>  isset($c->dob)?$c->dob:null,
            'organization_name' =>  $organization_name,
            'organization_id'   =>  isset($c->organization_id)?$c->organization_id:null,
            'employee_code'     =>  isset($c->employee_code)?$c->employee_code:null,
            'org_verified'      =>  $c->org_verified,
            'blood_group_id'    =>  $blood_group_name,
            'email'             =>  isset($c->email)?$c->email:'',
            'gender'            =>  ($c->gender == 0)?'Male':'Female',
            'height'            =>  isset($c->height)?$c->height:null,
            'marital_status'    =>  ($c->marital_status == 0)?'Unmarried':'Married',
            'phone'             =>  $c->phone,
            'picture'           =>  $c->picture,
            'weight'            =>  isset($c->weight)?$c->weight:null,
            'picture'           =>  isset($c->picture)? $c->picture :'',
        ];
        return $data;
    }
}
