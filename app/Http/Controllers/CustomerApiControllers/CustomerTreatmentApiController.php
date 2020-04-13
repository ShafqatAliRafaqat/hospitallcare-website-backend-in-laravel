<?php

namespace App\Http\Controllers\CustomerApiControllers;

use App\FCMDevice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\WebsiteApiResource\WebDoctorResource;
use App\Models\Admin\Doctor;
use App\Models\Admin\Treatment;
use Illuminate\Support\Facades\DB;

class CustomerTreatmentApiController extends Controller{

    public function top_Specializations(){
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
            $top_Specializations_name[$q]['picture_path']   = 'https://support.hospitallcare.com/backend/web_imgs/new_specialization/'.$ts.'.png';
        $q++;
        }
        return response()->json([
            'top_Specializations_names' => $top_Specializations_name,
        ]);
     }
     public function all_Specializations(){
        $treatments = Treatment::where('parent_id', null)->where('is_active',1)->orderbyDesc('position')->orderByDesc('updated_at')->select('id','name')->get();
        $q = 0;
        foreach($treatments as $treatment){
            $treatment_image = DB::table('treatment_images')->where('treatment_id',$treatment->id)->first();
            $treatment['picture'] = (isset($treatment_image))?'https://support.hospitallcare.com/backend/uploads/treatments/'.$treatment_image->picture:'https://support.hospitallcare.com/backend/web_imgs/treatment.png';
            $ts             =   $treatment->id;
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
            $treatment['centers']        = $centers;
            $treatment['doctors']        = $doctors->count();
        $q++;
        }
        return response()->json(["data" => $treatments],200);
     }
}
