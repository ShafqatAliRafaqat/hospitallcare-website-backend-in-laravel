<?php

namespace App\Http\Controllers\DoctorApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorApiResource\DoctorAllCentersResource;
use App\Http\Resources\DoctorApiResource\DoctorCenterDetailsResource;
use App\Http\Resources\DoctorApiResource\DoctorProfileResource;
use App\Http\Resources\DoctorVisitingTimesResource;
use App\Models\Admin\Center;
use App\Models\Admin\Doctor;
use App\Models\Admin\DoctorCertification;
use App\Models\Admin\DoctorQualification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class DoctorPracticeInfoApiController extends Controller
{
    public function PracticeInfo()
    {
        $doctor_id      =   Auth::user()->doctor_id;
        $centers        =   DB::table('doctors as d')
                            ->JOIN('center_doctor_schedule as cds','cds.doctor_id','d.id')
                            ->JOIN('medical_centers as mc','mc.id','cds.center_id')
                            ->select('mc.id as center_id','mc.center_name as center_name','mc.address as center_address','cds.is_primary','cds.appointment_duration')
                            ->WHERE('cds.doctor_id',$doctor_id)
                            ->groupBy('mc.id')
                            ->get();
        if ($centers) {
            return DoctorCenterDetailsResource::collection($centers);
        } else {
            return response()->json(['message'  =>  'There are no Centers']);
        }
    }
    public function AllCenters()
    {
        // $centers    =   Center::where('is_active',1)->select('id','center_name as name','address','is_approved')->orderBy('center_name','ASC')->get();
        $centers    =   Center::orderBy('center_name','ASC')->select('id','center_name as name','address','is_approved')->get();
        if ($centers) {
            return DoctorAllCentersResource::collection($centers);
        } else {
            return response()->json(['message'  =>  'There are no Centers']);
        }
    }

    public function AddNewCenter(Request $request)
    {
        $doctor_id          =   Auth::user()->doctor_id;
        $center_name        =   $request->center_name;
        $address            =   $request->address;
        $save_center        =   DB::table('medical_centers')->insert([
            'center_name'   =>  $center_name,
            'focus_area'    =>  $center_name,
            'address'       =>  $address,
            'requested_by'  =>  $doctor_id,
            'facilitator'   =>  0,
            'lat'           =>  31.5204,
            'lng'           =>  74.3587,
        ]);
        if ($save_center) {
            return response()->json(['message' => 'Center Added Successfully']);
        } else {
            return response()->json(['message' => 'Error Saving it!']);
        }
    }

    public function SaveCenterFirst(Request $request,$id)
    {
        $doctor_id      =   Auth::user()->doctor_id;
        $center_id      =   $id;
        $is_primary     =   $request->is_primary;
        $schedule_id    =   DB::table('center_doctor_schedule')->insertGetID([
            'center_id' =>  $center_id,
            'doctor_id' =>  $doctor_id,
            'is_primary'=>  $is_primary,
            'day_from'  =>  'Monday',
            'day_to'    =>  'Saturday',
            'time_from' =>  '11:00',
            'time_to'   =>  '19:00',
        ]);
        $doctor_treatment = DB::table('temp_doctor_treatment')->where('doctor_id',$doctor_id)->get();
        foreach($doctor_treatment as $dt){
            $doctor_treatments = DB::table('doctor_treatments')->insert([
                'doctor_id'    => $doctor_id,
                'schedule_id'  => $schedule_id,
                'treatment_id' => $dt->treatment_id,
            ]);
        }
        if ($schedule_id) {
            return response()->json(['message' => 'Center Added']);
        } else {
            return response()->json(['message' => 'Error Saving it!']);
        }
    }

    public function UpdatePrimaryLocation(Request $request,$id)
    {
        $doctor_id      =   Auth::user()->doctor_id;
        $center_id      =   $id;
        $is_primary     =   $request->is_primary;
        $Update_Center    =   DB::table('center_doctor_schedule')->where(['center_id' =>  $center_id,'doctor_id' =>  $doctor_id])->update([
            'is_primary'    =>  $is_primary,
        ]);
        if ($Update_Center) {
            return response()->json(['message' => 'Center Updated Successfully']);
        } else {
            return response()->json(['message' => 'Error Updaeting it!']);
        }
    }

    public function AppointmentSettingsView($id)
    {
        $doctor_id              =   Auth::user()->doctor_id;
        $center_id              =   $id;
        $appointment_details    =   DB::table('center_doctor_schedule')
                                    ->where(['center_id' => $center_id, 'doctor_id' => $doctor_id])
                                    ->select('appointment_duration','fare')
                                    ->first();
        if ($appointment_details) {
            $appointment_details->appointment_duration    =   isset($appointment_details->appointment_duration) ? "$appointment_details->appointment_duration" : '';
            $appointment_details->fare    =   isset($appointment_details->fare) ? $appointment_details->fare : '';
            return response()->json(['data' => $appointment_details], 200);
        } else {
            return response()->json(['message' => 'No Schedules are added Yet!'], 404);
        }
    }

    public function AppointmentSettings(Request $request, $id)
    {
        $doctor_id      =   Auth::user()->doctor_id;
        $center_id              =   $id;
        $appointment_duration   =   $request->appointment_duration;
        $appointment_fee        =   $request->appointment_fee;
        $update                 =   DB::table('center_doctor_schedule')
        ->where(['center_id' => $center_id, 'doctor_id' => $doctor_id])
        ->update([
            'appointment_duration'  =>  $appointment_duration,
            'fare'                  =>  $appointment_fee,
        ]);
        if ($update) {
            return response()->json(['message' => 'Appointment Settings are Updated Successfully'],200);
        } else {
            return response()->json(['message' => 'Error Updating it!'],404);
        }
    }

    public function VisitingTimes($id)
    {
        $doctor_id      =   Auth::user()->doctor_id;
        $center_id      =   $id;
        $centers        =   DB::table('doctors as d')
                            ->JOIN('center_doctor_schedule as cds','cds.doctor_id','d.id')
                            ->select('cds.is_primary','cds.center_id as center_id',DB::raw('GROUP_CONCAT(cds.id) as schedule_id'),DB::raw('GROUP_CONCAT(cds.time_from) as time_from'),DB::raw('GROUP_CONCAT(cds.time_to) as time_to'),'cds.day_from','cds.day_to')
                            ->WHERE(['cds.doctor_id' => $doctor_id, 'cds.center_id' => $center_id])
                            ->groupBy(['cds.day_from','cds.day_to'])
                            ->get();
        if ($centers) {
            return DoctorVisitingTimesResource::collection($centers);
        } else {
            return response()->json(['message'  =>  'There are no Centers'],404);
        }
    }

    public function UpdateVisitingTimes(Request $request, $id)
    {
        $doctor_id      = Auth::user()->doctor_id;
        $center_id      = $id;
        $main           = json_decode($request->data, true);
        foreach ($main as $datas) {
            $day_from = $datas['from_day'];
            $day_to   = $datas['to_day'];
            $times    = $datas['timings'];
            foreach ($times as $data) {
                $time_from = Carbon::parse($data['start_time']);                             // Appointment date
                $time_from = $time_from->format('H:i');
                $time_to   = Carbon::parse($data['end_time']);                             // Appointment date
                $time_to   = $time_to->format('H:i');
                $schedule_id = DB::table('center_doctor_schedule')->insertGetID([
                    'doctor_id'         => $doctor_id,
                    'center_id'         => $center_id,
                    'day_from'          => $day_from,
                    'day_to'            => $day_to,
                    'time_from'         => $time_from,
                    'time_to'           => $time_to,
                    ]);
                $doctor_treatment = DB::table('temp_doctor_treatment')->where('doctor_id',$doctor_id)->get();
                foreach($doctor_treatment as $dt){
                    $schedule_id = DB::table('doctor_treatments')->insert([
                        'doctor_id'         => $doctor_id,
                        'schedule_id'       => $schedule_id,
                        'treatment_id'      => $dt->treatment_id,
                        ]);
                }
            }
        }
        return response()->json(['message'  =>  'Schedule Added Successfully'],200);
    }
    public function SingleTimeUpdate(Request $request, $id)
    {
        $doctor_id              =   Auth::user()->doctor_id;
        $center_id              =   $id;
        $datas                  =   json_decode($request->data, true);
        $schedule_ids           =   $datas['schedule_id'];
        $schedule_id            =   explode(",",$schedule_ids );
        $count                  =   count($schedule_id);

        if (count($schedule_id) > 1) {
            foreach ($schedule_id as $id) {
            $delete_treatments      =   DB::table('doctor_treatments')->where('schedule_id',$id)->delete();
            $delete_center_sch      =   DB::table('center_doctor_schedule')->where('id',$id)->delete();
            }
        } else {
            $delete_treatments      =   DB::table('doctor_treatments')->where('schedule_id',$schedule_ids)->delete();
            $delete_center_sch      =   DB::table('center_doctor_schedule')->where('id',$schedule_ids)->delete();
        }

        $day_from               =   $datas['from_day'];
        $day_to                 =   $datas['to_day'];
        $times                  =   $datas['timings'];
        foreach ($times as $data) {
            $time_from      =   Carbon::parse($data['start_time']);                             // Appointment date
            $time_from      =   $time_from->format('H:i');
            $time_to        =   Carbon::parse($data['end_time']);                             // Appointment date
            $time_to        =   $time_to->format('H:i');
            $schedule_id    =   DB::table('center_doctor_schedule')->insertGetID([
                'doctor_id'         => $doctor_id,
                'center_id'         => $center_id,
                'day_from'          => $day_from,
                'day_to'            => $day_to,
                'time_from'         => $time_from,
                'time_to'           => $time_to,
                ]);
            $doctor_treatment = DB::table('temp_doctor_treatment')->where('doctor_id',$doctor_id)->get();
            foreach($doctor_treatment as $dt){
                $schedule_id = DB::table('doctor_treatments')->insert([
                    'doctor_id'         => $doctor_id,
                    'schedule_id'       => $schedule_id,
                    'treatment_id'      => $dt->treatment_id,
                ]);
            }
        }
        if ($schedule_id && $doctor_treatment) {
            return response()->json(['message'  =>  'Schedule Updated Successfully'],200);
        } else {
            return response()->json(['message'  =>  'Something Went Wrong! Please Try Again.'],404);
        }

    }
}
