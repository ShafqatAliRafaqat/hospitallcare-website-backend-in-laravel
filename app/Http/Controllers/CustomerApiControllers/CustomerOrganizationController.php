<?php

namespace App\Http\Controllers\CustomerApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Customer;
use App\Organization;
use Illuminate\Support\Facades\Auth;

class CustomerOrganizationController extends Controller
{
    public function all_organizations()
    {
        $organizations = Organization::all();
        return response()->json(['data'=>$organizations],200);
    }
    public function update_customer_organization(Request $request){
        $customer_id = Auth::user()->customer_id;
        $customer_update = Customer::where('id',$customer_id)->update([
            'organization_id'   => $request->organization_id,
            'employee_code'     => $request->employee_code,
            'org_verified'      => 0,
        ]);
        return response()->json(['message'=>'Orgnaization Saved Successfully.'],200);
    }
    public function delete_organization(){
        $customer_id = Auth::user()->customer_id;
        $update_customer = Customer::where('id',$customer_id)->update([
            'organization_id'   => null,
            'employee_code'     => null,
            'org_verified'      => 0,
        ]);
        return response()->json(['message'=>'Orgnaization Deleted Successfully.'],200);
    }
}
