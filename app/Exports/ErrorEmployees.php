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

class ErrorEmployees implements FromCollection,WithHeadings,WithEvents,ShouldAutoSize
{
    use Exportable;
    protected $request;
    public function __construct(Request $request,$id)
    {
        $this->request  = $request;
        $this->id       = $id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $request    =   $this->request;
        $customers       =   $this->id;
        // $customers  =   Customer::whereBetween('created_at', array($start, $end))
        //             ->OrwhereBetween('updated_at', array($start, $end))
        //             ->select('id','name','phone','address','patient_coordinator_id','status_id','notes')
        //             ->get();
        // dd($customers);
        // foreach ($customers as $customer) {
        //     // Gender ID Matching
        //     $customer['gender']             =   ($customer['gender'] == 1) ? 'Female' : 'Male';
        //     $customer['marital_status']     =   ($customer['marital_status'] == 1) ? 'Married' : 'Unmarried';
        //     $customer['age']                =   $customer['age'];
        //     $customer['weight']             =   $customer['weight'];
        //     $customer['height']             =   $customer['height'];
        //     // dd($customer['age'] );
        //     // Status ID Matching and returning names of it
        // }
        // $test = collect($customers);
        // $test = collect($customers)->map(function ($item) {
        //     return (object) $item;
        // });
        dd($customers);
        return $customers;

        // return Customer::all();
    }
    public function headings(): array
    {
        return [
            'ID',
            // 'Customer ID',
            'Name',
            // 'Email',
            'Phone',
            'Address',
            'Patient Owner',
            'Status',
            'Notes',
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
