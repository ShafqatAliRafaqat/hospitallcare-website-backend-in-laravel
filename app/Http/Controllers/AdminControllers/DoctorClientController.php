<?php

namespace App\Http\Controllers\AdminControllers;

use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\BloodGroup;
use App\Models\Admin\Center;
use App\Models\Admin\Customer;
use App\Models\Admin\CustomerAllergy;
use App\Models\Admin\CustomerDoctorNotes;
use App\Models\Admin\CustomerRiskFactor;
use App\Models\Admin\Doctor;
use App\Models\Admin\Treatment;
use App\Notification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DoctorClientController extends Controller
{
    public function unique_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
    public function index(){                                                                // Show All Customers which are created by doctor it self
        $doctor_id      = Auth::user()->doctor_id;
        $clients        = Customer::where('doctor_id',$doctor_id)->orderBy('updated_at','DESC')->get();
        return view('doctorpanel.clients.index', compact('clients'));

    }

    public function create()                                                                        // Doctor can create new customer
    {
        $blood_groups   =   BloodGroup::all();
        return view('doctorpanel.clients.create', compact('blood_groups'));
    }

    public function store(Request $request)                                                         // Doctor can Store data of new Customer
    {
        // dd($request->all());
          $validate = $request->validate([
              'name'                   => 'required|min:3',
              'email'                  => 'sometimes',
              'phone'                  => 'required|unique:customers,phone',
              'address'                => 'sometimes',
              'gender'                 => 'required',
              'marital_status'         => 'required',
              'age'                    => 'sometimes',
              'weight'                 => 'sometimes',
              'height'                 => 'sometimes',
              'notes'                  => 'required',
          ]);

        $doctor_id       =   Auth::user()->doctor_id;
        $customer_id = DB::table('customers')->insertGetId([
              'ref'                   => $this->unique_code(4),
              'name'                  => $request->name,
              'email'                 => $request->email,
              'phone'                 => $request->phone,
              'address'               => $request->address,
              'city_name'             => isset($request->city)? $request->city : null,
              'gender'                => $request->gender,
              'doctor_id'             => $doctor_id,
              'marital_status'        => $request->marital_status,
              'age'                   => $request->age,
              'weight'                => $request->weight,
              'height'                => $request->height,
              'notes'                 => $request->notes,
              'status_id'             => 10,
              'customer_lead'         => 4,                      // customer created by doctor website
              'blood_group_id'        => isset($request->blood_group_id)? $request->blood_group_id: null,
              'created_at'            => Carbon::now()->toDateTimeString(),
              'updated_at'            => Carbon::now()->toDateTimeString(),
          ]);
          if ($request->riskfactor_notes) {
            if($request['riskfactor_notes'][0] != null){
                $riskfactor_notes = $request['riskfactor_notes'];
                foreach($riskfactor_notes as $rn){
                    if(isset($rn)){
                        $riskfactor_notes_create = CustomerRiskFactor::create([
                            'notes'         =>  $rn,
                            'customer_id'   =>  $customer_id,
                        ]);
                    }
                }
            }
        }
        if ($request['allergies_notes']) {
            if($request['allergies_notes'][0] != null){
                $allergies_notes = $request['allergies_notes'];
                foreach($allergies_notes as $an){
                    if(isset($an)){
                        $allergies_notes_create = CustomerAllergy::create([
                            'notes'         => $an,
                            'customer_id'   => $customer_id,
                        ]);
                    }
                }
            }
        }
         if ($request['doctor_notes']) {
                $date               =   Carbon::now()->format('m/d/Y');
                $doctor_id          =   Auth::user()->doctor_id;
                $doctor_name        =   Doctor::where('id',$doctor_id)->select('name')->first();
                $doctor_notes       =   $request->doctor_notes;
                $notes              =   $doctor_name->name.' On '.$date.'  '.$doctor_notes;
                $insert             =   CustomerDoctorNotes::create(['notes' => $notes, 'customer_id' => $customer_id]);
        }
          $clients        = Customer::where('doctor_id',$doctor_id)->get();
          session()->flash('success', 'Customer Added Successfully');
          return redirect()->route('doctorclients.index');
    }

    public function show($id)                                                           // Doctor can view details of any customer
    {
       $doctor_id  =   Auth::user()->doctor_id;
       $customer       =  Customer::Where('id',$id)->withTrashed()->first();
        if($customer){
            $centers        =   Center::where('is_active', 1)->get();
            $treatments     =   Treatment::where('is_active', 1)->where('parent_id', null)->get();
            $procedures     =   Treatment::where('is_active', 1)->whereNotNull('parent_id')->get();
            $doctors        =   Doctor::all();
            $blood_group    =   BloodGroup::find($customer->blood_group_id);
            $doctor_notes   =   CustomerDoctorNotes::where('customer_id',$id)->get();
            $risk_factor_notes= CustomerRiskFactor::where('customer_id',$id)->get();
            $allergy_notes  =   CustomerAllergy::where('customer_id',$id)->get();
            $employee       =   Customer::where('parent_id',$id)->withTrashed()->get();
            $customers      =   Customer::where('id',$id)->with(['diagnostics','labs'])->withTrashed()->first();
            $doctor         =   Doctor:: where('id',$doctor_id)->with('centers')->first();
            if ($doctor->centers) {
                foreach ($doctor->centers as $s) {
                $array[]  =  $s->id;
                }
                if (isset($array)) {
                $center_id       = array_values(array_unique($array)); //Reorder array after making it unique
                }else{
                    $center_id = null;
                }
            }
            // dd($doctor,$center_id);
            $display = null;
            $lab = null;
            return view('doctorpanel.clients.show', compact('customer','doctor','center_id','centers','treatments','procedures','doctors','employee','display','lab','blood_group','doctor_notes','risk_factor_notes','allergy_notes'));
        } else {
            abort(403);
        }

    }

    public function edit($id)                                                         // User can View Edit form of Employee
    {
        $doctor_id      = Auth::user()->doctor_id;
        $customer       = Customer::where('id', $id)->where('doctor_id',$doctor_id)->with(['treatments', 'center', 'doctor', 'diagnostics', 'labs'])->withTrashed()->first();
        if($customer){
            $doctor_notes   = CustomerDoctorNotes::where('customer_id',$id)->get();
            $allergies      = CustomerAllergy::where('customer_id',$id)->select('notes')->get();
            $riskfactor     = CustomerRiskFactor::where('customer_id',$id)->select('notes')->get();
            $blood_groups   = BloodGroup::all();

            return view('doctorpanel.clients.edit', compact('allergies','riskfactor', 'customer','blood_groups','doctor_notes'));
        } else {
            abort(403);
        }
    }
    public function update(Request $request, $id)                                    // User can Store updated data in database
    {
        $doctor_id  = Auth::user()->doctor_id;
        $customer   = DB::table('customers')->where('id',$id)->where('doctor_id',$doctor_id)->first();
        if($customer){
          $validate = $request->validate([
              'name'                   => 'required|min:3',
              'email'                  => 'sometimes',
              'phone'                  => 'required|unique:customers,phone,'.$id,
              'address'                => 'sometimes',
              'gender'                 => 'required',
              'marital_status'         => 'required',
              'age'                    => 'sometimes',
              'weight'                 => 'sometimes',
              'height'                 => 'sometimes',
          ]);
            $Update_employee            = DB::table('customers')->where('id',$id)->where('doctor_id',$doctor_id)->update([
                'name'                  => $request->name,
                'email'                 => $request->email,
                'phone'                 => $request->phone,
                'address'               => $request->address,
                'city_name'             => isset($request->city)? $request->city : null,
                'gender'                => $request->gender,
                'marital_status'        => $request->marital_status,
                'age'                   => $request->age,
                'weight'                => $request->weight,
                'height'                => $request->height,
                'blood_group_id'        => isset($request->blood_group_id)? $request->blood_group_id:null,
                'updated_at'            => Carbon::now()->toDateTimeString(),
              ]);
              if ($request->riskfactor_notes) {
                $old_riskfactor_notes   =   CustomerRiskFactor::where('customer_id',$id)->forceDelete();
                if($request['riskfactor_notes'][0] != null){
                    $riskfactor_notes = $request['riskfactor_notes'];
                    foreach($riskfactor_notes as $rn){
                        if(isset($rn)){
                            $riskfactor_notes_create = CustomerRiskFactor::create([
                                'notes'         =>  $rn,
                                'customer_id'   =>  $id,
                            ]);
                        }
                    }
                }
            }
            if ($request['allergies_notes']) {
                $old_allergies_notes   =   CustomerAllergy::where('customer_id',$id)->forceDelete();
                if($request['allergies_notes'][0] != null){
                    $allergies_notes = $request['allergies_notes'];
                    foreach($allergies_notes as $an){
                        if(isset($an)){
                            $allergies_notes_create = CustomerAllergy::create([
                                'notes'         => $an,
                                'customer_id'   => $id,
                            ]);
                        }
                    }
                }
            }
             if ($request['doctor_notes']) {
                    $date               =   Carbon::now()->format('m/d/Y');
                    $doctor_id          =   Auth::user()->doctor_id;
                    $doctor_name        =   Doctor::where('id',$doctor_id)->select('name')->first();
                    $doctor_notes       =   $request->doctor_notes;
                    $notes              =   $doctor_name->name.' On '.$date.'  '.$doctor_notes;
                    $insert             =   CustomerDoctorNotes::create(['notes' => $notes, 'customer_id' => $id]);
            }
            $clients        = Customer::where('doctor_id',$doctor_id)->get();
            session()->flash('success', 'Customer Updated Successfully');
            return redirect()->route('doctorclients.index');
        }else{
            abort(403);
        }
    }
    public function addCustomerAllergiesNotes(Request $request, $customer_id){
        // dd($request->all());
            $allergies_notes = $request->allergies_notes;
            // $customer_allergy_delete =  CustomerAllergy::where('customer_id',$customer_id)->forcedelete();
            if(count($allergies_notes)>0 ){
                foreach($allergies_notes as $notes){
                    if(is_array($notes)){
                        $notes_data[] = implode(" ",$notes);
                        // $notes_data[]  = isset($notes->value)? $notes['value'] : $notes;
                        // foreach($notes as $note){
                        //     $notes_data[] =$note;  
                        // }
                    }else{
                        $notes_data[] =$notes;     
                    }
                }
                if(isset($notes_data[0])){
                    
                    foreach($notes_data as $insert_notes){
                        if(isset($insert_notes) && $insert_notes != null){
                            $insert = CustomerAllergy::create([
                                "customer_id"   => $customer_id,
                                "notes"         =>$insert_notes
                            ]);
                        }
                    }
                    $customer_allergies = CustomerAllergy::where('customer_id',$customer_id)->select('notes')->get();
                    return redirect()->back();    

                }else{

                    return response()->back();    
                }
            }
    }
    public function addCustomerRiskFactorNotes(Request $request,$customer_id){
            $riskfactor_notes = $request->riskfactor_notes;
            // $customer_riskfactor_delete =  CustomerRiskFactor::where('customer_id',$customer_id)->forcedelete();
            if(count($riskfactor_notes)>0 ){
                foreach($riskfactor_notes as $notes){
                    if(is_array($notes)){
                        $notes_data[] = implode(" ",$notes);
                        // $notes_data[]  = isset($notes->value)? $notes['value'] : $notes;
                        // foreach($notes as $note){
                        //     $notes_data[] =$note;  
                        // }
                    }else{
                        $notes_data[] =$notes;     
                    }
                }
                if(isset($notes_data[0])){
                    
                    foreach($notes_data as $insert_notes){
                        if(isset($insert_notes) && $insert_notes != null){
                            $insert = CustomerRiskFactor::create([
                                "customer_id"   => $customer_id,
                                "notes"         =>$insert_notes
                            ]);
                        }
                    }
                    $Customer_riskfactor = CustomerRiskFactor::where('customer_id',$customer_id)->select('notes')->get();
                    return redirect()->back();    

                }else{
                    return redirect()->back();    
                }
            }
    }
    public function addCustomerDoctorNotes(Request $request,$customer_id){
        $date               =   Carbon::now()->format('m/d/Y');
        $doctor_id          =   Auth::user()->doctor_id;
        $doctor_name        =   Doctor::where('id',$doctor_id)->select('name')->first();
        $doctor_notes       = $request->notes;
        $notes              = $doctor_name->name.' On '.$date.' '.$doctor_notes;
        $insert             = CustomerDoctorNotes::create(['notes' => $notes, 'customer_id' => $customer_id]);
                
        if($insert){
            return redirect()->back();    
        }else{
                return redirect()->back();    
        }
    }
    public function destroy($id)                                                                // Doctor can Delete any customer from has list
    {
      $doctor_id  = Auth::user()->doctor_id;
      $customer   = Customer::where('id', $id)->where('doctor_id',$doctor_id)->first();
      if($customer){
        $update_customer =  $customer->update([
          'doctor_id'    => null,
        ]);
      }
        session()->flash('success','Customer Deleted Successfully');
        return redirect()->back();
    }
    public function createNewAppointment(Request $request, $id){                      // Create new appointment through customer show
        $procedure_id       = $request->procedure_id;
        $doctor_id          = Auth::user()->doctor_id;
        if($procedure_id == 0){
          $treatment    = DB::table('treatments')->where('id',$procedure_id)->first();
          $procedure_id = $treatment->parent_id;
        }
        $appointment_date= Carbon::parse($request->appointment_date)->toDateTimeString();
        if ($procedure_id != null &&  $request->center_id != null &&  $request->center_id != 0 && $doctor_id != null) {
            $customer_procedure_id  = DB::table('customer_procedures')->insertGetId([
                'customer_id'       => $id,
                'treatments_id'     => $procedure_id,
                'hospital_id'       => $request->center_id,
                'doctor_id'         => $doctor_id,
                'cost'              => 0,
                'discount_per'      => 0,
                'discounted_cost'   => 0,
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
            $doctor_all     = "https://bit.ly/37BO0w5";
            $care_all       = "https://bit.ly/36OZs6t";
            //Message to Customer/Patient
            $message        = "Dear+$customerName,".$n.$n."Your+appointment+has+been+booked+with+$doctorName+at+$at.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Location:+$location".$n."Google+Map:+$map".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies. You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Now you can download our CareALL App for booking appointments and managing your health records: $care_all";
            $sms            = CustomerAppointmentSms($message, $data->customer_phone);

            //Message to Doctor
           if(isset($doctor_phone)){                       // Send message to doctor about appointment
             $message        = "Hello+$doctorName,".$n.$n."You+have+an+appointment+with+$customerName.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Center/Clinic:+$at.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Now you can download our DoctorALL App for managing your Appointments and view Patient Profile: $doctor_all";
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
        session()->flash('success', $data->name."'s Appointments is Booked Successfully");
        return redirect()->back();
    }
    session()->flash('error', "Something is missing. Select all fields to continue");
    return redirect()->back();
    }
    public function TreatmentHistory( $id)                                                      // Doctor Can view all the Histroy of Customer Treatments
    {
        $doctor_id  = Auth::user()->doctor_id;
        $customer   =  Customer::Where('id',$id)->where('doctor_id',$doctor_id)->withTrashed()->first();
        if($customer){
            return view('doctorpanel.clients.treatment_history', compact('customer'));
        }else{
            abort(403);
        }
    }
    public function appointment_history()                                                       // show all appointments of doctor with customer in doctor-panel
    {
        $doctor_id      = Auth::user()->doctor_id;

        $cp_patients    =   doctor_clients_cp($doctor_id);
        $cth_patients   =   doctor_clients_cth($doctor_id);
        $clients        = Array_merge($cp_patients, $cth_patients);
        return view('doctorpanel.clients.appointment_history', compact('clients'));
    }

    public function upcoming()                                          // function to get upcoming appointment of clinic
    {
        $endOfDay       = Carbon::now()->endOfDay()->toDateTimeString();      //today end time and date
        $doctor_id      = Auth::user()->doctor_id;
        $doctor = Doctor:: where('id',$doctor_id)->with('centers')->first();
        if ($doctor->centers) {
            foreach ($doctor->centers as $s) {
            $array[]  =  $s->id;
            }
            if (isset($array)) {
              $center_id       = array_values(array_unique($array)); //Reorder array after making it unique
            }else{
                $center_id = null;
            }
          }
        $clients        = DB::table('customers as c')
                        ->JOIN('customer_procedures as cp','cp.customer_id','c.id')
                        ->JOIN('treatments as t','cp.treatments_id','t.id')
                        ->JOIN('medical_centers as mc','cp.hospital_id','mc.id')
                        ->WHERE('cp.doctor_id',$doctor_id)
                        ->WHEREIN('cp.status',[0,2])
                        ->WHERE('appointment_date','>',$endOfDay)
                        ->select('c.*','cp.hospital_id','cp.id as customer_procedures_id','mc.center_name','cp.treatments_id as treatments_id','cp.doctor_id','cp.hospital_id','t.name as treatment_name','cp.cost as costs','cp.appointment_date')
                        // ->groupBy('cp.hospital_id')
                        ->get();
          // dd($clients);
        $message = "Upcoming Appointments";
        return view('doctorpanel.appointment.appointment', compact(['clients','message','center_id']));
    }
    public function today()                                                     // function to get today appointment of clinic
    {
        $startOfDay     = Carbon::now()->startOfDay()->toDateTimeString();      //today start time and date
        $endOfDay       = Carbon::now()->endOfDay()->toDateTimeString();        //today end time and date

        $doctor_id      =   Auth::user()->doctor_id;
        $doctor = Doctor:: where('id',$doctor_id)->with('centers')->first();
        if ($doctor->centers) {
            foreach ($doctor->centers as $s) {
            $array[]  =  $s->id;
            }
            if (isset($array)) {
              $center_id       = array_values(array_unique($array)); //Reorder array after making it unique
            }else{
                $center_id = null;
            }
          }
        $clients        = DB::table('customers as c')
                        ->JOIN('customer_procedures as cp','cp.customer_id','c.id')
                        ->JOIN('treatments as t','cp.treatments_id','t.id')
                        ->JOIN('medical_centers as mc','cp.hospital_id','mc.id')
                        ->WHERE('cp.doctor_id',$doctor_id)
                        ->WHEREIN('cp.status',[0,2])
                        ->whereBetween('appointment_date',[$startOfDay, $endOfDay])
                        ->select('c.*','cp.hospital_id','cp.id as customer_procedures_id','mc.center_name','cp.treatments_id as treatments_id','cp.doctor_id','cp.hospital_id','t.name as treatment_name','cp.cost as costs','cp.appointment_date')
                        ->get();
        $message        = "Today Appointments";
        return view('doctorpanel.appointment.appointment', compact(['clients','message','center_id']));
    }
    public function previous()                                          // function to get previous appointment of clinic
     {
        $startOfDay = Carbon::now()->startOfDay()->toDateTimeString();  //today start time and date
        $doctor_id              =   Auth::user()->doctor_id;
        $doctor = Doctor:: where('id',$doctor_id)->with('centers')->first();
        if ($doctor->centers) {
            foreach ($doctor->centers as $s) {
            $array[]  =  $s->id;
            }
            if (isset($array)) {
              $center_id       = array_values(array_unique($array)); //Reorder array after making it unique
            }else{
                $center_id = null;
            }
          }
        $clients      = DB::table('customers as c')
                        ->JOIN('customer_procedures as cp','cp.customer_id','c.id')
                        ->JOIN('treatments as t','cp.treatments_id','t.id')
                        ->JOIN('medical_centers as mc','cp.hospital_id','mc.id')
                        ->WHERE('cp.doctor_id',$doctor_id)
                        ->WHEREIN('cp.status',[0,2])
                        ->WHERE('appointment_date','<',$startOfDay)
                        ->select('c.*','cp.hospital_id','cp.id as customer_procedures_id','mc.center_name','cp.treatments_id as treatments_id','cp.doctor_id','cp.hospital_id','t.name as treatment_name','cp.cost as costs','cp.appointment_date')
                        ->orderByDesc('cp.appointment_date')
                        ->get();
        $message = "Previous Appointments";
        return view('doctorpanel.appointment.appointment', compact(['clients','message','center_id']));
    }
    public function Edit_Appointment(Request $request, $id)                                       // when doctor edit customer appointment from doctor panel;
    {
        $customer = DB::table('customer_procedures')->where('id',$request->customer_procedures_id)->first();
        if ($customer) {
            $customer_history = DB::table('customer_treatment_history')->insert([                 // insert customer treatment details in customer history table
            'customer_id'   => $customer->customer_id,
            'treatments_id' => $customer->treatments_id,
            'hospital_id'   => $customer->hospital_id,
            'doctor_id'     => $customer->doctor_id,
            'cost'          => $customer->cost,
            'appointment_date' => $customer->appointment_date,
            'appointment_from' => 3,
        ]);
        $new_customer_procedures = DB::table('customer_procedures')->insert([                      // insert new appointment date in customer procedure table
            'customer_id'   => $request->customer_id,
            'treatments_id' => $request->treatments_id,
            'hospital_id'   => $request->hospital_id,
            'doctor_id'     => $request->doctor_id,
            'cost'          => $customer->cost,
            'status'          => 2,                                                                 // when status is 2 it means treatment is ongoing, and doctor added new appointment date;
            'appointment_date' => $request->appointment_date,
            'appointment_from' => 3,
        ]);
        $customer_phone = customerPhone($request->customer_id);                                            // Get Customer phone number
        if(isset($customer_phone)){                                                                    // send message to customer
           $with           = doctorName($request->doctor_id);
           $at             = centerName($request->hospital_id);
           $customer_name  = customerName($request->customer_id);
           $location       = centerlocation($request->hospital_id);
           $map            = centerMap($request->hospital_id);
           $date           = Carbon::parse($request->appointment_date);                             // Appointment date
           $fdate          = $date->format('jS F Y');
           $time           = $date->format('h:i A');
           $n              = '\n';
           $care_all       = "https://bit.ly/36OZs6t";
           $message        = "Dear+$customer_name,".$n.$n."Your+appointment+has+been+booked+with+$with+at+$at.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Location:+$location".$n."Google+Map:+$map".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies.+You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Now you can download our CareALL App for booking appointments and managing your health records: $care_all";
           $sms            = CustomerAppointmentSms($message, $customer_phone);
       }
        $message           = "Your appointment scheduled at $date with $customer_name at $at.";
        NotificationHelper::GENERATE([
            'title' => 'Next Appointment',
            'body' => $message,
            'payload' => [
                'type' => "Next Appointment"
            ]
        ],[$request->doctor_id]);

        $check_customer_in_users = User::where('customer_id',$request->customer_id)->first();
        if($check_customer_in_users){
            $message = "Your Next Appointment with $with is on $date and at $at";
            NotificationHelper::GENERATE([
                'title' => 'Next Appointment',
                'body' => $message,
                'payload' => [
                    'type' => "Next Appointment"
                ]
            ],$check_customer_in_users->id);
        }
        $customer = DB::table('customer_procedures')->where('id',$request->customer_procedures_id)->delete(); // delete perivous customer procedure data
        }
        return redirect()->back();
    }
    public function cancelCustomerAppointment(Request $request, $id)                    // doctor can cancel appointment of customer
    {
        $customer = DB::table('customer_procedures')->where('id',$request->customer_procedures_id)->first();  // When doctor cancel customer appointment its status is 1
        $customer_updated = DB::table('customer_procedures')->where('id',$request->customer_procedures_id)->update(['status' => 1,]);  // When doctor cancel customer appointment its status is 1

        $customer_phone = customerPhone($customer->customer_id);                                            // Get Customer phone number
        if(isset($customer_phone)){                                                                    // send message to customer
            $with           = doctorName($customer->doctor_id);
            $at             = centerName($customer->hospital_id);
            $customer_name  = customerName($customer->customer_id);
            $location       = centerlocation($customer->hospital_id);
            $map            = centerMap($customer->hospital_id);
            $date           = Carbon::parse($customer->appointment_date);                             // Appointment date
            $fdate          = $date->format('jS F Y');
            $time           = $date->format('h:i A');
            $n              = '\n';
            $care_all       = "https://bit.ly/36OZs6t";
            $message        = "Dear+$customer_name,".$n.$n."Your+appointment+with+$with+at+$at+on+$fdate+at+$time+has+been+Canceled".$n.$n."We+will+Reschedule+your+appointment+as+soon+as+possible.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Now you can download our CareALL App for booking appointments and managing your health records: $care_all";
            $sms            = CustomerAppointmentSms($message, $customer_phone);
        }
        $not = Notification::create([
            'title' => 'Appointment Canceled',
            'body' => 'You Canceled appointment',
            'payload' => json_encode('Appointment Canceled'),
        ]);
         $id = User::where('doctor_id',$customer->doctor_id)->pluck('id')->first();

        if($id != null){
          $not=  $not->users()->sync($id);
        }

        return redirect()->back();
    }

    public function clientsDocumentsEdit($id)
    {
        $customer       =   Customer::where('id',$id)->first();
        $data      =   DB::table('customer_documents')
                            ->where('customer_id',$id)
                            ->orderBy('created_at','desc')
                            ->get();
        // foreach ($documents as $document) {
        //     $file_type  =   $document->file_type;
        //     if ($file_type == "image") {
        //         $data['images'][$document->id]     =   $document->slug;
        //     } elseif ($file_type == "pdf" || $file_type == "docx" || $file_type == "xlsx") {
        //         $data['files'][$document->id]     =   $document->slug;
        //     }
        // }
        // dd($documents,$data);
        return view('doctorpanel.client_documents.show', compact('customer','data'));
    }

    public function clientsDocumentsUpload(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pictures.*'        =>  'sometimes|mimes:jpeg,jpg,png',
            'files.*'           =>  'sometimes|mimes:pdf,docx,xlsx',
            'type'              =>  'required|string',
        ]);
        if($validator->fails()){
            session()->flash('error',$validator->errors());
            return redirect()->back()->withInput();
        } else{
            if ($request->file('pictures') || $request->file('files')) {
                $customer       =   Customer::where('id',$id)->first();
                $created_by     =   Auth::user()->doctor_id;
                $path           =   'backend/uploads/customer_documents/';
                $title          =   $request->title;
                $description    =   $request->description;
                $ftype          =   $request->type; //file type i.e P = Prescription
                $pictures       =   $request->file('pictures');
                $files          =   $request->file('files');
                $i              =   0;
                if ($pictures) {
                    foreach ($pictures as $picture) {
                        $file_type  =   $picture->guessExtension();
                        $type   =   null;
                        switch ($file_type) {
                        case ('jpeg' || 'jpg' || 'png'):
                            $type = "image";
                            break;
                        }
                        //name that we'll use for the coding
                        $slug       =   $customer->name.'_'.time().$i.'.'.$picture->getClientOriginalExtension();
                        //Name that is to be shown to users
                        $file_name      =   $picture->getClientOriginalName();
                        $create         =   DB::table("customer_documents")
                        ->insert([
                            'title'             =>  $title,
                            'description'       =>  $description,
                            'type'              =>  $ftype, // Type as in Prescription, Lab Reports, Radiology etc
                            'customer_id'       =>  $customer->id,
                            'file_name'         =>  $file_name,
                            'slug'              =>  $slug,
                            'created_by'        =>  $created_by,
                            'file_type'         =>  $type, //Type as in Image, Pdf or docx
                        ]);
                        $store          =   insert_customer_documents($slug,$picture,$path);
                        $i++;
                    }
                }
                if ($files) {
                    foreach ($files as $file) {
                        $file_type  =   $file->guessExtension();
                        $type   =   null;
                        switch ($file_type) {
                            case "pdf":
                                $type = "pdf";
                                break;
                            case 'docx':
                                $type = "docx";
                                break;
                            case 'xlsx':
                                $type = "xlsx";
                                break;
                            }
                        //name that we'll use for the coding
                        $slug       =   $customer->name.'_'.time().$i.'.'.$file->getClientOriginalExtension();
                        //Name that is to be shown to users
                        $file_name      =   $file->getClientOriginalName();
                        $create         =   DB::table("customer_documents")
                        ->insert([
                            'title'             =>  $title,
                            'description'       =>  $description,
                            'type'              =>  $ftype, // Type as in Prescription, Lab Reports, Radiology etc
                            'customer_id'       =>  $customer->id,
                            'file_name'         =>  $file_name,
                            'slug'              =>  $slug,
                            'created_by'        =>  $created_by,
                            'file_type'         =>  $type, //Type as in Image, Pdf or docx
                        ]);
                        $store          =   $store          =   $file->move($path,$slug);
                        $i++;
                    }
                }
                session()->flash('success','Uploaded Successfully');
                return redirect()->back();
            } else{
                session()->flash('error','Please Upload a File or Picture');
                return redirect()->back()->withInput();
            }
        }
    }
}
