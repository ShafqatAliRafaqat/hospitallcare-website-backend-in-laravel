<?php

use App\Exports\ErrorEmployees;
use App\Models\Admin\Center;
use App\Models\Admin\Customer;
use App\Models\Admin\Doctor;
use App\Models\Admin\Lab;
use App\Models\Admin\Treatment;
use App\Organization;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;

// use DateTime;
// use Image;
function view($view = null, $data = [], $mergeData = [])
{
    $factory = app(Illuminate\Contracts\View\Factory::class);

    if (func_num_args() === 0) {
        return $factory;
    }

    //if amp, add '-amp' to view name
    if(request()->segment(1) == 'amp'){
        $view .= '-amp';
    }
    return $factory->make($view, $data, $mergeData);
}


function medical_treatments($id){
    $med_treatments = DB::table('medical_centers as mc')
    ->LEFTJOIN('center_treatments as ct','ct.med_centers_id','mc.id')
    ->LEFTJOIN('treatments as t','ct.treatments_id','t.id')
    ->Where('mc.id',$id)
    ->select(DB::raw('GROUP_CONCAT(t.name) as treatment'),DB::raw('GROUP_CONCAT(t.id) as ids'))
    ->first();
    return $med_treatments;
}

function customer_treatments($id){
    $cust_treatments = DB::table('customers as mc')
    ->LEFTJOIN('customer_procedures as ct','ct.customer_id','mc.id')
    ->LEFTJOIN('treatments as t','ct.treatments_id','t.id')
    ->Where('mc.id',$id)
    ->select(DB::raw('GROUP_CONCAT(t.name) as treatment'),DB::raw('GROUP_CONCAT(t.id) as ids'))
    ->first();
    return $cust_treatments;
}

function customer_centers($id){
    $cust_centers = DB::table('customers as c')
    ->LEFTJOIN('customer_procedures as ct','ct.customer_id','c.id')
    ->LEFTJOIN('medical_centers as t','ct.hospital_id','t.id')
    ->Where('c.id',$id)
    ->select(DB::raw('GROUP_CONCAT(t.center_name) as name'),DB::raw('GROUP_CONCAT(t.id) as ids'))
    ->first();
    // dd($cust_treatments);
    return $cust_centers;
}

function TreatmentsAndCenters($id){
    $customer_details = DB::table('customers as c')
    ->LEFTJOIN('customer_procedures as ct','ct.customer_id','c.id')
    ->LEFTJOIN('medical_centers as mc','ct.hospital_id','mc.id')
    ->LEFTJOIN('treatments as t','ct.treatments_id','t.id')
    ->LEFTJOIN('doctors as d','ct.doctor_id','d.id')
    ->Where('c.id',$id)
    ->orderby('ct.id','ASC')
    ->select(DB::raw('GROUP_CONCAT(mc.center_name) as center_name'),DB::raw('GROUP_CONCAT(mc.id) as center_ids'),
        DB::raw('GROUP_CONCAT(d.name) as doctor_name'),DB::raw('GROUP_CONCAT(d.id) as doctor_ids'),
        DB::raw('GROUP_CONCAT(t.name) as treatment'),DB::raw('GROUP_CONCAT(t.id) as treatment_ids'),DB::raw('GROUP_CONCAT(ct.cost) as cost'),
        DB::raw('GROUP_CONCAT(ct.discount_per) as discount_per'),DB::raw('GROUP_CONCAT(ct.discounted_cost) as discounted_cost'),
        DB::raw('GROUP_CONCAT(ct.appointment_date) as appointment_date'),'mc.city_name as city_name')
    ->first();
    // dd($customer_details);
    return $customer_details;
}
function DiagnosticsAndLabs($id){
    $customer_details = DB::table('customers as c')
    ->LEFTJOIN('customer_diagnostics as cd','cd.customer_id','c.id')
    ->LEFTJOIN('labs as l','cd.lab_id','l.id')
    ->LEFTJOIN('diagnostics as d','cd.diagnostic_id','d.id')
    ->Where('c.id',$id)
    ->orderby('cd.id','ASC')
    ->select(DB::raw('GROUP_CONCAT(l.name) as lab_name'),DB::raw('GROUP_CONCAT(l.id) as lab_ids'),DB::raw('GROUP_CONCAT(d.name) as diagnostic_name'),DB::raw('GROUP_CONCAT(d.id) as diagnostic_ids'),DB::raw('GROUP_CONCAT(cd.cost) as cost'),DB::raw('GROUP_CONCAT(cd.discount_per) as discount_per'),DB::raw('GROUP_CONCAT(cd.discounted_cost) as discounted_cost'),DB::raw('GROUP_CONCAT(cd.appointment_date) as appointment_date'))
    ->first();
    return $customer_details;
}

