<?php

namespace App\Http\Controllers\WebsiteApiControllers;

use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WebsiteApiResource\WebCustomerResource;
use App\Models\Admin\Customer;
use App\Models\Admin\CustomerAllergy;
use App\Models\Admin\CustomerDoctorNotes;
use App\Models\Admin\CustomerRiskFactor;
use App\Models\Admin\Doctor;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\TempNotes;
use App\Notification;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WebCustomerController extends Controller
{

    public function fetchCustomer($id){
        $customer               = Customer::where('id',$id)->first();
        return WebCustomerResource::make($customer);
    }
    public function updateCustomer(Request $request){
        $id = $request->id;

        $validate = $request->validate([
            'name'                  => 'required|min:3',
        ]);

        $update_customer =  Customer::where('id',$id)->update([
            'name'          => $request->name,
            'weight'        => $request->weight,
            'height'        => $request->height,
            'address'       => $request->address,
            'email'         => $request->email,
            'gender'        => $request->gender,
            'marital_status'=> $request->marital_status,
            'blood_group_id'=> $request->blood_group_id,
        ]);
        $customer        = Customer::where('id',$id)->first();
        if($update_customer){
            $user = User::where('customer_id',$id)->first();
            if($user){
                $user_update =  User::where('customer_id',$id)->update(['name'=>$request->name]);
            }
        }
        return WebCustomerResource::make($customer);
    }
    public function approvedTreatmentAppointments(){
        $customer_id                =   Auth::user()->customer_id;
        $treatment_appointments     =   DB::table('medical_centers as mc')
                                        ->join('customer_procedures as cp','cp.hospital_id','mc.id')
                                        ->join('treatments as t','cp.treatments_id','t.id')
                                        ->join('doctors as d','cp.doctor_id','d.id')
                                        ->where('cp.customer_id',$customer_id)
                                        ->whereIn('cp.status',[0,2])
                                        ->select('cp.id','d.id as doctor_id','d.name as doctor_name','mc.center_name','mc.lat','mc.lng','mc.id as center_id','t.id as treatment_id','t.name as treatment_name','cp.appointment_date')
                                        ->get();
        if (isset($treatment_appointments)) {
            foreach ($treatment_appointments as $ta) {
                $ta->map            = "https://www.google.com/maps?saddr&daddr=$ta->lat,$ta->lng";
                $doctor_image       =   doctorImage($ta->doctor_id);
                $ta->doctor_image   =   (isset($doctor_image))? 'http://test.hospitallcare.com/backend/uploads/doctors/'.$doctor_image->picture:null;
            }
        }
        return response()->json(['data' => $treatment_appointments]);

    }
    public function pendingTreatmentAppointments(){
        $customer_id                =   Auth::user()->customer_id;
        $treatment_appointments     =   DB::table('medical_centers as mc')
                                        ->join('customer_procedures as cp','cp.hospital_id','mc.id')
                                        ->join('treatments as t','cp.treatments_id','t.id')
                                        ->join('doctors as d','cp.doctor_id','d.id')
                                        ->where('cp.customer_id',$customer_id)
                                        ->where('cp.status',4)
                                        ->select('cp.id','d.id as doctor_id','d.name as doctor_name','mc.center_name','mc.lat','mc.lng','mc.id as center_id','t.id as treatment_id','t.name as treatment_name','cp.appointment_date')
                                        ->get();
        if (isset($treatment_appointments)) {
            foreach ($treatment_appointments as $ta) {
                $ta->map            = "https://www.google.com/maps?saddr&daddr=$ta->lat,$ta->lng";
                $doctor_image       =   doctorImage($ta->doctor_id);
                $ta->doctor_image   =   (isset($doctor_image))? 'http://test.hospitallcare.com/backend/uploads/doctors/'.$doctor_image->picture:null;
            }
        }
        return response()->json(['data' => $treatment_appointments]);

    }

    public function getTreatmentHistory(){
        $customer_id                =   Auth::user()->customer_id;
        $treatment_appointments     =   DB::table('medical_centers as mc')
                                        ->join('customer_treatment_history as cp','cp.hospital_id','mc.id')
                                        ->join('treatments as t','cp.treatments_id','t.id')
                                        ->join('doctors as d','cp.doctor_id','d.id')
                                        ->where('cp.customer_id',$customer_id)
                                        ->select('d.id as doctor_id','d.name as doctor_name','mc.center_name','mc.lat','mc.lng','mc.id as center_id','t.id as treatment_id','t.name as treatment_name','cp.appointment_date')
                                        ->get();
        if (isset($treatment_appointments)) {
            foreach ($treatment_appointments as $ta) {
                $ta->map            = "https://www.google.com/maps?saddr&daddr=$ta->lat,$ta->lng";
                $doctor_image       =   doctorImage($ta->doctor_id);
                $ta->doctor_image   =   (isset($doctor_image))? 'http://test.hospitallcare.com/backend/uploads/doctors/'.$doctor_image->picture:null;
            }
        }
        return response()->json(['data' => $treatment_appointments]);

    }
    public function getLabAppointments(){
        $customer_id        =   Auth::user()->customer_id;
        $lab_appointments   =   DB::table('labs as l')
                                ->join('customer_diagnostics as cd','l.id','cd.lab_id')
                                ->join('diagnostics as d','d.id','cd.diagnostic_id')
                                ->where('cd.customer_id',$customer_id)
                                ->select('l.id as lab_id','l.name as lab_name','l.lat','l.lng',DB::raw('GROUP_CONCAT(d.name) as diagnostic_name'),'d.id as diagnostic_id','cd.appointment_date')
                                ->groupBy('cd.appointment_date','l.id')
                                ->get();
        if (isset($lab_appointments)) {
            foreach ($lab_appointments as $la) {
                $la->map            = "https://www.google.com/maps?saddr&daddr=$la->lat,$la->lng";
            }
        }
        return response()->json(['data' => $lab_appointments]);

    }
    public function getLabHistory()
    {
        $customer_id        =   Auth::user()->customer_id;
        $lab_appointments   =   DB::table('labs as l')
                                ->join('customer_diagnostic_history as cdh','l.id','cdh.lab_id')
                                ->join('diagnostics as d','d.id','cdh.diagnostic_id')
                                ->where('cdh.customer_id',$customer_id)
                                ->select('l.id as lab_id','l.name as lab_name','l.lat','l.lng',DB::raw('GROUP_CONCAT(d.name) as diagnostic_name'),'d.id as diagnostic_id','cdh.appointment_date')
                                ->groupBy('cdh.appointment_date','l.id')
                                ->get();
        if (isset($lab_appointments)) {
            foreach ($lab_appointments as $la) {
                $la->map            = "https://www.google.com/maps?saddr&daddr=$la->lat,$la->lng";
            }
        }
        return response()->json(['data' => $lab_appointments]);
    }
    public function cancelAppointment($id){
        $customer_procedure = DB::table('customer_procedures')->where('id',$id)->first();
        $update_customer_procedure =  DB::table('customer_procedures')->where('id',$id)->delete();
        $customer_phone         = customerPhone($customer_procedure->customer_id);                      // Get Customer phone number
        $customer_name          = customerName($customer_procedure->customer_id);
        if(isset($customer_phone)){                                                                    // send message to customer
            $with           = doctorName($customer_procedure->doctor_id);
            $at             = centerName($customer_procedure->hospital_id);
            $date           = Carbon::parse($customer_procedure->appointment_date);                             // Appointment date
            $fdate          = $date->format('jS F Y');
            $time           = $date->format('h:i A');
            $n              = '\n';
        $message        = "Dear+$customer_name,".$n.$n."Your+appointment+with+$with+at+$at+on+$fdate+at+$time+has+been+Canceled".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com".$n."For+Queries:+0322-2555600,".$n."0322-2555400";
        $sms            = CustomerAppointmentSms($message, $customer_phone);
        }
        $user = User::where('customer_id',$customer_procedure->customer_id)->first();
            if(isset($user)){
                $fcm_device = DB::table('f_c_m_devices')->where('user_id',$user->id)->first();
                if(isset($fcm_device)){
                    $message="Your Appointment with $with has been canceled";
                    NotificationHelper::GENERATE([
                        'title' => 'Appointment Canceled',
                        'body' => $message,
                        'payload' => [
                            'type' => "Appointment Canceled"
                        ]
                    ],$user->id);
                }
            }
        return response()->json(["data" =>$id],200);
    }
    public function getAllergies($id){
        $customer_id         = Auth::user()->customer_id;
        $customer_allergies = CustomerAllergy::where('customer_id',$customer_id)->select('notes')->get();
        if(count($customer_allergies)>0){
            return response()->json(['data'=>$customer_allergies],200);
        }else{
            return response()->json(['nodata'=>"There is no data"],200);
        }
    }
    public function updateAllergies(Request $request){
        $customer_id         = Auth::user()->customer_id;
            $allergies_notes = $request->options;
            $customer_allergy_delete =  CustomerAllergy::where('customer_id',$customer_id)->forcedelete();
            if(count($allergies_notes)>0 ){
                foreach($allergies_notes as $notes){
                    if(is_array($notes)){
                        $notes_data[] = implode(" ",$notes);
                        // $notes_data[]  = isset($notes->value)? $notes['value'] : $notes;
                        // foreach($notes as $note){
                        //     $notes_data[] =$note;  
                        // }
                    }else{
                        $notes_data[] =$notes;     
                    }
                }
                if(isset($notes_data[0])){
                    
                    foreach($notes_data as $insert_notes){
                        if(isset($insert_notes) && $insert_notes != null){
                            $insert = CustomerAllergy::create([
                                "customer_id"   => $customer_id,
                                "notes"         =>$insert_notes
                            ]);
                        }
                    }
                    $customer_allergies = CustomerAllergy::where('customer_id',$customer_id)->select('notes')->get();
                    return response()->json(['data'=>$customer_allergies],200);    

                }else{
                    return response()->json(['nodata'=>"There is no data"],200);    
                }
            }
    }
    public function getRiskfactor($id){
        $customer_id         = Auth::user()->customer_id;
        $Customer_riskfactor = CustomerRiskFactor::where('customer_id',$customer_id)->select('notes')->get();
        if(count($Customer_riskfactor)>0){
            return response()->json(['data'=>$Customer_riskfactor],200);
        }else{
            return response()->json(['nodata'=>"There is no data"],200);
        }
    }
    public function updateRiskfactor(Request $request){
        $customer_id         = Auth::user()->customer_id;
            $riskfactor_notes = $request->options;
            $customer_riskfactor_delete =  CustomerRiskFactor::where('customer_id',$customer_id)->forcedelete();
            if(count($riskfactor_notes)>0 ){
                foreach($riskfactor_notes as $notes){
                    if(is_array($notes)){
                        $notes_data[] = implode(" ",$notes);
                        // $notes_data[]  = isset($notes->value)? $notes['value'] : $notes;
                        // foreach($notes as $note){
                        //     $notes_data[] =$note;  
                        // }
                    }else{
                        $notes_data[] =$notes;     
                    }
                }
                if(isset($notes_data[0])){
                    
                    foreach($notes_data as $insert_notes){
                        if(isset($insert_notes) && $insert_notes != null){
                            $insert = CustomerRiskFactor::create([
                                "customer_id"   => $customer_id,
                                "notes"         =>$insert_notes
                            ]);
                        }
                    }
                    $Customer_riskfactor = CustomerRiskFactor::where('customer_id',$customer_id)->select('notes')->get();
                    return response()->json(['data'=>$Customer_riskfactor],200);    

                }else{
                    return response()->json(['nodata'=>"There is no data"],200);    
                }
            }
    }
    public function getDoctorNotes($id){
        $customer_id         = Auth::user()->customer_id;
        $customer_doctor_notes = CustomerDoctorNotes::where('customer_id',$customer_id)->select('notes')->get();
        if(count($customer_doctor_notes)>0){
            return response()->json(['data'=>$customer_doctor_notes],200);
        }else{
            return response()->json(['nodata'=>"There is no data"],200);
        }
    }
}
