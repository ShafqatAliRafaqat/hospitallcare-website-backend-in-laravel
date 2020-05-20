<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Center;
use App\Models\Admin\Customer;
use App\Models\Admin\Doctor;
use App\Models\Admin\DoctorImage;
use App\Models\Admin\Status;
use App\Models\Admin\TempCustomer;
use App\Models\Admin\Treatment;
use App\Organization;
use App\Models\Admin\UserImage;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if ( Auth::user()->hasRole('coordinator') || Auth::user()->hasRole('coordinator_plus') ) {

                $stats  = DB::  table('status as s')
                                ->selectRaw('s.id,s.name, count(c.status_id) as total')
                                ->leftjoin('customers as c','s.id','c.status_id')
                                ->groupby('s.id','s.name')
                                ->get();
        //Today Stats for Coodinator and for Coodinator _plus

                $updated        = DB:: table('customers')->whereRaw('Date(updated_at) = CURDATE()')->whereRaw('Date(created_at) != Date(updated_at)')->count();
                $created        = DB:: table('customers')->whereRaw('Date(created_at) = CURDATE()')->whereRaw('Date(created_at) = Date(updated_at)')->count();
                $total_today    = DB:: table('customers')->whereRaw('Date(created_at) = CURDATE() OR Date(updated_at) = CURDATE()')->count();

                //Stats of Patient Owners
                $owner_status        = DB::table('customers as c')
                                    ->Join('users as u','c.patient_coordinator_id','u.id')
                                    ->whereRaw('Date(c.created_at) = CURDATE()')
                                    ->orWhereRaw('Date(c.updated_at) = CURDATE()')
                                    ->groupby('c.patient_coordinator_id')
                                    ->selectRaw("u.name,count('c.*') as counts")
                                    ->get();
                //Total Updates and Creates by Owners
                $sum_of_owners = 0;
                foreach ($owner_status as $os) {
                    $sum_of_owners      =   $os->counts + $sum_of_owners;
                }

                //Pervious Week Stats for Coodinator and for Coodinator _plus

                Carbon  ::setWeekStartsAt(Carbon::SUNDAY);
                $currentDate    = Carbon::now();
                $agoDate        = $currentDate->copy()->subDays($currentDate->dayOfWeek)->subWeek()->setTime(23, 59, 59);
                $now            = Carbon::now()->startOfWeek();

                $created_previous_week  = DB::table('customers')->whereBetween('created_at', array($agoDate, $now))->whereRaw('Date(created_at) = Date(updated_at)')->count();
                $updated_previous_week  = DB::table('customers')->whereBetween('updated_at', array($agoDate, $now))->whereRaw('Date(created_at) != Date(updated_at)')->count();
                $total_previous_week    = DB::table('customers')->whereBetween('created_at', array($agoDate, $now))->OrwhereBetween('updated_at', array($agoDate, $now))->count();

        //This Week Stats for Coodinator and for Coodinator _plus

                Carbon::setWeekStartsAt(Carbon::MONDAY);
                $created_this_week      = DB::table('customers')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->whereRaw('Date(created_at) = Date(updated_at)')->count();
                $updated_this_week      = DB::table('customers')->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->whereRaw('Date(created_at) != Date(updated_at)')->count();
                $total_this_week        = DB::table('customers')->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->OrwhereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

        //This month Stats for Coodinator and for Coodinator _plus

                $created_this_month     = DB::table('customers')->where('created_at', '>=', Carbon::now()->startOfMonth()->toDateString())->whereRaw('Date(created_at) = Date(updated_at)')->count();
                $updated_this_month     = DB::table('customers')->where('updated_at', '>=', Carbon::now()->startOfMonth()->toDateString())->whereRaw('Date(created_at) != Date(updated_at)')->count();
                $total_this_month       = DB::table('customers')->where('created_at', '>=', Carbon::now()->startOfMonth()->toDateString())->Orwhere('updated_at', '>=', Carbon::now()->startOfMonth()->toDateString())->count();

        //This Year Stats for Coodinator and for Coodinator _plus

                $now = Carbon::now();
                $this_year = $now->year;
                $created_this_year      = DB::table('customers')->whereRaw("YEAR(created_at) = $this_year")->whereRaw('Date(created_at) = Date(updated_at)')->count();
                $updated_this_year      = DB::table('customers')->whereRaw("YEAR(updated_at) = $this_year")->whereRaw('Date(created_at) != Date(updated_at)')->count();
                $total_this_year        = DB::table('customers')->whereYear('created_at', $this_year)->OrwhereYear('updated_at', $this_year)->count();


                $status         = DB::table('status')->get();

                $customers      = DB::table('customers as c')
                                        ->join('status as s','s.id','c.status_id')
                                        ->leftjoin('customer_attachements as a','a.customer_id','c.id')
                                        ->select('c.*','s.name as status','a.attachment')
                                        ->get();
                $users          = DB::table('role_user as ru')
                                ->join('users as u','ru.user_id','u.id')
                                ->where('ru.role_id',6)
                                ->OrWhere('ru.role_id',1)
                                ->select('ru.role_id','ru.user_id','u.name')
                                ->orderBy('u.name','ASC')
                                ->get();
                $centers        =  Center::select('id','center_name')->orderBy('center_name','ASC')->get();
                $treatments     = Treatment::select('id','name')->whereNotNull('parent_id')->orderBy('name','ASC')->get();
                $website_leads  = DB::table('customer_procedures as cp')
                                    ->join('customers as c','c.id','cp.customer_id')
                                    ->Where('cp.status',4)
                                    ->count();
                $web_diagnostic =  DB::table('customer_diagnostics as cd')
                                        ->join('customers as c','c.id','cd.customer_id')
                                        ->Where('cd.status',4)
                                        ->groupBy('cd.lab_id','c.id','cd.appointment_date')
                                        ->count();
                $facebook_leads     =   TempCustomer::count(); //Total Customers in Temp_Customers Table
                $total_leads        =   $website_leads + $facebook_leads + $web_diagnostic; 

                $total_customers    =   $customers->count(); //Total customers in Customers Table
                $sum_all_customers  =   $total_customers + $facebook_leads; //Sum of All Customers
                $total_centers      =   $centers->count(); //Total Centers
                $total_doctors      =   Doctor::count();

        return view('adminpanel.home', compact('stats','customers','updated','created','created_this_week','updated_this_week','created_previous_week','updated_previous_week','created_this_month','updated_this_month','created_this_year','updated_this_year','total_today','total_previous_week','total_this_week','total_this_month','total_this_year','users','status','centers','treatments','total_customers','total_leads','sum_all_customers','total_centers','total_doctors','owner_status','sum_of_owners'));

        } elseif( Auth::user()->hasRole('admin') ) {

                $stats  = DB::table('status as s')
                                ->selectRaw('s.id,s.name, count(c.status_id) as total')
                                ->leftjoin('customers as c','s.id','c.status_id')
                                ->groupby('s.id','s.name')
                                ->get();

                //Today Stats for Admin
                $updated        = DB::table('customers')->whereRaw('Date(updated_at) = CURDATE()')->whereRaw('Date(created_at) != Date(updated_at)')->count();
                $created        = DB::table('customers')->whereRaw('Date(created_at) = CURDATE()')->whereRaw('Date(created_at) = Date(updated_at)')->count();
                $total_today    = DB::table('customers')->whereRaw('Date(created_at) = CURDATE() OR Date(updated_at) = CURDATE()')->count();

                //Stats of Patient Owners
                $owner_status   = DB::table('coordinator_performance as cp')
                                    ->Join('users as u','cp.owner_id','u.id')
                                    ->WhereRaw('Date(cp.created) = CURDATE()')
                                    ->orWhereRaw('Date(cp.updated) = CURDATE()')
                                    ->groupby('cp.owner_id')
                                    ->selectRaw("u.name,count('cp.*') as count, count(cp.created) as total_created,count(cp.updated) as total_updated")
                                    ->get();
                //Pervious week Stats for Admin

                Carbon::setWeekStartsAt(Carbon::SUNDAY);
                $currentDate    = Carbon::now();
                $agoDate        = $currentDate->copy()->subDays($currentDate->dayOfWeek)->subWeek()->setTime(23, 59, 59);
                $now            = Carbon::now()->startOfWeek();

                $created_previous_week  = DB::table('customers')->whereBetween('created_at', array($agoDate, $now))->whereRaw('Date(created_at) = Date(updated_at)')->count();
                $updated_previous_week  = DB::table('customers')->whereBetween('updated_at', array($agoDate, $now))->whereRaw('Date(created_at) != Date(updated_at)')->count();
                $total_previous_week    = DB::table('customers')->whereBetween('created_at', array($agoDate, $now))->OrwhereBetween('updated_at', array($agoDate, $now))->count();

                //This Week Stats for Admin

                Carbon::setWeekStartsAt(Carbon::MONDAY);
                $created_this_week      = DB::table('customers')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->whereRaw('Date(created_at) = Date(updated_at)')->count();
                $updated_this_week      = DB::table('customers')->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->whereRaw('Date(created_at) != Date(updated_at)')->count();
                $total_this_week        = DB::table('customers')->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->OrwhereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

        //This Month Stats for Admin

                $created_this_month     = DB::table('customers')->where('created_at', '>=', Carbon::now()->startOfMonth()->toDateString())->whereRaw('Date(created_at) = Date(updated_at)')->count();
                $updated_this_month     = DB::table('customers')->where('updated_at', '>=', Carbon::now()->startOfMonth()->toDateString())->whereRaw('Date(created_at) != Date(updated_at)')->count();
                $total_this_month       = DB::table('customers')->where('created_at', '>=', Carbon::now()->startOfMonth()->toDateString())->Orwhere('updated_at', '>=', Carbon::now()->startOfMonth()->toDateString())->count();

        //This Year Stats for Admin

                $now = Carbon::now();
                $this_year = $now->year;
                $created_this_year      = DB::table('customers')->whereRaw("YEAR(created_at) = $this_year")->whereRaw('Date(created_at) = Date(updated_at)')->count();
                $updated_this_year      = DB::table('customers')->whereRaw("YEAR(updated_at) = $this_year")->whereRaw('Date(created_at) != Date(updated_at)')->count();
                $total_this_year        = DB::table('customers')->whereYear('created_at', $this_year)->OrwhereYear('updated_at', $this_year)->count();


                $status             = DB::table('status')->get();
                $customers          = DB::table('customers as c')
                                    ->join('status as s','s.id','c.status_id')
                                    ->leftjoin('customer_attachements as a','a.customer_id','c.id')
                                    ->select('c.*','s.name as status','a.attachment')
                                    ->get();
                $users              = DB::table('role_user as ru')
                                    ->join('users as u','ru.user_id','u.id')
                                    ->where('ru.role_id',6)
                                    ->OrWhere('ru.role_id',1)
                                    ->select('ru.role_id','ru.user_id','u.name')
                                    ->orderBy('u.name','ASC')
                                    ->get();
                $centers            =   Center::select('id','center_name')->orderBy('center_name','ASC')->get();
                $treatments         =   Treatment::select('id','name')->whereNotNull('parent_id')->orderBy('name','ASC')->get();
                $specializations    =   Treatment::select('id','name')->whereNull('parent_id')->orderBy('name','ASC')->get();
                $organizations      =   Organization::orderBy('name','ASC')->get();
                $website_leads  = DB::table('customer_procedures as cp')
                                    ->join('customers as c','c.id','cp.customer_id')
                                    ->Where('cp.status',4)
                                    ->count();
                $web_diagnostic =  DB::table('customer_diagnostics as cd')
                                        ->join('customers as c','c.id','cd.customer_id')
                                        ->Where('cd.status',4)
                                        ->groupBy('cd.lab_id','c.id','cd.appointment_date')
                                        ->count();
                $facebook_leads     =   TempCustomer::count(); //Total Customers in Temp_Customers Table
                $total_leads        =   $website_leads + $facebook_leads + $web_diagnostic;
                $total_customers    =   $customers->count(); //Total customers in Customers Table
                $sum_all_customers  =   $total_customers + $facebook_leads; //Sum of All Customers
                $total_centers      =   $centers->count(); //Total Centers
                $total_doctors      =   Doctor::count();
                $cities             =   DB::table('cities_of_pak')->select('id','name')->orderBy('name','ASC')->get();
        return view('adminpanel.home', compact('stats','customers','updated','created','created_this_week','updated_this_week',
        'created_previous_week','updated_previous_week','created_this_month','updated_this_month','created_this_year',
        'updated_this_year','total_today','total_previous_week','total_this_week','total_this_month','total_this_year','users',
        'status','centers','treatments','organizations','total_customers','total_leads','sum_all_customers','total_centers',
        'total_doctors','owner_status','specializations','cities'));
        } elseif( Auth::user()->hasRole('organization_admin') ) {

                $organization_id    = Auth::user()->organization_id;
                $startOfDay         = Carbon::now()->startOfDay()->toDateTimeString();                                  // Date and time of start of today
                $endOfDay           = Carbon::now()->startOfDay()->addDays(1)->toDateTimeString();                      // Date and time of start of next day
                Carbon::setWeekStartsAt(Carbon::SUNDAY);
                Carbon::setWeekStartsAt(Carbon::MONDAY);
                $startOfWeek        = Carbon::now()->startOfWeek()->toDateTimeString();                                 // Date and time of start of week
                $endOfWeek          = Carbon::now()->endOfWeek()->toDateTimeString();                                   // Date and time of end of week
                $startOfPreviousWeek= Carbon::now()->subWeek()->startOfWeek()->toDateTimeString();                      // Date and time of start of pervious week
                $endOfPreviousWeek  = Carbon::now()->subWeek()->endOfWeek()->toDateTimeString();                        // Date and time of end of pervious week
                $endOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth()->toDateTimeString();                      // Date and time of end of pervious month
                $startOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth()->toDateTimeString();                  // Date and time of start of pervious month
                $startOfMonth       = Carbon::now()->startOfMonth()->toDateTimeString();                                // Date and time of start of month
                $endOfMonth         = Carbon::now()->endOfMonth()->toDateTimeString();                                  // Date and time of end of month
                $startOfYear        = Carbon::now()->startOfYear()->toDateTimeString();                                 // Date and time of start of year
                $endOfYear          = Carbon::now()->endOfYear()->toDateTimeString();                                   // Date and time of end of year

                $customers     = Customer::where('organization_id',$organization_id)->get();

                $status6= ["status_id" => 6 ,"organization_id"=>$organization_id ];
                $status5= ["status_id" => 5 ,"organization_id"=>$organization_id ];
                $status4= ["status_id" => 4 ,"organization_id"=>$organization_id ];
                $status3= ["status_id" => 3 ,"organization_id"=>$organization_id ];
                $status2= ["status_id" => 2 ,"organization_id"=>$organization_id ];
                $status1= ["status_id" => 1 ,"organization_id"=>$organization_id ];
            // Diagnostics Chat
                $numberofemployees = count($customers) ;
                foreach( $customers as $c){
                    // dd($c);
                    $cd = DB::table('customers as c')
                        ->join('customer_diagnostic_history as cd','cd.customer_id','c.id')
                        ->orwhere('cd.customer_id',$c->id)
                        ->orwhere('c.parent_id',$c->id)
                        ->select('c.id','cd.id as cdh','cd.cost','cd.discount_per','cd.discounted_cost')
                        ->get();

                    if (count($cd)>0){
                        foreach($cd as $c){
                            $customer_diagnostics[] = $c;
                        }
                    }
                }
                foreach( $customers as $c){
                    $cd = DB::table('customers as c')
                        ->join('customer_diagnostics as cd','cd.customer_id','c.id')
                        ->orwhere('cd.customer_id',$c->id)
                        ->orwhere('c.parent_id',$c->id)
                        ->select('c.id','cd.id as cd','cd.cost','cd.discount_per','cd.discounted_cost')
                        ->get();
                    if (count($cd)>0){
                        foreach($cd as $c){
                            $customer_diagnostics[] = $c;
                        }
                    }else{
                        $customer_diagnostics =null;
                    }
                }

                // Treatment Stats
                foreach( $customers as $c){
                    $cd = DB::table('customers as c')
                        ->join('customer_treatment_history as cd','cd.customer_id','c.id')
                        ->orwhere('cd.customer_id',$c->id)
                        ->orwhere('c.parent_id',$c->id)
                        ->select('cd.cost','cd.discount_per','cd.discounted_cost')
                        ->get();
                    if (count($cd)>0){
                        foreach($cd as $c){
                            $customer_treatments[] = $c;
                        }
                    }
                }
                // Treatment Stats
                foreach( $customers as $c){
                    $cd = DB::table('customers as c')
                        ->join('customer_procedures as cd','cd.customer_id','c.id')
                        ->orwhere('cd.customer_id',$c->id)
                        ->orwhere('c.parent_id',$c->id)
                        ->select('cd.cost','cd.discount_per','cd.discounted_cost')
                        ->get();
                    if (count($cd)>0){
                        foreach($cd as $c){
                            $customer_treatments[] = $c;
                        }
                    }else{
                        $customer_treatments =null;
                    }
                }
        //today stats for organization

                $todayDontCall = Customer::where($status6)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
                $todayNoContact= Customer::where($status5)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
                $todayCustomer = Customer::where($status4)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
                $todayHot      = Customer::where($status3)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
                $todayWarm     = Customer::where($status2)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
                $todayCold     = Customer::where($status1)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
                $total_today   = Customer::where("organization_id",$organization_id)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();

        //Previous Week stats for organization

                $previousWeekDontCall = Customer::where($status6)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
                $previousWeekNoContact= Customer::where($status5)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
                $previousWeekCustomer = Customer::where($status4)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
                $previousWeekHot      = Customer::where($status3)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
                $previousWeekWarm     = Customer::where($status2)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
                $previousWeekCold     = Customer::where($status1)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
                $total_previous_week  = Customer::where("organization_id",$organization_id)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();

        // This Week stats for organization

                $thisWeekDontCall = Customer::where($status6)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
                $thisWeekNoContact= Customer::where($status5)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
                $thisWeekCustomer = Customer::where($status4)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
                $thisWeekHot      = Customer::where($status3)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
                $thisWeekWarm     = Customer::where($status2)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
                $thisWeekCold     = Customer::where($status1)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
                $total_this_week  = Customer::where("organization_id",$organization_id)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();

        // This Month stats for organization

                $thisMonthDontCall = Customer::where($status6)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
                $thisMonthNoContact= Customer::where($status5)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
                $thisMonthCustomer = Customer::where($status4)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
                $thisMonthHot      = Customer::where($status3)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
                $thisMonthWarm     = Customer::where($status2)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
                $thisMonthCold     = Customer::where($status1)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
                $total_this_month  = Customer::where("organization_id",$organization_id)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();

        // This Year stats for organization

                $thisYearDontCall = Customer::where($status6)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
                $thisYearNoContact= Customer::where($status5)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
                $thisYearCustomer = Customer::where($status4)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
                $thisYearHot      = Customer::where($status3)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
                $thisYearWarm     = Customer::where($status2)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
                $thisYearCold     = Customer::where($status1)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
                $total_this_year  = Customer::where("organization_id",$organization_id)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();

                $active_employees = Customer::where("organization_id",$organization_id)->where('org_verified', 1)->count();
                $pending_employees= Customer::where("organization_id",$organization_id)->where('org_verified', 0)->count();
                
                $pending_claims =   DB::table('customers as c')
                                    ->join('customer_claims as cc','c.id','cc.customer_id')
                                    ->join('customers as cn','cn.id','cc.appointment_for')
                                    ->where('c.organization_id',$organization_id)
                                    ->where('cc.status',0)
                                    ->whereNull('cc.deleted_at')
                                    ->count();
                $active_claims  =   DB::table('customers as c')
                                    ->join('customer_claims as cc','c.id','cc.customer_id')
                                    ->join('customers as cn','cn.id','cc.appointment_for')
                                    ->where('c.organization_id',$organization_id)
                                    ->where('cc.status',1)
                                    ->whereNull('cc.deleted_at')
                                    ->count();
                $decline_claims =   DB::table('customers as c')
                                    ->join('customer_claims as cc','c.id','cc.customer_id')
                                    ->join('customers as cn','cn.id','cc.appointment_for')
                                    ->where('c.organization_id',$organization_id)
                                    ->where('cc.status',2)
                                    ->whereNull('cc.deleted_at')
                                    ->count();
                $hold_claims    =   DB::table('customers as c')
                                    ->join('customer_claims as cc','c.id','cc.customer_id')
                                    ->join('customers as cn','cn.id','cc.appointment_for')
                                    ->where('c.organization_id',$organization_id)
                                    ->where('cc.status',3)
                                    ->whereNull('cc.deleted_at')
                                    ->count();
        return view('orgpanel.home',compact('thisYearDontCall','thisYearNoContact','thisYearCustomer','thisYearHot','thisYearWarm','thisYearCold','total_this_year',
                'thisMonthDontCall','thisMonthNoContact','thisMonthCustomer','thisMonthHot','thisMonthWarm','thisMonthCold','total_this_month',
                'thisWeekDontCall','thisWeekNoContact','thisWeekCustomer','thisWeekHot','thisWeekWarm','thisWeekCold','total_this_week',
                'previousWeekDontCall','previousWeekNoContact','previousWeekCustomer','previousWeekHot','previousWeekWarm','previousWeekCold','total_previous_week',
                'todayDontCall','todayNoContact','todayCustomer','todayHot','todayWarm','todayCold','total_today',
                'customer_diagnostics','numberofemployees','customer_treatments','active_employees','pending_employees','active_claims','pending_claims',
                'decline_claims','hold_claims'
        ));
        }elseif( Auth::user()->hasRole('center_admin') ) {

                $medical_center_id = Auth::user()->medical_center_id;
                $startOfDay = Carbon::now()->startOfDay()->toDateTimeString();                                  // Date and time of start of today
                $endOfDay = Carbon::now()->startOfDay()->addDays(1)->toDateTimeString();                        // Date and time of start of next day
                Carbon::setWeekStartsAt(Carbon::SUNDAY);
                Carbon::setWeekStartsAt(Carbon::MONDAY);
                $startOfWeek = Carbon::now()->startOfWeek()->toDateTimeString();                                // Date and time of start of week
                $endOfWeek = Carbon::now()->endOfWeek()->toDateTimeString();                                    // Date and time of end of week
                $startOfPreviousWeek = Carbon::now()->subWeek()->startOfWeek()->toDateTimeString();             // Date and time of start of pervious week
                $endOfPreviousWeek = Carbon::now()->subWeek()->endOfWeek()->toDateTimeString();                 // Date and time of end of pervious week
                $endOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth()->toDateTimeString();              // Date and time of end of pervious month
                $startOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth()->toDateTimeString();          // Date and time of start of pervious month
                $startOfMonth = Carbon::now()->startOfMonth()->toDateTimeString();                              // Date and time of start of month
                $endOfMonth = Carbon::now()->endOfMonth()->toDateTimeString();                                  // Date and time of end of month
                $startOfYear = Carbon::now()->startOfYear()->toDateTimeString();                                // Date and time of start of year
                $endOfYear = Carbon::now()->endOfYear()->toDateTimeString();                                    // Date and time of end of year

                $center = Center:: where('id',$medical_center_id)->with('customer')->first();

        // Today Stats for Medical Center

                $todayDontCall = $center->customer->where('status_id',6)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
                $todayNoContact= $center->customer->where('status_id',5)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
                $todayCustomer = $center->customer->where('status_id',4)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
                $todayHot      = $center->customer->where('status_id',3)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
                $todayWarm     = $center->customer->where('status_id',2)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
                $todayCold     = $center->customer->where('status_id',1)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
                $total_today   = $center->customer->where('status_id','<',5)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();

        //Previous Week Stats for Medical Center

                $previousWeekDontCall = $center->customer->where('status_id',6)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
                $previousWeekNoContact= $center->customer->where('status_id',5)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
                $previousWeekCustomer = $center->customer->where('status_id',4)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
                $previousWeekHot      = $center->customer->where('status_id',3)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
                $previousWeekWarm     = $center->customer->where('status_id',2)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
                $previousWeekCold     = $center->customer->where('status_id',1)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
                $total_previous_week  = $center->customer->where('status_id','<',5)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();

        // This Week Stats for Medical Center

                $thisWeekDontCall = $center->customer->where('status_id',6)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
                $thisWeekNoContact= $center->customer->where('status_id',5)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
                $thisWeekCustomer = $center->customer->where('status_id',4)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
                $thisWeekHot      = $center->customer->where('status_id',3)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
                $thisWeekWarm     = $center->customer->where('status_id',2)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
                $thisWeekCold     = $center->customer->where('status_id',1)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
                $total_this_week  = $center->customer->where('status_id','<',5)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();

        // This Month  Stats for Medical Center

                $thisMonthDontCall = $center->customer->where('status_id',6)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
                $thisMonthNoContact= $center->customer->where('status_id',5)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
                $thisMonthCustomer = $center->customer->where('status_id',4)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
                $thisMonthHot      = $center->customer->where('status_id',3)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
                $thisMonthWarm     = $center->customer->where('status_id',2)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
                $thisMonthCold     = $center->customer->where('status_id',1)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
                $total_this_month  = $center->customer->where('status_id','<',5)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();

        // This Year Stats for Medical Center

                $thisYearDontCall = $center->customer->where('status_id',6)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
                $thisYearNoContact= $center->customer->where('status_id',5)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
                $thisYearCustomer = $center->customer->where('status_id',4)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
                $thisYearHot      = $center->customer->where('status_id',3)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
                $thisYearWarm     = $center->customer->where('status_id',2)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
                $thisYearCold     = $center->customer->where('status_id',1)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
                $total_this_year  = $center->customer->where('status_id','<',5)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();

                $status           = DB::table('status')->limit(4)->get();
                $centers          = Center::select('id','center_name')->get();

        return view('centerpanel.home',compact('thisYearDontCall','thisYearNoContact','thisYearCustomer','thisYearHot','thisYearWarm','thisYearCold','total_this_year',
                'thisMonthDontCall','thisMonthNoContact','thisMonthCustomer','thisMonthHot','thisMonthWarm','thisMonthCold','total_this_month',
                'thisWeekDontCall','thisWeekNoContact','thisWeekCustomer','thisWeekHot','thisWeekWarm','thisWeekCold','total_this_week',
                'previousWeekDontCall','previousWeekNoContact','previousWeekCustomer','previousWeekHot','previousWeekWarm','previousWeekCold','total_previous_week',
                'todayDontCall','todayNoContact','todayCustomer','todayHot','todayWarm','todayCold','total_today','status','centers'

        ));
        }
        elseif( Auth::user()->hasRole('doctor_admin') ) {
            $doctor_id      =   Auth::user()->doctor_id;
            $doctor         =   Doctor::where('id',$doctor_id)->first();
            if($doctor->profile_status == 2){
                $image          =   DoctorImage::where('doctor_id',$doctor_id)->first();
                return redirect()->route('doctorschedule.index');
            }
            $startOfDay = Carbon::now()->startOfDay()->toDateTimeString();                                  // Date and time of start of today
            $endOfDay = Carbon::now()->startOfDay()->addDays(1)->toDateTimeString();                        // Date and time of start of next day
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            Carbon::setWeekStartsAt(Carbon::MONDAY);
            $startOfWeek = Carbon::now()->startOfWeek()->toDateTimeString();                                // Date and time of start of week
            $endOfWeek = Carbon::now()->endOfWeek()->toDateTimeString();                                    // Date and time of end of week
            $startOfPreviousWeek = Carbon::now()->subWeek()->startOfWeek()->toDateTimeString();             // Date and time of start of pervious week
            $endOfPreviousWeek = Carbon::now()->subWeek()->endOfWeek()->toDateTimeString();                 // Date and time of end of pervious week
            $endOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth()->toDateTimeString();              // Date and time of end of pervious month
            $startOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth()->toDateTimeString();          // Date and time of start of pervious month
            $startOfMonth = Carbon::now()->startOfMonth()->toDateTimeString();                              // Date and time of start of month
            $endOfMonth = Carbon::now()->endOfMonth()->toDateTimeString();                                  // Date and time of end of month
            $startOfYear = Carbon::now()->startOfYear()->toDateTimeString();                                // Date and time of start of year
            $endOfYear = Carbon::now()->endOfYear()->toDateTimeString();                                    // Date and time of end of year
            $doctor = Doctor::where('id',$doctor_id)->with('customers')->first();
            // dd($doctor);
            // Today Stats for Medical Center

            $todayDontCall = $doctor->customers->where('status_id',6)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
            $todayNoContact= $doctor->customers->where('status_id',5)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
            $todayCustomer = $doctor->customers->where('status_id',4)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
            $todayHot      = $doctor->customers->where('status_id',3)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
            $todayWarm     = $doctor->customers->where('status_id',2)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
            $todayCold     = $doctor->customers->where('status_id',1)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();
            $total_today   = $doctor->customers->where('status_id','<',5)->whereBetween('updated_at',array($startOfDay,$endOfDay))->count();

            //Previous Week Stats for Medical Center

            $previousWeekDontCall = $doctor->customers->where('status_id',6)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
            $previousWeekNoContact= $doctor->customers->where('status_id',5)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
            $previousWeekCustomer = $doctor->customers->where('status_id',4)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
            $previousWeekHot      = $doctor->customers->where('status_id',3)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
            $previousWeekWarm     = $doctor->customers->where('status_id',2)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
            $previousWeekCold     = $doctor->customers->where('status_id',1)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();
            $total_previous_week  = $doctor->customers->where('status_id','<',5)->whereBetween('updated_at', array($startOfPreviousWeek, $endOfPreviousWeek))->count();

            // This Week Stats for Medical Center

            $thisWeekDontCall = $doctor->customers->where('status_id',6)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
            $thisWeekNoContact= $doctor->customers->where('status_id',5)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
            $thisWeekCustomer = $doctor->customers->where('status_id',4)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
            $thisWeekHot      = $doctor->customers->where('status_id',3)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
            $thisWeekWarm     = $doctor->customers->where('status_id',2)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
            $thisWeekCold     = $doctor->customers->where('status_id',1)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();
            $total_this_week  = $doctor->customers->where('status_id','<',5)->whereBetween('updated_at', array($startOfWeek, $endOfWeek))->count();

            // This Month  Stats for Medical Center

            $thisMonthDontCall = $doctor->customers->where('status_id',6)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
            $thisMonthNoContact= $doctor->customers->where('status_id',5)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
            $thisMonthCustomer = $doctor->customers->where('status_id',4)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
            $thisMonthHot      = $doctor->customers->where('status_id',3)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
            $thisMonthWarm     = $doctor->customers->where('status_id',2)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
            $thisMonthCold     = $doctor->customers->where('status_id',1)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();
            $total_this_month  = $doctor->customers->where('status_id','<',5)->whereBetween('updated_at', array($startOfMonth, $endOfMonth))->count();

            // This Year Stats for Medical Center

            $thisYearDontCall = $doctor->customers->where('status_id',6)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
            $thisYearNoContact= $doctor->customers->where('status_id',5)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
            $thisYearCustomer = $doctor->customers->where('status_id',4)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
            $thisYearHot      = $doctor->customers->where('status_id',3)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
            $thisYearWarm     = $doctor->customers->where('status_id',2)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
            $thisYearCold     = $doctor->customers->where('status_id',1)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();
            $total_this_year  = $doctor->customers->where('status_id','<',5)->whereBetween('updated_at', array($startOfYear, $endOfYear))->count();

            $status           = DB::table('status')->limit(4)->get();
            $doctors          = Center::select('id','center_name')->get();

        return view('doctorpanel.home',compact('thisYearDontCall','thisYearNoContact','thisYearCustomer','thisYearHot','thisYearWarm','thisYearCold','total_this_year',
                'thisMonthDontCall','thisMonthNoContact','thisMonthCustomer','thisMonthHot','thisMonthWarm','thisMonthCold','total_this_month',
                'thisWeekDontCall','thisWeekNoContact','thisWeekCustomer','thisWeekHot','thisWeekWarm','thisWeekCold','total_this_week',
                'previousWeekDontCall','previousWeekNoContact','previousWeekCustomer','previousWeekHot','previousWeekWarm','previousWeekCold','total_previous_week',
                'todayDontCall','todayNoContact','todayCustomer','todayHot','todayWarm','todayCold','total_today','status',));
        } else if (Auth::user()->hasRole('doctor_profile')) {
            $doctor_id      =   Auth::user()->doctor_id;
            $doctor         =   Doctor::where('id',$doctor_id)->first();
            if ($doctor->profile_status == 0) {
                $image          =   DoctorImage::where('doctor_id',$doctor_id)->first();
                return view('doctorpanel.new_profile_making.general_info',compact('doctor','image'));
            } else if($doctor->profile_status == 1){
                return redirect()->route('doctor_edit_specialization');
            } else if($doctor->profile_status == 2){
                return redirect()->route('doctor_edit_schedules');
            }
        } else {
            return view('adminpanel.home');
        }
    }
}
