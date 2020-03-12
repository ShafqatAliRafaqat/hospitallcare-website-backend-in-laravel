<?php

namespace App\Http\Controllers\AdminControllers;

use App\Exports\ByCenterExport;
use App\Exports\ByStatusExport;
use App\Exports\ByUserExport;
use App\Exports\CustomersExport;
use App\Exports\ByOrganizationExport;
use App\Exports\ByDoctorExport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
     /**
    * @return \Illuminate\Support\Collection
    */
    // public function importExportView()
    // {
    //    return view('import');
    // }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function export(Request $request)
    {
        // Export By ID of User/Admin/Coordinator
        $id = $request->patient_coordinator_id;
        $name = userName($id);
        // return Excel::download(new CustomersExport(), 'customers.xlsx');
        $exporter = app()->makeWith(ByUserExport::class, compact('request'));
        return $exporter->download($name.'\'s Patients.xlsx');
    }
    public function exportToExcel(Request $request)
    {
        // Export by Date only
        $exporter = app()->makeWith(CustomersExport::class, compact('request'));
        return $exporter->download('Customers.xlsx');
    }
    public function exportbystatus(Request $request)
    {
        // Export by Status ID and Date Range
        $id = $request->status_id;
        $statusName = statusName($id);
        $now    =   Carbon::now()->toDateString();
        $exporter   = app()->makeWith(ByStatusExport::class, compact('request'));
        return $exporter->download($statusName.' Leads '.$now.'.xlsx');
    }

    public function exportbycenter(Request $request)
    {
        if ( Auth::user()->can('excel_report') ) {
        $now    =   Carbon::now()->toDateString();
        $exporter   = app()->makeWith(ByCenterExport::class, compact('request'));
        return $exporter->download('Leads '.$now.'.xlsx');
        } else {
          abort(403);
      }
    }
    public function exportbyOrganization(Request $request)
    {
        if ( Auth::user()->can('excel_report') ) {
            $now    =   Carbon::now()->toDateString();
            $exporter   = app()->makeWith(ByOrganizationExport::class, compact('request'));
            return $exporter->download('OrganizationalLeads '.$now.'.xlsx');
        } else {
          abort(403);
        }
    }

    public function exportbyDoctor(Request $request)
    {
        if ( Auth::user()->can('excel_report') ) {
            $now    =   Carbon::now()->toDateString();
            $exporter   = app()->makeWith(ByDoctorExport::class, compact('request'));
            return $exporter->download("List Of Doctors ".$now.".xlsx");
        } else {
          abort(403);
        }
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function import()
    // {
    //     Excel::import(new UsersImport,request()->file('file'));

    //     return back();
    // }
}
