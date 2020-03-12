<?php

namespace App\Http\Controllers\AdminControllers;

use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PendingAppointmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers          =   DB::table('customer_procedures as cp')
        ->join('customers as c','c.id','cp.customer_id')
        ->orWhere('cp.status',4)
        ->select('c.id','c.name','c.phone','c.phone','cp.treatments_id','cp.hospital_id','cp.doctor_id','cp.appointment_date','cp.appointment_from','cp.created_at','cp.id as cp_id')
        ->orderBy('cp.created_at','Desc')
        ->get();
        // dd($customers);
        return view('adminpanel.pendingappointments.index',compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('adminpanel.pendingappointments.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $update     =   DB::table('customer_procedures')->where('id',$id)->update([
            'status'    =>  0,
        ]);
        if ($update) {
            $data           = datafromCustomerProcedureId($id);
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
            $message        = "Dear+$customerName,".$n.$n."Your+appointment+has+been+Approved+with+$doctorName+at+$at.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Location:+$location".$n."Google+Map:+$map".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies. You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Now you can download our CareALL App for booking appointments and managing your health records: https://bit.ly/2GBo2Nm";
            $sms            = CustomerAppointmentSms($message, $data->customer_phone);

            //Message to Doctor
           if(isset($doctor_phone)){                       // Send message to doctor about appointment
             $message        = "Hello+$doctorName,".$n.$n."You+have+an+appointment+with+$customerName.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Center/Clinic:+$at.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Also download our DoctorALL App for better clinical management in case you do not have it installed already: https://bit.ly/37BO0w5";
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
                ],$check_customer_in_users->id);
            }
        session()->flash('success', $data->name."'s Appointments is Approved Successfully with ".$doctorName);
        return redirect()->back();
    } else {
        session()->flash('error', "Something's Wrong! Could not update.");
        return redirect()->route('pendingappointments.index');
    }
}
    public function updatePendingAppointment(Request $request, $id){
        $treatment_id       = $request->treatment_id;
        $procedure_id       = $request->procedure_id;
        if($procedure_id == 0){
            $procedure_id = $treatment_id;
        }
        $appointment_date= Carbon::parse($request->appointment_date)->toDateTimeString();
        if ($procedure_id != null &&  $request->hospital_id != null &&  $request->hospital_id != 0 && $request->doctor_id != null && $request->doctor_id != 0) {
            $update_customer_procedure = DB::table('customer_procedures')->where('id',$id)->update([
                'customer_id'       => $request->customer_id,
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
            $data           = datafromCustomerProcedureId($id);
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
            $message        = "Dear+$customerName,".$n.$n."Your+appointment+has+been+Approved+with+$doctorName+at+$at.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Location:+$location".$n."Google+Map:+$map".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies. You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Now you can download our CareALL App for booking appointments and managing your health records: https://bit.ly/2GBo2Nm";
            $sms            = CustomerAppointmentSms($message, $data->customer_phone);

            //Message to Doctor
           if(isset($doctor_phone)){                       // Send message to doctor about appointment
             $message        = "Hello+$doctorName,".$n.$n."You+have+an+appointment+with+$customerName.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Center/Clinic:+$at.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Also download our DoctorALL App for better clinical management in case you do not have it installed already: https://bit.ly/37BO0w5";
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
                ],$check_customer_in_users->id);
            }
        session()->flash('success', $data->name."'s Appointments is Approved Successfully with ".$doctorName);
        return redirect()->back();
        }
        session()->flash('error', " There is something missing. Select all fields to continue");
        return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $data           =   datafromCustomerProcedureId($id);
        if ($data) {
            $delete         =   DB::table('customer_procedures')->where('id',$id)->delete();
            if ($delete) {
                $doctorName     = $data->doctor_name." ".$data->doctor_last_name;
                $doctor_phone   = ($data->doctor_phone) ? $data->doctor_phone : $data->assistant_phone;
                $customerName   = $data->name;
                $at             = $data->center_name;
                $date_orginal   = Carbon::parse($data->appointment_date);
                $date           = $date_orginal->format('Y-m-d h:i A');                         // Appointment date
                $fdate          = $date_orginal->format('jS F Y');
                $time           = $date_orginal->format('h:i A');
                $n              = '\n';
            //Message to Customer/Patient
            $message        = "Dear+$customerName,".$n.$n."Your+appointment+has+been+Canceled+with+$doctorName+at+$at.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries and Reschedule:+0322-2555600,".$n."0322-2555400".$n.$n."Now you can download our CareALL App for booking appointments and managing your health records: https://bit.ly/2GBo2Nm";
            $sms            = CustomerAppointmentSms($message, $data->customer_phone);

            //Notification to Customer User
            $message                    = "Your appointment scheduled at $date with $doctorName at $at has been Canceled";
            $check_customer_in_users    = User::where('customer_id',$data->customer_id)->first();
            if($check_customer_in_users){
                NotificationHelper::GENERATE([
                    'title' => 'Appointment Canceled!',
                    'body' => $message,
                    'payload' => [
                        'type' => "Canceled appointment"
                    ]
                ],$check_customer_in_users->id);
            }
            session()->flash('success', $data->name."'s Appointments is Canceled Successfully with ".$doctorName);
            return redirect()->back();
        } else {
            session()->flash('error','Something Happened! Could not Delete');
            return redirect()->route('pendingappointments.index');
        }
    }
}
}
