<?php

namespace App\Http\Controllers\DoctorApiControllers;

use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Doctor;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\DoctorApiResource\DoctorCustomerResource;
use App\Models\Admin\CustomerAllergy;
use App\Models\Admin\CustomerRiskFactor;
use App\Models\Admin\CustomerDoctorNotes;
use App\Models\Admin\Treatment;
use App\Notification;
use App\User;

class DoctorTreatmentApiController extends Controller
{
    public function today_appointment(){
        $startOfDay     = Carbon::now()->startOfDay()->toDateTimeString();      //today start time and date
        $endOfDay       = Carbon::now()->endOfDay()->toDateTimeString();        //today end time and date

        $doctor_id      =   Auth::user()->doctor_id;

        $clients        = DB::table('customers as c')
                        ->JOIN('customer_procedures as cp','cp.customer_id','c.id')
                        ->WHERE('cp.doctor_id',$doctor_id)
                        ->where(function($query) {
                            $query->where('cp.status','!=',1);
                            $query->where('cp.status','!=',3);
                            $query->where('cp.status','!=',4);
                            })
                        ->orderBy('cp.created_at','DESC')
                        ->whereBetween('appointment_date',[$startOfDay, $endOfDay])
                        ->select('c.*','cp.hospital_id','cp.id as customer_procedures_id','cp.treatments_id as treatments_id','cp.doctor_id','cp.hospital_id','cp.cost as costs','cp.appointment_date')
                        ->get();
        if(count($clients)> 0){
            return DoctorCustomerResource::collection($clients);
        } else {
            return response()->json(['message' => 'There is no appointment today'], 404);
        }
    }

    public function upcoming_appointment(){
        $endOfDay       = Carbon::now()->endOfDay()->toDateTimeString();      //today end time and date
        $doctor_id      = Auth::user()->doctor_id;
        $client         = DB::table('customers as c')
                        ->JOIN('customer_procedures as cp','cp.customer_id','c.id')
                        ->WHERE('cp.doctor_id',$doctor_id)
                        ->where(function($query) {
                            $query->where('cp.status','!=',1);
                            $query->where('cp.status','!=',3);
                            $query->where('cp.status','!=',4);
                            })
                        ->orderBy('cp.created_at','DESC')
                        ->WHERE('appointment_date','>',$endOfDay)
                        ->select('c.*','cp.hospital_id','cp.id as customer_procedures_id','cp.treatments_id as treatments_id','cp.doctor_id','cp.hospital_id','cp.cost as costs','cp.appointment_date');
        $clients = $client->paginate(5);
        if(count($clients)> 0){
            return DoctorCustomerResource::collection($clients);
        } else {
            return response()->json(['message' => 'There is no upcoming appointment'], 404);
        }
    }

    public function cancel_appointment($id)                                // doctor can cancel appointment of customer
    {
        $customer = DB::table('customer_procedures')->where('id',$id)->first();
        if(isset($customer)){
            $customer_updated       = DB::table('customer_procedures')->where('id',$id)->update(['status' => 1,]);  // When doctor cancel customer appointment its status is 1
            $customer_phone         = customerPhone($customer->customer_id);       // Get Customer phone number
            $customer_name          = customerName($customer->customer_id);
            if(isset($customer_phone)){                                                                    // send message to customer
                $with           = doctorName($customer->doctor_id);
                $at             = centerName($customer->hospital_id);
                $date           = Carbon::parse($customer->appointment_date);                             // Appointment date
                $fdate          = $date->format('jS F Y');
                $time           = $date->format('h:i A');
                $n              = '\n';
            $message        = "Dear+$customer_name,".$n.$n."Your+appointment+with+$with+at+$at+on+$fdate+at+$time+has+been+Canceled".$n.$n."We+will+Reschedule+your+appointment+as+soon+as+possible.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com".$n."For+Queries:+0322-2555600,".$n."0322-2555400";
            $sms            = CustomerAppointmentSms($message, $customer_phone);
            }
            $message            =   "You have Canceled an Appointment with $customer_name";
            $not = Notification::create([
                'title'     => 'Appointment Canceled',
                'body'      => $message,
                'payload'   => json_encode('Appointment Canceled'),
            ]);
            $id = User::where('doctor_id',$customer->doctor_id)->pluck('id')->first();
            if($id != null){
              $not=  $not->users()->sync($id);
            }
            return response()->json(['message' => 'Appointment canceled successfully'], 200);
        }
        return response()->json(['message' => 'There is no appointment for cancel'], 404);
    }


