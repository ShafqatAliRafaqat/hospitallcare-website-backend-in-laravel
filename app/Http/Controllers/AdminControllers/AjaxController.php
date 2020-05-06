<?php

namespace App\Http\Controllers\AdminControllers;


use App\Exports\DoctorSeoExport;
use App\Exports\ErrorEmployees;
use App\Http\Controllers\Controller;
use App\Models\Admin\Center;
use App\Models\Admin\Customer;
use App\Models\Admin\Diagnostics;
use App\Models\Admin\Doctor;
use App\Models\Admin\Procedure;
use App\Models\Admin\TempCustomer;
use App\Models\Admin\TempNotes;
use App\Models\Admin\Treatment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Excel;

class AjaxController extends Controller
{
    // public function fetchTreatments(Request $request)
    // {
    // 	// $id = $request->id;
    // 	// $procedures = Procedure::where(['treatment_id'=>$id,'is_active'=>1])
    // 	//                          ->select('procedures.id','procedures.procedure_name as procedure')
    // 	//                          ->get();
    // 	// return response()->json($procedures);
    //     $data = 'Treatments';
    //     $id = $request->id;
    //     $result = DB::table('treatments')
    //     ->where('parent_id',$id)
    //     ->where('is_active')
    //     ->select('id','name')
    //     ->get();
    //     return view('adminpanel.templates.options', compact('result','data'));
    // }
    public function fetchCentersByLocation(Request $request)
    {
      $data       = 'Center';
      $city_name  = $request->city_name;
      $result     = DB::table('medical_centers')->where('city_name',$city_name)->where('is_active',1)->orderBy('center_name','ASC')->get();
      return view('adminpanel.templates.centersByDoctor', compact('result','data'));
    }
    public function fetchCenters(Request $request)
    {
        $data = 'Center';

        $id = $request->id;
        $result = DB::table('medical_centers as mc')
        ->LEFTJOIN('center_treatments as ct','ct.med_centers_id','mc.id')
        ->LEFTJOIN('treatments as t','ct.treatments_id','t.id')
        ->WHERE('ct.treatments_id',$id)
        ->where('mc.is_active',1)
        ->select('mc.id as id','mc.center_name as name','t.id as center_treatments')
        ->orderBy('mc.center_name','ASC')
        ->get();
        return view('adminpanel.templates.options', compact('result','data'));
    }
    public function getCenterDoctorTreatments(Request $request)
    {
        $data = 'Procedure';
        $doctor_id          = $request->doctor_id;
        $center_id          = $request->center_id;
        $result             = getCenterTreatments($doctor_id, $center_id);
        // dd($result);
        return view('doctorpanel.templates.options', compact('result','data'));
    }

