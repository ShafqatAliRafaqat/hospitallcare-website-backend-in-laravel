<?php

namespace App\Http\Controllers\CustomerApiControllers;

use App\FCMDevice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerApiResource\CustomerCenterResource;
use App\Http\Resources\CustomerApiResource\CustomerDoctorResource;
use App\Http\Resources\WebsiteApiResource\WebCenterResource;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\WebsiteApiResource\WebDoctorResource;
use App\Models\Admin\Center;
use App\Models\Admin\Doctor;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\DB;

class CustomerCenterApiController extends Controller{
    
    public function all_centers(Request $request){
        if($request->search){
            $centers = Center::orderby('updated_at','DESC')->where('center_name','LIKE','%'.$request->search.'%')->where('is_approved','!=',0)->select('id','center_name as name','address','lat','lng')->paginate(50);
        }else{
            $centers = Center::orderby('updated_at','DESC')->where('is_approved','!=',0)->select('id','center_name as name','address','lat','lng')->paginate(30);
        }
        foreach($centers as $center){
            $center->doctors   = DB::table('center_doctor_schedule as cds')
                            ->join('doctors as d','d.id','cds.doctor_id')
                            ->where('d.is_active',1)
                            ->where('cds.center_id',$center->id)
                            ->groupBy('cds.center_id')
                            ->count();
            $center->map            = "https://www.google.com/maps?saddr&daddr=$center->lat,$center->lng";
            $center_image = DB::table('center_images')->where('center_id',$center->id)->first();
            $center['picture'] = (isset($center_image))?'http://test.hospitallcare.com/backend/uploads/centers/'.$center_image->picture:'http://test.hospitallcare.com/backend/web_imgs/hospital.jpg';
        }
        return CustomerCenterResource::collection($centers); 
        
    }

    public function top_centers(){
        $centers = Center::orderby('updated_at','DESC')->where('on_web',1)->select('id','center_name as name','address')->get();
        foreach($centers as $center){

            $center_image = DB::table('center_images')->where('center_id',$center->id)->first();
            $center['picture'] = (isset($center_image))?'http://test.hospitallcare.com/backend/uploads/centers/'.$center_image->picture:'http://test.hospitallcare.com/backend/web_imgs/hospital.jpg';
        }
        return response()->json(['data'=> $centers],200);
    }
    public function fetchCenter(Request $request , $id){
        $center_doctor   = DB::table('center_doctor_schedule as cds')
                            ->join('doctors as d','d.id','cds.doctor_id')
                            ->select('d.*')
                            ->where('d.is_active',1)
                            ->where('cds.center_id',$id)
                            ->groupBy('cds.doctor_id')
                            ->orderBy('d.is_partner','DESC')
                            ->get();
        $center_doctor = doctor_filter($request,$center_doctor);
        if(count($center_doctor)>0){
            foreach($center_doctor as $doctor_id){
                $doctor_ids[] = $doctor_id->id;
            }
            $center_doctor = Doctor::whereIn('id',$doctor_ids)->orderBy('is_partner','DESC')->get();
            foreach($center_doctor as $doctor){
                $doctor->doctor_schedules    = DB::table('center_doctor_schedule as cds')
                                            ->select('cds.id','cds.center_id',
                                            DB::raw('GROUP_CONCAT(cds.time_from) as time_from'),DB::raw('GROUP_CONCAT(cds.time_to) as time_to'),DB::raw('GROUP_CONCAT(cds.day_from) as day_from'),DB::raw('GROUP_CONCAT(cds.day_to) as day_to'),'cds.fare','cds.discount','cds.appointment_duration','cds.is_primary')
                                            ->where('cds.doctor_id',$doctor->id)
                                            ->where('cds.center_id',$id)
                                            ->groupBy('cds.center_id')
                                            ->get();
            }
        }
        return CustomerDoctorResource::collection($center_doctor);
    }
    public function getCenterTreatment(Request $request){
        $doctor_id          = $request->doctor_id;
        $center_id          = $request->center_id;
        $treatment          = getCenterTreatments($doctor_id, $center_id);
        $doctor_schedules   = getDoctorCenterSchedule($doctor_id, $center_id);
        if(count($doctor_schedules)>0){
            foreach($doctor_schedules as $doctor_schedule){
                $appointment_duration = $doctor_schedule->appointment_duration;
                if(isset($appointment_duration)){
                    $str_time =$appointment_duration;
                    sscanf($str_time, "%d", $minutes);
                    $appointment_duration = $minutes * 60;
                }else{
                    $appointment_duration = 900;
                }
                if(isset($doctor_schedule->time_from)){
                    $str_time =$doctor_schedule->time_from;
                    sscanf($str_time, "%d:%d", $hours, $minutes);
                    $time_from = $hours * 3600 + $minutes * 60;
                }else{
                    $time_from = 0;
                }
                if(isset($doctor_schedule->time_to)){
                    $str_time =$doctor_schedule->time_to;
                    sscanf($str_time, "%d:%d", $hours, $minutes);
                    $time_to = $hours * 3600 + $minutes * 60;
                }else{
                    $time_to = 86400;
                }
        
                $hoursRange[] = hoursRange($time_from,$time_to,$appointment_duration);
            }
            for($i=0; $i<count($hoursRange);$i++){

                for ($col = 0; $col < count($hoursRange[$i]); $col++) {
                    $range[] = $hoursRange[$i][$col];
                }
            }
            $range = array_unique($range);
           sort($range);
        }else{
            $range = null;
        }
        

        return response()->json(["data" =>$treatment,'schedules' => $range,],200);
    }
}