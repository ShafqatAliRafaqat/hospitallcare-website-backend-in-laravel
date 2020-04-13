<?php

namespace App\Http\Controllers\CustomerApiControllers;

use App\FCMDevice;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerApiResource\CustomerDoctorDetailResource;
use App\Http\Resources\CustomerApiResource\CustomerDoctorResource;
use App\Http\Resources\DoctorApiResource\DoctorProfileResource;
use App\Models\Admin\Customer;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Doctor;
use App\Models\Admin\Lab;
use App\Models\Admin\Treatment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerLabApiController extends Controller{
    public function all_labs(){
        $labs   =  DB::table('labs as l')
                ->leftjoin('lab_images as li','l.id','li.lab_id')
                ->orderby('l.updated_at','DESC')->select('l.id','l.name','l.address','li.picture')->get();
        foreach($labs as $lab){
            if($lab->picture != NULL){
                $lab->picture       =   'https://support.hospitallcare.com/backend/uploads/labs/'.$lab->picture;
            }
        }
        // $labs   = Lab::orderby('updated_at','DESC')->select('id','name','address')->get();

        return response()->json(['data'=>$labs],200);
    }
    public function diagnostics($id){
        $diagnostics = DB::table('labs as l')
                        ->LEFTJOIN('lab_diagnostics as ld','ld.lab_id','l.id')
                        ->LEFTJOIN('diagnostics as d','ld.diagnostic_id','d.id')
                        ->WHERE('ld.lab_id',$id)
                        ->select('d.id','d.name','ld.cost','d.description','ld.lab_id')
                        ->orderBy('d.name',"ASC")
                        ->get();
        return response()->json(['data'=>$diagnostics],200);
    }
    public function common_diagnostics($id){
        $diagnostics = DB::table('labs as l')
                        ->LEFTJOIN('lab_diagnostics as ld','ld.lab_id','l.id')
                        ->LEFTJOIN('diagnostics as d','ld.diagnostic_id','d.id')
                        ->WHERE('ld.lab_id',$id)
                        ->WHERE('d.is_common',1)
                        ->select('d.id','d.name','ld.cost','d.description','ld.lab_id')
                        ->orderBy('d.name',"ASC")
                        ->get();
        return response()->json(['data'=>$diagnostics],200);
    }
    public function book_diagnostics(Request $request){
        $customer_id = Auth::user()->customer_id;
        $user        = Auth::user();
        if(isset($request->lab_id) && $request->id[0] != null && isset($request->date) && isset($request->time)){
            $i                  =   0;
            $ids                =   json_decode($request->id, true);
            $costs              =   json_decode($request->cost, true);
            $appointment_date   =   Carbon::createFromTimestamp(strtotime($request->date.$request->time))->toDateTimeString();
            $bundle_id          =   $customer_id.time().rand(10,100000);
            foreach($ids as $id){
                $diagnostic_id  = $id;
                $lab_id         = $request->lab_id;
                $cost           = $costs[$i];
                $customer_diagnostics[] = DB::table('customer_diagnostics')->INSERT([
                    'customer_id'       =>  $customer_id,
                    'diagnostic_id'     =>  $diagnostic_id,
                    'lab_id'            =>  $lab_id,
                    'cost'              =>  $cost,
                    'discounted_cost'   =>  $cost,
                    'status'            =>  4,
                    'appointment_from'  =>  2,
                    'appointment_date'  =>  $appointment_date,
                    'bundle_id'         =>  $bundle_id,
                    'home_sampling'     =>  isset($request->home_sampling) ? $request->home_sampling : 0,
                ]);

            }
            $customer = Customer::where('id',$customer_id)->first();
            $customer_phone         = $customer->phone;                                                    // Get Customer phone number
            $customer_name          = $customer->name;
        if(isset($customer_phone)){                                                                  // send message to customer
            $with           = labName($request->lab_id);
            $fdate          = $request->date;
            $location       = lablocation($request->lab_id);
            $map            = labMap($request->lab_id);
            $time           = $request->time;
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
            return response()->json(['message'=>"Your Lab appointment has been booked"],200);
        }
        return response()->json(['message'=>'Enter valid data'],404);
    }
}
