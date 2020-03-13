<?php

namespace App\Http\Controllers\CustomerApiControllers;

use App\FCMDevice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Models\Admin\Customer;
use App\Models\Admin\CustomerImages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerLoginApiController extends Controller
{
    public function login(Request $request)
    {
        $customer    = DB::table('customers')->where('email',$request->email)->first();
        
        if(isset($customer)){
            $user     = DB::table('users')->where('email',$request->email)->whereNotNull('customer_id')->select('id','name','email','phone','is_approved','customer_id')->first();
            $emailorphone = 'email';
        }else{
            $request['email'] = formatPhone($request->email);
            $customer         = Customer::where('phone',$request->email)->first();
            $user             = User::where('phone',$request->email)->whereNotNull('customer_id')->select('id','name','email','phone','is_approved','customer_id')->first();
            $emailorphone     = 'phone';
        }

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
        
        $credentials = [
            $emailorphone   => $request->email,
            'password'      => $request->password,
            'customer_id'   => isset($user)?$user->customer_id:"",
        ];
        
        if (isset($user) && $user->customer_id != null) {          
            if (auth()->attempt($credentials)) {
               
                $user_id          = Auth::user()->id;
                $customer_id      = Auth::user()->customer_id;
                $customer         = DB::table('customers')->where('id',$customer_id)->select('id','name','email','gender','marital_status','phone','phone_verified','organization_id','employee_code','org_verified')->first();
                if($customer->phone_verified == 1){
                        $customer_phone = "approved";
                    }else{
                        $customer_phone = "not_approved";
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
                $customer_image = DB::table('customer_images')->where('customer_id',$customer_id)->first();
                $customer->customer_id = $customer->id;
                $customer->picture = isset($customer_image)?'http://test.hospitallcare.com/backend/uploads/customers/'.$customer_image->picture:'';
                $token = auth()->user()->createToken('user')->accessToken;
                return response()->json(['customer_phone'=>$customer_phone,'token' => $token,'data' => $customer], 200);
            } else {
                return response()->json(['message' => 'Wrong Email or Password'], 401);
            }
        } else {
            return response()->json(['message' => 'Please, Signup first to continue'], 401);
        }
    }
    public function loginWithGoogle(Request $request)
    {
        $validate = $request->validate([
            'email'          => 'required',
            'google_id'      => 'required',
            'fcm_token'      => 'required',
        ]);
        
        $user = User::where('email',$request->email)->where('google_id',$request->google_id)->whereNotNull('customer_id')->select('id','name','email','phone','is_approved','customer_id')->first();
        $credentials = [
            'email'         => $request->email,
            'password'      => $request->google_id,
            'customer_id'   => isset($user)?$user->customer_id:"",
        ];
        if (isset($user) && $user->customer_id != null) {
            if (auth()->attempt($credentials)) {
                $user_id = Auth::user()->id;
                $customer_id = Auth::user()->customer_id;
                $customer = DB::table('customers')->where('id',$customer_id)->select('id','name','email','gender','marital_status','phone','phone_verified','organization_id','employee_code','org_verified')->first();
                $customer_phone = ($customer->phone_verified ==1)?"approved"  : "not_approved"  ;
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
                $customer_image = DB::table('customer_images')->where('customer_id',$customer_id)->first();
                $customer->picture = isset($customer_image)?'http://test.hospitallcare.com/backend/uploads/customers/'.$customer_image->picture:'';
                
                $token = auth()->user()->createToken('user')->accessToken;
                return response()->json(['data'=>$customer,'customer_phone'=>$customer_phone,'token' => $token,'user' => $user], 200);
            } else {
                return response()->json(['message' => 'Wrong Email or Password'], 401);
            }
        } else {
            return response()->json(['message' => 'Please, Signup first to continue'], 401);
        }
    }
    public function loginWithFacebook(Request $request)
    {
        $validate = $request->validate([
            'facebook_id'    => 'required',
            'fcm_token'      => 'required',
        ]);
        $facebook_id = $request->facebook_id;
        $user = User::where('facebook_id',$facebook_id)->whereNotNull('customer_id')->select('id','name','email','phone','is_approved','customer_id')->first();

        if (isset($user) && $user->customer_id != null) {
            if ($user) {
                $user_id = $user->id;
                $customer_id = $user->customer_id;
                $customer = DB::table('customers')->where('id',$customer_id)->where('phone_verified','1')->select('id','name','email','gender','marital_status','phone','phone_verified','organization_id','employee_code','org_verified')->first();
                    if(isset($customer)){
                        $customer_phone = "approved";
                    }else{
                        $customer_phone = "not_approved";
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
                $customer_image = DB::table('customer_images')->where('customer_id',$customer_id)->first();
                $customer->picture = isset($customer_image)?'http://test.hospitallcare.com/backend/uploads/customers/'.$customer_image->picture:'';
                
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->accessToken;

                return response()->json(['data'=>$customer,'customer_phone'=>$customer_phone,'token'=>$token,'user' => $user], 200);
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
        return response()->json(["message" =>"User Logout Successfully"], 200); // modify as per your need
    }
    public function details()
    {
        $user = auth()->user();
        return UserResource::make($user);
    }
    public function newPasswordCodeVerification(Request $request){
        $validate = $request->validate([
            'code'             => 'required',
        ]);
        $customer_phone_validation =  DB::table('customer_phone_verification')->where('customer_id',$request->customer_id)->where('code',$request->code)->first();
        if($customer_phone_validation){
            $data['phone'] = $customer_phone_validation->phone;
            $data['customer_id'] = $customer_phone_validation->customer_id;
            $customer_phone_validation =  DB::table('customer_phone_verification')->where('customer_id',$request->customer_id)->delete();
            return response()->json(['createpassword' => 'Code is verified', 'data' => $data, 'message' =>'Code is verified'], 200);
        }
        return response()->json(['message' => 'Enter valid data'], 401);
    }
    public function newPassword(Request $request){
        
        $validate = $request->validate([
            'password'          => 'required|string|min:6',
        ]);
        $customer       = Customer::where('id', $request->customer_id)->update(['phone_verified' =>1,'customer_lead' => 3,]);
        $customer       = DB::table('customers')->where('id',$request->customer_id)->select('id','name','email','gender','marital_status','phone','phone_verified')->first();
        $customer_user  = User::where('customer_id',$request->customer_id)->first();
        if(!$customer_user){
            $user = User::create([
                'name'      => $customer->name,
                'email'     => $customer->email,
                'password'  => Hash::make($request->password),
                'customer_id'=>$customer->id,
                'phone'     => $customer->phone,
                'is_approved'=> 1,
            ]);
                $role       = DB::table('roles')->where('name', 'customer_user')->first();
                $role       = DB::table('role_user')->insert([
                'role_id'   => $role->id,
                'user_id'   => $user->id
                ]);
        }else{
            $update_user = $customer_user->update([
                'name'      => $customer->name,
                'email'     => $customer->email,
                'password'  => Hash::make($request->password),
                'customer_id'=>$customer->id,
                'phone'     => $customer->phone,
                'is_approved'=> 1,
            ]);
            $user  = User::where('customer_id',$request->customer_id)->first();
        }
                if($customer->phone_verified){
                    $customer_phone = "approved";
                }else{
                    $customer_phone = "not_approved";
                }
                $device = FCMDevice::where([
                    ['user_id',$user->id],
                    ['token', $request->fcm_token],
                ])->first();

                if(!$device){
                    FCMDevice::create([
                        'user_id' => $user->id,
                        'token' => $request->fcm_token,
                    ]);
                }
                $customer_image = DB::table('customer_images')->where('customer_id',$request->customer_id)->first();
                $customer->picture = isset($customer_image)?'http://test.hospitallcare.com/backend/uploads/customers/'.$customer_image->picture:'';
                
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->accessToken;

            return response()->json(['data'=>$customer,'customer_phone'=>$customer_phone,'token'=>$token,'user' => $user], 200);
        
    }
}
