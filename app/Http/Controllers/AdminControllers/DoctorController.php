<?php

namespace App\Http\Controllers\AdminControllers;

use App\FCMDevice;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Center;
use App\Models\Admin\CenterImage;
use App\Models\Admin\City;
use App\Models\Admin\Doctor;
use App\Models\Admin\DoctorImage;
use App\Models\Admin\DoctorPartnershipFiles;
use App\Models\Admin\DoctorPartnershipImages;
use App\Models\Admin\Procedure;
use App\Models\Admin\Treatment;
use App\Services\DoctorServices;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class DoctorController extends Controller
{
    /** @var DoctorServices */
    private $service;
    public function __construct()
    {
        $this->service  =   new DoctorServices();
    }
    public function index()
    {
        if( Auth::user()->can('view_medical_centers') ){
            $doctors    =  Doctor::where('is_approved','!=',0)->orderBy('updated_at','DESC')->with('doctor_image','centers')->get();
            return view('adminpanel.doctors.index', compact('doctors'));
        } else {
            abort(403);
        }
    }
    public function Tempdoctors()
    {
        if( Auth::user()->can('view_medical_centers') ){
            $doctors =  Doctor::where('is_approved','=',0)->orderBy('updated_at','DESC')->with('doctor_image','centers')->get();
            return view('adminpanel.doctors.temp_index', compact('doctors'));
        } else {
            abort(403);
        }
    }
    public function show_deleted()
    {
        if( Auth::user()->can('view_medical_centers') ){
            $doctors =  Doctor::orderBy('updated_at','DESC')->with('doctor_image','centers')->onlyTrashed()->get();
            return view('adminpanel.doctors.soft_deleted', compact('doctors'));
        } else {
            abort(403);
        }
    }

    public function create()
    {
        if( Auth::user()->can('create_medical_center') ){
            $treatments     =   Treatment::where('is_active',1)->orderBy('name','ASC')->get();
            $specialities   =   Treatment::where('is_active',1)->whereNull('parent_id')->orderBy('name','ASC')->get();
            $centers        =   Center::where('is_active',1)->orderBy('center_name','ASC')->get();
            $countries      =   DB::table('countries')->orderBy('nicename','ASC')->get();
            $degrees        =   DB::table('degrees')->orderBy('name','ASC')->get();
            $universities   =   DB::table('universities')->orderBy('name','ASC')->get();
            $cities         =   City::where('is_active',1)->get();
            return view('adminpanel.doctors.create', compact('treatments','specialities','centers','countries','degrees','universities','cities'));
        } else {
            abort(403);
        }
    }

    public function store(Request $request)
    {
        if( Auth::user()->can('create_medical_center') ){
            $doctor     = $this->service->createService($request);
            if (isset($doctor[0]) != null) {
                session()->flash('error', $doctor[1]);
                return redirect()->back()->withInput();
            } else {
                session()->flash('success', 'Doctor Created Successfully');
                $doctor_id  =   $doctor->id;
                return redirect()->route('ViewSchedules',$doctor_id);
            }
        } else {
            abort(403);
        }
    }

    public function update(Request $request, $id)
    {
        if( Auth::user()->can('edit_medical_center') ){
            $doctor     = $this->service->updateService($request,$id);
            if (isset($doctor[0]) != null) {
                session()->flash('error', $doctor[1]);
                return redirect()->back()->withInput();
            } else {
                session()->flash('success', 'Doctor Created Successfully');
                return redirect()->route('ViewSchedules',$id);
            }
        } else {
            abort(403);
        }
    }

    public function View_Schedules($id)
    {
        if( Auth::user()->can('view_medical_centers') ){
            $doctor         =   Doctor::where('id',$id)->withTrashed()->first();
            $center_list    =   DB::table('center_doctor_schedule as cds')
            ->join('medical_centers as mc','cds.center_id','mc.id')
            ->where('cds.doctor_id',$id)
            ->select('cds.doctor_id as id','mc.id as center_id','mc.center_name','mc.focus_area','mc.address')
            ->groupBy('mc.id')
            ->get();
            return view('adminpanel.doctors.centers_show', compact('doctor','center_list'));
        } else {
            abort(403);
        }
    }

    public function Edit_Schedules(Request $request,$id)
    {
        if (Auth::user()->can('edit_doctorschedule')) {
            $doctor_id      =   $request->doctor_id;
            $center         =   Center::where('id',$id)->first();
            $doctor         =   Doctor::where('id',$doctor_id)->first();
            $center_schedule = DB::table('center_doctor_schedule')->where('doctor_id',$doctor_id)->where('center_id',$id)->get();
        // dd($center_schedule,$doctor_id,$center,$doctor);
            return view('adminpanel.doctors.center_schedule_edit',compact('doctor','center','center_schedule'));
        }
        else {
            abort(403);
        }
    }

    public function Update_Center_schedule(Request $request,$id)
    {
        if( Auth::user()->can('edit_doctorschedule') ){
            $doctor_id              =   $request->doctor_id;
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
                session()->flash('success', 'Schedule Updated Successfully');
                return redirect()->route('ViewSchedules',$doctor_id);

            } else {
                abort(403);
            }
        }

        public function show($id)
        {
            if( Auth::user()->can('view_medical_centers') ){
                $doctor                 = Doctor::where('id',$id)->with('doctor_image','centers','treatments')->withTrashed()->first();
                $doctor_qualification   = DB::table('doctor_qualification')->where('doctor_id',$id)->get();
                $doctor_certification   = DB::table('doctor_certification')->where('doctor_id',$id)->get();
                $doctor_views           = DB::table('doctor_views')->where('doctor_id',$id)->count();
                $doctor_schedules       = DB::table('center_doctor_schedule as cds')
                ->join('medical_centers as mc','mc.id','cds.center_id')
                ->select('cds.id','cds.center_id','mc.center_name',DB::raw('GROUP_CONCAT(cds.time_from) as time_from'),DB::raw('GROUP_CONCAT(cds.time_to) as time_to'),DB::raw('GROUP_CONCAT(cds.day_from) as day_from'),DB::raw('GROUP_CONCAT(cds.day_to) as day_to'),'cds.fare','cds.discount','cds.appointment_duration','cds.is_primary')
                ->where('cds.doctor_id',$id)
                ->groupBy('cds.center_id')
                ->get();
                $grouped_treatments     =   $doctor->treatments->groupBy('id');
                if (count($grouped_treatments)>0) {
                    foreach ($grouped_treatments as $gt) {
                        $treatment_names[]   =   $gt[0]->name;
                    }
                } else {
                    $treatment_names    =   NULL;
                }
                return view('adminpanel.doctors.show', compact('doctor','doctor_schedules','doctor_certification', 'doctor_qualification','treatment_names','doctor_views'));
            } else {
                abort(403);
            }
        }

        public function edit($id)
        {
            if (Auth::user()->can('edit_medical_center')) {
                $doctor = $this->service->edit_doctor($id);
                return view('adminpanel.doctors.edit', $doctor);
            }
            else {
                abort(403);
            }
        }
        public function approve_doctors_edit($id)
        {
            if (Auth::user()->can('edit_medical_center')) {
                $doctor = $this->service->edit_doctor($id);
                return view('adminpanel.doctors.edit_approval', $doctor);
            }
            else {
                abort(403);
            }
        }

        public function approve_doctor(Request $request, $id)
        {
        if( Auth::user()->can('edit_medical_center') ){
            $doctor             = $this->service->updateService($request,$id);
            if (isset($doctor[0]) != null) {
                session()->flash('error', $doctor[1]);
                return redirect()->route('doctors.edit',$id);
            } else {
                $doctor_db          =   Doctor::where('id',$id)->first();
                $update_users       =   DB::table('users')->where('doctor_id',$id)->update([
                    'is_approved'   =>  1,
                ]);
                $n              = '\n';
                $message        = "Congratulations!".$n.$n."Your+Profile+for+DoctorALL+App+has+been+Approved!".$n.$n."Welcome+onboard+$doctor_db->name".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400";
                $sms            = CustomerAppointmentSms($message, $doctor_db->phone);
                if($update_users){
                    $message  = "Your profile for DoctorALL App is approved";
                    NotificationHelper::GENERATE([
                        'title'     => 'Profile Approved',
                        'body'      => $message,
                        'payload'   => [
                            'type'  => "Profile Approved"
                        ]
                    ],[$id]);
                }
                session()->flash('success', 'Doctor Approved Successfully');
                return redirect()->route('ViewSchedules',$id);
            }
        } else {
            abort(403);
        }
    }
    public function destroy(Request $request)                                                // Soft Deleted
    {
        if( Auth::user()->can('delete_medical_center') ){
            $id = $request->id;
            $doctor = Doctor::where('id',$id)->with('doctor_image')->first();
            $doctor->deleted_by     = Auth::user()->id;
            $doctor->save();
            $doctor_user = User::where('doctor_id',$id)->delete();
            $deleted    =   $doctor->delete();
            session()->flash('success', 'Doctor Deleted Successfully');
            return response()->json(["data" =>"Doctor Deleted"]);
        } else {
            abort(403);
        }
    }
    public function Delete_Center_schedule(Request $request, $id)
    {
        $doctor_id              =   $request->doctor_id;
        $old_schedule           =   DB::table('center_doctor_schedule')->where(['doctor_id' => $doctor_id, 'center_id' => $id])->get();
        $count_old_schedule1    =   count($old_schedule);
        for ($i=0; $i < $count_old_schedule1; $i++) {
            $find_schedule[]      =   DB::table('doctor_treatments')->where('schedule_id',$old_schedule[$i]->id)->first();
        }
        $find_schedule      =   (array_values(array_filter($find_schedule)));

        $old_schedule_id    =   isset($find_schedule[0]) ? $find_schedule[0]->schedule_id : NULL;
        if ($old_schedule_id != NULL) {
            $delete_treatments  =   DB::table('doctor_treatments')->where('schedule_id',$old_schedule_id)->delete();
        }
        $delete_schedules   =   DB::table('center_doctor_schedule')->where(['doctor_id' => $doctor_id, 'center_id' => $id])->delete();
        if ($delete_schedules) {
            session()->flash('success', 'Doctor Deleted Successfully');
            return redirect()->back();
        }
        session()->flash('error', 'Something Went Wrong. Couldn\'t Delete');
        return redirect()->back();
    }
    public function per_delete(Request $request)                                                                                 // Permanent Delete data
    {
        $id = $request->id;
        if( Auth::user()->can('delete_medical_center') ){
            $doctor = Doctor::where('id',$id)->with('doctor_image')->withTrashed()->first();
            if (isset($doctor->doctor_image)) {
                $doctor_image = $doctor->doctor_image->forcedelete();
                //    DELETING image from Storage
                $doctor_image = DB::table('doctor_images')->where('doctor_id',$id)->select('picture')->first();
                if($doctor_image){
                    $image_path   =  public_path()."/backend/uploads/doctors/".$doctor_image->picture;
                    $imageMedium  =  public_path()."/backend/uploads/doctors/540x370-".$doctor_image->picture;
                    $imageSmall   =  public_path()."/backend/uploads/doctors/80x55-".$doctor_image->picture;
                    File::delete($image_path);
                    File::delete($imageMedium);
                    File::delete($imageSmall);
                    //DELETING Image from Database
                    $delete_image = DB::table('doctor_images')->where('doctor_id', $id)->delete();
                }
            }
            // DELETING Center Treatments from Database
            $delete_doctor_Treatments   =   DB::table('doctor_treatments')->where('doctor_id',$id)->delete();
            $delete_schedule            =   DB::table('center_doctor_schedule')->where('doctor_id',$id)->delete();

            $doctor_user  =   User::where('doctor_id',$id)->withTrashed()->first();
                if(isset($doctor_user)){
                    $delete_doctor_role     =   DB::table('role_user')->where('user_id',$doctor_user->id)->delete();
                    $device = FCMDevice::where('user_id',$doctor_user->id)->first();
                    if(isset($device)){
                        $device->delete();
                    }
                    User::where('doctor_id',$id)->forcedelete();
                }
            $doctor->forcedelete();
            session()->flash('success', 'Doctor Deleted Successfully');
            return response()->json(["data" =>"Doctor Deleted"]);
        } else {
            abort(403);
        }
    }
    public function restore(Request $request)                                                                        // Restore deleted data
    {
        if( Auth::user()->can('delete_medical_center') ){
            $id = $request->id;
            $doctor = Doctor::where('id',$id)->with('doctor_image')->withTrashed()->first();
            $doctor->restore();
            $doctor_user = User::where('doctor_id',$id)->withTrashed()->restore();
            session()->flash('success', 'Doctor Restore Successfully');
            return response()->json(["data" =>"Doctor Deleted"]);
        } else {
            abort(403);
        }
    }

}
