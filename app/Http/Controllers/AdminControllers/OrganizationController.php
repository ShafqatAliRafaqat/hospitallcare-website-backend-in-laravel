<?php

namespace App\Http\Controllers\AdminControllers;

use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Customer;
use App\Models\Admin\Treatment;
use App\Organization;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class OrganizationController extends Controller
{
    public function index()                                                 // Admin can view All the list of organizations
    {

        if ( Auth::user()->can('organizations') ) {

            $organization =Organization:: all();

            return view('adminpanel.organizations.index', compact('organization'));
      } else {
          abort(403);
      }
    }
    public function show_deleted()                                                 // Admin can view All the list of organizations
    {

        if ( Auth::user()->can('organizations') ) {
            $organization =Organization::onlyTrashed()->get();
            return view('adminpanel.organizations.soft_deleted', compact('organization'));
      } else {
          abort(403);
      }
    }

    public function create()                                               // Admin can View create new organization
    {
        if ( Auth::user()->can('organizations') ) {
            return view('adminpanel.organizations.create');
        } else {
            abort(403);
        }
    }

    public function store(Request $request)                                 // Admin can store new organization
    {
        if ( Auth::user()->can('organizations') ) {
            $validate = $request->validate([
                'name'     => 'required|min:3',
                'phone'    => 'required',
                'address'  => 'sometimes',
                ]);

            $insert = DB::table('organizations')->insertGetId([
                'name'      => $request->name,
                'phone'     => $request->phone,
                'address'   => $request->address,
            ]);

            session()->flash('success', 'Organization Added Successfully');
            return redirect()->route('organization.index');
        } else {
          abort(403);
        }
    }
    public function show($id)                                           // Admin can view All the details of organization
    {
        $organizations      = Organization::where('id',$id)->first();
        $active_employees   = Customer  ::where([['organization_id','=', $id],['employee_code','!=', null],['org_verified',1]])->get();
        $pending_employees  = Customer  ::where([['organization_id','=', $id],['employee_code','!=', null],['org_verified',0]])->get();
        $users              = User      ::where('organization_id', $id)->with('Organization','Role')->get();
      return view('adminpanel.organizations.show', compact('organizations','users','active_employees','pending_employees'));
    }

    public function edit($id)                                           // admin can view edit form for organization
    {
        $organizations = Organization::findOrFail($id);

        return view('adminpanel.organizations.edit', compact('organizations'));
    }

    public function update(Request $request, $id)                       // Admin can Update organization
    {
          if ( Auth::user()->can('organizations') ) {
          $validate = $request->validate([
              'name'      => 'required|min:3',
              'phone'     => 'required',
              'address'   => 'sometimes',
              ]);
              $Update_customer = DB::table('organizations')->where('id',$id)->update([
              'name'      => $request->name,
              'phone'     => $request->phone,
              'address'   => $request->address,
              ]);

            session()->flash('success', 'Organization Updated Successfully');
            return redirect()->route('organization.index');
        }
    }
    public function destroy($id)                                            // admin can soft delete organization
    {
        $organization = Organization::where('id',$id)->delete();
        session()->flash('success','Organization Deleted Successfully');

        return redirect()->route('organization.index');
    }
    public function per_delete($id)                                            // admin can delete permanment organization
    {
        $organization = Organization::where('id',$id)->withTrashed()->forcedelete();
        if($organization){
            $customers = Customer:: where('organization_id', $id)->withTrashed()->update([
                'organization_id' => null,
                'employee_code'=>null,
            ]);
            $users = User::where('organization_id',$id)->forcedelete();
        }
        session()->flash('success','Organization Deleted Successfully');
        return redirect()->back();
    }
    public function restore($id)                                            // admin can restore organization
    {
        $organization = Organization::where('id',$id)->withTrashed()->restore();
        session()->flash('success','Organization Restore Successfully');

        return redirect()->route('organization.index');
    }
    public function approve_pending_customer(Request $request)               //admin can Reject approve request to link with organization
    {
        $id = $request->id;
        $customer                   = Customer::where('id',$id)->update([
                'org_verified'      => 1
        ]);
        if ($customer) {
            //Notification to Customer User
            $organization_id            =   Auth::user();
            $organization_name          =   organizationName($organization_id->organization_id);
            $message                    = $organization_name." has Approved you as their Employee";
            // $organization_name      =   organizationName($customer->organization_id);
            $check_customer_in_users    = User::where('customer_id',$id)->first();
                if($check_customer_in_users){
                     $n = NotificationHelper::GENERATE([
                        'title' => 'Approved By Organization!',
                        'body' => $message,
                        'payload' => [
                            'type' => "Organization Approved"
                        ]
                    ],$check_customer_in_users->id);
                }
        }
        session()->flash('success','Customer Request has been Approvied');
        return redirect()->back();
    }
    public function reject_pending_customer(Request $request)                // admin can Reject pending request to link with organization
    {
        $id = $request->id;
        $customer = Customer::where('id',$id)->update([
            'organization_id' => null,
            'employee_code'   => null,
            'org_verified'    => 0
        ]);
        session()->flash('success','Customer Request has been Rejected');
        return redirect()->back();
    }
}