    public function next_appointment(Request $request, $id)                                       // when doctor edit customer appointment from doctor panel;
    {
        $input = $request->all();
        $timestamp = Carbon::createFromTimestamp(strtotime($input['date'] . $input['time']));
        $customer = DB::table('customer_procedures')->where('id',$id)->first();
        if ($customer) {
            $customer_history = DB::table('customer_treatment_history')->insert([                 // insert customer treatment details in customer history table
            'customer_id'   => $customer->customer_id,
            'treatments_id' => $customer->treatments_id,
            'hospital_id'   => $customer->hospital_id,
            'doctor_id'     => $customer->doctor_id,
            'cost'          => $customer->cost,
            'appointment_date' => $customer->appointment_date,
            'appointment_from' => 3,
        ]);
        $new_customer_procedures = DB::table('customer_procedures')->insert([                      // insert new appointment date in customer procedure table
            'customer_id'   => $customer->customer_id,
            'treatments_id' => $customer->treatments_id,
            'hospital_id'   => $customer->hospital_id,
            'doctor_id'     => $customer->doctor_id,
            'cost'          => $customer->cost,
            'status'          => 2,                                                                 // when status is 2 it means treatment is ongoing, and doctor added new appointment date;
            'appointment_date' => $timestamp,
            'appointment_from' => 3,
        ]);
       $customer_phone         = customerPhone($customer->customer_id);                                            // Get Customer phone number
       $customer_name          = customerName($customer->customer_id);
       $with                   = doctorName($customer->doctor_id);
       $at                     = centerName($customer->hospital_id);
       $location               = centerlocation($customer->hospital_id);
       $map                    = centerMap($customer->hospital_id);
       $date                   = Carbon::parse($timestamp);                             // Appointment date
       $fdate                  = $date->format('jS F Y');
       $time                   = $date->format('h:i A');
       $n                      = '\n';
       if(isset($customer_phone)){
                                                                                                          // send message to customer
           $message        = "Dear+$customer_name,".$n.$n."Your+appointment+has+been+booked+with+$with+at+$at.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Location:+$location".$n."Google+Map:+$map".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies.+You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com".$n."For+Queries:+0322-2555600,".$n."0322-2555400";
           $sms            = CustomerAppointmentSms($message, $customer_phone);
       }
        $message = "Your Next Appointment with $customer_name is on $date and at $at";
        NotificationHelper::GENERATE([
            'title' => 'Next Appointment',
            'body' => $message,
            'payload' => [
                'type' => "Next Appointment"
            ]
        ],[$customer->doctor_id]);

        $check_customer_in_users = User::where('customer_id',$customer->customer_id)->first();
            if($check_customer_in_users){
                $message = "Your Next Appointment with $with is on $date and at $at";
                NotificationHelper::GENERATE([
                    'title' => 'Next Appointment',
                    'body' => $message,
                    'payload' => [
                        'type' => "Next Appointment"
                    ]
                ],$check_customer_in_users->id);
            }
        $customer = DB::table('customer_procedures')->where('id',$id)->delete(); // delete perivous customer procedure data
        }
        return response()->json(['message' => 'Appointment updated successfully'], 200);
    }


    public function TreatmentHistory($id) // User Can view all the Histroy of Customer Treatments
    {
        $clients        = DB::table('customers as c')
                        ->JOIN('customer_treatment_history as cp','cp.customer_id','c.id')
                        ->JOIN('treatments as t','cp.treatments_id','t.id')
                        ->JOIN('medical_centers as mc','cp.hospital_id','mc.id')
                        ->WHERE('c.id',$id)
                        ->orderBy('cp.created_at','DESC')
                        ->select('c.name','mc.center_name','t.name as treatment_name','cp.appointment_date')
                        ->get();
        if(count($clients)>0){
           return response()->json(['data' => $clients], 200);
        }
        return response()->json(['message' => 'There is no treatment history'], 404);
    }


