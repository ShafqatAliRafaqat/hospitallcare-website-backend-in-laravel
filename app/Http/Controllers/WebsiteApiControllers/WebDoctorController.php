<?php

namespace App\Http\Controllers\WebsiteApiControllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WebsiteApiResource\DoctorProfileResource;
use App\Http\Resources\WebsiteApiResource\WebDoctorResource;
use App\Models\Admin\Doctor;
use App\Models\Admin\Treatment;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\TempNotes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WebDoctorController extends Controller
{
    public function all_doctors(Request $request){
        $d = Doctor::where('is_approved','!=',0)
                    ->where('is_active',1)
                    ->orderBy('is_partner','DESC')
                    ->with(['doctor_image','centers']);

        $d = doctor_filter($request,$d);

        $doctors = $d->paginate(9);

        foreach($doctors as $doctor){
            $doctor['treatments']           = DB::table('doctor_treatments as dt')
                                                ->join('treatments as t','t.id','dt.treatment_id')
                                                ->select('t.*')
                                                ->where('dt.doctor_id',$doctor->id)
                                                ->whereNull('t.deleted_at')
                                                ->groupBy('dt.treatment_id')
                                                ->get();
            $doctor['doctor_schedules']     = DB::table('center_doctor_schedule as cds')
                                                ->join('medical_centers as mc','mc.id','cds.center_id')
                                                ->select('cds.id','cds.center_id','mc.center_name',
                                                DB::raw('GROUP_CONCAT(cds.time_from) as time_from'),DB::raw('GROUP_CONCAT(cds.time_to) as time_to'),DB::raw('GROUP_CONCAT(cds.day_from) as day_from'),DB::raw('GROUP_CONCAT(cds.day_to) as day_to'),'cds.fare','cds.discount','cds.appointment_duration','cds.is_primary','mc.lat','mc.lng')
                                                ->whereNull('mc.deleted_at')
                                                ->where('cds.doctor_id',$doctor->id)
                                                ->groupBy('cds.center_id')
                                                ->get();
        }
        // return response()->json(['data=>',$doctors]);
        $specializations    =   Treatment::whereNull('parent_id')->orderBy('updated_at','DESC')->select('id','name','updated_at')->get();
        return WebDoctorResource::collection($doctors)->additional(['meta' => ["specializations" => $specializations]]);
        
    }
    public function top_doctors(){
       $doctors      = topDoctors();
        $top_doctors = WebDoctorResource::collection($doctors);
        return  base64_encode(json_encode($top_doctors));
    }
    public function fetchDoctor(Request $request,$id)
    {
        $doctor                 = Doctor::where('id',$id)->with('doctor_image','centers','treatments')->withTrashed()->first();
        // doctor_ view table data
        $customer_id = $request->id;
        if($customer_id){
            $doctor_view = DB::table('doctor_views')->where('doctor_id',$id)->where('customer_id',$customer_id)->where('view_from',0)->first();
            if(!$doctor_view){
                $insert = DB::table('doctor_views')->insert([
                    'doctor_id'         => $id,
                    'customer_id'       => isset($customer_id)?$customer_id:null,
                    'view_from'         => 0,
                    'viewed_or_booked'  => 0,
                ]);
            }
        }else{
            $insert = DB::table('doctor_views')->insert([
                'doctor_id'         => $id,
                'customer_id'       => isset($customer_id)?$customer_id:null,
                'view_from'         => 0,
                'viewed_or_booked'  => 0,
            ]);
        }
        $doctor_qualification   = DB::table('doctor_qualification')->where('doctor_id',$id)->get();
        $doctor_certification   = DB::table('doctor_certification')->where('doctor_id',$id)->get();
        $doctor_schedules       = DB::table('center_doctor_schedule as cds')
                                ->join('medical_centers as mc','mc.id','cds.center_id')
                                ->select('cds.id','cds.center_id','mc.center_name','mc.phone','mc.assistant_phone',DB::raw('GROUP_CONCAT(cds.time_from) as time_from'),DB::raw('GROUP_CONCAT(cds.time_to) as time_to'),DB::raw('GROUP_CONCAT(cds.day_from) as day_from'),DB::raw('GROUP_CONCAT(cds.day_to) as day_to'),'cds.fare','cds.discount','cds.appointment_duration','cds.is_primary','mc.lat as center_lat','mc.lng as center_lng')
                                ->where('cds.doctor_id',$id)
                                ->groupBy('cds.center_id')
                                ->get();
        $grouped_treatments     =   $doctor->treatments->groupBy('id');
        if (count($grouped_treatments)>0) {
            foreach ($grouped_treatments as $gt) {
                $specialization_check   =   DB::table('treatments')->WhereNull('parent_id')->whereNull('deleted_at')->where('id',$gt[0]->id)->first();
                $treatment_check        =   DB::table('treatments')->WhereNotNull('parent_id')->whereNull('deleted_at')->where('id',$gt[0]->id)->first();
                // $all_treatments[]       =   $gt[0]->name;
                if(isset($specialization_check)){
                    $specialization[]      =   $gt[0]->name;
                    $specialization_id[]             =   $gt[0]->id;
                }
                if(isset($treatment_check)){
                    $all_treatments[]      =   $gt[0]->name;
                    $treatment_id[]        =   $gt[0]->id;
                }
            }
        if (count($doctor_schedules)>0 ) {
            $not_primary    =   true;
            foreach ($doctor_schedules as $ds) {
                if ($ds->is_primary==1) {
                    $is_primary         =   $ds->center_id;
                    $center_lat         =   $ds->center_lat;
                    $center_lng         =   $ds->center_lng;
                    $secondry           =   NULL;
                    $doctor->lat        =   $ds->center_lat;
                    $doctor->lng        =   $ds->center_lng;
                    $not_primary        =   false;
                } else if($not_primary){
                    $is_primary         =   NULL;
                    $secondry           =   $ds->center_id;
                    $lat                =   $ds->center_lat;
                    $lng                =   $ds->center_lng;
                    $doctor->lat        =   $ds->center_lat;
                    $doctor->lng        =   $ds->center_lng;
                }
            }
            if ($is_primary) {
                $nearest_clinics   =   DB::table('medical_centers')
                                        ->whereNull('deleted_at')
                                        ->select('id','center_name as name','lat','lng',
                                            DB::raw('( 3956*2 * acos( cos( radians('.$center_lat.') ) *
                                            cos( radians( lat ) ) * cos( radians( lng ) -
                                            radians('.$center_lng.') ) + sin( radians('.$center_lat.') ) *
                                            sin( radians( lat ) ) ) ) AS distance')
                                            )
                                        ->orderBy('distance')
                                        ->get();
            } else if ($secondry){
                $nearest_clinics   =   DB::table('medical_centers')
                                        ->whereNull('deleted_at')
                                        ->select('id','center_name as name','lat','lng',
                                            DB::raw('( 3956*2 * acos( cos( radians('.$lat.') ) *
                                            cos( radians( lat ) ) * cos( radians( lng ) -
                                            radians('.$lng.') ) + sin( radians('.$lat.') ) *
                                            sin( radians( lat ) ) ) ) AS distance')
                                            )
                                        ->orderBy('distance')
                                        ->get();
            }
            if ($nearest_clinics) {
                $related_centers    =   $nearest_clinics->where('distance','<',7);
                foreach ($related_centers as $c) {
                    $clinic_id[]    =   $c->id;
                }
                if (isset($clinic_id) && isset($specialization_id[0]) ) {
                $related_doctors    =   DB::table('center_doctor_schedule as cds')
                                        ->join('doctor_treatments as dt','dt.doctor_id','cds.doctor_id')
                                        ->join('doctors as d','dt.doctor_id','d.id')
                                        ->whereIn('cds.center_id',$clinic_id)
                                        ->where('d.is_active',1)
                                        ->whereNull('d.deleted_at')
                                        ->where('dt.treatment_id',$specialization_id[0])
                                        ->select('d.id as id','d.name as name','d.last_name as last_name')
                                        ->groupBy('d.id')
                                        ->where('d.id','!=',$id)
                                        ->get();
                } else {
                    $clinic_id          =   '';
                    $related_doctors    =   '';
                }
            }
        }
        } else {
            $specialization     =   [];
            $specialization_id  =   [];
            $all_treatments     =   [];
            $clinic_id          =   '';
            $related_doctors    =   '';
            $related_centers    =   '';
        }
        return DoctorProfileResource::make($doctor)->additional(['meta'=> [
            'doctor_qualification'  =>  $doctor_qualification,
            'doctor_certification'  =>  $doctor_certification,
            'doctor_schedules'      =>  $doctor_schedules,
            'specialization'        =>  (isset($specialization)? $specialization: []),
            'specialization_id'     =>  (isset($specialization_id)? $specialization_id: []),
            'all_treatments'        =>  (isset($all_treatments)? $all_treatments: []),
            'related_doctors'       =>  isset($related_doctors)?$related_doctors:'',
            'related_centers'       =>  isset($related_centers)?$related_centers:'',
        ]]);
    }

    public function createLead(Request $request)
    {
        $data               =   $request->input();
        if ($data) {
            $validate       =   $request->validate([
              'name'                    => 'required',
              'phone'                   => 'required',
              'treatment_name'          => 'required',
              'email'                   => 'sometimes',
              'booking_date'            => 'sometimes',
              'booking_message'         => 'sometimes',
            ]);
            $createLead     =   DB::table('temp_customers')->insertGetId([
                'name'              =>  $request->name,
                'phone'             =>  $request->phone,
                'email'             =>  $request->email,
                'treatment'         =>  $request->treatment_name,
                'lead_from'         =>  1,
                'created_at'        => Carbon::now()->toDateTimeString(),
            ]);
            if($request->booking_message){
                $time  = (isset($request->booking_date) ? Carbon::parse($request->booking_date)->format('d-m-Y h:i A') : "Not Added");
                $notes = TempNotes::create([
                    'customer_id'       =>  $createLead,
                    'notes'             =>  'Doctor Name: '.$request->doctor_name.'<br>'.
                                            'Treatment: '.$request->treatment_name.'<br>'.
                                            'Appointment Date: '.$time.'<br>'.
                                            $request->booking_message,
                ]);
             }

            if ($createLead) {
                return response()->json(['message' => 'You have successfully Booked an Appointment. Our Customer Care will call
                    you in a while for Confirmation'],200);
            } else {
                return response()->json(['message' => 'Something Went Wrong'],404);
            }
        }

    }
}
