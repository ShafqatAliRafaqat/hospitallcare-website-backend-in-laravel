<?php

namespace App\Http\Resources\DoctorApiResource;

use App\Models\Admin\BloodGroup;
use App\Models\Admin\CustomerDoctorNotes;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class DoctorCustomerResource extends JsonResource
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
        $appointment_date = new DateTime($c->appointment_date);
        $image          =   DB::table('customer_images')->where('customer_id',$c->id)->first();
        if($c->blood_group_id != null){
            $blood_group = BloodGroup::where('id',$c->blood_group_id)->first();
        }
        $doctor_notes = CustomerDoctorNotes::where('customer_id',$c->id)->select('notes')->first();
        $data = [
            'id'        	=> $c->id,
            'ref'        	=> $c->ref,
            'name'          => $c->name,
            'email'         => (isset($c->email))?$c->email:'',
            // 'phone'      	=> (isset($c->phone))?$c->phone:'',
            'picture'       =>  ((isset($image->picture))? 'http://test.hospitallcare.com/backend/uploads/customers/'.$image->picture:(($c->gender == 0)?'http://test.hospitallcare.com/backend/web_imgs/app-male.png':'http://test.hospitallcare.com/backend/web_imgs/app-female.png')),
            'address'     	=> (isset($c->address))?$c->address:'',
            'gender'        => ($c->gender == 0)?'Male':'Female',
            'marital_status'=> ($c->marital_status == 0)?'Unmarried':'Married',
            'blood_group'   => ($c->blood_group_id != null)?$blood_group->name:'',
            'age'           => (isset($c->age))?$c->age:'',
            'height'        => (isset($c->height))?$c->height:'',
            'weight'        => (isset($c->weight))?$c->weight:'',
            'doctor_notes'  => (isset($doctor_notes))?$doctor_notes->notes:'',
            'customer_procedure'=> (isset($c->customer_procedures_id))?$c->customer_procedures_id:'',
            'date'          => (isset($appointment_date))?$appointment_date->format('m-d-Y'):'',
            'time'          => (isset($appointment_date))?$appointment_date->format('h:i A'):'',
        ];
        return $data;
    }
}
