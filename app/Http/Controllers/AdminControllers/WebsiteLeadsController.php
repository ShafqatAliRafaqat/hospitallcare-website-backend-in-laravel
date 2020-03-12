<?php
namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\BloodGroup;
use App\Models\Admin\Center;
use App\Models\Admin\Customer;
use App\Models\Admin\Lab;
use App\Models\Admin\Status;
use App\Models\Admin\TempCustomer;
use App\Models\Admin\TempNotes;
use App\Models\Admin\Treatment;
use App\Organization;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WebsiteLeadsController extends Controller
{
    public function index(){                                                                        // Get all website lead
        if (Auth::user()->can('view_customers')) {
            $customers = DB::table('customers as c')
                ->join('status as s', 's.id', 'c.status_id')
                ->join('customer_procedures as cp', 'cp.customer_id', 'c.id')
                ->leftjoin('customer_attachements as a', 'a.customer_id', 'c.id')
                ->select('c.*', 's.name as status', 'a.attachment')
                ->where('c.deleted_at', null)
                ->where('cp.appointment_from', 1)
                ->where('s.deleted_at', null)
                ->orderBy('c.updated_at', 'DESC') 
                ->groupBy("cp.customer_id")
                ->paginate(15);
            $count = COUNT($customers);
            // dd($customers);
            return view('adminpanel.customers.index', compact('customers','count'));

        } else {
            abort(403);
        }
    }
    public function WebUsers(){                                                                        // Get all website lead
        if ( Auth::user()->can('view_user_management') ) {
            $users = User::where('customer_id','!=',null)->orderBy('created_at', 'DESC')->with('Customer','Role')->get();
            return view('adminpanel.websiteleads.webusers', compact('users'));
        } else {
            abort(403);
        }
    }
    public function edit($id)                                                                       // Edit Website lead
    {
            $customer       =   Customer::where('id',$id)->first();
            $customer_notes =   TempNotes::where('customer_id',$id)->get();
            $centers        =   Center::where('is_active' ,1)->get();
            $status         =   Status::where('active',    1)->get();
            $treatments     =   Treatment::where('is_active', 1)->where('parent_id',NULL)->get();
            $procedures     =   Treatment::where('is_active', 1)->whereNotNull('parent_id')->get();
            $blood_groups   =   BloodGroup::all();
            $users          =   DB::table('role_user as ru')
                                ->join('users as u','ru.user_id','u.id')
                                ->where('ru.role_id',6)
                                ->OrWhere('ru.role_id',1)
                                ->OrWhere('ru.role_id',9)
                                ->select('ru.role_id','ru.user_id','u.name')
                                ->get();
            $time           =   (isset($customer->appointment_date) ? Carbon::parse($customer->appointment_date)->format('Y-m-d\TH:i') : NULL);
            $organization   =   Organization::all();
            $lab            =   Lab::all();
            
            $customer_lead = 2; //2 shows that the lead came from Website.
            return view('adminpanel.websiteleads.edit', compact('lab','customer','organization','status','centers','procedures','treatments','users','time','customer_lead','blood_groups','customer_notes'));
    }
    public function destroy($id){                                                                 // Delete website lead
        if ( TempCustomer::destroy($id)){
            session()->flash('success', 'Lead is been Deleted Successfully');
            return redirect()->route('webleads.index');
      }
    }
}
