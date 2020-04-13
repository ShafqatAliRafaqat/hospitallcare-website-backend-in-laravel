<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\BloodGroup;
use App\Models\Admin\Center;
use App\Models\Admin\Customer;
use App\Models\Admin\Doctor;
use App\Models\Admin\CustomerAllergy;
use App\Models\Admin\CustomerDoctorNotes;
use App\Models\Admin\CustomerRiskFactor;
use App\Models\Admin\Lab;
use App\Models\Admin\Status;
use App\Models\Admin\Treatment;
use App\Organization;
use App\Services\CustomerServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
//Organizational Admin Side
class DependentController extends Controller
{
        /** @var CustomerServices */
        private $service;

        public function __construct()
        {
            $this->service = new CustomerServices();
        }
        public function unique_code($limit)
        {
            return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
        }
    public function create(Request $request)                                        // User Can View Create Dependent form
    {
        $employee_id    =   $request->employee_id;
        $centers        =   Center    ::where('is_active',1)->get();
        $status         =   Status    ::where('active', 1)->get();
        $treatments     =   Treatment ::where('is_active', 1)->where('parent_id',NULL)->get();
        $procedures     =   Treatment ::where('is_active', 1)->get();
        $organization   =   Organization::all();
        $customer       =   Customer  :: where('id', $employee_id)->first();
        $no_contact     =   Status    ::where('id',5)->first();
        return view('orgpanel.dependents.create', compact('procedures','status','treatments','organization','customer','no_contact'));
    }
    public function store(Request $request)                                         //User Can Create Dependent
    {
          $validate = $request->validate([
              'relation'              => 'required',
              'phone'                 => 'nullable|unique:customers',
          ]);
          // dd($request->all());
          $customer = Customer::create([
            'ref'                   => $this->unique_code(4),
            'name'                  =>$request->name,
            'email'                 =>$request->email,
            'phone'                 =>$request->phone,
            'address'               =>$request->address,
            'city_name'             => isset($request->city)?$request->city : null,
            'gender'                =>$request->gender,
            'marital_status'        =>$request->marital_status,
            'age'                   =>$request->age,
            'weight'                =>$request->weight,
            'height'                =>$request->height,
            'notes'                 =>$request->notes,
            'status_id'             =>$request->status_id,
            'patient_coordinator_id'=>$request->patient_coordinator_id,
          ]);
          $save_relation  =   $this->service->create_relation($request->relation, $request->parent_id,$customer->id);
          // dd($customer->id);
          session()->flash('success', 'Dependent Added Successfully');
          return redirect()->route('employees.show',$request->parent_id);

    }
    public function show($id)                                                           //User Can View All Details of Dependent
    {
        $customers      =   Customer::where('id',$id)->with(['diagnostics','labs'])->withTrashed()->first();
        // $customer  =  Customer::where('id',$id)->first();
        // $customer           =  DB::table('customers as c')
        //                     ->join('status as s','s.id','c.status_id')
        //                     ->leftjoin('customer_attachements as a','a.customer_id','c.id')
        //                     ->select('c.*','s.name as status','a.attachment')
        //                     ->where('c.id' , $id)
        //                     ->first();
        $centers            =   Center::where('is_active', 1)->get();
        $treatments         =   Treatment::where('is_active', 1)->where('parent_id', null)->get();
        $procedures         =   Treatment::where('is_active', 1)->whereNotNull('parent_id')->get();
        $doctors            =   Doctor::all();
        $blood_group        =   BloodGroup::find($customers->blood_group_id);
        $doctor_notes       =   CustomerDoctorNotes::where('customer_id',$id)->first();
        $risk_factor_notes  =   CustomerRiskFactor::where('customer_id',$id)->get();
        $allergy_notes      =   CustomerAllergy::where('customer_id',$id)->get();
        // $employee       =   Customer::where('parent_id',$id)->withTrashed()->get();
        $employee           =   DB::table('customer_dependents as cd')
                            ->join('customers as c','c.id','cd.parent_customer_id')
                            ->where('cd.assc_customer_id',$id)
                            ->select('c.id','c.name','c.phone','c.dob','cd.relation','c.gender','c.weight','c.height','c.marital_status','c.address','cd.bundle_id','cd.status','c.email')
                            ->get();
       // $employee = Customer::where('id',$customer->parent_id)->first();

        $labs            =   Lab::all();
        if (isset($customers->labs)) {
            foreach ($customers->labs as $lab) {
                $array[]  =  $lab->id;
            }
            if (isset($array)) {
                $lab      = array_values(array_unique($array)); //Reorder array after making it unique
            } else {
                $lab = NULL;
            }
        }
        $display   =  null;
        if($customers->organization_id && $customers->employee_code){
          $display =  1;
        }
        // dd($customers);
        // return view('adminpanel.customers.show', compact('customer','centers','treatments','procedures','doctors','employee','display','lab','blood_group','doctor_notes','risk_factor_notes','allergy_notes','labs'));
      return view('orgpanel.dependents.show',  compact('customers','centers','treatments','procedures','doctors','employee','display','lab','blood_group','doctor_notes','risk_factor_notes','allergy_notes','labs'));
    }
    public function edit(Request $request ,$id)
    {
        $org_id               =   Auth::user()->organization_id;
        $dependent            =   Customer::where('id',$id)->first();
        $doctor_notes         =   CustomerDoctorNotes::where('customer_id',$id)->get();
        $org_employees        =   Customer::where('organization_id',$org_id)->get();
        $patient_coordinator  =   Auth::user()->find($dependent->patient_coordinator_id);
        $owner                =   isset($patient_coordinator)? $patient_coordinator->name :'';
        $organization         =   Organization::all();
        $parent_customer_id   = $request->customer_id;
        $dependent->parent_id = $parent_customer_id;
        $employee             =   DB::table('customer_dependents as cd')
                              ->join('customers as c','c.id','cd.parent_customer_id')
                              ->where('cd.parent_customer_id',$id)
                              ->where('cd.assc_customer_id',$parent_customer_id)
                              ->select('cd.relation','cd.bundle_id as relation_bundle_id')
                              ->first();
        // dd($employee,$parent_customer_id,$id,$request->dependent_id);
        if ($employee) {
          $dependent->relation            = $employee->relation;
          $dependent->relation_bundle_id  = $employee->relation_bundle_id;
        }
        // dd($employee);
        return view('orgpanel.dependents.edit', compact('customer','owner','dependent','org_employees','doctor_notes'));
    }

