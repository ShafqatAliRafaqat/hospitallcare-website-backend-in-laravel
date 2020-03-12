<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Customer;
use App\Models\Admin\TempCustomer;
use App\Organization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TempCustomerController extends Controller
{
    public function unique_code($limit)
    {
      return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
    public function index()
    {
        $org_id         =  Auth::user()->organization_id;
        $temp_customers = TempCustomer::where('organization_id',$org_id)->get();

        return view('orgpanel.tempcustomers.index',compact('temp_customers'));
    }

    public function edit($id)
    {
        $employee   =   TempCustomer::where('id',$id)->first();
        $org        =   Organization::where('id',$employee->organization_id)->select('name')->first();
        $owner      =   Auth::user()->name;
        $no_contact =   DB::table('status')->where('id',5)->first();
        return view('orgpanel.tempcustomers.edit', compact('employee','org','status','owner','no_contact'));
    }

    public function update(Request $request, $id)
    {
        if( Auth::user()->can('create_employee')){
          $validate = $request->validate([
              'name'                   => 'required|min:3',
              'email'                  => 'sometimes',
              'phone'                  => 'sometimes|unique:customers,phone',
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

        $org_id = Auth::user()->organization_id;
        $insert = DB::table('customers')->insertGetId([
              'ref'                 => $this->unique_code(4),
              'name'                => $request->name,
              'email'               => $request->email,
              'phone'               => $request->phone,
              'address'             => $request->address,
              'gender'              => $request->gender,
              'organization_id'     => $org_id,
              'employee_code'       => $request->employee_code,
              'marital_status'      => $request->marital_status,
              'age'                 => $request->age,
              'weight'              => $request->weight,
              'height'              => $request->height,
              'notes'               => $request->notes,
              'status_id'           => $request->status_id,
              'phone_verified'      => 1,
              'created_at'          => Carbon::now()->toDateTimeString(),
              'updated_at'          => Carbon::now()->toDateTimeString(),
              'patient_coordinator_id'=> Auth::user()->id,
          ]);

          if ( TempCustomer::destroy($id) ) {

            session()->flash('success', 'Employee Added Successfully');

            return redirect()->route('employees.index');
        }
        } else {
            abort(403);
        }
    }

    public function destroy($id)
    {
        if ( TempCustomer::destroy($id) ) {

            session()->flash('success', 'Employee Deleted Successfully');
            return redirect()->route('notuploaded.index');
        }
    }
}
