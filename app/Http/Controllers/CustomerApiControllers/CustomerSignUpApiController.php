<?php

namespace App\Http\Controllers\CustomerApiControllers;

use App\FCMDevice;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Admin\Doctor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\DoctorResource;
use App\Models\Admin\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CustomerSignUpApiController extends Controller
{
    public function unique_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
    public function signup(Request $request){

        $validate = $request->validate([
            'name'              => 'required|string|min:3|max:255',
            'email'             => 'required|string|max:255',
            'password'          => 'required|string|min:6',
            'gender'            => 'required',
            'marital_status'    => 'required',
            'phone'             => 'required',
            'fcm_token'         => 'required',
        ]);
        $request['phone'] = formatPhone($request->phone);
        
        $customer    = DB::table('customers')->where('email',$request->email)->first();
        $user        = DB::table('users')->where('email',$request->email)->whereNotNull('customer_id')->first();
        if(isset($customer) || isset($user)){
            return response()->json(['changeemail'=>'Email already exists'],401);
        }
        $customer    = DB::table('customers')->where('phone',$request->phone)->first();
        $user        = DB::table('users')->where('phone',$request->phone)->whereNotNull('customer_id')->first();

        if(isset($customer) && !$user ){
            DB::table('customer_phone_verification')->where('customer_id',$customer->id)->delete();
            $customer_phone_validation =  DB::table('customer_phone_verification')->where('customer_id',$customer->id)->first();
            if(!$customer_phone_validation){
                $code = random_int(100000, 999999);
                $sms = "You+verification+code+is+$code";
                $code_sended = CustomerAppointmentSms($sms , $customer->phone);
                if ($sms) {
                    $phone_validation =  DB::table('customer_phone_verification')->insert([
                    'customer_id'   =>  $customer->id,
                    'phone'         =>  $customer->phone ,
                    'code'          =>  $code,
                    ]);    
                }
            }
            return response()->json(['createpassword' => 'Code Sent Successfully!','customer_id'=>$customer->id], 401);
        }
        if(isset($customer) || isset($user)){
            return response()->json(['message'=>'Phone already exists'],401);
        }

            $customer = Customer::create([
                'ref'           => $this->unique_code(4),
                'name'          =>  $request->name,
                'email'         =>  $request->email,
                'gender'        =>  $request->gender,
                'marital_status'=>  $request->marital_status,
                'phone'         =>  $request->phone,
                'status_id'     => 11,
                'customer_lead' => 3,
                'created_at'    => Carbon::now()->toDateTimeString(),
                'updated_at'    => Carbon::now()->toDateTimeString(),
            ]);
            if ($customer) {
                $user = User::create([
                    'name'          => $request->name,
                    'email'         => $request->email,
                    'password'      => Hash::make($request->password),
                    'customer_id'   => $customer->id,
                    'phone'         => $request->phone,
                ]);
                $role           = DB::table('roles')->where('name', 'customer_user')->first();
                $role           = DB::table('role_user')->insert([
                'role_id'   => $role->id,
                'user_id'   => $user->id
                ]);
                $user_id    = $user->id;
                $device     = FCMDevice::where([
                    ['user_id',$user_id],
                    ['token', $request->fcm_token],
                ])->first();

                if (!$device) {
                    FCMDevice::create([
                        'user_id' => $user_id,
                        'token' => $request->fcm_token,
                    ]);
                }
                $customer->user_id = $user_id;
                $token = $user->createToken('user')->accessToken;
                if(isset($customer) && $user ){
                    $code = random_int(100000, 999999);
                    $sms = "You+verification+code+is+$code";
                    $code_sended = CustomerAppointmentSms($sms , $customer->phone);
                    if ($sms) {
                        $phone_validation =  DB::table('customer_phone_verification')->insert([
                        'customer_id'   =>  $customer->id,
                        'phone'         =>  $customer->phone ,
                        'code'          =>  $code,
                        ]);
                        return response()->json(['token' => $token,'data' => $customer,'user' =>$user], 200);
                    } else {
                        return response()->json(['message' => 'Enter valid data'], 401);
                    }
                }
                return response()->json(['token' => $token,'data' => $customer,'user' =>$user], 200);
            } else {
                return response()->json(['message' => 'Enter valid data'], 401);
            }
    }
    public function signUpWithGoogle(Request $request){

        $validate = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'sometimes',
            'google_id'         => 'required',
            'phone'             => 'required',
            'fcm_token'         => 'required',
        ]);
        $request['phone'] = formatPhone($request->phone);

        $user      = DB::table('users')->where('email',$request->email)->whereNotNull('customer_id')->Where('google_id',$request->google_id)->first();
       
        $customer  = DB::table('customers')->where('email',$request->email)->first();
        // dd($customer);
        if($user || $customer){
            return response()->json(['message' => 'Email already exists'], 401);
        }

        $customer    = DB::table('customers')->where('phone',$request->phone)->first();
        $user        = DB::table('users')->where('phone',$request->phone)->whereNotNull('customer_id')->first();

        if(isset($customer) && !$user ){
            $customer_phone_validation =  DB::table('customer_phone_verification')->where('customer_id',$customer->id)->first();
            if(!$customer_phone_validation){
                $code = random_int(100000, 999999);
                $sms = "You+verification+code+is+$code";
                $code_sended = CustomerAppointmentSms($sms , $customer->phone);
                if ($sms) {
                    $phone_validation =  DB::table('customer_phone_verification')->insert([
                    'customer_id'   =>  $customer->id,
                    'phone'         =>  $customer->phone ,
                    'code'          =>  $code,
                    ]);    
                }
            }
            return response()->json(['createpassword' => 'Code Sent Successfully!','customer_id'=>$customer->id], 401);
        }
        if($user || $customer){
            return response()->json(['message' => 'phone already exists'], 401);
        }
        $customer = Customer::create([
            'ref'           => $this->unique_code(4),
            'name'          =>  $request->name,
            'email'         =>  $request->email,
            'phone'         =>  $request->phone,
            'status_id'     => 11,
            'customer_lead' => 3,
            'created_at'    => Carbon::now()->toDateTimeString(),
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        if ($customer) {
            $user = User::create([
                'name'          => $request->name,
                'email'         => $request->email,
                'password'      => Hash::make($request->google_id),
                'google_id'     => $request->google_id,
                'customer_id'   => $customer->id,
                'phone'         => $request->phone,
            ]);
            $role           = DB::table('roles')->where('name', 'customer_user')->first();
            $role           = DB::table('role_user')->insert([
            'role_id'   => $role->id,
            'user_id'   => $user->id
            ]);
            $user_id    = $user->id;
            $device     = FCMDevice::where([
                ['user_id',$user_id],
                ['token', $request->fcm_token],
            ])->first();

            if (!$device) {
                FCMDevice::create([
                    'user_id' => $user_id,
                    'token' => $request->fcm_token,
                ]);
            }
            $customer->user_id = $user_id;
            $token = $user->createToken('user')->accessToken;
            if(isset($customer) && $user ){
                $code = random_int(100000, 999999);
                $sms = "You+verification+code+is+$code";
                $code_sended = CustomerAppointmentSms($sms , $customer->phone);
                if ($sms) {
                    $phone_validation =  DB::table('customer_phone_verification')->insert([
                    'customer_id'   =>  $customer->id,
                    'phone'         =>  $customer->phone ,
                    'code'          =>  $code,
                    ]);
                    return response()->json(['token' => $token,'data' => $customer,'user' =>$user], 200);
                } else {
                    return response()->json(['message' => 'Enter valid data'], 401);
                }
            }
            return response()->json(['token' => $token,'data' => $customer,'user' =>$user], 200);
        } else {
            return response()->json(['message' => 'Enter valid data'], 401);
        }
    }
    public function signUpWithFacebook(Request $request){

        $validate = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'sometimes',
            'facebook_id'       => 'required',
            'phone'             => 'required',
            'fcm_token'         => 'required',
        ]);
        $request['phone'] = formatPhone($request->phone);

        $user      = DB::table('users')->where('email',$request->email)->whereNotNull('customer_id')->Where('facebook_id',$request->facebook_id)->first();
        $customer  = DB::table('customers')->where('email',$request->email)->first();
        if($user || $customer){
            return response()->json(['message' => 'Email already exists'], 401);
        }
        $customer    = DB::table('customers')->where('phone',$request->phone)->first();
        $user        = DB::table('users')->where('phone',$request->phone)->whereNotNull('customer_id')->first();

        if(isset($customer) && !$user ){
            $customer_phone_validation =  DB::table('customer_phone_verification')->where('customer_id',$customer->id)->first();
            if(!$customer_phone_validation){
                $code = random_int(100000, 999999);
                $sms = "You+verification+code+is+$code";
                $code_sended = CustomerAppointmentSms($sms , $customer->phone);
                if ($sms) {
                    $phone_validation =  DB::table('customer_phone_verification')->insert([
                    'customer_id'   =>  $customer->id,
                    'phone'         =>  $customer->phone ,
                    'code'          =>  $code,
                    ]);    
                }
            }
            return response()->json(['createpassword' => 'Code Sent Successfully!','customer_id'=>$customer->id], 401);
        }
        if($user || $customer){
            return response()->json(['message' => 'Phone already exists'], 401);
        }
        $customer = Customer::create([
            'ref'           => $this->unique_code(4),
            'name'          =>  $request->name,
            'email'         =>  $request->email,
            'phone'         =>  $request->phone,
            'status_id'     =>  11,
            'customer_lead' =>  3,
            'created_at'    => Carbon::now()->toDateTimeString(),
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        if ($customer) {
            $user = User::create([
                'name'          => $request->name,
                'email'         => $request->email,
                'password'      => Hash::make($request->facebook_id),
                'facebook_id'   => $request->facebook_id,
                'customer_id'   => $customer->id,
                'phone'         => $request->phone,
            ]);
            $role           = DB::table('roles')->where('name', 'customer_user')->first();
            $role           = DB::table('role_user')->insert([
            'role_id'   => $role->id,
            'user_id'   => $user->id
            ]);
            $user_id    = $user->id;
            $device     = FCMDevice::where([
                ['user_id',$user_id],
                ['token', $request->fcm_token],
            ])->first();

            if (!$device) {
                FCMDevice::create([
                    'user_id' => $user_id,
                    'token' => $request->fcm_token,
                ]);
            }
            $customer->user_id = $user_id;
            $token = $user->createToken('user')->accessToken;
            if(isset($customer) && $user ){
                $code = random_int(100000, 999999);
                $sms = "You+verification+code+is+$code";
                $code_sended = CustomerAppointmentSms($sms , $customer->phone);
                if ($sms) {
                    $phone_validation =  DB::table('customer_phone_verification')->insert([
                    'customer_id'   =>  $customer->id,
                    'phone'         =>  $customer->phone ,
                    'code'          =>  $code,
                    ]);
                    return response()->json(['token' => $token,'data' => $customer,'user' =>$user], 200);
                } else {
                    return response()->json(['message' => 'Enter valid data'], 401);
                }
            }
            return response()->json(['token' => $token,'data' => $customer,'user' =>$user], 200);
        } else {
            return response()->json(['message' => 'Enter valid data'], 401);
        }
    }

    public function customerPhoneVerification(Request $request){
        $id     = Auth::user()->customer_id;
        $validate = $request->validate([
            'code'             => 'required|min:6|max:6',
        ]);
        $customer_phone_validation =  DB::table('customer_phone_verification')->where('customer_id',$id)->where('code',$request->code)->first();
        if($customer_phone_validation){
            $customer = Customer::where('id', $id)->first();
          $update_user =  $customer->where('id', $id)->update([
                'phone_verified'=> 1,
            ]);
            if($update_user){
                $message  = "Your phone number is verified for CustomerALL App";
                NotificationHelper::GENERATE([
                    'title' => 'Phone Verification',
                    'body' => $message,
                    'payload' => [
                        'type' => "Phone verification"
                    ]
                ],[$id]);
            }
            $custoemr_phone_validation =  DB::table('customer_phone_verification')->where('customer_id',$id)->where('code',$request->code)->delete();
            return response()->json(['message' => 'Code is verified'], 200);
        }
        return response()->json(['message' => 'Enter valid code'], 404);
    }
    public function resendCode(Request $request){
        
        $id = isset($request->customer_id)? $request->customer_id :null;
        $customer = Customer::where('id',$id)->first();
        DB::table('customer_phone_verification')->where('customer_id',$id)->delete();
        if(isset($customer)){
            $code = random_int(100000, 999999);
            $sms = "You+verification+code+is+$code";
            $code_sended = CustomerAppointmentSms($sms , $customer->phone);
            if ($sms) {
                $phone_validation =  DB::table('customer_phone_verification')->insert([
                'customer_id'   =>  $customer->id,
                'phone'         =>  $customer->phone ,
                'code'          =>  $code,
                ]);
                return response()->json(['message' => "Code send successfully"], 200);
            } else {
                return response()->json(['message' => 'Enter valid data'], 401);
            }
        }
    }
    public function forgetSendPhoneCode(Request $request){

        $validate = $request->validate([
            'phone'       => 'required',
        ]);
        $request['phone']    = formatPhone($request->phone);

        $customer = Customer::where('phone', $request->phone)->first();
        $user     = User::where('phone', $request->phone)->first();
        if(isset($customer) && !$user ){
            $code = random_int(100000, 999999);
            $sms = "You+verification+code+is+$code";
            $code_sended = CustomerAppointmentSms($sms , $customer->phone);
            if ($sms) {
                $phone_validation =  DB::table('customer_phone_verification')->insert([
                'customer_id'   =>  $customer->id,
                'phone'         =>  $customer->phone ,
                'code'          =>  $code,
                ]);

                return response()->json(['createpassword' => 'Code Sent Successfully!','customer_id'=>$customer->id], 200);
            } else {
                return response()->json(['message' => 'Enter valid data'], 401);
            }
        }
        if(!$customer || !$user){
            return response()->json(['message' => 'Please, Signup first to continue'], 401);
        }
        if ($customer && $user) {
            $code = random_int(100000, 999999);
            $sms = "Your+verification+code+is+$code";
            $code_sended = CustomerAppointmentSms($sms , $request->phone);
            if ($code_sended) {
                $customer_phone_validation =  DB::table('customer_phone_verification')->insert([
                'customer_id'   =>  $customer->id,
                'phone'         =>  $request->phone ,
                'code'          =>  $code,
                ]);
                return response()->json(['message' => 'Code Sent Successfully!','customer_id'=> $customer->id], 200);
            } else {
                return response()->json(['message' => 'Enter valid phone number'], 401);
            }
        }
        return response()->json(['message' => 'Phone number not exist'], 401);
    }

    public function forgetPhoneVerification(Request $request){

        $validate = $request->validate([
            'code'             => 'required',
        ]);

        $customer_phone_validation =  DB::table('customer_phone_verification')->where('code',$request->code)->first();
        if($customer_phone_validation){
            $url_code = random_int(100000000, 999999999);
            $phone  = $customer_phone_validation->phone;
            $customer = Customer::where('phone', $phone)->update([
                'reset_password' => $url_code,
            ]);
            $url = "https://support.hospitallcare.com/forget_password/$url_code";
            $sms = 'To+change+your+password,+please+follow+this+link+\n'.$url;
            $code_sended = CustomerAppointmentSms($sms , $phone);
            $doctor_phone_validation =  DB::table('customer_phone_verification')->where('code',$request->code)->delete();

            return response()->json(['message' => 'Link is send to your phone number. Follow this link to change password'], 200);
        }
        return response()->json(['message' => 'Enter valid data'], 401);
    }
}