    public function DiagnosticHistory($id) // User Can view all the History of Customer Diagnostic
    {
        $diagnostics    =   DB::table('customer_diagnostic_history as cdh')
                            ->join('diagnostics as d','d.id','cdh.diagnostic_id')
                            ->join('labs as l','l.id','cdh.lab_id')
                            ->where('customer_id', $id)
                            ->orderBy('cdh.created_at','DESC')
                            ->select('d.name as diagnostic','cdh.appointment_date','l.name as lab_name')
                            ->get();
        if(count($diagnostics)>0){
            return response()->json(['data' => $diagnostics], 200);
         }
         return response()->json(['message' => 'There is no diagnostic history'], 404);
    }
    public function Allergies($id){
        $allergies      = CustomerAllergy::where('customer_id',$id)->select('id','notes')->first();
        if($allergies){
            return response()->json(['data' => $allergies], 200);
         }
         return response()->json(['message' => 'There is no allergies'], 404);
    }
    public function RiskFactor($id){
        $riskfactor     = CustomerRiskFactor::where('customer_id',$id)->select('id','notes')->first();
        if($riskfactor){
            return response()->json(['data' => $riskfactor], 200);
         }
         return response()->json(['message' => 'There is no risk factor'], 404);
    }
    public function DoctorNotes($id){
        $doctor_notes = CustomerDoctorNotes::where('customer_id',$id)->select('id','notes')->first();
        if($doctor_notes){
            return response()->json(['data' => $doctor_notes], 200);
         }
         return response()->json(['message' => 'There is no Doctor Notes'], 404);
    }
    public function EditAllergies(Request $request ,$id){
        $allergies          =   CustomerAllergy::where('customer_id',$id)->first();
        $date               =   Carbon::now()->format('m/d/Y');
        $doctor_id          =   Auth::user()->doctor_id;
        $doctor_name        =   Doctor::where('id',$doctor_id)->select('name')->first();
        // $doctor_id          =   Auth::user()->id;
        if($allergies){
            $allergies_notes       = $request->notes;
            $notes  =   '<p>'.$doctor_name->name.' On '.$date.'</p>'.'<p>'.$allergies_notes.'</p>'.'<br>'.$allergies->notes;
            $update = $allergies->update(['notes' => $notes]);
            return response()->json(['message' => 'Allergies Updated successfully'], 200);
        }else{
            $allergies_notes       = $request->notes;
            $notes  =   '<p>'.$doctor_name->name.' On '.$date.'</p>'.'<p>'.$allergies_notes.'</p>'.'<br>';
            $insert = CustomerAllergy::create(['notes' => $notes, 'customer_id' => $id]);
            return response()->json(['message' => 'Allergies inserted successfully'], 200);
        }
    }
    public function EditRiskFactor(Request $request,$id){
        $riskfactor         =   CustomerRiskFactor::where('customer_id',$id)->first();
        $date               =   Carbon::now()->format('m/d/Y');
        $doctor_id          =   Auth::user()->doctor_id;
        $doctor_name        =   Doctor::where('id',$doctor_id)->select('name')->first();
        // $doctor_id          =   Auth::user()->id;
        if($riskfactor){
            $riskfactor_notes       = $request->notes;
            $notes  =   '<p>'.$doctor_name->name.' On '.$date.'</p>'.'<p>'.$riskfactor_notes.'</p>'.'<br>'.$riskfactor->notes;
            $update = $riskfactor->update(['notes' => $notes]);
            return response()->json(['message' => 'Risk Factor Updated successfully'], 200);
        } else {
            $riskfactor_notes       = $request->notes;
            $notes  =   '<p>'.$doctor_name->name.' On '.$date.'</p>'.'<p>'.$riskfactor_notes.'</p>'.'<br>';
            $insert = CustomerRiskFactor::create(['notes' => $notes , 'customer_id' => $id]);
            return response()->json(['message' => 'Risk Factor inserted successfully'], 200);
        }
    }
    public function EditDoctorNotes(Request $request,$id){
        // $doctornotes        =   CustomerDoctorNotes::where('customer_id',$id)->first();
        $date               =   Carbon::now()->format('m/d/Y');
        $doctor_id          =   Auth::user()->doctor_id;
        $doctor_name        =   Doctor::where('id',$doctor_id)->select('name')->first();
        // $doctor_id          =   Auth::user()->id;
        // if($doctornotes){
        //     $doctor_notes   = $request->notes;
        //     $notes          = $doctor_name->name.' On '.$date.' '.$doctor_notes.' '.$doctornotes->notes;
        //     $update = $doctornotes->update(['notes' => $notes]);
        //     return response()->json(['message' => 'Doctor Notes Updated Successfully'], 200);
        // }else{
            $doctor_notes       = $request->notes;
            $notes              = $doctor_name->name.' On '.$date.' '.$doctor_notes;
            $insert             = CustomerDoctorNotes::create(['notes' => $notes, 'customer_id' => $id]);
            return response()->json(['message' => 'Doctor notes inserted successfully'], 200);
        // }
    }

