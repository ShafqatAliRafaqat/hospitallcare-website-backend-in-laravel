<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Center;
use App\Models\Admin\Customer;
use App\Models\Admin\Doctor;
use App\Models\Admin\Treatment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function report(Request $request){
        $users_db  = DB::table('role_user as ru')
                                ->join('users as u','ru.user_id','u.id')
                                ->where('ru.role_id',6)
                                ->OrWhere('ru.role_id',1)
                                ->OrWhere('ru.role_id',9)
                                ->select('ru.role_id','ru.user_id','u.name')
                                ->orderBy('u.name','ASC')
                                ->get();

        $centers_db =   Center::select('id','center_name')->orderBy('center_name','ASC')->get();
        $status_db = DB::table('status')->get();
        $treatments_db     = Treatment::select('id','name')->whereNotNull('parent_id')->orderBy('name','ASC')->get();

        if ( Auth::user()->can('report_generation') ) {
            $start   =  $request->start_date;
            $ending  =  $request->end_date;
            $end     =  Carbon::parse($ending)->addDay(1);
            if($request->center_id==NULL && $request->status_id==NULL && $request->patient_coordinator_id==NULL && $request->treatment_id==NULL){
                $customers  =   Customer::whereBetween('created_at', array($start, $end))->OrwhereBetween('updated_at', array($start, $end))->orderBy('updated_at','DESC')->get();
                $statuses   =   DB::table('status')->get();

                foreach ($customers as $customer) {
                    foreach ($statuses as $status) {
                        if($status->id == $customer['status_id']){
                            $customer['status_id'] = $status->name;
                        }
                    }
                    $users  =  Auth::user()->find($customer->patient_coordinator_id);
                    $customer['patient_coordinator_id']=  isset($users) ? $users->name : '';
                    $customer['notes']                 =  strip_tags($customer->notes);
                    $customer_details                  =  TreatmentsAndCenters($customer->id);
                    $customer['treatment']             =  $customer_details->treatment;
                    $customer['center']                =  $customer_details->center_name;
                    $customer['doctor']                =  $customer_details->doctor_name;
                    $customer['cost']                  =  $customer_details->cost;
                }
                return view('adminpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','treatments_db'));
            }                                                                       // Customer Report Generation End

                                                                                    // When There is only Center selected by Admin
            if($request->center_id && $request->status_id==NULL && $request->patient_coordinator_id==NULL && $request->treatment_id==NULL){
                $center_id  =   $request->center_id;
                // $center  =   Center::where('id',$center_id)->select('id','center_name')->first();
                $customers  =   DB::table('customer_procedures as cp')
                                    ->join('medical_centers as mc','mc.id','cp.hospital_id')
                                    ->join('customers as c','c.id','cp.customer_id')
                                    ->join('treatments as t','t.id','cp.treatments_id')
                                    ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                                    ->where('cp.hospital_id',$center_id)
                                    ->select('c.id as id','c.name as name','c.phone as phone','c.address','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','cp.cost as cost')
                                    ->Where(function ($q) use($start,$end){
                                            $q->orWhereBetween('c.updated_at', [$start, $end])
                                            ->orwhereBetween('c.created_at', array($start, $end));
                                    })
                                    ->orderBy('c.updated_at','DESC')
                                    ->get();
                $statuses   =   DB::table('status')->get();
                foreach ($customers as $customer) {                                     // Status ID Matching and returning names of it
                    foreach ($statuses as $status) {
                        if($status->id == $customer->status_id){
                            $customer->status_id     =   $status->name;
                        }
                    }
                    $users =  Auth::user()->find($customer->patient_coordinator_id);
                    $customer->patient_coordinator_id =  isset($users) ? $users->name :'';
                    // $customer['marital_status']    =  ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
                    $customer->notes                  =  strip_tags($customer->notes);
                }
                $center_name                =   centerName($request->center_id);
            return view('adminpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','center_name','patient_coordinator_name','status_name','treatment_name','center_id','status_id','treatment_id','patient_coordinator_id','treatments_db'));

            }// End of Only Center Selection
            // When There is only Status selected by Admin
            if ($request->center_id==NULL && $request->status_id && $request->patient_coordinator_id==NULL && $request->treatment_id==NULL) {
                $status_id  =   $request->status_id;
                $customers  =   Customer::where('status_id',$status_id)
                                        ->Where(function ($q) use($start,$end){
                                            $q->orWhereBetween('updated_at', [$start, $end])
                                            ->orwhereBetween('created_at', array($start, $end));
                                        })
                                        ->select('id','name','phone','address','patient_coordinator_id','status_id','notes')
                                        ->orderBy('updated_at','DESC')
                                        ->get();
                $statuses   =   DB::table('status')->get();

                foreach ($customers as $customer) {
                    // Gender ID Matching
                    // $customer['gender']         =   ($customer->gender == 1) ? 'Female' : 'Male';
                    // Status ID Matching and returning names of it

                    foreach ($statuses as $status) {

                        if($status->id == $customer['status_id']){
                            $customer['status_id']     =   $status->name;
                        }
                    }
                    $users  =  Auth::user()->find($customer->patient_coordinator_id);
                    $customer['patient_coordinator_id'] =   isset($users) ? $users->name : '';
                    // $customer['marital_status']      =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';

                    $customer['notes']      =   strip_tags($customer->notes);
                    $customer_details       =   TreatmentsAndCenters($customer->id);
                    $customer['treatment']  =   $customer_details->treatment;
                    $customer['center']     =   $customer_details->center_name;
                    $customer['doctor']                =  $customer_details->doctor_name;
                    $customer['cost']       =   $customer_details->cost;
                }
                $status_name                =   statusName($request->status_id);
            return view('adminpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','center_name','patient_coordinator_name','status_name','treatment_name','center_id','status_id','treatment_id','patient_coordinator_id','treatments_db'));

        }// End of only Status Selection

        // When Patient Owner is selected only
        if ($request->center_id==NULL && $request->status_id==NULL && $request->patient_coordinator_id && $request->treatment_id==NULL) {
            $id         =   $request ->patient_coordinator_id;
            $customers  =   Customer::where('patient_coordinator_id',$id)
                ->Where(function ($q) use($start,$end){
                    $q->orWhereBetween('updated_at', [$start, $end])
                    ->orwhereBetween('created_at', array($start, $end));
                })
                ->select('id','name','phone','address','patient_coordinator_id','status_id','notes')
                ->orderBy('updated_at','DESC')
                ->get();
                // dd($customers);
            $statuses   =   DB::table('status')->get();
            foreach ($customers as $customer) {
                // Gender ID Matching
                // $customer['gender']         =   ($customer->gender == 1) ? 'Female' : 'Male';
                // Status ID Matching and returning names of it
                foreach ($statuses as $status) {
                    if($status->id == $customer['status_id']){
                        $customer['status_id']     =   $status->name;
                    }
                }
                $users      =   Auth::user()->find($customer->patient_coordinator_id);
                $customer['patient_coordinator_id']     =   isset($users) ? $users->name : '';
                // $customer['marital_status']             =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
                $customer['notes']                      =   strip_tags($customer->notes);

                $customer_details                       =   TreatmentsAndCenters($customer->id);
                $customer['treatment']                 =   $customer_details->treatment;
                $customer['center']                    =   $customer_details->center_name;
                    $customer['doctor']                =  $customer_details->doctor_name;
                $customer['cost']                      =   $customer_details->cost;
        }
            $patient_coordinator_name       =   userName($request->patient_coordinator_id);
            $patient_coordinator_id         =   $request->patient_coordinator_id;
            return view('adminpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','center_name','patient_coordinator_name','status_name','treatment_name','center_id','status_id','treatment_id','patient_coordinator_id','treatments_db'));
        }// End of Patient Owner selected only

        // When Center and Status are slected only
        if($request->center_id && $request->status_id && $request->patient_coordinator_id==NULL && $request->treatment_id==NULL){
            $center_id  =   $request->center_id;
            $status_id  =   $request->status_id;
            $matchThese = ['cp.hospital_id' => $center_id, 'c.status_id' => $status_id];
            $customers     =   DB::table('customer_procedures as cp')
                            ->join('medical_centers as mc','mc.id','cp.hospital_id')
                            ->join('customers as c','c.id','cp.customer_id')
                            ->join('treatments as t','t.id','cp.treatments_id')
                            ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                            ->where($matchThese)
                            ->select('c.id as id','c.name as name','c.phone as phone','c.address','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','cp.cost as cost')
                            ->Where(function ($q) use($start,$end){
                                    $q->orWhereBetween('c.updated_at', [$start, $end])
                                    ->orwhereBetween('c.created_at', array($start, $end));
                            })
                            ->orderBy('c.updated_at','DESC')
                            ->get();
        $statuses   =   DB::table('status')->get();
        foreach ($customers as $customer) {
            // Status ID Matching and returning names of it
            foreach ($statuses as $status) {
                if($status->id == $customer->status_id){
                    $customer->status_id     =   $status->name;
                }
            }
            $users      =   Auth::user()->find($customer->patient_coordinator_id);
            $customer->patient_coordinator_id    =  isset($users) ? $users->name : '';
            // $customer['marital_status']             =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
            $customer->notes                      =   strip_tags($customer->notes);
        }
                $center_name                =   centerName($request->center_id);
                $status_name                =   statusName($request->status_id);
            return view('adminpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','center_name','patient_coordinator_name','status_name','treatment_name','center_id','status_id','treatment_id','patient_coordinator_id','treatments_db'));
        }// End of Center and Status Selection


        // When Center and Patient Owner is selected
        if ($request->center_id && $request->status_id==NULL && $request->patient_coordinator_id && $request->treatment_id==NULL) {
            $center_id      =   $request->center_id;
            $id             =   $request->patient_coordinator_id;
            $matchThese = ['cp.hospital_id' => $center_id, 'c.patient_coordinator_id' => $id];
            $customers     =   DB::table('customer_procedures as cp')
                            ->join('medical_centers as mc','mc.id','cp.hospital_id')
                            ->join('customers as c','c.id','cp.customer_id')
                            ->join('treatments as t','t.id','cp.treatments_id')
                            ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                            ->where($matchThese)
                            ->select('c.id as id','c.name as name','c.phone as phone','c.address','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','cp.cost as cost')
                            ->Where(function ($q) use($start,$end){
                                    $q->orWhereBetween('c.updated_at', [$start, $end])
                                    ->orwhereBetween('c.created_at', array($start, $end));
                            })
                            ->get();
        $statuses   =   DB::table('status')->get();
        foreach ($customers as $customer) {
            // Status ID Matching and returning names of it
            foreach ($statuses as $status) {
                if($status->id == $customer->status_id){
                    $customer->status_id     =   $status->name;
                }
            }
            $users      =   Auth::user()->find($customer->patient_coordinator_id);
            $customer->patient_coordinator_id    =   isset($users) ? $users->name : '';
            // $customer['marital_status']             =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
            $customer->notes                      =   strip_tags($customer->notes);
        }
            $center_name                    =   centerName($request->center_id);
            $patient_coordinator_name       =   userName($request->patient_coordinator_id);
            $patient_coordinator_id         =   $request->patient_coordinator_id;
            return view('adminpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','center_name','patient_coordinator_name','status_name','treatment_name','center_id','status_id','treatment_id','patient_coordinator_id','treatments_db'));

        }// End of Center and Patient Owner selected


        // When Status and Patient Owner is selected
        if ($request->center_id==NULL && $request->status_id && $request->patient_coordinator_id && $request->treatment_id==NULL) {
            $status_id      =   $request->status_id;
            $id             =   $request->patient_coordinator_id;
            $matchThese = ['c.status_id' => $status_id, 'c.patient_coordinator_id' => $id];
            $customers     =   DB::table('customer_procedures as cp')
                            ->join('medical_centers as mc','mc.id','cp.hospital_id')
                            ->join('customers as c','c.id','cp.customer_id')
                            ->join('treatments as t','t.id','cp.treatments_id')
                            ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                            ->where($matchThese)
                            ->select('c.id as id','c.name as name','c.phone as phone','c.address','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','cp.cost as cost')
                            ->Where(function ($q) use($start,$end){
                                    $q->orWhereBetween('c.updated_at', [$start, $end])
                                    ->orwhereBetween('c.created_at', array($start, $end));
                            })
                            ->orderBy('c.updated_at','DESC')
                            ->get();
        $statuses   =   DB::table('status')->get();
        foreach ($customers as $customer) {
            // Status ID Matching and returning names of it
            foreach ($statuses as $status) {
                if($status->id == $customer->status_id){
                    $customer->status_id     =   $status->name;
                }
            }
            $users      =   Auth::user()->find($customer->patient_coordinator_id);
            $customer->patient_coordinator_id   =  isset($users) ? $users->name : '';
            // $customer['marital_status']      =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
            $customer->notes                    =   strip_tags($customer->notes);
        }
                $status_name                    =   statusName($request->status_id);
                $patient_coordinator_name       =   userName($request->patient_coordinator_id);
            return view('adminpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','center_name','patient_coordinator_name','status_name','treatment_name','center_id','status_id','treatment_id','patient_coordinator_id','treatments_db'));
        }// End of Status and Patient Owner selected

        //When Three are Selected
        if ($request->center_id && $request->status_id && $request->patient_coordinator_id && $request->treatment_id==NULL) {
            $center_id      =   $request->center_id;
            $id             =   $request->patient_coordinator_id;
            $status_id      =   $request->status_id;
            $matchThese = ['c.status_id' => $status_id, 'c.patient_coordinator_id' => $id, 'cp.hospital_id'  =>  $center_id];
                        $customers     =   DB::table('customer_procedures as cp')
                            ->join('medical_centers as mc','mc.id','cp.hospital_id')
                            ->join('customers as c','c.id','cp.customer_id')
                            ->join('treatments as t','t.id','cp.treatments_id')
                            ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                            ->where($matchThese)
                            ->select('c.id as id','c.name as name','c.phone as phone','c.address','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','cp.cost as cost')
                            ->Where(function ($q) use($start,$end){
                                    $q->orWhereBetween('c.updated_at', [$start, $end])
                                    ->orwhereBetween('c.created_at', array($start, $end));
                            })
                            ->orderBy('c.updated_at','DESC')
                            ->get();
            $statuses   =   DB::table('status')->get();
            foreach ($customers as $customer) {
            // Status ID Matching and returning names of it
                foreach ($statuses as $status) {
                    if($status->id == $customer->status_id){
                        $customer->status_id     =   $status->name;
                    }
                }
                $users      =   Auth::user()->find($customer->patient_coordinator_id);
                $customer->patient_coordinator_id    =  isset($users) ? $users->name :'';
                // $customer['marital_status']             =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
                $customer->notes                      =   strip_tags($customer->notes);
        }
            $center_name                    =   centerName($request->center_id);
            $status_name                    =   statusName($request->status_id);
            $patient_coordinator_name       =   userName($request->patient_coordinator_id);
            $patient_coordinator_id         =   $request->patient_coordinator_id;
            return view('adminpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','center_name','patient_coordinator_name','status_name','treatment_name','center_id','status_id','treatment_id','patient_coordinator_id','treatments_db'));

        } //End of All are Selected

        // When only Treatment Id is Selected
        if ($request->center_id==NULL && $request->status_id==NULL && $request->patient_coordinator_id==NULL && $request->treatment_id) {
            $treatment_id   =   $request->treatment_id;
            $matchThese     = ['cp.treatments_id' => $treatment_id];
            $customers      =   DB::table('customer_procedures as cp')
                            ->join('medical_centers as mc','mc.id','cp.hospital_id')
                            ->join('customers as c','c.id','cp.customer_id')
                            ->join('treatments as t','t.id','cp.treatments_id')
                            ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                            ->where($matchThese)
                            ->select('c.id as id','c.name as name','c.phone as phone','c.address','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','cp.cost as cost')
                            ->Where(function ($q) use($start,$end){
                                    $q->orWhereBetween('c.updated_at', [$start, $end])
                                    ->orwhereBetween('c.created_at', array($start, $end));
                            })
                            ->orderBy('c.updated_at','DESC')
                            ->get();
        $statuses   =   DB::table('status')->get();
        foreach ($customers as $customer) {
            // Status ID Matching and returning names of it
            foreach ($statuses as $status) {
                if($status->id == $customer->status_id){
                    $customer->status_id     =   $status->name;
                }
            }
            $users                              =   Auth::user()->find($customer->patient_coordinator_id);
            $customer->patient_coordinator_id   =   isset($users) ? $users->name :'';
            // $customer['marital_status']      =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
            $customer->notes                    =   strip_tags($customer->notes);
            $treatment_name                     =   TreatmentName($treatment_id);
        }
            return view('adminpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','center_name','patient_coordinator_name','status_name','treatment_name','center_id','status_id','treatment_id','patient_coordinator_id','treatments_db'));
        }// End of when only Treatment Id is Selected

        // When Center_id and Treatment Id is Selected
        if ($request->center_id && $request->status_id==NULL && $request->patient_coordinator_id==NULL && $request->treatment_id) {
            $treatment_id   =   $request->treatment_id;
            $center_id      =   $request->center_id;
            $matchThese     = ['cp.treatments_id' => $treatment_id, 'cp.hospital_id' => $center_id];
            $customers      =   DB::table('customer_procedures as cp')
                                ->join('medical_centers as mc','mc.id','cp.hospital_id')
                                ->join('customers as c','c.id','cp.customer_id')
                                ->join('treatments as t','t.id','cp.treatments_id')
                                ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                                ->where($matchThese)
                                ->select('c.id as id','c.name as name','c.phone as phone','c.address','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','d.name as doctor','cp.cost as cost')
                                ->Where(function ($q) use($start,$end){
                                        $q->orWhereBetween('c.updated_at', [$start, $end])
                                        ->orwhereBetween('c.created_at', array($start, $end));
                                })
                                ->orderBy('c.updated_at','DESC')
                                ->get();
        $statuses   =   DB::table('status')->get();
        foreach ($customers as $customer) {
            // Status ID Matching and returning names of it
            foreach ($statuses as $status) {
                if($status->id == $customer->status_id){
                    $customer->status_id     =   $status->name;
                }
            }
            $users                              =   Auth::user()->find($customer->patient_coordinator_id);
            $customer->patient_coordinator_id   =   isset($users) ? $users->name :'';
            // $customer['marital_status']      =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
            $customer->notes                    =   strip_tags($customer->notes);
            $center_name                        =   centerName($request->center_id);
            $treatment_name                     =   TreatmentName($treatment_id);

        }
            return view('adminpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','center_name','patient_coordinator_name','status_name','treatment_name','center_id','status_id','treatment_id','patient_coordinator_id','treatments_db'));
        }// End of when Center_id and Treatment Id is Selected

        // When status_id and Treatment Id is Selected
        if ($request->center_id==NULL && $request->status_id && $request->patient_coordinator_id==NULL && $request->treatment_id) {
            $treatment_id   =   $request->treatment_id;
            $status_id      =   $request->status_id;
            $matchThese     = ['cp.treatments_id' => $treatment_id, 'c.status_id' => $status_id];
            $customers      =   DB::table('customer_procedures as cp')
                                ->join('medical_centers as mc','mc.id','cp.hospital_id')
                                ->join('customers as c','c.id','cp.customer_id')
                                ->join('treatments as t','t.id','cp.treatments_id')
                                ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                                ->where($matchThese)
                                ->select('c.id as id','c.name as name','c.phone as phone','c.address','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','cp.cost as cost')
                                ->Where(function ($q) use($start,$end){
                                        $q->orWhereBetween('c.updated_at', [$start, $end])
                                        ->orwhereBetween('c.created_at', array($start, $end));
                                })
                                ->orderBy('c.updated_at','DESC')
                                ->get();
        $statuses   =   DB::table('status')->get();
        foreach ($customers as $customer) {
            // Status ID Matching and returning names of it
            foreach ($statuses as $status) {
                if($status->id == $customer->status_id){
                    $customer->status_id     =   $status->name;
                }
            }
            $users                              =   Auth::user()->find($customer->patient_coordinator_id);
            $customer->patient_coordinator_id   =   isset($users) ? $users->name :'';
            // $customer['marital_status']      =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
            $customer->notes                    =   strip_tags($customer->notes);
            $status_name                        =   statusName($request->status_id);
            $treatment_name                     =   TreatmentName($treatment_id);

        }
            return view('adminpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','center_name','patient_coordinator_name','status_name','treatment_name','center_id','status_id','treatment_id','patient_coordinator_id','treatments_db'));
        }// End of when status_id and Treatment Id is Selected

        // When Patient_Owner_id and Treatment_id is Selected
        if ($request->center_id==NULL && $request->status_id==NULL  && $request->patient_coordinator_id && $request->treatment_id) {
            $treatment_id   =   $request->treatment_id;
            $patient_coordinator_id      =   $request->patient_coordinator_id;
            $matchThese     = ['cp.treatments_id' => $treatment_id, 'c.patient_coordinator_id' => $patient_coordinator_id];
            $customers      =   DB::table('customer_procedures as cp')
                                ->join('medical_centers as mc','mc.id','cp.hospital_id')
                                ->join('customers as c','c.id','cp.customer_id')
                                ->join('treatments as t','t.id','cp.treatments_id')
                                ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                                ->where($matchThese)
                                ->select('c.id as id','c.name as name','c.phone as phone','c.address','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','cp.cost as cost')
                                ->Where(function ($q) use($start,$end){
                                        $q->orWhereBetween('c.updated_at', [$start, $end])
                                        ->orwhereBetween('c.created_at', array($start, $end));
                                })
                                ->orderBy('c.updated_at','DESC')
                                ->get();
        $statuses   =   DB::table('status')->get();
        foreach ($customers as $customer) {
            // Status ID Matching and returning names of it
            foreach ($statuses as $status) {
                if($status->id == $customer->status_id){
                    $customer->status_id     =   $status->name;
                }
            }
            $users                              =   Auth::user()->find($customer->patient_coordinator_id);
            $customer->patient_coordinator_id   =   isset($users) ? $users->name :'';
            // $customer['marital_status']      =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
            $customer->notes                    =   strip_tags($customer->notes);
            $patient_coordinator_name           =   userName($request->patient_coordinator_id);
            $treatment_name                     =   TreatmentName($treatment_id);

        }
            return view('adminpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','center_name','patient_coordinator_name','status_name','treatment_name','center_id','status_id','treatment_id','patient_coordinator_id','treatments_db'));
        }// End of when Patient_Owner_id and Treatment Id is Selected

        // When Center_id Patient_Owner_id and Treatment_id is Selected
        if ($request->center_id && $request->status_id==NULL  && $request->patient_coordinator_id && $request->treatment_id) {
            $treatment_id   =   $request->treatment_id;
            $center_id   =   $request->center_id;
            $patient_coordinator_id      =   $request->patient_coordinator_id;
            $matchThese     = ['cp.treatments_id' => $treatment_id, 'c.patient_coordinator_id' => $patient_coordinator_id,'cp.hospital_id' => $center_id];
            $customers      =   DB::table('customer_procedures as cp')
                                ->join('medical_centers as mc','mc.id','cp.hospital_id')
                                ->join('customers as c','c.id','cp.customer_id')
                                ->join('treatments as t','t.id','cp.treatments_id')
                                ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                                ->where($matchThese)
                                ->select('c.id as id','c.name as name','c.phone as phone','c.address','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','cp.cost as cost')
                                ->Where(function ($q) use($start,$end){
                                        $q->orWhereBetween('c.updated_at', [$start, $end])
                                        ->orwhereBetween('c.created_at', array($start, $end));
                                })
                                ->orderBy('c.updated_at','DESC')
                                ->get();
            $statuses   =   DB::table('status')->get();
        foreach ($customers as $customer) {
            // Status ID Matching and returning names of it
            foreach ($statuses as $status) {
                if($status->id == $customer->status_id){
                    $customer->status_id     =   $status->name;
                }
            }
            $users                              =   Auth::user()->find($customer->patient_coordinator_id);
            $customer->patient_coordinator_id   =   isset($users) ? $users->name :'';
            // $customer['marital_status']      =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
            $customer->notes                    =   strip_tags($customer->notes);
            $patient_coordinator_name           =   userName($request->patient_coordinator_id);
            $treatment_name                     =   TreatmentName($treatment_id);
            $center_name                        =   centerName($request->center_id);

        }
            return view('adminpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','center_name','patient_coordinator_name','status_name','treatment_name','center_id','status_id','treatment_id','patient_coordinator_id','treatments_db'));
        }// End of when Center_id Patient_Owner_id and Treatment Id is Selected

         // When status_id Patient_Owner_id and Treatment_id is Selected
        if ($request->center_id==NULL && $request->status_id  && $request->patient_coordinator_id && $request->treatment_id) {
            $treatment_id   =   $request->treatment_id;
            $status_id      =   $request->status_id;
            $patient_coordinator_id      =   $request->patient_coordinator_id;
            $matchThese     =   ['cp.treatments_id' => $treatment_id, 'c.patient_coordinator_id' => $patient_coordinator_id,'c.status_id' => $status_id];
            $customers      =   DB::table('customer_procedures as cp')
                                ->join('medical_centers as mc','mc.id','cp.hospital_id')
                                ->join('customers as c','c.id','cp.customer_id')
                                ->join('treatments as t','t.id','cp.treatments_id')
                                ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                                ->where($matchThese)
                                ->select('c.id as id','c.name as name','c.phone as phone','c.address','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','cp.cost as cost')
                                ->Where(function ($q) use($start,$end){
                                        $q->orWhereBetween('c.updated_at', [$start, $end])
                                        ->orwhereBetween('c.created_at', array($start, $end));
                                })
                                ->orderBy('c.updated_at','DESC')
                                ->get();
            $statuses   =   DB::table('status')->get();
        foreach ($customers as $customer) {
            // Status ID Matching and returning names of it
            foreach ($statuses as $status) {
                if($status->id == $customer->status_id){
                    $customer->status_id     =   $status->name;
                }
            }
            $users                              =   Auth::user()->find($customer->patient_coordinator_id);
            $customer->patient_coordinator_id   =  isset($users) ? $users->name :'';
            // $customer['marital_status']      =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
            $customer->notes                    =   strip_tags($customer->notes);
            $patient_coordinator_name           =   userName($request->patient_coordinator_id);
            $treatment_name                     =   TreatmentName($treatment_id);
            $status_name                        =   statusName($request->status_id);
        }
            return view('adminpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','center_name','patient_coordinator_name','status_name','treatment_name','center_id','status_id','treatment_id','patient_coordinator_id','treatments_db'));
        }// End of when status_id Patient_Owner_id and Treatment Id is Selected

         // When status_id center_id and Treatment_id is Selected
        if ($request->center_id && $request->status_id  && $request->patient_coordinator_id==NULL && $request->treatment_id) {
            $treatment_id   =   $request->treatment_id;
            $status_id      =   $request->status_id;
            $center_id      =   $request->center_id;
            $matchThese     =   ['cp.treatments_id' => $treatment_id, 'cp.hospital_id' => $center_id,'c.status_id' => $status_id];
            $customers      =   DB::table('customer_procedures as cp')
                                ->join('medical_centers as mc','mc.id','cp.hospital_id')
                                ->join('customers as c','c.id','cp.customer_id')
                                ->join('treatments as t','t.id','cp.treatments_id')
                                ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                                ->where($matchThese)
                                ->select('c.id as id','c.name as name','c.phone as phone','c.address','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','cp.cost as cost')
                                ->Where(function ($q) use($start,$end){
                                        $q->orWhereBetween('c.updated_at', [$start, $end])
                                        ->orwhereBetween('c.created_at', array($start, $end));
                                })
                                ->orderBy('c.updated_at','DESC')
                                ->get();
            $statuses   =   DB::table('status')->get();
        foreach ($customers as $customer) {
            // Status ID Matching and returning names of it
            foreach ($statuses as $status) {
                if($status->id == $customer->status_id){
                    $customer->status_id     =   $status->name;
                }
            }
            $users                              =   Auth::user()->find($customer->patient_coordinator_id);
            $customer->patient_coordinator_id   =   isset($users) ? $users->name :'';
            // $customer['marital_status']      =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
            $customer->notes                    =   strip_tags($customer->notes);
            $center_name                        =   centerName($request->center_id);
            $treatment_name                     =   TreatmentName($treatment_id);
            $status_name                        =   statusName($request->status_id);

        }
            return view('adminpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','center_name','patient_coordinator_name','status_name','treatment_name','center_id','status_id','treatment_id','patient_coordinator_id','treatments_db'));
        }// End of when center_id, status_id and Treatment Id are Selected

        // When All are Selected
        if ($request->center_id && $request->status_id  && $request->patient_coordinator_id && $request->treatment_id) {
            $treatment_id   =   $request->treatment_id;
            $status_id      =   $request->status_id;
            $center_id      =   $request->center_id;
            $patient_coordinator_id             =   $request->patient_coordinator_id;
            $matchThese     =   ['cp.treatments_id' => $treatment_id, 'cp.hospital_id' => $center_id,'c.status_id' => $status_id,'c.patient_coordinator_id' => $patient_coordinator_id];
            $customers      =   DB::table('customer_procedures as cp')
                                ->join('medical_centers as mc','mc.id','cp.hospital_id')
                                ->join('customers as c','c.id','cp.customer_id')
                                ->join('treatments as t','t.id','cp.treatments_id')
                                ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                                ->where($matchThese)
                                ->select('c.id as id','c.name as name','c.phone as phone','c.address','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','cp.cost as cost')
                                ->Where(function ($q) use($start,$end){
                                        $q->orWhereBetween('c.updated_at', [$start, $end])
                                        ->orwhereBetween('c.created_at', array($start, $end));
                                })
                                ->orderBy('c.updated_at','DESC')
                                ->get();
            $statuses   =   DB::table('status')->get();
        foreach ($customers as $customer) {
            // Status ID Matching and returning names of it
            foreach ($statuses as $status) {
                if($status->id == $customer->status_id){
                    $customer->status_id     =   $status->name;
                }
            }
            $users                              =   Auth::user()->find($customer->patient_coordinator_id);
            $customer->patient_coordinator_id   =   isset($users) ? $users->name :'';
            // $customer['marital_status']      =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
            $customer->notes                    =   strip_tags($customer->notes);
            $center_name                        =   centerName($request->center_id);
            $treatment_name                     =   TreatmentName($treatment_id);
            $status_name                        =   statusName($request->status_id);
            $patient_coordinator_name           =   userName($request->patient_coordinator_id);
        }
            return view('adminpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','center_name','patient_coordinator_name','status_name','treatment_name','center_id','status_id','treatment_id','patient_coordinator_id','treatments_db'));
        }// End of when center_id, status_id and Treatment Id are Selected

        } else {
              abort(403);
          }
    }

    public function clientsreport(Request $request){
        if ( Auth::user()->can('client_report_generation') ) {

            $start          =   $request->start_date;
            $ending         =   $request->end_date;
            $end            =   Carbon::parse($ending)->addDay(1);
            $users_db       = DB::table('role_user as ru')
                            ->join('users as u','ru.user_id','u.id')
                            ->where('ru.role_id',6)
                            ->OrWhere('ru.role_id',1)
                            ->select('ru.role_id','ru.user_id','u.name')
                            ->get();
            $centers_db             =   Center::select('id','center_name')->get();
            $status_db              = DB::table('status')->limit(4)->get();
            $center_id              =   Auth::user()->medical_center_id;
            $center_clients         =   Center::where('id',$center_id)->with('customer')->first();
            if($request->status_id==NULL){
                $customers                =   $center_clients->customer->whereBetween('updated_at', array($start, $end));
                if(count($customers) > 0){
                        $status_name  =   "Not Selected";
                        return view('centerpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','status_name'));
                } else{
                    return view('centerpanel.reports.no-show',compact('start','ending','customers','users_db','centers_db','status_db'));
                }
            }

            if ($request->status_id) {
                $status_id          =   $request->status_id;
                $customers          =   $center_clients->customer->where('status_id',$status_id)->whereBetween('updated_at', array($start, $end));
                if(count($customers) > 0){
                    $statuses   =   DB::table('status')->get();
                    foreach ($customers as $customer) {
                        foreach ($statuses as $status) {
                            if($status->id == $customer['status_id']){
                                $customer['status_id']     =   $status->name;
                                $status_name  =   $status->name;
                            }
                        }
                    }

                return view('centerpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','center_name','patient_coordinator_name','status_name','status_id'));
                } else{
                return view('centerpanel.reports.no-show',compact('start','ending','customers','users_db','centers_db','status_db'));
                }
            }

        } else {
              abort(403);
        }
    }

    public function doctorclientsreport(Request $request){
        if ( Auth::user()->can('client_report_generation') ) {
            $start          =   $request->start_date;
            $ending         =   $request->end_date;
            $end            =   Carbon::parse($ending)->addDay(1);
            $users_db       =   DB::table('role_user as ru')
                                ->join('users as u','ru.user_id','u.id')
                                ->where('ru.role_id',6)
                                ->OrWhere('ru.role_id',1)
                                ->select('ru.role_id','ru.user_id','u.name')
                                ->get();
            $centers_db             =   Doctor::select('id','name')->get();
            $status_db              =   DB::table('status')->limit(4)->get();
            $doctor_id              =   Auth::user()->doctor_id;
            $center_clients         =   Doctor::where('id',$doctor_id)->with('customers')->first();
            if($request->status_id==NULL){
                $customers                =   $center_clients->customers->whereBetween('updated_at', array($start, $end));
                if(count($customers) > 0){
                        $status_name  =   "Not Selected";
                        return view('doctorpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','status_name'));
                } else{
                    return view('doctorpanel.reports.no-show',compact('start','ending','customers','users_db','centers_db','status_db'));
                }
            }

            if ($request->status_id) {
                $status_id          =   $request->status_id;
                $customers          =   $center_clients->customers->where('status_id',$status_id)->whereBetween('updated_at', array($start, $end));
                if(count($customers) > 0){
                    $statuses   =   DB::table('status')->get();
                    foreach ($customers as $customer) {
                        foreach ($statuses as $status) {
                            if($status->id == $customer['status_id']){
                                $customer['status_id']     =   $status->name;
                                $status_name  =   $status->name;
                            }
                        }
                    }

                return view('doctorpanel.reports.show',compact('start','ending','customers','users_db','centers_db','status_db','center_name','patient_coordinator_name','status_name','status_id'));
                } else{
                return view('doctorpanel.reports.no-show',compact('start','ending','customers','users_db','centers_db','status_db'));
                }
            }

        } else {
              abort(403);
        }
    }

    //Fetching Customers who were created today
    public function CreatedToday(){
        if (Auth::user()->can('report_generation')) {
            $info       =   "Created Today";
            $customers  =   Customer::whereRaw('Date(created_at) = CURDATE()')->whereRaw('Date(created_at) = Date(updated_at)')->with(['treatments','center'])->get();
            return view('adminpanel.reports.view_customers', compact('customers','info'));
        }
    }

    //Fetching Customers who were Updated today
    public function UpdatedToday(){
        if (Auth::user()->can('report_generation')) {
            $info       =   "Updated Today";
            $customers   =   Customer::whereRaw('Date(updated_at) = CURDATE()')->whereRaw('Date(created_at) != Date(updated_at)')->with(['treatments','center'])->get();
            return view('adminpanel.reports.view_customers', compact('customers','info'));
        }
    }

    //Fetching Customers who were created This Week
    public function CreatedThisWeek(){
        if (Auth::user()->can('report_generation')) {
            $info       =   "Created This Week";
            Carbon::setWeekStartsAt(Carbon::MONDAY);
            $customers   =   Customer::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                        ->whereRaw('Date(created_at) = Date(updated_at)')->with(['treatments','center'])->get();
            return view('adminpanel.reports.view_customers', compact('customers','info'));
        }
    }

    //Fetching Customers who were Updated This Week
    public function UpdatedThisWeek(){
        if (Auth::user()->can('report_generation')) {
            $info       =   "Updated This Week";
            Carbon::setWeekStartsAt(Carbon::MONDAY);
            $customers   =   Customer::whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                        ->whereRaw('Date(created_at) != Date(updated_at)')->with(['treatments','center'])->get();
            return view('adminpanel.reports.view_customers', compact('customers','info'));
        }
    }

    //Fetching Customers who were created This Month
    public function CreatedThisMonth(){
        if (Auth::user()->can('report_generation')) {
            $info       =   "Created This Month";
            $customers   =   Customer::where('created_at', '>=', Carbon::now()->startOfMonth()->toDateString())
                        ->whereRaw('Date(created_at) = Date(updated_at)')->with(['treatments','center'])->get();
            return view('adminpanel.reports.view_customers', compact('customers','info'));
        }
    }

    //Fetching Customers who were Updated This Month
    public function UpdatedThisMonth(){
        if (Auth::user()->can('report_generation')) {
            $info       =   "Updated This Month";
            $customers   =   Customer::where('updated_at', '>=', Carbon::now()->startOfMonth()->toDateString())
                            ->whereRaw('Date(created_at) != Date(updated_at)')->with(['treatments','center'])->get();
            return view('adminpanel.reports.view_customers', compact('customers','info'));
        }
    }

    //Fetching Customers who were created Previous Month
    public function CreatedPreviousWeek(){
        if (Auth::user()->can('report_generation')) {
            $info       =   "Created Previous Week";
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            $currentDate = \Carbon\Carbon::now();
            $agoDate = $currentDate->copy()->subDays($currentDate->dayOfWeek)->subWeek()->setTime(23, 59, 59);
            $now = Carbon::now()->startOfWeek();
            $customers   =   Customer::whereBetween('created_at', array($agoDate, $now))
                          ->whereRaw('Date(created_at) = Date(updated_at)')->with(['treatments','center'])->get();
            return view('adminpanel.reports.view_customers', compact('customers','info'));
        }
    }

    //Fetching Customers who were Updated Previous Month
    public function UpdatedPreviousWeek(){
        if (Auth::user()->can('report_generation')) {
            $info       =   "Updated Previous Week";
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            $currentDate = \Carbon\Carbon::now();
            $agoDate = $currentDate->copy()->subDays($currentDate->dayOfWeek)->subWeek()->setTime(23, 59, 59);
            $now = Carbon::now()->startOfWeek();
            $customers   =   Customer::whereBetween('updated_at', array($agoDate, $now))
                          ->whereRaw('Date(created_at) != Date(updated_at)')->with(['treatments','center'])->get();
            return view('adminpanel.reports.view_customers', compact('customers','info'));
        }
    }

    //Fetching Customers who were created This Year
    public function CreatedThisYear(){
        if (Auth::user()->can('report_generation')) {
            $info       =   "Created This Year";
            $now = Carbon::now();
            $this_year = $now->year;
            $customers   =   Customer::whereRaw("YEAR(created_at) = $this_year")
                        ->whereRaw('Date(created_at) = Date(updated_at)')->with(['treatments','center'])->get();
            return view('adminpanel.reports.view_customers', compact('customers','info'));
        }
    }

    //Fetching Customers who were Updated This Year
    public function UpdatedThisYear(){
        if (Auth::user()->can('report_generation')) {
            $info       =   "Updated This Year";
            $now = Carbon::now();
            $this_year = $now->year;
            $customers   =   Customer::whereRaw("YEAR(updated_at) = $this_year")
                        ->whereRaw('Date(created_at) != Date(updated_at)')->with(['treatments','center'])->get();
            return view('adminpanel.reports.view_customers', compact('customers','info'));
        }
    }

    //Fetching Cold Leads
    public function ColdLeads(){
        if (Auth::user()->can('report_generation')) {
            $info           =   "All Cold Leads";
            $customers      =   Customer::where('status_id',1)->with(['treatments','center'])->get();
            return view('adminpanel.reports.view_customers', compact('customers','info'));
        }
    }

    //Fetching Warm Leads
    public function WarmLeads(){
        if (Auth::user()->can('report_generation')) {
            $info   =   "All Warm Leads";
            $customers      =   Customer::where('status_id',2)->with(['treatments','center'])->get();
            return view('adminpanel.reports.view_customers', compact('customers','info'));
        }
    }

    //Fetching Hot Leads
    public function HotLeads(){
        if (Auth::user()->can('report_generation')) {
            $info   =   "All Hot Leads";
            $customers      =   Customer::where('status_id',3)->with(['treatments','center'])->get();
            return view('adminpanel.reports.view_customers', compact('customers','info'));
        }
    }

    //Fetching Customer Leads
    public function CustomerLeads(){
        if (Auth::user()->can('report_generation')) {
            $info   =   "All Customer Leads";
            $customers      =   Customer::where('status_id',4)->with(['treatments','center'])->get();
            return view('adminpanel.reports.view_customers', compact('customers','info'));
        }
    }

    //Fetching No Contact Leads
    public function NoContactLeads(){
        if (Auth::user()->can('report_generation')) {
            $info   =   "All No Contact Leads";
            $customers      =   Customer::where('status_id',5)->with(['treatments','center'])->get();
            return view('adminpanel.reports.view_customers', compact('customers','info'));
        }
    }

    //Fetching Don't Call Leads
    public function DontCallLeads(){
        if (Auth::user()->can('report_generation')) {
            $info   =   "All Don't Call Leads";
            $customers      =   Customer::where('status_id',9)->with(['treatments','center'])->get();
            return view('adminpanel.reports.view_customers', compact('customers','info'));
        }
    }

}
