<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Validator;
use App\Models\Admin\Customer;
use App\Models\Admin\Center;
use App\Models\Admin\Status;
use App\Models\Admin\Treatment;

class EmployeeController extends Controller
{
    public function unique_code($limit)
    {
      return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
    public function index()                                      // Usercan View the List of All employess of Organization
    {
        $org_id     =   Auth::user()->organization_id;
        if ( Auth::user()->can('view_employee') ) {
            $employees = DB::table('customers as c')
                            ->join('status as s','s.id','c.status_id')
                            ->leftjoin('customer_attachements as a','a.customer_id','c.id')
                            ->where('organization_id',$org_id)
                            ->where('c.deleted_at',null)
                            ->select('c.*','s.name as status','a.attachment')
                            ->orderBy('updated_at','DESC')
                            ->get();
          return view('orgpanel.employees.index', compact('employees'));
      } else {
          abort(403);
      }
    }
    public function activeEmployees()                      // Usercan View the List of All employess of Organization
    {
        $org_id     =   Auth::user()->organization_id;
        if ( Auth::user()->can('view_employee') ) {
            $employees = DB::table('customers as c')
                            ->join('status as s','s.id','c.status_id')
                            ->leftjoin('customer_attachements as a','a.customer_id','c.id')
                            ->where('organization_id',$org_id)
                            ->where('c.org_verified',1)
                            ->where('c.deleted_at',null)
                            ->select('c.*','s.name as status','a.attachment')
                            ->orderBy('updated_at','DESC')
                            ->get();
            $title = "Active" ;
          return view('orgpanel.employees.index', compact('employees','title'));
      } else {
          abort(403);
      }
    }
    public function pendingEmployees()                          // Usercan View the List of All employess of Organization
    {
        $org_id     =   Auth::user()->organization_id;
        if ( Auth::user()->can('view_employee') ) {
            $employees = DB::table('customers as c')
                            ->join('status as s','s.id','c.status_id')
                            ->leftjoin('customer_attachements as a','a.customer_id','c.id')
                            ->where('organization_id',$org_id)
                            ->where('c.org_verified',0)
                            ->where('c.deleted_at',null)
                            ->select('c.*','s.name as status','a.attachment')
                            ->orderBy('updated_at','DESC')
                            ->get();
          $title = "Pending" ;
          return view('orgpanel.employees.index', compact('employees','title'));
      } else {
          abort(403);
      }
    }

    public function create()                              // User can View the Create form for new Employee
    {
      if ( Auth::user()->can('create_employee') ) {
          $centers      =   Center    ::where('is_active',1)->get();
          $no_contact   =   Status    ::where('id',5)->first();
          $treatments   =   Treatment ::where('is_active', 1)->where('parent_id',NULL)->get();
          $procedures   =   Treatment ::where('is_active', 1)->get();
          $organization =   Organization::all();
          $org_id       =   Auth::user()->organization_id;
          $org          =   Organization::where('id',$org_id)->select('name')->first();

          return view('orgpanel.employees.create', compact('procedures','no_contact','status','treatments','org_id','org'));
      } else {
          abort(403);
      }
    }

    public function store(Request $request)                // User can Store data of new employee
    {
        if( Auth::user()->can('create_employee')){
          $validate = $request->validate([
              'name'                   => 'required|min:3',
              'email'                  => 'sometimes',
              'phone'                  => 'required|unique:customers,phone',
              'employee_code'          => 'required',
              'address'                => 'sometimes',
              'gender'                 => 'required',
              'marital_status'         => 'required',
              'age'                    => 'sometimes',
              'weight'                 => 'sometimes',
              'height'                 => 'sometimes',
              'notes'                  => 'required',
              'status_id'              => 'required|exists:status,id',
              'patient_coordinator_id' => 'sometimes',
          ]);

        $org_id       =   Auth::user()->organization_id;
        $insert = DB::table('customers')->insertGetId([
              'ref'                   => $this->unique_code(4),
              'name'                  => $request->name,
              'email'                 => $request->email,
              'phone'                 => $request->phone,
              'address'               => $request->address,
              'city_name'             => isset($request->city)?$request->city : null,
              'gender'                => $request->gender,
              'organization_id'       => $org_id,
              'employee_code'         => $request->employee_code,
              'marital_status'        => $request->marital_status,
              'age'                   => $request->age,
              'weight'                => $request->weight,
              'height'                => $request->height,
              'notes'                 => $request->notes,
              'status_id'             => $request->status_id,
              'org_verified'          => 1,
              'created_at'            => Carbon::now()->toDateTimeString(),
              'updated_at'            => Carbon::now()->toDateTimeString(),
              'patient_coordinator_id'=> Auth::user()->id,
          ]);
          session()->flash('success', 'Employee Added Successfully');
          return redirect()->route('employees.index');
        } else {
            abort(403);
        }
    }

    public function show($id)                              // User can view details of any employee
    {
      $customer = DB::table('customers as c')
                      ->join('status as s','s.id','c.status_id')
                      ->leftjoin('customer_attachements as a','a.customer_id','c.id')
                      ->select('c.*','s.name as status','a.attachment')
                      ->where(['c.id' => $id])
                      ->first();
      $employee           =   DB::table('customer_dependents as cd')
                          ->join('customers as c','c.id','cd.parent_customer_id')
                          ->where('cd.assc_customer_id',$id)
                          ->select('c.id','c.name','c.phone','c.dob','cd.relation','c.gender','c.weight','c.height','c.marital_status','c.address','cd.bundle_id','cd.status','c.email')
                          ->get();
       $customers = Customer::where('id',$id)->first();
       if ($customers->labs) {
          foreach ($customers->labs as $lab) {
          $array[]  =  $lab->id;
          }
          if (isset($array)) {
            $lab        = array_values(array_unique($array)); //Reorder array after making it unique
          } else {
            $lab = NULL;
          }

        }
       $display   = null;

       if($customers->organization_id && $customers->employee_code){
         $display =1;
       }
      return view('orgpanel.employees.show', compact('customer','employee','display','lab'));
    }

    public function edit($id)                                                         // User can View Edit form of Employee
    {
      if ( Auth::user()->can('edit_employee') ) {
        $employee           = Customer    ::where('id',$id)->withTrashed()->first();
        $org                = Organization::where('id',$employee->organization_id)->select('name')->first();
        $patient_coordinator= Auth::user()->find($employee->patient_coordinator_id);
        $owner = isset($patient_coordinator)? $patient_coordinator->name :null;
        return view('orgpanel.employees.edit', compact('employee','org','status','owner'));
      } else {
          abort(403);
      }
    }
    public function update(Request $request, $id)                                    // User can Store updated data in database
    {
      if ( Auth::user()->can('edit_employee') ) {

          $validate = $request->validate([
              'name'                   => 'required|min:3',
              'email'                  => 'sometimes',
              'phone'                  => 'required|unique:customers,phone,'.$id,
              'employee_code'          => 'required',
              'address'                => 'sometimes',
              'gender'                 => 'required',
              'marital_status'         => 'required',
              'age'                    => 'sometimes',
              'weight'                 => 'sometimes',
              'height'                 => 'sometimes',
          ]);
          $update_date   =   Customer::where('id',$id)->select('updated_at')->first();
            $Update_employee = DB::table('customers')
              ->where('id',$id)->update([
              'name'                  => $request->name,
              'email'                 => $request->email,
              'phone'                 => $request->phone,
              'address'               => $request->address,
              'city_name'             => isset($request->city)?$request->city : null,
              'gender'                => $request->gender,
              'marital_status'        => $request->marital_status,
              'age'                   => $request->age,
              'employee_code'         => $request->employee_code,
              'weight'                => $request->weight,
              'height'                => $request->height,
              'org_verified'          => 1,
              'updated_at'            => $update_date->updated_at,
              ]);

            session()->flash('success', 'Employee Updated Successfully');
            return redirect()->route('employees.index');
      } else {
          abort(403);
      }
    }

    public function destroy($id)                                            // User can Delete any employee from database
    {
        $employee  = Customer::where('id', $id)->update([
          'organization_id' =>  null,
          'employee_code'   =>  null,
          'org_verified'    =>  0,
        ]);
        if($employee){

          $delete_customer_attachements= DB::table('customer_attachements')->where('customer_id',$id)->delete();
        }
        session()->flash('success','Employee Deleted Successfully');
        return redirect()->back();
    }

    public function TreatmentHistory( $id)                        // User Can view all the Histroy of Customer Treatments
    {
      $customer  =  DB::table('customers as c')
                    ->join('status as s','s.id','c.status_id')
                    ->leftjoin('customer_attachements as a','a.customer_id','c.id')
                    ->select('c.*','s.name as status','a.attachment')
                    ->where(['c.id' => $id])
                    ->first();
       return view('orgpanel.employees.emp_history', compact('customer'));
    }
}