    public function fetchDoctors(Request $request)
    {
        $data         = 'Doctor';
        $treatment_id = $request->procedure_id;
        $center_id    = $request->center_id;
        if($center_id ==1 ){
            $result       =   DB::table('center_doctor_schedule as cds')
                                ->join('doctors as d','d.id','cds.doctor_id')
                                ->where('cds.center_id',$center_id)
                                ->where('d.is_active',1)
                                ->select('d.name as name','d.id as id')
                                ->orderBy('d.name','ASC')
                                ->get();
        }else{
            $result       =   DB::table('center_doctor_schedule as cds')
                            ->join('doctor_treatments as dt', 'cds.id', 'dt.schedule_id')
                            ->join('treatments as t', 't.id', 'dt.treatment_id')
                            ->join('doctors as d', 'd.id', 'dt.doctor_id')
                            ->join('medical_centers as mc', 'mc.id', 'cds.center_id')
                            ->where('cds.center_id', $center_id)
                            ->where('dt.treatment_id', $treatment_id)
                            ->where('d.is_active', 1)
                            ->select('t.name as treatment_name', 't.id as treatment_id', 'd.name as name', 'd.id as id', 'mc.center_name as center_name', 'mc.id as center_id')
                            ->orderBy('d.name', 'ASC')
                            ->get();
        }
        return view('adminpanel.templates.doctor_options', compact('result','data'));
    }
    //Fetches Treatment cost saved against a Medical Center For Cost 1
    public function fetchMedTreatmentCost1(Request $request)
    {
      $data         = 0;
      $treatment_id = $request->procedure_id;
      $center_id    = $request->center_id;
      $result       =   DB::table('center_treatments as ct')
                        ->join('medical_centers as mc','mc.id','ct.med_centers_id')
                        ->where('ct.med_centers_id',$center_id)
                        ->where('ct.treatments_id',$treatment_id)
                        ->first();
      $result       = $result->cost;
      return view('adminpanel.templates.cost_input1', compact('result','data'));
    }
    //Fetches Treatment cost saved against a Medical Center for Cost 2
    public function fetchMedTreatmentCost2(Request $request)
    {
      $data         = 0;
      $treatment_id = $request->procedure_id;
      $center_id    = $request->center_id;
      $result       =   DB::table('center_treatments as ct')
                        ->join('medical_centers as mc','mc.id','ct.med_centers_id')
                        ->where('ct.med_centers_id',$center_id)
                        ->where('ct.treatments_id',$treatment_id)
                        ->first();
      $result       = $result->cost;
      return view('adminpanel.templates.cost_input2', compact('result','data'));
    }
    public function fetchDocTreatmentCost(Request $request)
    {
      $data         = 0;
      $treatment_id = $request->procedure_id;
      $center_id    = $request->center_id;
      $doctor_id    = $request->doctor_id;
      if ($treatment_id != 0) {
        $result       = DB::table('center_treatments as ct')
                        ->join('medical_centers as m', 'm.id', 'ct.med_centers_id')
                        ->where('ct.med_centers_id', $center_id)
                        ->where('ct.treatments_id', $treatment_id)
                        ->first();
          $result       = $result->cost;
      }
      if($treatment_id == 0){
        $result       = DB::table('center_doctor_schedule')
                            ->where('doctor_id', $doctor_id)
                            ->first();
        $result       = $result->fare;
      }
      return view('adminpanel.templates.doc_cost_input', compact('result','data'));
    }
    public function fetchTreatments(Request $request)
    {
      $data   = 'Treatments';
      $id     = $request->id;
      $result = DB::table('treatments')
                ->where('parent_id',$id)
                ->where('is_active',1)
                ->select('id','name')
                ->orderBy('name','ASC')
                ->get();
      return view('adminpanel.templates.options', compact('result','data'));
    }
  public function fetchMultipleTreatments(Request $request)
  {
    // return response()->json(['data' => $request->specialization_ids]);
      $data   = 'Treatments';
      $id     = $request->specialization_ids;
      $result = DB::table('treatments')
                ->whereIn('parent_id',$id)
                ->where('is_active',1)
                ->select('id','name')
                ->orderBy('name','ASC')
                ->get();
      return view('adminpanel.templates.multiProcedures_options', compact('result','data'));
      // return view('adminpanel.templates.options', compact('result','data'));
  }
  public function fetchDoctorSchedule(Request $request)
    {
      $center_id      =   $request->center_id;
      $doctor_id      =   $request->doctor_id;
      $result         =   DB::table('center_doctor_schedule')->where(['doctor_id' => $doctor_id, 'center_id' => $center_id])->get();
      return view('adminpanel.templates.schedule', compact('result'));
    }
    public function fetchLabs(Request $request)
    {
      $data     = "Lab";
      $id       = $request->id;
      $result = DB::table('labs as l')
                ->LEFTJOIN('lab_diagnostics as ld','ld.lab_id','l.id')
                ->LEFTJOIN('diagnostics as d','ld.diagnostic_id','d.id')
                ->WHERE('ld.diagnostic_id',$id)
                ->WHERE('l.is_active',1)
                ->select('l.id as id','l.name as name')
                ->get();
      return view('adminpanel.templates.lab_options', compact('result','data'));
    }

    public function fetchDiagnostics(Request $request)
    {
      $data     = "Diagnostic";
      $id       = $request->id;
      $result   = DB::table('labs as l')
                  ->LEFTJOIN('lab_diagnostics as ld','ld.lab_id','l.id')
                  ->LEFTJOIN('diagnostics as d','ld.diagnostic_id','d.id')
                  ->WHERE('ld.lab_id',$id)
                  ->WHERE('d.is_active',1)
                  ->select('d.id as id','d.name as name')
                  ->orderBy('d.name','ASC')
                  ->get();
      return view('adminpanel.templates.lab_options', compact('result','data'));
    }

    public function fetchDiagnosticCost(Request $request)
    {
      $data           = 0;
      $diagnostic_id  = $request->diagnostic_id;
      $lab_id         = $request->lab_id;
      $result         =   DB::table('lab_diagnostics')
                        ->where('diagnostic_id',$diagnostic_id)
                        ->where('lab_id',$lab_id)
                        ->first();
    //   $result       = $result->cost;
      return view('adminpanel.templates.diagnostic_cost', compact('result','data'));
    }

