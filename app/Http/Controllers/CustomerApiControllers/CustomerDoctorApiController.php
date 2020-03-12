<?php

namespace App\Http\Controllers\CustomerApiControllers;

use App\FCMDevice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerApiResource\CustomerDoctorDetailResource;
use App\Http\Resources\CustomerApiResource\CustomerDoctorResource;
use App\Http\Resources\DoctorApiResource\DoctorProfileResource;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Doctor;
use App\Models\Admin\Treatment;
use Illuminate\Support\Facades\DB;

class CustomerDoctorApiController extends Controller{
    public function all_doctors(Request $request){

        if($request->search){
            $doctors = Doctor::where('is_approved','!=',0)->where('is_active',1)->where('name','LIKE','%'.$request->search.'%')->orderBy('is_partner','DESC')->get();
        }else{
            $doctors = Doctor::where('is_approved','!=',0)->where('is_active',1)->orderBy('is_partner','DESC')->get();
        }
        $doctors = doctor_filter($request,$doctors);
        if(count($doctors)>0){
            foreach($doctors as $doctor_id){
                $doctor_ids[] = $doctor_id->id;
            }
            $doctors = Doctor::whereIn('id',$doctor_ids)->orderBy('is_partner','DESC')->paginate(50);
            foreach($doctors as $doctor){
                $doctor->doctor_schedules     = DB::table('center_doctor_schedule as cds')
                                                ->select('cds.id','cds.center_id',
                                                DB::raw('GROUP_CONCAT(cds.time_from) as time_from'),DB::raw('GROUP_CONCAT(cds.time_to) as time_to'),DB::raw('GROUP_CONCAT(cds.day_from) as day_from'),DB::raw('GROUP_CONCAT(cds.day_to) as day_to'),'cds.fare','cds.discount','cds.appointment_duration','cds.is_primary')
                                                ->where('cds.doctor_id',$doctor->id)
                                                ->groupBy('cds.center_id')
                                                ->get();
                }
        }
        return CustomerDoctorResource::collection($doctors);
    }
    public function top_doctors(){
        $doctors = Doctor::where('is_approved','!=',0)->where('on_web',1)->orderBy('is_partner','DESC')->select('id','name','focus_area','gender')->get();
        foreach($doctors as $doctor){
            $gender = ($doctor->gender == 1 ) ? "Male.png":"Female.png";
            $doctor_image = DB::table('doctor_images')->where('doctor_id',$doctor->id)->first();
            $doctor['picture'] = (isset($doctor_image))?'https://support.hospitallcare.com/backend/uploads/doctors/'.$doctor_image->picture:('https://support.hospitallcare.com/backend/web_imgs/'.$gender);
        }
        return response()->json(['data'=> $doctors],200);
     }
     public function getTreatmentDoctor(Request $request,$id){
        // $treatment = Treatment::where('id',$id)->first();
        $doctor_treatment   = DB::table('doctor_treatments as dt')
                            ->join('doctors as d','d.id','dt.doctor_id')
                            ->select('d.*')
                            ->where('d.is_approved','!=',0)
                            ->where('d.is_active',1)
                            ->where('dt.treatment_id',$id)
                            ->groupBy('dt.doctor_id')
                            ->orderBy('d.is_partner','DESC')
                            ->get();
        $doctor_treatment = doctor_filter($request,$doctor_treatment);
        if(count($doctor_treatment)>0){
            foreach($doctor_treatment as $doctor_id){
                $doctor_ids[] = $doctor_id->id;
            }
            $doctor_treatment = Doctor::whereIn('id',$doctor_ids)->orderBy('is_partner','DESC')->get();
            foreach($doctor_treatment as $doctor){
                $doctor->doctor_schedules     = DB::table('center_doctor_schedule as cds')
                                                ->select('cds.id','cds.center_id',
                                                DB::raw('GROUP_CONCAT(cds.time_from) as time_from'),DB::raw('GROUP_CONCAT(cds.time_to) as time_to'),DB::raw('GROUP_CONCAT(cds.day_from) as day_from'),DB::raw('GROUP_CONCAT(cds.day_to) as day_to'),'cds.fare','cds.discount','cds.appointment_duration','cds.is_primary')
                                                ->where('cds.doctor_id',$doctor->id)
                                                ->groupBy('cds.center_id')
                                                ->get();
                }
        }
        return CustomerDoctorResource::collection($doctor_treatment);
    }
    public function fetchDoctor($id)
    {
        $doctor       = Doctor::where('id',$id)->with('doctor_image','centers','treatments')->withTrashed()->first();
        // doctor_ view table data
        $customer_id  = Auth::user()->customer_id;
        $doctor_view  = DB::table('doctor_views')->where('doctor_id',$id)->where('customer_id',$customer_id)->where('view_from',1)->first();
        if(!$doctor_view){
            $insert = DB::table('doctor_views')->insert([
                'doctor_id'         => $id,
                'customer_id'       => isset($customer_id)?$customer_id:null,
                'view_from'         => 1,
                'viewed_or_booked'  => 0,
            ]);
        }
        // $doctor_qualification   = DB::table('doctor_qualification')->where('doctor_id',$id)->get();
        // $doctor_certification   = DB::table('doctor_certification')->where('doctor_id',$id)->get();
        $doctor_schedules       = DB::table('center_doctor_schedule as cds')
                                ->join('medical_centers as mc','mc.id','cds.center_id')
                                ->select('cds.id','cds.center_id','mc.address','mc.phone','mc.assistant_phone','mc.center_name',DB::raw('GROUP_CONCAT(cds.time_from) as time_from'),DB::raw('GROUP_CONCAT(cds.time_to) as time_to'),DB::raw('GROUP_CONCAT(cds.day_from) as day_from'),DB::raw('GROUP_CONCAT(cds.day_to) as day_to'),'cds.fare','cds.discount','cds.appointment_duration','cds.is_primary')
                                ->where('cds.doctor_id',$id)
                                ->groupBy('cds.center_id')
                                ->get();
        $grouped_treatments     =   $doctor->treatments->groupBy('id');
        if (count($grouped_treatments)>0) {
            foreach ($grouped_treatments as $gt) {
                $specialization_check   =   DB::table('treatments')->WhereNull('parent_id')->where('id',$gt[0]->id)->first();
                $treatment_check        =   DB::table('treatments')->WhereNotNull('parent_id')->where('id',$gt[0]->id)->first();
                if(isset($specialization_check)){
                    $specialization[]      =   $gt[0]->name;
                }
                if(isset($treatment_check)){
                    $all_treatments[]       =   $gt[0]->name;
                }
            }
        } else {
            $specialization    =   [];
            $all_treatments    =   [];
        }
        return CustomerDoctorDetailResource::make($doctor)->additional(['meta'=> [
            // 'doctor_qualification'  =>  $doctor_qualification,
            // 'doctor_certification'  =>  $doctor_certification,
            'doctor_schedules'      =>  $doctor_schedules,
            'specialization'        =>  (isset($specialization)? $specialization: []),
            'all_treatments'        =>  (isset($all_treatments)? $all_treatments: []),
        ]]);
    }
    public function HomeSearch(Request $request)
    {
        $name                   =   $request->search;
        if (isset($name)) {
            $specializations    =   DB::table('treatments')
                                    ->where('name','LIKE','%'.$name.'%')
                                    ->whereNull('parent_id')
                                    ->where('is_active',1)
                                    ->whereNull('deleted_at')
                                    ->select('id','name')
                                    ->get();
            foreach ($specializations as $s) {
                $picture        =   DB::table('treatment_images')->where('treatment_id',$s->id)->select('picture')->first();
                $s->picture     =   isset($picture)? 'https://support.hospitallcare.com/backend/uploads/treatments/'.$picture->picture : 'https://support.hospitallcare.com/backend/web_imgs/treatment.png';
            }
            $doctors            =   DB::table('doctors as d')
                                    ->where('d.is_approved','!=',0)
                                    ->where('d.is_active',1)
                                    ->whereNull('d.deleted_at')
                                    ->where('d.name','LIKE','%'.$name.'%')
                                    ->select('d.id as id','d.name as name','d.focus_area','d.gender as gender')
                                    ->orderByDesc('d.is_partner')
                                    ->get();
            foreach ($doctors as $d) {
                $picture        =   doctorImage($d->id);
                $gender         =   ($d->gender == 1 ) ? 'Male.png':'Female.png';
                $d->picture     =   isset($picture)? 'https://support.hospitallcare.com/backend/uploads/doctors/'.$picture->picture : 'https://support.hospitallcare.com/backend/web_imgs/'.$gender;
            }
            $centers            =   DB::table('medical_centers')
                                    ->where('is_approved','!=',0)
                                    ->whereNull('deleted_at')
                                    ->where('center_name','LIKE','%'.$name.'%')
                                    ->select('id','center_name as name')
                                    ->get();
            foreach ($centers as $c) {
                $picture        =   DB::table('center_images')->where('center_id',$c->id)->select('picture')->first();
                $c->picture     =   isset($picture)? 'https://support.hospitallcare.com/backend/uploads/centers/'.$picture->picture : 'https://support.hospitallcare.com/backend/web_imgs/hospital.jpg';
            }
        return response()->json(
            ['data' =>  [
                'specializations'   =>  $specializations,
                'doctors'           =>  $doctors,
                'centers'           =>  $centers,
            ]],200);
        } else {
            return response()->json(
            ['message' =>  "No Data"],200);
        }

    }
}
