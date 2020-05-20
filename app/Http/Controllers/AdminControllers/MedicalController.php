<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Center;
use App\Models\Admin\CenterImage;
use App\Models\Admin\CenterPartnershipFiles;
use App\Models\Admin\CenterPartnershipImages;
use App\Models\Admin\City;
use App\Models\Admin\Procedure;
use App\Models\Admin\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class MedicalController extends Controller
{

    public function index()
    {
        if( Auth::user()->can('view_medical_centers') ){
        $centers = DB::table('medical_centers as c')
                        ->leftjoin('center_images as i','i.center_id','c.id')
                        ->select(DB::raw('GROUP_CONCAT(i.picture) as images'),'c.*')
                        ->where('facilitator',0)
                        ->where('c.deleted_at',null)
                        ->where('c.is_approved',1)
                        ->groupby('c.id','c.focus_area','c.center_name','c.lat','c.lng','c.address','c.meta_title','c.meta_description','c.url', 'c.is_active','c.is_sponsered','c.created_at','c.updated_at')
                        ->orderBy('updated_at','DESC')
                        ->get();
        return view('adminpanel.medicalcenters.index', compact('centers'));
        } else {
            abort(403);
        }
    }
    public function show_deleted()
    {
        if( Auth::user()->can('view_medical_centers') ){
        $centers = DB::table('medical_centers as c')
                        ->leftjoin('center_images as i','i.center_id','c.id')
                        ->select(DB::raw('GROUP_CONCAT(i.picture) as images'),'c.*')
                        ->where('facilitator',0)
                        ->where('c.deleted_at','!=',null)
                        ->groupby('c.id','c.focus_area','c.center_name','c.lat','c.lng','c.address','c.meta_title','c.meta_description','c.url', 'c.is_active','c.is_sponsered','c.created_at','c.updated_at')
                        ->orderBy('updated_at','DESC')
                        ->get();
        return view('adminpanel.medicalcenters.soft_deleted', compact('centers'));
        } else {
            abort(403);
        }
    }

    public function create()
    {
        if( Auth::user()->can('create_medical_center') ){
            $treatments =   Treatment::where('is_active',1)->get();
            $cities     =   City::where('is_active',1)->get();
            $button     =   "Save Center";
            return view('adminpanel.medicalcenters.create', compact('treatments','button','cities'));
        } else {
            abort(403);
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        if( Auth::user()->can('create_medical_center') ){
            $validate = $request->validate([
                'treatment_id'          => 'required',
                'focus_area'            => 'required',
                'center_name'           => 'required',
                'assistant_name'        => 'sometimes',
                'assistant_phone'       => 'sometimes',
                'lng'                   => ['required', 'regex:/[0-9]([0-9]|-(?!-))+/'],
                'lat'                   => ['required', 'regex:/[0-9]([0-9]|-(?!-))+/'],
                'address'               => 'required',
                'city_name'             => 'sometimes',
                'city_id'               => 'sometimes',
                'area_id'               => 'sometimes',
                'area'                  => 'nullable',
                'picture'               => 'sometimes|image|max:2048',
                'ad_spent'              =>  'sometimes',
                'revenue_share'         =>  'sometimes',
                'additional_details'    =>  'sometimes',
                'article'               =>  'sometimes',
                'created_by'            => 'sometimes',
                'meta_title'            => 'string|nullable',
                'meta_description'      => 'string|nullable',
                'url'                   => 'string|nullable',
                'meta_heading'          => 'string|nullable',
                'image_alt'             => 'string|nullable',
                'is_active'             => 'nullable',
                'on_web'                => 'nullable',
                'is_approved'           => 'nullable',
            ]);
            $center                     = new Center;
            $center->center_name        = $request->input('center_name');
            $center->focus_area         = $request->input('focus_area');
            $center->assistant_name     = $request->input('assistant_name');
            $center->assistant_phone    = $request->input('assistant_phone');
            $center->phone              = $request->input('phone');
            $center->city_name          = $request->input('city_name');
            $center->city_id            = $request->input('city_id');
            $center->area_id            = $request->input('area_id');
            $center->address            = $request->input('address');
            $center->area               = $request->input('area');
            $center->lat                = $request->input('lat');
            $center->lng                = $request->input('lng');
            $center->facilitator        = 0;
            $center->is_approved        = 1;
            $center->is_active          = $request->input('is_active');
            $center->on_web             = $request->input('on_web');
            $center->ad_spent           = $request->input('ad_spent');
            $center->revenue_share      = $request->input('revenue_share');
            $center->additional_details = $request->input('additional_details');
            $center->article            = $request->input('article');
            $center->meta_title         = $request->input('meta_title');
            $center->meta_description   = $request->input('meta_description');
            $center->url                = $request->input('url');
            $center->meta_heading       = $request->input('meta_heading');
            $center->image_alt          = $request->input('image_alt');
            $center->created_by         = Auth::user()->id;
            $center->save();
            $center_id                  = $center->id;
        /*
            Defining th uploading path if not exist create new
        */
        $image       = $request->file('picture');
        $destinationPath = '/backend/uploads/centers/';
        if ($request->file('picture')) {
            if(!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
            }
            /*
                Uploading the Image to folder
            */
            $filename    = str_slug($request->input('center_name')).'-'.time().'.'.$image->getClientOriginalExtension(); // then insert images
            $table = "center_images";
            $id_name = "center_id";
            $insert_images = insert_images($center_id, $destinationPath,$table,$id_name, $filename,$image);
        }

        if ($request->file('ptnr_picture')) {
                $destinationPath = '/backend/uploads/center_partnership_images/';
                $images = $request->file('ptnr_picture');
                $i = 0;
                foreach ($images as $image) {
                    $filename = time().$i. '.' . $image->getClientOriginalExtension();
                    $location = public_path($destinationPath.$filename);
                    Image::make($image)->save($location);
                    $insert = DB::table('center_partnership_images')->insert(['center_id' => $center_id, 'picture' => $filename]);
                $i++;
                }
            }
            if ($request->file('ptnr_files')) {
                $destinationPath = public_path().'/backend/uploads/center_partnership_files/';
                $files = $request->file('ptnr_files');
                $k = 0;
                foreach ($files as $file) {
                    // Uploading PDF file in FOLDER
                    $filename   = time().$k.'.'.$file->getClientOriginalName();
                    $file->move($destinationPath,$filename);
                    $insert = DB::table('center_partnership_files')->insert(['center_id' => $center_id, 'file' => $filename]);
                    $k++;
                }
            }
        /*
            Saving the data to database in Medical_centers table
        */
        foreach (array_combine($request->treatment_id, $request->cost) as $treatment_id => $cost) {
           $add_Treatments = DB::table('center_treatments')->INSERT([
               'med_centers_id'=> $center_id,
               'treatments_id' => $treatment_id,
               'cost'          => $cost
            ]);
        }
        session()->flash('success', 'Center Created Successfully');
        return redirect()->route('medical.index');
        } else {
            abort(403);
        }
    }

    public function show($id)
    {
    if( Auth::user()->can('view_medical_centers') ){
        $center     = Center::where('id',$id)->with('center_treatment','center_image','center_partnership_images','center_partnership_files')->withTrashed()->first();
        // dd($center->center_partnership_images);
        return view('adminpanel.medicalcenters.show', compact('center'));
        } else {
            abort(403);
        }
    }

    public function edit($id)
    {
        if( Auth::user()->can('edit_medical_center') ){

            $center         =   Center::where('id',$id)->with('center_treatment')->withTrashed()->first();
            $image          =   CenterImage::where('center_id',$id)->withTrashed()->first();
            $ptnr_images    =   CenterPartnershipImages::where('center_id',$id)->withTrashed()->get();
            $ptnr_files     =   CenterPartnershipFiles::where('center_id',$id)->withTrashed()->get();
            $treatments     =   Treatment::where('is_active', 1)->with('treatment_center')->withTrashed()->get();
            $procedures     =   Procedure::where('is_active', 1)->withTrashed()->get();
            $cities_db      =   City::where('is_active',1)->get();
            $areas          =   DB::table('city_areas')->where('city_id',$center->city_id)->get();
            // dd($cities , $areas);
            return view('adminpanel.medicalcenters.edit', compact('center','ptnr_files','ptnr_images', 'image', 'treatments', 'procedures','cities_db','areas'));
        } else {
            abort(403);
        }
    }

    public function update(Request $request, $id)
    {
        if( Auth::user()->can('edit_medical_center') ){
                $validate = $request->validate([
                'treatment_id'          => 'required',
                'focus_area'            => 'required',
                'center_name'           => 'required',
                'assistant_name'        => 'sometimes',
                'assistant_phone'       => 'sometimes',
                'lng'                   => ['required', 'regex:/[0-9]([0-9]|-(?!-))+/'],
                'lat'                   => ['required', 'regex:/[0-9]([0-9]|-(?!-))+/'],
                'ad_spent'              =>  'sometimes',
                'revenue_share'         =>  'sometimes',
                'additional_details'    =>  'sometimes',
                'article'               =>  'sometimes',
                'updated_by'            => 'sometimes',
                'meta_title'            => 'string|nullable',
                'meta_description'      => 'string|nullable',
                'url'                   => 'string|nullable',
                'meta_heading'          => 'string|nullable',
                'image_alt'             => 'string|nullable',
                'city_name'             => 'sometimes',
                'city_id'               => 'sometimes',
                'area_id'               => 'sometimes',
                'address'               => 'sometimes',
                'area'                  => 'nullable',
                'is_active'             => 'nullable',
                'on_web'                => 'nullable',
                ]);
            $destinationPath = '/backend/uploads/centers/';
                $image       = $request->file('picture');
                if($image != null){                                  // Delete all images first
                    $table='center_images';
                    $id_name='center_id';
                    $delete_images = delete_images($id,$destinationPath,$table,$id_name);

                    $filename    = str_slug($request->input('center_name')).'-'.time().'.'.$image->getClientOriginalExtension(); // then insert images
                    $table = "center_images";
                    $id_name = "center_id";
                    $insert_images = insert_images($id, $destinationPath,$table,$id_name, $filename,$image);
                }
                if(!($request->has('picture'))){
                    $table='center_images';
                    $id_name='center_id';
                    $delete_images = delete_images($id,$destinationPath,$table,$id_name);
                }

                $new_images       = $request->file('ptnr_picture');
                $center_partnership_imagesPath = '/backend/uploads/center_partnership_images/';
                if ($request->has('old_ptnr_picture') && $new_images != null) {

                    $all_images = CenterPartnershipImages::where('center_id',$id)->select('picture')->get();
                    foreach ($all_images as $value) {
                        $db_old_images[] =  $value->picture;
                    }
                    $old_ptnr_picture = $request->old_ptnr_picture;
                    if(count($all_images) != count($old_ptnr_picture)){
                        $result = array_diff($db_old_images,$old_ptnr_picture);
                        if ($result) {
                            foreach ($result as $image) {
                                $images= DB::table('center_partnership_images')->where('center_id', $id)->where('picture', $image)->first();

                                $resizeName  = '540x370-';
                                $resizeName2 = '80x55-';
                                $image_path  =  public_path().$center_partnership_imagesPath.$images->picture;
                                $imageMedium = public_path().$center_partnership_imagesPath.$resizeName.$images->picture;
                                $imageSmall  = public_path().$center_partnership_imagesPath.$resizeName2.$images->picture;
                                File::delete($image_path);
                                File::delete($imageMedium);
                                File::delete($imageSmall);
                                $images= DB::table('center_partnership_images')->where('center_id', $id)->where('picture', $image)->delete();

                            }
                        }
                    }

                    $t = 0;
                    foreach ($new_images as $image) {
                            $all_ptnr_picture[] = str_slug($request->input('center_name')).'-'.time().$t.'.'.$image->getClientOriginalExtension();
                        $t++;
                        }
                        $im = 0;
                        // dd($all_ptnr_picture);
                        foreach ($all_ptnr_picture as $filename) {
                            $table = "center_partnership_images";
                            $id_name = "center_id";
                            $insert_images = insert_images($id, $center_partnership_imagesPath,$table,$id_name, $filename,$new_images[$im]);
                            $im++;
                    }
                }
                    if ($new_images && !($request->has('old_ptnr_picture'))) {
                        $m=0;
                        foreach ($new_images as $image) {
                            $new_ptnr_picture[]    = str_slug($request->input('center_name')).'-'.time().$m.'.'.$image->getClientOriginalExtension();
                        $m++;
                        }
                       $all_ptnr_picture = $new_ptnr_picture;
                        $im = 0;
                        foreach ($all_ptnr_picture as $filename) {
                            $table = "center_partnership_images";
                            $id_name = "center_id";
                            $insert_images = insert_images($id, $center_partnership_imagesPath,$table,$id_name, $filename,$new_images[$im]);
                            $im++;
                        }
                    }
                    if($new_images == null){
                        if ($request->has('old_ptnr_picture')) {
                            $all_images = CenterPartnershipImages::where('center_id',$id)->select('picture')->get();
                            foreach ($all_images as $value) {
                                $db_old_images[] =  $value->picture;
                            }
                            $old_ptnr_picture = $request->old_ptnr_picture;
                            if(count($all_images) != count($old_ptnr_picture)){
                                $result = array_diff($db_old_images,$old_ptnr_picture);
                                if ($result) {
                                    foreach ($result as $image) {
                                        $images= DB::table('center_partnership_images')->where('center_id', $id)->where('picture', $image)->first();

                                        $resizeName  = '540x370-';
                                        $resizeName2 = '80x55-';
                                        $image_path  =  public_path().$center_partnership_imagesPath.$images->picture;
                                        $imageMedium = public_path().$center_partnership_imagesPath.$resizeName.$images->picture;
                                        $imageSmall  = public_path().$center_partnership_imagesPath.$resizeName2.$images->picture;
                                        File::delete($image_path);
                                        File::delete($imageMedium);
                                        File::delete($imageSmall);
                                        $images= DB::table('center_partnership_images')->where('center_id', $id)->where('picture', $image)->delete();

                                    }
                                }
                            }
                        }
                        if(!($request->has('old_ptnr_picture'))){
                            $table='center_partnership_images';
                            $id_name='center_id';
                            $delete_images = delete_images($id,$center_partnership_imagesPath,$table,$id_name);
                        }
                    }
                    $center_partnership_file_Path = public_path().'/backend/uploads/center_partnership_files/';
                    $new_files = $request->file('ptnr_files');

                    if ($new_files != null) {
                        if (!($request->has('old_ptnr_files'))) {
                        $all_files = CenterPartnershipFiles::where('center_id',$id)->select('file')->first();
                        if($all_files != null){
                        $all_files = CenterPartnershipFiles::where('center_id',$id)->select('file')->get();
                            foreach ($all_files as $value) {
                                $db_old_files[] =  $value->file;
                            }
                            $old_ptnr_files = $request->old_ptnr_files;
                            if(count($all_files) != count($old_ptnr_files)){
                                $result = array_diff($db_old_files,$old_ptnr_files);
                                if ($result) {
                                    foreach ($result as $file) {
                                        $file= DB::table('center_partnership_files')->where('center_id', $id)->where('file', $file)->first();
                                        $file_path  =  public_path().$center_partnership_file_Path.$file->file;
                                        File::delete($file_path);
                                        $file= DB::table('center_partnership_files')->where('center_id', $id)->where('file', $file)->delete();
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
                            $insert = DB::table('center_partnership_files')->insert(['center_id' => $id, 'file' => $filename]);
                            $k++;
                        }
                    }
                        if($new_files == null){
                            if ($request->has('old_ptnr_files')) {
                                $all_files = CenterPartnershipFiles::where('center_id',$id)->select('file')->get();
                                foreach ($all_files as $value) {
                                    $db_old_files[] =  $value->file;
                                }
                                $old_ptnr_files = $request->old_ptnr_files;
                                if(count($all_files) != count($old_ptnr_files)){
                                    $result = array_diff($db_old_files,$old_ptnr_files);
                                    if ($result) {
                                        foreach ($result as $file) {
                                            $files= DB::table('center_partnership_files')->where('center_id', $id)->where('file', $file)->first();
                                            $file_path  =  public_path().$center_partnership_file_Path.$files->file;
                                            File::delete($file_path);
                                             $images= DB::table('center_partnership_files')->where('center_id', $id)->where('file', $file)->delete();

                                        }
                                    }
                                }
                            }
                            if(!($request->has('old_ptnr_files'))){
                                $files= DB::table('center_partnership_files')->where('center_id', $id)->get();
                                foreach ($files as $file) {
                                    $file_path  =  public_path().$center_partnership_file_Path.$file->file;
                                    File::delete($file_path);
                                     $images= DB::table('center_partnership_files')->where('center_id', $id)->where('file', $file->file)->delete();
                                }
                            }
                        }
                    $center                     = Center::find($id);
                    $center->center_name        = $request->input('center_name');
                    $center->focus_area         = $request->input('focus_area');
                    $center->assistant_name     = $request->input('assistant_name');
                    $center->assistant_phone    = $request->input('assistant_phone');
                    $center->phone              = $request->input('phone');
                    $center->city_name          = $request->input('city_name');
                    $center->address            = $request->input('address');
                    $center->city_id            = $request->input('city_id');
                    $center->area_id            = $request->input('area_id');
                    $center->area               = $request->input('area');
                    $center->lat                = $request->input('lat');
                    $center->lng                = $request->input('lng');
                    $center->ad_spent           = $request->input('ad_spent');
                    $center->revenue_share      = $request->input('revenue_share');
                    $center->additional_details = $request->input('additional_details');
                    $center->article            = $request->input('article');
                    $center->is_active          = $request->input('is_active');
                    $center->on_web             = $request->input('on_web');
                    $center->is_approved        = 1;
                    $center->meta_title         = $request->input('meta_title');
                    $center->meta_description   = $request->input('meta_description');
                    $center->url                = $request->input('url');
                    $center->meta_heading       = $request->input('meta_heading');
                    $center->image_alt          = $request->input('image_alt');
                    $center->updated_by         = Auth::user()->id;
                    $center->save();

                    if($request->input('treatment_id')) {

                    $deleted= DB::table('center_treatments')->where('med_centers_id',$id)->delete();

                    foreach (array_combine($request->treatment_id, $request->cost) as $treatment_id => $cost) {
                        $add_Treatments = DB::table('center_treatments')->INSERT([
                            'med_centers_id' => $id,
                            'treatments_id'  => $treatment_id,
                            'cost'           => $cost
                        ]);
                    }
                }
                session()->flash('success', 'Medical Center Updated Successfully');
                return redirect()->route('medical.index');

        } else {
            abort(403);
        }
    }

    public function Tempcenter()
    {
        if( Auth::user()->can('view_medical_centers') ){
        $centers = DB::table('medical_centers as c')
                        ->leftjoin('center_images as i','i.center_id','c.id')
                        ->select(DB::raw('GROUP_CONCAT(i.picture) as images'),'c.*')
                        ->where('facilitator',0)
                        ->where('c.deleted_at',null)
                        ->where('c.is_approved',0)
                        ->groupby('c.id','c.focus_area','c.center_name','c.lat','c.lng','c.address','c.meta_title','c.meta_description','c.url', 'c.is_active','c.is_sponsered','c.created_at','c.updated_at')
                        ->orderBy('updated_at','DESC')
                        ->get();
        return view('adminpanel.medicalcenters.approval_index', compact('centers'));
        } else {
            abort(403);
        }
    }

    public function approve_center_edit($id)
    {
        if( Auth::user()->can('edit_medical_center') ){
            $center     = Center::where('id',$id)->withTrashed()->first();
            $treatments = Treatment::where('is_active',1)->get();
            $button     = "Approve Center";
            return view('adminpanel.medicalcenters.approval_edit', compact('center','treatments','button'));
        } else {
            abort(403);
        }
    }

public function approve_center(Request $request, $id)
{
    dd($request->input());
}
    public function destroy($id)                                                                            // Soft delete
    {
        if( Auth::user()->can('delete_medical_center') ){
        $medical = Center::where('id',$id)->with('center_image')->first();
            $medical->delete();
            session()->flash('success', 'Medical Center Deleted Successfully');
            return redirect()->route('medical.index');
        }
      }
    public function per_delete($id)                                                                            // Permanent delete data
    {
        if (Auth::user()->can('delete_medical_center')) {
            $medical = Center::where('id', $id)->with('center_image')->withTrashed()->first();
            if ($medical) {                       //DELETING image from Storage
                $center_image = DB::table('center_images')->where('center_id', $id)->select('picture')->first();
                if ($center_image) {
                    $image_path   =  public_path()."/backend/uploads/centers/".$center_image->picture;
                    $imageMedium  =  public_path()."/backend/uploads/centers/540x370-".$center_image->picture;
                    $imageSmall   =  public_path()."/backend/uploads/centers/80x55-".$center_image->picture;

                    File::delete($image_path);
                    File::delete($imageMedium);
                    File::delete($imageSmall);

                    //DELETING Image from Database
                    if (isset($medical->center_image)) {
                        $center_image = $medical->center_image->forcedelete();
                    }
                }
                // DELETING Center Treatments from Database
                $delete_Med_Treatments= DB::table('center_treatments')->where('med_centers_id', $id)->delete();
                $medical->forcedelete();
                session()->flash('success', 'Medical Center Deleted Successfully');
                return redirect()->back();
            }
        }
    }
    public function restore($id)                                                                                // Restore Deleted data
    {
        if( Auth::user()->can('delete_medical_center') ){
        $medical = Center::where('id',$id)->with('center_image')->withTrashed()->first();
            $medical->restore();
            session()->flash('success', 'Medical Center Restore Successfully');
            return redirect()->route('medical.index');
        }
      }
    public function custom(){
        return 'Custom Route Of my Resource Controller';
    }

}