    public function fetchDiagnosticCost2(Request $request)
    {
      $data           = 0;
      $diagnostic_id  = $request->diagnostic_id;
      $lab_id         = $request->lab_id;
      $result         =   DB::table('lab_diagnostics')
                        ->where('diagnostic_id',$diagnostic_id)
                        ->where('lab_id',$lab_id)
                        ->first();
      $result       = $result->cost;
      return view('adminpanel.templates.diagnostic_cost2', compact('result','data'));
    }
    public function fetchOrganizations(Request $request)
    {
        $data = 'Organizations';
        $id = $request->id;
        $result = DB::table('organizations')
        ->where('id',$id)
        ->select('id','name')
        ->get();
        return view('adminpanel.templates.options', compact('result','data'));
    }

    public function fetchCenterTreatments(Request $request){
      $data         =   'Treatments';
      $id           =   $request->id;
      $treatments   =   Center::where('id',$id)->with('center_treatment')->first();
      $result       =   $treatments->center_treatment;
      return view('adminpanel.templates.doctor_options', compact('result','data'));
    }

    public function importIndex()
    {
        return view('adminpanel.import.index');
    }

    public function unique_code($limit)
    {
      return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    public function importCustomers()
    {
        $array_procedure = [];
        $validate = request()->validate([
            'file' => 'required'
        ]);

        $ok = true;
        $first_treatment = false;
        $second_treatment = false;
        $third_treatment = false;
        $file = request()->file('file');
        $handle = fopen($file, "r");
        if ($file == NULL) {
            session()->flash('error', 'File is empty');
            return redirect()->back();
        }
        else {
            $entry = 1;
            $data = [];
            $row = 1;
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
                if($row == 1){ $row++; continue; }

                 $customer_name             = $filesop[0];
                 $email                     = $filesop[1];
                 $phone_str                  = $filesop[2];
                   if($phone_str[0] == '3' || $phone_str[0] == '4'){
                      $phone      =   '0'.$filesop[2];
                   } else if($phone_str[0] == 9 && $phone_str[1] == 2){
                      $str2 = substr($phone_str, 2);
                      $phone      =   '0'.$str2;
                   } else if($phone_str[0] == '+' && $phone_str[1] == '9' && $phone_str[2] == '2'){
                      $str2 = substr($phone_str, 3);
                      $phone      =   '0'.$str2;
                   } else {
                      $phone      =   $filesop[2];
                   }
                 $address                   = $filesop[3];
                  if ($address == NULL) {
                     $address = NULL;
                  }
                 $gender                    = $filesop[4];
                 if ($gender == NULL) {
                     $gender = 0;
                 }
                 $marital_status            = $filesop[5];
                 if ($marital_status == NULL) {
                     $marital_status = 0;
                 }
                 $age                       = $filesop[6];
                 if ($age == NULL) {
                     $age = NULL;
                 }
                 $weight                    = $filesop[7];
                if ($weight == NULL) {
                     $weight = NULL;
                 }
                 $height                    = $filesop[8];
                 if ($height == NULL) {
                     $height = NULL;
                 }
                 $notes                     = $filesop[9];
                 if ($notes == NULL) {
                  $notes = '<p>What:</p>';
                 } else if ($notes != NULL){
                     $notes = '<p>What:</p>'.'<p><strong>Previous Notes</strong></p>'.$filesop[9];
                 }
                 $patient_coordinator_id    = $filesop[10];
                 $next                      = $filesop[11];
                 $status                    = $filesop[12];
                 $procedure1                = $filesop[13];
                 $center1                   = $filesop[14];
                 $doctor1                   = $filesop[15];
                 $procedure2                = $filesop[16];
                 $center2                   = $filesop[17];
                 $doctor2                   = $filesop[18];
                 $procedure3                = $filesop[19];
                 $center3                   = $filesop[20];
                 $doctor3                   = $filesop[21];

                // Finding/Checking Procedure 1 in Database
                if($first_treatment = ($procedure1 != NULL && $center1 != NULL && $doctor1 != NULL)){
                $procedure_fetch1 = DB::table('treatments')
                        ->where('name',$procedure1)
                        ->select('id')
                        ->first();
                if($procedure_fetch1){
                $pd1 = $procedure_fetch1->id;
                } else{
                    return redirect()->route('customers.index')->with('error', 'PROCEDURE 1 name is not in the Database. Error at the Entry No. '.$entry);
                }
                $center_fetch1 = DB::table('medical_centers')
                    ->where('center_name',$center1)
                    ->select('id')
                    ->first();
                if($center_fetch1){
                    $center_id1 = $center_fetch1->id;
                } else{
                    return redirect()->route('customers.index')->with('error', 'CENTER 1 name is not in the Database. Error at the Entry No. '.$entry);
                    }
                $doctor_fetch1 = DB::table('doctors')
                    ->where('name',$doctor1)
                    ->select('id')
                    ->first();
                if($doctor_fetch1){
                    $doctor_id1 = $doctor_fetch1->id;
                } else{
                    return redirect()->route('customers.index')->with('error', 'DOCTOR 1 name is not in the Database. Error at the Entry No. '.$entry);
                }
                }

                // Finding/Checking Procedure 2 in Database
                if($second_treatment = ($procedure2 != NULL && $center2 != NULL && $doctor2 != NULL)){
                 $procedure_fetch2 = DB::table('treatments')
                    ->where('name',$procedure2)
                    ->select('id')
                    ->first();
                if($procedure_fetch2){
                $pd2 = $procedure_fetch2->id;
                } else{
                    return redirect()->route('customers.index')->with('error', 'PROCEDURE 2 name is not in the Database. Error at the Entry No. '.$entry);
                }

                $center_fetch2 = DB::table('medical_centers')
                    ->where('center_name',$center2)
                    ->select('id')
                    ->first();
                if($center_fetch2){
                    $center_id2 = $center_fetch2->id;
                } else{
                    return redirect()->route('customers.index')->with('error', 'CENTER 2 name is not in the Database. Error at the Entry No. '.$entry);
                    }
                $doctor_fetch2 = DB::table('doctors')
                    ->where('name',$doctor2)
                    ->select('id')
                    ->first();
                if($doctor_fetch2){
                    $doctor_id2 = $doctor_fetch2->id;
                } else{
                    return redirect()->route('customers.index')->with('error', 'DOCTOR 2 name is not in the Database. Error at the Entry No. '.$entry);
                }
                }

                // Finding/Checking Procedure 3 in Database
                if($third_treatment = ($procedure3 != NULL && $center3 != NULL && $doctor3 != NULL)){
                 $procedure_fetch3 = DB::table('treatments')
                    ->where('name',$procedure3)
                    ->select('id')
                    ->first();
                if($procedure_fetch3){
                $pd3 = $procedure_fetch3->id;
                } else{
                    return redirect()->route('customers.index')->with('error', 'PROCEDURE 3 name is not in the Database. Error at the Entry No. '.$entry);
                }

                $center_fetch3 = DB::table('medical_centers')
                    ->where('center_name',$center3)
                    ->select('id')
                    ->first();
                if($center_fetch3){
                    $center_id3 = $center_fetch3->id;
                } else{
                    return redirect()->route('customers.index')->with('error', 'CENTER 3 name is not in the Database. Error at the Entry No. '.$entry);
                    }
                $doctor_fetch3 = DB::table('doctors')
                    ->where('name',$doctor3)
                    ->select('id')
                    ->first();
                if($doctor_fetch3){
                    $doctor_id3 = $doctor_fetch3->id;
                } else{
                    return redirect()->route('customers.index')->with('error', 'DOCTOR 3 name is not in the Database. Error at the Entry No. '.$entry);
                }
                }

                $status_fetch = DB::table('status')
                    ->where('name',$status)
                    ->select('id')
                    ->first();
                if($status_fetch){
                    $status_id = $status_fetch->id;
                } else{
                    session()->flash('success', 'STATUS name is not in the Database. Error at the Entry No. '.$entry);
                    return redirect()->route('customers.index');
                }

                $insert = DB::table('customers')->insertGetId([
                      'ref'                     => $this->unique_code(4),
                      'name'                    => $customer_name,
                      'email'                   => $email,
                      'phone'                   => $phone,
                      'address'                 => $address,
                      'gender'                  => $gender,
                      'marital_status'          => $marital_status,
                      'age'                     => $age,
                      'weight'                  => $weight,
                      'height'                  => $height,
                      'notes'                   => $notes,
                      'status_id'               => $status_id,
                      'next_contact_date'       => date('Y-m-d',strtotime($next)),
                      'patient_coordinator_id'  => $patient_coordinator_id,
                      'created_at'              => Carbon::now()->toDateTimeString(),
                      'updated_at'              => Carbon::now()->toDateTimeString(),

                ]);

                    if ($first_treatment && $insert) {
                        $add_customer_pro1 = DB::table('customer_procedures')
                        ->INSERT(['customer_id' => $insert,'treatments_id' => $pd1,'hospital_id' => $center_id1,'doctor_id' => $doctor_id1]);
                    }

                    if ($second_treatment && $insert) {
                        $add_customer_pro2 = DB::table('customer_procedures')
                        ->INSERT(['customer_id' => $insert,'treatments_id' => $pd2,'hospital_id' => $center_id2,'doctor_id' => $doctor_id2]);
                    }

                    if ($third_treatment && $insert) {
                        $add_customer_pro3 = DB::table('customer_procedures')
                        ->INSERT(['customer_id' => $insert,'treatments_id' => $pd3,'hospital_id' => $center_id3,'doctor_id' => $doctor_id3]);
                    }
                    $entry++;
                }
            session()->flash('success', 'Excel Sheet Uploaded Successfully');
            return redirect()->route('customers.index');
            }
    }

