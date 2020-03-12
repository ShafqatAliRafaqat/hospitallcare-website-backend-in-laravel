<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CenterTreatmenteApiResource;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\DoctorTreatmenteApiResource;
use App\Http\Resources\CenterDoctorApiResource;

class RelationTableApiController extends Controller
{
    public function Center_Treatment(){
        $center_treatments =   DB::table('center_treatments')->get();
        return CenterTreatmenteApiResource:: collection($center_treatments);
    }
    public function Center_Doctor(){

        $center_treatments =   DB::table('center_doctor_schedule')->get();
        return CenterDoctorApiResource:: collection($center_treatments);
    }
    public function Doctor_Treatment(){
        $center_treatments =   DB::table('doctor_treatments')->get();
        return DoctorTreatmenteApiResource:: collection($center_treatments);
    }
}