    public function update(Request $request, $id)                                             //User Can edit Dependent Data
    {
        $input = $request->all();
        $validate = $request->validate([
          'phone'                  => 'nullable|unique:customers,phone,'.$id,
        ]);
        $customer = Customer::where('id',$id)->update([
          'ref'                     =>  $this->unique_code(4),
          'name'                    =>  $request->name,
          'email'                   =>  $request->email,
          'phone'                   =>  $request->phone,
          'address'                 =>  $request->address,
          'city_name'               =>  isset($request->city)?$request->city : null,
          'gender'                  =>  $request->gender,
          'marital_status'          =>  $request->marital_status,
          'age'                     =>  $request->age,
          'weight'                  =>  $request->weight,
          'height'                  =>  $request->height,
          'notes'                   =>  $request->notes,
          'status_id'               =>  $request->status_id,
          'patient_coordinator_id'  =>  $request->patient_coordinator_id,
        ]);
        $relation       = $request->relation;
        $bundle_id      = $request->bundle_id;
        $parent_id      = $request->parent_id;
        if ($relation == 'Friend/Other') {
            $claimable = 0;
        }else{
            $claimable = 1;
        }
        $customer_dependents      =     DB::table('customer_dependents')
                                        ->where('parent_customer_id',$id)
                                        ->where('bundle_id',$bundle_id)
                                        ->update([
            'parent_customer_id'            =>  $id,
            'assc_customer_id'              =>  $parent_id,
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
                                                ->where('assc_customer_id',$id)
                                                ->where('bundle_id',$bundle_id)
                                                ->update([
                'parent_customer_id'                =>  $parent_id,
                'assc_customer_id'                  =>  $id,
                'relation'                          =>  $relation_inverse,
                'claimable'                         =>  $claimable,
                'updated_at'                        =>  Carbon::now()->toDateTimeString(),
            ]);
        }
        session()->flash('success', 'Dependent Updated Successfully');
        return redirect()->route('employees.show',$parent_id);
    }

    public function destroy($id)                                                      //User Can delete Dependent
    {
        $employee       =   Customer::where('id',$id)->select('parent_id')->first();
        $employee_id    =   $employee->parent_id;
        $customer       =   Customer::where('id', $id)->update([
          'parent_id' =>null,
        ]);
       session()->flash('success', 'Dependent Deleted Successfully');
        return redirect()->route('employees.show',$employee_id);
    }
}
