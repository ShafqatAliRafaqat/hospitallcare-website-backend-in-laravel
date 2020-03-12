<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\CustomerDoctorNotes;
use App\Models\Admin\Customer;
use App\Models\Admin\CustomerAllergy;
use App\Models\Admin\CoodinatorPerformance;
use App\Models\Admin\CustomerRiskFactor;
use App\Helpers\NotificationHelper;
use App\Models\Admin\Lab;
use App\User;

class CustomerServices extends Service
{
    public function unique_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
    public function validate($input,$id)
    {
        $rules = [
            'card_id'               => 'sometimes',
            'name'                  => 'required|min:3',
            'email'                 => 'sometimes',
            'address'               => 'sometimes',
            'gender'                => 'required',
            'marital_status'        => 'required',
            'age'                   => 'sometimes',
            'weight'                => 'sometimes',
            'height'                => 'sometimes',
            'blood_group_id'        => 'sometimes',
            'notes'                 => 'required',
            'doctor_notes'          => 'sometimes',
            'status_id'             => 'required|exists:status,id',
            'next_contact_date'     => 'required',
            'patient_coordinator_id' => 'sometimes',
        ];
        $this->validateOrAbort($input, $rules);
    }
    public function treatmentValidate($input)
    {
        $rules = [
            'procedure_id'      => 'required',
            'hospital_id'       => 'required',
            'doctor_id'         => 'required',
            'cost'              => 'required',
            'treatment_discount'=> 'sometimes',
            'discounted_tcost'  => 'sometimes',
            'appointment_date'  => 'sometimes',
        ];
        $this->validateOrAbort($input, $rules);
    }
    public function diagnosticValidate1($input)
    {
        $rules = [
            'diagnostic_id1'                  => 'required',
            'lab_id1'                         => 'required',
            'diagnostics_cost'                => 'required',
            'diagnostic_appointment_date1'    => 'sometimes',
            'discount1'                       => 'sometimes',
        ];
        $this->validateOrAbort($input, $rules);
    }
    public function diagnosticValidate2($input)
    {
        $rules = [
            'diagnostic_id2'                 => 'required',
            'lab_id2'                        => 'required',
            'diagnostics_cost2'              => 'required',
            'diagnostic_appointment_date2'   => 'sometimes',
            'discount2'                      => 'sometimes',
          ];
        $this->validateOrAbort($input, $rules);
    }
    public function returnBackConditions($data){
          if($data['organization_id']){                    // if Orgnization is selected the they should enter Employee code
            $employee_code = $data['employee_code'];
            if ($employee_code ==null) {
                $message = 'Enter Employee Code';
                return $message;
            }
          }
          if ($data['treatment_id'][0]!=NULL) {                                            // validation to insert treatment
          $count_treatments     = count($data['procedure_id']);
          for ($i=0; $i < $count_treatments; $i++) {
              if ($data['procedure_id'][$i]==null ) {
                $message = 'Please Select all Procedure fields';
                return $message;
                }
              if ($data['hospital_id'][$i]==null || $data['hospital_id'][$i]==0) {
                $message = 'Please Select all Center fields';
                return $message;
              }
              if ($data['doctor_id'][$i] == null || $data['doctor_id'][$i] == 0) {
                $message = 'Please Select all Doctor fields';
                return $message;
                }
          }
          }
    }
    public function create($data){

        $this->validate($data,null);
        $returnBackConditions = $this->returnBackConditions($data);
        if($returnBackConditions != null){
            return ['error',$returnBackConditions];
        }
        $data['patient_coordinator_id'] = Auth::user()->id;
        $customer = Customer::create($this->getSecureInput($data));
        $customer_id     = $customer->id;
        if ($customer) {
            $created = Carbon::now()->toDateTimeString();
            $updated =null;
            $coordinator_performance = CoodinatorPerformance::create($this->getSecureInputCoodinatorPerformance($data,$customer_id,$created,$updated));
        }
        // if ($data['doctor_notes']) {
        //     $doctor_notes = CustomerDoctorNotes::create($this->getSecureInputCustomerDoctorNotes($data,$customer_id));
        // }
        if ($data['riskfactor_notes']) {
            $old_riskfactor_notes   =   CustomerRiskFactor::where('customer_id',$customer_id)->forceDelete();
            if($data['riskfactor_notes'][0] != null){
                $riskfactor_notes = $data['riskfactor_notes'];
                foreach($riskfactor_notes as $rn){
                    if(isset($rn)){
                        $riskfactor_notes_create = CustomerRiskFactor::create($this->getSecureInputCustomerRiskFactor($rn,$customer_id));
                    }
                }
            }
        }
        if ($data['allergies_notes']) {
            $old_allergies_notes   =   CustomerAllergy::where('customer_id',$customer_id)->forceDelete();
            if($data['allergies_notes'][0] != null){
                $allergies_notes = $data['allergies_notes'];
                foreach($allergies_notes as $an){
                    if(isset($an)){
                        $allergies_notes_create = CustomerAllergy::create($this->getSecureInputCustomerAllergy($an,$customer_id));
                    }
                }
            }
        }
        // insert treatment in customer procedure table
        if ($data['procedure_id'][0] != null && $data['hospital_id'][0] != null && $data['hospital_id'][0] != 0 && $data['doctor_id'][0] != null && $data['doctor_id'][0] != 0) {
            $arr_count = count($data['procedure_id']);
            for ($i = 0; $i < $arr_count; $i++) {
                $this->treatmentValidate($data);
                $this->treatment($data,$i,$customer_id);
                if(isset($data['appointment_date'][$i])){
                    $with           = $customer->name;
                    $at             = centerName($data['hospital_id'][$i]);
                    $date           = Carbon::parse($data['appointment_date'][$i]);                             // Appointment date
                    $date           = $date->format('Y-m-d h:i A');
                    $message        = "Your new appointment is scheduled at $date with $with at $at";
                    $check_doctor_in_users = User::where('doctor_id',$data['doctor_id'][$i])->first();
                    if($check_doctor_in_users){
                        NotificationHelper::GENERATE([
                            'title' => 'New Appointment',
                            'body' => $message,
                            'payload' => [
                                'type' => "New appointment"
                            ]
                        ],[$data['doctor_id'][$i]]);
                    }
                    $check_customer_in_users = User::where('customer_id',$customer_id)->first();
                    if(isset($check_customer_in_users)){
                        NotificationHelper::GENERATE([
                            'title' => 'New Appointment',
                            'body' => $message,
                            'payload' => [
                                'type' => "New appointment"
                            ]
                        ],$check_customer_in_users->id);
                    }
                    if(isset($customer->phone)){                                                                    // send message to customer
                        $with           = doctorName($data['doctor_id'][$i]);
                        $at             = centerName($data['hospital_id'][$i]);
                        $location       = centerlocation($data['hospital_id'][$i]);
                        $map            = centerMap($data['hospital_id'][$i]);
                        $date           = Carbon::parse($data['appointment_date'][$i]);                             // Appointment date
                        $fdate          = $date->format('jS F Y');
                        $time           = $date->format('h:i A');
                        $n              = '\n';
                        $message        = "Dear+$customer->name,".$n.$n."Your+appointment+has+been+booked+with+$with+at+$at.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Location:+$location".$n."Google+Map:+$map".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies. You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Now you can download our CareALL App for booking appointments and managing your health records: https://bit.ly/2GBo2Nm";
                        $sms            = CustomerAppointmentSms($message, $customer->phone);
                    }
                    $doctor_phone = doctorPhone($data['doctor_id'][$i]);                                            // Get Doctor phone number
                   if(isset($doctor_phone)){                                                                       // Send message to doctor about appointment
                       $with           = $customer->name;
                       $at             = centerName($data['hospital_id'][$i]);
                       $doctorName     = doctorName($data['doctor_id'][$i]);                                            // Get Doctor phone number
                       $date           = Carbon::parse($data['appointment_date'][$i]);                             // Appointment date
                       $fdate          = $date->format('jS F Y');
                       $time           = $date->format('h:i A');
                       $n              = '\n';
                       $message        = "Hello+$doctorName,".$n.$n."You+have+an+appointment+with+$with.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Center/Clinic:+$at.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Also download our DoctorALL App for better clinical management in case you do not have it installed already: https://bit.ly/37BO0w5";
                       $sms            = CustomerAppointmentSms($message, $doctor_phone);
                   }
                }

            }
        }
                // Insert Diagnostic # 1 in customer diagnostics table
        if ($data['diagnostic_id1'][0]!=NULL && $data['diagnostic_id1'][0]!=0 && $data['lab_id1'][0]!=NULL  && $data['lab_id1'][0]!=0) {
            $arr_count          =   count($data['diagnostic_id1']);
            $bundle_id          =   $customer_id.time().rand(10,100000);
                for ($i=0; $i < $arr_count; $i++) {
                    $this->diagnosticValidate1($data);
                    $this->Customerdiagnostic1($data,$i,$customer_id,null,$bundle_id);
                    if(isset($data['diagnostic_appointment_date1'][$i]) && isset($customer->phone)){                        // send message to customer
                        $lab            =   Lab::where('id',$data['lab_id1'])->withTrashed()->first();
                        $with           =   $lab->name;
                        $location       =   $lab->address;
                        $date           =   Carbon::parse($data['diagnostic_appointment_date1']);                             // Appointment date
                        $time           =   $date->format('h:i A');
                        $fdate          =   $date->format('jS F Y');
                        $n              =   '\n';
                        $message        =   "Dear+$customer->name,".$n.$n."Your+appointment+has+been+booked+with+$with.".$n.$n."Date: $fdate".$n."Time:$time".$n.$n."Location:+$location".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies. You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Now you can download our CareALL App for booking appointments and managing your health records: https://bit.ly/2GBo2Nm";
                        $sms            =   CustomerAppointmentSms($message, $customer->phone);
                }
            }
        }
            // Insert Diagnostic # 2 in customer diagnostics table
        if ($data['diagnostic_id2'][0]!=null && $data['diagnostic_id2'][0]!=0 && $data['lab_id2'][0]!=null  && $data['lab_id2'][0]!=0) {
            $arr_count          =   count($data['diagnostic_id2']);
            $bundle_id          =   $customer_id.time().rand(10,100000);
                for ($i=0; $i < $arr_count; $i++) {
                    $this->diagnosticValidate2($data);
                    $this->Customerdiagnostic2($data,$i,$customer_id,null,$bundle_id);
                }
                if(isset($data['diagnostic_appointment_date2'][$i]) && isset($customer->phone)){                                                                    // send message to customer
                    $lab            =   Lab::where('id',$data['lab_id2'])->withTrashed()->first();
                    $with           =   $lab->name;
                    $location       =   $lab->address;
                    $date           =   Carbon::parse($data['diagnostic_appointment_date2']);                             // Appointment date
                    $time           =   $date->format('h:i A');
                    $fdate          =   $date->format('jS F Y');
                    $n              =   '\n';
                    $message        =   "Dear+$customer->name,".$n.$n."Your+appointment+has+been+booked+with+$with.".$n.$n."Date: $fdate".$n."Time:$time".$n.$n."Location:+$location".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies. You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Now you can download our CareALL App for booking appointments and managing your health records: https://bit.ly/2GBo2Nm";
                    $sms            =   CustomerAppointmentSms($message, $customer->phone);
                }
        }
        return $customer;
    }
    public function update($data,$id){

        $this->validate($data,$id);

        $returnBackConditions = $this->returnBackConditions($data);

        if($returnBackConditions != null){
            return ['error',$returnBackConditions];
        }
        $customer =  Customer::where('id',$id)->first();
        $data['customer_lead'] = $customer->customer_lead;
        $customer_updated = $customer->update($this->getSecureInput($data));
        $customer_id     = $id;
        if ($customer_updated) {
            $updated = Carbon::now()->toDateTimeString();
            $created =null;
            $coordinator_performance = CoodinatorPerformance::create($this->getSecureInputCoodinatorPerformance($data,$customer_id,$created,$updated));
        }
        $customer = Customer::where('id',$id)->first();
        $customer_user = User::where('customer_id',$id)->first();
        if(isset($customer_user)){
            $update_customer_user = $customer_user->update([
                "name"            => $customer->name,
                "phone"           => $customer->phone,
            ]);
        }
        if ($customer_updated) {                                                                                 // delete customer procedures then update data
            $customer_procedure = DB::table('customer_procedures')->where('customer_id', $id)->get();
            $delete_customer_procedure = DB::table('customer_procedures')->where('customer_id', $id)->delete();
        }
        // if ($data['doctor_notes']) {
        //     $old_doctor_notes   =   CustomerDoctorNotes::where('customer_id',$id)->forceDelete();
        //     $doctor_notes = CustomerDoctorNotes::create($this->getSecureInputCustomerDoctorNotes($data,$customer_id));
        // }
        if ($data['riskfactor_notes']) {
            $old_riskfactor_notes   =   CustomerRiskFactor::where('customer_id',$customer_id)->forceDelete();
            if($data['riskfactor_notes'][0] != null){
                $riskfactor_notes = $data['riskfactor_notes'];
                foreach($riskfactor_notes as $rn){
                    if(isset($rn)){
                        $riskfactor_notes_create = CustomerRiskFactor::create($this->getSecureInputCustomerRiskFactor($rn,$customer_id));
                    }
                }
            }
        }
        if ($data['allergies_notes']) {
            $old_allergies_notes   =   CustomerAllergy::where('customer_id',$customer_id)->forceDelete();
            if($data['allergies_notes'][0] != null){
                $allergies_notes = $data['allergies_notes'];
                foreach($allergies_notes as $an){
                    if(isset($an)){
                        $allergies_notes_create = CustomerAllergy::create($this->getSecureInputCustomerAllergy($an,$customer_id));
                    }
                }
            }
        }
        // insert treatment in customer procedure table

        if ($data['procedure_id'][0] != null && $data['hospital_id'][0] != null && $data['hospital_id'][0] != 0 && $data['doctor_id'][0] != null && $data['doctor_id'][0] != 0) {
            $arr_count = count($data['procedure_id']);
            for ($i = 0; $i < $arr_count; $i++) {
                $this->treatmentValidate($data);
                $this->treatment($data, $i, $customer_id);
                if(count($customer_procedure)>0){
                    if(isset($customer_procedure[$i])){
                        $customer_procedure_appointment  =$customer_procedure[$i]->appointment_date;
                    }
                }else{
                    $customer_procedure_appointment = '';
                }
                // $customer_procedure_appointment  =(count($customer_procedure)>0)? $customer_procedure[$i]->appointment_date:'';
                $customer_procedure_appointment = Carbon::parse($customer_procedure_appointment);
                $old_date = $customer_procedure_appointment->format('Y-m-d h:i A');
                $date           = Carbon::parse($data['appointment_date'][$i]);
                $date           = $date->format('Y-m-d h:i A');

                if ((isset($data['appointment_date'][$i])) && ($date != $old_date)) {
                    $with           = $customer->name;
                    $doctorName     = doctorName($data['doctor_id'][$i]);                                            // Get Doctor phone number
                    $at             = centerName($data['hospital_id'][$i]);
                    $message        = "Your new appointment is scheduled at $date with $with at $at";
                    $customermessage= "Your new appointment is scheduled at $date with $doctorName at $at";
                    $check_doctor_in_users = User::where('doctor_id',$data['doctor_id'][$i])->first();
                    if($check_doctor_in_users){
                        NotificationHelper::GENERATE([
                            'title' => 'New Appointment',
                            'body' =>  $message,
                            'payload' => [
                                'type' => "Appointment Updated"
                            ]
                        ], [$data['doctor_id'][$i]]);
                    }
                    $check_customer_in_users = User::where('customer_id',$customer->id)->first();

                    if(isset($check_customer_in_users)){
                        NotificationHelper::GENERATE([
                            'title' => 'New Appointment',
                            'body' =>  $customermessage,
                            'payload' => [
                                'type' => "Appointment Updated"
                            ]
                        ], $check_customer_in_users->id);
                    }
                    if (isset($customer->phone)) {                                                                    // send message to customer
                        $with           = doctorName($data['doctor_id'][$i]);
                        $at             = centerName($data['hospital_id'][$i]);
                        $location       = centerlocation($data['hospital_id'][$i]);
                        $map            = centerMap($data['hospital_id'][$i]);
                        $date           = Carbon::parse($data['appointment_date'][$i]);                             // Appointment date
                        $fdate          = $date->format('jS F Y');
                        $time           = $date->format('h:i A');
                        $n              = '\n';
                        $message        = "Dear+$customer->name,".$n.$n."Your+appointment+has+been+booked+with+$with+at+$at.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Location:+$location".$n."Google+Map:+$map".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies.+You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Now you can download our CareALL App for booking appointments and managing your health records: https://bit.ly/2GBo2Nm";
                        $sms            = CustomerAppointmentSms($message, $customer->phone);
                    }
                    $doctor_phone = doctorPhone($data['doctor_id'][$i]);                  // Get Doctor phone number
                if (isset($doctor_phone)) {                                               // Send message to doctor about appointment
                    $with           = $customer->name;
                    $at             = centerName($data['hospital_id'][$i]);
                    $doctorName     = doctorName($data['doctor_id'][$i]);                                            // Get Doctor phone number
                    $date           = Carbon::parse($data['appointment_date'][$i]);                             // Appointment date
                    $fdate          = $date->format('jS F Y');
                    $time           = $date->format('h:i A');
                    $n              = '\n';
                    $message        = "Hello+$doctorName,".$n.$n."You+have+an+appointment+with+$with.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Center/Clinic:+$at.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Also download our DoctorALL App for better clinical management in case you do not have it installed already: https://bit.ly/37BO0w5";
                    $sms            = CustomerAppointmentSms($message, $doctor_phone);
                }
                }
            }
        }
        if ($customer_updated && isset($data['lab_id1'])) {
            $customer_lab1 = DB::table('customer_diagnostics')->where('customer_id', $customer_id)->where('lab_id',$data['lab_id1'])->first();
            $customer_lab2 = DB::table('customer_diagnostics')->where('customer_id', $customer_id)->where('lab_id', $data['lab_id2'])->first();
            $customer_diagnostics = DB::table('customer_diagnostics')->where('customer_id', $id)->delete();
        }
                // Insert Diagnostic # 1 in customer diagnostics table
                if (isset($data['lab_id1']) && $data['lab_id1'] != null) {
                    if ($data['diagnostic_id1'][0]!=null && $data['diagnostic_id1'][0]!=0 && $data['lab_id1'][0]!=null  && $data['lab_id1'][0]!=0) {
                        $arr_count          =   count($data['diagnostic_id1']);
                        $bundle_id          =   $customer_id.time().rand(10,100000);
                        for ($i=0; $i < $arr_count; $i++) {
                            $this->diagnosticValidate1($data);
                            $this->Customerdiagnostic1($data, $i, $customer_id,$customer_lab1,$bundle_id);
                        }
                            if(isset($customer_lab1)){
                                $customer_diagnostics  = $customer_lab1->appointment_date;
                                $customer_diagnostics   = Carbon::parse($customer_diagnostics);
                                $old_diagnostic_date    = $customer_diagnostics->format('Y-m-d h:i A');
                            }else{
                                $old_diagnostic_date = '';
                            }
                            if(isset($data['diagnostic_appointment_date1']) && isset($customer->phone)){                                                                    // send message to customer
                                $date           =   Carbon::parse($data['diagnostic_appointment_date1']);                             // Appointment date
                                $diagnostic_date=   $date->format('Y-m-d h:i A');
                                $time           =   $date->format('h:i A');
                                $fdate          =   $date->format('jS F Y');

                                if($old_diagnostic_date != $diagnostic_date){
                                    $lab            =   Lab::where('id',$data['lab_id1'])->withTrashed()->first();
                                    $with           =   $lab->name;
                                    $location       =   $lab->address;
                                    $n              =   '\n';
                                    $message        =   "Dear+$customer->name,".$n.$n."Your+appointment+has+been+booked+with+$with.".$n.$n."Date: $fdate".$n."Time:$time".$n.$n."Location:+$location".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies. You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Now you can download our CareALL App for booking appointments and managing your health records: https://bit.ly/2GBo2Nm";
                                    $sms            =   CustomerAppointmentSms($message, $customer->phone);
                            }
                        }
                    }
                }
                // Insert Diagnostic # 2 in customer diagnostics table
                if(isset($data['lab_id2']) && $data['lab_id2'] != null){
                  if ($data['diagnostic_id2'][0]!=null && $data['diagnostic_id2'][0]!=0 && $data['lab_id2'][0]!=null  && $data['lab_id2'][0]!=0) {
                      $arr_count          =   count($data['diagnostic_id2']);
                      $bundle_id          =   $customer_id.time().rand(10,100000);
                      for ($i=0; $i < $arr_count; $i++) {
                          $this->diagnosticValidate2($data);
                          $this->Customerdiagnostic2($data, $i, $customer_id, $customer_lab2,$bundle_id);
                      }
                      if (isset($customer_lab2)) {
                          $customer_diagnostics2  = $customer_lab2->appointment_date;
                          $customer_diagnostics2   = Carbon::parse($customer_diagnostics2);
                          $old_diagnostic_date2    = $customer_diagnostics2->format('Y-m-d h:i A');
                      } else {
                          $old_diagnostic_date2 = '';
                      }
                      if (isset($data['diagnostic_appointment_date2']) && isset($customer->phone)) {
                          $date           =   Carbon::parse($data['diagnostic_appointment_date2']);                             // Appointment date
                          $diagnostic_date=   $date->format('Y-m-d h:i A');
                          $time           =   $date->format('h:i A');
                          $fdate          =   $date->format('jS F Y');
                          if ($old_diagnostic_date2 != $diagnostic_date) {
                              $lab            =   Lab::where('id', $data['lab_id2'])->withTrashed()->first();
                              $with           =   $lab->name;
                              $location       =   $lab->address;
                              $n              =   '\n';
                              $message        =   "Dear+$customer->name,".$n.$n."Your+appointment+has+been+booked+with+$with.".$n.$n."Date: $fdate".$n."Time:$time".$n.$n."Location:+$location".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies. You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Now you can download our CareALL App for booking appointments and managing your health records: https://bit.ly/2GBo2Nm";
                              $sms            =   CustomerAppointmentSms($message, $customer->phone);
                          }
                      }
                  }
                  }
        return $customer;
    }
    public function Customerdiagnostic1($data,$i,$customer_id,$customer_lab1,$bundle_id){
        $diagnostic_id        = $data['diagnostic_id1'][$i];
        $lab_id               = $data['lab_id1'];
        $cost                 = $data['diagnostics_cost'][$i];
        $home_sampling        = isset($data['home_sampling1'])? $data['home_sampling1'] : 0;
        $appointment_date     = $data['diagnostic_appointment_date1'];
        $appointment_from     = isset($data['diagnostics_appointment_from'][$i])? $data['diagnostics_appointment_from'][$i] : 0;
        if($data['discount1'] != 0){
            $discount            = $data['discount1'];
            $discounted_cost      = $cost - ($cost * ($discount/100));
          }else{
              $discount         = isset($customer_lab1)? $customer_lab1->discount_per : $data['discount1'];
              $discounted_cost  = isset($customer_lab1)? $customer_lab1->discounted_cost : $cost - ($cost * ($discount/100));
          }
        if ($data['diagnostic_id1'][$i]!=null && $data['lab_id1']!=null && $data['diagnostic_id1'][$i]!=0 && $data['lab_id1']!=0) {
            $add_Treatments     =   DB::table('customer_diagnostics')->INSERT($this->insertcustomerdiagnostics($customer_id,$diagnostic_id,$lab_id,$cost,$discounted_cost, $appointment_date,$discount,$appointment_from,$home_sampling,$bundle_id));
          }
    }
    public function Customerdiagnostic2($data,$i,$customer_id,$customer_lab2,$bundle_id){
        $diagnostic_id        = $data['diagnostic_id2'][$i];
        $lab_id               = $data['lab_id2'];
        $cost                 = $data['diagnostics_cost2'][$i];
        $home_sampling        = isset($data['home_sampling2']) ? $data['home_sampling2'] : 0;
        $appointment_date     = $data['diagnostic_appointment_date2'];
        $appointment_from     = isset($data['diagnostics_appointment_from2'][$i])? $data['diagnostics_appointment_from2'][$i] : 0;
        if($data['discount2'] != 0){
            $discount            = $data['discount2'];
            $discounted_cost      = $cost - ($cost * ($discount/100));
          }else{
              $discount        = isset($customer_lab2)? $customer_lab2->discount_per : $data['discount2'];
              $discounted_cost  = isset($customer_lab2)? $customer_lab2->discounted_cost : $cost - ($cost * ($discount/100));
          }
        if ($data['diagnostic_id2'][$i]!=null && $data['lab_id2']!=null && $data['diagnostic_id2'][$i]!=0 && $data['lab_id2']!=0) {
            $add_Treatments     =   DB::table('customer_diagnostics')->INSERT($this->insertcustomerdiagnostics($customer_id,$diagnostic_id,$lab_id,$cost,$discounted_cost, $appointment_date,$discount,$appointment_from,$home_sampling,$bundle_id));
        }
    }
    public function treatment($data,$i,$customer_id){
        $treatment_id         = $data['treatment_id'][$i];
        $procedure_id         = $data['procedure_id'][$i];
        if($procedure_id == 0){
            $procedure_id = $treatment_id;
        }
        $hospital_id          = $data['hospital_id'][$i];
        $doctor_id            = $data['doctor_id'][$i];
        $cost                 = $data['cost'][$i];
        $treatment_discount   = $data['treatment_discount'][$i];
        if ($data['discounted_tcost'][$i] == NULL) {
          $discounted_tcost     =  $data['cost'][$i];
        } else {
          $discounted_tcost     =  $data['discounted_tcost'][$i];
        }
        $appointment_date     = $data['appointment_date'][$i];
        $appointment_from     = $data['appointment_from'][$i];
        if ($data['procedure_id'][$i] != null && $data['hospital_id'][$i] != null && $data['hospital_id'][$i] != 0 && $data['doctor_id'][$i] != null && $data['doctor_id'][$i] != 0) {
            $add_Treatments = DB::table('customer_procedures')->INSERT([
                'customer_id'       => $customer_id,
                'treatments_id'     => $procedure_id,
                'hospital_id'       => $hospital_id,
                'doctor_id'         => $doctor_id,
                'cost'              => $cost,
                'discount_per'      => $treatment_discount,
                'discounted_cost'   => $discounted_tcost,
                'status'            => 0,
                'appointment_date'  => $appointment_date,
                'appointment_from'  => isset($appointment_from)?$appointment_from:0,
            ]);
        }
        return $add_Treatments;
    }
    public function getSecureInputCustomerDoctorNotes($input,$id){
        $data = [
            'notes'         =>  $input['doctor_notes'],
            'customer_id'   =>  $id,
        ];
        return $data;
    }
    public function getSecureInputCoodinatorPerformance($input,$id,$created,$updated){
        $data = [
            'owner_id'      =>  $input['patient_coordinator_id'],
            'created'       =>  $created,
            'updated'       =>  $updated,
            'customer_id'   =>  $id,
        ];
        return $data;
    }
    public function getSecureInputCustomerAllergy($input,$id){
        $data = [
            'notes'         =>  $input,
            'customer_id'   =>  $id,
        ];
        return $data;
    }
    public function getSecureInputCustomerRiskFactor($input,$id){
        $data = [
            'notes'         =>  $input,
            'customer_id'   =>  $id,
        ];
        return $data;
    }

