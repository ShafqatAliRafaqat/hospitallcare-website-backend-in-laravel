<?php

namespace App\Exports;

use App\Models\Admin\Center;
use App\Models\Admin\Doctor;
use App\Organization;
use App\User;
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

class ByDoctorExport implements FromCollection,WithHeadings,WithEvents,ShouldAutoSize
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
        // dd($request->all());
        $start          =   $request->start_date;
        $ending         =   $request->end_date;
        $end            =   Carbon::parse($ending)->addDay(1);
        // dd($request->center_id);
        $i              =   1;
        // When No options are selected. Doctors would be shown from given range
        if($start && $ending && $request->specialization_id == null && $request->center_id == null){
            if ($request->onboard != null) {
                $case   = Doctor::where('is_partner',$request->onboard)->select('id','name','pmdc','gender','phone','is_partner','created_by','updated_by');
            } else {
                $case   = Doctor::select('id','name','pmdc','gender','phone','is_partner','created_by','updated_by');
            }
                $doctors        =   $case->where(function($query) use($start,$end){
                                        $query->whereBetween('created_at', array($start, $end))
                                        ->OrwhereBetween('updated_at', array($start, $end));
                                    })
                                    ->get();
            foreach ($doctors as $doctor) {
                $speciality_name        =   [];
                $center_name            =   [];
                $doctor['id']           =   $i;
                $doctor['gender']       =   ($doctor->gender == 0) ? 'Female' : 'Male';
                $doctor['is_partner']   =   ($doctor->is_partner == 0) ? 'Not Onboard' : 'Onboard';
                $created_by             =   User::where('id',$doctor->created_by)->first();
                $updated_by             =   User::where('id',$doctor->updated_by)->first();
                $doctor['created_by']   =   isset($created_by) ? $created_by->name : '';
                $doctor['updated_by']   =   isset($updated_by) ? $updated_by->name : '';
                $speciality             =   DB::table('doctor_treatments as dt')
                                            ->join('treatments as t','t.id','dt.treatment_id')
                                            ->where('doctor_id',$doctor->id)
                                            ->whereNull('t.parent_id')
                                            ->select('t.name')
                                            ->groupBy('dt.treatment_id')
                                            ->get();
                $centers                =   DB::table('center_doctor_schedule as cds')
                                            ->join('medical_centers as mc','mc.id','cds.center_id')
                                            ->where('doctor_id',$doctor->id)
                                            ->select('mc.center_name')
                                            ->groupBy('mc.id')
                                            ->get();
                foreach ($speciality as $s) {
                    $speciality_name[]         =   $s->name;
                }
                $doctor['specialities'] =   implode(",", $speciality_name);
                foreach ($centers as $c) {
                    $center_name[]         =   $c->center_name;
                }
                $doctor['centers']      =   implode(",", $center_name);
                // $doctor['speciality']   =   implode(",", $speciality->name);
                $i++;
            }
        //When only Specialization is selected
        } else if ($start && $ending && $request->specialization_id != null && $request->center_id == null) {
            $specialization_id  =   $request->specialization_id;
            $onboard            =   $request->onboard;
            if ($request->onboard != null) {
                $case       =   DB::table('doctor_treatments as dt')->where('is_partner',$onboard);
            } else {
                $case       =   DB::table('doctor_treatments as dt');
            }
                $doctors            =   $case->join('doctors as d','d.id','dt.doctor_id')
                        ->join('treatments as t','t.id','dt.treatment_id')
                        ->where('dt.treatment_id',$specialization_id)
                        ->where(function($query) use($start,$end){
                        $query->whereBetween('d.created_at', array($start, $end))
                        ->OrwhereBetween('d.updated_at', array($start, $end));
                        })->groupBy('d.id')
                        ->select('d.id','d.name','d.pmdc','d.gender','d.phone','d.is_partner','d.created_by','d.updated_by','t.name as specialities')
                        ->get();
            foreach ($doctors as $doctor) {
                $speciality_name        =   [];
                $center_name            =   [];
                $doctor->id             =   $i;
                $doctor->gender         =   ($doctor->gender == 0) ? 'Female' : 'Male';
                $doctor->is_partner     =   ($doctor->is_partner == 0) ? 'Not Onboard' : 'Onboard';
                $created_by             =   User::where('id',$doctor->created_by)->first();
                $updated_by             =   User::where('id',$doctor->updated_by)->first();
                $doctor->created_by     =   isset($created_by) ? $created_by->name : '';
                $doctor->updated_by     =   isset($updated_by) ? $updated_by->name : '';
                $centers                =   DB::table('center_doctor_schedule as cds')
                                            ->join('medical_centers as mc','mc.id','cds.center_id')
                                            ->where('doctor_id',$doctor->id)
                                            ->select('mc.center_name')
                                            ->groupBy('mc.id')
                                            ->get();
                foreach ($centers as $c) {
                    $center_name[]         =   $c->center_name;
                }
                $doctor->centers      =   implode(",", $center_name);
                $i++;
            }

        //When Both Specialization and Center are selected
        } else if ($start && $ending && $request->specialization_id != null && $request->center_id != null) {
            $specialization_id  =   $request->specialization_id;
            $center_id          =   $request->center_id;
            $onboard            =   $request->onboard;
            $doctors            =   DB::table('doctor_treatments as dt')
                                ->join('doctors as d','d.id','dt.doctor_id')
                                ->join('treatments as t','t.id','dt.treatment_id')
                                ->join('center_doctor_schedule as cds','cds.doctor_id','d.id')
                                ->join('medical_centers as mc','mc.id','cds.center_id')
                                ->When($onboard != null, function($q) use($specialization_id,$center_id,$onboard){
                                $q->where('cds.center_id',$center_id)->where('is_partner',$onboard)->where('dt.treatment_id',$specialization_id);
                                })
                                ->When($onboard == null, function($q) use($specialization_id,$center_id){
                                $q->where('cds.center_id',$center_id)->where('dt.treatment_id',$specialization_id);
                                })
                                ->where(function($query) use($start,$end){
                                $query->whereBetween('d.created_at', array($start, $end))
                                ->OrwhereBetween('d.updated_at', array($start, $end));
                                })->groupBy('d.id')
                                ->select('d.id','d.name','d.pmdc','d.gender','d.phone','d.is_partner','d.created_by','d.updated_by','t.name as specialities','mc.center_name')
                                ->get();
            foreach ($doctors as $doctor) {
                $speciality_name        =   [];
                $center_name            =   [];
                $doctor->id             =   $i;
                $doctor->gender         =   ($doctor->gender == 0) ? 'Female' : 'Male';
                $doctor->is_partner     =   ($doctor->is_partner == 0) ? 'Not Onboard' : 'Onboard';
                $created_by             =   User::where('id',$doctor->created_by)->first();
                $updated_by             =   User::where('id',$doctor->updated_by)->first();
                $doctor->created_by     =   isset($created_by) ? $created_by->name : '';
                $doctor->updated_by     =   isset($updated_by) ? $updated_by->name : '';
            }
            // When Specialization is null and Center is selected
        } else if ($start && $ending && $request->specialization_id == null && $request->center_id != null) {
            $specialization_id  =   $request->specialization_id;
            $center_id          =   $request->center_id;
            $onboard            =   $request->onboard;
            if ($request->onboard != null) {
                $doctors            =   DB::table('doctor_treatments as dt')
                                    ->join('doctors as d','d.id','dt.doctor_id')
                                    ->join('treatments as t','t.id','dt.treatment_id')
                                    ->join('center_doctor_schedule as cds','cds.doctor_id','d.id')
                                    ->join('medical_centers as mc','mc.id','cds.center_id')
                                    ->where(function($q) use($center_id,$onboard){
                                    $q->where('cds.center_id',$center_id)
                                    ->where('is_partner',$onboard);
                                    })
                                    ->where(function($query) use($start,$end){
                                    $query->whereBetween('d.created_at', array($start, $end))
                                    ->OrwhereBetween('d.updated_at', array($start, $end));
                                    })->groupBy('d.id')
                                    ->select('d.id','d.name','d.pmdc','d.gender','d.phone','d.is_partner','d.created_by','d.updated_by','t.name as specialities','mc.center_name')
                                    ->get();
            } else {
                $doctors            =   DB::table('doctor_treatments as dt')
                                        ->join('doctors as d','d.id','dt.doctor_id')
                                        ->join('treatments as t','t.id','dt.treatment_id')
                                        ->join('center_doctor_schedule as cds','cds.doctor_id','d.id')
                                        ->join('medical_centers as mc','mc.id','cds.center_id')
                                        ->where(function($q) use($center_id,$specialization_id){
                                        $q->where('cds.center_id',$center_id);
                                        })
                                        ->where(function($query) use($start,$end){
                                        $query->whereBetween('d.created_at', array($start, $end))
                                        ->OrwhereBetween('d.updated_at', array($start, $end));
                                        })->groupBy('d.id')
                                        ->select('d.id','d.name','d.pmdc','d.gender','d.phone','d.is_partner','d.created_by','d.updated_by','t.name as specialities','mc.center_name')
                                        ->get();
            }
            foreach ($doctors as $doctor) {
                $speciality_name        =   [];
                $center_name            =   [];
                $doctor->id             =   $i;
                $doctor->gender         =   ($doctor->gender == 0) ? 'Female' : 'Male';
                $doctor->is_partner     =   ($doctor->is_partner == 0) ? 'Not Onboard' : 'Onboard';
                $created_by             =   User::where('id',$doctor->created_by)->first();
                $updated_by             =   User::where('id',$doctor->updated_by)->first();
                $doctor->created_by     =   isset($created_by) ? $created_by->name : '';
                $doctor->updated_by     =   isset($updated_by) ? $updated_by->name : '';
            }
        }
        // dd($doctors);
        return $doctors;// doctor Export End
    }
    // {
    //     $buffer                 =   fetchdoctorapi(1);
    //     $payload                =   json_decode($buffer,true);
    //     // dd($payload['payload']);
    //     $total_pages            =   $payload['payload']['meta']['pagination']['total_pages'];
    //     $datas                  =   $payload['payload']['data'];
    //     $export                 =   [];
    //     $i                      =   0;
    //     for ($q=51; $q <= 54; $q++) {
    //         $buffer                 =   fetchdoctorapi($q+1);
    //         $payload                =   json_decode($buffer,true);
    //         $datas                  =   $payload['payload']['data'];
    //         foreach ($datas as $data) {
    //         $speciality                     =   [];
    //         $address                        =   [];
    //         $export[$i]['sr']               =   $i+1;
    //         $export[$i]['id']               =   $data['id'] ;
    //         $export[$i]['name']             =   $data['name'];
    //         $export[$i]['fee']              =   $data['fee'];
    //         $export[$i]['gender']           =   $data['gender'];
    //         $export[$i]['avatar']           =   "https://api2.hayaat.pk/".$data['avatar'];
    //         $export[$i]['education']        =   $data['education'];
    //         $export[$i]['bio']              =   $data['bio'];

    //         foreach ($data['specialities'] as $specialities) {
    //             foreach ($specialities as $s) {
    //                 $speciality[]   =   $s['name'];
    //             }
    //         }
    //         foreach ($data['locations'] as $location) {
    //             foreach ($location as $l) {
    //                 $address[]      =   $l['address'];
    //                 foreach ($l['timings'] as $timings) {
    //                     $export[$i]['timings']  =   $timings;
    //                 }
    //             }
    //         }
    //         // dd($timings);
    //         $export[$i]['specialities']     =   implode(",", $speciality);
    //         $export[$i]['centers']          =   implode(",", $address);
    //         $i++;
    //     }
    //     }
    //     $sorted = collect($export);
    //     return $sorted;
    // }
    public function headings(): array
    {
        return [
            'Sr. #',
            'Name',
            'PMDC',
            'Gender',
            'Phone',
            'Partnership Status',
            'Created By',
            'Updated By',
            'Specialities',
            'Centers ',

            // 'Sr. #',
            // 'id',
            // 'Name',
            // 'Fee',
            // 'Gender',
            // 'Avatar',
            // 'Education',
            // 'About/Bio',
            // 'Timings',
            // 'Specialities',
            // 'Centers ',
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
