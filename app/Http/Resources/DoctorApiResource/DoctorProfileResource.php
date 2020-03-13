<?php

namespace App\Http\Resources\DoctorApiResource;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\Admin\DoctorQualification;
use Illuminate\Support\Facades\DB;

class DoctorProfileResource extends JsonResource
{

    public function toArray($request)
    {
        return self::DoctorProfile($this);
    }
    public static function DoctorProfile($d){
        $experience =YearsDiff($d->experience);
        $education = DoctorQualification::where('doctor_id', $d->id)->select('id', 'country', 'university', 'degree', 'graduation_year')->get();
        $cp_patients    =   DB::table('doctors as d')
                        ->join('customer_procedures as cp','cp.doctor_id','d.id')
                        ->join('customers as c','c.id','cp.customer_id')
                        ->where(['cp.doctor_id' => $d->id])
                        ->select('c.id')
                        ->groupBy('c.id')
                        ->get()->toArray();
        $cth_patients   =   DB::table('doctors as d')
                        ->join('customer_treatment_history as cth','cth.doctor_id','d.id')
                        ->join('customers as c','c.id','cth.customer_id')
                        ->Where(['cth.doctor_id' => $d->id])
                        ->select('c.id')
                        ->groupBy('c.id')
                        ->get()->toArray();
        $patients  = Array_merge($cp_patients, $cth_patients);
        $patients  = count($patients);
        $cp        = DB::table('customers as c')
                        ->JOIN('customer_procedures as cp', 'cp.customer_id', 'c.id')
                        ->WHERE('cp.doctor_id', $d->id)
                        ->WHERE('cp.status', '!=', 1)
                        ->select('cp.id')
                        ->get()->toArray();
        $cth        = DB::table('customers as c')
                        ->JOIN('customer_treatment_history as cp', 'cp.customer_id', 'c.id')
                        ->WHERE('cp.doctor_id', $d->id)
                        ->select('cp.id')
                        ->get()->toArray();
        $appointments = Array_merge($cp, $cth);
        $appointments = count($appointments);

        $data = [
            'id'                => $d->id,
            'first_name'        => $d->name,
            'last_name'         => (isset($d->last_name))?$d->last_name:'',
            'appointments'      => (isset($appointments))?$appointments:'',
            'patients'          => (isset($patients))?$patients:'',
            'email'             => (isset($d->email))?$d->email:'',
            'phone'             => (isset($d->phone))?$d->phone:'',
            'pmdc'              =>(isset($d->pmdc))?$d->pmdc:'',
            'city_name'         =>(isset($d->city_name))?$d->city_name:'',
            'experience'        => $experience,
            'address'           => (isset($d->address))?$d->address:'',
            'about'    		    => (isset($d->about))?$d->about:'',
            'picture'           => ( (isset($d->doctor_image))? 'http://test.hospitallcare.com/backend/uploads/doctors/'.$d->doctor_image->picture:(($d->gender == 1)?'http://test.hospitallcare.com/backend/web_imgs/doctor-male.png':'http://test.hospitallcare.com/backend/web_imgs/doctor-female.png')),
            'education'    		=> (isset($education))?$education:'',

        ];
        return $data;
    }
}