function TreatmentsCentersRelation($id){
    $customer       =   Customer::Where('id',$id)->with(['center','treatments','doctor','diagnostics','labs'])->withTrashed()->first();
    // dd($customers);
    return $customer;
}
function CustomerHistory($id){
    $customer       =   Customer::Where('id',$id)->with(['center_history','treatments_history','doctor_history','diagnostics_history','labs_history'])->withTrashed()->first();
    return $customer;
}
function indexTreatmentsCenters($id){
    $customer_details = DB::table('customers as c')
    ->LEFTJOIN('customer_procedures as ct','ct.customer_id','c.id')
    ->LEFTJOIN('medical_centers as mc','ct.hospital_id','mc.id')
    ->LEFTJOIN('treatments as t','ct.treatments_id','t.id')
    ->Where('c.id',$id)
    ->orderby('ct.id','ASC')
    ->select(DB::raw('GROUP_CONCAT(mc.center_name) as center_name'),DB::raw('GROUP_CONCAT(mc.id) as center_ids'),DB::raw('GROUP_CONCAT(t.name) as treatment'),DB::raw('GROUP_CONCAT(t.id) as treatment_ids'),'ct.appointment_date as appointment_date')
    ->first();
    return $customer_details;
}

function indexCustomerDiagnostics($id){
    $customer_diagnostic    =   DB::table('customers as c')
                                ->LEFTJOIN('customer_diagnostics as cd','c.id','cd.customer_id')
                                ->LEFTJOIN('labs as l','l.id','cd.lab_id')
                                ->LEFTJOIN('diagnostics as d','d.id','cd.diagnostic_id')
                                ->Where('cd.customer_id',$id)
                                ->ORDERBY('cd.id','ASC')
                                ->select(DB::raw('GROUP_CONCAT(l.name) as lab_name'),DB::raw('GROUP_CONCAT(l.id) as lab_id'),DB::raw('GROUP_CONCAT(d.name) as diagnostic_name'),DB::raw('GROUP_CONCAT(d.id) as diagnostic_id'))
                                ->first();
    return $customer_diagnostic;
}
function CustomerDiagnostics($id,$lab_id){
    $customer_diagnostic    =   DB::table('customers as c')
                                ->LEFTJOIN('customer_diagnostics as cd','c.id','cd.customer_id')
                                ->LEFTJOIN('labs as l','l.id','cd.lab_id')
                                ->LEFTJOIN('diagnostics as d','d.id','cd.diagnostic_id')
                                ->Where('cd.customer_id',$id)
                                ->Where('cd.lab_id',$lab_id)
                                ->ORDERBY('cd.id','ASC')
                                ->select(DB::raw('l.name as lab_name'),DB::raw('GROUP_CONCAT(l.id) as lab_id'),DB::raw('GROUP_CONCAT(d.name) as diagnostic_name'),DB::raw('GROUP_CONCAT(d.id) as diagnostic_id'),'cd.appointment_date')
                                ->first();
    // dd($customer_diagnostic);
    return $customer_diagnostic;
}
// Fetch all treatments using doctor_id and center_id
function getCenterTreatments($doctor_id, $center_id){
    $schedule   =   DB::table('center_doctor_schedule')
                    ->Where(['doctor_id' => $doctor_id, 'center_id' => $center_id])
                    ->select('id')
                    ->first();
    $treatments = DB::table('treatments as t')
                        ->join('doctor_treatments as dt','dt.treatment_id','t.id')
                        ->Where('dt.doctor_id',$doctor_id)
                        ->Where('dt.schedule_id',$schedule->id)
                        ->select('t.id','t.name as treatment_name')
                        ->groupBy('t.id')
                        ->get();
    // $treatments = DB::table('treatments as t')
    //                     ->join('doctor_treatments as dt','dt.treatment_id','t.id')
    //                     ->join('center_treatments as ct','ct.treatments_id','t.id')
    //                     ->Where('dt.doctor_id',$doctor_id)
    //                     ->Where('ct.med_centers_id',$center_id)
    //                     ->select('t.id','t.name as treatment_name')
    //                     ->groupBy('t.id')
    //                     ->get();
    return $treatments;
}
function getDoctorCenterSchedule($doctor_id, $center_id)
{
    $schedule   =   DB::table('center_doctor_schedule')
                    ->Where(['doctor_id' => $doctor_id, 'center_id' => $center_id])
                    ->select('time_from','time_to','appointment_duration','is_primary')
                    ->get();
    return $schedule;
}
function hoursRange( $lower, $upper, $step, $format = '' ) {
    // $times = array();

    if ( empty( $format ) ) {
        $format = 'H:i';
    }
    $i=0;
    foreach ( range( $lower, $upper, $step ) as $increment ) {
        $increment = gmdate( 'H:i', $increment );

        list( $hour, $minutes ) = explode( ':', $increment );

        $date = new DateTime( $hour . ':' . $minutes );

        $times[] = $date->format( $format );
        $i++;
    }
    return $times;
}
function ParentTreatment($id){
    $treatment = Treatment::Where('id',$id)->first();
    if(isset($treatment)){
        if($treatment->parent_id == null){
            $ParentTreatment = DB::table('treatments as t1')
            ->Where('t1.id',$id)
            ->select('t1.name as parent_name','t1.id as parent_id')
            ->first();
        }else{
            $ParentTreatment = DB::table('treatments as t1')
            ->join('treatments as t2','t1.id','t2.parent_id')
            ->Where('t2.id',$id)
            ->select('t1.name as parent_name','t1.id as parent_id')
            ->first();
        }
    }else{
        $ParentTreatment =null;
    }
    // dd($ParentTreatment->parent_id);
    return $ParentTreatment;
}
function Doctor_treatment_show($id){
    $treatments =Treatment::Where('id',$id)->withTrashed()->first();

    return $treatments;
}
function DoctorTreatments($id){
    $treatments     =   DB::table('doctor_treatments as dt')
                        ->join('treatments as t','dt.treatment_id','t.id')
                        ->Where('schedule_id',$id)
                        ->select('t.id as treatment_id','t.name as treatment_name','dt.cost as treatment_cost')
                        ->get();
    return $treatments;
}

