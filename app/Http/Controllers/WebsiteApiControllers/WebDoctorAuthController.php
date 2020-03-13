<?php

namespace App\Http\Controllers\WebsiteApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Customer;
use App\Models\Admin\Doctor;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class WebDoctorAuthController extends Controller
{
    public function doctorSignIn(Request $request){
        $validate = $request->validate([
            'phone'     =>'required',
        ]);
        $request['phone']    = formatPhone($request->phone);
        if ($request['phone']) {
            $phone  =   $request['phone'];
            $doctor_table   =   DB::table('doctors')->where('phone',$request['phone'])->first();
            if ($doctor_table) {
                $user_table   =   DB::table('users')->where('phone',$request['phone'])->whereNotNull('doctor_id')->first();
                if ($user_table) {
                    return response()->json(['doctor_panel' => 'Route to Doctor Panel'], 200);
                } else {
                    $url_code = random_int(100000000, 999999999);
                    $doctor = Doctor::where('phone', $phone)->update([
                        'reset_password' => $url_code,
                    ]);
                    $url = "http://test.hospitallcare.com/forget_password/$url_code";
                    $sms = 'To+change+your+password,+please+follow+this+link+\n'.$url;
                    $code_sended = CustomerAppointmentSms($sms , $phone);
                    return response()->json(['create_doctor_password' => 'Code is verified'], 200);
                }
            } else {
                return response()->json(['doctor_signUp' => 'Please Sign Up (not in Doctor Table)'], 200);
            }
        }
        return response()->json(['invalid_data' => 'Enter the Valid Phone Number'], 200);
    }
    public function doctorSignUp(Request $request)
    {
        $validate = $request->validate([
            'phone'     =>'required',
            // 'email'     => 'required|unique:users,email'
        ]);
        $user_email = DB::table('users')->where('email',$request->email)->first();
        if($user_email){
            return response()->json(['message'=>"Email already in our system, Enter Email"],401);
        }
        $request['phone']    = formatPhone($request->phone);
        if ($request['phone']) {
            $phone  =   $request['phone'];
            $doctor_table   =   DB::table('doctors')->where('phone',$request['phone'])->first();
            if ($doctor_table) {
                $user_table   =   DB::table('users')->where('phone',$request['phone'])
                ->whereNotNull('doctor_id')
                ->first();
                if ($user_table) {
                    return response()->json(['doctor_panel' => 'Route to Doctor Panel'], 200);
                } else {
                    $url_code = random_int(100000000, 999999999);
                    $doctor = Doctor::where('phone', $phone)->update([
                        'reset_password' => $url_code,
                    ]);
                    $url = "http://test.hospitallcare.com/forget_password/$url_code";
                    $sms = 'To+change+your+password,+please+follow+this+link+\n'.$url;
                    $code_sended = CustomerAppointmentSms($sms , $phone);
                    return response()->json(['create_doctor_password' => 'Code is verified'], 200);
                }
            } else {
                // Sign Up Doctor
                $validate = $request->validate([
                    'first_name'    =>'required',
                    'last_name'     =>'sometimes',
                    'email'         =>'sometimes',
                    'pmdc'          =>'required',
                    'phone'         =>'required',
                    'password'      =>'required',
                ]);
                $doctor = Doctor::create([
                    'name'          =>  $request->first_name,
                    'last_name'     =>  $request->last_name,
                    'email'         =>  $request->email,
                    'phone'         =>  $phone,
                    'pmdc'          =>  $request->pmdc,
                    'gender'        =>  $request->gender,
                    'address'       =>  'Lahore',
                    'city_name'     =>  'Lahore',
                    'lat'           =>  31.5204,
                    'lng'           =>  74.3587,
                ]);
                if ($doctor) {
                    $user = User::create([
                    'name'      =>  $request->first_name,
                    'email'     =>  $request->email,
                    'phone'     =>  $phone,
                    'password'  =>  Hash::make($request->password),
                    'doctor_id' =>  $doctor->id,
                ]);
                $role           = DB::table('roles')->where('name', 'doctor_profile')->first();
                $role           = DB::table('role_user')->insert([
                    'role_id'   => $role->id,
                    'user_id'   => $user->id,
                ]);
                $user_id    = $user->id;
                $code = random_int(100000, 999999);
                $sms = "You+verification+code+is+$code";
                $code_sended = CustomerAppointmentSms($sms , $request->phone);
                if ($code_sended) {
                    $doctor_phone_validation =  DB::table('doctor_phone_verification')->insert([
                    'doctor_id'     =>  $doctor->id,
                    'phone'         =>  $request->phone,
                    'code'          =>  $code,
                    ]);

                    return response()->json(['code_sent' => $doctor->id ], 200);
                }
            }
            return response()->json(['doctor_signUp' => 'Signed up Successfully'], 200);
            }
        }
        return response()->json(['invalid_data' => 'Enter the Valid Phone Number'], 200);

    }
    public function doctorCodeVarification(Request $request)
    {
        $id         = $request->doctor_id;
        $user       = DB::table('users')->where('doctor_id',$id)->select('id')->first();
        $user_id     = $user->id;
        $validate   = $request->validate([
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
            $doctor_phone_validation =  DB::table('doctor_phone_verification')->where('doctor_id',$id)->where('code',$request->code)->delete();
            return response()->json(['varified' => 'Code is verified'], 200);
        }
        return response()->json(['message' => 'Enter Correct 6 Digit Code'], 401);
    }

    public function forgotPasswordDoctor(Request $request)
    {
        $validate = $request->validate([
            'phone'       => 'required',
        ]);
        $request['phone']    = formatPhone($request->phone);

        $phone_check    = Doctor::where('phone',$request->phone)->withTrashed()->first();
        $usertable      = User::where('phone',$request->phone)->whereNotNull('doctor_id')->withTrashed()->first();

        if (isset($phone_check) && isset($usertable)) {
            $doctor_id  =   $usertable->doctor_id;
            $phone_check    = DB::table('doctor_phone_verification')->where('phone', $request->phone)->first();
            if (isset($phone_check)) {
                DB::table('doctor_phone_verification')->where('phone', $request->phone)->delete();
            }
            $code = random_int(100000, 999999);
            $sms = "$code+is+your+HospitALL+account+verification+code.+This+code+will+expire+in+10+minutes";
            $code_sended = CustomerAppointmentSms($sms , $request->phone);

            if ($code_sended) {
                $doctor_phone_verification =  DB::table('doctor_phone_verification')->insert([
                'doctor_id'     =>  $doctor_id,
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
    public function doctorForgetCodeVarification(Request $request){
        $validate = $request->validate([
            'code'             => 'required',
        ]);
        $request['codeSended']    = formatPhone($request->codeSended);

        $customer_phone_validation =  DB::table('doctor_phone_verification')->where('phone',$request->codeSended)->where('code',$request->code)->first();
        if($customer_phone_validation){
            return response()->json(['codeVarified' => 'Code is verified'], 200);
        }
        return response()->json(['message' => 'Enter valid data'], 404);
    }
    public function newPasswordDoctor( Request $request){
        $validate = $request->validate([
            'password'       => 'required',
        ]);
        $phone = $request->codeSended;
        $password = $request->password;
        $user = User::where('phone',$phone)->whereNotNull('doctor_id')->first();
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

}
