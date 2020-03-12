<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Center;
use App\Models\Admin\Customer;
use App\Models\Admin\Doctor;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Treatment;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
class DoctorScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctor_id          =   Auth::user()->doctor_id;
        $doctor             =   Doctor::where('id',$doctor_id)->with('centers')->first();
        if (count($doctor->centers)>0) {
         foreach ($doctor->centers as $c) {
             $center_id[] = $c->id;
         }
         $center_id = array_values(array_unique($center_id));
         $count              =   count($center_id);
     }else{
        $center_id[] =null;
    }

    if($center_id[0] != null){
        for ($i=0; $i < $count ; $i++) {
            $doctorschedule[]   =   DB::table('center_doctor_schedule')->where('doctor_id', $doctor_id)->where('center_id', $center_id[$i])->get();
            $centers[]          =   Center::where('id',$center_id[$i])->first();
        }
    }

    return view('doctorpanel.doctorschedule.index', compact('doctorschedule','center_id','centers'));
}
public function edit($center_id)
{

    if (Auth::user()->can('edit_doctorschedule')) {
        $doctor_id = Auth::user()->doctor_id;
        $center = Center::where('id',$center_id)->first();
        $doctor = Doctor::where('id',$doctor_id)->first();
        $center_schedule = DB::table('center_doctor_schedule')->where('doctor_id',$doctor_id)->where('center_id',$center_id)->get();
        return view('doctorpanel.doctorschedule.edit',compact('doctor','center','center_schedule'));
    }
    else {
        abort(403);
    }
}

