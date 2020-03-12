<?php

namespace App\Exports;

use App\Models\Admin\Customer;
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

class ByIDExport implements FromCollection,WithHeadings,WithEvents,ShouldAutoSize
{
    use Exportable;
    protected $request;
    protected $id;
    public function __construct(Request $request,$id)
    {
        $this->request = $request;
        $this->id = $id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $id       =   $this->id;
        $request    =   $this->request;
        // dd($request, $id);

        $start      =   $request->start_date;
        $end        =   $request->end_date;
        // dd($start,$end);
        if (!$start && !$end) {
        $start      =   Carbon::now()->subyear(1)->startOfYear();
        $end        =   Carbon::now();
        }
        $customers  =   Customer::where('patient_coordinator_id',$id)
                    ->whereBetween('created_at', array($start, $end))
                    ->whereBetween('updated_at', array($start, $end))
                    ->get();
        $statuses   =   DB::table('status')->get();
        foreach ($customers as $customer) {
            // Gender ID Matching
            $customer['gender']         =   ($customer->gender == 1) ? 'Female' : 'Male';
            // Status ID Matching and returning names of it
            foreach ($statuses as $status) {
                if($status->id == $customer['status_id']){
                    $customer['status_id']     =   $status->name;
                }
            }
            $users      =   Auth::user()->find($customer->patient_coordinator_id);
            $customer['patient_coordinator_id']     =   $users->name;
            $customer['marital_status']             =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
            $customer['notes']                      =   strip_tags($customer->notes);

            $customer_details                       =   TreatmentsAndCenters($customer->id);
            $customer['Procedures']                 =   $customer_details->treatment;
            $customer['Centers']                    =   $customer_details->center_name;
            $customer['Costs']                      =   $customer_details->cost;
            $customer['created_at']                 =   $customer->created_at->format('Y-m-d');
            $customer['updated_at']                 =   $customer->updated_at->format('Y-m-d');
        }

        return $customers;

        // return Customer::all();
    }
    public function headings(): array
    {
        return [
            'ID',
            'Customer ID',
            'Name',
            'Email',
            'Phone',
            'Address',
            'Gender',
            'Marital Status',
            'Age',
            'Weight',
            'Height',
            'Notes',
            'Status',
            'Follow Up',
            'Patient Owner',
            'Created at',
            'Updated at',
            'Procedures',
            'Centers',
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
