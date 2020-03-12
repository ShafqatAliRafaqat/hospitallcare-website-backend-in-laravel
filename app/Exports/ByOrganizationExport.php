<?php

namespace App\Exports;

use App\Models\Admin\Center;
use App\Models\Admin\Customer;
use App\Organization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ByOrganizationExport implements FromCollection,WithHeadings,WithEvents,ShouldAutoSize
{
    use Exportable;
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $request        =   $this->request;
        $start          =   $request->start_date;
        $ending         =   $request->end_date;
        $end            =   Carbon::parse($ending)->addDay(1);
        // When No options are selected. Employees would be shown from given range
        if($request->organization_id==NULL){
        $customers  =   Customer::whereBetween('created_at', array($start, $end))
                    ->OrwhereBetween('updated_at', array($start, $end))
                    ->select('id','name','phone','address','patient_coordinator_id','status_id','notes')
                    ->get();
        $statuses   =   DB::table('status')->get();
        foreach ($customers as $customer) {
            foreach ($statuses as $status) {
                if($status->id == $customer['status_id']){
                    $customer['status_id']     =   $status->name;
                }
            }
            $users      =   Auth::user()->find($customer->patient_coordinator_id);
            $customer['patient_coordinator_id']     =   $users->name;
            $customer['notes']                      =   strip_tags($customer->notes);
            $customer_details                       =   TreatmentsAndCenters($customer->id);
            $customer['procedures']                 =   $customer_details->treatment;
            $customer['centers']                    =   $customer_details->center_name;
            $customer['doctors']                    =   $customer_details->doctor_name;
            $customer['costs']                      =   $customer_details->cost;
        }
        // dd($customers);
        return $customers;
        }// Customer Export End

        // When Organization ID is selected. Employees would be shown from given range
        if($request->organization_id != NULL){
            $organization_id        = $request->organization_id;
            $selected_organization  =   Organization::where('id',$organization_id)->select('name')->first();
            $customers  =   Customer::where('organization_id',$organization_id)
                        ->Where(function ($q) use($start,$end){
                                $q->orWhereBetween('created_at', array($start, $end))
                                ->OrwhereBetween('updated_at', array($start, $end));
                            })
                        ->select('id','name','employee_code','phone','address','patient_coordinator_id','status_id','notes')
                        ->get();
            $statuses   =   DB::table('status')->get();
            $i = 1;
            foreach ($customers as $customer) {
                foreach ($statuses as $status) {
                    if($status->id == $customer['status_id']){
                        $customer['status_id']     =   $status->name;
                    }
                }
                $users                                  =   Auth::user()->find($customer->patient_coordinator_id);
                $customer['Organization']               =   $selected_organization->name;
                $customer['patient_coordinator_id']     =   $users->name;
                $customer['notes']                      =   strip_tags($customer->notes);
                $customer_treatment_details             =   TreatmentsAndCenters($customer->id);
                $customer['procedures']                 =   $customer_treatment_details->treatment;
                $customer['centers']                    =   $customer_treatment_details->center_name;
                $customer['doctors']                    =   $customer_treatment_details->doctor_name;
                $customer['discounted_cost']            =   $customer_treatment_details->discounted_cost;
                $customer_diagnostic_details            =   DiagnosticsAndLabs($customer->id);
                $customer['lab']                        =   $customer_diagnostic_details->lab_name;
                $customer['diagnostics']                =   $customer_diagnostic_details->diagnostic_name;
                $customer['cost']                       =   $customer_diagnostic_details->discounted_cost;
                $customer['id']                         =   $i;
                $i++;
            }
            // dd($customers);
            return $customers;
        }// Customer Export End
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Employee Code',
            'Phone',
            'Address',
            // 'Gender',
            // 'Marital Status',
            // 'Age',
            // 'Weight',
            // 'Height',
            'Patient Owner',
            'Status',
            'Notes',
            'Organization Name',
            // 'Follow Up',
            // 'Created at',
            // 'Updated at',
            'Procedures',
            'Centers',
            'Doctors',
            'Costs',
            'Labs',
            'Diagnostics',
            'Costs'
        ];
    }

     /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }

}
