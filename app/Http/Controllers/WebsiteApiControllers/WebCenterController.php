<?php

namespace App\Http\Controllers\WebsiteApiControllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WebsiteApiResource\WebCenterResource;
use App\Http\Resources\WebsiteApiResource\WebTopCenterResource;
use App\Http\Resources\WebsiteApiResource\WebDoctorResource;
use App\Models\Admin\Center;
use App\Models\Admin\Doctor;
use Illuminate\Support\Facades\DB;

class WebCenterController extends Controller
{
    public function all_centers(){
        $center = Center::orderby('on_web','DESC')->where('is_approved','!=',0)->with(['center_image','center_treatment'])->paginate(24);
        foreach ($center as $c) {
        $center_doctor   = DB::table('center_doctor_schedule as cds')
                            ->join('doctors as d','d.id','cds.doctor_id')
                            ->select('d.id')
                            ->where('d.is_active',1)
                            ->where('cds.center_id',$c->id)
                            ->groupBy('cds.doctor_id')
                            ->orderBy('d.is_partner','DESC')
                            ->get();
            $c['count_doctor']  =    $center_doctor->count();
        }
        return WebCenterResource::collection($center);
    }
    public function top_centers(){
        $center = Center::orderby('updated_at','DESC')
                ->where('on_web',1)
                ->with(['center_image'])
                ->get();
        return WebTopCenterResource::collection($center);
    }
    public function fetchCenter(Request $request){
        $id = $request->centerId;
        $center = Center::where('id',$id)->with(['center_image','center_treatment'])->first();
        $center_doctor   = DB::table('center_doctor_schedule as cds')
                            ->join('doctors as d','d.id','cds.doctor_id')
                            ->select('d.id','d.gender','d.experience')
                            ->where('d.is_active',1)
                            ->where('cds.center_id',$center->id)
                            ->groupBy('cds.doctor_id')
                            ->orderBy('d.is_partner','DESC')
                            ->get();
        $center_doctor  = doctor_filter($request,$center_doctor);
        if(count($center_doctor)>0){
            foreach($center_doctor as $doctor_id){
                $doctor_ids[] = $doctor_id->id;
            }
            $center_doctor = Doctor::whereIn('id',$doctor_ids)->orderBy('is_partner','DESC')->paginate(9);
            foreach($center_doctor as $doctor){

                $doctor_id  =   $doctor->id;
                $treatments = DB::table('doctor_treatments as dt')
                                            ->join('treatments as t','t.id','dt.treatment_id')
                                            ->join('center_treatments as ct','t.id','ct.treatments_id')
                                            ->where(function($query) use ($doctor_id,$id) {
                                            $query->where('dt.doctor_id',$doctor_id)
                                            ->where('ct.med_centers_id',$id);
                                            })->select('t.*')
                                            ->groupBy('t.id')
                                            ->orderBy('t.name','DESC')
                                            ->get();
                $doctor->treatments = $treatments;
                $doctor->doctor_schedules    = DB::table('center_doctor_schedule as cds')
                                            ->join('medical_centers as mc','mc.id','cds.center_id')
                                            ->select('cds.id','cds.center_id',
                                            DB::raw('GROUP_CONCAT(cds.time_from) as time_from'),DB::raw('GROUP_CONCAT(cds.time_to) as time_to'),DB::raw('GROUP_CONCAT(cds.day_from) as day_from'),DB::raw('GROUP_CONCAT(cds.day_to) as day_to'),'cds.fare','cds.discount','cds.appointment_duration','cds.is_primary','mc.lat','mc.lng')
                                            ->where('cds.doctor_id',$doctor->id)
                                            ->where('cds.center_id',$center->id)
                                            ->groupBy('cds.center_id')
                                            ->get();
            }
        }
        $center_treatments      =   DB::table('center_treatments as ct')
                                ->join('treatments as t','t.id','treatments_id')
                                ->where('ct.med_centers_id',$id)
                                ->select('t.id','t.name')
                                ->take(50)
                                ->get();
        if ($center->lat != null && $center->lng != null) {
            $latitude = $center->lat;
            $longitude = $center->lng;
            $nearest_clinics   =   DB::table('medical_centers')
                                    ->select('id','center_name as name','lat','lng',
                                        DB::raw('( 3956*2 * acos( cos( radians('.$latitude.') ) *
                                        cos( radians( lat ) ) * cos( radians( lng ) -
                                        radians('.$longitude.') ) + sin( radians('.$latitude.') ) *
                                        sin( radians( lat ) ) ) ) AS distance')
                                        )
                                    ->orderBy('distance')
                                    ->where('id','!=',$id)
                                    ->get();
            if ($nearest_clinics) {
                $clinics   =    $nearest_clinics->where('distance','<',5);
            }
        }
        return WebDoctorResource::collection($center_doctor)->additional(['meta'=>[
            'center' => WebCenterResource::make($center),
            'center_treatments' => $center_treatments,
            'nearest_clinics' =>   $clinics,
        ]]);
    }
}
