<?php

namespace App\Http\Controllers\DoctorApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Customer;
use App\Http\Resources\DoctorApiResource\DoctorCustomerResource;

class DoctorPatientApiController extends Controller
{
    public function Patient(){
        $doctor_id = Auth::user()->doctor_id;
        $cp_patients    =   DB::table('doctors as d')
                        ->join('customer_procedures as cp','cp.doctor_id','d.id')
                        ->join('customers as c','c.id','cp.customer_id')
                        ->where(['cp.doctor_id' => $doctor_id])
                        ->whereIn('cp.status',[0,2])
                        ->select('c.id','c.name','c.gender')
                        ->groupBy('c.id')
                        ->get()->toArray();
        $cth_patients   =   DB::table('doctors as d')
                        ->join('customer_treatment_history as cth','cth.doctor_id','d.id')
                        ->join('customers as c','c.id','cth.customer_id')
                        ->Where(['cth.doctor_id' => $doctor_id])
                        ->select('c.id','c.name','c.gender')
                        ->groupBy('c.id')
                        ->get()->toArray();
        $patients        = Array_merge($cp_patients, $cth_patients);
        foreach ($patients as $p) {
            $image          =   DB::table('customer_images')->where('customer_id',$p->id)->first();
            $p->gender      =   ($p->gender == 0)?'Male':'Female';
            $p->image       =  (isset($image->picture))? 'https://support.hospitallcare.com/backend/uploads/customers/'.$image->picture:'';
            // $p->image       =   ((isset($image->picture))? 'https://support.hospitallcare.com/backend/uploads/customers/'.$image->picture:(($p->gender == 0)?'https://support.hospitallcare.com/backend/web_imgs/app-male.png':'https://support.hospitallcare.com/backend/web_imgs/app-female.png'));
        }
        return response()->json(['data' => $patients], 200);
    }
    public function PatientDetails($id){
        $customer = Customer:: where('id',$id)->get();
        if($customer){
            return DoctorCustomerResource::collection($customer);
        } else {
            return response()->json(['message' => 'There is no patient'], 404);
        }
    }
}
