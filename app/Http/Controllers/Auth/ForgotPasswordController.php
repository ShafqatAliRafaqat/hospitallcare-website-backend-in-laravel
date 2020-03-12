<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin\Customer;
use App\Models\Admin\Doctor;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function forgetPasswordForm($id){

        $doctor   = Doctor::where('reset_password',$id)->first();
        $customer = Customer::where('reset_password',$id)->first();
        if($doctor){
            return view('auth.passwords.doctor_reset', compact('doctor'));
        }elseif($customer){
            return view('auth.passwords.customer_reset', compact('customer'));
        }else{
            return view('auth.token_expired');
        }
    }
    public function resetDoctorPassword(Request $request){

        $id         = $request->id;
        $password   = $request->password;
        $doctor     = Doctor::where('id',$id)->first();
        $doctor_user= User::where('doctor_id',$id)->first();
        if($doctor_user){
            $user = User::where('doctor_id',$id)->update([
                'password'      => Hash::make($password),
                // 'is_approved'   => 1,
            ]);
        }else{
            $user = User::create([
                'name'        => $doctor->name,
                'email'       => $doctor->email,
                'password'    => Hash::make($password),
                'phone'       => $doctor->phone,
                'doctor_id'   => $doctor->$id,
                // 'is_approved' => 1,
            ]);
        }
        if($user){
            $doctor = Doctor::where('id',$id)->update([
                'reset_password' => null,
                'phone_verified' => 1,
            ]);
        }
        return redirect()->route('auth.login');
    }
    public function resetCustomerPassword(Request $request){

        $id         = $request->id;
        $password   = $request->password;
        $customer   = Customer::where('id',$id)->first();
        $customer_user= User::where('doctor_id',$id)->first();
        if($customer_user){
            $user = User::where('customer_id',$id)->update([
                'password'      => Hash::make($password),
                // 'is_approved'   => 1,
            ]);
            if($user){
                $customer = Customer::where('id',$id)->update([
                    'reset_password' => null,
                    'phone_verified' => 1,
                ]);
            }
        }
        
        return redirect('https://www.hospitallcare.com/#/');
    }
}
