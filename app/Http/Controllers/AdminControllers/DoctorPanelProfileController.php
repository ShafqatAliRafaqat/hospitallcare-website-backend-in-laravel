<?php

namespace App\Http\Controllers\AdminControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Customer;
use App\Models\Admin\Doctor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Center;
use App\Models\Admin\DoctorImage;
use App\Services\DoctorServices;
use App\User;

class DoctorPanelProfileController extends Controller
{
        /** @var DoctorServices */
    private $service;
    public function __construct()
    {
        $this->service  =   new DoctorServices();
    }
    public function doctorLoginPage()
    {
        return view('adminpanel.doctoruser.doctor_login');
    }
    public function doctorLogin(Request $request)
    {
        $email_or_phone     = is_numeric($request->email_or_phone);
        dd($email_or_phone);
        return view('adminpanel.doctoruser.doctor_login');
    }
    public function doctorGeneralInfo(Request $request,$id)
    {
        $doctor_id      =   Auth::user()->doctor_id;
        $doctor         =   Doctor::where('id',$doctor_id)->first();
        if($doctor->profile_status == 1){
            return redirect()->route('doctor_edit_specialization');
        } else if($doctor->profile_status == 2){
            return redirect()->route('doctor_edit_schedules');
        }
        $validate   =   $request->validate([
            'name'                      =>  'required',
            'last_name'                 =>  'sometimes',
            'focus_area'                =>  'required',
            'email'                     =>  'sometimes',
            'gender'                    =>  'required',
            'city_name'                 =>  'sometimes',
            'address'                   =>  'sometimes',
            'pmdc'                      =>  'required',
            'phone'                     =>  'required',
            'about'                     =>  'sometimes',
            'lat'                       =>  'sometimes',
            'lng'                       =>  'sometimes',
        ]);
        $doctor     =   Doctor::where('id',$id)->update([
            'name'                      =>  $request->name,
            'last_name'                 =>  $request->last_name,
            'focus_area'                =>  $request->focus_area,
            'email'                     =>  $request->email,
            'gender'                    =>  $request->gender,
            'city_name'                 =>  $request->city_name,
            'address'                   =>  $request->address,
            'pmdc'                      =>  $request->pmdc,
            'phone'                     =>  $request->phone,
            'about'                     =>  $request->about,
            'lat'                       =>  $request->lat,
            'lng'                       =>  $request->lng,
            'profile_status'            =>  1,
        ]);
        $doctor_name    =   $request->name;
        $picture        =   $this->service->imagesUpdateControll($request,$id,$doctor_name);
        if ($doctor) {
            $user   =   DB::table('users')->where('doctor_id',$id)->first();
            $doctor_user    =   User::where('doctor_id',$id)->update([
                'name'      =>  $request->name,
            ]);
        }
        return  redirect()->route('doctor_edit_specialization');
    }
    public function doctorEditSpecialization()
    {
        $doctor_id      =   Auth::user()->doctor_id;
        $doctor         =   Doctor::where('id',$doctor_id)->first();
        if($doctor->profile_status == 0){
            $image          =   DoctorImage::where('doctor_id',$doctor_id)->first();
            return view('doctorpanel.new_profile_making.general_info',compact('doctor','image'));
        } else if($doctor->profile_status == 2) {
            return redirect()->route('doctor_edit_schedules');
        }
        $specializations   =   DB::table('treatments')
                                ->whereNull('parent_id')
                                ->where('is_active',1)
                                ->select('id','name')
                                ->get();
        $except             =   [1,45,69];
        $centers            =   Center::where('is_active',1)->whereNotIn('id',$except)->get();
        return view('doctorpanel.new_profile_making.specialization_info',compact('specializations','centers'));
    }
    public function doctorSpecializationUpdate(Request $request)
    {
        $specializations    =   $request->specializations;
        $procedures         =   $request->procedures;
        $centers            =   $request->centers;
        $doctor_id          =   Auth::user()->doctor_id;
        $delete_treatments  =   DB::table('temp_doctor_treatment')->where('doctor_id',$doctor_id)->delete();
        $delete_treatments  =   DB::table('doctor_treatments')->where('doctor_id',$doctor_id)->delete();
        $doctor_treatment   =   DB::table('center_doctor_schedule')->where('doctor_id',$doctor_id)->delete();
        if($specializations){
            foreach ($specializations as $s) {
                $insert_treatments[] = DB::table('temp_doctor_treatment')->insert([
                'doctor_id'    => $doctor_id,
                'treatment_id' => $s,
                ]);
            }
        }
        if ($procedures) {
            foreach ($procedures as $p) {
                $parent_id  =   DB::table('treatments')->where('id',$p)->first();
                $parent_id  =   $parent_id->parent_id;
                if ($parent_id) {
                $insert_treatments[] = DB::table('temp_doctor_treatment')->insert([
                    'doctor_id'     => $doctor_id,
                    'treatment_id'  => $p,
                    'parent_id'     => $parent_id,
                ]);
                }
            }
        }
        if ($centers) {
            $doctor_treatment = DB::table('temp_doctor_treatment')->where('doctor_id',$doctor_id)->get();
            foreach ($centers as $center_id) {
               $schedule_id    =   DB::table('center_doctor_schedule')->insertGetID([
                    'center_id' =>  $center_id,
                    'doctor_id' =>  $doctor_id,
                    'day_from'  =>  'Monday',
                    'day_to'    =>  'Saturday',
                    'time_from' =>  '11:00',
                    'time_to'   =>  '19:00',
                ]);
                foreach($doctor_treatment as $dt){
                    $doctor_treatments = DB::table('doctor_treatments')->insert([
                        'doctor_id'    => $doctor_id,
                        'schedule_id'  => $schedule_id,
                        'treatment_id' => $dt->treatment_id,
                    ]);
                }
            }
        }
        $change_status  =   Doctor::where('id',$doctor_id)->update([
                'profile_status'    =>  2,
            ]);
        return  redirect()->route('doctor_edit_schedules');
    }
    public function doctorEditSchedules()
    {
        $doctor_id      =   Auth::user()->doctor_id;
        $user_id        =   Auth::user()->id;
        $doctor         =   Doctor::where('id',$doctor_id)->first();
        if($doctor->profile_status == 0){
            $image      =   DoctorImage::where('doctor_id',$doctor_id)->first();
            return view('doctorpanel.new_profile_making.general_info',compact('doctor','image'));
        } else if($doctor->profile_status == 1) {
            return redirect()->route('doctor_edit_specialization');
        }
        $change_role    =   DB::table('role_user')->where('user_id',$user_id)->update([
            'role_id'   =>  12,
        ]);
        return  redirect()->route('adminDashboard');
    }
}
