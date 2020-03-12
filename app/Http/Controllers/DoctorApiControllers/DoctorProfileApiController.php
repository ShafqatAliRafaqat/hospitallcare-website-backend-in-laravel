<?php

namespace App\Http\Controllers\DoctorApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Doctor;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\DoctorApiResource\DoctorProfileResource;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\DoctorQualification;
use Intervention\Image\Facades\Image;
use App\Models\Admin\DoctorCertification;
use Illuminate\Support\Carbon;

class DoctorProfileApiController extends Controller
{
    public function DoctorProfessionalProfile(){

        $id = Auth::user()->doctor_id;
        $specialization = DB::table('treatments as t')
                            ->JOIN('temp_doctor_treatment as dt','dt.treatment_id','t.id')
                            ->WHERE('dt.doctor_id',$id)
                            ->WHERE('dt.parent_id','=',null)
                            ->orderBy('dt.created_at','DESC')
                            ->select('t.id','t.name')
                            ->get();
        $treatments     = DB::table('treatments as t')
                            ->JOIN('temp_doctor_treatment as dt','dt.treatment_id','t.id')
                            ->WHERE('dt.doctor_id',$id)
                            ->WHERE('dt.parent_id','!=',null)
                            ->orderBy('dt.created_at','DESC')
                            ->select('t.id','t.name')
                            ->get();
        $date           = Doctor::where('id', $id)->select('experience')->first();
        $doctor         = YearsDiff($date->experience);
        $certification  = DoctorCertification::where('doctor_id', $id)->select('id', 'country', 'university', 'title as degree', 'year')->get();
        if ($doctor) {
            return response()->json(['data'=>[['experience'=>$doctor, 'specialization'=>$specialization,'treatments'=>$treatments,'certification'=>$certification]]], 200);
        } else {
            return response()->json(['error'=>'Please Enter an ID that resides in Database'], 404);
        }
    }
    public function DoctorProfile(){

        $id = Auth::user()->doctor_id;
        $doctor = Doctor::where('id', $id)->with('doctor_image')->get();

        if ($doctor) {
            return DoctorProfileResource::collection($doctor);
        } else {
            return response()->json(['error'=>'Please Enter an ID that resides in Database'], 404);
        }
    }
    public function UpdateDoctorProfile(Request $request){
        $id = Auth::user()->doctor_id;
        $validate   = $request->validate([
            'email'                  => 'nullable|unique:doctors,email,'.$id,
          ]);
        $request['phone']    = formatPhone($request->phone);
        $doctor_updated =Doctor::where('id', $id)->update([
            'name'          => $request->full_name,
            'phone'         => $request->phone,
            'email'         => $request->email,
            'pmdc'          => $request->pmdc,
            'about'         => $request->about,
        ]);
        $doctor_user = User::where('doctor_id',$id)->first();
        $doctor_email = $doctor_user->email;
        if($doctor_email ==null){
            $doctorUser = $doctor_user->update([
                'email' => $request->email,
            ]);
        }
        $doctor = Doctor::where('id', $id)->with('doctor_image')->get();
        $destinationPath = '/backend/uploads/doctors/';                  // Defining th uploading path if not exist create new
        $image       = $request->file('picture');
        if ($request->file('picture') != null) {                                 //     Uploading the Image to folde

            $table='doctor_images';
            $id_name='doctor_id';
            $delete_images = delete_images($id,$destinationPath,$table,$id_name);

            //name that we'll use for the coding
            $filename           =   str_slug($request->full_name).'-'.time().'.'.$image->getClientOriginalExtension();
            $location           =   public_path($destinationPath.$filename);
        if ($image != null) {
            Image::make($image)->save($location);
            $insert = DB::table('doctor_images')->insert([$id_name => $id, 'picture' => $filename]);
            }
        }
        $doctor = Doctor::where('id', $id)->with('doctor_image')->get();
        if ($doctor) {
            return DoctorProfileResource::collection($doctor);
        } else {
                return response()->json(['error'=>'Please Enter an ID that resides in Database'], 404);
        }
    }
    public function RemoveDoctorEducation($id){

        $doctor_id = Auth::user()->doctor_id;
        $doctor = Doctor::where('id', $doctor_id)->with('doctor_image')->first();
        $delete_education = DoctorQualification::where('id',$id)->forcedelete();

        if ($delete_education) {
            return response()->json(['message'=>'Data removed Successfully'], 200);
        } else {
                return response()->json(['error'=>'Please Enter an ID that resides in Database'], 404);
        }
    }
    public function CreateDoctorEducation(Request $request){
        $doctor_id      =   Auth::user()->doctor_id;
        $doctor         =   Doctor::where('id', $doctor_id)->with('doctor_image')->first();
        $university     =   $request->university;
        $check_uni      =   DB::table('universities')->where('name',$university)->first();
        if ($check_uni == NULL) {
            $insert_uni     =   DB::table('universities')->insert([
                'name'      =>  $university,
            ]);
        }
        $create_education = DoctorQualification::create([
            'doctor_id'         => $doctor_id,
            'country'           => $request->country,
            'university'        => $request->university,
            'degree'            => $request->degree,
            'graduation_year'   => $request->year,
        ]);

        if ($create_education) {
            return response()->json(['message'=>'Data inserted Successfully'], 200);
        } else {
                return response()->json(['error'=>'Please Enter an ID that resides in Database'], 404);
        }
    }
    public function UpdateDoctorEducation(Request $request,$id){

        $doctor_id = Auth::user()->doctor_id;
        $doctor = Doctor::where('id', $doctor_id)->with('doctor_image')->first();

        $delete_education = DoctorQualification::where('id',$id)->update([
            'doctor_id'         => $doctor_id,
            'country'           => $request->country,
            'university'        => $request->university,
            'degree'            => $request->degree,
            'graduation_year'   => $request->year,
        ]);

        if ($delete_education) {
            return response()->json(['message'=>'Data inserted Successfully'], 200);
        } else {
                return response()->json(['error'=>'Please Enter an ID that resides in Database'], 404);
        }
    }
    public function DoctorCertification(){

        $doctor_id = Auth::user()->doctor_id;
        $doctor = Doctor::where('id', $doctor_id)->with('doctor_image')->first();
        $certification = DoctorCertification::where('doctor_id', $doctor_id)->select('id', 'country', 'university', 'title as degree', 'year')->get();
        if ($certification) {
            return response()->json(['data'=>$certification], 200);
        } else {
                return response()->json(['error'=>'Please Enter an ID that resides in Database'], 404);
        }
    }
    public function RemoveDoctorCertification($id){

        $doctor_id = Auth::user()->doctor_id;
        $doctor = Doctor::where('id', $doctor_id)->with('doctor_image')->first();
        $delete_education = DoctorCertification::where('id',$id)->forcedelete();

        if ($delete_education) {
            return response()->json(['message'=>'Data Removed Successfully'], 200);
        } else {
                return response()->json(['error'=>'Please Enter an ID that resides in Database'], 404);
        }
    }
    public function CreateDoctorCertification(Request $request){

        $doctor_id      =   Auth::user()->doctor_id;
        $doctor         =   Doctor::where('id', $doctor_id)->with('doctor_image')->first();
        $university     =   $request->university;
        $check_uni      =   DB::table('universities')->where('name',$university)->first();
        if ($check_uni == NULL) {
            $insert_uni     =   DB::table('universities')->insert([
                'name'      =>  $university,
                'ui'        =>  1,
            ]);
        }
        $degree                 =   $request->degree;
        $check_certification    =   DB::table('degrees')->where('name',$degree)->first();
        if ($check_certification == NULL) {
            $insert_cert    =   DB::table('degrees')->insert([
                'name'      =>  $degree,
                'dc'        =>  1,
            ]);
        }
        $delete_education = DoctorCertification::create([
            'doctor_id'         => $doctor_id,
            'country'           => $request->country,
            'university'        => $university,
            'title'             => $degree,
            'year'              => $request->year,
        ]);

        if ($delete_education) {
            return response()->json(['message'=>'Data inserted Successfully'], 200);
        } else {
                return response()->json(['error'=>'Please Enter an ID that resides in Database'], 404);
        }
    }
    public function UpdateDoctorCertification(Request $request,$id){

        $doctor_id = Auth::user()->doctor_id;
        $doctor    = Doctor::where('id', $doctor_id)->first();
        $delete_education = DoctorCertification::where('id',$id)->update([
            'doctor_id'         => $doctor_id,
            'country'           => $request->country,
            'university'        => $request->university,
            'title'             => $request->degree,
            'year'              => $request->year,
        ]);

        if ($delete_education) {
            return response()->json(['message'=>'Data inserted Successfully'], 200);
        } else {
                return response()->json(['error'=>'Please Enter an ID that resides in Database'], 404);
        }
    }
    public function DoctorExperience(Request $request){
        $id     = Auth::user()->doctor_id;
        $doctor = Doctor::where('id', $id)->first();
        if($request->experience != "null"){
            $experience = Carbon::parse($request->experience);
            $doctor_updated =$doctor->update([
                'experience'          => $experience,
            ]);
            if ($doctor_updated) {
                return response()->json(['message'=>'Data inserted Successfully'], 200);
            } else {
                    return response()->json(['error'=>'Please Enter an ID that resides in Database'], 404);
            }
        }else{
            return response()->json(['message'=>'Data inserted Successfully'], 200);
        }
    }

    public function AddEducation()
    {
        $countries      =   DB::table('countries')->select('id','nicename as name')->orderBy('nicename','ASC')->get();
        $degrees        =   DB::table('degrees')->select('id','name')->where('dc',0)->orderBy('name','ASC')->get();
        $universities   =   DB::table('universities')->select('id','name')->where('ui',0)->orderBy('name','ASC')->get();
        if ($countries) {
            return response()->json(['data'=>[['countries'=>$countries,'degrees'=>$degrees,'universities'=>$universities]]], 200);
        } else {
            return response()->json(['error'=>'Please Enter an ID that resides in Database'], 404);
        }
    }
        public function Addcertification()
    {
        $countries      =   DB::table('countries')->select('id','nicename as name')->orderBy('nicename','ASC')->get();
        $certifications =   DB::table('degrees')->orderBy('name','ASC')->get();
        $universities   =   DB::table('universities')->select('id','name')->orderBy('name','ASC')->get();
        if ($countries) {
            return response()->json(['data'=>[['countries'=>$countries,'certifications'=>$certifications,'universities'=>$universities]]], 200);
        } else {
            return response()->json(['error'=>'Please Enter an ID that resides in Database'], 404);
        }
    }
}
