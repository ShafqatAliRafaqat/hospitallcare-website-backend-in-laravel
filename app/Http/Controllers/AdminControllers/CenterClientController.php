<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Center;
use App\Models\Admin\Customer;
use App\Models\Admin\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CenterClientController extends Controller
{
    public function index()                                              // function to get all cutomer of any medical center
    {
        $center_id              =   Auth::user()->medical_center_id;
        $center_clients         =   DB:: table('customer_procedures')->where('hospital_id', $center_id)->orderBy('appointment_date','DESC')->get();
        $clients = [];
        foreach($center_clients as $c){
            $clients[] = Customer::where('id',$c->customer_id)->where('status_id','<',5)->with(['treatments'])->first();
        }
        return view('centerpanel.clients.index', compact('clients'));
    }
    public function upcoming()                                          // function to get upcoming appointment of clinic
    {
        $endOfDay = Carbon::now()->endOfDay()->toDateTimeString();      //today end time and date
     
        $center_id              =   Auth::user()->medical_center_id;
        $center_clients         =   DB:: table('customer_procedures')->where('hospital_id', $center_id)->where('appointment_date','>', $endOfDay)->orderBy('appointment_date','ASC')->get();
        $clients = [];
        foreach($center_clients as $c){
            $clients[] = Customer::where('id',$c->customer_id)->with(['treatments'])->first();
        }
        return view('centerpanel.appointment.appointment', compact('clients'));
    }
    public function today()                                              // function to get today appointment of clinic 
    {
        $startOfDay = Carbon::now()->startOfDay()->toDateTimeString();   //today start time and date
        $endOfDay = Carbon::now()->endOfDay()->toDateTimeString();       //today end time and date

        $center_id              =   Auth::user()->medical_center_id;
        $center_clients         =   DB:: table('customer_procedures')->where('hospital_id', $center_id)->whereBetween('appointment_date',[$startOfDay, $endOfDay])->orderBy('appointment_date','DESC')->get();
        $clients = [];
        foreach($center_clients as $c){
            $clients[] = Customer::where('id',$c->customer_id)->with(['treatments'])->first();
        }
        return view('centerpanel.appointment.appointment', compact('clients'));
    }
    public function previous()                                          // function to get previous appointment of clinic
     {
        $startOfDay = Carbon::now()->startOfDay()->toDateTimeString();  //today start time and date

        $center_id              =   Auth::user()->medical_center_id;
        $center_clients         =   DB:: table('customer_procedures')->where('hospital_id', $center_id)->where('appointment_date','<',$startOfDay)->orderBy('appointment_date','DESC')->get();
        $clients = [];
        
        foreach($center_clients as $c){
            $clients[] = Customer::where('id',$c->customer_id)->with(['treatments'])->first();
        }    
        return view('centerpanel.appointment.appointment', compact('clients'));
    }

}
