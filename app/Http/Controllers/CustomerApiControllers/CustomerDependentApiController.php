<?php

namespace App\Http\Controllers\CustomerApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Customer;
use App\Models\Admin\CustomerImages;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
class CustomerDependentApiController extends Controller
{
    public function unique_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
    public function all_dependents()
    {
        $customer_id    = Auth::user()->customer_id;
        $dependent      = DB::table('customer_dependents as cd')
                        ->join('customers as c','c.id','cd.assc_customer_id')
                        ->where('cd.parent_customer_id',$customer_id)
                        ->select('c.id','c.name','c.phone','c.dob','c.gender','c.weight','c.height','c.marital_status','c.address','cd.bundle_id','cd.status','cd.claimable','cd.assc_read_medical_access as assc_read_medical','cd.assc_write_medical_access as assc_write_medical','cd.assc_manage_appointment_access as assc_manage_appointment')
                        ->get();
        if(count($dependent)>0){
            foreach($dependent as $d){
                $customer_dependents_assc      =     DB::table('customer_dependents')
                                                    ->where('parent_customer_id',$d->id)
                                                    ->where('assc_customer_id',$customer_id)->first();
                if ($customer_dependents_assc) {
                    $d->read_medical           =   $customer_dependents_assc->assc_read_medical_access;
                    $d->write_medical          =   $customer_dependents_assc->assc_write_medical_access;
                    $d->manage_appointment     =   $customer_dependents_assc->assc_manage_appointment_access;
                    $d->relation                =   $customer_dependents_assc->relation;
                } else {
                    $d->assc_read_medical           =   0;
                    $d->assc_write_medical          =   0;
                    $d->assc_manage_appointment     =   0;
                }
                $customer_image = DB::table('customer_images')->where('customer_id',$d->id)->select('picture')->first();
                $d->picture   = isset($customer_image)? 'https://support.hospitallcare.com/backend/uploads/customers/'.$customer_image->picture:'';
            }
        }
        return response()->json(['data'=>$dependent], 200);
    }
    public function search_dependent(Request $request)
    {
        $customer_id    =   Auth::user()->customer_id;
        $phone          =   formatPhone($request->phone);
        //if user inputs his/her own phone number
        $customer_check       =   DB::table('customers')->where('phone',$phone)->where('id',$customer_id)->select('id','name','gender','parent_id')->first();
        if ($customer_check) {
            return response()->json(['data'=>$customer_check,'message' => 'You can not add yourself!','status' => 'same_user'], 200);
        }
        $customer       =   DB::table('customers')->where('phone',$phone)->where('id','!=',$customer_id)->select('id','name','gender','parent_id')->first();
        if ($customer) {
            $dependent_check   = DB::table('customer_dependents')
                                ->where('parent_customer_id',$customer_id)
                                ->where('assc_customer_id', $customer->id)
                                ->get();
            if ($dependent_check->count()>0) {
                $customer   =   null;
                return response()->json(['data'=>$customer,'message' => 'Member is already in your Fiends and Family List','status' => 'duplicate'], 200);
            } else {
                $picture    =   DB::table('customer_images')->where('customer_id',$customer->id)->first();
                $customer->picture      =   null;
                if ($picture) {
                    $customer->picture      =   'https://support.hospitallcare.com/backend/uploads/customers/'.$picture->picture;
                }
                return response()->json(['data'=>$customer,'message' => 'Member Found','status' => 'found'], 200);
            }
        } else {
            return response()->json(['data'=>$customer,'message' => 'Member not Found','status' => 'not_found'], 200);
        }
    }
    public function set_relation(Request $request)
    {
        $parent_customer_id     =   Auth::user()->customer_id;
        $relation               =   $request->relation;
        $assc_customer_id       =   $request->dependent_id;
        $read_medical           =   $request->read_medical;
        $write_medical          =   $request->write_medical;
        $manage_appointment     =   $request->manage_appointment;
        $bundle_id              =   $parent_customer_id.$assc_customer_id.time().rand(10,100000);
        if ($relation == 'Friend/Other') {
            $claimable = 0;
        }else{
            $claimable = 1;
        }
        $customer_dependents      =     DB::table('customer_dependents')->insertGetId([
            'parent_customer_id'                =>  $parent_customer_id,
            'assc_customer_id'                  =>  $assc_customer_id,
            'relation'                          =>  $relation,
            'assc_read_medical_access'          =>  0,
            'assc_write_medical_access'         =>  0,
            'assc_manage_appointment_access'    =>  0,
            'bundle_id'                         =>  $bundle_id,
            'status'                            =>  2,
            'claimable'                         =>  $claimable,
        ]);
        if ($customer_dependents) {
            if ($relation == 'Parent') {
                $relation_inverse = 'Child';
            }elseif ($relation == 'Child') {
                $relation_inverse = 'Parent';
            }elseif ($relation == 'Husband') {
                $relation_inverse = 'Spouse';
            }elseif ($relation == 'Spouse') {
                $relation_inverse = 'Husband';
            } else {
                $relation_inverse = $relation;
            }
            $customer_dependents_assc      =     DB::table('customer_dependents')->insertGetId([
                'parent_customer_id'                =>  $assc_customer_id,
                'assc_customer_id'                  =>  $parent_customer_id,
                'relation'                          =>  $relation_inverse,
                'assc_read_medical_access'          =>  $read_medical,
                'assc_write_medical_access'         =>  $write_medical,
                'assc_manage_appointment_access'    =>  $manage_appointment,
                'bundle_id'                         =>  $bundle_id,
                'status'                            =>  0,
                'claimable'                         =>  $claimable,
            ]);
        }
        if ($customer_dependents_assc) {
            return response()->json(['message' => 'Relationship is updated Successfully!'], 200);
        }
        return response()->json(['message' => 'Could not Update Relation'], 404);
    }
    public function create_dependent(Request $request){
        $parent_customer_id = Auth::user()->customer_id;
        $validate           = $request->validate([
            'relation'      => 'required',
            'name'          => 'required',
            'phone'         => 'required',
        ]);
        $phone = formatPhone($request->phone);
        $check_phone = Customer::where('phone',$phone)->first();
        if($check_phone){
            return response()->json(['message'=>'Phone number is already registered. Enter other number'],404);
        }
        if(isset($request->dob)){
            $dob      =   Carbon::parse($request->dob);
            $age      =   $dob->diff(Carbon::now())->format('%y');
        }
        $customer = DB::table('customers')->insertGetId([
            'ref'           => $this->unique_code(4),
            'name'          =>  $request->name,
            'gender'        =>  $request->gender,
            'marital_status'=>  $request->marital_status,
            'weight'        =>  $request->weight,
            'height'        =>  $request->height,
            'dob'           =>  $request->dob,
            'age'           =>  $age,
            'phone'         =>  $phone,
            'address'       =>  $request->address,
            'status_id'     =>  11,
            'customer_lead' =>  3,
            'created_at'    =>  Carbon::now()->toDateTimeString(),
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);
        if ($customer) {
        $assc_customer_id       =   $customer;
        $read_medical           =   $request->read_medical;
        $write_medical           =   $request->write_medical;
        $manage_appointment          =   $request->manage_appointment;
        $relation               =   $request->relation;
        $bundle_id              =   $parent_customer_id.$assc_customer_id.time().rand(10,100000);
        if ($relation == 'Friend/Other') {
            $claimable = 0;
        }else{
            $claimable = 1;
        }
        $customer_dependents      =     DB::table('customer_dependents')->insertGetId([
            'parent_customer_id'                =>  $parent_customer_id,
            'assc_customer_id'                  =>  $assc_customer_id,
            'relation'                          =>  $relation,
            'assc_read_medical_access'          =>  0,
            'assc_write_medical_access'         =>  0,
            'assc_manage_appointment_access'    =>  0,
            'bundle_id'                         =>  $bundle_id,
            'status'                            =>  2,
            'claimable'                         =>  $claimable,

        ]);
        if ($customer_dependents) {
            if ($relation == 'Parent') {
                $relation_inverse = 'Child';
            }elseif ($relation == 'Child') {
                $relation_inverse = 'Parent';
            }elseif ($relation == 'Husband') {
                $relation_inverse = 'Spouse';
            }elseif ($relation == 'Spouse') {
                $relation_inverse = 'Husband';
            } else {
                $relation_inverse = $relation;
            }
            $customer_dependents_assc      =     DB::table('customer_dependents')->insertGetId([
                'parent_customer_id'                =>  $assc_customer_id,
                'assc_customer_id'                  =>  $parent_customer_id,
                'relation'                          =>  $relation_inverse,
                'assc_read_medical_access'          =>  $read_medical,
                'assc_write_medical_access'          =>  $write_medical,
                'assc_manage_appointment_access'    =>  $manage_appointment,
                'bundle_id'                         =>  $bundle_id,
                'status'                            =>  0,
                'claimable'                         =>  $claimable,
            ]);
        }
        }
        $destinationPath = '/backend/uploads/customers/';                  // Defining th uploading path if not exist create new
        $image       = $request->file('picture');
        if ($request->file('picture') != null) {                                 //     Uploading the Image to folde
            $table='customer_images';
            $id_name='customer_id';
            $filename           =   str_slug($request->name).'-'.time().'.'.$image->getClientOriginalExtension();
            $location           =   public_path($destinationPath.$filename);
        if ($image != null) {
            Image::make($image)->save($location);
            $insert = DB::table('customer_images')->insert(['customer_id' => $customer->id, 'picture' => $filename]);
            }
        }
        return response()->json(['message'=>"Family member added successfully"],200);
    }
    public function update_dependent(Request $request,$dependent_id){
        $parent_customer_id = Auth::user()->customer_id;
        $validate           = $request->validate([
            'relation'              => 'required',
            'name'                  => 'required',
            'phone'                 => 'required',
        ]);
        $phone = formatPhone($request->phone);
        $check_phone = Customer::where('phone',$phone)->where('id','!=',$dependent_id)->first();
        if($check_phone){
            return response()->json(['message'=>'Phone number is already registered. Enter another number'],404);
        }
        if(isset($request->dob)){
            $dob      =   Carbon::parse($request->dob);
            $age      =   $dob->diff(Carbon::now())->format('%y');
        }
        $customer = Customer::where('id',$dependent_id)->update([
            'ref'           => $this->unique_code(4),
            'name'          =>  $request->name,
            'gender'        =>  $request->gender,
            'marital_status'=>  $request->marital_status,
            'weight'        =>  $request->weight,
            'height'        =>  $request->height,
            'dob'           =>  $request->dob,
            'age'           =>  $age,
            'phone'         =>  $phone,
            'address'       =>  $request->address,
            'customer_lead' =>  3,
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);
        $assc_customer_id       =   $dependent_id;
        $read_medical           =   $request->read_medical;
        $write_medical          =   $request->write_medical;
        $manage_appointment     =   $request->manage_appointment;
        $relation               =   $request->relation;
        if ($relation == 'Friend/Other') {
            $claimable = 0;
        }else{
            $claimable = 1;
        }
        $customer_dependents      =     DB::table('customer_dependents')
                                ->where('parent_customer_id',$parent_customer_id)
                                ->where('assc_customer_id',$assc_customer_id)
                                ->update([
            'parent_customer_id'            =>  $parent_customer_id,
            'assc_customer_id'              =>  $assc_customer_id,
            'relation'                      =>  $relation,
            'claimable'                     =>  $claimable,
            'updated_at'                    =>  Carbon::now()->toDateTimeString(),
        ]);
        if ($customer_dependents) {
            if ($relation == 'Parent') {
                $relation_inverse = 'Child';
            }elseif ($relation == 'Child') {
                $relation_inverse = 'Parent';
            }elseif ($relation == 'Husband') {
                $relation_inverse = 'Spouse';
            }elseif ($relation == 'Spouse') {
                $relation_inverse = 'Husband';
            } else {
                $relation_inverse = $relation;
            }
            $customer_dependents_assc      =     DB::table('customer_dependents')
                                ->where('parent_customer_id',$assc_customer_id)
                                ->where('assc_customer_id',$parent_customer_id)
                                ->update([
                'parent_customer_id'                =>  $assc_customer_id,
                'assc_customer_id'                  =>  $parent_customer_id,
                'relation'                          =>  $relation_inverse,
                'assc_read_medical_access'          =>  $read_medical,
                'assc_write_medical_access'         =>  $write_medical,
                'assc_manage_appointment_access'    =>  $manage_appointment,
                'claimable'                         =>  $claimable,
                'updated_at'                        =>  Carbon::now()->toDateTimeString(),
            ]);
        }
        $destinationPath    = '/backend/uploads/customers/';        //  Defining th uploading path if not exist create new
        $image              = $request->file('picture');
        if ($request->file('picture') != null) {                    //  Uploading the Image to folder
            $table              =   'customer_images';
            $id_name            =   'customer_id';
            $delete_images      =   delete_images($dependent_id,$destinationPath,$table,$id_name);
            $filename           =   str_slug($request->name).'-'.time().'.'.$image->getClientOriginalExtension();
            $location           =   public_path($destinationPath.$filename);
        if ($image != null) {
            Image::make($image)->save($location);
            $insert = DB::table('customer_images')->insert(['customer_id' => $dependent_id, 'picture' => $filename]);
            }
        }
        return response()->json(['message'=>"Family member updated successfully"],200);
    }
    public function approve_dependent(Request $request ,$id)
    {
        $parent_customer_id     =   Auth::user()->customer_id;
        $assc_customer_id       =   $id;
        $read_medical           =   $request->read_medical;
        $write_medical          =   $request->write_medical;
        $manage_appointment     =   $request->manage_appointment;
        $status                 =   $request->status;
        $approve_dependent      =   DB::table('customer_dependents')
                                    ->where('parent_customer_id',$parent_customer_id)
                                    ->where('assc_customer_id',$assc_customer_id)
                                    ->update([
                                        'assc_read_medical_access'          => $read_medical,
                                        'assc_write_medical_access'         => $write_medical,
                                        'assc_manage_appointment_access'    => $manage_appointment,
                                        'status'                    => 1,
                                    ]);
        if ($approve_dependent) {
            //change status from Sent to Approved
            $approve_dependent_inverse      =   DB::table('customer_dependents')
                        ->where('parent_customer_id',$assc_customer_id)
                        ->where('assc_customer_id',$parent_customer_id)
                        ->update([
                            'status'                    => 1,
                        ]);
            return response()->json(['message'=>"Dependant Approved Successfully!"],200);
        }
        return response()->json(['data'=> $request->input() ], 200 );
    }
    public function delete_dependent($bundle_id){
        $relation_delete = DB::table('customer_dependents')->where('bundle_id',$bundle_id)->delete();
        if ($relation_delete) {
            return response()->json(['message'=>"Dependant Deleted Successfully"],200);
        } else {
            return response()->json(['message'=>"Could not Delete Dependant"],200);
        }
    }
}
