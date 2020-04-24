<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Procedure;
use App\Models\Admin\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use App\Models\Admin\TreatmentImages;
class ProcedureController extends Controller
{

    public function index()
    {
        $procedures = Treatment::where('parent_id','<>',null)->orderBy('updated_at', 'DESC')->with('treatment_image')->get();

        return view('adminpanel.procedure.index', compact('procedures'));
    }
    public function show_deleted()
    {
        $procedures = Treatment::where('parent_id','<>',null)->orderBy('updated_at', 'DESC')->onlyTrashed()->with('treatment_image')->get();

        return view('adminpanel.procedure.soft_deleted', compact('procedures'));
    }

    public function create()
    {
        $treatments = Treatment::where('is_active', 1)->where('parent_id', null)->orderBy('name','ASC')->get();
        return view('adminpanel.procedure.create', compact('treatments'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'treatment_id'          => 'required|exists:treatments,id',
            'procedure_name'        => 'required|min:3',
            'payload_ur'            => 'string|nullable',
            'payload_en'            => 'string|nullable',
            'landing_page_url'      => 'string|nullable',
            'message'               => 'string|nullable|max:256',
            'link_description'      => 'string|nullable|max:30',
            'headline'              => 'string|nullable|max:30',
            'article'               => 'sometimes',
            'article_heading'       => 'string|nullable',
            'meta_title'            => 'string|nullable',
            'meta_description'      => 'string|nullable',
            'url'                   => 'string|nullable',
            'meta_heading'          => 'string|nullable',
            'is_active'             => 'string|nullable',
            'show_in_menu'          => 'string|nullable',
        ],[ 'treatment_id.required' => 'Select at least one treatment' ]);
        $treatment = Treatment::create([
            'parent_id'        =>   $request->input('treatment_id'),
            'name'             =>   $request->input('procedure_name'),
            'message'           =>  $request->input('message'),
            'link_description'  =>  $request->input('link_description'),
            'headline'          =>  $request->input('headline'),
            'payload_ur'        =>  $request->input('payload_ur'),
            'payload_en'        =>  $request->input('payload_en'),
            'landing_page_url'  =>  $request->input('landing_page_url'),
            'article'           =>   (isset($request->article)) ? $request->article : NULL,
            'article_heading'   =>   $request->input('article_heading'),
            'meta_title'        =>   $request->input('meta_title'),
            'meta_description'  =>   $request->input('meta_description'),
            'url'               =>   $request->input('url'),
            'meta_heading'      =>   $request->input('meta_heading'),
            'is_active'         =>   $request->input('is_active'),
            'created_by'        =>   Auth::user()->id,
            'show_in_menu'      =>   $request->input('show_in_menu')
        ]);
        if ($request->file('picture')) {
        $destinationPath = '/backend/uploads/treatments/';
        if(!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }

        $images = $request->file('picture');

            $i = 0;
                foreach ($images as $image) {
                    $filename = time().$i. '.' . $image->getClientOriginalExtension();

                    $location = public_path('backend/uploads/treatments/' . $filename);
                    Image::make($image)->save($location);
                    if ($treatment) {
                        TreatmentImages::create([
                        'treatment_id'  => $treatment->id,
                        'picture'       => $filename,
                    ]);
                    }
                    $i++;
                }
            }
        session()->flash('success', 'Procedure Created Successfully');
        return redirect()->route('procedure.index');
    }

    public function edit($id)
    {
        $procedure  = Treatment::where('id',$id)->with('treatment_image')->first();
        $treatments = Treatment::where('parent_id', null)->whereIsActive(1)->with('treatment_image')->get();
        return view('adminpanel.procedure.edit', compact('procedure', 'treatments'));
    }

