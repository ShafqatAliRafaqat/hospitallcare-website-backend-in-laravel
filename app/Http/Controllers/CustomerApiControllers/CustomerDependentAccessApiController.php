<?php

namespace App\Http\Controllers\CustomerApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerDependentAccessApiController extends Controller
{
    public function dependent_history($customer_id)
    {
        $customers                     =   Customer::Where('id',$customer_id)->first();
        $treatment_appointments   =   DB::table('medical_centers as mc')
                                        ->join('customer_treatment_history as cp','cp.hospital_id','mc.id')
                                        ->join('treatments as t','cp.treatments_id','t.id')
                                        ->join('customers as c','cp.customer_id','c.id')
                                        ->join('doctors as d','cp.doctor_id','d.id')
                                        ->where('cp.customer_id',$customers->id)
                                        ->select('cp.id','c.name as customer_name','d.id as doctor_id','d.name as doctor_name','mc.center_name','mc.lat','mc.lng','mc.id as center_id','t.id as treatment_id','t.name as treatment_name','cp.appointment_date')
                                        ->orderBy('cp.updated_at','DESC')
                                        ->get();
        if(isset($treatment_appointments)){
            foreach ($treatment_appointments as $ta) {
                $ta->map            = "https://www.google.com/maps?saddr&daddr=$ta->lat,$ta->lng";
                $doctor_image       =   doctorImage($ta->doctor_id);
                $ta->doctor_image   =   (isset($doctor_image))? 'https://support.hospitallcare.com/backend/uploads/doctors/'.$doctor_image->picture:null;
            }
        }
        return response()->json(['data' => isset($treatment_appointments)?$treatment_appointments:[]]);
    }
}