    public function importEmployees()
    {
        $array_procedure    =   [];
        $array_phones       =   [];
        $validate = request()->validate([
            'file' => 'required'
        ]);

        $ok = true;
        $file = request()->file('file');
        $handle = fopen($file, "r");
        if ($file == NULL) {
            session()->flash('error', 'File is empty');
            return redirect()->back();
        }
        else {
            $entry = 1;
            $data = [];
            $row = 1;
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
                if($row == 1){ $row++; continue; }

                 $employee_name                 = $filesop[0];
                 $employee_code                 = $filesop[1];
                 $email                         = $filesop[2];
                  if ($email == NULL) {
                     $email = NULL;
                 }
                 $phone_str                         = $filesop[3];
                 if($phone_str[0] == '3' || $phone_str[0] == '4'){
                    $phone      =   '0'.$filesop[3];
                 } else if($phone_str[0] == 9 && $phone_str[1] == 2){
                    $str2 = substr($phone_str, 2);
                    $phone      =   '0'.$str2;
                 } else if($phone_str[0] == '+' && $phone_str[1] == '9' && $phone_str[2] == '2'){
                    $str2 = substr($phone_str, 3);
                    $phone      =   '0'.$str2;
                 } else {
                    $phone      =   $filesop[3];
                 }
                 $organization                  = Auth::user()->organization_id;
                 $address                       = $filesop[4];
                 if ($address == NULL) {
                     $address = NULL;
                 }
                 $gender                        = $filesop[5];
                 if ($gender == NULL) {
                     $gender = 0;
                 }
                 $marital_status                = $filesop[6];
                    if ($marital_status == NULL) {
                     $marital_status = 0;
                 }
                 $age                           = $filesop[7];
                    if ($age == NULL) {
                     $age = NULL;
                 }
                 $weight                        = $filesop[8];
                    if ($weight == NULL) {
                     $weight = NULL;
                 }
                 $height                        = $filesop[9];
                  if ($height == NULL) {
                     $height = NULL;
                 }
                 $notes                         = '<p>What: </p><p>When: </p><p>Where: </p><p>Budget: </p><p>Why: </p>';
                 $patient_coordinator_id        = Auth::user()->id;
                 $status                        = 5;

                $phone_check       =   Customer::where('phone',$phone)->get();
                 if (count($phone_check) > 0) {
                    // $array_phones[] =   ['name' => $employee_name,'employee_code' => $employee_code,'email' => $email,'phone' => $phone,'address' =>$address,'gender' => $gender,'marital_status' =>   $marital_status, 'age' => $age,'weight' => $weight,'height' => $height];
                    $matchThese         =   ['phone' => $phone, 'employee_code' => $employee_code];
                    $check_temp         =   TempCustomer::where($matchThese)->get();
                    if (count($check_temp) == 0) {
                    $insert = DB::table('temp_customers')->insertGetId([
                      'name'                    => $employee_name,
                      'employee_code'           => $employee_code,
                      'email'                   => $email,
                      'phone'                   => $phone,
                      'address'                 => $address,
                      'gender'                  => $gender,
                      'marital_status'          => $marital_status,
                      'age'                     => $age,
                      'weight'                  => $weight,
                      'height'                  => $height,
                      'organization_id'         =>$organization,
                      'created_at'              => date('Y-m-d'),
                      'updated_at'              => date('Y-m-d'),

                ]);
                    }

                 } else{
                $insert = DB::table('customers')->insertGetId([
                      'ref'                     => $this->unique_code(4),
                      'name'                    => $employee_name,
                      'employee_code'           => $employee_code,
                      'email'                   => $email,
                      'phone'                   => $phone,
                      'address'                 => $address,
                      'gender'                  => $gender,
                      'marital_status'          => $marital_status,
                      'age'                     => $age,
                      'weight'                  => $weight,
                      'height'                  => $height,
                      'notes'                   => $notes,
                      'status_id'               => $status,
                      'next_contact_date'       => NULL,
                      'organization_id'         =>$organization,
                      'patient_coordinator_id'  => $patient_coordinator_id,
                      'created_at'              => Carbon::now()->toDateTimeString(),
                      'updated_at'              => Carbon::now()->toDateTimeString(),

                ]);
                    $entry++;
                }
            }

            if(count($array_phones) > 0){
                // ErrorEmployeeExport($array_phones);
                session()->flash('success', 'Could not Upload Few of the Following');
                return redirect()->route('notuploaded.index');
            }
            session()->flash('success', 'Excel Sheet Uploaded Successfully');
            return redirect()->route('employees.index');
            }
    }

    public function importPendingIndex()
    {
      return view('adminpanel.import.import_pending');
    }

    public function importPendingLeads()
    {
        $array_procedure    =   [];
        $array_phones       =   [];
        $validate = request()->validate([
            'file' => 'required'
        ]);

        $ok = true;
        $file = request()->file('file');
        $handle = fopen($file, "r");
        if ($file == NULL) {
            session()->flash('error', 'File is empty');
            return redirect()->back();
        }
        else {
            $entry = 1;
            $data = [];
            $row = 1;
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
                if($row == 1){ $row++; continue; }
                 $customer_name                 = $filesop[0];
                 $email                         = $filesop[1];
                 $phone_str                     = $filesop[2];
                 if($phone_str[0] == '3' || $phone_str[0] == '4'){
                    $phone      =   '0'.$filesop[2];
                 } else if($phone_str[0] == 9 && $phone_str[1] == 2){
                    $str2 = substr($phone_str, 2);
                    $phone      =   '0'.$str2;
                 } else if($phone_str[0] == '+' && $phone_str[1] == '9' && $phone_str[2] == '2'){
                    $str2 = substr($phone_str, 3);
                    $phone      =   '0'.$str2;
                 } else {
                    $phone      =   $filesop[2];
                 }
                 $treatment                     = $filesop[3];
                 $gender                        = $filesop[4];
                 if ($gender == NULL) {
                     $gender = 0;
                 }
                 $marital_status                = $filesop[5];
                    if ($marital_status == NULL) {
                     $marital_status = 0;
                 }
                 $age                           = $filesop[6];
                    if ($age == NULL) {
                     $age = NULL;
                 }
                 $weight                        = $filesop[7];
                    if ($weight == NULL) {
                     $weight = NULL;
                 }
                 $height                        = $filesop[8];
                  if ($height == NULL) {
                     $height = NULL;
                 }
                 $notes                         = $filesop[9];

                $insert = DB::table('temp_customers')->insertGetId([
                      'name'                    => $customer_name,
                      'email'                   => $email,
                      'phone'                   => $phone,
                      'treatment'               => $treatment,
                      'gender'                  => $gender,
                      'marital_status'          => $marital_status,
                      'age'                     => $age,
                      'weight'                  => $weight,
                      'height'                  => $height,
                      'lead_from'               => 0,
                      'created_at'              => Carbon::now()->toDateTimeString(),
                      'updated_at'              => Carbon::now()->toDateTimeString(),

                ]);
                  if (isset($notes)) {
                    $customer_notes = TempNotes::insert([
                      'customer_id' =>  $insert,
                      'notes'       =>  $notes,
                    ]);

                  }
                    $entry++;
                }
            }

            // if(count($array_phones) > 0){
            //     // ErrorEmployeeExport($array_phones);
            //     session()->flash('success', 'Could not Upload Few of the Following');
            //     return redirect()->route('notuploaded.index');
            // }
            session()->flash('success', 'Excel Sheet Uploaded Successfully');
            return redirect()->route('temp_leads');
    }

    public function importEmployeeIndex()
    {
        return view('orgpanel.import.index');
    }

    function treatmentLiveSearch(Request $request)
    {
      $treatment_name   = $request->treatment_name;
      $result           = Treatment::whereNULL('parent_id')->where('name','LIKE','%'.$treatment_name.'%')->get();
      return view('adminpanel.templates.ajax_list_return', compact('result'));
    }
    function procedureLiveSearch(Request $request)
    {
      $procedure_name   = $request->procedure_name;
      $result           = Treatment::whereNotNULL('parent_id')->where('name','LIKE','%'.$procedure_name.'%')->get();
      return view('adminpanel.templates.ajax_list_return', compact('result'));
    }
    public function diagnosticLiveSearch(Request $request)
    {
      $diagnostic_name    = $request->diagnostic_name;
      $result             = Diagnostics::where('name','LIKE','%'.$diagnostic_name.'%')->get();
      return view('adminpanel.templates.ajax_list_return', compact('result'));
    }
    public function patientCoordinatorPerformance(Request $request){
        if(!($request->start_date) && !($request->end_date)){
            $start_date = Carbon::now()->startOfDay()->toDateTimeString();                                  // Date and time of start of today
            $end_date = Carbon::now()->startOfDay()->addDays(1)->toDateTimeString();                        // Date and time of start of next day
        }else{
            $start_date = $request->start_date;
            $end_date   = $request->end_date;
        }
        $owner_status   = DB::table('coordinator_performance as cp')
                            ->Join('users as u','cp.owner_id','u.id')
                            ->WhereBetween('cp.created',[$start_date,$end_date])
                            ->orWhereBetween('cp.updated',[$start_date,$end_date])
                            ->groupby('cp.owner_id')
                            ->selectRaw("u.name,count('cp.*') as count, count(cp.created) as total_created,count(cp.updated) as total_updated")
                            ->get();
        $owner_name         = $owner_status->pluck('name');
        $owner_total_updated= $owner_status->pluck('total_updated');
        $owner_total_created= $owner_status->pluck('total_created');

        return  compact('owner_name','owner_total_updated','owner_total_created','sum_of_owners');
    }
  public function seoDoctor()
  {
    return view('adminpanel.import.seo_doctor');
  }
  public function importDoctorSeo()
  {
    $array_phones       =   [];
    $request            =   [];
    $validate = request()->validate([
        'file' => 'required'
    ]);
    $ok = true;
    $file = request()->file('file');
    if($file->getClientOriginalExtension() != "csv"){
      session()->flash('error','Please Upload a CSV file only!');
      return redirect()->back();
    }
    $handle = fopen($file, "r");
    if ($file == NULL) {
        session()->flash('error', 'File is empty');
        return redirect()->back();
    }
    else {
        $entry = 1;
        $data = [];
        $row = 1;
        while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
            if($row == 1){ $row++; continue; }
            $doctor_find        = null;
            $doctor_id          = $filesop[0];
            $doctor_find        = Doctor::where('id',$doctor_id)->first();
            if ($doctor_find) {
              $name               = nullCheck($filesop[1],$doctor_find->name);
              $meta_title         = nullCheck($filesop[2],$doctor_find->meta_title);
              $meta_description   = nullCheck($filesop[3],$doctor_find->meta_description);
              $url                = nullCheck($filesop[4],$doctor_find->url);
              $meta_heading       = nullCheck($filesop[5],$doctor_find->meta_heading);
              $image_alt          = nullCheck($filesop[6],$doctor_find->image_alt);
              $update = DB::table('doctors')->where('id',$doctor_id)->update([
                    'name'                =>  $name,
                    'meta_title'          =>  $meta_title,
                    'meta_description'    =>  $meta_description,
                    'url'                 =>  $url,
                    'meta_heading'        =>  $meta_heading,
                    'image_alt'           =>  $image_alt,
              ]);
              $entry++;
            } else {
              //Array of IDs that are not in our Database
              $request[]      = $doctor_id;
            }
        }
        if(count($request) > 0 ){
          //Getting the unknown IDs Exported
            $now        =   Carbon::now()->toDateString();
            $exporter   =   app()->makeWith(DoctorSeoExport::class, compact('request'));
            return $exporter->download('DoctorSeoExport'.$now.'.xlsx');
        }
    }
    session()->flash('success', 'Excel Sheet Uploaded Successfully');
    return redirect()->back();
  }
  public function seoTreatment()
  {
    return view('adminpanel.import.seo_treatment');
  }
  public function importTreatmentSeo()
  {
    $array_phones       =   [];
    $request            =   [];
    $validate = request()->validate([
        'file' => 'required'
    ]);
    $ok = true;
    $file = request()->file('file');
    if($file->getClientOriginalExtension() != "csv"){
      session()->flash('error','Please Upload a CSV file only!');
      return redirect()->back();
    }
    $handle = fopen($file, "r");
    if ($file == NULL) {
        session()->flash('error', 'File is empty');
        return redirect()->back();
    }
    else {
        $entry = 1;
        $data = [];
        $row = 1;
        while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
            if($row == 1){ $row++; continue; }
            $treatment_find     = null;
            $treatment_id       = $filesop[0];
            $treatment_find     = Treatment::where('id',$treatment_id)->first();
            if ($treatment_find) {
              $name               = nullCheck($filesop[1],$treatment_find->name);
              $meta_title         = nullCheck($filesop[2],$treatment_find->meta_title);
              $meta_description   = nullCheck($filesop[3],$treatment_find->meta_description);
              $url                = nullCheck($filesop[4],$treatment_find->url);
              $meta_heading       = nullCheck($filesop[5],$treatment_find->meta_heading);
              $image_alt          = nullCheck($filesop[6],$treatment_find->image_alt);
              $update = DB::table('treatments')->where('id',$treatment_id)->update([
                    'name'                =>  $name,
                    'meta_title'          =>  $meta_title,
                    'meta_description'    =>  $meta_description,
                    'url'                 =>  $url,
                    'meta_heading'        =>  $meta_heading,
                    'image_alt'           =>  $image_alt,
              ]);
              $entry++;
            } else {
              //Array of IDs that are not in our Database
              $request[]      = $treatment_id;
            }
        }
        if(count($request) > 0 ){
          //Getting the unknown IDs Exported
            $now        =   Carbon::now()->toDateString();
            $exporter   =   app()->makeWith(DoctorSeoExport::class, compact('request'));
            return $exporter->download('SeoExport'.$now.'.csv');
        }
    }
    session()->flash('success', 'Excel Sheet Uploaded Successfully');
    return redirect()->back();
  }
  public function seoCenter()
  {
    return view('adminpanel.import.seo_center');
  }
  public function importCenterSeo()
  {
    $array_phones       =   [];
    $request            =   [];
    $validate = request()->validate([
        'file' => 'required'
    ]);
    $ok = true;
    $file = request()->file('file');
    if($file->getClientOriginalExtension() != "csv"){
      session()->flash('error','Please Upload a CSV file only!');
      return redirect()->back();
    }
    $handle = fopen($file, "r");
    if ($file == NULL) {
        session()->flash('error', 'File is empty');
        return redirect()->back();
    }
    else {
        $entry = 1;
        $data = [];
        $row = 1;
        while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
            if($row == 1){ $row++; continue; }
            $center_find        = null;
            $center_id          = $filesop[0];
            $center_find        = Center::where('id',$center_id)->first();
            if ($center_find) {
              $center_name        = nullCheck($filesop[1],$center_find->center_name);
              $meta_title         = nullCheck($filesop[2],$center_find->meta_title);
              $meta_description   = nullCheck($filesop[3],$center_find->meta_description);
              $url                = nullCheck($filesop[4],$center_find->url);
              $meta_heading       = nullCheck($filesop[5],$center_find->meta_heading);
              $image_alt          = nullCheck($filesop[6],$center_find->image_alt);
              $update = DB::table('medical_centers')->where('id',$center_id)->update([
                    'center_name'         =>  $center_name,
                    'meta_title'          =>  $meta_title,
                    'meta_description'    =>  $meta_description,
                    'url'                 =>  $url,
                    'meta_heading'        =>  $meta_heading,
                    'image_alt'           =>  $image_alt,
              ]);
              $entry++;
            } else {
              //Array of IDs that are not in our Database
              $request[]      = $center_id;
            }
        }
        if(count($request) > 0 ){
          //Getting the unknown IDs Exported
            $now        =   Carbon::now()->toDateString();
            $exporter   =   app()->makeWith(DoctorSeoExport::class, compact('request'));
            return $exporter->download('SeoExport'.$now.'.csv');
        }
    }
    session()->flash('success', 'Excel Sheet Uploaded Successfully');
    return redirect()->back();
  }
}
