<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\BloodGroup;
use App\Models\Admin\Center;
use App\Models\Admin\Customer;
use App\Models\Admin\CustomerAllergy;
use App\Models\Admin\CustomerDoctorNotes;
use App\Models\Admin\CustomerRiskFactor;
use App\Models\Admin\Diagnostics;
use App\Models\Admin\Doctor;
use App\Models\Admin\Lab;
use App\Models\Admin\Status;
use App\Models\Admin\Treatment;
use App\Organization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Validator;
use App\Services\CustomerServices;
class DependentsController extends Controller
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

    public function index()
    {
      if ( Auth::user()->can('view_customers') ) {                                    // User can View all te Dependents of employee
        $customers = DB::table('customers as c')
                        ->join('status as s','s.id','c.status_id')
                        ->select('c.*','s.name as status')
                        ->orderBy('updated_at','DESC')
                        ->where('c.deleted_at',null)
                        ->get();
          return view('adminpanel.dependents.index', compact('customers'));
      } else {
          abort(403);
      }
    }

    public function createdependent($id)                                            // User can View create form of Dependents
    {
      if ( Auth::user()->can('create_customer') ) {
          $centers      = Center::where('is_active',1)->get();
          $status       = Status::where('active', 1)->get();
          $treatments   = Treatment ::where('is_active', 1)->where('parent_id',NULL)->get();
          $procedures   = Treatment ::where('is_active', 1)->get();
          $organization = Organization::all();
          $customer     = Customer::where('id', $id)->first();
          $blood_groups = BloodGroup::all();
          $diagnostics  = Diagnostics::all();
          $lab          = Lab::where('is_active', 1)->get();
          return view('adminpanel.dependents.create', compact('procedures','status','lab','treatments','organization','customer','diagnostics','blood_groups'));
      } else {
          abort(403);
      }
    }
    public function store(Request $request)                                         // User can Store data of new Dependent of employee
    {
      if ( Auth::user()->can('create_customer') ) {
        $validate = $request->validate([
              'parent_customer_id'     => 'required',
              'phone'                 => 'nullable|unique:customers,phone',
          ]);
          $input = $request->all();
          $customer = $this->service->create($input);

          if (isset($customer[0]) != null) {
              session()->flash('error', $customer[1]);

              return redirect()->route('customers.create');

          } else {
            session()->flash('success', 'Customer Added Successfully');
            return redirect()->route('customers.show',[$request->parent_id]);
          }
      } else {
        abort(403);
      }
    }

    public function show($id)
    {
      $customer  =  DB::table('customers as c')
      ->join('status as s','s.id','c.status_id')
      ->leftjoin('customer_attachements as a','a.customer_id','c.id')
      ->select('c.*','s.name as status','a.attachment')
      ->where(['c.id' => $id])
      ->first();
      $doctor_notes       =   CustomerDoctorNotes::where('customer_id',$id)->first();
      $risk_factor_notes  =   CustomerRiskFactor::where('customer_id',$id)->get();
      $treatments         =   Treatment::where('is_active', 1)->where('parent_id', null)->get();
      $procedures         =   Treatment::where('is_active', 1)->whereNotNull('parent_id')->get();
      $doctors            =   Doctor::all();
      $labs               =   Lab::all();
      $centers            =   Center::where('is_active', 1)->get();
      $allergy_notes      =   CustomerAllergy::where('customer_id',$id)->get();
      $employee           =   DB::table('customer_dependents as cd')
                          ->join('customers as c','c.id','cd.parent_customer_id')
                          ->where('cd.assc_customer_id',$id)
                          ->select('c.id','c.name','c.phone','c.dob','cd.relation','c.gender','c.weight','c.height','c.marital_status','c.address','cd.bundle_id','cd.status','c.email')
                          ->get();
      $customers          =  Customer::where('id',$id)->with(['diagnostics','labs'])->first();
      $customers          =  Customer::where('id',$id)->with(['diagnostics','labs'])->first();
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
      $display   =  null;
      if($customers->organization_id && $customers->employee_code){
        $display =  1;
      }
      $blood_group   =   BloodGroup::find($customer->blood_group_id);
      return view('adminpanel.customers.show', compact('customer','treatments','procedures','doctors','employee','display','lab','blood_group','risk_factor_notes','allergy_notes','labs','centers'));
    }
    public function edit(Request $request, $id)
    {
        $parent_customer_id    = $request->customer_id;
        $customer       = Customer::where('id', $id)->with(['treatments', 'center', 'doctor', 'diagnostics', 'labs'])->withTrashed()->first();
        $doctor_notes   = CustomerDoctorNotes::where('customer_id',$id)->get();
        $allergies      = CustomerAllergy::where('customer_id',$id)->select('notes')->get();
        $riskfactor     = CustomerRiskFactor::where('customer_id',$id)->select('notes')->get();
        $customer_status= Status::where('id', $customer->status_id)->first();
        $centers        = Center::where('is_active', 1)->get();
        $status         = Status::where('active', 1)->get();
        $treatments     = Treatment::where('is_active', 1)->where('parent_id', null)->get();
        $procedures     = Treatment::where('is_active', 1)->whereNotNull('parent_id')->get();
        $doctors        = Doctor::all();
        $blood_groups   = BloodGroup::all();
        $organization   = Organization::all();
        $labs           = Lab::where('is_active', 1)->with('diagnostic')->get();
        $employee       =   DB::table('customer_dependents as cd')
                          ->join('customers as c','c.id','cd.parent_customer_id')
                          ->where('cd.parent_customer_id',$id)
                          ->where('cd.assc_customer_id',$parent_customer_id)
                          ->select('cd.relation')
                          ->first();
        if ($employee) {
          $customer->relation = $employee->relation;
        }
        $users          = DB::table('role_user as ru')
                          ->join('users as u','ru.user_id','u.id')
                          ->where('ru.role_id',6)
                          ->OrWhere('ru.role_id',1)
                          ->select('ru.role_id','ru.user_id','u.name')
                          ->get();

        return view('adminpanel.dependents.edit', compact('labs','allergies','riskfactor','customer_appointment_date1','customer_cost1','customer_cost2','customer_labs','customer_lab1','customer_lab2','customer_diagnostics1','customer_diagnostics2', 'diagnostics', 'customer', 'customer_status', 'organization', 'status', 'centers', 'procedures', 'treatments', 'doctors', 'users','blood_groups','doctor_notes','parent_customer_id'));
    }
    public function update(Request $request, $id)                     // User can update dependent data
    {
        if (Auth::user()->can('create_customer')) {
            $input = $request->all();
            // $validate = $request->validate([
            //     'phone'                 => 'nullable|unique:customers,phone,'.$id,
            // ]);

            $customer = $this->service->update($input, $id);

            if (isset($customer[0]) != null) {
                session()->flash('error', $customer[1]);
                return redirect()->route('dependents.edit', $id);
            } else {
                session()->flash('success', 'Customer Updated Successfully');
                return redirect()->route('dependents.show', [$id]);
            }
        }else{
            abort(403);
        }
    }
    public function destroy($id)                                                      //User Can delete Dependent
    {
        $employee       =   Customer::where('id',$id)->select('parent_id')->first();
        if ($employee) {
            $employee_id    =   $employee->parent_id;
            $customer       =   Customer::where('id', $id)->update([
          'parent_id' =>null,
        ]);
            session()->flash('success', 'Dependent Deleted Successfully');
            return redirect()->route('customers.show', $employee_id);
        }
        else{
          session()->flash('error', 'Enter Valid Dependent ID');
            return redirect()->back();
        }

    }
}