function CustomerLabDiagnostics($customer_id,$lab_id){
    $diagnostics    =   DB::table('customer_diagnostics')
                        ->Where(['customer_id' => $customer_id,'lab_id' => $lab_id])
                        ->groupBy('bundle_id')
                        ->get();
    return $diagnostics;
}
function CustomerLabDiagnosticsDetails($bundle_id){
    $diagnostics    =   DB::table('customer_diagnostics as cd')
                        ->join('diagnostics as d','d.id','cd.diagnostic_id')
                        ->join('labs as l','l.id','cd.lab_id')
                        ->Where('bundle_id', $bundle_id)
                        ->select('d.id as id','d.name as name','cd.*','l.name as lab_name')
                        ->get();
    return $diagnostics;
}
function CustomerLabHistory($customer_id,$lab_id){
    $diagnostics    =   DB::table('customer_diagnostic_history')
                        ->Where(['customer_id' => $customer_id,'lab_id' => $lab_id])
                        ->groupBy('bundle_id')
                        ->get();
    return $diagnostics;
}
function CustomerLabHistoryDetails($bundle_id){
    $diagnostics    =   DB::table('customer_diagnostic_history as cdh')
                        ->join('diagnostics as d','d.id','cdh.diagnostic_id')
                        ->join('labs as l','l.id','cdh.lab_id')
                        ->Where('bundle_id', $bundle_id)
                        ->select('d.id as id','d.name as name','cdh.*','l.name as lab_name')
                        ->get();
    return $diagnostics;
}
function doctor_clients_cp($doctor_id){
    $cp_patients    =   DB::table('doctors as d')
                        ->join('customer_procedures as cp','cp.doctor_id','d.id')
                        ->join('customers as c','c.id','cp.customer_id')
                        ->JOIN('treatments as t','cp.treatments_id','t.id')
                        ->JOIN('medical_centers as mc','cp.hospital_id','mc.id')
                        ->where(['cp.doctor_id' => $doctor_id])
                        ->where('cp.appointment_date','!=', null)
                        ->whereIn('cp.status',[0,2])
                        ->select('c.*','cp.hospital_id','cp.id as customer_procedures_id','mc.center_name','cp.treatments_id as treatments_id','t.name as treatment_name','cp.cost as costs','cp.appointment_date','cp.doctor_id')
                        ->get()->toArray();
    return $cp_patients;
}
function doctor_clients_cth($doctor_id){
    $cth_patients   =   DB::table('doctors as d')
                        ->join('customer_treatment_history as cth','cth.doctor_id','d.id')
                        ->join('customers as c','c.id','cth.customer_id')
                        ->JOIN('treatments as t','cth.treatments_id','t.id')
                        ->JOIN('medical_centers as mc','cth.hospital_id','mc.id')
                        ->Where(['cth.doctor_id' => $doctor_id])
                        ->where('cth.appointment_date','!=', null)
                        ->select('c.*','cth.hospital_id','cth.id as customer_procedures_id','mc.center_name','cth.treatments_id as treatments_id','t.name as treatment_name','cth.cost as costs','cth.appointment_date','cth.doctor_id')
                        ->get()->toArray();
    return $cth_patients;
}
function userName($id){

    $userID     =   Auth::user();
    $userName   =   $userID->name;
    return $userName;
}

