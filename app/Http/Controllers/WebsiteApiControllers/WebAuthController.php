<?php

namespace App\Http\Controllers\WebsiteApiControllers;

use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Customer;
use App\Models\Admin\Doctor;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WebAuthController extends Controller
{
    public function unique_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
    public function phoneVarification(Request $request){
        $validate = $request->validate([
            'phone'       => 'required',
        ]);
        $request['phone']    = formatPhone($request->phone);

        $phone_check    = Customer::where('phone',$request->phone)->withTrashed()->first();

        if (isset($phone_check)) {
            $customer_id = $phone_check->id;
            $customer_name = $phone_check->name;
            $customer_user = User::where('customer_id',$customer_id)->withTrashed()->first();
            if(isset($customer_user)){
                return response()->json(['customeruser' => 'Customer user already Exists!'], 200);
            }
        }else{
            $customer_id = null;
            $customer_name = null;
        }

        $usertable = User::where('phone',$request->phone)->whereNotNull('customer_id')->withTrashed()->first();
        if(isset($usertable)){
            return response()->json(['customeruser' => 'Customer user already Exists!'], 200);
        }

        $phone_check    = DB::table('customer_phone_verification')->where('phone', $request->phone)->first();
        if (isset($phone_check)) {
            DB::table('customer_phone_verification')->where('phone', $request->phone)->delete();
        }

        $code = random_int(100000, 999999);
        $sms = "$code+is+your+HospitALL+account+verification+code.+This+code+will+expire+in+10+minutes";
        $code_sended = CustomerAppointmentSms($sms , $request->phone);

        if ($code_sended) {
                $customer_phone_verification =  DB::table('customer_phone_verification')->insert([
                'phone'         =>  $request->phone,
                'code'          =>  $code,
                ]);

                 return response()->json(['codeSended' => $request->phone, "customertable" =>$customer_id, "customerName" => $customer_name], 200);
            } else {
                return response()->json(['message' => 'Enter valid data'], 401);
            }
    }

    public function codeVarification(Request $request){
        $validate = $request->validate([
            'code'             => 'required',
        ]);
        $request['codeSended']    = formatPhone($request->codeSended);

        $customer_phone_validation =  DB::table('customer_phone_verification')->where('phone',$request->codeSended)->where('code',$request->code)->first();
        if($customer_phone_validation){
            return response()->json(['codeVarified' => 'Code is verified'], 200);
        }
        return response()->json(['message' => 'Enter valid data'], 404);
    }


    public function signUp(Request $request){
        $validate = $request->validate([
            'name'                  => 'required|min:3',
            'password'              => 'required|string|min:6',
        ]);
        $request['codeSended']    = formatPhone($request->codeSended);

        $customer_phone_validation =  DB::table('customer_phone_verification')->where('phone',$request->codeSended)->where('code',$request->code)->first();
        if($customer_phone_validation){
            if(!isset($request->customertable)){
                $customer_id = DB::table('customers')->insertGetId([
                    'ref'          => $this->unique_code(4),
                    'name'         => $request->name,
                    'phone'        => $request->codeSended,
                    'status_id'    => 11,
                    'customer_lead'=> 2,
                    'phone_verified'=>1,
                    'created_at'   => Carbon::now()->toDateTimeString(),
                    'updated_at'   => Carbon::now()->toDateTimeString(),
                ]);
                
                $customer =  Customer::where('id',$customer_id)->withTrashed()->first();
            }else{
                $customer_id = $request->customertable;
                $customer_update = Customer::where('id',$customer_id)->update(['name' => $request->name,]);
                $customer =  Customer::where('id',$customer_id)->withTrashed()->first();
            }

            if($customer_id){
                $user =  User::create([
                    'name'        => $request->name,
                    'phone'       => $request->codeSended,
                    'customer_id' => $customer_id,
                    'password'    => Hash::make($request->password),
                ]);
                $role       = DB::table('roles')->where('name','customer_user')->first();
                $role       = DB::table('role_user')->insert([
                'role_id'   => $role->id,
                'user_id'   => $user->id
                ]);
                if($request->treatment_id && $request->center_id){
                    $date = Carbon::createFromTimestamp(strtotime($request['date'] . $request['time']))->toDateTimeString();
                    $add_Treatments = DB::table('customer_procedures')->INSERT([
                        'customer_id'       =>  $customer_id,
                        'treatments_id'     =>  $request->treatment_id,
                        'hospital_id'       =>  $request->center_id,
                        'doctor_id'         =>  $request->doctor_id,
                        'status'            =>  4,
                        'cost'              =>  0,
                        'discounted_cost'   =>  0,
                        'appointment_date'  =>  $date,
                        'appointment_from'  =>  1,
                    ]);
                    if($customer_id && isset($request->doctor_id)){
                        $doctor_view = DB::table('doctor_views')->where('doctor_id',$request->doctor_id)->where('customer_id',$customer_id)->where('view_from',0)->first();
                        if(!$doctor_view){
                            $insert = DB::table('doctor_views')->insert([
                                'doctor_id'         => $request->doctor_id,
                                'customer_id'       => isset($customer_id)?$customer_id:null,
                                'view_from'         => 0,
                                'viewed_or_booked'  => 1,
                            ]);
                        }else{
                            $insert = $doctor_view->update(['viewed_or_booked'  => 1]);
                        }
                    }
                    $customer_phone         = $customer->phone;                                              // Get Customer phone number
                    $customer_name          = $customer->name;
                    $with                   = doctorName($request->doctor_id);
                    $at                     = centerName($request->center_id);
                    $location               = centerlocation($request->center_id);
                    $map                    = centerMap($request->center_id);
                    $date                   = Carbon::parse($request->date);                                                // Appointment date
                    $fdate                  = $date->format('jS F Y');
                    $time                   = $date->format('h:i A');
                    $n                      = '\n';
                    if(isset($customer_phone)){
                                                                                                                            // send message to customer
                        $message        = "Dear+$customer_name,".$n.$n."Thank+you+for+booking+an+appointment+through+HospitALL.".$n.$n."Our+Customer+Care+will+approve+your+appointment+with+$with+after+the+confirmation.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Location:+$location".$n."Google+Map:+$map".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies.+You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com".$n."For+Queries:+0322-2555600,".$n."0322-2555400";
                        $sms            = CustomerAppointmentSms($message, $customer_phone);
                    }
                }
            $token = $user->createToken('user')->accessToken;
            }
            $customer_phone_validation =  DB::table('customer_phone_verification')->where('phone',$request->codeSended)->where('code',$request->code)->delete();
            return response()->json(['customer' => $customer, 'access_token' =>$token], 200);
        }
        return response()->json(['message' => 'Enter valid data'], 404);
    }


    public function signIn(Request $request){
        $validate = $request->validate([
            'phone'     =>'required',
            'password'  => 'required|string|min:6',
        ]);

        $request['phone']    = formatPhone($request->phone);
        $usertable = User::where('phone',$request->phone)->whereNotNull('customer_id')->first();
        $customer  = Customer::where('phone',$request->phone)->first();
        $credentials = [
            'phone'     => $request->phone,
            'password'  => $request->password,
            'customer_id'  => isset($usertable)?$usertable->customer_id:'',
        ];
            if (auth()->attempt($credentials) && $usertable && $customer) {
                $customer_id= Auth::user()->customer_id;
                $user= Auth::user();
                if($request->treatment_id && $request->center_id){
                    $date = Carbon::createFromTimestamp(strtotime($request['date'] . $request['time']))->toDateTimeString();
                    $add_Treatments = DB::table('customer_procedures')->INSERT([
                        'customer_id'       =>  $customer_id,
                        'treatments_id'     =>  $request->treatment_id,
                        'hospital_id'       =>  $request->center_id,
                        'doctor_id'         =>  $request->doctor_id,
                        'status'            =>  4,
                        'cost'              =>  0,
                        'discounted_cost'   =>  0,
                        'appointment_date'  =>  $date,
                        'appointment_from'  =>  1,
                    ]);
                    $customer_phone         = $customer->phone;                                              // Get Customer phone number
                    $customer_name          = $customer->name;
                    $with                   = doctorName($request->doctor_id);
                    $at                     = centerName($request->center_id);
                    $location               = centerlocation($request->center_id);
                    $map                    = centerMap($request->center_id);
                    $date                   = Carbon::parse($request->date);                                                // Appointment date
                    $fdate                  = $date->format('jS F Y');
                    $time                   = $date->format('h:i A');
                    $n                      = '\n';
                    if(isset($customer_phone)){
                                                                                                                            // send message to customer
                        $message        = "Dear+$customer_name,".$n.$n."Thank+you+for+booking+an+appointment+through+HospitALL.".$n.$n."Our+Customer+Care+will+approve+your+appointment+with+$with+after+the+confirmation.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Location:+$location".$n."Google+Map:+$map".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies.+You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com".$n."For+Queries:+0322-2555600,".$n."0322-2555400";
                        $sms            = CustomerAppointmentSms($message, $customer_phone);
                    }
                    if(isset($user)){
                        $fcm_device = DB::table('f_c_m_devices')->where('user_id',$user->id)->first();
                        if(isset($fcm_device)){
                            $message="Your Appointment with $with is on $date and at $at";
                            NotificationHelper::GENERATE([
                                'title' => 'Pending Appointment',
                                'body' => $message,
                                'payload' => [
                                    'type' => "Pending Appointment"
                                ]
                            ],$user->id);
                        }
                    }
                }
                if($customer_id && isset($request->doctor_id)){
                    $doctor_view = DB::table('doctor_views')->where('doctor_id',$request->doctor_id)->where('customer_id',$customer_id)->where('view_from',0)->first();
                    if(!$doctor_view){
                        $insert = DB::table('doctor_views')->insert([
                            'doctor_id'         => $request->doctor_id,
                            'customer_id'       => isset($customer_id)?$customer_id:null,
                            'view_from'         => 0,
                            'viewed_or_booked'  => 1,
                        ]);
                    }else{
                        $doctor_view = DB::table('doctor_views')->where('doctor_id',$request->doctor_id)->where('customer_id',$customer_id)->where('view_from',0)->update(['viewed_or_booked'  => 1]);
                    }
                }
                $token = auth()->user()->createToken('user')->accessToken;
                return response()->json(['customer'=>$customer, "access_token" =>$token], 200);
            } else {
                return response()->json(['message' => 'Wrong phone number or Password'], 404);
            }
    }
    public function book_appointment(Request $request)
    {
        $customer = Customer::where('id',$request->customer_id)->first();
        if($request->treatment_id && $request->center_id && $customer){
            $date = Carbon::createFromTimestamp(strtotime($request['date'] . $request['time']))->toDateTimeString();
            $add_Treatments = DB::table('customer_procedures')->INSERT([
                'customer_id'       =>  $request->customer_id,
                'treatments_id'     =>  $request->treatment_id,
                'hospital_id'       =>  $request->center_id,
                'doctor_id'         =>  $request->doctor_id,
                'status'            =>  4,
                'cost'              =>  0,
                'discounted_cost'   =>  0,
                'appointment_date'  =>  $date,
                'appointment_from'  =>  1,
            ]);
            $customer_phone         = $customer->phone;                                              // Get Customer phone number
            $customer_name          = $customer->name;
            $with                   = doctorName($request->doctor_id);
            $at                     = centerName($request->center_id);
            $location               = centerlocation($request->center_id);
            $map                    = centerMap($request->center_id);
            $date                   = Carbon::parse($request->date);                                                // Appointment date
            $fdate                  = $date->format('jS F Y');
            $time                   = $date->format('h:i A');
            $n                      = '\n';
            if(isset($customer_phone)){
                                                                                                                    // send message to customer
                $message        = "Dear+$customer_name,".$n.$n."Thank+you+for+booking+an+appointment+through+HospitALL.".$n.$n."Our+Customer+Care+will+approve+your+appointment+with+$with+after+the+confirmation.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Location:+$location".$n."Google+Map:+$map".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies.+You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com".$n."For+Queries:+0322-2555600,".$n."0322-2555400";
                $sms            = CustomerAppointmentSms($message, $customer_phone);
            }
            $user = User::where('customer_id',$customer->id)->first();
            if(isset($user)){
                $fcm_device = DB::table('f_c_m_devices')->where('user_id',$user->id)->first();
                if(isset($fcm_device)){
                    $message="Your Appointment with $with is on $date and at $at";
                    NotificationHelper::GENERATE([
                        'title' => 'Pending Appointment',
                        'body' => $message,
                        'payload' => [
                            'type' => "Pending Appointment"
                        ]
                    ],$user->id);
                }
            }

            return response()->json(['message'=>$date], 200);
        } else {
            return response()->json(['message' => 'Wrong Email or Password'], 401);
        }
    }
    public function logout(Request $request)
    {
        return response()->json(['data' =>"User Logout Successfully" ], 200); // modify as per your need
    }

    public function forgetPhoneVarification(Request $request){

        $validate = $request->validate([
            'phone'       => 'required',
        ]);
        $request['phone']    = formatPhone($request->phone);

        $phone_check    = Customer::where('phone',$request->phone)->withTrashed()->first();
        $usertable      = User::where('phone',$request->phone)->whereNotNull('customer_id')->withTrashed()->first();

        if (isset($phone_check) && isset($usertable)) {

            $phone_check    = DB::table('customer_phone_verification')->where('phone', $request->phone)->first();
            if (isset($phone_check)) {
                DB::table('customer_phone_verification')->where('phone', $request->phone)->delete();
            }
            $code = random_int(100000, 999999);
            $sms = "$code+is+your+HospitALL+account+verification+code.+This+code+will+expire+in+10+minutes";
            $code_sended = CustomerAppointmentSms($sms , $request->phone);

            if ($code_sended) {
                $customer_phone_verification =  DB::table('customer_phone_verification')->insert([
                'phone'         =>  $request->phone,
                'code'          =>  $code,
                ]);

                return response()->json(['codeSended' => $request->phone], 200);
            } else {
                return response()->json(['message' => 'Enter valid data'], 401);
            }
        }else{
            return response()->json(['registor' => 'please register first'], 200);
        }

    }
    public function newPassword( Request $request){
        $validate = $request->validate([
            'password'       => 'required',
        ]);
        $phone = $request->codeSended;
        $password = $request->password;
        $user = User::where('phone',$phone)->first();
        if(isset($user)){
            $update_user = $user->update([
                "password" => Hash::make($password),
            ]);
            if($update_user){
                return response()->json(['data' => "User data updated"], 200);
            }else{
            return response()->json(['message' => 'Wrong Email or Password'], 401);
            }
        }
        return response()->json(['message' => 'Wrong Email or Password'], 401);
    }

    public function sendFeedback(Request $request)
    {
        $validate       =    $request->validate([
            'name'      =>   'required',
            'phone'     =>   'required',
            'email'     =>   'sometimes',
            'message'   =>   'required',
        ]);
        $store  =   DB::table('feedbacks')->insert([
            'name'          =>   $request->name,
            'phone'         =>   $request->phone,
            'email'         =>   $request->email,
            'message'       =>   $request->message,
            'created_at'    =>   Carbon::now()->toDateTimeString(),
            'updated_at'    =>   Carbon::now()->toDateTimeString(),
        ]);
        if ($store) {
            return response()->json(['message' => 'Your Feedback has been sent Successfully!'], 200);

        } else {
            return response()->json(['message' => 'Invalid Data'], 401);
        }
    }
}
