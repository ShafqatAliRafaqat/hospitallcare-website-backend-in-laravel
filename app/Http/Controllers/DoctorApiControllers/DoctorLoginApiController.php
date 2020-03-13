<?php

namespace App\Http\Controllers\DoctorApiControllers;

use App\FCMDevice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;

class DoctorLoginApiController extends Controller
{
    public function login(Request $request)
    {
        $user_with_email = User::where('email',$request->email)->first();
        if(isset($user_with_email)){
            $user = User::where('email',$request->email)->whereNotNull('doctor_id')->select('id','name','email','is_approved','doctor_id')->first();
            $emailorphone = 'email';
        }else{
            $request['email']    = formatPhone($request->email);
            $user = User::where('phone',$request->email)->whereNotNull('doctor_id')->select('id','name','email','is_approved','doctor_id')->first();
            $emailorphone = 'phone';
        }
        $credentials = [
            $emailorphone   => $request->email,
            'password'      => $request->password
        ];
        if (isset($user) && $user->doctor_id != null) {
            if (auth()->attempt($credentials)) {
                $user_id        = Auth::user()->id;
                $doctor_id      = Auth::user()->doctor_id;
                $doctor_phone   = DB::table('doctors')->where('id',$doctor_id)->where('phone_verified','1')->first();
                    if(isset($doctor_phone)){
                        $doctor_phone = "approved";
                    }else{
                        $doctor_phone = "not_approved";
                    }
                $device = FCMDevice::where([
                    ['user_id',$user_id],
                    ['token', $request->fcm_token],
                ])->first();

                if(!$device){
                    FCMDevice::create([
                        'user_id' => $user_id,
                        'token' => $request->fcm_token,
                    ]);
                }

                $doctor_image = DB::table('doctor_images')->where('doctor_id',$doctor_id)->first();
                $user['picture'] = isset($doctor_image)?'http://test.hospitallcare.com/backend/uploads/doctors/'.$doctor_image->picture:'';

                $token = auth()->user()->createToken('user')->accessToken;
                return response()->json(['doctor_phone'=>$doctor_phone,'token' => $token,'user' => $user], 200);
            } else {
                return response()->json(['message' => 'Wrong Email or Password'], 401);
            }
        } else {
            return response()->json(['message' => 'Please, Signup first to continue'], 401);
        }
    }
    public function loginWithGoogle(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->google_id
        ];
        $user = User::where('email',$request->email)->where('google_id',$request->google_id)->whereNotNull('doctor_id')->select('id','name','email','is_approved','doctor_id')->first();

        if (isset($user) && $user->doctor_id != null) {
            if (auth()->attempt($credentials)) {
                $user_id = Auth::user()->id;
                $doctor_id = Auth::user()->doctor_id;
                $doctor_phone = DB::table('doctors')->where('id',$doctor_id)->where('phone_verified','1')->first();
                    if(isset($doctor_phone)){
                        $doctor_phone = "approved";
                    }else{
                        $doctor_phone = "not_approved";
                    }
                $device = FCMDevice::where([
                    ['user_id',$user_id],
                    ['token', $request->fcm_token],
                ])->first();

                if(!$device){
                    FCMDevice::create([
                        'user_id' => $user_id,
                        'token' => $request->fcm_token,
                    ]);
                }

                $doctor_image = DB::table('doctor_images')->where('doctor_id',$doctor_id)->first();
                $user['picture'] = isset($doctor_image)?'http://test.hospitallcare.com/backend/uploads/doctors/'.$doctor_image->picture:'';

                $token = auth()->user()->createToken('user')->accessToken;
                return response()->json(['doctor_phone'=>$doctor_phone,'token' => $token,'user' => $user], 200);
            } else {
                return response()->json(['message' => 'Wrong Email or Password'], 401);
            }
        } else {
            return response()->json(['message' => 'Please, Signup first to continue'], 401);
        }
    }
    public function loginWithFacebook(Request $request)
    {
        $facebook_id = $request->facebook_id;
        $user = User::where('facebook_id',$facebook_id)->whereNotNull('doctor_id')->select('id','name','email','is_approved','doctor_id')->first();

        if (isset($user) && $user->doctor_id != null) {
            if ($user) {
                $user_id = $user->id;
                $doctor_id = $user->doctor_id;
                $doctor_phone = DB::table('doctors')->where('id',$doctor_id)->where('phone_verified','1')->first();
                    if(isset($doctor_phone)){
                        $doctor_phone = "approved";
                    }else{
                        $doctor_phone = "not_approved";
                    }
                $device = FCMDevice::where([
                    ['user_id',$user_id],
                    ['token', $request->fcm_token],
                ])->first();

                if(!$device){
                    FCMDevice::create([
                        'user_id' => $user_id,
                        'token' => $request->fcm_token,
                    ]);
                }
                $doctor_image = DB::table('doctor_images')->where('doctor_id',$doctor_id)->first();
                $user['picture'] = isset($doctor_image)?'http://test.hospitallcare.com/backend/uploads/doctors/'.$doctor_image->picture:'';

                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->accessToken;

                return response()->json(['doctor_phone'=>$doctor_phone,'token'=>$token,'user' => $user], 200);
        } else {
            return response()->json(['message' => 'Wrong Email or Password'], 401);
        }
        } else {
            return response()->json(['message' => 'Please, Signup first to continue'], 401);
        }
    }
    public function logout(Request $request)
    {
        $user = Auth::user()->token();
        $id   = Auth::user()->id;
        $device = FCMDevice::where('user_id',$id)->first();
        if(isset($device)){
            $device->delete();
        }
        $user->revoke();
        $massage = "User Logout Successfully";
        return response()->json([$massage], 200);; // modify as per your need
    }
    public function details()
    {
        $user = auth()->user();
        return UserResource::make($user);
    }
    public function is_approved()
    {
        $id     = Auth::user()->id;
        $doctor_id     = Auth::user()->doctor_id;
        $result = User::where('id',$id)->select('is_approved')->first();
        $doctor_treatments = DB::table('temp_doctor_treatment')->where('doctor_id',$doctor_id)->first();
        if(isset($doctor_treatments)){
            $professional_profile = "approved";
        }else{
            $professional_profile = "not_approved";
        }
        $doctor_profile = DB::table('doctors')->where('id',$doctor_id)->where('pmdc','!=','null')->first();
        if(isset($doctor_profile)){
            $personal_profile = "approved";
        }else{
            $personal_profile = "not_approved";
        }
        $doctor_phone = DB::table('doctors')->where('id',$doctor_id)->where('phone_verified','1')->first();
        if(isset($doctor_phone)){
            $doctor_phone = "approved";
        }else{
            $doctor_phone = "not_approved";
        }
        $doctor_centers = DB::table('center_doctor_schedule')->where('doctor_id',$doctor_id)->first();
        if(isset($doctor_centers)){
            $practice_profile = "approved";
        }else{
            $practice_profile = "not_approved";
        }
        return response()->json(['doctor_phone'=>$doctor_phone,'is_approved'=>$result->is_approved, 'practice_profile'=>$practice_profile,'personal_profile'=>$personal_profile,'professional_profile'=>$professional_profile], 200);
    }

}
