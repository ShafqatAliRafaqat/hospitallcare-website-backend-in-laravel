<?php

namespace App\Http\Controllers\WebsiteApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WebsiteApiResource\DoctorProfileResource;
use App\Http\Resources\WebsiteApiResource\WebDoctorResource;
use App\Models\Admin\Doctor;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\TempNotes;
use Carbon\Carbon;

class WebSearchController extends Controller
{
    public function doctorFilter(Request $request){
        $male = ($request->male) ? 1 : '';
        $female = ($request->female) ? 0 : '';
        $available_any_day = ($request->available_any_day) ? 0 : '';
        $available_today = ($request->available_today) ? 0 : '';
        $available_on_weekend = ($request->available_on_weekend) ? 0 : '';
        $doctors = Doctor::where('gender',$male)->get();
        // return response()->json(['data' =>$male],200);
        return WebDoctorResource::collection($doctors);
    }

    public function HomeSearch(Request $request)
    {
        $name                   =   $request->search;
        if (isset($name)) {
            $specializations    =   DB::table('treatments')
                                    ->where('name','LIKE','%'.$name.'%')
                                    // ->whereNull('parent_id')
                                    ->where('is_active',1)
                                    ->whereNull('deleted_at')
                                    ->select('id','name')
                                    ->get();
            $doctors            =   DB::table('doctors as d')
                                    ->where('d.is_approved','!=',0)
                                    ->where('d.is_active',1)
                                    ->where('d.name','LIKE','%'.$name.'%')
                                    ->whereNull('d.deleted_at')
                                    ->select('d.id as id','d.name as name','d.focus_area','d.gender as gender')
                                    // ->where('d.phone_verified','!=','0')
                                    ->orderByDesc('d.is_partner')
                                    ->get();
            foreach ($doctors as $d) {
                $d->picture        =   doctorImage($d->id);
            }
            $centers            =   DB::table('medical_centers')
                                    ->where('is_approved','!=',0)
                                    ->where('center_name','LIKE','%'.$name.'%')
                                    ->whereNull('deleted_at')
                                    ->select('id','center_name as name')
                                    ->get();
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