public function update(Request $request, $center_id)
{
      if( Auth::user()->can('edit_doctorschedule') ){
        $id                     =   $center_id;
        $doctor_id = Auth::user()->doctor_id;
        $old_schedule           =   DB::table('center_doctor_schedule')->where(['doctor_id' => $doctor_id, 'center_id' => $id])->get();
        $count_old_schedule1    =   count($old_schedule);
        for ($i=0; $i < $count_old_schedule1; $i++) {
            $find_schedule[]      =   DB::table('doctor_treatments')->where('schedule_id',$old_schedule[$i]->id)->first();
        }
        $find_schedule      =   (array_values(array_filter($find_schedule)));
        $old_schedule_id    =   $find_schedule[0]->schedule_id;
        $schedule           = DB::table('center_doctor_schedule')->where(['doctor_id' => $doctor_id, 'center_id' => $id])->get();
            /*
                Saving the data to database in center_doctor_schedule table
            */
                if ($request->day_from[0] && $request->time_from[0]!=null) {
                    $day_from       =   $request->input('day_from');
                    $day_to         =   $request->input('day_to');
                    $time_from      =   $request->input('time_from');
                    $time_to        =   $request->input('time_to');
                    $fare                   =   $request->fare;
                    $discount               =   $request->discount;
                    $appointment_duration   =   $request->appointment_duration;
                    if($request->input('is_primary') == NULL){
                        $is_primary = 0;
                    } else {
                        $is_primary = 1;
                    }

                    $count_all      =   count($day_to);
                    for ($j=0; $j < $count_all; $j++) {
                        $insert_schedule1[]    =   DB::table('center_doctor_schedule')
                        ->insertGetID([
                            'center_id'             => $id,
                            'doctor_id'             => $doctor_id,
                            'time_from'             => $time_from[$j],
                            'time_to'               => $time_to[$j],
                            'day_from'              => $day_from[$j],
                            'day_to'                => $day_to[$j],
                            'fare'                  => $fare,
                            'discount'              => $discount,
                            'appointment_duration'  => $appointment_duration,
                            'is_primary'            => $is_primary,
                            'updated_at'            => date('Y-m-d'),
                        ]);
                    }

                    if ($insert_schedule1) {
                        $updated_schedule   =   $insert_schedule1[0];
                        $Update_schedule_id = DB::table('doctor_treatments')->where('schedule_id', $old_schedule_id)->update([
                            'schedule_id' => $updated_schedule,
                        ]);
                        foreach ($schedule as $s) {
                            $schedule_delete  = DB::table('center_doctor_schedule')->where('id', $s->id)->delete();
                        }
                    }
                }
                $change_status  =   Doctor::where('id',$doctor_id)->update([
                    'profile_status'    =>  3,
                ]);
                session()->flash('success', 'Schedule Updated Successfully');
                return redirect()->route('doctorschedule.index');

            } else {
                abort(403);
            }
        }
    public function doctor_profile()                                                     // show profile of doctor in doctor panel
    {
            if( Auth::user()->can('edit_doctorschedule') ){
                $id                     =   Auth::user()->doctor_id;
                $doctor                 = Doctor::where('id',$id)->with('doctor_image','centers','treatments')->withTrashed()->first();
                $doctor_qualification   = DB::table('doctor_qualification')->where('doctor_id',$id)->get();
                $doctor_certification   = DB::table('doctor_certification')->where('doctor_id',$id)->get();
                $doctor_schedules       = DB::table('center_doctor_schedule as cds')
                                        ->join('medical_centers as mc','mc.id','cds.center_id')
                                        ->select('cds.id','cds.center_id','mc.center_name',DB::raw('GROUP_CONCAT(cds.time_from) as time_from'),DB::raw('GROUP_CONCAT(cds.time_to) as time_to'),DB::raw('GROUP_CONCAT(cds.day_from) as day_from'),DB::raw('GROUP_CONCAT(cds.day_to) as day_to'),'cds.fare','cds.discount','cds.appointment_duration','cds.is_primary')
                                        ->where('cds.doctor_id',$id)
                                        ->groupBy('cds.center_id')
                                        ->get();
                return view('doctorpanel.profile.index', compact('doctor','doctor_schedules','doctor_certification', 'doctor_qualification'));
            } else {
                abort(403);
            }
    }
    public function doctor_profile_edit()                                                     // show profile of doctor in doctor panel
    {
        if (Auth::user()->can('edit_doctorschedule')) {
            $id                     =   Auth::user()->doctor_id;
            $doctor                 =   Doctor::where('id', $id)
                                        ->with(['doctor_image','centers','treatments','doctor_qualification','doctor_certification'])
                                        ->first();
            $doctor_qualification   =   DB::table('doctor_qualification')->where('doctor_id',$id)->get();
            $doctor_certification   =   DB::table('doctor_certification')->where('doctor_id',$id)->get();
            $centers                =   Center::with('center_treatment')->get();
            $center_id[]            =   null;
            $countries              =   DB::table('countries')->get();
            $degrees                =   DB::table('degrees')->orderBy('name','ASC')->get();
            $universities           =   DB::table('universities')->orderBy('name','ASC')->get();
            return view('doctorpanel.profile.edit', compact('doctor','center_id','doctor_certification', 'doctor_qualification','centers','countries','degrees','universities'));
        } else {
            abort(403);
        }
    }
    public function save_doctor_profile(Request $request,$id)                                                     // show profile of doctor in doctor panel
    {
        if (Auth::user()->can('edit_doctorschedule')) {
            $validate = $request->validate([
                'focus_area'                =>  'required',
                'name'                      =>  'sometimes',
                'last_name'                 =>  'sometimes',
                'email'                     =>  'sometimes',
                'lng'                       =>  ['required', 'regex:/[0-9]([0-9]|-(?!-))+/'],
                'lat'                       =>  ['required', 'regex:/[0-9]([0-9]|-(?!-))+/'],
                'address'                   =>  'required',
                'pmdc'                      =>  'sometimes',
                'notes'                     =>  'sometimes',
                'phone'                     =>  'sometimes',
                'assistant_name'            =>  'sometimes',
                'assistant_phone'           =>  'sometimes',
                'experience'                =>  'sometimes',
            ]);

            if ($request->file('picture')) {
                $destinationPath = '/backend/uploads/doctors/';
                    /*
                        Uploading the Image to folder
                    */
                        $center_image           = DB::table('doctor_images')->where('doctor_id', $id)->select('picture')->first();
                        $image                  = $request->file('picture');
                        $filename               = str_slug($request->input('name')).'-'.time().'.'.$image->getClientOriginalExtension();
                        $resizeName             = '540x370-'.$filename;
                        $resizeName2            = '80x55-'.$filename;
                        $location               = public_path($destinationPath.$filename);
                        $resizeLoc              = public_path($destinationPath.$resizeName);
                        $resizeLoc2             = public_path($destinationPath.$resizeName2);
                        Image::make($image)->save($location);
                        Image::make($image)->resize(540, 370)->save($resizeLoc);
                        Image::make($image)->resize(80, 55)->save($resizeLoc2);
                        if ($center_image ) {
                            $image_path             =  public_path()."/backend/uploads/doctors/".$center_image->picture;
                            $imageMedium            = public_path()."/backend/uploads/doctors/540x370-".$center_image->picture;
                            $imageSmall             = public_path()."/backend/uploads/doctors/80x55-".$center_image->picture;
                            File::delete($image_path);
                            File::delete($imageMedium);
                            File::delete($imageSmall);
                            $delete_image   = DB::table('doctor_images')->where('doctor_id', $id)->delete();
                        }
                        $insert         = DB::table('doctor_images')->insert(['doctor_id' => $id, 'picture' => $filename]);
                    }

                if ($request->qua_degree[0] != null) {                                                   // Checks for Qualification fields
                    $count_qua_degree = count($request->qua_degree);
                    for ($i = 0; $i < $count_qua_degree; $i++) {
                        if ($request->qua_university[$i] == null || $request->qua_country[$i] == null || $request->qua_graduation_year[$i] == null) {
                            session()->flash('error', 'Please Select all Qualification fields');
                            return redirect()->back();
                        }
                    }
                }

                if ($request->cer_degree[0] != null) {                                                   // Checks for Certification fields

                    $count_cer_degree = count($request->cer_degree);
                    for ($c = 0; $c < $count_cer_degree; $c++) {
                        if ($request->cer_university[$c] == null || $request->cer_country[$c] == null || $request->cer_graduation_year[$c] == null) {
                            session()->flash('error', 'Please Select all Certification fields');
                            return redirect()->back();
                        }
                    }
                }


                $doctor                     = Doctor::find($id);
                $doctor->name               = $request->input('name');
                $doctor->last_name          = $request->input('last_name');
                $doctor->email              = $request->input('email');
                $doctor->focus_area         = $request->input('focus_area');
                $doctor->address            = $request->input('address');
                $doctor->pmdc               = $request->input('pmdc');
                $doctor->lat                = $request->input('lat');
                $doctor->lng                = $request->input('lng');
                $doctor->notes              = $request->input('notes');
                $doctor->phone              = $request->input('phone');
                $doctor->assistant_name     = $request->input('assistant_name');
                $doctor->assistant_phone    = $request->input('assistant_phone');
                $doctor->experience         = $request->input('experience');
                $doctor->save();
                $doctor_id                  = $doctor->id;

                // delete all qualification data from doctor_qualification then insert data
                $doctor_qualification   = DB::table('doctor_qualification')->where('doctor_id', $id)->delete();
                // insert qualification data of doctor
                if ($request->qua_country[0]!=NULL || $request->qua_university[0]!=NULL || $request->qua_degree[0]!=NULL || $request->qua_graduation_year[0]!=NULL) {
                    $qua_country        = $request->qua_country;
                    $qua_university     = $request->qua_university;
                    $qua_degree         = $request->qua_degree;
                    $qua_graduation_year= $request->qua_graduation_year;
                    $count = count($request->qua_degree);

                    for($q=0;$q<$count;$q++){
                        $qualification = DB::table('doctor_qualification')->insertGetId([
                            'doctor_id'         => $doctor_id,
                            'country'           => $qua_country[$q],
                            'university'        => $qua_university[$q],
                            'degree'            => $qua_degree[$q],
                            'graduation_year'   => $qua_graduation_year[$q]
                        ]);
                    }
                }
            // delete all qualification data from doctor_qualification then insert data
                $doctor_certification   = DB::table('doctor_certification')->where('doctor_id', $id)->delete();
        // insert certification data of doctor
                if ($request->cer_country[0]!=NULL || $request->cer_university[0]!=NULL || $request->cer_degree[0]!=NULL || $request->cer_graduation_year[0]!=NULL) {
                    $cer_country        = $request->cer_country;
                    $cer_university     = $request->cer_university;
                    $cer_degree         = $request->cer_degree;
                    $cer_graduation_year= $request->cer_graduation_year;
                    $count = count($request->cer_degree);
                    for($c=0;$c<$count;$c++){
                        $certification = DB::table('doctor_certification')->insertGetId([
                            'doctor_id'         => $doctor_id,
                            'country'           => $cer_country[$c],
                            'university'        => $cer_university[$c],
                            'title'            => $cer_degree[$c],
                            'year'              => $cer_graduation_year[$c]
                        ]);
                    }
                }

                session()->flash('success', 'Doctor Updated Successfully');
                return redirect()->route('doctor_profile');
            } else {
                abort(403);
            }
        }


    }