    public function update(Request $request, $id)
    {
            $validate = $request->validate([
                'treatment_id'      => 'required|exists:treatments,id',
                'name'              => 'required|min:3',
                'payload_ur'        => 'string|nullable',
                'payload_en'        => 'string|nullable',
                'landing_page_url'  => 'string|nullable',
                'message'           => 'string|nullable|max:256',
                'link_description'  => 'string|nullable|max:30',
                'headline'          => 'string|nullable|max:30',
                'article'           => 'sometimes',
                'article_heading'   => 'string|nullable',
                'meta_title'        => 'string|nullable',
                'meta_description'  => 'string|nullable',
                'url'               => 'string|nullable',
                'meta_heading'      => 'string|nullable',
                'is_active'         => 'nullable',
                'show_in_menu'      => 'nullable',
            ],[ 'treatment_id.required' => 'Select at least one treatment' ]);

            $treatment = Treatment::where('id', $id)->first();
            $treatment->update([
                'parent_id'         =>  $request->input('treatment_id'),
                'name'              =>  $request->input('name'),
                'message'           =>  $request->input('message'),
                'link_description'  =>  $request->input('link_description'),
                'headline'          =>  $request->input('headline'),
                'payload_ur'        =>  $request->input('payload_ur'),
                'payload_en'        =>  $request->input('payload_en'),
                'landing_page_url'  =>  $request->input('landing_page_url'),
                'article'           =>  (isset($request->article)) ? $request->article : NULL,
                'article_heading'   =>  $request->input('article_heading'),
                'meta_title'        =>  $request->input('meta_title'),
                'meta_description'  =>  $request->input('meta_description'),
                'url'               =>  $request->input('url'),
                'meta_heading'      =>  $request->input('meta_heading'),
                'is_active'         =>  $request->input('is_active'),
                'show_in_menu'      =>  $request->input('show_in_menu'),
                'updated_by'        => Auth::user()->id,
            ]);

            $destinationPath = public_path('/backend/uploads/treatments/');
            $pic = $request->picture;
                if($pic != null){
                    $old_treatment_images = TreatmentImages::where('treatment_id', $id)->whereNotIn('picture',$pic)->get();
                }else{
                $old_treatment_images = TreatmentImages::where('treatment_id', $id)->get();
                }
            if (count($old_treatment_images)>0) {
                    foreach ($old_treatment_images as $treatment_image) {
                        $oldImage = $treatment_image->picture;
                        $oldImageLoc = public_path($destinationPath . $oldImage);
                        File::delete($oldImageLoc);
                        $treatment_image->delete();
                    }
            }
            if ($request->file('picture')) {
            $originalImages = $request->file('picture');
            if ($originalImages) {
                $i = 0;
                foreach ($originalImages as $image) {
                    $filename = time().$i. '.' . $image->getClientOriginalExtension();

                    $location = public_path('backend/uploads/treatments/' . $filename);
                    Image::make($image)->save($location);
                    if ($treatment) {
                        TreatmentImages::create([
                        'treatment_id'  => $treatment->id,
                        'picture'       => $filename,
                    ]);
                    }
                    $i++;
                }
            }
        }
        session()->flash('success', 'Procedure Updated Successfully');
        return redirect()->route('procedure.index');
    }

    public function destroy(Request $request)
    {
        if (Auth::user()->can('delete_treatment')) {
            $id = $request->id;
            $treatment = Treatment::where('id', $id)->withTrashed()->first();
            $treatment->deleted_by  =   Auth::user()->id;
            $treatment->save();
            $treatment->delete();
            // session()->flash('error', 'Procedure Deleted Successfully');
            return response()->json(["data" =>"Restore"]);
        } else {
            abort(403);
        }
    }
    public function per_delete(Request $request)                                                // admin can delete permanently data
    {
        if (Auth::user()->can('delete_treatment')) {
            $id = $request->id;
            $treatment = Treatment::where('id', $id)->with('treatment_image')->withTrashed()->first();
            if ($treatment->treatment_image != NULL) {
                if ($treatment->treatment_image) {
                    foreach ($treatment->treatment_image as $image) {
                        $image_path = public_path() . "/backend/uploads/treatments/" . $image->picture;
                        File::delete($image_path);
                        $image->forcedelete();
                    }
                }
            }
            $treatment->forcedelete();
            // session()->flash('error', 'Procedure Deleted Successfully');
            return response()->json(["data" =>"Restore"]);
        } else {
            abort(403);
        }
    }
    public function restore(Request $request)                                                                        //admin can restore deleted data
    {
        if (Auth::user()->can('delete_treatment')) {
            $id = $request->id;
            $treatment = Treatment::where('id', $id)->withTrashed()->first();
            $treatment->restore();
            // session()->flash('error', 'Procedure Restore Successfully');
            return response()->json(["data" =>"Restore"]);
        } else {
            abort(403);
        }
    }

}
