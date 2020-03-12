<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Doctor;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\TreatmentResource;

class DoctorApiController extends Controller
{
    public function index(){
        $doctor = Doctor:: orderby('created_at','DESC')->with(['treatments','centers','doctor_image'])->get();
        return DoctorResource :: collection($doctor);
    }
    public function show($id)
    {
        $doctor = Doctor::where('id',$id)->with(['treatments','doctor_image'])->first();
        if($doctor){
            return DoctorResource::make($doctor);
        } else {
            return response()->json(['error'=>'Please Enter an ID that resides in Database'],200);
        }

    }
    public function treatments($id)
    {
        $doctor = Doctor::where('id',$id)->with('treatments')->first();
        $treatments = $doctor->treatments;
        if($treatments){
            return TreatmentResource::collection($treatments);
        } else {
            return response()->json(['error'=>'Please Enter an ID that resides in Database'],200);
        }

    }
}
