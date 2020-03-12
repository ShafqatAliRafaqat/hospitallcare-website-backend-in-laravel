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
                        ->leftjoin('customer_attachements as a','a.customer_id','c.id')
                        ->select('c.*','s.name as status','a.attachment')
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
          $centers      = Center    :: where('is_active',1)->get();
          $status       = Status    ::where('active', 1)->get();
          $treatments   = Treatment ::where('is_active', 1)->where('parent_id',NULL)->get();
          $procedures   = Treatment ::where('is_active', 1)->get();
          $organization = Organization::all();
          $customer     = Customer:: where('id', $id)->first();
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
              'parent_id'              => 'required',
            //   'phone'                 => 'nullable|unique:customers,phone',
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
      $doctor_notes   =   CustomerDoctorNotes::where('customer_id',$id)->first();
      $risk_factor_notes= CustomerRiskFactor::where('customer_id',$id)->get();
      $treatments     =   Treatment::where('is_active', 1)->where('parent_id', null)->get();
      $procedures     =   Treatment::where('is_active', 1)->whereNotNull('parent_id')->get();
      $doctors        =   Doctor::all();
      $allergy_notes  =   CustomerAllergy::where('customer_id',$id)->get();
      $employee       =  Customer::where('parent_id',$id)->get();
      $customers      =  Customer::where('id',$id)->with(['diagnostics','labs'])->first();
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
      return view('adminpanel.customers.show', compact('customer','treatments','procedures','doctors','employee','display','lab','blood_group','risk_factor_notes','allergy_notes'));
    }

    public function edit($id)
    {
      $customer   = Customer::where('id', $id)->with(['treatments', 'center', 'doctor', 'diagnostics', 'labs'])->first();
      $allergies  = CustomerAllergy::where('customer_id',$id)->select('notes')->get();
      $riskfactor = CustomerRiskFactor::where('customer_id',$id)->select('notes')->get();
      if (count($customer->labs) > 0) {
          foreach ($customer->labs as $c) {
              $lab_id[] = $c->id;
          }

          $customer_labs = array_unique($lab_id);

          $customer_labs = array_values($customer_labs);

          $count = count($customer_labs);
          if ($count == 2) {
              $customer_lab1 = $customer->labs->where('id', $customer_labs[0])->where('is_active', 1)->first();
              $customer_lab2 = $customer->labs->where('id', $customer_labs[1])->where('is_active', 1)->first();
              $customer_cost1 = DB::table('customer_diagnostics')->where('customer_id', $id)->where('lab_id', $customer_labs[0])->get();
              $customer_cost2 = DB::table('customer_diagnostics')->where('customer_id', $id)->where('lab_id', $customer_labs[1])->get();
              $customer_diagnostics_table1 = DB::table('customer_diagnostics')->where('customer_id', $id)->where('lab_id', $customer_labs[0])->get();
              foreach ($customer_diagnostics_table1 as $d1) {
                  $d_id = $d1->diagnostic_id;
                  $customer_diagnostics1[] = Diagnostics::where('id', $d_id)->where('is_active', 1)->with('customer')->first();
              }
              $customer_diagnostics_table2 = DB::table('customer_diagnostics')->where('customer_id', $id)->where('lab_id', $customer_labs[1])->get();
              foreach ($customer_diagnostics_table2 as $d2) {
                  $d_id = $d2->diagnostic_id;
                  $customer_diagnostics2[] = Diagnostics::where('id', $d_id)->where('is_active', 1)->with('customer')->first();
              }
          }
          if ($count == 1) {
            $customer_cost1 = DB::table('customer_diagnostics')->where('customer_id', $id)->where('lab_id', $customer_labs[0])->get();
              $customer_lab1 = $customer->labs->where('id', $customer_labs[0])->where('is_active', 1)->first();
              $customer_diagnostics_table1 = DB::table('customer_diagnostics')->where('customer_id', $id)->where('lab_id', $customer_labs[0])->get();
              $customer_appointment_date1 = $customer_diagnostics_table1[0]->appointment_date;
              foreach ($customer_diagnostics_table1 as $d1) {
                  $d_id = $d1->diagnostic_id;
                  $customer_diagnostics1[] = Diagnostics::where('id', $d_id)->where('is_active', 1)->with('customer')->first();
              }
          }
      }
        $centers      = Center    ::  where('is_active', 1)->get();
        $status       = Status    ::  where('active'   , 1)->get();
        $doctor_notes = CustomerDoctorNotes::where('customer_id',$id)->get();
        $treatments   = Treatment ::  where('is_active', 1)->where('parent_id',NULL)->get();
        $procedures   = Treatment ::  where('is_active', 1)->whereNotNull('parent_id')->get();
        $organization = Organization::all();
        $doctors      = Doctor::all();
        $diagnostics  = Diagnostics::where('is_active', 1)->get();
        $labs         = Lab::where('is_active', 1)->with('diagnostic')->get();
        $blood_groups = BloodGroup::all();
        $users        = DB::table('role_user as ru')
                          ->join('users as u','ru.user_id','u.id')
                          ->where('ru.role_id',6)
                          ->OrWhere('ru.role_id',1)
                          ->select('ru.role_id','ru.user_id','u.name')
                          ->get();

        return view('adminpanel.dependents.edit', compact('labs','allergies','riskfactor','customer_appointment_date1','customer_cost1','customer_cost2','customer_labs','customer_lab1','customer_lab2','customer_diagnostics1','customer_diagnostics2', 'diagnostics', 'customer', 'customer_status', 'organization', 'status', 'centers', 'procedures', 'treatments', 'doctors', 'users','blood_groups','doctor_notes'));
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
