<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Customer;
use App\Organization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Center;
use App\Models\Admin\CustomerDoctorNotes;
use App\Models\Admin\Status;
use App\Models\Admin\Treatment;
use App\Services\CustomerServices;
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
            'relation'              => (isset($request->relation)) ?$request->relation : null,
            'parent_id'             => (isset($request->parent_id)) ?$request->parent_id : null,
            'patient_coordinator_id'=>$request->patient_coordinator_id,
          ]);
          session()->flash('success', 'Dependent Added Successfully');
          return redirect()->route('employees.show',$request->parent_id);

    }
    public function show($id)                                                           //User Can View All Details of Dependent
    {
        $customer  =  Customer::where('id',$id)->with(['diagnostics','labs'])->first();
        if ($customer->labs) {
          foreach ($customer->labs as $lab) {
          $array[]  =  $lab->id;
          }
          if (isset($array)) {
            $lab        = array_values(array_unique($array)); //Reorder array after making it unique
          } else {
            $lab = NULL;
          }

        }
       $employee = Customer::where('id',$customer->parent_id)->first();
      return view('orgpanel.dependents.show', compact('customer','employee','lab'));
    }
    public function edit(Request $request ,$id)
    {
        $org_id             =   Auth::user()->organization_id;
        $dependent          =   Customer::where('id',$id)->first();
        $doctor_notes       =   CustomerDoctorNotes::where('customer_id',$id)->get();
        $org_employees      =   Customer::where('organization_id',$org_id)->get();
        $patient_coordinator=   Auth::user()->find($dependent->patient_coordinator_id);
        $owner              =   isset($patient_coordinator)? $patient_coordinator->name :'';
        $organization       =   Organization::all();
        return view('orgpanel.dependents.edit', compact('customer','owner','dependent','org_employees','doctor_notes'));
    }

    public function update(Request $request, $id)                                             //User Can edit Dependent Data
    {
        $input = $request->all();
        $validate = $request->validate([
          'phone'                  => 'nullable|unique:customers,phone,'.$id,
        ]);
        $customer = Customer::where('id',$id)->update([
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
          'relation'              => (isset($request->relation)) ?$request->relation : null,
          'parent_id'             => (isset($request->parent_id)) ?$request->parent_id : null,
          'patient_coordinator_id'=>$request->patient_coordinator_id,
        ]);
        session()->flash('success', 'Dependent Updated Successfully');
        return redirect()->route('employees.show' , $request->parent_id);
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
