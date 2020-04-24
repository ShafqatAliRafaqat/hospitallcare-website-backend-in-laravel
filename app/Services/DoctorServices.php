<?php
namespace App\Services;

use App\Models\Admin\Center;
use App\Models\Admin\Doctor;
use App\Models\Admin\DoctorImage;
use App\Models\Admin\DoctorPartnershipFiles;
use App\Models\Admin\DoctorPartnershipImages;
use App\Models\Admin\Procedure;
use App\Models\Admin\Treatment;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class DoctorServices extends Service{
    public function createService($data){
        $request                =   $data;
        $data                   =   $data->all();
        $this->validate($data,null);
        $returnBackConditions   = $this->returnBackConditions($data);
        if($returnBackConditions != null){
            return ['error',$returnBackConditions];
        }
        $doctor                 =   Doctor::create($this->getSecureInput($data));
        $doctor_id              =   $doctor->id;
        $doctor_name            =   $doctor->name;
        $created_by             =   Doctor::where('id',$doctor_id)->update([
            'created_by'        =>  Auth::user()->id,
        ]);

        $imagesControll         =   $this->imagesControll($request,$doctor_id,$doctor_name);
        $qua_education          =   $this->qualification_education($data,$doctor_id);
        $centers_treatments     =   $this->centers_and_treatments($data,$doctor_id);
        return $doctor;
    }
    public function updateService($data,$id){
        $request                =   $data;
        $data                   =   $data->all();
        $this->validate($data,$id);
        $returnBackConditions   = $this->returnBackConditions($data);
        if($returnBackConditions != null){
            return ['error',$returnBackConditions];
        }
        $doctor                 =   Doctor::where('id',$id)->update($this->getSecureInput($data));
        $doctor_name            =   doctorName($id);
        $updated_by             =   Doctor::where('id',$id)->update([
            'updated_by'        =>  Auth::user()->id,
        ]);
        $doctor_phone           =   $data['phone'];
        $updated_user_phone     =   User::where('doctor_id',$id)->update([
            'phone'             =>  $doctor_phone,
            'name'              =>  $doctor_name,
        ]);
        $imagesUpdateControll   =   $this->imagesUpdateControll($request,$id,$doctor_name);
        $doctor_qualification   =   DB::table('doctor_qualification')->where('doctor_id', $id)->delete();
        $doctor_certification   =   DB::table('doctor_certification')->where('doctor_id', $id)->delete();
        $qua_education          =   $this->qualification_education($data,$id);
        $centers_treatments     =   $this->centers_and_treatments($data,$id);
        // $centers_treatments     =   $this->update_centers_and_treatments($data,$id);
        return $doctor;
    }

    public function validate($input,$id)
    {
        $rules = [
            'name'                      =>  'required',
            'last_name'                 =>  'sometimes',
            'email'                     =>  'sometimes',
            'gender'                    =>  'required',
            'city_name'                 =>  'sometimes',
            'address'                   =>  'sometimes',
            'pmdc'                      =>  'sometimes',
            'about'                     =>  'sometimes',
            'notes'                     =>  'sometimes',
            'phone'                     =>  'sometimes',
            'assistant_name'            =>  'sometimes',
            'meta_speciality'           =>  'sometimes',
            'assistant_phone'           =>  'sometimes',
            'ad_spent'                  =>  'sometimes',
            'revenue_share'             =>  'sometimes',
            'additional_details'        =>  'sometimes',
            'experience'                =>  'sometimes',
            'meta_title'                =>  'string|nullable',
            'meta_description'          =>  'string|nullable',
            'url'                       =>  'string|nullable',
            'meta_heading'              => 'string|nullable',
            'is_active'                 =>  'nullable',
            'on_web'                    =>  'nullable',
        ];
        $this->validateOrAbort($input, $rules);
    }
    public function returnBackConditions($data){
        if ($data['qua_degree'][0] != null) {                                                   // Checks for Qualification fields
            $count_qua_degree = count($data['qua_degree']);
            for ($i = 0; $i < $count_qua_degree; $i++) {
                if ($data['qua_graduation_year'][$i] == null) {
                    $message= 'Please Select Graduation Year';
                    return $message;
                }
            }
        }
        if ($data['cer_degree'][0] != null) {                                                   // Checks for Certification fields
            $count_cer_degree = count($data['cer_degree']);
            for ($i = 0; $i < $count_cer_degree; $i++) {
                if ($data['cer_graduation_year'][$i] == null) {
                    $message= 'Please Select Certification Year';
                    return $message;
                }
            }
        }
    }
    public function imagesControll($request,$doctor_id,$doctor_name)
    {
     $destinationPath = '/backend/uploads/doctors/';                                    // Defining th uploading path if not exist create new
     $image       = $request->file('picture');
     if ($request->file('picture')) {
        if(!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }
        $filename    = str_slug($doctor_name).'-'.time().'.'.$image->getClientOriginalExtension();
        $table = "doctor_images";
        $id_name = "doctor_id";
        $insert_images = insert_images($doctor_id, $destinationPath,$table,$id_name, $filename,$image);
    }

    if ($request->file('ptnr_picture')) {
        $destinationPath = '/backend/uploads/doctor_partnership_images/';
        $images = $request->file('ptnr_picture');
        $i = 0;
        foreach ($images as $image) {
            $filename = time().$i. '.' . $image->getClientOriginalExtension();
            $location = public_path($destinationPath.$filename);
            Image::make($image)->save($location);
            $insert = DB::table('doctor_partnership_images')->insert(['doctor_id' => $doctor_id, 'picture' => $filename]);
            $i++;
        }
    }
    if ($request->file('ptnr_files')) {
        $destinationPath = public_path().'/backend/uploads/doctor_partnership_files/';
        $files = $request->file('ptnr_files');
        $k = 0;
        foreach ($files as $file) {
                    // Uploading PDF file in FOLDER
            $filename   = time().$k.'.'.$file->getClientOriginalName();
            $file->move($destinationPath,$filename);
            $insert = DB::table('doctor_partnership_files')->insert(['doctor_id' => $doctor_id, 'file' => $filename]);
            $k++;
        }
    }
}
public function qualification_education($request,$doctor_id)
{
    // insert qualification data of doctor
    if ($request['qua_degree'][0] != NULL || $request['qua_graduation_year'][0] != NULL) {
        $qua_country            =   isset($request['qua_country'])?$request['qua_country']:NULL;
        $qua_university         =   isset($request['qua_university'])?$request['qua_university']:NULL;
        $qua_degree             =   $request['qua_degree'];
        $qua_graduation_year    =   $request['qua_graduation_year'];
        $count                  =   count($request['qua_degree']);
        for($q=0;$q<$count;$q++){
            if (isset($qua_university[$q])) {
                $check_uni              =   DB::table('universities')->where('name',$qua_university[$q])->first();
                if ($check_uni == NULL) {
                    $insert_uni         =   DB::table('universities')->insert([
                    'name'          =>  $qua_university[$q],
                    ]);
                }
            }

            if (isset($qua_degree[$q])) {
                $check_degree           =   DB::table('degrees')->where('name',$qua_degree[$q])->first();
                if ($check_degree == NULL) {
                    $insert_degree         =   DB::table('degrees')->insert([
                    'name'          =>  $qua_degree[$q],
                    ]);
                }
            }

            $qualification = DB::table('doctor_qualification')->insertGetId([
                'doctor_id'         => $doctor_id,
                'country'           => $qua_country[$q],
                'university'        => $qua_university[$q],
                'degree'            => $qua_degree[$q],
                'graduation_year'   => $qua_graduation_year[$q]
            ]);
        }
    }
        // insert certification data of doctor
    if ($request['cer_degree'][0]!=NULL || $request['cer_graduation_year'][0]!=NULL) {
        $cer_country            =  isset($request['cer_country'])?$request['cer_country']:NULL;
        $cer_university         =  isset($request['cer_university'])?$request['cer_university']:NULL;
        $cer_degree             = $request['cer_degree'];
        $cer_graduation_year    = $request['cer_graduation_year'];
        $count = count($request['cer_degree']);
        for($c=0;$c<$count;$c++){
            if (isset($cer_university[$c])) {
                $check_uni              =   DB::table('universities')->where('name',$cer_university[$c])->first();
                if ($check_uni == NULL) {
                    $insert_uni         =   DB::table('universities')->insert([
                        'name'          =>  $cer_university[$c],
                        'ui'            =>  1,
                    ]);
                }
            }
            if (isset($qua_degree[$c])) {
                $check_degree           =   DB::table('degrees')->where('name',$cer_degree[$c])->first();
                if ($check_degree == NULL) {
                    $insert_degree         =   DB::table('degrees')->insert([
                            'name'          =>  $cer_degree[$c],
                            'dc'            =>  1,
                    ]);
                }
            }

            $certification = DB::table('doctor_certification')->insertGetId([
                'doctor_id'         => $doctor_id,
                'country'           => $cer_country[$c],
                'university'        => $cer_university[$c],
                'title'             => $cer_degree[$c],
                'year'              => $cer_graduation_year[$c]
            ]);
        }
    }
}
public function centers_and_treatments($request,$doctor_id)
{
        $specializations    =   $request['specializations'];
        $procedures         =   $request['procedures'];
        $centers            =   $request['centers'];
        $deleteTempTreatment=   DB::table('temp_doctor_treatment')->where('doctor_id',$doctor_id)->delete();
        $delete_treatments  =   DB::table('doctor_treatments')->where('doctor_id',$doctor_id)->delete();
        $doctor_schedule    =   DB::table('center_doctor_schedule')->where('doctor_id',$doctor_id)->delete();
        if($specializations){
            foreach ($specializations as $s) {
                $insert_treatments[] = DB::table('temp_doctor_treatment')->insert([
                'doctor_id'    => $doctor_id,
                'treatment_id' => $s,
                ]);
            }
        }
        if ($procedures) {
            foreach ($procedures as $p) {
                $parent_id  =   DB::table('treatments')->where('id',$p)->first();
                $parent_id  =   $parent_id->parent_id;
                if ($parent_id) {
                $insert_treatments[] = DB::table('temp_doctor_treatment')->insert([
                    'doctor_id'     => $doctor_id,
                    'treatment_id'  => $p,
                    'parent_id'     => $parent_id,
                ]);
                }
            }
        }
        if ($centers) {
            $doctor_treatment = DB::table('temp_doctor_treatment')->where('doctor_id',$doctor_id)->get();
            foreach ($centers as $center_id) {
               $schedule_id     =   DB::table('center_doctor_schedule')->insertGetID([
                    'center_id' =>  $center_id,
                    'doctor_id' =>  $doctor_id,
                    'day_from'  =>  'Monday',
                    'day_to'    =>  'Saturday',
                    'time_from' =>  '11:00',
                    'time_to'   =>  '19:00',
                    'created_at'=> date('Y-m-d'),
                    'updated_at'=> date('Y-m-d'),
                ]);
                foreach($doctor_treatment as $dt){
                    $doctor_treatments = DB::table('doctor_treatments')->insert([
                        'doctor_id'    => $doctor_id,
                        'schedule_id'  => $schedule_id,
                        'treatment_id' => $dt->treatment_id,
                    ]);
                }
            }
        }
}

    public function update_centers_and_treatments($request,$id)
    {
         if ($request['centers']) {
            $centers            =   $request['centers'];
            $treatments         =   $request['treatments'];
    //Get all the Centers Which are in Center Doctor Schedule Table But are unselected by the User.
            $delete_treatments  =   DB::table('doctor_treatments')->where('doctor_id',$id)->delete();
            $delete_centers     =   DB::table('center_doctor_schedule')->where('doctor_id',$id)->whereNotIn('center_id',$centers)->delete();
            foreach($centers as $c){
                $center            =   DB::table('center_doctor_schedule')->where('doctor_id',$id)->where('center_id',$c)->first();
                if(!$center){
                    $insert_schedule[]    =   DB::table('center_doctor_schedule')
                    ->insertGetID([
                        'center_id'             => $c,
                        'doctor_id'             => $id,
                        'created_at'            => date('Y-m-d'),
                        'updated_at'            => date('Y-m-d'),
                    ]);
                }
                $center            =   DB::table('center_doctor_schedule')->where('doctor_id',$id)->where('center_id',$c)->first();
                if ($center) {
                    foreach ($treatments as $treatment_id) {
                        $add_Treatments = DB::table('doctor_treatments')->INSERT([
                           'schedule_id'    => $center->id,
                           'treatment_id'   => $treatment_id,
                           'doctor_id'      => $id,
                       ]);
                    }
                }
            }
        }
    }

public function getSecureInput($input){
        $data = [                                                                                           // insert data in customer table
        'name'                  => $input['name'],
        'last_name'             => $input['last_name'],
        'email'                 => $input['email'],
        'focus_area'            => $input['focus_area'],
        'gender'                => $input['gender'],
        'city_name'             => $input['city_name'],
        'address'               => $input['address'],
        'pmdc'                  => $input['pmdc'],
        'lat'                   => $input['lat'],
        'lng'                   => $input['lng'],
        'about'                 => $input['about'],
        'notes'                 => $input['notes'],
        'phone'                 => $input['phone'],
        'assistant_name'        => $input['assistant_name'],
        'speciality'            => $input['meta_speciality'],
        'assistant_phone'       => $input['assistant_phone'],
        'ad_spent'              => $input['ad_spent'],
        'revenue_share'         => $input['revenue_share'],
        'additional_details'    => $input['additional_details'],
        'experience'            => $input['experience'],
        'is_active'             => isset($input['is_active']) ? $input['is_active']:NULL,
        'on_web'                => isset($input['on_web']) ? $input['on_web']:NULL,
        'is_partner'            => isset($input['is_partner']) ? $input['is_partner']:0,
        'online_consultation'   => isset($input['online_consultation']) ? $input['online_consultation']:0,
        'meta_title'            => $input['meta_title'],
        'meta_description'      => $input['meta_description'],
        'url'                   => $input['url'],
        'meta_heading'          => $input['meta_heading'],
        'phone_verified'        => 1,
        'is_approved'           => 1,
    ];
    return $data;
}
public function edit_doctor($id)
{
    $doctor                 =   Doctor::where('id', $id)->with(['doctor_image','centers','treatments','doctor_qualification','doctor_certification',
        'doctor_partnership_images','doctor_partnership_files'])->first();
    $doctor_qualification   =   DB::table('doctor_qualification')->where('doctor_id',$id)->get();
    $doctor_certification   =   DB::table('doctor_certification')->where('doctor_id',$id)->get();
    $specialities           =   Treatment::where('is_active',1)->whereNull('parent_id')->orderBy('name','ASC')->get();
    $treatments             =   Treatment::where('is_active',1)->whereNotNull('parent_id')->orderBy('name','ASC')->get();
    $centers                =   Center::where('is_active',1)->orderBy('center_name','ASC')->get();
    $image                  =   DoctorImage::where('doctor_id',$id)->first();
    $ptnr_images            =   DoctorPartnershipImages::where('doctor_id',$id)->withTrashed()->get();
    $ptnr_files             =   DoctorPartnershipFiles::where('doctor_id',$id)->withTrashed()->get();
    $countries              =   DB::table('countries')->get();
    $degrees                =   DB::table('degrees')->orderBy('name','ASC')->get();
    $universities           =   DB::table('universities')->orderBy('name','ASC')->get();
    if (count($doctor->centers) > 0) {
        foreach ($doctor->centers as $d) {
            $center[]       =  $d->id;
        }
        $old_centers        =   array_values(array_unique($center));
    } else {
        $center[]           =   NULL;
        $old_centers        =   NULL;
    }
    if (count($doctor->treatments) > 0) {
        foreach ($doctor->treatments as $t) {
            $treatment[]    =   $t->id;
        }
        $old_treatments     =   array_values(array_unique($treatment));
        $old_speciality     = Treatment::whereIn('id',$old_treatments)->where('is_active',1)->whereNull('parent_id')->select('id')->get();
        $old_treatment      = Treatment::whereIn('id',$old_treatments)->where('is_active',1)->whereNotNull('parent_id')->select('id')->get();
    } else {
        $treatment[]        =   NULL;
        $old_speciality     =   NULL;
        $old_treatment      =   NULL;
        $old_treatments     =   NULL;
    }

    $d  = compact('doctor','old_speciality','old_treatment','ptnr_files','ptnr_images', 'image','doctor_certification', 'doctor_qualification', 'centers', 'old_centers','treatments','old_treatments','countries','degrees','universities','specialities');
    return $d;
}

public function imagesUpdateControll($request,$id,$doctor_name)
{
    $destinationPath = '/backend/uploads/doctors/';
    $image       = $request->file('picture');
        if($image != null){                                                                             // Delete all images first
            $table='doctor_images';
            $id_name='doctor_id';
            $delete_images = delete_images($id,$destinationPath,$table,$id_name);

            $filename    = str_slug($doctor_name).'-'.time().'.'.$image->getClientOriginalExtension(); // then insert images
            $table = "doctor_images";
            $id_name = "doctor_id";
            $insert_images = insert_images($id, $destinationPath,$table,$id_name, $filename,$image);
        }
        if(!($request->has('picture'))){
            $table='doctor_images';
            $id_name='doctor_id';
            $delete_images = delete_images($id,$destinationPath,$table,$id_name);
        }

        $new_images       = $request->file('ptnr_picture');
        $doctor_partnership_imagesPath = '/backend/uploads/doctor_partnership_images/';
        if ($request->has('old_ptnr_picture') && $new_images != null) {

            $all_images = DoctorPartnershipImages::where('doctor_id',$id)->select('picture')->get();
            foreach ($all_images as $value) {
                $db_old_images[] =  $value->picture;
            }
            $old_ptnr_picture = $request->old_ptnr_picture;
            if(count($all_images) != count($old_ptnr_picture)){
                $result = array_diff($db_old_images,$old_ptnr_picture);
                if ($result) {
                    foreach ($result as $image) {
                        $images= DB::table('doctor_partnership_images')->where('doctor_id', $id)->where('picture', $image)->first();

                        $resizeName  = '540x370-';
                        $resizeName2 = '80x55-';
                        $image_path  =  public_path().$doctor_partnership_imagesPath.$images->picture;
                        $imageMedium = public_path().$doctor_partnership_imagesPath.$resizeName.$images->picture;
                        $imageSmall  = public_path().$doctor_partnership_imagesPath.$resizeName2.$images->picture;
                        File::delete($image_path);
                        File::delete($imageMedium);
                        File::delete($imageSmall);
                        $images= DB::table('doctor_partnership_images')->where('doctor_id', $id)->where('picture', $image)->delete();

                    }
                }
            }

            $t = 0;
            foreach ($new_images as $image) {
                $all_ptnr_picture[] = str_slug($doctor_name).'-'.time().$t.'.'.$image->getClientOriginalExtension();
                $t++;
            }
            $im = 0;
            foreach ($all_ptnr_picture as $filename) {
                $table = "doctor_partnership_images";
                $id_name = "doctor_id";
                $insert_images = insert_images($id, $doctor_partnership_imagesPath,$table,$id_name, $filename,$new_images[$im]);
                $im++;
            }
        }
        if ($new_images && !($request->has('old_ptnr_picture'))) {
            $m=0;
            foreach ($new_images as $image) {
                $new_ptnr_picture[]    = str_slug($doctor_name).'-'.time().$m.'.'.$image->getClientOriginalExtension();
                $m++;
            }
            $all_ptnr_picture = $new_ptnr_picture;
            $im = 0;
            foreach ($all_ptnr_picture as $filename) {
                $table = "doctor_partnership_images";
                $id_name = "doctor_id";
                $insert_images = insert_images($id, $doctor_partnership_imagesPath,$table,$id_name, $filename,$new_images[$im]);
                $im++;
            }
        }
        if($new_images == null){
            if ($request->has('old_ptnr_picture')) {
                $all_images = DoctorPartnershipImages::where('doctor_id',$id)->select('picture')->get();
                foreach ($all_images as $value) {
                    $db_old_images[] =  $value->picture;
                }
                $old_ptnr_picture = $request->old_ptnr_picture;
                if(count($all_images) != count($old_ptnr_picture)){
                    $result = array_diff($db_old_images,$old_ptnr_picture);
                    if ($result) {
                        foreach ($result as $image) {
                            $images= DB::table('doctor_partnership_images')->where('doctor_id', $id)->where('picture', $image)->first();

                            $resizeName  = '540x370-';
                            $resizeName2 = '80x55-';
                            $image_path  =  public_path().$doctor_partnership_imagesPath.$images->picture;
                            $imageMedium = public_path().$doctor_partnership_imagesPath.$resizeName.$images->picture;
                            $imageSmall  = public_path().$doctor_partnership_imagesPath.$resizeName2.$images->picture;
                            File::delete($image_path);
                            File::delete($imageMedium);
                            File::delete($imageSmall);
                            $images= DB::table('doctor_partnership_images')->where('doctor_id', $id)->where('picture', $image)->delete();

                        }
                    }
                }
            }
            if(!($request->has('old_ptnr_picture'))){
                $table='doctor_partnership_images';
                $id_name='doctor_id';
                $delete_images = delete_images($id,$doctor_partnership_imagesPath,$table,$id_name);
            }
        }
        $center_partnership_file_Path = public_path().'/backend/uploads/doctor_partnership_files/';
        $new_files = $request->file('ptnr_files');

        if ($new_files != null) {
            if (!($request->has('old_ptnr_files'))) {
                $all_files = DoctorPartnershipFiles::where('doctor_id',$id)->select('file')->first();
                if($all_files != null){
                    $all_files = DoctorPartnershipFiles::where('doctor_id',$id)->select('file')->get();
                    foreach ($all_files as $value) {
                        $db_old_files[] =  $value->file;
                    }
                    $old_ptnr_files = $request->old_ptnr_files;
                    if(count($all_files) != count($old_ptnr_files)){
                        $result = array_diff($db_old_files,$old_ptnr_files);
                        if ($result) {
                            foreach ($result as $file) {
                                $file= DB::table('doctor_partnership_files')->where('doctor_id', $id)->where('file', $file)->first();
                                $file_path  =  public_path().$center_partnership_file_Path.$file->file;
                                File::delete($file_path);
                                $file= DB::table('doctor_partnership_files')->where('doctor_id', $id)->where('file', $file)->delete();
                            }
                        }
                    }
                }
            }
            $k = 0;
            foreach ($new_files as $file) {
                    // Uploading PDF file in FOLDER
                $filename   = time().$k.'.'.$file->getClientOriginalName();
                $file->move($center_partnership_file_Path,$filename);
                $insert = DB::table('doctor_partnership_files')->insert(['doctor_id' => $id, 'file' => $filename]);
                $k++;
            }
        }
        if($new_files == null){
            if ($request->has('old_ptnr_files')) {
                $all_files = DoctorPartnershipFiles::where('doctor_id',$id)->select('file')->get();
                foreach ($all_files as $value) {
                    $db_old_files[] =  $value->file;
                }
                $old_ptnr_files = $request->old_ptnr_files;
                if(count($all_files) != count($old_ptnr_files)){
                    $result = array_diff($db_old_files,$old_ptnr_files);
                    if ($result) {
                        foreach ($result as $file) {
                            $files= DB::table('doctor_partnership_files')->where('doctor_id', $id)->where('file', $file)->first();
                            $file_path  =  public_path().$center_partnership_file_Path.$files->file;
                            File::delete($file_path);
                            $images= DB::table('doctor_partnership_files')->where('doctor_id', $id)->where('file', $file)->delete();

                        }
                    }
                }
            }
            if(!($request->has('old_ptnr_files'))){
                $files= DB::table('doctor_partnership_files')->where('doctor_id', $id)->get();
                foreach ($files as $file) {
                    $file_path  =  public_path().$center_partnership_file_Path.$file->file;
                    File::delete($file_path);
                    $images= DB::table('doctor_partnership_files')->where('doctor_id', $id)->where('file', $file->file)->delete();
                }
            }
        }
    }

}
