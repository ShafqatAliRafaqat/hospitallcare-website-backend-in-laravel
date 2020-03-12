<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Treatment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Validator;
use App\Models\Admin\Customer;
use App\Organization;
class EmployeeStatusController extends Controller
{
    public function informed()                                                      // Those Customer which are informed
    {
        $org_id = Auth::user()->organization_id;
        if ( Auth::user()->can('employee_status') ) {

            $employees = Customer::where('organization_id',$org_id)->where('status_id',1)->get();

           return view('orgpanel.employee_status.informed', compact('employees'));
      } else {
          abort(403);
      }
    }

    public function got_appointment()                                              // Those Customer which are got appointment
    {
        $org_id     =   Auth::user()->organization_id;

        if ( Auth::user()->can('employee_status') ) {

            $employees = Customer::where('organization_id',$org_id)->where('status_id',2)->get();

            return view('orgpanel.employee_status.got_appointment', compact('employees'));
      } else {
          abort(403);
      }
    }
    public function took_appointment()                                            // Those Customer which are took appointment
    {
        $org_id = Auth::user()->organization_id;

        if ( Auth::user()->can('employee_status') ) {

            $employees = Customer::where('organization_id',$org_id)->where('status_id',3)->get();

            return view('orgpanel.employee_status.took_appointment', compact('employees'));
      } else {
          abort(403);
      }
    }
    public function took_treatment()                                            // Those Customer which are took treatment
    {
        $org_id  = Auth::user()->organization_id;

        if ( Auth::user()->can('employee_status') ) {

            $employees = Customer::where('organization_id',$org_id)->where('status_id',4)->get();

            return view('orgpanel.employee_status.took_treatment', compact('employees'));
      } else {
          abort(403);
      }
    }
    public function no_contact()                                            // Those Customer which are took treatment
    {
        $org_id  = Auth::user()->organization_id;

        if ( Auth::user()->can('employee_status') ) {

            $employees = Customer::where('organization_id',$org_id)->where('status_id',5)->get();

            return view('orgpanel.employee_status.no_contact', compact('employees'));
      } else {
          abort(403);
      }
    }
    public function today_stats()                                              // Those Customer which are not contacted yet
    {
        $org_id     = Auth::user()->organization_id;
        $startOfDay = Carbon::now()->startOfDay()->toDateTimeString();                                  // Date and time of start of today
        $endOfDay   = Carbon::now()->startOfDay()->addDays(1)->toDateTimeString();                        // Date and time of start of next day

        if ( Auth::user()->can('employee_status') ) {

            $employees = Customer::where('organization_id',$org_id)->whereBetween('updated_at',[$startOfDay , $endOfDay])->get();
            $message   = "Today Employee Stats ";
            return view('orgpanel.org_panel.show_detail', compact(['employees','message']));
      } else {
          abort(403);
      }
    }
    public function this_week_stats()                                              // Those Customer which are not contacted yet
    {
        $org_id     = Auth::user()->organization_id;
        $startOfWeek= Carbon::now()->startOfWeek()->toDateTimeString();                                // Date and time of start of week
        $endOfWeek  = Carbon::now()->endOfWeek()->toDateTimeString();                                    // Date and time of end of week

        if ( Auth::user()->can('employee_status') ) {

            $employees = Customer::where('organization_id',$org_id)->whereBetween('updated_at',[$startOfWeek , $endOfWeek])->get();
            $message   = "This Week Employee Stats ";
            return view('orgpanel.org_panel.show_detail', compact(['employees','message']));
      } else {
          abort(403);
      }
    }
    public function previous_week_stats()                                              // Those Customer which are not contacted yet
    {
        $org_id     =   Auth::user()->organization_id;
        $startOfPreviousWeek = Carbon::now()->subWeek()->startOfWeek()->toDateTimeString();             // Date and time of start of previous week
        $endOfPreviousWeek   = Carbon::now()->subWeek()->endOfWeek()->toDateTimeString();                 // Date and time of end of previous week

        if ( Auth::user()->can('employee_status') ) {

            $employees = Customer::where('organization_id',$org_id)->whereBetween('updated_at',[$startOfPreviousWeek , $endOfPreviousWeek])->get();
            $message   = "previous Week Employee Stats ";
            return view('orgpanel.org_panel.show_detail',compact(['employees','message']));
      } else {
          abort(403);
      }
    }
    public function this_month_stats()                                              // Those Customer which are not contacted yet
    {
        $org_id       = Auth::user()->organization_id;
        $startOfMonth = Carbon::now()->startOfMonth()->toDateTimeString();                              // Date and time of start of month
        $endOfMonth   = Carbon::now()->endOfMonth()->toDateTimeString();                                  // Date and time of end of month

        if ( Auth::user()->can('employee_status') ) {

            $employees = Customer::where('organization_id',$org_id)->whereBetween('updated_at',[$startOfMonth , $endOfMonth])->get();
            $message   = "This Month Employee Stats ";
            return view('orgpanel.org_panel.show_detail',compact(['employees','message']));
      } else {
          abort(403);
      }
    }
    public function this_year_stats()                                              // Those Customer which are not contacted yet
    {
        $org_id     = Auth::user()->organization_id;
        $startOfYear= Carbon::now()->startOfYear()->toDateTimeString();                                // Date and time of start of year
        $endOfYear  = Carbon::now()->endOfYear()->toDateTimeString();                                    // Date and time of end of year

        if ( Auth::user()->can('employee_status') ) {

            $employees = Customer::where('organization_id',$org_id)->whereBetween('updated_at',[$startOfYear , $endOfYear])->get();
            $message   = "This Year Employee Stats ";
            return view('orgpanel.org_panel.show_detail',compact(['employees','message']));
      } else {
          abort(403);
      }
    }
    public function upcoming()                                          // function to get upcoming appointment of clinic
    {
        $endOfDay       = Carbon::now()->endOfDay()->toDateTimeString();      //today end time and date
        $org_id         = Auth::user()->organization_id;
        $employees      = DB::table('customers as c')
                        ->JOIN('customer_diagnostics as cd','cd.customer_id','c.id')
                        ->JOIN('diagnostics as d','cd.diagnostic_id','d.id')
                        ->JOIN('labs as l','cd.lab_id','l.id')
                        ->WHERE(['c.organization_id' => $org_id])
                        ->WHERE('appointment_date','>',$endOfDay)
                        ->where('c.deleted_at',null)
                        ->select('c.*','cd.lab_id','l.name as lab_name',DB::raw('GROUP_CONCAT(cd.diagnostic_id) as diagnostics_id'),DB::raw('GROUP_CONCAT(d.name) as diagnostic_name'),'cd.appointment_date')
                        ->groupBy('cd.lab_id')
                        ->get();
        $message = "Upcoming Diagnostics";
        return view('orgpanel.employee_diagnostic.employee_diagnostic',compact(['employees','message']));
    }
    public function today()                                              // function to get today appointment of clinic
    {
        $startOfDay = Carbon::now()->startOfDay()->toDateTimeString();   //today start time and date
        $endOfDay = Carbon::now()->endOfDay()->toDateTimeString();       //today end time and date
        $org_id     = Auth::user()->organization_id;
        $employees        =   DB::table('customers as c')
                    ->JOIN('customer_diagnostics as cd','cd.customer_id','c.id')
                    ->JOIN('diagnostics as d','cd.diagnostic_id','d.id')
                    ->JOIN('labs as l','cd.lab_id','l.id')
                    ->WHERE(['c.organization_id' => $org_id])
                    ->whereBetween('appointment_date',[$startOfDay,$endOfDay])
                    ->where('c.deleted_at',null)
                    ->select('c.*','cd.lab_id','l.name as lab_name',DB::raw('GROUP_CONCAT(cd.diagnostic_id) as diagnostics_id'),DB::raw('GROUP_CONCAT(d.name) as diagnostic_name'),'cd.appointment_date')
                    ->groupBy('cd.lab_id')
                    ->get();
        $message = "Today Diagnostics";
        return view('orgpanel.employee_diagnostic.employee_diagnostic',compact(['employees','message']));
    }
    public function previous()                                          // function to get previous appointment of clinic
     {
        $startOfDay = Carbon::now()->startOfDay()->toDateTimeString();  //today start time and date
        $org_id         = Auth::user()->organization_id;
        $employees      = DB::table('customers as c')
                        ->JOIN('customer_diagnostics as cd','cd.customer_id','c.id')
                        ->JOIN('diagnostics as d','cd.diagnostic_id','d.id')
                        ->JOIN('labs as l','cd.lab_id','l.id')
                        ->WHERE(['c.organization_id' => $org_id])
                        ->WHERE('appointment_date','<',$startOfDay)
                        ->where('c.deleted_at',null)
                        ->select('c.*','cd.lab_id','l.name as lab_name',DB::raw('GROUP_CONCAT(cd.diagnostic_id) as diagnostics_id'),DB::raw('GROUP_CONCAT(d.name) as diagnostic_name'),'cd.appointment_date')
                        ->groupBy('cd.lab_id')
                        ->get();
        $message = "Previous Diagnostics";
        return view('orgpanel.employee_diagnostic.employee_diagnostic',compact(['employees','message']));
    }

}
