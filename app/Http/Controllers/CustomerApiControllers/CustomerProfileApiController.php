<?php

namespace App\Http\Controllers\CustomerApiControllers;

use App\FCMDevice;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerApiResource\CustomerProfileResource;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\WebsiteApiResource\WebDoctorResource;
use App\Models\Admin\Customer;
use App\Models\Admin\CustomerAllergy;
use App\Models\Admin\CustomerRiskFactor;
use App\Models\Admin\Doctor;
use App\Models\Admin\Treatment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class CustomerProfileApiController extends Controller{
    public function book_appointment(Request $request){

        $user           = Auth::user();
        $customer_id    = isset($request->customer_id)? $request->customer_id : Auth::user()->customer_id;
        $customer       = Customer::where("id",$customer_id)->first();

        // $date = Carbon::createFromTimestamp(strtotime($request->date . $request->time))->toDateTimeString();
        // return response()->json(['data'=> $date, 'request date' => $request->date, 'request Time' => $request->time], 200);

        if($request->treatment_id && $request->center_id && $customer){
            $input = $request->all();
            $date  = Carbon::createFromTimestamp(strtotime($request->date . $request->time))->toDateTimeString();
            $add_Treatments = DB::table('customer_procedures')->INSERT([
                'customer_id'       =>  $customer_id,
                'treatments_id'     =>  $request->treatment_id,
                'hospital_id'       =>  $request->center_id,
                'doctor_id'         =>  $request->doctor_id,
                'status'            =>  4,
                'cost'              =>  0,
                'discounted_cost'   =>  0,
                'appointment_date'  =>  $date,
                'appointment_from'  =>  2,
            ]);
            $doctor_view = DB::table('doctor_views')->where('doctor_id',$request->doctor_id)->where('customer_id',$customer_id)->where('view_from',1)->update(['viewed_or_booked'=>1]);
            $customer_phone         = $customer->phone;                                                    // Get Customer phone number
            $customer_name          = $customer->name;
        if(isset($customer_phone)){                                                                  // send message to customer
            $with           = doctorName($request->doctor_id);
            $fdate          = $input['date'];
            $location       = centerlocation($request->center_id);
            $map            = centerMap($request->center_id);
            $time           = $input['time'];
            $n              = '\n';
            $message        = "Dear+$customer_name,".$n.$n."Thank+you+for+booking+an+appointment+through+HospitALL.".$n.$n."Our+Customer+Care+will+approve+your+appointment+with+$with+after+the+confirmation.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Location:+$location".$n."Google+Map:+$map".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies.+You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com".$n."For+Queries:+0322-2555600,".$n."0322-2555400";
            $sms            = CustomerAppointmentSms($message, $customer_phone);
        }
        if(isset($user)){
            $fcm_device = DB::table('f_c_m_devices')->where('user_id',$user->id)->first();
            if(isset($fcm_device)){
                $message ="Your Appointment is pending for approval";
                NotificationHelper::GENERATE([
                    'title' => 'Appointment Booked',
                    'body' => $message,
                    'payload' => [
                        'type' => "Appointment Booked"
                    ]
                ],$user->id);
            }
        }
            return response()->json(['message'=>"Your Appointment has been booked"], 200);
        } else {
            return response()->json(['message' => 'Wrong Email or Password'], 401);
        }
    }
    public function treatments(){
        $customer_id = Auth::user()->customer_id;
        $cp_treatments  =   DB::table('customer_procedures as cp')
                        ->join('doctors as d','d.id','cp.doctor_id')
                        ->join('medical_centers as mc','mc.id','cp.hospital_id')
                        ->join('treatments as t','t.id','cp.treatments_id')
                        ->where('cp.customer_id', $customer_id)
                        ->where('cp.status','!=',2)
                        ->select('d.name as doctor_name','mc.center_name','t.name as treatment_name','cp.appointment_date')
                        ->get()->toArray();
        $cth_treatments =   DB::table('customer_treatment_history as cth')
                        ->join('doctors as d','d.id','cth.doctor_id')
                        ->join('medical_centers as mc','mc.id','cth.hospital_id')
                        ->join('treatments as t','t.id','cth.treatments_id')
                        ->Where('cth.customer_id', $customer_id)
                        ->select('d.name as doctor_name','mc.center_name','t.name as treatment_name','cth.appointment_date')
                        ->get()->toArray();
        $treatments        = Array_merge($cp_treatments, $cth_treatments);
        return response()->json(['data' => $treatments], 200);
    }
    public function diagnostics(){
        $customer_id = Auth::user()->customer_id;
        $cd_diagnostics  =   DB::table('customer_diagnostics as cd')
                        ->join('labs as l','l.id','cd.lab_id')
                        ->join('diagnostics as d','d.id','cd.diagnostic_id')
                        ->where('cd.customer_id', $customer_id)
                        ->select('d.name as diagnostic_name','l.name as lab_name','cd.appointment_date')
                        ->get()->toArray();
        $cdh_diagnostics =   DB::table('customer_diagnostic_history as cdh')
                        ->join('labs as l','l.id','cdh.lab_id')
                        ->join('diagnostics as d','d.id','cdh.diagnostic_id')
                        ->where('cdh.customer_id', $customer_id)
                        ->select('d.name as diagnostic_name','l.name as lab_name','cdh.appointment_date')
                        ->get()->toArray();
        $diagnostics        = Array_merge($cd_diagnostics, $cdh_diagnostics);
        return response()->json(['data' => $diagnostics], 200);
    }
    public function doctorNotes(){
        $customer_id    = Auth::user()->customer_id;
        $doctor_notes   = DB::table('customer_doctor_notes')->where('customer_id',$customer_id)->select('notes')->get();
        return response()->json(['data' => $doctor_notes], 200);
    }
    public function all_allergies(){
        $customer_id = Auth::user()->customer_id;
        $allergies = CustomerAllergy::where('customer_id',$customer_id)->select('notes','id')->get();
        return response()->json(['data' => $allergies], 200);
    }
    public function create_allergy(Request $request){
        $customer_id = Auth::user()->customer_id;
        if($request->notes != null){
            $allergies = CustomerAllergy::create([
                'customer_id'   => $customer_id,
                'notes'         => $request->notes,
            ]);
            return response()->json(['message'=>"Data enter Successfully"],200);
        }else{
            return response()->json(['message'=>'Enter Valid data'],404);
        }
    }
    public function update_allergy(Request $request , $id){
        $customer_id = Auth::user()->customer_id;
        $allergies = CustomerAllergy::where('id',$id)->where('customer_id',$customer_id)->first();
        if(isset($allergies) && $request->notes != null){
            $allergies = $allergies->update([
                'notes' => $request->notes,
            ]);
            return response()->json(['message'=>"Data updated Successfully"],200);
        }else{
            return response()->json(['message'=>'Enter Valid data'],404);
        }
    }
    public function delete_allergy($id){
        $customer_id = Auth::user()->customer_id;
        $allergies = CustomerAllergy::where('id',$id)->where('customer_id',$customer_id)->first();
        return response()->json(['message'=>'Data deleted successfully'],200);
    }
    public function all_riskfactor(){
        $customer_id = Auth::user()->customer_id;
        $riskfactors = CustomerRiskFactor::where('customer_id',$customer_id)->select('notes','id')->get();
        return response()->json(['data' => $riskfactors], 200);
    }
    public function create_riskfactor(Request $request){
        $customer_id = Auth::user()->customer_id;
        if($request->notes != null){
            $riskfactor = CustomerRiskFactor::create([
                'customer_id'   => $customer_id,
                'notes'         => $request->notes,
            ]);
            return response()->json(['message'=>"Data enter Successfully"],200);

        }else{
            return response()->json(['message'=>'Enter Valid data'],404);
        }
    }
    public function update_riskfactor(Request $request , $id){
        $customer_id = Auth::user()->customer_id;
        $riskfactor = CustomerRiskFactor::where('id',$id)->where('customer_id',$customer_id)->first();
        if(isset($riskfactor) && $request->notes != null){
            $riskfactor = $riskfactor->update([
                'notes' => $request->notes,
            ]);
            return response()->json(['message'=>"Data updated Successfully"],200);
        }else{
            return response()->json(['message'=>'Enter Valid data'],404);
        }
    }
    public function delete_riskfactor($id){
        $customer_id = Auth::user()->customer_id;
        $riskfactor = CustomerRiskFactor::where('id',$id)->where('customer_id',$customer_id)->first();
        return response()->json(['message'=>'Data deleted successfully'],200);
    }
    public function getCustomerProfile(){
        $customer_id = Auth::user()->customer_id;
        $customer  = Customer::where('id',$customer_id)->first();
        $customer_image = DB::table('customer_images')->where('customer_id',$customer_id)->first();
        $customer->picture = isset($customer_image)?'http://test.hospitallcare.com/backend/uploads/customers/'.$customer_image->picture:'';
        return CustomerProfileResource::make($customer);
    }
    public function updateCustomerProfile(Request $request){

        $id = Auth::user()->customer_id;
        $validate = $request->validate([
            'name'                  => 'required|min:3',
        ]);
        if(isset($request->dob)){
            $dob      =   Carbon::parse($request->dob);
            $age      =   $dob->diff(Carbon::now())->format('%y');
        }
        $update_customer =  Customer::where('id',$id)->update([
            'name'          => $request->name,
            'weight'        => $request->weight,
            'height'        => $request->height,
            'address'       => $request->address,
            'email'         => $request->email,
            'gender'        => $request->gender,
            'marital_status'=> $request->marital_status,
            'blood_group_id'=> $request->blood_group_id,
            'dob'           => isset($request->dob)?$request->dob:'',
            'age'           => isset($age)?$age:null,
        ]);

        $destinationPath = '/backend/uploads/customers/';                  // Defining th uploading path if not exist create new
        $image       = $request->file('picture');
        if ($request->file('picture') != null) {                                 //     Uploading the Image to folde
            $table='customer_images';
            $id_name='customer_id';
            $delete_images = delete_images($id,$destinationPath,$table,$id_name);
            //name that we'll use for the coding
            $filename           =   str_slug($request->name).'-'.time().'.'.$image->getClientOriginalExtension();
            $location           =   public_path($destinationPath.$filename);
        if ($image != null) {
            Image::make($image)->save($location);
            $insert = DB::table('customer_images')->insert(['customer_id' => $id, 'picture' => $filename]);
            }
        }
        if($update_customer){
            $user_update =  User::where('customer_id',$id)->update(['name'=>$request->name]);
        }
        return response()->json(['message'=>"Your profile has been updated successfully"],200);
    }
    public function getTreatmentHistory(){
        $customer_id                =   Auth::user()->customer_id;
        $dependents                 =   Customer::where('parent_id',$customer_id)->orWhere('id',$customer_id)->get();
        foreach($dependents as $dependent){
            $treatment_appointments[]   =   DB::table('medical_centers as mc')
                                            ->join('customer_treatment_history as cp','cp.hospital_id','mc.id')
                                            ->join('treatments as t','cp.treatments_id','t.id')
                                            ->join('customers as c','cp.customer_id','c.id')
                                            ->join('doctors as d','cp.doctor_id','d.id')
                                            ->where('cp.customer_id',$dependent->id)
                                            ->select('cp.id','c.name as customer_name','d.id as doctor_id','d.name as doctor_name','mc.center_name','mc.lat','mc.lng','mc.id as center_id','t.id as treatment_id','t.name as treatment_name','cp.appointment_date')
                                            ->orderBy('cp.updated_at','DESC')
                                            ->get();
        }
        foreach ($treatment_appointments as $ta) {
            if(count($ta)>0){
                foreach($ta as $data){
                    $treatment_appointment[] = $data;
                }
            }
        }
        if(isset($treatment_appointment)){
            foreach ($treatment_appointment as $ta) {
                $ta->map            = "https://www.google.com/maps?saddr&daddr=$ta->lat,$ta->lng";
                $doctor_image       =   doctorImage($ta->doctor_id);
                $ta->doctor_image   =   (isset($doctor_image))? 'http://test.hospitallcare.com/backend/uploads/doctors/'.$doctor_image->picture:null;
            }
        }
        return response()->json(['data' => isset($treatment_appointment)?$treatment_appointment:[]]);
    }
    public function getPendingTreatment(){
        $customer_id                =   Auth::user()->customer_id;
        $dependents                 =   Customer::where('parent_id',$customer_id)->orWhere('id',$customer_id)->get();
        foreach($dependents as $dependent){
            $treatment_appointments[]   =   DB::table('medical_centers as mc')
                                            ->join('customer_procedures as cp','cp.hospital_id','mc.id')
                                            ->join('treatments as t','cp.treatments_id','t.id')
                                            ->join('customers as c','cp.customer_id','c.id')
                                            ->join('doctors as d','cp.doctor_id','d.id')
                                            ->where('cp.customer_id',$dependent->id)
                                            ->where('cp.status',4)
                                            ->select('cp.id','c.name as customer_name','d.id as doctor_id','d.name as doctor_name','mc.center_name','mc.lat','mc.lng','mc.id as center_id','t.id as treatment_id','t.name as treatment_name','cp.appointment_date')
                                            ->orderBy('cp.updated_at','DESC')
                                            ->get();
        }
        foreach ($treatment_appointments as $ta) {
            if(count($ta)>0){
                foreach($ta as $data){
                    $treatment_appointment[] = $data;
                }
            }
        }
        if(isset($treatment_appointment)){
            foreach ($treatment_appointment as $ta) {
                $ta->map            = "https://www.google.com/maps?saddr&daddr=$ta->lat,$ta->lng";
                $doctor_image       =   doctorImage($ta->doctor_id);
                $ta->doctor_image   =   (isset($doctor_image))? 'http://test.hospitallcare.com/backend/uploads/doctors/'.$doctor_image->picture:null;
            }
        }
        return response()->json(['data' => isset($treatment_appointment)?$treatment_appointment:[]]);
    }
    public function getApprovedTreatment(){
        $customer_id                =   Auth::user()->customer_id;
        $dependents                 =   Customer::where('parent_id',$customer_id)->orWhere('id',$customer_id)->get();
        foreach($dependents as $dependent){
            $treatment_appointments[]   =   DB::table('medical_centers as mc')
                                            ->join('customer_procedures as cp','cp.hospital_id','mc.id')
                                            ->join('treatments as t','cp.treatments_id','t.id')
                                            ->join('customers as c','cp.customer_id','c.id')
                                            ->join('doctors as d','cp.doctor_id','d.id')
                                            ->where('cp.customer_id',$dependent->id)
                                            ->whereIn('cp.status',[0,2])
                                            ->select('cp.id','c.name as customer_name','d.id as doctor_id','d.name as doctor_name','mc.center_name','mc.lat','mc.lng','mc.id as center_id','t.id as treatment_id','t.name as treatment_name','cp.appointment_date')
                                            ->orderBy('cp.updated_at','DESC')
                                            ->get();
        }
        foreach ($treatment_appointments as $ta) {
            if(count($ta)>0){
                foreach($ta as $data){
                    $treatment_appointment[] = $data;
                }
            }
        }
        if(isset($treatment_appointment)){
            foreach ($treatment_appointment as $ta) {
                $ta->map            = "https://www.google.com/maps?saddr&daddr=$ta->lat,$ta->lng";
                $doctor_image       =   doctorImage($ta->doctor_id);
                $ta->doctor_image   =   (isset($doctor_image))? 'http://test.hospitallcare.com/backend/uploads/doctors/'.$doctor_image->picture:null;
            }
        }
        return response()->json(['data' => isset($treatment_appointment)?$treatment_appointment:[]]);
    }
    public function cancelTreatment($id){
        $customer_procedure     = DB::table('customer_procedures')->where('id',$id)->first();
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
        $update_customer_procedure =  DB::table('customer_procedures')->where('id',$id)->delete();
        return response()->json(['message' => "Your appointment has been canceled"],200);
    }
    public function approvedLabAppointments(){
        $customer_id        =   Auth::user()->customer_id;
        $dependents         =   Customer::where('parent_id',$customer_id)->orWhere('id',$customer_id)->get();
        foreach($dependents as $dependent){
            $lab_appointments[]   =   DB::table('labs as l')
                                        ->join('customer_diagnostics as cd','l.id','cd.lab_id')
                                        ->join('diagnostics as d','d.id','cd.diagnostic_id')
                                        ->join('customers as c','cd.customer_id','c.id')
                                        ->where('cd.customer_id',$dependent->id)
                                        ->where('cd.status',0)
                                        ->select(DB::raw('GROUP_CONCAT(cd.id) as id'),'l.id as lab_id','c.name as customer_name','l.name as lab_name','l.address','l.assistant_name','l.assistant_phone','l.lat','l.lng',DB::raw('GROUP_CONCAT(d.name) as diagnostic_name'),DB::raw('GROUP_CONCAT(cd.discounted_cost) as cost'),'cd.appointment_date','cd.home_sampling')
                                        ->groupBy('cd.bundle_id')
                                        ->orderBy('cd.id','DESC')
                                        ->get();
        }
        foreach ($lab_appointments as $la) {
            if(count($la)>0){
                foreach($la as $data){
                    $lab_appointment[] = $data;
                }
            }
        }
       if(isset($lab_appointment)){
        foreach ($lab_appointment as $la) {
            $la->map            = "https://www.google.com/maps?saddr&daddr=$la->lat,$la->lng";
        }
       }
        return response()->json(['data' =>isset($lab_appointment)? $lab_appointment:[]]);
    }
    public function penddingLabAppointments(){
        $customer_id        =   Auth::user()->customer_id;
        $dependents         =   Customer::where('parent_id',$customer_id)->orWhere('id',$customer_id)->get();
        foreach($dependents as $dependent){
            $lab_appointments[]   =   DB::table('labs as l')
                                        ->join('customer_diagnostics as cd','l.id','cd.lab_id')
                                        ->join('diagnostics as d','d.id','cd.diagnostic_id')
                                        ->join('customers as c','cd.customer_id','c.id')
                                        ->where('cd.customer_id',$dependent->id)
                                        ->where('cd.status',4)
                                        ->select(DB::raw('GROUP_CONCAT(cd.id) as id'),'l.id as lab_id','c.name as customer_name','l.name as lab_name','l.address','l.assistant_name','l.assistant_phone','l.lat','l.lng',DB::raw('GROUP_CONCAT(d.name) as diagnostic_name'),DB::raw('GROUP_CONCAT(cd.discounted_cost) as cost'),'cd.appointment_date','cd.home_sampling')
                                        ->groupBy('cd.bundle_id')
                                        ->orderBy('cd.id','DESC')
                                        ->get();
        }
        foreach ($lab_appointments as $la) {
            if(count($la)>0){
                foreach($la as $data){
                    $lab_appointment[] = $data;
                }
            }
        }
       if(isset($lab_appointment)){
        foreach ($lab_appointment as $la) {
            $la->map            = "https://www.google.com/maps?saddr&daddr=$la->lat,$la->lng";
        }
       }
        return response()->json(['data' =>isset($lab_appointment)? $lab_appointment:[]]);
    }
    public function getLabHistory()
    {
        $customer_id        =   Auth::user()->customer_id;
        $dependents         =   Customer::where('parent_id',$customer_id)->orWhere('id',$customer_id)->get();
        foreach($dependents as $dependent){
            $lab_appointments[]   =   DB::table('labs as l')
                                    ->join('customer_diagnostic_history as cd','l.id','cd.lab_id')
                                    ->join('diagnostics as d','d.id','cd.diagnostic_id')
                                    ->join('customers as c','cd.customer_id','c.id')
                                    ->where('cd.customer_id',$dependent->id)
                                    ->select(DB::raw('GROUP_CONCAT(cd.id) as id'),'l.id as lab_id','c.name as customer_name','l.name as lab_name','l.address','l.assistant_name','l.assistant_phone','l.lat','l.lng',DB::raw('GROUP_CONCAT(d.name) as diagnostic_name'),DB::raw('GROUP_CONCAT(cd.discounted_cost) as cost'),'cd.appointment_date','cd.home_sampling')
                                    ->groupBy('cd.bundle_id')
                                    ->orderBy('cd.updated_at','DESC')
                                    ->get();
        }
        foreach ($lab_appointments as $la) {
            if(count($la)>0){
                foreach($la as $data){
                    $lab_appointment[] = $data;
                }
            }
        }
       if(isset($lab_appointment)){
        foreach ($lab_appointment as $la) {
            $la->map            = "https://www.google.com/maps?saddr&daddr=$la->lat,$la->lng";
        }
       }
        return response()->json(['data' =>isset($lab_appointment)? $lab_appointment:[]]);
    }
    public function cancelLabAppointment(Request $request){
        $customer_id= Auth::user()->customer_id;
        $user       = Auth::user();
        $lab_id     = $request->lab_id;
        $id         = $request->id;
        $ids        = explode(',',$id);
        if(isset($lab_id) && isset($id)){
            foreach($ids as $id){
                $update_customer_procedure =  DB::table('customer_diagnostics')->where('id',$id)->delete();
            }
        }
        $customer_phone         = customerPhone($customer_id);                      // Get Customer phone number
        $customer_name          = customerName($customer_id);
        if(isset($customer_phone)){                                                                    // send message to customer
            $at             = LabName($lab_id);
            $date           = Carbon::parse($request->appointment_date);                             // Appointment date
            $fdate          = $date->format('jS F Y');
            $time           = $date->format('h:i A');
            $n              = '\n';
        $message        = "Dear+$customer_name,".$n.$n."Your+appointment+at+$at+on+$fdate+at+$time+has+been+Canceled".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com".$n."For+Queries:+0322-2555600,".$n."0322-2555400";
        $sms            = CustomerAppointmentSms($message, $customer_phone);
        }
            if(isset($user)){
                $fcm_device = DB::table('f_c_m_devices')->where('user_id',$user->id)->first();
                if(isset($fcm_device)){
                    $message="Your Appointment with $at has been canceled";
                    NotificationHelper::GENERATE([
                        'title' => 'Appointment Canceled',
                        'body' => $message,
                        'payload' => [
                            'type' => "Appointment Canceled"
                        ]
                    ],$user->id);
                }
            }
        return response()->json(['message' => "Your appointment has been canceled"],200);
    }
}