    public function Specilization(){
        $doctor_id = Auth::user()->doctor_id;
        $treatments = Treatment::where('parent_id',null)->orderBy('name','ASC')->select('id','name')->get();

        foreach($treatments as $t){
            $aleady_treatments = DB::table('temp_doctor_treatment')->where('doctor_id',$doctor_id)->where('treatment_id',$t->id)->where('parent_id',null)->first();
            (isset($aleady_treatments)?$t['already'] = true: $t['already'] = false);
        }
        if($treatments){
            return response()->json(['data' => $treatments], 200);
        }else{
            return response()->json(['message' => 'There is no Specilization'], 404);
        }
    }

    public function UpdateSpecilization(Request $request){
        $doctor_id = Auth::user()->doctor_id;
        if($request->data){
            $treatments = DB::table('temp_doctor_treatment')->where('doctor_id',$doctor_id)->where('parent_id',null)->delete();
            $specilization = json_decode($request->data, true);
            foreach ($specilization as $treatment) {
                $t_id=   $treatment;
                foreach ($t_id as $t) {
                    $insert_treatments[] = DB::table('temp_doctor_treatment')->insert([
                    'doctor_id'    => $doctor_id,
                    'treatment_id' => $t,
                ]);
                }
            }
        }
        return response()->json(['message' => "Specilization added successfully"], 200);
    }
    public function Treatment(){
        $doctor_id      = Auth::user()->doctor_id;
        $specilization  = DB::table('temp_doctor_treatment')->where('doctor_id',$doctor_id)->where('parent_id',null)->select('treatment_id')->get();
        foreach($specilization as $s){
            $treatment     = Treatment::where('parent_id',$s->treatment_id)->orderBy('name','ASC')->select('id','name')->get();
            foreach($treatment as $t){
                $treatments[] = Treatment::where('id',$t->id)->select('id','name','parent_id')->first();
                foreach($treatments as $t){
                    $aleady_treatments = DB::table('temp_doctor_treatment')->where('doctor_id',$doctor_id)->where('treatment_id',$t->id)->where('parent_id','!=',null)->first();
                    (isset($aleady_treatments)?$t['already'] = true: $t['already'] = false);
                }
            }
        }
        if(isset($treatments)){
            return response()->json(['data' => $treatments], 200);
        }else{
            return response()->json(['message' => 'There is no Specilization'], 404);
        }
    }
    public function UpdateTreatment(Request $request){
        $doctor_id = Auth::user()->doctor_id;
        $delete_treatments = DB::table('temp_doctor_treatment')->where('doctor_id',$doctor_id)->where('parent_id','!=',null)->delete();
        $data_array = json_decode($request->data, true);
        foreach($data_array as $td){
            $t_id=   $td;
            foreach($t_id as $treatment){
                    $insert_treatments[] = DB::table('temp_doctor_treatment')->insert([
                        'doctor_id'     => $doctor_id,
                        'treatment_id'  => $treatment['id'],
                        'parent_id'     => $treatment['parent_id'],
                    ]);
            }
        }
        $center             =   DB::table('center_doctor_schedule')->where('doctor_id',$doctor_id)->get();
        if(count($center)>0){
            foreach ($center as $c) {
                $array[]  =  $c->center_id;
            }
            $center_id      = (count($array)>0)? array_values(array_unique($array)) : NUll;
            if(isset($center_id)){
                foreach($center_id as $c_id){
                    $center  =   DB::table('center_doctor_schedule')->where('doctor_id',$doctor_id)->where('center_id',$c_id)->first();

                    if ($center) {
                        $delete_treatments  =   DB::table('doctor_treatments')->where('doctor_id',$doctor_id)->delete();
                        $treatments = DB::table('temp_doctor_treatment')->where('doctor_id',$doctor_id)->get();
                        foreach ($treatments as $treatment_id) {
                            $add_Treatments = DB::table('doctor_treatments')->INSERT([
                            'schedule_id'    => $center->id,
                            'treatment_id'   => $treatment_id->treatment_id,
                            'doctor_id'      => $doctor_id,
                            ]);
                        }
                    }
                }
            }
        }
        return response()->json(['message' => 'Treatment added successfully'], 200);
    }
}
