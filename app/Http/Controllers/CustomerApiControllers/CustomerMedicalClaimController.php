<?php

namespace App\Http\Controllers\CustomerApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerMedicalClaimController extends Controller
{
    public function allClaims()
    {
        $customer_id        =   Auth::user()->customer_id;
        $claims             =   DB::table('customer_claims as cc')
                                ->join('customers as c','c.id','cc.appointment_for')
                                ->leftjoin('customer_images as ci','ci.customer_id','cc.appointment_for')
                                ->where('cc.customer_id',$customer_id)
                                ->select('cc.id as claim_id','cc.title','cc.appointment_for as appointment_for','cc.status as status','c.name as customer_name','c.relation','cc.created_at as created_on','cc.comment as comment','ci.picture')
                                ->orderBy('cc.updated_at','DESC')
                                ->get();
        return json_encode(['data'  =>  $claims]);
    }
    public function customerTreatmentHistory(Request $request ,$id)
    {
        if ($request->type == 'treatments') {
            $cth    =   [];
            $cth    =   DB::table('customer_treatment_history as cth')
                        ->join('treatments as t','t.id','cth.treatments_id')
                        ->join('doctors as d','d.id','cth.doctor_id')
                        ->where('cth.customer_id',$id)
                        ->where('cth.claimed',0)
                        ->select('cth.id as cth_id','t.name as treatment_name','d.name as doctor_name','cth.appointment_date as appointment_date')
                        ->get();
            if (count($cth) > 0) {
                return json_encode(['data' => $cth, 'message' => 'Treatments Found'],200);
            } else {
                return json_encode(['data'  =>  $cth,'message' => "No Treatments Found"],200);
            }
        } else if ($request->type == 'diagnostics') {
            $cdh    =   [];
            $cdh    =   DB::table('customer_diagnostic_history as cdh')
                        // ->join('diagnostics as d','d.id','cdh.diagnostic_id')
                        ->join('labs as l','l.id','cdh.lab_id')
                        ->where('cdh.customer_id',$id)
                        ->where('cdh.claimed',0)
                        ->select('cdh.id as cdh_id','l.name as lab_name','cdh.appointment_date as appointment_date','cdh.bundle_id as bundle_id')
                        ->groupBy('cdh.bundle_id')
                        ->get();
            if (count($cdh) > 0) {
                return json_encode(['data' => $cdh,       'message' => 'Diagnostics Found'],200);
            } else {
                return json_encode(['data' => $cdh,       'message' => 'No Diagnostics Found'],200);
            }
        }
    }
    public function newClaim(Request $request)
    {
        $customer_id        =   Auth::user()->customer_id;
        if ($request->cth_id) {
            $cth    =   DB::table('customer_treatment_history as cth')
                        ->join('medical_centers as mc','mc.id','cth.hospital_id')
                        ->where('cth.id',$request->cth_id)
                        ->select('mc.center_name','cth.appointment_date')
                        ->first();
            $insert_claim       =   DB::table('customer_claims')->insertGetId([
                'customer_id'           =>  $customer_id,
                'title'                 =>  $request->title,
                'appointment_for'       =>  $request->appointment_for,
                'category'              =>  $request->category,
                'total_amount'          =>  $request->appointment_fee,
                'cth_id'                =>  $request->cth_id,
                'status'                =>  0,
                'doctor_fee'            =>  $request->doctor_fee,
                'diagnostic_fee'        =>  $request->diagnostic_fee,
                'medicine_fee'          =>  $request->medicine_fee,
                'other_fee'             =>  $request->other_fee,
                'center_name'           =>  $cth->center_name,
                'appointment_date'      =>  $cth->appointment_date,
            ]);
        $claimed            =   DB::table('customer_treatment_history')->where('id',$request->cth_id)
                                ->update([
                                    'claimed'   =>  1,
                                ]);
        }
        //if Diagnostic is selected
        elseif ($request->bundle_id) {
            $cdh    =   DB::table('customer_diagnostic_history as cdh')
            ->join('labs as l','l.id','cdh.lab_id')
            ->where('cdh.bundle_id',$request->bundle_id)
            ->select('l.name as lab_name','cdh.appointment_date')
            ->first();
            $insert_claim       =   DB::table('customer_claims')->insertGetId([
                'customer_id'           =>  $customer_id,
                'title'                 =>  $request->title,
                'appointment_for'       =>  $request->appointment_for,
                'category'              =>  $request->category,
                'total_amount'          =>  $request->appointment_fee,
                'cdh_bundle_id'         =>  $request->bundle_id,
                'status'                =>  0,
                'doctor_fee'            =>  $request->doctor_fee,
                'diagnostic_fee'        =>  $request->diagnostic_fee,
                'medicine_fee'          =>  $request->medicine_fee,
                'other_fee'             =>  $request->other_fee,
                'center_name'           =>  $cdh->lab_name,
                'appointment_date'      =>  $cdh->appointment_date,
            ]);
            $claimed            =   DB::table('customer_diagnostic_history')->where('bundle_id',$request->bundle_id)
                                    ->update([
                                        'claimed'   =>  1,
                                    ]);
        }
        elseif ($request->cth_id == null && $request->bundle_id == null) {
            $insert_claim       =   DB::table('customer_claims')->insertGetId([
                'customer_id'           =>  $customer_id,
                'title'                 =>  $request->title,
                'appointment_for'       =>  $request->appointment_for,
                'category'              =>  $request->category,
                'total_amount'          =>  $request->appointment_fee,
                'status'                =>  0,
                'doctor_fee'            =>  $request->doctor_fee,
                'diagnostic_fee'        =>  $request->diagnostic_fee,
                'medicine_fee'          =>  $request->medicine_fee,
                'other_fee'             =>  $request->other_fee,
                'center_name'           =>  $request->center_name,
                'appointment_date'      =>  $request->appointment_date,
            ]);
        }
        $i  =   0;
        if ($insert_claim) {
            $customer_name          =  str_slug(customerName($request->appointment_for));
            $invoices_path          =  'backend/uploads/customer_invoices/';
            $documents_path         =  'backend/uploads/customer_claim_documents/';
            $invoices               =  $request->file('invoices');
            $others                 =  $request->file('others');
            if ($invoices) {
                foreach ($invoices as $invoice) {
                    $slug           =   $customer_name.'_'.time().$i.'.'.$invoice->getClientOriginalExtension();
                    $create         =   DB::table("customer_invoices")
                    ->insert([
                        'claim_id'          =>  $insert_claim,
                        'image'             =>  $slug,
                    ]);
                    $store          =   insert_customer_documents($slug,$invoice,$invoices_path);
                    $i++;
                }
            }
            if ($others) {
                foreach ($others as $other) {
                    $slug           =   $customer_name.'_'.time().$i.'.'.$other->getClientOriginalExtension();
                    $create         =   DB::table("customer_claim_documents")
                    ->insert([
                        'claim_id'          =>  $insert_claim,
                        'image'             =>  $slug,
                    ]);
                    $store          =   insert_customer_documents($slug,$other,$documents_path);
                    $i++;
                }
            }
            return json_encode(['message' => "Claim Uploaded Successfully!"],200);
        }

        return json_encode(['message' => "Could not Upload your Claim!"],200);
    }
}