    public function getSecureInput($input){
        $data = [                                                                                           // insert data in customer table
            'ref'                   => $this->unique_code(4),
            'card_id'               => (isset($input['card_id'])) ? $input['card_id'] :NULL,
            'name'                  => $input['name'],
            'email'                 => $input['email'],
            'phone'                 => $input['phone'],
            'address'               => $input['address'],
            'city_name'             => isset($input['city'])? $input['city'] : null,
            'gender'                => $input['gender'],
            'organization_id'       => (isset($input['organization_id'])) ? $input['organization_id'] : null,
            'org_verified'          => (isset($input['organization_id'])) ? 1 : 0,
            'employee_code'         => (isset($input['organization_id'])) ? $input['employee_code'] : null,
            'marital_status'        => $input['marital_status'],
            'age'                   => $input['age'],
            'weight'                => $input['weight'],
            'height'                => $input['height'],
            'blood_group_id'        => $input['blood_group_id'],
            'notes'                 => $input['notes'],
            'status_id'             => $input['status_id'],
            'phone_verified'        => 1,
            'customer_lead'         => (isset($input['customer_lead']))? $input['customer_lead']:0,
            'relation'              => (isset($input['relation'])) ? $input['relation'] : null,
            'parent_id'             => (isset($input['parent_id'])) ? $input['parent_id'] : null,
            'next_contact_date'     => date('Y-m-d', strtotime($input['next_contact_date'])),
            'patient_coordinator_id'=> $input['patient_coordinator_id'],
        ];
        return $data;
    }
    public function insertcustomerdiagnostics($customer_id,$diagnostic_id,$lab_id,$cost,$discounted_cost, $appointment_date,$discount,$appointment_from,$home_sampling,$bundle_id){
        $data = [
            'customer_id'       =>  $customer_id,
            'diagnostic_id'     =>  $diagnostic_id,
            'lab_id'            =>  $lab_id,
            'cost'              =>  $cost,
            'discounted_cost'   =>  $discounted_cost,
            'appointment_date'  =>  $appointment_date,
            'appointment_from'  =>  $appointment_from,
            'discount_per'      =>  $discount,
            'status'            =>  0,
            'home_sampling'     =>  $home_sampling,
            'bundle_id'         =>  $bundle_id,
        ];
        return $data;
    }
}