function statusName($id){
    $status     =   DB::table('status')->Where('id',$id)->first();
    if(isset($status)){
        $statusName =   $status->name;
    }else{
        $statusName = '';
    }
    return $statusName;
}

function labName($id){
    $lab         =   Lab::Where('id',$id)->withTrashed()->first();
    $labName     =   $lab->name;
    return $labName;
}
function labMap($id){
    $lab        =   Lab::Where('id',$id)->withTrashed()->first();
    $labLat     =   $lab->lat;
    $labLng     =   $lab->lng;
    $url  = 'http://maps.google.com/?q='.$labLat.','.$labLng;
    return $url;
}
function labLocation($id){
    $lab         =   Lab::Where('id',$id)->withTrashed()->first();
    $labAddress  =   $lab->address;
    return $labAddress;
}
function diagnosticName($id){
    $diagnostic         =   DB::table('diagnostics')->Where('id',$id)->first();
    $diagnosticName     =   $diagnostic->name;
    return $diagnosticName;
}
function centerLocation($id){
    $center         =   Center::Where('id',$id)->withTrashed()->first();
    $centerAddress  =   $center->address;
    return $centerAddress;
}
function centerMap($id){
    $center         =   Center::Where('id',$id)->withTrashed()->first();
    $centerLat     =   $center->lat;
    $centerLng     =   $center->lng;
    $url  = 'http://maps.google.com/?q='.$centerLat.','.$centerLng;
    return $url;
}
function centerName($id){
    $center         =   Center::Where('id',$id)->withTrashed()->first();
    $centerName     =   $center->center_name;
    return $centerName;
}
function doctorName($id){
    $doctor             =   Doctor::Where('id',$id)->withTrashed()->first();
    $doctor_name        =   isset($doctor)?$doctor->name :'';
    return $doctor_name;
}
function TreatmentName($id){
    $treatment          =   Treatment::Where('id',$id)->withTrashed()->first();
    $treatment_name     =   $treatment->name;
    return $treatment_name;
}
function customerName($id){
    $customer             =   Customer::Where('id',$id)->withTrashed()->first();
    $customer_name        =   $customer->name;
    return $customer_name;
}
function doctorPhone($id){
    $doctor             =   Doctor::Where('id',$id)->withTrashed()->first();
    $doctor_phone       =   ($doctor->phone) ? $doctor->phone : $doctor->assistant_phone;
    return $doctor_phone;
}
function customerPhone($id){
    $customer             =   Customer::Where('id',$id)->withTrashed()->first();
    $customer_phone       =   $customer->phone;
    return $customer_phone;
}
function organizationName($id){
    $organization           =   Organization::Where('id',$id)->withTrashed()->first();
    $organizationName       =   $organization->name;
    return $organizationName;
}
function claimStatusName($id)
{
    $claimStatusName    =   (($id == 0) ? 'Pending' : (($id == 1) ? 'Approved' : (($id == 2) ? 'Decline' : (($id == 3) ? 'On Hold' : ''))));
    return $claimStatusName;
}
function get_times( $default = '11:00', $interval = '+60 minutes' ) {
    $output = '';
    $current = strtotime( '00:00' );
    $end = strtotime( '23:59' );

    while( $current <= $end ) {
        $time = date( 'H:i', $current );
        $sel = ( $time == $default ) ? ' selected' : '';

        $output .= "<option value=\"{$time}\"{$sel}>" . date( 'h.i A', $current ) .'</option>';
        $current = strtotime( $interval, $current );
    }
    return $output;
}

