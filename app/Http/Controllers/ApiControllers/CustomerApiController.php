<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\CustomerLeadResource;
use App\Models\Admin\TempCustomer;
use Carbon\Carbon;
use App\Models\Admin\TempNotes;
use App\Models\Admin\Customer;
use Illuminate\Support\Facades\DB;

class CustomerApiController extends Controller
{

    public function customer_lead()                         //Get data of customer lead from facebook
    {
        $customer = TempCustomer::where('organization_id','=',null)->where('lead_from','=',0)->get();
        return CustomerResource::collection($customer);
    }
    public function index()                                 // Get those leads which  are customer now in CRM
    {
        $customer = Customer::where('customer_lead','=',1)->get();
        return CustomerLeadResource::collection($customer);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name'                  => 'required|min:3',
            'email'                 => 'sometimes',
            'phone'                 => 'required|min:9',
            'address'               => 'sometimes',
            'gender'                => 'sometimes',
            'marital_status'        => 'sometimes',
            'age'                   => 'sometimes',
            'weight'                => 'sometimes',
            'height'                => 'sometimes',
            'treatment'             => 'sometimes',
        ]);
        $c = DB::table('temp_customers')->where('phone',$request->phone)->first();
        if(isset($c)){
            return CustomerResource::make($c);
        }
        $customer = TempCustomer::create([
            'name'                  => $request->name,
            'email'                 => $request->email,
            'phone'                 => $request->phone,
            'address'               => $request->address,
            'gender'                => $request->gender,
            'marital_status'        => $request->marital_status,
            'age'                   => $request->age,
            'weight'                => $request->weight,
            'height'                => $request->height,
            'treatment'             => $request->treatment,
            'lead_from'             => 0,
            'created_at'            => Carbon::now()->toDateTimeString(),
            'updated_at'            => Carbon::now()->toDateTimeString(),
            ]);
            if($request->notes){
                $notes = TempNotes::create([
                    'customer_id'       =>  $customer->id,
                    'notes'             =>  $request->notes,
                ]);
            }

            return CustomerResource::make($customer);
    }

    public function show($id)
    {
        $customer = TempCustomer::where('id',$id)->where('organization_id','=',null)->where('lead_from','=',0)->first();
        if($customer){
            return CustomerResource::make($customer);
        } else {
            return response()->json(['error'=>'Please Enter an ID that resides in Database'],200);
        }

    }

    public function updates(Request $request, $id)
    {
        $validate = $request->validate([
            'name'                   => 'required|min:3',
            'email'                  => 'sometimes',
            'phone'                  => 'required|min:9',
            'address'                => 'sometimes',
            'gender'                 => 'sometimes',
            'marital_status'         => 'sometimes',
            'age'                    => 'sometimes',
            'weight'                 => 'sometimes',
            'height'                 => 'sometimes',
            'treatment'              => 'sometimes',
            'notes'                  => 'sometimes'

        ]);
        $customer= TempCustomer::where('id',$id)
                                ->where('organization_id','=',null)
                                ->where('lead_from','=',0)
                                ->with('customer_notes')
                                ->first();

        if($customer){

        $updated =$customer->update([
            'name'                  => $request->name,
            'email'                 => $request->email,
            'phone'                 => $request->phone,
            'address'               => $request->address,
            'gender'                => $request->gender,
            'marital_status'        => $request->marital_status,
            'age'                   => $request->age,
            'weight'                => $request->weight,
            'height'                => $request->height,
            'treatment'             => $request->treatment,
            'lead_from'             => 0,
            'created_at'            => Carbon::now()->toDateTimeString(),
            'updated_at'            => Carbon::now()->toDateTimeString(),
            ]);
            if($request->notes){
            $notes = TempNotes::where('customer_id',$id)->get();
            $count = $notes->count();
            if ($count>1) {
                $i = 0;
                foreach ($notes as $note) {
                    $n = $note->notes;

                    if(strcasecmp($n, $request->notes) != 0)
                    {
                        $i++;
                    }
                }
                if($i == $count){
                    $newnotes = TempNotes::create([
                        'customer_id'       =>  $customer->id,
                        'notes'             =>  $request->notes,
                    ]);
                }
            }else{
                $newnotes = TempNotes::create([
                    'customer_id'       =>  $customer->id,
                    'notes'             =>  $request->notes,
                ]);
            }
            }

            return CustomerResource::make($customer);
        }else{
            return response()->json(['error'=>"Please enter valid customer id"], 200);
    }
        }

    public function destroy($id)
    {
        $customer =TempCustomer::where('id', $id)->where('organization_id','=',null)->where('lead_from','=',0)->first();
        if($customer){
            $customer->delete();
            $massage = "Customer Deleted Successfully";
            return response()->json([$massage], 200);
        }else{
            return response()->json(['error'=>"Please enter valid customer id"], 200);
        }

    }
}
