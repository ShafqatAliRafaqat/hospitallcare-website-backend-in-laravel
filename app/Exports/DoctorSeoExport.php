<?php

namespace App\Exports;

use App\Models\Admin\Center;
use App\Models\Admin\Customer;
use App\Models\Admin\Status;
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

class DoctorSeoExport implements FromCollection,WithHeadings,WithEvents,ShouldAutoSize
{
    use Exportable;
    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
        // dd("aa",$request);
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $request        =   $this->request;
        foreach ($request as $key => $value) {
            $array[]['id']  =   $value;
        }
        $collection     =   collect($array);
        return $collection;
    }

    public function headings(): array
    {
        return [
            'ID',
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
