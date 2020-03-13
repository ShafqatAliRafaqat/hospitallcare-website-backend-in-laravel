<?php

namespace App\Http\Controllers\DoctorApiControllers;

use App\FCMDevice;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerApiResource\CustomerDoctorResource;
use App\Http\Resources\WebsiteApiResource\WebCenterResource;
use App\Http\Resources\WebsiteApiResource\WebDoctorResource;
use App\Models\Admin\Center;
use App\Models\Admin\Doctor;
use App\User;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CustomerDocumentsApiController extends Controller{
    public function upload(Request $request)
    {
        $store          =   null;
        $doctor_id      =   Auth::user()->doctor_id;
        $customer_id    =   $request->customer_id;
        $customer_name  =   str_slug(customerName($customer_id));
        // dd($customer_name);
        $title          =   $request->title;
        $description    =   $request->description;
        $ftype          =   $request->type; //file type sent from app- i.e P = Prescription
        $files          =   $request->file('picture');
        $path           =   'backend/uploads/customer_documents/';
        $i = 0;
        if ($files) {
            foreach ($files as $file) {
                $file_type  =   $file->guessExtension();
                $type   =   null;
                switch ($file_type) {
                    case "pdf":
                        $type = "pdf";
                        break;
                    case 'docx':
                        $type = "docx";
                        break;
                    case 'xlsx':
                        $type = "xlsx";
                        break;
                    case ('jpeg' || 'jpg' || 'png'):
                        $type = "image";
                        break;
                }
                    //name that we'll use for the coding
                    $slug       =   $customer_name.'_'.time().$i.'.'.$file->getClientOriginalExtension();
                    //Name that is to be shown to users
                    $file_name      =   $file->getClientOriginalName();
                    $create         =   DB::table("customer_documents")
                                        ->insert([
                                            'title'             =>  $title,
                                            'description'       =>  $description,
                                            'type'              =>  $ftype, // Type as in Prescription, Lab Reports, Radiology etc
                                            'customer_id'       =>  $customer_id,
                                            'created_by'         =>  $doctor_id,
                                            'file_name'         =>  $file_name,
                                            'slug'              =>  $slug,
                                            'file_type'         =>  $type, //Type as in Image, Pdf or docx
                                        ]);
                if ($type == "image") {
                    $store          =   insert_customer_documents($slug,$file,$path);
                    $store          =   1;
                } else if($type == "pdf" || $type == "docx" || $type == "xlsx"){
                    $store          =   $file->move($path,$slug);
                } else {
                    return response()->json(['message' => "Upload image of type jpg,jpeg,png or file of type pdf,docx or xlsx"],404);
                }
                $i++;
            }
            if ($store != null) {
                return response()->json(['message' => "Saved"],200);
            } else {
                return response()->json(['message' => "Not Saved"],404);
            }

        }else {
            return response()->json(['message' => "Please Upload a file"],404);
        }
    }
    public function show_all(Request $request)
    {
        $type           =   $request->type;
        $doctor_id      =   Auth::user()->doctor_id;
        $customer_id    =   $request->customer_id;
        $files          =   DB::table("customer_documents")->where('customer_id',$customer_id)->where('type',$type)->get();
        $data['images']['image']    = [];
        $data['files']['file']      = [];
        if ($files->count() > 0) {
            foreach ($files as $file) {
                $file_type                                  =   $file->file_type;
                $date                                       =   Carbon::parse($file->created_at);
                $created_at                                 =   $date->format('jS F Y');
                if ($file_type == "image") {
                    $data['images']['image'][]              =   'http://test.hospitallcare.com/backend/uploads/customer_documents/'.$file->slug;
                    $data['images']['image_id'][]           =   $file->id;
                    $data['images']['image_title'][]        =   $file->title;
                    $data['images']['image_created_at'][]   =   $created_at;

                    // $data['images'][]['id']      =   $file->id;
                } elseif ($file_type == "pdf" || $file_type == "docx" || $file_type == "xlsx") {
                    $data['files']['file'][]                =   'http://test.hospitallcare.com/backend/uploads/customer_documents/'.$file->slug;
                    $data['files']['file_id'][]             =   $file->id;
                    $data['files']['file_title'][]          =   $file->title;
                    $data['files']['file_created_at'][]     =   $created_at;
                }
            }
            return response()->json(['data' => $data],200);
        } else {
            return response()->json(['data' => $data],200);
        }
    }
    public function delete_files(Request $request)
    {
        $ids            =   $request->id;
        $doctor_id      =   Auth::user()->doctor_id;
        $customer_id    =   $request->customer_id;
        $path           =   'backend/uploads/customer_documents/';
        if ($ids) {
            $datas      =   DB::table('customer_documents')->where('customer_id',$customer_id)->whereIn('id',$ids)->get();
            if ($datas) {
                foreach ($datas as $data) {
                    $delete_in_db    =   DB::table('customer_documents')->where('id',$data->id)->delete();
                    if ($delete_in_db) {
                        $path.$data->slug;
                        File::delete($path.$data->slug);
                    }
                }
                return response()->json(['message' => 'Deleted Successfully','data' => $datas],200);
            } else {
                return response()->json(['message' => "No Files Found!"],404);
            }
        } else{
            return response()->json(['message' => "Select any file to delete"],200);
        }

    }
}
