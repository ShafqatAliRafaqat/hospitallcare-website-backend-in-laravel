<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Center;
use App\Http\Resources\CenterResource;
use App\Http\Resources\TreatmentResource;
use App\Http\Resources\DoctorResource;

class CenterApiController extends Controller
{
    public function index(){
        $center = Center:: orderby('created_at','DESC')->with(['center_image','center_treatment'])->get();
        return CenterResource :: collection($center);
    }
    public function show($id)
    {
        $center = Center::where('id',$id)->with('center_treatment')->first();
        if($center){
            return CenterResource::make($center);
        } else {
            return response()->json(['error'=>'Please Enter an ID that resides in Database'],200);
        }

    }
    public function treatments($id)
    {
        $center = Center::where('id',$id)->with('center_treatment')->first();
        $treatment = $center->center_treatment;
        if($treatment){
            return TreatmentResource::collection($treatment);
        } else {
            return response()->json(['error'=>'Please Enter an ID that resides in Database'],200);
        }

    }
    public function doctors($id)
    {
        $center = Center::where('id',$id)->with('doctor')->first();
        $doctor = $center->doctor;
        if($doctor){
            return DoctorResource::collection($doctor);
        } else {
            return response()->json(['error'=>'Please Enter an ID that resides in Database'],200);
        }

    }
}