function get_times_to( $default = '19:00', $interval = '+60 minutes' ) {

    $output = '';

    $current = strtotime( '00:00' );
    $end = strtotime( '23:59' );

    while( $current <= $end ) {
        $time = date( 'H:i', $current );
        $sel = ( $time == $default ) ? ' selected' : '';

        $output .= "<option value=\"{$time}\"{$sel}>" . date( 'h.i A', $current ) .'</option>';
        $current = strtotime( $interval, $current );
    }
    return $output;
}
function get_years() {

    $years =array_reverse( range(1900, strftime("%Y", time())));
    return $years;
}
function get_days() {

    $days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday', );
    return $days;
}
function ErrorEmployeeExport($id){
    $id = collect($id)->map(function ($item) {
        return (object) $item;
    });
    $exporter = app()->makeWith(ErrorEmployees::class, compact('id'));
    // dd($exporter->id);
    return $exporter->download('Customers.xlsx');
}
function YearsDiff($date)
{
    $givenDate      =   Carbon::parse($date);
    $experience     =   $givenDate->diff(Carbon::now())->format('%y Years & %m Months');
    return $experience;
}
function AppointmentTimeConvert($appointment_date){
    $time               =   "";
    if ($appointment_date != NULL) {
        $time           =   Carbon::parse($appointment_date)->format('Y-m-d\TH:i');
    }
    return $time;
}

function CustomerAppointmentSms($message , $phone_number){
    $phone_dash = preg_replace("/[^0-9]/", "", $phone_number);
    $message    = str_replace('&', 'and', $message);
    $message    = str_replace('،', 'and', $message);
    $message    = str_replace(' ', '+', $message);
    $str1       = ltrim($phone_dash, '0');
    $phone      = '92'.$str1;
    $sms        = 'http://smsctp3.eocean.us:24555/api?action=sendmessage&username=Nestol&password=32JNoi90&recipient='.$phone.'&originator=99095&messagedata='.$message.'';
    $curl_handle=curl_init();
    curl_setopt($curl_handle,CURLOPT_URL,$sms);
    curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
    $buffer = curl_exec($curl_handle);
    curl_close($curl_handle);
    if (empty($buffer)){
        return "Nothing returned from url.";
    }
    else{
        return $sms;
    }
}

