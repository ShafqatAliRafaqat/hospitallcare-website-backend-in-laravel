<?php

namespace App\Http\Controllers\AdminControllers;

use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Customer;
use App\Models\Admin\MedicalClaims;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
class EmployeeMedicalClaimController extends Controller
{
    public function pendingClaims()
    {
        $organization_id        =   Auth::user()->organization_id;
        $pending                =   true;
        $claims                 =   DB::table('customers as c')
                                    ->join('customer_claims as cc','c.id','cc.customer_id')
                                    ->join('customers as cn','cn.id','cc.appointment_for')
                                    ->where('c.organization_id',$organization_id)
                                    ->whereIn('cc.status',[0,3])
                                    ->whereNull('cc.deleted_at')
                                    ->select('cc.id as claim_id','c.id as employee_id','c.name as employee_name','c.phone as employee_phone','cn.id as d_id','cn.name as d_name','cn.phone as d_phone','cn.relation','cc.title','cc.status','cc.created_at as claim_date')
                                    ->orderBy('cc.created_at','DESC')
                                    ->get();
        return view('orgpanel.claims.pendingclaims', compact('claims','pending'));
    }
    public function allClaims()
    {
        $organization_id        =   Auth::user()->organization_id;
        $pending                =   false;
        $claims                 =   DB::table('customers as c')
                                    ->join('customer_claims as cc','c.id','cc.customer_id')
                                    ->join('customers as cn','cn.id','cc.appointment_for')
                                    ->where('c.organization_id',$organization_id)
                                    ->whereIn('cc.status',[1,2])
                                    ->whereNull('cc.deleted_at')
                                    ->select('cc.id as claim_id','c.id as employee_id','c.name as employee_name','c.phone as employee_phone','cn.id as d_id','cn.name as d_name','cn.phone as d_phone','cn.relation','cc.title','cc.status','cc.created_at as claim_date')
                                    ->orderBy('cc.created_at','DESC')
                                    ->get();
        return view('orgpanel.claims.pendingclaims', compact('claims','pending'));
    }
    public function editClaim($id)
    {
        $patient                    =   DB::table('customer_claims')->where('id',$id)->first();
        if ($patient->cth_id != null) {
            $type                   =   'treatment';
        } else if ($patient->cdh_bundle_id != null) {
            $type                   =   'diagnostic';
        } else {
            $type                   =   'custom';
        }
        $patient                    =   editOrShowClaim($patient,$id);
        $invoices                   =   DB::table('customer_invoices')->where('claim_id',$id)->get();
        $customer_claim_documents   =   DB::table('customer_claim_documents')->where('claim_id',$id)->get();
        if ($patient->parent_id != null) {
            $employee_details               =   Customer::where('id',$patient->parent_id)->first();
        } else {
            $employee_details['name']           =   $patient->name;
            $employee_details['employee_code']  =   $patient->employee_code;
            $employee_details['email']          =   $patient->email;
            $employee_details['phone']          =   $patient->phone;
        }
        return view('orgpanel.claims.edit',compact('employee_details','patient','type','invoices','customer_claim_documents'));
    }
    public function show($id)
    {
        $patient                    =   DB::table('customer_claims')->where('id',$id)->first();
        if ($patient->cth_id != null) {
            $type                   =   'treatment';
        } else if ($patient->cdh_bundle_id != null) {
            $type                   =   'diagnostic';
        } else {
            $type                   =   'custom';
        }
        $patient                    =   editOrShowClaim($patient,$id);
        $invoices                   =   DB::table('customer_invoices')->where('claim_id',$id)->get();
        $customer_claim_documents   =   DB::table('customer_claim_documents')->where('claim_id',$id)->get();
        if ($patient->parent_id != null) {
            $employee_details               =   Customer::where('id',$patient->parent_id)->first();
        } else {
            $employee_details['name']           =   $patient->name;
            $employee_details['employee_code']  =   $patient->employee_code;
            $employee_details['email']          =   $patient->email;
            $employee_details['phone']          =   $patient->phone;
        }
        return view('orgpanel.claims.show',compact('employee_details','patient','type','invoices','customer_claim_documents'));
    }
    public function updateClaim(Request $request, $id)
    {
        $claim_details      =   DB::table('customer_claims')->where('id',$id)->first();
        $customer_id        =   $claim_details->customer_id;
        $validate       =   $request->validate([
            'action'                =>  'required',
            'internal_comment'      =>  'sometimes',
            'comment'               =>  'sometimes'
        ]);
        if ($request->action != NULL) {
            $update         =   DB::table('customer_claims')->where('id',$id)->update([
                'status'            =>  $request->action,
                'internal_comment'  =>  $request->internal_comment,
                'comment'           =>  $request->comment,
            ]);
            if ($request->action == 1 && $claim_details->status != 1) {
            //Notification to Customer User
            $message                    = "Your Claim for ".$claim_details->title." has been Approved";
            $check_customer_in_users    = User::where('customer_id',$customer_id)->first();
                if($check_customer_in_users){
                    NotificationHelper::GENERATE([
                        'title'     => 'Claim Approved!',
                        'body'      => $message,
                        'payload'   => [
                            'type'  => "Claim Approved"
                        ]
                    ],$check_customer_in_users->id);
                }
            }
            if ($request->action == 2 && $claim_details->status != 2) {
            //Notification to Customer User
            $message                    = "Your Claim for ".$claim_details->title." has been Declined";
            $check_customer_in_users    = User::where('customer_id',$customer_id)->first();
                if($check_customer_in_users){
                    NotificationHelper::GENERATE([
                        'title'     => 'Claim Declined!',
                        'body'      => $message,
                        'payload'   => [
                            'type'  => "Claim Declined"
                        ]
                    ],[$check_customer_in_users->id]);
                }
            }
        }
        session()->flash('success', 'Claim has been Updated Successfully');
        return redirect()->route('pending_claims');
    }
    public function deleteClaim(Request $request, $id)
    {
        $deleteClaim    =   MedicalClaims::where('id',$id)->delete();
        session()->flash('error', 'Claim has been Moved to Trash Successfully');
        return redirect()->back();
    }
    public function showDeletedClaim()
    {
        $organization_id        =   Auth::user()->organization_id;
        $claims                 =   DB::table('customers as c')
                                    ->join('customer_claims as cc','c.id','cc.customer_id')
                                    ->join('customers as cn','cn.id','cc.appointment_for')
                                    ->where('c.organization_id',$organization_id)
                                    ->whereNotNull('cc.deleted_at')
                                    ->select('cc.id as claim_id','c.id as employee_id','c.name as employee_name','c.phone as employee_phone','cn.id as d_id','cn.name as d_name','cn.phone as d_phone','cn.relation','cc.title','cc.status')
                                    ->orderBy('cc.created_at','DESC')
                                    ->get();
        return view('orgpanel.claims.deleted', compact('claims','pending'));
    }
    public function restoreDeletedClaim($id)
    {
        $restore        =   DB::table('customer_claims')->where('id',$id)->update([
            'deleted_at'    => NULL,
        ]);
        session()->flash('success','The Claim has been Restored Successfully!');
        return redirect()->back();
    }
    public function forceDeleteClaim($id)
    {
        $find                           =   MedicalClaims::where('id',$id)->withTrashed()->first();
        if ($find) {
            $customer_invoices          =   DB::table('customer_invoices')->where('claim_id',$find->id)->get();
            if ($customer_invoices) {
                foreach ($customer_invoices as $invoice) {
                    $image_path         =  public_path()."/backend/uploads/customer_invoices/".$invoice->image;
                    File::delete($image_path);
                }
            $delete_invoices            =   DB::table('customer_invoices')->where('claim_id',$find->id)->delete();
            }
            $customer_claim_documents   =   DB::table('customer_claim_documents')->where('claim_id',$find->id)->get();
            if ($customer_claim_documents) {
                foreach ($customer_claim_documents as $document) {
                    $image_path         =  public_path()."/backend/uploads/customer_claim_documents/".$document->image;
                    File::delete($image_path);
                }
            $delete_documents           =   DB::table('customer_claim_documents')->where('claim_id',$find->id)->delete();
            }
            $customer_claims            =   MedicalClaims::where('id',$id)->forceDelete();
            if ($customer_claims) {
                session()->flash('success','Deleted Successfully!');
                return redirect()->back();
            }
        } else {
            session()->flash('error','Sorry! Could not Delete.');
            return redirect()->back();
        }
    }
}
