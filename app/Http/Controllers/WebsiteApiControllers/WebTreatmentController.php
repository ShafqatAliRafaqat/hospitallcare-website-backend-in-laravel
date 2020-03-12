<?php

namespace App\Http\Controllers\WebsiteApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebsiteApiResource\WebDoctorResource;
use App\Http\Resources\WebsiteApiResource\WebTreatmentResource;
use App\Models\Admin\Doctor;
use App\Models\Admin\Treatment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WebTreatmentController extends Controller
{
    public function all_treatments(){
        $treatment = Treatment::where('parent_id', null)->where('is_active',1)->orderByDesc('position')->orderby('updated_at','DESC')->with('treatment_image')->paginate(24);
        return WebTreatmentResource::collection($treatment);
    }
    public function fetchTreatmentDoctor(Request $request){
        $id = $request->treatmentId;
        if (is_numeric($id)) {
            $treatment = Treatment::where('id',$id)->with('treatment_image')->first();

        } else {
            $treatment = Treatment::where('name',$id)->with('treatment_image')->first();
        }

        if (isset($request->center_id)) {
            $center_id  =   $request->center_id;
            $doctor_treatment   = DB::table('doctor_treatments as dt')
                                    ->join('doctors as d','d.id','dt.doctor_id')
                                    ->join('center_doctor_schedule as cds','cds.doctor_id','dt.doctor_id')
                                    ->select('d.id','d.gender')
                                    ->where('d.is_active',1)
                                    ->where('dt.treatment_id',$treatment->id)
                                    ->where('cds.center_id',$center_id)
                                    ->groupBy('dt.doctor_id')
                                    ->orderBy('d.is_partner','DESC')->get();
        } else {
            $doctor_treatment   = DB::table('doctor_treatments as dt')
                    ->join('doctors as d','d.id','dt.doctor_id')
                    ->select('d.id','d.gender')
                    ->where('dt.treatment_id',$treatment->id)
                    ->where('d.is_active',1)
                    ->groupBy('dt.doctor_id')
                    ->orderBy('d.is_partner','DESC')->get();
                }
        $doctor_treatment = doctor_filter($request,$doctor_treatment);
        if(count($doctor_treatment)>0){
            foreach($doctor_treatment as $doctor_id){
                $doctor_ids[] = $doctor_id->id;
            }
            $doctor_treatment = Doctor::whereIn('id',$doctor_ids)->orderBy('is_partner','DESC')->paginate(9);
            foreach($doctor_treatment as $doctor){
                $doctor->doctor_schedules     = DB::table('center_doctor_schedule as cds')
                                                ->join('medical_centers as mc','mc.id','cds.center_id')
                                                ->select('cds.id','cds.center_id',
                                                DB::raw('GROUP_CONCAT(cds.time_from) as time_from'),DB::raw('GROUP_CONCAT(cds.time_to) as time_to'),DB::raw('GROUP_CONCAT(cds.day_from) as day_from'),DB::raw('GROUP_CONCAT(cds.day_to) as day_to'),'cds.fare','cds.discount','cds.appointment_duration','cds.is_primary','mc.lat','mc.lng')
                                                ->where('cds.doctor_id',$doctor->id)
                                                ->groupBy('cds.center_id')
                                                ->get();
                }
        }
        if ($treatment->parent_id == null) {
            $related_treatments = Treatment::where('parent_id',$id)->select('id','name')->get();
        } else {
            $related_treatments = Treatment::where('parent_id',$treatment->parent_id)->where('id','!=',$id)->select('id','name')->get();
        }
        $treatment_center = DB::table('medical_centers as c')
                                ->join('center_treatments as ct','ct.med_centers_id','c.id')
                                ->where('ct.treatments_id',$treatment->id)
                                ->select('c.id','c.center_name as name')
                                ->get();
        return WebDoctorResource::collection($doctor_treatment)->additional(['meta'=>[
            'treatment' =>WebTreatmentResource::make($treatment),
            'related_treatments' => $related_treatments,
            'related_centers' => $treatment_center,
        ]]);

    }

    public function getTreatmentDoctor(Request $request){
        $treatment = Treatment::where('name',$request->name)->with('treatment_image')->first();
        $doctor_treatment   = DB::table('doctor_treatments as dt')
                            ->join('doctors as d','d.id','dt.doctor_id')
                            ->select('d.*')
                            ->where('dt.treatment_id',$treatment->id)
                            ->where('d.is_active',1)
                            ->groupBy('dt.doctor_id')
                            ->orderBy('d.is_partner','DESC')
                            ->paginate(9);
        return WebDoctorResource::collection($doctor_treatment)->additional(['meta'=>[
            'treatment' =>WebTreatmentResource::make($treatment),
        ]]);
    }

    public function top_Specializations()
    {
        $top_Specializations_names      =   ["InVitro Fertilization (IVF)","Obesity Surgery",'Hair Transplant and PRP',"Plastic Surgery","Gynecology","Orthopedics","Dentistry","ENT SPECIALIST"];
        $top_Specializations            =   [33,32,199,3,114,62,148,99];
        $q = 0;
        foreach ($top_Specializations as $ts) {
            $centers        =   DB::table('treatments as t')
                                    ->join('center_treatments as ct','ct.treatments_id','t.id')
                                    ->join('medical_centers as mc','mc.id','ct.med_centers_id')
                                    ->where('parent_id', null)
                                    ->where(function($query) use ($ts){
                                    $query->where('ct.treatments_id',$ts);
                                    })
                                    ->count();
            $doctors        =   DB::table('doctors as d')
                                ->join('doctor_treatments as dt','dt.doctor_id','d.id')
                                ->join('treatments as t','t.id','dt.treatment_id')
                                ->where('t.parent_id', null)
                                ->where(function($query) use ($ts){
                                $query->where('dt.treatment_id',$ts);
                                })->selectRaw('count(dt.doctor_id)')->groupBy('d.id')
                                ->get();

            $top_Specializations_name[$q]['id']             = $ts;
            $top_Specializations_name[$q]['name']           = $top_Specializations_names[$q];
            $top_Specializations_name[$q]['centers']        = $centers;
            $top_Specializations_name[$q]['doctors']        = $doctors->count();
            $top_Specializations_name[$q]['picture_path']   = 'https://support.hospitallcare.com/backend/web_imgs/new_specialization/'.$ts.'.svg';
        $q++;
        }
        return response()->json([
            'top_Specializations_names' => $top_Specializations_name,
        ]);
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
                $ranges = $hoursRange[$i];
                for ($col = 0; $col < count($ranges); $col++) {
                    $range[] = $ranges[$col];
                }
            }
            $range = array_unique($range);
           sort($range);
        }else{
            $range = null;
        }
        return response()->json(["data" =>$treatment,'schedules' => $range],200);
    }
}
