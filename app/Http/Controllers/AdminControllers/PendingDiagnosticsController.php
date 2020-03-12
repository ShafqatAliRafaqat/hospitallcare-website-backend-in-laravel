<?php

namespace App\Http\Controllers\AdminControllers;

use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PendingDiagnosticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers          =   DB::table('customer_diagnostics as cd')
        ->join('customers as c','c.id','cd.customer_id')
        ->orWhere('cd.status',4)
        ->select('c.id','c.name','c.phone','c.phone',DB::raw('GROUP_CONCAT(cd.diagnostic_id) as diagnostic_id'),'cd.lab_id','cd.cost','cd.appointment_date','cd.appointment_from','cd.created_at',DB::raw('GROUP_CONCAT(cd.id) as cd_id'))
        ->orderBy('cd.id','Desc')
        ->groupBy('cd.lab_id','c.id','cd.appointment_date')
        ->get();
        // dd($customers);
        return view('adminpanel.pendingdiagnostics.index',compact('customers'));
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
        //
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
        // $update     =   DB::table('customer_diagnostics')->where('id',$id)->update([
        //     'status'    =>  0,
        // ]);
        $diagnostic_ids         =       $request->diagnostic_ids;
        // dd($diagnostic_ids);
        $diagnostics_array      =       explode(',', $diagnostic_ids);
        if(count($diagnostics_array) > 0){
            $update     =   DB::table('customer_diagnostics')->whereIn('id',$diagnostics_array)->update([
                'status'    =>  0,
            ]);
        // $update     =   true;
        if ($update) {
            $data           = datafromCustomerDiagnosticId($diagnostics_array[0]);
            // dd($data);
            $customerName       =   $data->name;
            $customer_phone     =   $data->customer_phone;
            $at                 =   $data->lab_name;
            $lab_phone          =   $data->lab_phone;
            $location           =   $data->address;
            $map                =   'http://maps.google.com/?q='.$data->lat.','.$data->lng;
            $date_orginal       =   Carbon::parse($data->appointment_date);
            $date               =   $date_orginal->format('Y-m-d h:i A');                         // Appointment date
            $fdate              =   $date_orginal->format('jS F Y');
            $time               =   $date_orginal->format('h:i A');
            $n                  =   '\n';

            //Message to Customer/Patient
            $message        = "Dear+$customerName,".$n.$n."Your+appointment+for+diagnostics+at+$at+has+been+Approved.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Location:+$location".$n."Google+Map:+$map".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies. You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400";
            $sms            = CustomerAppointmentSms($message, $customer_phone);

            //Notification to Customer User
            $message                    = "Your appointment for Diagnostics scheduled on $date at $at has been Approved";
            $check_customer_in_users    = User::where('customer_id',$data->customer_id)->first();
                if($check_customer_in_users){
                    NotificationHelper::GENERATE([
                        'title' => 'Appointment Approved!',
                        'body' => $message,
                        'payload' => [
                            'type' => "New Appointment"
                        ]
                    ],$check_customer_in_users->id);
                }
            //Message to Lab Assistant
           if(isset($lab_phone)){                       // Send message to doctor about appointment
             $message        = "Hello,".$n.$n."You+have+an+appointment+with+$customerName.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Lab:+$at.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries:+0322-2555600,".$n."0322-2555400";
             $sms            = CustomerAppointmentSms($message, $lab_phone);
            }
            session()->flash('success', $customerName."'s Appointment for Diagnostics is Approved Successfully!");
            return redirect()->back();
            } else {
                session()->flash('error', "Something's Wrong! Could not update.");
                return redirect()->route('pendingdiagnostics.index');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $diagnostic_ids         =       $request->diagnostic_ids;
        // dd($diagnostic_ids);
        $diagnostics_array      =       explode(',', $diagnostic_ids);
        if(count($diagnostics_array) > 0){
            $data           = datafromCustomerDiagnosticId($diagnostics_array[0]);
            $delete     =   DB::table('customer_diagnostics')->whereIn('id',$diagnostics_array)->delete();
            if ($delete) {
                $customerName       =   $data->name;
                $customer_phone     =   $data->customer_phone;
                $at                 =   $data->lab_name;
                $lab_phone          =   $data->lab_phone;
                $location           =   $data->address;
                $map                =   'http://maps.google.com/?q='.$data->lat.','.$data->lng;
                $date_orginal       =   Carbon::parse($data->appointment_date);
                $date               =   $date_orginal->format('Y-m-d h:i A');                         // Appointment date
                $fdate              =   $date_orginal->format('jS F Y');
                $time               =   $date_orginal->format('h:i A');
                $n                  =   '\n';

                //Message to Customer/Patient
                $message        = "Dear+$customerName,".$n.$n."Your+appointment+for+diagnostics+at+$at+has+been+Canceled.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Location:+$location".$n."Google+Map:+$map".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com.".$n."For+Queries and Reschedule:+0322-2555600,".$n."0322-2555400";
                $sms            = CustomerAppointmentSms($message, $customer_phone);

                //Notification to Customer User
                $message                    = "Your appointment for Diagnostics scheduled on $date at $at has been Canceled".$n.$n."For+Queries:+0322-2555600,".$n."0322-2555400";
                $check_customer_in_users    = User::where('customer_id',$data->customer_id)->first();
                if($check_customer_in_users){
                    NotificationHelper::GENERATE([
                        'title' => 'Appointment Canceled!',
                        'body' => $message,
                        'payload' => [
                            'type' => "Canceled Appointment"
                        ]
                    ],$check_customer_in_users->id);
                }
                session()->flash('success', $customerName."'s Appointment for Diagnostics is Canceled!");
                return redirect()->back();
            } else {
                session()->flash('error', "Something's Wrong! Could not Delete.");
                return redirect()->route('pendingdiagnostics.index');
            }
        }
    }
}
