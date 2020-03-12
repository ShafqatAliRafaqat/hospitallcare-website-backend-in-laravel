<?php

namespace App\Console\Commands;

use App\Models\Admin\Center;
use App\Models\Admin\Customer;
use App\Models\Admin\Doctor;
use App\Models\Admin\Lab;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client;

class SendSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendsms:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today      = Carbon::now()->toDateTimeString();
        $start_time = date("Y-m-d H:i:s", strtotime("$today + 4 hours"));
        $end_time   = date("Y-m-d H:i:s", strtotime("$today + 5 hours"));
        $customer_procedures   = DB::table('customer_procedures')->whereBetween('appointment_date', [$start_time,$end_time])->where('status', '!=', 1)->get();
        if(count($customer_procedures)>0){
        foreach ($customer_procedures as $c) {
            $center         =   Center::where('id', $c->hospital_id)->withTrashed()->first();
            $at             =   $center->center_name;
            $location       =   $center->address;
            $map            =   "";
            $doctor         =   Doctor::where('id', $c->doctor_id)->withTrashed()->first();
            $with           =   $doctor->name;
            $doctor_phone   =   $doctor->phone;
            $customer       =   Customer::where('id', $c->customer_id)->withTrashed()->first();
            $customer_phone =   $customer->phone;
            $customer_name  =   $customer->name;
            $date           =   Carbon::parse($c->appointment_date);
            $fdate          =   $date->format('jS F Y');
            $time           =   $date->format('h:i A');
            $n              =   '\n';

            if (isset($customer_phone)) {
                $phone_dash = preg_replace("/[^0-9]/", "", $customer_phone);
                $n              =   '\n';
                $message        = "Reminder.".$n.$n."Dear+$customer_name,".$n.$n."Your+appointment+has+been+booked+with+$with+at+$at.".$n.$n."Date:+$fdate+".$n."Time:+$time+".$n.$n."Location:+$location".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies.+You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Facilitator,".$n."Hospitallcare.com".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Now you can download our CareALL App for booking appointments and managing your health records: https://bit.ly/2GBo2Nm";
                $message = str_replace('&', 'and', $message);
                $message = str_replace('،', 'and', $message);
                $message = str_replace(' ', '+', $message);
                $str1       = ltrim($phone_dash, '0');
                $phone      = '92'.$str1;
                $sms  = 'http://smsctp3.eocean.us:24555/api?action=sendmessage&username=Nestol&password=32JNoi90&recipient='.$phone.'&originator=99095&messagedata='.$message.'';
                $curl_handle=curl_init();
                curl_setopt($curl_handle,CURLOPT_URL,$sms);
                curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
                curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
                $buffer = curl_exec($curl_handle);
                curl_close($curl_handle);
            }
        }
    }
        $customer_diagnostics   = DB::table('customer_diagnostics')->whereBetween('appointment_date',[$start_time,$end_time])->get();
        if(count($customer_diagnostics)>0){
            foreach ($customer_diagnostics as $cd) {
            $lab            =   Lab::where('id',$cd->lab_id)->withTrashed()->first();
            $with           =   $lab->name;
            $location       =   $lab->address;
            $customer       =   Customer::where('id', $cd->customer_id)->withTrashed()->first();
            $customer_name  =   $customer->name;
            $customer_phone =   $customer->phone;
            $date           =   Carbon::parse($cd->appointment_date);                             // Appointment date
            $date           =   $date->format('h:i A');
            $fdate          =   $date->format('jS F Y');
            if (isset($customer_phone)) {
                $phone_dash = preg_replace("/[^0-9]/", "", $customer_phone);
                $n              =   '\n';
                $message        = "Reminder.".$n.$n."Dear+$customer->name,".$n.$n."Your+appointment+has+been+booked+with+$with.".$n.$n."Date: $fdate".$n."Time:$date".$n.$n."Location:+$location".$n.$n."Note:+Appointment+is+subject+to+Queue+and+Emergencies.You+might+have+to+wait+in+such+situations.".$n.$n."The+Best+Healthcare+Provider,".$n."Hospitallcare.com".$n."For+Queries:+0322-2555600,".$n."0322-2555400".$n.$n."Now you can download our CareALL App for booking appointments and managing your health records: https://bit.ly/2GBo2Nm";
                $message = str_replace('&', 'and', $message);
                $message = str_replace('،', 'and', $message);
                $message = str_replace(' ', '+', $message);
                $str1       = ltrim($phone_dash, '0');
                $phone      = '92'.$str1;
                $sms  = 'http://smsctp3.eocean.us:24555/api?action=sendmessage&username=Nestol&password=32JNoi90&recipient='.$phone.'&originator=99095&messagedata='.$message.'';
                $curl_handle=curl_init();
                curl_setopt($curl_handle,CURLOPT_URL,$sms);
                curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
                curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
                $buffer = curl_exec($curl_handle);
                curl_close($curl_handle);
            }
            }
        }
        if(count($customer_procedures)>0){
            $this->info('SMS Sent successfully');
        }else{
            $this->info("there is no data to send sms");
        }
    }
}
