<?php

namespace App\Http\Controllers\AdminControllers;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\BloodGroup;
use App\Models\Admin\CareallPassCategory;
use App\Models\Admin\Center;
use App\Models\Admin\CoodinatorPerformance;
use App\Models\Admin\Customer;
use App\Models\Admin\CustomerAllergy;
use App\Models\Admin\CustomerDoctorNotes;
use App\Models\Admin\CustomerImages;
use App\Models\Admin\CustomerRiskFactor;
use App\Models\Admin\Diagnostics;
use App\Models\Admin\Doctor;
use App\Models\Admin\Lab;
use App\Models\Admin\Status;
use App\Models\Admin\TempCustomer;
use App\Models\Admin\TempNotes;
use App\Models\Admin\Treatment;
use App\Organization;
use App\Services\CustomerServices;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CustomerController extends Controller
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

    public function index(Request $request) // User can View the list of Customer
    {
        // dd(time().rand(10,10000));
        if (Auth::user()->can('view_customers')) {
            $customers = DB::table('customers as c')
                ->join('status as s', 's.id', 'c.status_id')
                ->leftjoin('customer_attachements as a', 'a.customer_id', 'c.id')
                ->select('c.*', 's.name as status', 'a.attachment')
                ->where('c.deleted_at', null)
                ->orderBy('updated_at', 'DESC')
                ->paginate(15);
            $count = DB::table('customers as c')
                ->where('c.deleted_at', null)
                ->count();
            return view('adminpanel.customers.index', compact('customers','count'));

        } else {
            abort(403);
        }
    }
    public function customerSearch(Request $request)
    {
        if (Auth::user()->can('view_customers')) {
          if (isset($request->name_or_phone)) {
          $name_or_phone    = $request->name_or_phone;
          $customers        = DB::table('customers as c')
                            ->join('status as s', 's.id', 'c.status_id')
                            ->leftjoin('customer_attachements as a', 'a.customer_id', 'c.id')
                            ->select('c.*', 's.name as status', 'a.attachment')
                            ->where('c.deleted_at','=',NULL)
                            ->where(function($query) use ($name_or_phone) {
                            $query->where('c.name','LIKE','%'.$name_or_phone.'%')
                            ->OrWhere('c.phone','LIKE','%'.$name_or_phone.'%');
                            })->orderBy('updated_at', 'DESC')
                            ->get();
          $count            = DB::table('customers as c')
                            ->where('c.deleted_at','=',NULL)
                            ->where(function($query) use ($name_or_phone) {
                            $query->where('c.name','LIKE','%'.$name_or_phone.'%')
                            ->OrWhere('c.phone','LIKE','%'.$name_or_phone.'%');
                            })->count();
          } else if (isset($request->date)) {
          $date             = date('Y-m-d', strtotime($request->date));
          $customers        = DB::table('customers as c')
                            ->join('status as s', 's.id', 'c.status_id')
                            ->leftjoin('customer_attachements as a', 'a.customer_id', 'c.id')
                            ->select('c.*', 's.name as status', 'a.attachment')
                            ->where('c.deleted_at', null)
                            ->where(function($query) use ($date) {
                            $query->where('c.next_contact_date','LIKE','%'.$date.'%')
                            ->OrWhere('c.updated_at','LIKE','%'.$date.'%');
                            })->orderBy('updated_at', 'DESC')
                            ->get();
          $count            = DB::table('customers as c')
                            ->where('c.deleted_at', null)
                            ->where(function($query) use ($date) {
                            $query->where('c.next_contact_date','LIKE','%'.$date.'%')
                            ->OrWhere('c.updated_at','LIKE','%'.$date.'%');
                            })->count();
          }
          return view('adminpanel.customers.index2', compact('customers','name_or_phone','date','count'));
          } else {
              abort(403);
          }
    }
    public function indexCardHolders() // User Can view all the details of Customer
    {
      $customers = DB::table('customers as c')
                      ->join('status as s','s.id','c.status_id')
                      ->leftjoin('customer_attachements as a','a.customer_id','c.id')
                      ->leftjoin('organizations as o','o.id','c.organization_id')
                      ->select('c.*','s.name as status','a.attachment','o.name as organization_name')
                      ->whereNotNull('card_id')
                      ->where('c.deleted_at',null)
                      ->orderBy('updated_at','DESC')
                      ->get();
      return view('adminpanel.customers.general', compact('customers'));
    }
    public function OngoingProcedures() // User can View the list of Customer
    {
        if (Auth::user()->can('view_customers')) {

            $customers = DB::table('customers as c')
                ->join('status as s', 's.id', 'c.status_id')
                ->leftjoin('customer_attachements as a', 'a.customer_id', 'c.id')
                ->leftjoin('customer_procedures as cp', 'cp.customer_id', 'c.id')
                ->where('cp.status',2)
                ->where('c.deleted_at',null)
                ->select('c.*', 's.name as status', 'a.attachment')
                ->orderBy('updated_at', 'DESC')
                ->get();

            return view('adminpanel.customers.index-with-datatables', compact('customers'));

        } else {
            abort(403);
        }
    }
    public function NoShowCustomers() // User can View the no show status customers list
    {
        if (Auth::user()->can('view_customers')) {

            $customers = DB::table('customers as c')
                ->join('status as s', 's.id', 'c.status_id')
                ->leftjoin('customer_attachements as a', 'a.customer_id', 'c.id')
                ->where('s.id',13)
                ->where('c.deleted_at',null)
                ->select('c.*', 's.name as status', 'a.attachment')
                ->orderBy('updated_at', 'DESC')
                ->get();

            return view('adminpanel.customers.index-with-datatables', compact('customers'));

        } else {
            abort(403);
        }
    }
    public function corporate()
    { // User can view all the corporaters
      if (Auth::user()->can('view_customers')) {
      $customers = DB::table('customers as c')
                      ->join('status as s','s.id','c.status_id')
                      ->leftjoin('customer_attachements as a','a.customer_id','c.id')
                      ->leftjoin('organizations as o','o.id','c.organization_id')
                      ->select('c.*','s.name as status','a.attachment','o.name as organization_name')
                      ->where('organization_id','!=',NULL)
                      ->where('c.deleted_at',null)
                      ->orderBy('updated_at','DESC')
                      ->get();
          return view('adminpanel.customers.corporate', compact('customers'));
      } else {
          abort(403);
      }
    }
    public function GeneralCustomers(){                          // User can view all general customers
      if ( Auth::user()->can('view_customers') ) {
        $customers = DB::table('customers as c')
                        ->join('status as s','s.id','c.status_id')
                        ->leftjoin('customer_attachements as a','a.customer_id','c.id')
                        ->leftjoin('organizations as o','o.id','c.organization_id')
                        ->select('c.*','s.name as status','a.attachment','o.name as organization_name')
                        ->where('c.deleted_at',null)
                        ->where(['organization_id' => NULL, 'employee_code' => NULL, 'parent_id' => NULL])
                        ->orderBy('updated_at','DESC')
                        ->paginate(15);
        $count     =  DB::table('customers as c')
                        ->where(['organization_id' => NULL, 'employee_code' => NULL, 'parent_id' => NULL])
                        ->where('c.deleted_at', null)
                        ->count();
          return view('adminpanel.customers.index', compact('customers','count'));
        } else {
            abort(403);
        }
    }
    public function CanceledAppointments()
    {
        if (Auth::user()->can('view_customers')) {
            $customers = DB::table('customers as c')
                ->join('status as s', 's.id', 'c.status_id')
                ->leftjoin('customer_attachements as a', 'a.customer_id', 'c.id')
                ->leftjoin('customer_procedures as cp','cp.customer_id','c.id')
                ->select('c.*', 's.name as status', 'a.attachment','cp.treatments_id','cp.doctor_id','cp.hospital_id')
                ->orderBy('updated_at', 'DESC')
                ->where('c.deleted_at',null)
                ->where('cp.status',1)
                ->groupBy('cp.customer_id')
                ->get();
            return view('adminpanel.customers.index-with-datatables', compact('customers'));
        } else {
            abort(403);
        }
    }
    public function show_deleted() // User can View the list of Customer
    {
      if (Auth::user()->can('view_customers')) {
        $customers = DB::table('customers as c')
            ->join('status as s', 's.id', 'c.status_id')
            ->leftjoin('customer_attachements as a', 'a.customer_id', 'c.id')
            ->select('c.*', 's.name as status', 'a.attachment')
            ->where('c.deleted_at','!=',null)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('adminpanel.customers.soft_deleted', compact('customers'));
        } else {
            abort(403);
        }
    }

    public function create() // User Can view Create new Customer form
    {
        if (Auth::user()->can('create_customer')) {
            $centers        =   Center::where('is_active', 1)->orderBy('center_name','ASC')->get();
            $status         =   Status::where('active', 1)->get();
            $treatments     =   Treatment::where('is_active', 1)->where('parent_id', null)->orderBy('name','ASC')->get();
            $procedures     =   Treatment::where('is_active', 1)->orderBy('name','ASC')->get();
            $organization   =   Organization::all();
            $blood_groups   =   BloodGroup::all();
            $lab = Lab::where('is_active', 1)->orderBy('name','ASC')->get();
            return view('adminpanel.customers.create', compact('procedures', 'status', 'treatments', 'organization', 'lab','blood_groups'));
        } else {
            abort(403);
        }
    }

    public function store(Request $request) // User can Insert all the data of New Customer
    {
        if (Auth::user()->can('create_customer')) {
            $validate   = $request->validate([
              'phone'                  => 'nullable|unique:customers,phone',
            ]);
            $input = $request->all();
            $customer = $this->service->create($input);
            if (isset($customer[0]) != null) {
                session()->flash('error', $customer[1]);
                return redirect()->route('customers.create');
            } else {
                session()->flash('success', 'Customer Added Successfully');
                return redirect()->route('customers.index');
            }
        } else {
            abort(403);
        }
    }

    public function show($id)           // User Can view all the details of Customer
    {
        $customer           =  DB::table('customers as c')
                            ->join('status as s','s.id','c.status_id')
                            ->leftjoin('customer_attachements as a','a.customer_id','c.id')
                            ->select('c.*','s.name as status','a.attachment')
                            ->where(['c.id' => $id])
                            ->first();
        $centers            =   Center::where('is_active', 1)->get();
        $treatments         =   Treatment::where('is_active', 1)->where('parent_id', null)->get();
        $procedures         =   Treatment::where('is_active', 1)->whereNotNull('parent_id')->get();
        $doctors            =   Doctor::all();
        $blood_group        =   BloodGroup::find($customer->blood_group_id);
        $doctor_notes       =   CustomerDoctorNotes::where('customer_id',$id)->first();
        $risk_factor_notes  =   CustomerRiskFactor::where('customer_id',$id)->get();
        $allergy_notes      =   CustomerAllergy::where('customer_id',$id)->get();
        // $employee       =   Customer::where('parent_id',$id)->withTrashed()->get();
        $employee           =   DB::table('customer_dependents as cd')
                            ->join('customers as c','c.id','cd.parent_customer_id')
                            ->where('cd.assc_customer_id',$id)
                            ->select('c.id','c.name','c.phone','c.dob','cd.relation','c.gender','c.weight','c.height','c.marital_status','c.address','cd.bundle_id','cd.status','c.email')
                            ->get();
        $labs            =   Lab::all();
        $customers      =   Customer::where('id',$id)->with(['diagnostics','labs'])->withTrashed()->first();
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
        // $careall_pass   =   DB::table('customer_careall as cc')->
        //                     join('careallpass_category')->where('customer_id',$id)->first();
        // dd($careall_pass);
        return view('adminpanel.customers.show', compact('customer','centers','treatments','procedures','doctors','employee','display','lab','blood_group','doctor_notes','risk_factor_notes','allergy_notes','labs'));
    }

    public function edit($id) // User can view Edit Cutomer form
    {
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
        // $diagnostics    = Diagnostics::where('is_active', 1)->get();

        // $diagnostics_bundle    =   DB::table('customer_diagnostics')
        //                         ->Where('customer_id',$id)
        //                         ->select('bundle_id')
        //                         ->groupBy('bundle_id')
        //                         ->get()->take(2);
        // if(count($diagnostics_bundle) > 0){
        //     if(isset($diagnostics_bundle[0])) {
        //     $customer_diagnostics_table1     =   DB::table('customer_diagnostics as cd')
        //                                     ->join('diagnostics as d','d.id','cd.diagnostic_id')
        //                                     ->join('labs as l','l.id','cd.lab_id')
        //                                     ->Where(['bundle_id' => $diagnostics_bundle[0]->bundle_id, 'customer_id' => $id])
        //                                     ->select('d.id as id','d.name as name','cd.*','l.name as lab_name')
        //                                     ->get();
        //     } else{
        //         $customer_diagnostics_table1     = null;
        //     }
        //     if(isset($diagnostics_bundle[1])) {
        //     $customer_diagnostics_table2     =   DB::table('customer_diagnostics as cd')
        //                                     ->join('diagnostics as d','d.id','cd.diagnostic_id')
        //                                     ->join('labs as l','l.id','cd.lab_id')
        //                                     ->Where(['bundle_id' => $diagnostics_bundle[1]->bundle_id, 'customer_id' => $id])
        //                                     ->select('d.id as id','d.name as name','cd.*','l.name as lab_name')
        //                                     ->get();
        //     } else {
        //         $customer_diagnostics_table2     = null;
        //     }
        // }
        $users = DB::table('role_user as ru')
            ->join('users as u', 'ru.user_id', 'u.id')
            ->where('ru.role_id', 6)
            ->OrWhere('ru.role_id', 1)
            ->OrWhere('ru.role_id', 9)
            ->select('ru.role_id', 'ru.user_id', 'u.name')
            ->orderBy('u.name','ASC')
            ->get();

        return view('adminpanel.customers.edit', compact('labs','allergies','riskfactor','customer', 'customer_status', 'organization', 'status', 'centers', 'procedures', 'treatments', 'doctors', 'users','blood_groups','doctor_notes'));
    }

    public function update(Request $request, $id) // User can insert updated data of customer
    {
        if (Auth::user()->can('create_customer')) {
            $validate   = $request->validate([
              'phone'                  => 'nullable|unique:customers,phone,'.$id,
            ]);
            $input = $request->all();
            // dd($input);
            $customer = $this->service->update($input, $id);
            if (isset($customer[0]) != null) {
                session()->flash('error', $customer[1]);
                return redirect()->route('customers.edit', $id);
            } else {
                session()->flash('success', 'Customer Updated Successfully');
                return redirect()->route('customers.show', [$id]);
            }
        }else{
            abort(403);
        }
    }
    public function destroy($id)                                                            // Soft Delete
    {
      $deleted_customer = Customer::where('id', $id)->delete();
      $deleted_user = User::where('customer_id', $id)->delete();
      session()->flash('success', 'Customer Deleted Successfully');
      return redirect()->back();
    }
    public function search_destroy($id)                                                            // Soft Delete
    {
      $deleted_customer = Customer::where('id', $id)->delete();
      $deleted_user = User::where('customer_id', $id)->delete();
      session()->flash('success', 'Customer Deleted Successfully');
      return redirect()->route('customers.index');
    }
    public function per_delete($id)                                                         // permanet Delete
    {
      $customer = Customer::where('parent_id', $id)->withTrashed()->get();
      if ($customer) {
          $dependent = Customer::where('parent_id', $id)->update([
              'parent_id' => null,
          ]);
      }
      $customer_user = User::where("customer_id", $id)->withTrashed()->first();
      if(isset($customer_user)){
          $delete_customer_user = $customer_user->forcedelete();
      }
      $customer_images       =   CustomerImages::where('customer_id',$id)->forceDelete();
      $old_allergies_notes   =   CustomerAllergy::where('customer_id',$id)->forceDelete();
      $old_riskfactor_notes  =   CustomerRiskFactor::where('customer_id',$id)->forceDelete();
      $customer_doctor_notes =   CustomerDoctorNotes::where('customer_id',$id)->forceDelete();
      $deleted_customer      =   Customer::where('id', $id)->forcedelete();
      if ($deleted_customer) {

          $delete_customer_procedure          = DB::table('customer_procedures')->where('customer_id', $id)->delete();
          $delete_customer_treatment_history  = DB::table('customer_treatment_history')->where('customer_id', $id)->delete();
          $delete_customer_diagnostics        = DB::table('customer_diagnostics')->where('customer_id', $id)->delete();
          $delete_customer_diagnostics_history= DB::table('customer_diagnostic_history')->where('customer_id', $id)->delete();
          $delete_customer_attachements       = DB::table('customer_attachements')->where('customer_id', $id)->delete();
          $coordinator_performance            = DB::table('coordinator_performance')->where('customer_id', $id)->delete();
      }
      session()->flash('success', 'Customer Deleted Successfully');
      return redirect()->back();
    }
    public function restore($id)                                                              // retore deleted data
    {
        $deleted_customer = Customer::where('id', $id)->withTrashed()->restore();
        session()->flash('success', 'Customer restore Successfully');
        return redirect()->back();
    }
  public function TempLeads(){
    $temp_customers = TempCustomer::orderBy('updated_at','DESC')->whereNotNull('phone')->where('organization_id',NULL)->where('lead_from',0)->get();
    $info           = "ChatBot";
    return view('adminpanel.tempcustomers.index',compact('temp_customers','info'));
  }
  public function EditLeads($id)
  {
    $customer       = TempCustomer::where('id',$id)->with('customer_notes')->first();
    $customer_notes = TempNotes::where('customer_id',$id)->get();
    $lab            = Lab::where('is_active',1)->get();
    $organization   = Organization::all();
    $centers        = Center    ::where('is_active' ,1)->get();
    $status         = Status    ::where('active',    1)->get();
    $treatments     = Treatment ::where('is_active', 1)->where('parent_id',NULL)->get();
    $procedures     = Treatment ::where('is_active', 1)->whereNotNull('parent_id')->get();
    $blood_groups   = BloodGroup::all();
    $users          = DB::table('role_user as ru')
                      ->join('users as u','ru.user_id','u.id')
                      ->where('ru.role_id',6)
                      ->OrWhere('ru.role_id',1)
                      ->OrWhere('ru.role_id',9)
                      ->select('ru.role_id','ru.user_id','u.name')
                      ->get();
    $customer_lead  = 1;                                                                                    // those customer which are coming from facebook, facebook lead status = 1 and website lead = 2
    $time           =  (isset($customer->appointment_date) ? Carbon::parse($customer->appointment_date)->format('Y-m-d\TH:i') : NULL);

    return view('adminpanel.tempcustomers.edit', compact('customer','customer_notes','customer_lead','organization','status','centers','procedures','treatments','users','time','lab','blood_groups'));
  }
  public function UpdateLeads( Request $request, $id)
  {
    if ( Auth::user()->can('create_customer') ) {
        $input = $request->all();
            $validate   = $request->validate([
              'phone'                  => 'required|unique:customers,phone',
            ]);
        $customer = $this->service->create($input);
        if (isset($customer[0]) != null) {
            session()->flash('error', $customer[1]);
            return redirect()->route('customers.create');
        } else {
            TempNotes::where('customer_id', $id)->delete();
            TempCustomer::where('id', $id)->delete();
            session()->flash('success', 'Customer Added Successfully');
            return redirect()->route('customers.index');
        }
    } else {
        abort(403);
    }
    }

    public function DestroyLeads(Request $request)
    {
        $id = $request->id;
        if (TempCustomer::destroy($id)) {
            TempNotes::where('customer_id', $id)->delete();
            // session()->flash('success', 'Lead is been Deleted Successfully');
            return response()->json(["data" =>"okay"],200);
        }
    }
    public function TreatmentHistory( $id) // User Can view all the Histroy of Customer Treatments
    {
      $customer  =  DB::table('customers as c')
                    ->join('status as s','s.id','c.status_id')
                    ->leftjoin('customer_attachements as a','a.customer_id','c.id')
                    ->select('c.*','s.name as status','a.attachment')
                    ->where(['c.id' => $id])
                    ->first();
       return view('adminpanel.customers.treatment_history', compact('customer'));
    }
    // Admin can add new notes for customer detail page
    public function editCustomerNotes(Request $request, $id){
        if(isset($request->notes)){
            $insert      =   Customer::where('id',$id)->update(['notes' => $request->notes]);
            session()->flash('success', 'Customer Notes Updated successfully');
        }else{
            session()->flash('error', 'Enter valid data');
        }
    return redirect()->back();
    }
    // Admin can add new Allergies Notes for customer from customer detail page
    public function editCustomerAllergiesNotes(Request $request, $id){
    if($request['allergies_notes']){
        $delete = CustomerAllergy::where('customer_id',$id)->forcedelete();
        $allergies_notes = $request['allergies_notes'];
        foreach($allergies_notes as $an){
            if(isset($an)){
                $allergies_notes_create = CustomerAllergy::create([
                    'notes'         => $an,
                    'customer_id'   => $id,
                ]);
            }
        }
        session()->flash('success', 'Customer Allergies Updated Successfully');
    }else{
        session()->flash('error', 'Enter Valid Data');
    }
    return redirect()->back();
    }
    // Admin can add new risk factor notes for customer from customer detail page
    public function editCustomerRiskFactorNotes(Request $request, $id){
    if($request['riskfactor_notes']){
        $delete = CustomerRiskFactor::where('customer_id',$id)->forcedelete();
        $riskfactor_notes = $request['riskfactor_notes'];
        foreach($riskfactor_notes as $rn){
            if(isset($rn)){
                $riskfactor_notes_create = CustomerRiskFactor::create([
                    'notes'         =>  $rn,
                    'customer_id'   =>  $id,
                ]);
            }
        }
        session()->flash('success', 'Customer Risk Factor Updated Successfully');
    }else{
        session()->flash('error', 'Enter Valid Data');
    }

    return redirect()->back();
    }
    public function createNewAppointment(Request $request, $id){                                                    // Create new appointment through customer show
        $treatment_id       = $request->treatment_id;
        $procedure_id       = $request->procedure_id;
        if($procedure_id == 0){
            $procedure_id = $treatment_id;
        }
        $appointment_date= Carbon::parse($request->appointment_date)->toDateTimeString();
        if ($procedure_id != null &&  $request->hospital_id != null &&  $request->hospital_id != 0 && $request->doctor_id != null && $request->doctor_id != 0) {
            $customer_procedure_id = DB::table('customer_procedures')->insertGetId([
                'customer_id'       => $id,
                'treatments_id'     => $procedure_id,
                'hospital_id'       => $request->hospital_id,
                'doctor_id'         => $request->doctor_id,
                'cost'              => $request->cost[0],
                'discount_per'      => $request->treatment_discount,
                'discounted_cost'   => ($request->discounted_cost != 0)? $request->discounted_cost : $request->cost[0],
                'status'            => 0,
                'appointment_date'  => $appointment_date,
                'appointment_from'  => isset($request->appointment_from)?$request->appointment_from:0,
            ]);
            $data           = datafromCustomerProcedureId($customer_procedure_id);
            $doctorName     = $data->doctor_name." ".$data->doctor_last_name;
            $doctor_phone   = ($data->doctor_phone) ? $data->doctor_phone : $data->assistant_phone;
            $customerName   = $data->name;
            $at             = $data->center_name;
            $location       = $data->address;
            $map            = 'http://maps.google.com/?q='.$data->lat.','.$data->lng;
            $date_orginal   = Carbon::parse($data->appointment_date);
            $date           = $date_orginal->format('Y-m-d h:i A');                         // Appointment date
            $fdate          = $date_orginal->format('jS F Y');
            $time           = $date_orginal->format('h:i A');
            $n              = '\n';
            //Message to Customer/Patient
            $message        = "Dear+$customerName,".$n.$n."Your+appointment+has+been+booked+with+$doctorName+at+$at.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Location:+$location".$n."Google+Map:+$map".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies. You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400";
            $sms            = CustomerAppointmentSms($message, $data->customer_phone);

            //Message to Doctor
           if(isset($doctor_phone)){                       // Send message to doctor about appointment
             $message        = "Hello+$doctorName,".$n.$n."You+have+an+appointment+with+$customerName.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Center/Clinic:+$at.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400";
             $sms            = CustomerAppointmentSms($message, $doctor_phone);
         }
            //Notification to Doctor User
         $message        = "Your new appointment is scheduled at $date with $customerName at $at";
         $check_doctor_in_users = User::where('doctor_id',$data->doctor_id)->first();
         if($check_doctor_in_users){
            NotificationHelper::GENERATE([
                'title' => 'New Appointment',
                'body' => $message,
                'payload' => [
                    'type' => "New appointment"
                ]
            ],[$data->doctor_id]);
        }
        //Notification to Customer User
        $message                    = "Your appointment scheduled at $date with $doctorName at $at has been Approved";
        $check_customer_in_users    = User::where('customer_id',$data->customer_id)->first();
            if($check_customer_in_users){
                NotificationHelper::GENERATE([
                    'title' => 'Appointment Approved!',
                    'body' => $message,
                    'payload' => [
                        'type' => "New appointment"
                    ]
                ],[$data->customer_id]);
            }
        session()->flash('success', $data->name."'s Appointments is Booked Successfully with ".$doctorName);
        return redirect()->back();
    }
    session()->flash('error', " Something is missing. Select all fields to continue");
    return redirect()->back();
    }
    public function nextAppointment(Request $request){
        $input = $request->all();
        $timestamp = Carbon::createFromTimestamp(strtotime($request->appointment_date))->toDateTimeString();
        $customer_procedures = DB::table('customer_procedures')->where('id',$request->id)->first();
        if ($customer_procedures) {
            $customer_history = DB::table('customer_treatment_history')->insert([                 // insert customer treatment details in customer history table
            'customer_id'   => $customer_procedures->customer_id,
            'treatments_id' => $customer_procedures->treatments_id,
            'hospital_id'   => $customer_procedures->hospital_id,
            'doctor_id'     => $customer_procedures->doctor_id,
            'cost'          => $customer_procedures->cost,
            'discount_per'  => $customer_procedures->discount_per,
            'discounted_cost'=> $customer_procedures->discounted_cost,
            'appointment_date' => $customer_procedures->appointment_date,
            'appointment_from' => $customer_procedures->appointment_from,
        ]);
        $new_customer_procedures = DB::table('customer_procedures')->insert([                      // insert new appointment date in customer procedure table
            'customer_id'   => $customer_procedures->customer_id,
            'treatments_id' => $customer_procedures->treatments_id,
            'hospital_id'   => $customer_procedures->hospital_id,
            'doctor_id'     => $customer_procedures->doctor_id,
            'cost'          => $request->cost,
            'discount_per'  => 0,
            'discounted_cost'=> $request->cost,
            'status'        => 2,                                                                 // when status is 2 it means treatment is ongoing, and doctor added new appointment date;
            'appointment_date' => $timestamp,
            'appointment_from' => $customer_procedures->appointment_from,
        ]);
       $customer_phone         = customerPhone($customer_procedures->customer_id);                                            // Get Customer phone number
       $customer_name          = customerName($customer_procedures->customer_id);
       $doctorName             = doctorName($customer_procedures->doctor_id);
       $doctor_phone           = doctorPhone($customer_procedures->doctor_id);
       $at                     = centerName($customer_procedures->hospital_id);
       $location               = centerlocation($customer_procedures->hospital_id);
       $map                    = centerMap($customer_procedures->hospital_id);
       $date                   = Carbon::parse($timestamp);                             // Appointment date
       $fdate                  = $date->format('jS F Y');
       $time                   = $date->format('h:i A');
       $n                      = '\n';
       if(isset($customer_phone)){
                                                                                                          // send message to customer
           $message        = "Dear+$customer_name,".$n.$n."Your+appointment+has+been+booked+with+$doctorName+at+$at.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Location:+$location".$n."Google+Map:+$map".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies.+You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com".$n."For+Queries:+0322-2555600,".$n."0322-2555400";
           $sms            = CustomerAppointmentSms($message, $customer_phone);
       }
       if(isset($doctor_phone)){
        $message        = "Hello+$doctorName,".$n.$n."You+have+an+appointment+with+$customer_name.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Center/Clinic:+$at.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400";
        $sms            = CustomerAppointmentSms($message, $doctor_phone);
       }
        $message="Your Next Appointment with $customer_name is on $date and at $at";
        NotificationHelper::GENERATE([
            'title' => 'Appointment Updated',
            'body' => $message,
            'payload' => [
                'type' => "Next Appointment"
            ]
        ],[$customer_procedures->doctor_id]);
        $message="Your Next Appointment with $doctorName is on $date and at $at";
        NotificationHelper::GENERATE([
            'title' => 'Appointment Updated',
            'body' => $message,
            'payload' => [
                'type' => "Next Appointment"
            ]
        ],[$customer_procedures->customer_id]);

        $customer = DB::table('customer_procedures')->where('id',$request->id)->delete(); // delete perivous customer procedure data
        }
        $message = "next appointment has been scheduled";
        return  compact('message');
    }
    public function TreatmentToHistory(Request $request,$id)
    {
      $treatment_id                 =   $request->treatment_id;
      $center_id                    =   $request->center_id;
      $doctor_id                    =   $request->doctor_id;
      $cost                         =   $request->cost;
      $discounted_cost              =   $request->discounted_cost;
      $discount_per                 =   $request->discount_per;
      $appointment_date             =   $request->appointment_date;
      $appointment_from             =   $request->appointment_from;
      $insert                       =   DB::table('customer_treatment_history')->insert([
        'customer_id'               =>  $id,
        'treatments_id'             =>  $treatment_id,
        'hospital_id'               =>  $center_id,
        'doctor_id'                 =>  $doctor_id,
        'cost'                      =>  $cost,
        'discounted_cost'           =>  $discounted_cost,
        'discount_per'              =>  $discount_per,
        'appointment_date'          =>  $appointment_date,
        'appointment_from'          =>  $appointment_from,
        ]);
      if ($insert) {
        $delete                     =   DB::table('customer_procedures')->where([
                    'customer_id'   =>  $id,
                    'treatments_id' =>  $treatment_id,
                    'hospital_id'   =>  $center_id,
                    'doctor_id'     =>  $doctor_id,
        ])->delete();
        if ($delete) {
        session()->flash('success', 'Treatment History Updated Successfully');
        return redirect()->route('customers.show', [$id]);
        } else {
        session()->flash('success', 'Treatment Moved to History But Couldn\'t remove it from here!');
        return redirect()->route('customers.show', [$id]);
      }
      }
        session()->flash('error', 'Sorry! Something Just Happened');
        return redirect()->route('customers.show', [$id]);
    }
    public function NewDiagnosticAppointment(Request $request, $id)
    {
        $validate   = $request->validate([
            'diagnostic_id'                  => 'required',
            'lab_id'                         => 'required',
            'diagnostics_cost'               => 'required',
            'diagnostic_appointment_date'    => 'sometimes',
            'discount'                       => 'sometimes',
        ]);
        $customer_id                    =   $id;
        $lab_id                         =   $request->lab_id;
        $diagnostic_id                  =   $request->diagnostic_id;
        $cost                           =   $request->diagnostics_cost;
        $diagnostic_appointment_date    =   $request->diagnostic_appointment_date;
        $discount                       =   $request->discount;
        $arr_count                      =   count($diagnostic_id);
        $bundle_id                      =   $customer_id.time().rand(10,100000);
        for ($i=0; $i < $arr_count; $i++) {
            if($discount != 0){
                $discounted_cost        = $cost[$i] - ($cost[$i] * ($discount/100));
            } elseif ($discount == 0) {
                $discounted_cost        = $cost[$i];
            }
            $insert     =   DB::table('customer_diagnostics')->insertGetId([
                'customer_id'       =>  $customer_id,
                'diagnostic_id'     =>  $diagnostic_id[$i],
                'lab_id'            =>  $lab_id,
                'cost'              =>  $cost[$i],
                'discounted_cost'   =>  $discounted_cost,
                'appointment_date'  =>  $diagnostic_appointment_date,
                'appointment_from'  =>  0,
                'discount_per'      =>  $discount,
                'status'            =>  0,
                'home_sampling'     =>  isset($request->home_sampling) ? $request->home_sampling : 0,
                'bundle_id'         =>  $bundle_id,
            ]);
        }
        if ($insert) {
            $data['patient_coordinator_id'] = Auth::user()->id;
            $created =  Carbon::now()->toDateTimeString();
            $updated =  null;
            $coordinator_performance = CoodinatorPerformance::create($this->service->getSecureInputCoodinatorPerformance($data,$customer_id,$created,$updated));
            $update_customer        =   Customer::where('id',$customer_id)->update(['updated_at' => $updated]);
            $customer   =   Customer::where('id',$customer_id)->first();
            if(isset($customer->phone)){ // send message to customer
                $date           =   Carbon::parse($diagnostic_appointment_date);     // Appointment date
                $diagnostic_date=   $date->format('Y-m-d h:i A');
                $time           =   $date->format('h:i A');
                $fdate          =   $date->format('jS F Y');
                $lab            =   Lab::where('id',$lab_id)->withTrashed()->first();
                $with           =   $lab->name;
                $location       =   $lab->address;
                $n              =   '\n';
                $message        =   "Dear+$customer->name,".$n.$n."Your+appointment+has+been+booked+with+$with.".$n.$n."Date: $fdate".$n."Time:$time".$n.$n."Location:+$location".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies. You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Now you can download our CareALL App for booking appointments and managing your health records: https://bit.ly/2GBo2Nm";
                $sms            =   CustomerAppointmentSms($message, $customer->phone);
            }
            $check_customer_in_users = User::where('customer_id',$customer_id)->first();
            if(isset($check_customer_in_users)){
                $message        =   isset($request->home_sampling) ? "Your new appointment for Diagnostics is scheduled at $date by $with at your given address" : "Your new appointment for Diagnostics is scheduled at $date";
                NotificationHelper::GENERATE([
                    'title'     => 'New Appointment!',
                    'body'      => $message,
                    'payload'   => [
                    'type'      => "New Appointment!"
                    ]
                ],$check_customer_in_users->id);
            }
            session()->flash('success','Diagnostic added Successfully!');
            return redirect()->route('customers.show',$customer_id);
        } else {
            session()->flash('error','Could not Add the Diagnostic');
            return redirect()->route('customers.show',$customer_id);
        }
    }
    public function EditDiagnosticAppointment($bundle_id)
    {
        $bundles        =   DB::table('customer_diagnostics')->where('bundle_id',$bundle_id)->get();
        $labs           =   Lab::where('is_active', 1)->orderBy('name','ASC')->get();
        $diagnostics    =   Diagnostics::where('is_active', 1)->get();

        return view('adminpanel.customers.edit_diagnostics',compact('bundles','labs','diagnostics'));
        // dd($bundles);
    }
    public function UpdateDiagnosticAppointment(Request $request, $bundle_id)
    {
        $validate   = $request->validate([
            'diagnostic_id'                  => 'required',
            'lab_id'                         => 'required',
            'diagnostics_cost'               => 'required',
            'diagnostic_appointment_date'    => 'sometimes',
            'discount'                       => 'sometimes',
        ]);
        $bundles        =   DB::table('customer_diagnostics')->where('bundle_id',$bundle_id)->first();
        $customer_id                    =   $bundles->customer_id;
        $lab_id                         =   $request->lab_id;
        $diagnostic_id                  =   $request->diagnostic_id;
        $cost                           =   $request->diagnostics_cost;
        $diagnostic_appointment_date    =   $request->diagnostic_appointment_date;
        $time_parsed                    =   Carbon::parse($diagnostic_appointment_date);
        $old_time                       =   Carbon::parse($bundles->appointment_date);
        $discount                       =   $request->discount;
        $arr_count                      =   count($diagnostic_id);
        $bundle_id                      =   $bundles->bundle_id;
        $delete_bundles        =   DB::table('customer_diagnostics')->where('bundle_id',$bundle_id)->delete();
        if ($delete_bundles) {
            for ($i=0; $i < $arr_count; $i++) {
                if($discount != 0){
                    $discounted_cost        = $cost[$i] - ($cost[$i] * ($discount/100));
                } elseif ($discount == 0) {
                    $discounted_cost        = $cost[$i];
                }
                $insert     =   DB::table('customer_diagnostics')->insertGetId([
                    'customer_id'       =>  $customer_id,
                    'diagnostic_id'     =>  $diagnostic_id[$i],
                    'lab_id'            =>  $lab_id,
                    'cost'              =>  $cost[$i],
                    'discounted_cost'   =>  $discounted_cost,
                    'appointment_date'  =>  $diagnostic_appointment_date,
                    'appointment_from'  =>  0,
                    'discount_per'      =>  $discount,
                    'status'            =>  0,
                    'home_sampling'     =>  isset($request->home_sampling) ? $request->home_sampling : 0,
                    'bundle_id'         =>  $bundle_id,
                ]);
            }
            if ($insert) {
                $data['patient_coordinator_id'] = Auth::user()->id;
                $updated =  Carbon::now()->toDateTimeString();
                $created =  null;
                $coordinator_performance = CoodinatorPerformance::create($this->service->getSecureInputCoodinatorPerformance($data,$customer_id,$created,$updated));
                $update_customer        =   Customer::where('id',$customer_id)->update(['updated_at' => $updated]);
            }
            if (!$time_parsed->equalTo($bundles->appointment_date) ) {
                $customer   =   Customer::where('id',$customer_id)->first();
                if(isset($customer->phone)){ // send message to customer
                    $date               =   Carbon::parse($diagnostic_appointment_date);     // Appointment date
                    $diagnostic_date    =   $date->format('Y-m-d h:i A');
                    $time               =   $date->format('h:i A');
                    $fdate              =   $date->format('jS F Y');
                    $lab                =   Lab::where('id',$lab_id)->withTrashed()->first();
                    $with               =   $lab->name;
                    $location           =   $lab->address;
                    $n                  =   '\n';
                    $message            =   "Dear+$customer->name,".$n.$n."Your+appointment+has+been+booked+with+$with.".$n.$n."Date: $fdate".$n."Time:$time".$n.$n."Location:+$location".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies. You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Now you can download our CareALL App for booking appointments and managing your health records: https://bit.ly/2GBo2Nm";
                    $sms            =   CustomerAppointmentSms($message, $customer->phone);
                }
                $check_customer_in_users = User::where('customer_id',$customer_id)->first();
                if(isset($check_customer_in_users)){
                    $message        =   isset($request->home_sampling) ? "Your new appointment for Diagnostics is scheduled at $date by $with at your given address" : "Your new appointment for Diagnostics is scheduled at $date";
                    NotificationHelper::GENERATE([
                        'title' => 'Appointment Update!',
                        'body' => $message,
                        'payload' => [
                            'type' => "Appointment Update!"
                        ]
                    ],$check_customer_in_users->id);
                }
            }
            session()->flash('success','Diagnostic Updated Successfully!');
            return redirect()->route('customers.show',$customer_id);
        } else {
            session()->flash('error','Could not Update the Diagnostic');
            return redirect()->route('customers.show',$customer_id);
        }
    }
    public function DiagnosticHistory($id) // User Can view all the History of Customer Diagnostic
    {
      $customer  =  DB::table('customers as c')
                    ->join('status as s','s.id','c.status_id')
                    ->leftjoin('customer_attachements as a','a.customer_id','c.id')
                    ->select('c.*','s.name as status','a.attachment')
                    ->where(['c.id' => $id])
                    ->first();
      $customer_history = DB:: table('customer_treatment_history')->where('customer_id',$id)->get();
      return view('adminpanel.customers.diagnostic_history', compact('customer','customer_history'));
    }
  public function DiagnosticToHistory(Request $request,$id)
  {
    if ($request->bundle_id) {
        $bundle_id          =   $request->bundle_id;
        $bundles             =   DB::table('customer_diagnostics')->where('bundle_id',$bundle_id)->get();
        if ($bundles) {
            if ($bundles[0]->appointment_date == null) {
                session()->flash('error', 'Please Update the Appointment Date for these Diagnostics to close');
                return redirect()->route('customers.show', [$id]);
            }
            foreach ($bundles as $bundle) {
                $insert             =   DB::table('customer_diagnostic_history')->INSERT([
                'customer_id'       =>  $id,
                'diagnostic_id'     =>  $bundle->diagnostic_id,
                'lab_id'            =>  $bundle->lab_id,
                'cost'              =>  $bundle->cost,
                'discounted_cost'   =>  $bundle->discounted_cost,
                'appointment_date'  =>  $bundle->appointment_date,
                'appointment_from'  =>  $bundle->appointment_from,
                'discount_per'      =>  $bundle->discount_per,
                'bundle_id'         =>  $bundle_id,
                ]);
            }
            if ($insert) {
                $delete     =   DB::table('customer_diagnostics')->where([
                    'bundle_id'    =>  $bundle_id,
                ])->delete();
              if ($delete) {
              session()->flash('success', 'Diagnostic History Updated Successfully');
              return redirect()->route('customers.show', [$id]);
              } else {
                  session()->flash('error', 'Diagnostic Moved to History But Couldn\'t remove it from here!');
                  return redirect()->route('customers.show', [$id]);
                }
            }
        }
    }
      session()->flash('error', 'Sorry! Couldn\'t find these Diagnostics');
      return redirect()->route('customers.show', [$id]);
  }
}