function datafromCustomerProcedureId($customer_procedure_id)
{
    $data   =   DB::table('customer_procedures as cp')
                ->join('customers as c','c.id','cp.customer_id')
                ->join('doctors as d','d.id','cp.doctor_id')
                ->join('medical_centers as mc','mc.id','cp.hospital_id')
                ->select('c.name','c.phone as customer_phone','c.id as customer_id','d.id as doctor_id','d.name as doctor_name','d.last_name as doctor_last_name','d.phone as doctor_phone','d.assistant_phone','mc.center_name','mc.address','mc.lat','mc.lng','cp.appointment_date')
                ->where('cp.id',$customer_procedure_id)
                ->first();
    return $data;
}
function datafromCustomerDiagnosticId($customer_diagnostic_id)
{
    $data   =   DB::table('customer_diagnostics as cd')
                ->join('customers as c','c.id','cd.customer_id')
                ->join('labs as l','l.id','cd.lab_id')
                ->select('c.name','c.phone as customer_phone','c.id as customer_id','l.name as lab_name','l.address','l.assistant_phone as lab_phone','l.lat','l.lng','cd.appointment_date')
                ->where('cd.id',$customer_diagnostic_id)
                ->first();
    return $data;
}
function delete_images($id,$path,$table,$id_name){
    $images         =   DB::table($table)->Where($id_name, $id)->get();
    $resizeName     =   '540x370-';
    $resizeName1    =   '266x266-';
    $resizeName2    =   '80x55-';
    if (isset($images)) {
        foreach ($images as $image) {
            $image_path     =   public_path().$path.$image->picture;
            $imageMedium    =   public_path().$path.$resizeName.$image->picture;
            $imageUser      =   public_path().$path.$resizeName1.$image->picture;
            $imageSmall     =   public_path().$path.$resizeName2.$image->picture;
            File::delete($image_path);
            File::delete($imageMedium);
            File::delete($imageUser);
            File::delete($imageSmall);
            $delete_image = DB::table($table)->Where($id_name, $id)->delete();
        }
        return "Images deleted";
    }else{
        return "Error in deleting image";
    }
}
function insert_images($id,$path,$table,$id_name,$filename,$image){
$resizeName  =  '266x266-'.$filename;
$location    =  public_path($path.$filename);
$resizeLoc2  =  public_path($path.$resizeName);
if($image != null){
    Image::make($image)->save($location);
    Image::make($image)->fit(266)->save($resizeLoc2);
}
    $insert = DB::table($table)->insert([$id_name => $id, 'picture' => $filename]);
}
function insert_customer_documents($slug,$file,$path){
    $location       =    public_path($path.$slug);
    Image::make($file)->save($location);
}
function formatPhone($phone){
    $phone_str  =   $phone;
    if(strlen($phone) > 3){
        if($phone_str[0] == '3' || $phone_str[0] == '4' || $phone_str[0] == '5'){
            $phone      =   '0'.$phone;
            $phone = substr($phone, 0, 4) .'-'.substr($phone,4);
        } else if($phone_str[0] == 9 && $phone_str[1] == 2){
            $str2 = substr($phone_str, 2);
            $phone      =   '0'.$str2;
            $phone = substr($phone, 0, 4) .'-'.substr($phone,4);
        } else if($phone_str[0] == '0' && $phone_str[1] == '9' && $phone_str[2] == '2'){
            $str2 = substr($phone_str, 3);
            $phone      =   '0'.$str2;
            $phone = substr($phone, 0, 4) .'-'.substr($phone,4);
        } else if($phone_str[0] == '+' && $phone_str[1] == '9' && $phone_str[2] == '2'){
            $str2 = substr($phone_str, 3);
            $phone      =   '0'.$str2;
            $phone = substr($phone, 0, 4) .'-'.substr($phone,4);
        } else if($phone_str[4] == '-'){
            $phone = $phone;
        } else {
            $phone = substr($phone, 0, 4) .'-'.substr($phone,4);
        }
    }
    return $phone;
}
function doctorCertification($id){
    $doctor_certification    = DB::table('doctor_certification')->Where('doctor_id',$id)->select('country','title','university','year')->get();
    return $doctor_certification;
}
function doctorQualification($id){
    $doctor_qualification    = DB::table('doctor_qualification')->Where('doctor_id',$id)->select('graduation_year as year','country','university','degree')->get();
    return $doctor_qualification;
}
function doctorImage($id){
    $doctor_image           =   DB::table('doctor_images')->Where('doctor_id',$id)->select('picture')->first();
    return $doctor_image;
}
function doctor_filter($request, $d){
    $male               = ($request->male == true) ? 1 : null;
    $female             = ($request->female === true) ? 0 : null;

    if(($request->available != null) || ($request->consultation_fee != null) || ($request->nearest_doctor != null)){
        $available = $request->available;
        $latitude = (isset($request->latitude)? $request->latitude: '30.3753');
        $longitude = (isset($request->longitude)? $request->longitude: '69.3451');
        $today_date = date('d-m-Y');
        $nearest_doctors_id = [];
        $all_doctors_id     = [];
        $today_doctors_id   = [];
        $weekend_doctors_id = [];
        $fee_doctors_id     = [];
        $available_today    = ($available == 1) ? Carbon::now()->format( 'l' ): '';

        $available_doctors = DB::table("center_doctor_schedule as cds")
                            ->join('doctors as d','d.id','cds.doctor_id')
                            ->join('medical_centers as mc','mc.id','cds.center_id')
                            ->Where('d.is_approved','!=',0)
                            ->select('cds.doctor_id','cds.center_id','d.name','cds.day_from','cds.day_to','cds.fare as fee','mc.lat','mc.lng',
                             DB::raw('( 3956*2 * acos( cos( radians('.$latitude.') ) *
                                        cos( radians( mc.lat ) ) * cos( radians( mc.lng ) -
                                        radians('.$longitude.') ) + sin( radians('.$latitude.') ) *
                                        sin( radians( mc.lat ) ) ) ) AS distance')
                            )
                            ->orderBy('distance')
                            ->get();
        foreach($available_doctors as $doctor){
                $day_from   = isset($doctor->day_from)?$doctor->day_from:'Monday';
                $day_to   = isset($doctor->day_to)?$doctor->day_to:'Sunday';
                $fee        = $doctor->fee;
                $doctor_id  = $doctor->doctor_id;
                $lat        = $doctor->lat;
                $lng        = $doctor->lng;

                if($request->nearest_doctor){
                    $nearest_doctor = explode("-", $request->nearest_doctor);
                    $distance       = $doctor->distance;

                    if ($nearest_doctor[0] <= $distance && $distance <= $nearest_doctor[1]) {
                        $nearest_doctors_id[] = $doctor_id;
                    }else{
                        $nearest_doctors_id[] = null;
                    }
                }
                if($request->consultation_fee){
                    $consultation_fee = explode("-", $request->consultation_fee);

                    if ($consultation_fee[0] <= $fee && $consultation_fee[1] >= $fee) {
                        $fee_doctors_id[] = $doctor_id;
                    }
                }
                if($available == 0 ){
                    $all_doctors_id[] = $doctor_id;
                }
                if($available == 1 ){
                    $day_from   = getDay($day_from)->format('d-m-Y');
                    $day_from1  = strtotime($day_from);
                    $day_to     = getDay($day_to)->format('d-m-Y');
                    $day_to1    = strtotime($day_to);
                    $today_date1= strtotime($today_date);
                        if($day_from1 <= $today_date1 && $day_to1 >= $today_date1) {
                            $today_doctors_id[] = $doctor_id;
                        }
                    // if ($available_today <= $day_from && $available_today >= $day_to) {
                    //     $today_doctors_id[] = $doctor_id;
                    // }
                }
               if($available == 2 ){
                    if(("Sunday" >= $day_from && "Sunday" <= $day_to) || ("Saturday" >= $day_from && "Saturday" <= $day_to)){
                        $weekend_doctors_id[] = $doctor_id;
                    }
                }
        }
        if($request->nearest_doctor != null){
            $d  = $d->WhereIn('id',$nearest_doctors_id);
        }
        if(count($all_doctors_id)>0 && $request->available == 0){
            $d  = $d->WhereIn('id',$all_doctors_id);
        }
        if(count($today_doctors_id)>0 && $request->available == 1){
            $d  = $d->WhereIn('id',$today_doctors_id);
        }
        if(count($weekend_doctors_id)>0 && $request->available == 2){
            $d  = $d->WhereIn('id',$weekend_doctors_id);
        }
        if(count($fee_doctors_id)>0 && $request->consultation_fee != null){
            $d  = $d->WhereIn('id',$fee_doctors_id);
        }
   }
   if(($request->female == true ) && ($request->male == true)){
        $d;
    }
    if(($request->male == true)  && ($request->female == false)){
        $d  =  $d->Where('gender',$male);
    }
    if(($request->female == true) && ($request->male == false)) {
        $d  =  $d->Where('gender',$female);
    }
    if(isset($request->online_consultation)){
        $d = $d->where('online_consultation',$request->online_consultation);
    }
    return $d;
}
function topDoctors(){
    $doctors = Doctor::Where('is_approved','!=',0)->Where('on_web',1)->orderBy('updated_at','DESC')->with(['doctor_image','centers','treatments'])->get();
    foreach($doctors as $doctor){
        $doctor['doctor_schedules']         = DB::table('center_doctor_schedule as cds')
                                                ->join('medical_centers as mc','mc.id','cds.center_id')
                                                ->select('cds.id','cds.center_id','mc.center_name',DB::raw('GROUP_CONCAT(cds.time_from) as time_from'),DB::raw('GROUP_CONCAT(cds.time_to) as time_to'),DB::raw('GROUP_CONCAT(cds.day_from) as day_from'),DB::raw('GROUP_CONCAT(cds.day_to) as day_to'),'cds.fare','cds.discount','cds.appointment_duration','cds.is_primary')
                                                ->Where('cds.doctor_id',$doctor->id)
                                                ->groupBy('cds.center_id')
                                                ->get();
    }
    return $doctors;
}
function getDay($day)
{
    $days = ['Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7];

    $today = new \DateTime();
    $today->setISODate((int)$today->format('o'), (int)$today->format('W'), $days[ucfirst($day)]);
    return $today;
}
function TreatmentCost($doctor_id , $center_id){
    $cost = DB::table('center_doctor_schedule')->where('doctor_id',$doctor_id)->where('center_id',$center_id)->select('fare','discount')->first();
    return $cost;
}
function DoctorViews($id){
    $doctor_views       = DB::table('doctor_views')->where('doctor_id',$id)->count();
    return $doctor_views;
}
function editOrShowClaim($patient,$id){
    $patient            =   DB::table('customer_claims')->where('id',$id)->first();
    if ($patient->cth_id != null) {
        //Treatments
        $patient        =   DB::table('customer_claims as cc')
                        ->join('customers as c','c.id','cc.appointment_for')
                        ->join('customer_treatment_history as cth','cth.id','cc.cth_id')
                        ->join('treatments as t','cth.treatments_id','t.id')
                        ->join('medical_centers as mc','cth.hospital_id','mc.id')
                        ->join('doctors as d','cth.doctor_id','d.id')
                        ->where('cc.id',$id)
                        ->select('c.*','cc.id as claim_id','cc.title as claim_title','cc.created_at as claim_date','cc.status as claim_status','cc.comment as claim_comment','cc.category as category','cc.internal_comment as internal_comment','mc.center_name as center_name','d.name as doctor_name','t.name as treatment_name','cth.appointment_date as treatment_date','cc.doctor_fee as doctor_fee','cc.diagnostic_fee as diagnostic_fee','cc.medicine_fee as medicine_fee','cc.other_fee as other_fee','cc.total_amount as total_amount')
                        ->first();
    } else if ($patient->cdh_bundle_id != null) {
        //Diagnostics
        $patient        =   DB::table('customer_claims as cc')
                        ->join('customers as c','c.id','cc.appointment_for')
                        ->join('customer_diagnostic_history as cdh','cdh_bundle_id','cc.cdh_bundle_id')
                        ->join('diagnostics as d','d.id','cdh.diagnostic_id')
                        ->join('labs as l','l.id','cdh.lab_id')
                        ->where('cc.id',$id)
                        ->select('c.*','cc.id as claim_id','cc.title as claim_title','cc.created_at as claim_date','cc.status as claim_status','cc.comment as claim_comment','cc.category as category','cc.internal_comment as internal_comment','l.name as center_name','cdh.appointment_date as treatment_date','cc.doctor_fee as doctor_fee','cc.diagnostic_fee as diagnostic_fee','cc.medicine_fee as medicine_fee','cc.other_fee as other_fee','cc.total_amount as total_amount'
                        )
                        ->first();
    } else {
        //Custom-Personally Added
        $patient        =   DB::table('customer_claims as cc')
                        ->join('customers as c','c.id','cc.appointment_for')
                        ->where('cc.id',$id)
                        ->select('c.*','cc.id as claim_id','cc.title as claim_title','cc.created_at as claim_date','cc.status as claim_status','cc.comment as claim_comment','cc.category as category','cc.internal_comment as internal_comment','cc.center_name as center_name','cc.appointment_date as treatment_date','cc.doctor_fee as doctor_fee','cc.diagnostic_fee as diagnostic_fee','cc.medicine_fee as medicine_fee','cc.other_fee as other_fee','cc.total_amount as total_amount')
                        ->first();
    }
    return $patient;
}
function nullCheck($variable, $query_variable)
{
    if($variable == NULL){
      $variable = $query_variable;
    }
    return $variable;
}
function getAllCities(){
    $cities = DB::table('cities_of_pak')->orderBy('name','ASC')->select('name')->get();
    return $cities;
}
?>
