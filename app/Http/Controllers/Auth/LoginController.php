<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    protected function credentials(Request $request)
    {
      if(is_numeric($request->get('email'))){
        $phone              =   formatPhone($request->get('email'));
        $check_doctor       =   User::where('phone',$phone)->whereNotNull('doctor_id')->first();
        if ($check_doctor) {
            return ['phone'=>$phone,'password'=>$request->get('password')];
        } else {
            $check_customer     =   User::where('phone',$phone)->whereNotNull('customer_id')->first();
            if ($check_customer) {
                $phone  =   '03';
            }
        }
        return ['phone'=>$phone,'password'=>$request->get('password')];
      }
      else if (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
        return ['email' => $request->get('email'), 'password'=>$request->get('password')];
      }
    }
}
