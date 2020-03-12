<?php

namespace App\Exports;

use App\Models\Admin\Center;
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

class ByCenterExport implements FromCollection,WithHeadings,WithEvents,ShouldAutoSize
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

        // When No options are selected. Customers would be shown from given range
        if($request->center_id==NULL && $request->status_id==NULL && $request->patient_coordinator_id==NULL){
        $customers  =   Customer::from('customers')->where(function($q) use($start,$end){
                        $q->whereBetween('created_at', array($start, $end))
                    ->OrwhereBetween('updated_at', array($start, $end));
                    })
                    ->select('id','name','phone','city_name','age','weight','height','gender','patient_coordinator_id','status_id','notes')
                    ->get();
        // dd($customers[0]);
        $statuses   =   DB::table('status')->get();
        $i = 1;
        foreach ($customers as $key => $customer) {
            foreach ($statuses as $status) {
                if($status->id == $customer['status_id']){
                    $customer['status_id']     =   $status->name;
                }
            }
            $customer['gender']                     =   ($customer->gender == 0) ? 'Male' : 'Female';
            $users                                  =   Auth::user()->find($customer->patient_coordinator_id);
            $customer['patient_coordinator_id']     =   isset($users)?$users->name:NULL;
            $customer['notes']                      =   strip_tags($customer->notes);
            $customer_details                       =   TreatmentsAndCenters($customer->id);
            if ($request->city_name != NULL) {
                if ($customer_details->city_name != $request->city_name) {
                    //Deleting Item that Does not Match the Selected City
                     unset($customers[$key]);
                     //Skiping this iteration
                    continue;
                }
            }
            $customer['procedures']                 =   $customer_details->treatment;
            $customer['centers']                    =   $customer_details->center_name;
            $customer['doctors']                    =   $customer_details->doctor_name;
            $customer['costs']                      =   $customer_details->cost;
            $customer['discount_per']               =   $customer_details->discount_per;
            $customer['discounted_cost']            =   $customer_details->discounted_cost;
            $customer_diagnostic_details            =   DiagnosticsAndLabs($customer->id);
            $customer['lab']                        =   $customer_diagnostic_details->lab_name;
            $customer['diagnostics']                =   $customer_diagnostic_details->diagnostic_name;
            $customer['diagnostic_cost']            =   $customer_diagnostic_details->cost;
            $customer['diagnostic_discount_per']    =   $customer_diagnostic_details->discount_per;
            $customer['diagnostic_discounted_cost'] =   $customer_diagnostic_details->discounted_cost;
            $customer['id']                         =   $i;
            $i++;
        }
        return $customers;
        }// Customer Export End

        // When There is only Center selected by Admin
        if($request->center_id && $request->status_id==NULL && $request->patient_coordinator_id==NULL){
        $center_id          =   $request->center_id;
        // $center             =   Center::where('id',$center_id)->select('id','center_name')->first();
        $centercustomer     =   DB::table('customer_procedures as cp')
                            ->join('medical_centers as mc','mc.id','cp.hospital_id')
                            ->join('customers as c','c.id','cp.customer_id')
                            ->join('treatments as t','t.id','cp.treatments_id')
                            ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                            ->where('cp.hospital_id',$center_id)
                            ->select('c.id as id','c.name as customer_name','c.phone as phone','c.address','c.age','c.weight','c.height','c.gender','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','cp.cost as cost','cp.discount_per as discount_per','cp.discounted_cost as discounted_cost')
                            ->Where(function ($q) use($start,$end){
                                    $q->orWhereBetween('c.updated_at', [$start, $end])
                                    ->orwhereBetween('c.created_at', array($start, $end));
                            })
                            ->get();
        $statuses   =   DB::table('status')->get();
        $i = 1;

        foreach ($centercustomer as $customer) {
            // Status ID Matching and returning names of it
            foreach ($statuses as $status) {
                if($status->id == $customer->status_id){
                    $customer->status_id     =   $status->name;
                }
            }
            $customer->gender                       =   ($customer->gender == 0) ? 'Male' : 'Female';
            $users                                  =   Auth::user()->find($customer->patient_coordinator_id);
            $customer->patient_coordinator_id       =   $users->name;
            // $customer['marital_status']             =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
            $customer->notes                        =   strip_tags($customer->notes);
            $customer_diagnostic_details            =   DiagnosticsAndLabs($customer->id);
            $customer->lab                          =   $customer_diagnostic_details->lab_name;
            $customer->diagnostics                  =   $customer_diagnostic_details->diagnostic_name;
            $customer->diagnostic_cost              =   $customer_diagnostic_details->cost;
            $customer->diagnostic_discount_per      =   $customer_diagnostic_details->discount_per;
            $customer->diagnostic_discounted_cost   =   $customer_diagnostic_details->discounted_cost;
            $customer->id                           =   $i;
            $i++;
        }
        return $centercustomer;
        }// End of Only Center Selection

        // When There is only Status slected by Admin
        if ($request->center_id==NULL && $request->status_id && $request->patient_coordinator_id==NULL) {
            $status_id  =   $request->status_id;
            $customers  =   Customer::where('status_id',$status_id)->Where(function ($q) use($start,$end){
                        $q->orWhereBetween('updated_at', [$start, $end])
                        ->orwhereBetween('created_at', array($start, $end));
                    })
                    ->select('id','name','phone','address','age','weight','height','gender','patient_coordinator_id','status_id','notes')
                    ->get();
                    // dd($customers);
        $statuses   =   DB::table('status')->get();
        $i = 1;
        foreach ($customers as $key => $customer) {
            // Gender ID Matching
            // $customer['gender']         =   ($customer->gender == 1) ? 'Female' : 'Male';
            // Status ID Matching and returning names of it
            foreach ($statuses as $status) {
                if($status->id == $customer['status_id']){
                    $customer['status_id']     =   $status->name;
                }
            }
            $customer['gender']                     =   ($customer->gender == 0) ? 'Male' : 'Female';
            $users                                  =   Auth::user()->find($customer->patient_coordinator_id);
            $customer['patient_coordinator_id']     =   $users->name;
            $customer['notes']                      =   strip_tags($customer->notes);
            $customer_details                       =   TreatmentsAndCenters($customer->id);
            if ($request->city_name != NULL) {
                if ($customer_details->city_name != $request->city_name) {
                    //Deleting Item that Does not Match the Selected City
                     unset($customers[$key]);
                     //Skiping this iteration
                    continue;
                }
            }
            $customer['procedures']                 =   $customer_details->treatment;
            $customer['centers']                    =   $customer_details->center_name;
            $customer['doctors']                    =   $customer_details->doctor_name;
            $customer['costs']                      =   $customer_details->cost;
            $customer['discount_per']               =   $customer_details->discount_per;
            $customer['discounted_cost']            =   $customer_details->discounted_cost;
            $customer_diagnostic_details            =   DiagnosticsAndLabs($customer->id);
            $customer['lab']                        =   $customer_diagnostic_details->lab_name;
            $customer['diagnostics']                =   $customer_diagnostic_details->diagnostic_name;
            $customer['diagnostic_cost']            =   $customer_diagnostic_details->cost;
            $customer['diagnostic_discount_per']    =   $customer_diagnostic_details->discount_per;
            $customer['diagnostic_discounted_cost'] =   $customer_diagnostic_details->discounted_cost;
            $customer['id']                         =   $i;
            $i++;
            // $customer['created_at']                 =   $customer->created_at->format('Y-m-d');
        }
        return $customers;
        }// End of only Status Selection

        // When Patient Owner is selected only
        if ($request->center_id==NULL && $request->status_id==NULL && $request->patient_coordinator_id) {
            $id         =   $request ->patient_coordinator_id;
            $customers  =   Customer::where('patient_coordinator_id',$id)->Where(function ($q) use($start,$end){
                            $q->orWhereBetween('updated_at', [$start, $end])
                            ->orwhereBetween('created_at', array($start, $end));
                            })
                        ->select('id','name','phone','address','age','weight','height','gender','patient_coordinator_id','status_id','notes')
                        ->get();
            $statuses   =   DB::table('status')->get();
            $i = 1;
            foreach ($customers as $key => $customer) {
                // Status ID Matching and returning names of it
                foreach ($statuses as $status) {
                    if($status->id == $customer['status_id']){
                        $customer['status_id']     =   $status->name;
                    }
                }
                $customer['gender']                     =   ($customer->gender == 0) ? 'Male' : 'Female';
                $users                                  =   Auth::user()->find($customer->patient_coordinator_id);
                $customer['patient_coordinator_id']     =   $users->name;
                // $customer['marital_status']             =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
                $customer['notes']                      =   strip_tags($customer->notes);

                $customer_details                       =   TreatmentsAndCenters($customer->id);
                if ($request->city_name != NULL) {
                    if ($customer_details->city_name != $request->city_name) {
                        //Deleting Item that Does not Match the Selected City
                         unset($customers[$key]);
                         //Skiping this iteration
                        continue;
                    }
                }
                $customer['procedures']                 =   $customer_details->treatment;
                $customer['centers']                    =   $customer_details->center_name;
                $customer['doctors']                    =   $customer_details->doctor_name;
                $customer['costs']                      =   $customer_details->cost;
                $customer['discount_per']               =   $customer_details->discount_per;
                $customer['discounted_cost']            =   $customer_details->discounted_cost;
                $customer_diagnostic_details            =   DiagnosticsAndLabs($customer->id);
                $customer['lab']                        =   $customer_diagnostic_details->lab_name;
                $customer['diagnostics']                =   $customer_diagnostic_details->diagnostic_name;
                $customer['diagnostic_cost']            =   $customer_diagnostic_details->cost;
                $customer['diagnostic_discount_per']    =   $customer_diagnostic_details->discount_per;
                $customer['diagnostic_discounted_cost'] =   $customer_diagnostic_details->discounted_cost;
                $customer['id']                         =   $i;
                $i++;
                // $customer['created_at']                 =   $customer->created_at->format('Y-m-d');
                // $customer['updated_at']                 =   $customer->updated_at->format('Y-m-d');
        }
        return $customers;
        }// End of Patient Owner selected only

        // When Center and Status are slected only
        if($request->center_id && $request->status_id && $request->patient_coordinator_id==NULL){
            $center_id      =   $request->center_id;
            $status_id      =   $request->status_id;
            $matchThese     =   ['cp.hospital_id' => $center_id, 'c.status_id' => $status_id];
            $customers      =   DB::table('customer_procedures as cp')
                                ->join('medical_centers as mc','mc.id','cp.hospital_id')
                                ->join('customers as c','c.id','cp.customer_id')
                                ->join('treatments as t','t.id','cp.treatments_id')
                                ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                                ->where($matchThese)
                                ->select('c.id as id','c.name as customer_name','c.phone as phone','c.address','c.age','c.weight','c.height','c.gender','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','cp.cost as cost','cp.discount_per as discount_per','cp.discounted_cost as discounted_cost')
                                ->Where(function ($q) use($start,$end){
                                        $q->orWhereBetween('c.updated_at', [$start, $end])
                                        ->orwhereBetween('c.created_at', array($start, $end));
                                })
                                ->get();
        $statuses   =   DB::table('status')->get();
        $i = 1;
        foreach ($customers as $customer) {
            // Status ID Matching and returning names of it
            foreach ($statuses as $status) {
                if($status->id == $customer->status_id){
                    $customer->status_id     =   $status->name;
                }
            }
            $customer->gender                       =   ($customer->gender == 0) ? 'Male' : 'Female';
            $users      =   Auth::user()->find($customer->patient_coordinator_id);
            $customer->patient_coordinator_id       =   $users->name;
            // $customer['marital_status']          =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
            $customer->notes                        =   strip_tags($customer->notes);
            $customer_diagnostic_details            =   DiagnosticsAndLabs($customer->id);
            $customer->lab                          =   $customer_diagnostic_details->lab_name;
            $customer->diagnostics                  =   $customer_diagnostic_details->diagnostic_name;
            $customer->diagnostic_cost              =   $customer_diagnostic_details->cost;
            $customer->diagnostic_discount_per      =   $customer_diagnostic_details->discount_per;
            $customer->diagnostic_discounted_cost   =   $customer_diagnostic_details->discounted_cost;
            $customer->id                           =   $i;
            $i++;
        }
        return $customers;
        }// End of Center and Status Selection

        // When Center and Patient Owner is selected
        if ($request->center_id && $request->status_id==NULL && $request->patient_coordinator_id) {
            $center_id      =   $request->center_id;
            $id             =   $request->patient_coordinator_id;
            $matchThese = ['cp.hospital_id' => $center_id, 'c.patient_coordinator_id' => $id];
            $customers     =   DB::table('customer_procedures as cp')
                            ->join('medical_centers as mc','mc.id','cp.hospital_id')
                            ->join('customers as c','c.id','cp.customer_id')
                            ->join('treatments as t','t.id','cp.treatments_id')
                            ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                            ->where($matchThese)
                            ->select('c.id as id','c.name as customer_name','c.phone as phone','c.address','c.age','c.weight','c.height','c.gender','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','cp.cost as cost','cp.discount_per as discount_per','cp.discounted_cost as discounted_cost')
                            ->Where(function ($q) use($start,$end){
                                    $q->orWhereBetween('c.updated_at', [$start, $end])
                                    ->orwhereBetween('c.created_at', array($start, $end));
                            })
                            ->get();
        $statuses   =   DB::table('status')->get();
        $i = 1;
        foreach ($customers as $customer) {
            // Status ID Matching and returning names of it
            foreach ($statuses as $status) {
                if($status->id == $customer->status_id){
                    $customer->status_id     =   $status->name;
                }
            }
            $customer->gender                       =   ($customer->gender == 0) ? 'Male' : 'Female';
            $users      =   Auth::user()->find($customer->patient_coordinator_id);
            $customer->patient_coordinator_id       =   $users->name;
            // $customer['marital_status']          =   ($customer->marital_status == 1) ? 'Married' : 'Unmarried';
            $customer->notes                        =   strip_tags($customer->notes);
            $customer_diagnostic_details            =   DiagnosticsAndLabs($customer->id);
            $customer->lab                          =   $customer_diagnostic_details->lab_name;
            $customer->diagnostics                  =   $customer_diagnostic_details->diagnostic_name;
            $customer->diagnostic_cost              =   $customer_diagnostic_details->cost;
            $customer->diagnostic_discount_per      =   $customer_diagnostic_details->discount_per;
            $customer->diagnostic_discounted_cost   =   $customer_diagnostic_details->discounted_cost;
            $customer->id                           =   $i;
            $i++;
        }
        return $customers;
        }// End of Center and Patient Owner selected

        // When Status and Patient Owner is selected
        if ($request->center_id==NULL && $request->status_id && $request->patient_coordinator_id) {
            $status_id      =   $request->status_id;
            $id             =   $request->patient_coordinator_id;
            $matchThese     =   ['c.status_id' => $status_id, 'c.patient_coordinator_id' => $id];
            if ($request->city_name==NULL) {
                $query  =    DB::table('customer_procedures as cp');
            } else {
                $query  =    DB::table('customer_procedures as cp')->where('mc.city_name',$request->city_name);
            }
            $customers     =   $query->join('medical_centers as mc','mc.id','cp.hospital_id')
                            ->join('customers as c','c.id','cp.customer_id')
                            ->join('treatments as t','t.id','cp.treatments_id')
                            ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                            ->where($matchThese)
                            ->select('c.id as id','c.name as customer_name','c.phone as phone','c.address','c.age','c.weight','c.height','c.gender','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','cp.cost as cost','cp.discount_per as discount_per','cp.discounted_cost as discounted_cost')
                            ->Where(function ($q) use($start,$end){
                                    $q->orWhereBetween('c.updated_at', [$start, $end])
                                    ->orwhereBetween('c.created_at', array($start, $end));
                            })
                            ->get();
        $statuses   =   DB::table('status')->get();
        $i          =   1;
        foreach ($customers as $customer) {
            // Status ID Matching and returning names of it
            foreach ($statuses as $status) {
                if($status->id == $customer->status_id){
                    $customer->status_id     =   $status->name;
                }
            }
            $customer->gender                       =   ($customer->gender == 0) ? 'Male' : 'Female';
            $users                                  =   Auth::user()->find($customer->patient_coordinator_id);
            $customer->patient_coordinator_id       =   $users->name;
            $customer->notes                        =   strip_tags($customer->notes);
            $customer_diagnostic_details            =   DiagnosticsAndLabs($customer->id);
            $customer->lab                          =   $customer_diagnostic_details->lab_name;
            $customer->diagnostics                  =   $customer_diagnostic_details->diagnostic_name;
            $customer->diagnostic_cost              =   $customer_diagnostic_details->cost;
            $customer->diagnostic_discount_per      =   $customer_diagnostic_details->discount_per;
            $customer->diagnostic_discounted_cost   =   $customer_diagnostic_details->discounted_cost;
            $customer->id                           =   $i;
            $i++;
        }
        return $customers;
        }// End of Status and Patient Owner selected

        //When All are Selected
        if ($request->center_id && $request->status_id && $request->patient_coordinator_id) {
            $center_id      =   $request->center_id;
            $id             =   $request->patient_coordinator_id;
            $status_id      =   $request->status_id;
            $matchThese = ['c.status_id' => $status_id, 'c.patient_coordinator_id' => $id, 'cp.hospital_id'  =>  $center_id];
                        $customers     =   DB::table('customer_procedures as cp')
                            ->join('medical_centers as mc','mc.id','cp.hospital_id')
                            ->join('customers as c','c.id','cp.customer_id')
                            ->join('treatments as t','t.id','cp.treatments_id')
                            ->LEFTJOIN('doctors as d','cp.doctor_id','d.id')
                            ->where($matchThese)
                            ->select('c.id as id','c.name as customer_name','c.phone as phone','c.address','c.age','c.weight','c.height','c.gender','c.patient_coordinator_id','c.status_id','c.notes','t.name as treatment','mc.center_name as center','d.name as doctor','cp.cost as cost','cp.discount_per as discount_per','cp.discounted_cost as discounted_cost')
                            ->Where(function ($q) use($start,$end){
                                    $q->orWhereBetween('c.updated_at', [$start, $end])
                                    ->orwhereBetween('c.created_at', array($start, $end));
                            })
                            ->get();
            $statuses   =   DB::table('status')->get();
            $i          =   1;
            foreach ($customers as $customer) {
            // Status ID Matching and returning names of it
                foreach ($statuses as $status) {
                    if($status->id == $customer->status_id){
                        $customer->status_id     =   $status->name;
                    }
                }
                $customer->gender                       =   ($customer->gender == 0) ? 'Male' : 'Female';
                $users                                  =   Auth::user()->find($customer->patient_coordinator_id);
                $customer->patient_coordinator_id       =   $users->name;
                $customer->notes                        =   strip_tags($customer->notes);
                $customer_diagnostic_details            =   DiagnosticsAndLabs($customer->id);
                $customer->lab                          =   $customer_diagnostic_details->lab_name;
                $customer->diagnostics                  =   $customer_diagnostic_details->diagnostic_name;
                $customer->diagnostic_cost              =   $customer_diagnostic_details->cost;
                $customer->diagnostic_discount_per      =   $customer_diagnostic_details->discount_per;
                $customer->diagnostic_discounted_cost   =   $customer_diagnostic_details->discounted_cost;
                $customer->id                           =   $i;
                $i++;
        }

        return $customers;
        } //End of All are Selected

    }
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Phone',
            'Address',
            'Age',
            'Weight',
            'Height',
            'Gender',
            'Patient Owner',
            'Status',
            'Notes',
            'Procedures',
            'Centers',
            'Doctors',
            'Costs',
            'Discount %',
            'Discounted Cost',
            'Labs',
            'Diagnostics',
            'Costs',
            'Discount %',
            'Discounted Cost'
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
