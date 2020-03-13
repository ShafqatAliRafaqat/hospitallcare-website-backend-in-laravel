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
class CustomerDependentController extends Controller
{
    public function unique_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
    public function all_dependents()
    {
        $customer_id = Auth::user()->customer_id;
        $dependent   = Customer::where('parent_id',$customer_id)->select('id','name','phone','dob','relation','gender','weight','height','marital_status','address')->get();
        if(count($dependent)>0){
            foreach($dependent as $d){
                $customer_image = DB::table('customer_images')->where('customer_id',$d->id)->select('picture')->first();
                $d['picture']   = isset($customer_image)? 'http://test.hospitallcare.com/backend/uploads/customers/'.$customer_image->picture:'';
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
            if ($customer->parent_id != $customer_id) {
                $picture    =   DB::table('customer_images')->where('customer_id',$customer->id)->first();
                $customer->picture      =   null;
                if ($picture) {
                    $customer->picture      =   'http://test.hospitallcare.com/backend/uploads/customers/'.$picture->picture;
                }
                return response()->json(['data'=>$customer,'message' => 'Member Found','status' => 'found'], 200);
            } else {
                $customer   =   null;
                return response()->json(['data'=>$customer,'message' => 'Member is already in your Fiends and Family List','status' => 'duplicate'], 200);
            }
        } else {
            return response()->json(['data'=>$customer,'message' => 'Member not Found','status' => 'not_found'], 200);
        }
    }
    public function set_relation(Request $request)
    {
        $customer_id    =   Auth::user()->customer_id;
        $relation       =   $request->relation;
        $dependent_id   =   $request->dependent_id;
        $customer       =   DB::table('customers')->where('id',$dependent_id)->update([
            'relation'  =>  $relation,
            'parent_id' =>  $customer_id,
        ]);
        if ($customer) {
            return response()->json(['message' => 'Relationship is updated Successfully!'], 200);
        }
        return response()->json(['message' => 'Could not Update Relation'], 404);
    }
    public function create_dependent(Request $request){
        $customer_id = Auth::user()->customer_id;
        $validate = $request->validate([
            'relation'              => 'required',
            'name'                  => 'required',
            'phone'                 => 'required',
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
        $customer = Customer::create([
            'ref'           => $this->unique_code(4),
            'name'          =>  $request->name,
            'gender'        =>  $request->gender,
            'marital_status'=>  $request->marital_status,
            'weight'        =>  $request->weight,
            'height'        =>  $request->height,
            'dob'           =>  $request->dob,
            'age'           =>  $age,
            'phone'         =>  $phone,
            'parent_id'     =>  $customer_id,
            'address'       =>  $request->address,
            'relation'      =>  $request->relation,
            'status_id'     =>  11,
            'customer_lead' =>  3,
            'created_at'    =>  Carbon::now()->toDateTimeString(),
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);
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
        $customer_id = Auth::user()->customer_id;
        $validate = $request->validate([
            'relation'              => 'required',
            'name'                  => 'required',
            'phone'                 => 'required',
        ]);
        $phone = formatPhone($request->phone);
        $check_phone = Customer::where('phone',$phone)->where('id','!=',$dependent_id)->first();
        if($check_phone){
            return response()->json(['message'=>'Phone number is already registered. Enter other number'],404);
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
            'relation'      =>  $request->relation,
            'customer_lead' =>  3,
            'created_at'    =>  Carbon::now()->toDateTimeString(),
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);
        $destinationPath = '/backend/uploads/customers/';                  // Defining th uploading path if not exist create new
        $image       = $request->file('picture');
        if ($request->file('picture') != null) {                                 //     Uploading the Image to folde
            $table='customer_images';
            $id_name='customer_id';
            $delete_images = delete_images($dependent_id,$destinationPath,$table,$id_name);
            $filename           =   str_slug($request->name).'-'.time().'.'.$image->getClientOriginalExtension();
            $location           =   public_path($destinationPath.$filename);
        if ($image != null) {
            Image::make($image)->save($location);
            $insert = DB::table('customer_images')->insert(['customer_id' => $dependent_id, 'picture' => $filename]);
            }
        }
        return response()->json(['message'=>"Family member updated successfully"],200);
    }
    public function delete_dependent($id){
        $customer = Customer::where('id',$id)->update([
            'parent_id' => null
        ]);
        return response()->json(['message'=>"Dependant Deleted Successfully"],200);

    }
}
