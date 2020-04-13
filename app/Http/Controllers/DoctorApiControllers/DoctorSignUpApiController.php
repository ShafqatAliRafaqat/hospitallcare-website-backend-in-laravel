<?php

namespace App\Http\Controllers\DoctorApiControllers;

use App\FCMDevice;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Admin\Doctor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\DoctorResource;
use Illuminate\Support\Facades\Auth;
class DoctorSignUpApiController extends Controller
{

    public function doctorSignUp(Request $request){
        $validate = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|string|max:255',
            'password'          => 'required|string|min:6',
        ]);
        $users = User::where('email', $request->email)->whereNotNull('doctor_id')->first();
        if($users){
            return response()->json(['message' => 'Email already exists'], 404);
        }
        if (!$users) {
            $doctor = Doctor::create([
            'name'          =>  $request->name,
            'last_name'     =>  $request->last_name,
            'email'         =>  $request->email,
            'address'       =>  'Lahore',
            'lat'           =>  31.5204,
            'lng'           =>  74.3587,
        ]);
            if ($doctor) {
                $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  =>Hash::make($request->password),
                'doctor_id' => $doctor->id,
            ]);
                $role           = DB::table('roles')->where('name', 'doctor_admin')->first();
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
                $token = $user->createToken('user')->accessToken;
                return response()->json(['token' => $token,'data' => $doctor,'user' =>$user], 200);
            } else {
                return response()->json(['message' => 'Enter valid data'], 401);
            }
        }else{
            return response()->json(['message' => 'Email already exists'], 404);
        }
    }
    public function doctorSignUpWithGoogle(Request $request){

        $validate = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|string|email|max:255',
            'google_id'         => 'required',
        ]);
        $users = User::where('email', $request->email)->whereNotNull('doctor_id')->first();
        if($users){
            return response()->json(['message' => 'Email already exists'], 404);
        }
        if (!$users) {
        $doctor = Doctor::create([
            'name'          =>  $request->name,
            'email'         =>  $request->email,
            'address'       =>  'Lahore',
            'lat'           =>  31.5204,
            'lng'           =>  74.3587,
        ]);
        if($doctor){

            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'google_id' => $request->google_id,
                'password'  => Hash::make($request->google_id),
                'doctor_id' => $doctor->id,
            ]);
            $role           = DB::table('roles')->where('name','doctor_admin')->first();
            $role           = DB::table('role_user')->insert([
                'role_id'   => $role->id,
                'user_id'   => $user->id
                ]);
                $user_id    = $user->id;
                $device     = FCMDevice::where([
                    ['user_id',$user_id],
                    ['token', $request->fcm_token],
                ])->first();

                if(!$device){
                    FCMDevice::create([
                        'user_id' => $user_id,
                        'token' => $request->fcm_token,
                    ]);
                }
                $token = $user->createToken('user')->accessToken;
                return response()->json(['token' => $token,'data' => $doctor,'user' =>$user], 200);
        }else {
            return response()->json(['message' => 'Enter valid data'], 401);
        }
    }else{
        return response()->json(['message' => 'Email already exists'], 404);
    }
    }
    public function doctorSignUpWithFacebook(Request $request){

        $validate = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'sometimes',
            'facebook_id'       => 'required',
        ]);
        $users = User::where('facebook_id', $request->facebook_id)->whereNotNull('doctor_id')->first();
        if($users){
            return response()->json(['message' => 'Email already exists'], 404);
        }
        if (!$users) {
        $doctor = Doctor::create([
            'name'          =>  $request->name,
            'email'         =>  $request->email,
            'address'       =>  'Lahore',
            'lat'           =>  31.5204,
            'lng'           =>  74.3587,
        ]);
        if($doctor){

            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'facebook_id'=> $request->facebook_id,
                'password'  => Hash::make($request->facebook_id),
                'doctor_id' => $doctor->id,
            ]);
            $role           = DB::table('roles')->where('name','doctor_admin')->first();
            $role           = DB::table('role_user')->insert([
                'role_id'   => $role->id,
                'user_id'   => $user->id
                ]);
                $user_id    = $user->id;
                $device     = FCMDevice::where([
                    ['user_id',$user_id],
                    ['token', $request->fcm_token],
                ])->first();

                if(!$device){
                    FCMDevice::create([
                        'user_id' => $user_id,
                        'token' => $request->fcm_token,
                    ]);
                }
                $token = $user->createToken('user')->accessToken;
                return response()->json(['token' => $token,'data' => $doctor,'user' =>$user], 200);
        }else {
            return response()->json(['message' => 'Enter valid data'], 401);
        }
        }else{
            return response()->json(['message' => 'Email already exists'], 404);
        }
    }

    public function sendPhoneCode(Request $request,$id){
        $validate = $request->validate([
            'phone'       => 'required',
        ]);
        $id     = Auth::user()->doctor_id;
        $request['phone']           =  '0'.$request->phone;
        $phone_check    = Doctor::where('phone', $request->phone)->first();
        if (isset($phone_check)) {
            return response()->json(['message' => 'Phone number already Exists!'], 401);
        }

        $doctor= Doctor::where('id', $id)->first();
        if ($doctor) {
            $code = random_int(100000, 999999);
            $sms = "You+verification+code+is+$code";
            $code_sended = CustomerAppointmentSms($sms , $request->phone);
            if ($code_sended) {
                $doctor_phone_validation =  DB::table('doctor_phone_verification')->insert([
                'doctor_id'     =>  $doctor->id,
                'phone'         =>  $request->phone ,
                'code'          =>  $code,
                ]);

                return response()->json(['message' => 'Code Sent Successfully!'], 200);
            } else {
                return response()->json(['message' => 'Enter valid data'], 401);
            }
        }
        return response()->json(['message' => 'There is no doctor'], 404);
    }

    public function doctorPhoneVerification(Request $request, $id){
        $id     = Auth::user()->doctor_id;
        $user_id= Auth::user()->id;
        $validate = $request->validate([
            'code'             => 'required',
        ]);
        $doctor_phone_validation =  DB::table('doctor_phone_verification')->where('doctor_id',$id)->where('code',$request->code)->first();
        if($doctor_phone_validation){
            $doctor= Doctor::where('id', $id)->first();
          $update_user =  $doctor->where('id', $id)->update([
                'phone_verified'=> 1,
                'phone'         => $doctor_phone_validation->phone,
            ]);
          $update_user_phone =  User::where('id', $user_id)->update([
                'phone'         => $doctor_phone_validation->phone,
            ]);
            if($update_user){
                $message  = "Your phone number is verified for DoctorALL App";
                NotificationHelper::GENERATE([
                    'title' => 'Phone Verification',
                    'body' => $message,
                    'payload' => [
                        'type' => "Phone verification"
                    ]
                ],[$id]);
            }
            $doctor_phone_validation =  DB::table('doctor_phone_verification')->where('doctor_id',$id)->where('code',$request->code)->delete();
            return response()->json(['message' => 'Code is verified'], 200);
        }
        return response()->json(['message' => 'Enter valid data'], 404);
    }
    public function forgetSendPhoneCode(Request $request){

        $request['phone']           =  '0'.$request->phone;
        $validate = $request->validate([
            'phone'       => 'required',
        ]);
        $doctor= Doctor::where('phone', $request->phone)->first();
        if ($doctor) {
            $code = random_int(100000, 999999);
            $sms = "Your+verification+code+is+$code";
            $code_sended = CustomerAppointmentSms($sms , $request->phone);
            if ($code_sended) {
                $doctor_phone_validation =  DB::table('doctor_phone_verification')->insert([
                'doctor_id'     =>  $doctor->id,
                'phone'         =>  $request->phone ,
                'code'          =>  $code,
                ]);
                return response()->json(['message' => 'Code Sent Successfully!'], 200);
            } else {
                return response()->json(['message' => 'Enter valid phone number'], 401);
            }
        }
        return response()->json(['message' => 'Phone number does not exist'], 401);
    }

    public function forgetPhoneVerification(Request $request){

        $validate = $request->validate([
            'code'             => 'required',
        ]);
        $code = $request->code;
        $doctor_phone_validation =  DB::table('doctor_phone_verification')->where('code',$request->code)->first();
        if($doctor_phone_validation){
            $url_code = random_int(100000000, 999999999);
            $phone  = $doctor_phone_validation->phone;
            $doctor = Doctor::where('phone', $phone)->update([
                'reset_password' => $url_code,
            ]);
            $url = "https://support.hospitallcare.com/forget_password/$url_code";
            $sms = "To+change+your+password,+please+follow+this+link+\n$url";
            $code_sended = CustomerAppointmentSms($sms , $phone);
            $doctor_phone_validation =  DB::table('doctor_phone_verification')->where('code',$request->code)->delete();

            return response()->json(['message' => 'Code is verified'], 200);
        }
        return response()->json(['message' => 'Enter valid data'], 401);
    }
}
