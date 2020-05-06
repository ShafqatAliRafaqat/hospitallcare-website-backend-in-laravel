<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Treatment;
use App\Models\Admin\TreatmentImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class TreatmentController extends Controller
{

    public function index()
    {
        if (Auth::user()->can('view_treatment')) {
            $treatments = Treatment::where('parent_id', null)->orderBy('updated_at', 'DESC')->with('treatment_image')->get();
            return view('adminpanel.treatment.index', compact('treatments'));
        } else {
            abort(403);
        }
    }
    public function show_deleted()
    {
        if (Auth::user()->can('view_treatment')) {
            $treatments = Treatment::where('parent_id', null)->orderBy('updated_at', 'DESC')->with('treatment_image')->onlyTrashed()->get();
            return view('adminpanel.treatment.soft_deleted', compact('treatments'));
        } else {
            abort(403);
        }
    }

    public function create()
    {
        if (Auth::user()->can('create_treatment')) {
            return view('adminpanel.treatment.create');
        } else {
            abort(403);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create_treatment')) {
            $validate = $request->validate([
                'treatment_name'    => 'required|min:3',
                'payload_ur'        => 'string|nullable',
                'payload_en'        => 'string|nullable',
                'landing_page_url'  => 'string|nullable',
                'message'           => 'string|nullable|max:255',
                'link_description'  => 'string|nullable|max:30',
                'headline'          => 'string|nullable|max:30',
                'article'           => 'sometimes',
                'article_heading'   => 'string|nullable',
                'meta_title'        => 'string|nullable',
                'meta_description'  => 'string|nullable',
                'url'               => 'string|nullable',
                'meta_heading'      => 'string|nullable',
                'image_alt'         => 'string|nullable',
                'is_active'         => 'string|nullable',
                'show_in_menu'      => 'string|nullable',
                'position'          => 'string|nullable',
            ]);
            $treatment = Treatment::create([
                'name'              => $request->input('treatment_name'),
                'message'           => $request->input('message'),
                'link_description'  => $request->input('link_description'),
                'headline'          => $request->input('headline'),
                'payload_ur'        => $request->input('payload_ur'),
                'payload_en'        => $request->input('payload_en'),
                'landing_page_url'  => $request->input('landing_page_url'),
                'article'           => $request->input('article'),
                'article_heading'   => $request->input('article_heading'),
                'meta_title'        => $request->input('meta_title'),
                'meta_description'  => $request->input('meta_description'),
                'url'               => $request->input('url'),
                'meta_heading'      => $request->input('meta_heading'),
                'image_alt'         => $request->input('image_alt'),
                'is_active'         => $request->input('is_active'),
                'show_in_menu'      => $request->input('show_in_menu'),
                'position'          => $request->input('position'),
                'created_by'        => Auth::user()->id,
            ]);

            $destinationPath = '/backend/uploads/treatments/';
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
            session()->flash('success', 'Treatment Created Successfully');

            return redirect()->route('treatment.index');
        } else {
            abort(403);
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit_treatment')) {
            $treatment = Treatment::where('id', $id)->with('treatment_image')->first();
            return view('adminpanel.treatment.edit', compact('treatment'));
        } else {
            abort(403);
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit_treatment')) {
            $validate = $request->validate([
                'treatment_name'    => 'required|min:3',
                'payload_ur'        => 'string|nullable',
                'payload_en'        => 'string|nullable',
                'landing_page_url'  => 'string|nullable',
                'message'           => 'string|nullable|max:255',
                'link_description'  => 'string|nullable|max:30',
                'headline'          => 'string|nullable|max:30',
                'article'           => 'sometimes',
                'article_heading'   => 'string|nullable',
                'meta_title'        => 'string|nullable',
                'meta_description'  => 'string|nullable',
                'url'               => 'string|nullable',
                'meta_heading'      => 'string|nullable',
                'image_alt'      => 'string|nullable',
                'is_active'         => 'string|nullable',
                'show_in_menu'      => 'string|nullable',
                'position'          => 'string|nullable',
            ]);

            $treatment = Treatment::where('id', $id)->first();
            $treatment->update([
                'name'              => $request->input('treatment_name'),
                'message'           => $request->input('message'),
                'link_description'  => $request->input('link_description'),
                'headline'          => $request->input('headline'),
                'payload_ur'        => $request->input('payload_ur'),
                'payload_en'        => $request->input('payload_en'),
                'landing_page_url'  => $request->input('landing_page_url'),
                'article'           => $request->input('article'),
                'article_heading'   => $request->input('article_heading'),
                'meta_title'        => $request->input('meta_title'),
                'meta_description'  => $request->input('meta_description'),
                'url'               => $request->input('url'),
                'meta_heading'      => $request->input('meta_heading'),
                'image_alt'         => $request->input('image_alt'),
                'is_active'         => $request->input('is_active'),
                'show_in_menu'      => $request->input('show_in_menu'),
                'position'          => $request->input('position'),
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
            session()->flash('success', 'Treatment Updated Successfully');
            return redirect()->route('treatment.index');
        } else {
            abort(403);
        }
    }
    public function destroy(Request $request)                                                                    // Admin can soft Delete
    {
        $id = $request->id;
        if (Auth::user()->can('delete_treatment')) {
            $treatment = Treatment::where('id',$id)->first();
            $treatment->deleted_by  =   Auth::user()->id;
            $treatment->save();
            if ($treatment) {
                $procedures = Treatment::where('parent_id',$id)->with('treatment_image')->get();
                foreach($procedures as $procedure){
                    $procedure->delete();
                }
                $treatment->delete();
                // session()->flash('error', 'Treatment Deleted Successfully');
                return response()->json(["data" =>"Deleted"]);
            }

        } else {
            abort(403);
        }
    }
    public function per_delete(Request $request)                                                                // Admin can delete permanently data
    {
        if (Auth::user()->can('delete_treatment')) {
            $id = $request->id;
            $treatment = Treatment::where('id',$id)->with('treatment_image')->withTrashed()->first();
            if ($treatment) {
                $procedures = Treatment::where('parent_id',$id)->withTrashed()->with('treatment_image')->get();
                foreach($procedures as $procedure){
                    if ($procedure->treatment_image) {
                        foreach ($procedure->treatment_image as $image) {
                            $image_path = public_path() . "/backend/uploads/treatments/" . $image->picture;
                            File::delete($image_path);
                            $image->forcedelete();
                        }
                    }
                    $procedure->forcedelete();
                }
                if ($treatment->treatment_image) {
                    foreach ($treatment->treatment_image as $image) {
                        $image_path = public_path() . "/backend/uploads/treatments/" . $image->picture;
                        File::delete($image_path);
                        $image->forcedelete();
                    }
                }
                $treatment->forcedelete();
                // session()->flash('error', 'Treatment Deleted Successfully');
                return response()->json(["data" =>"Deleted"]);
            }

        } else {
            abort(403);
        }
    }
    public function restore(Request $request)                                                                            // admin can restore deleted data
    {
        if (Auth::user()->can('delete_treatment')) {
            $id = $request->id;
            $treatment = Treatment::where('id',$id)->withTrashed()->first();
            if ($treatment) {
                $procedures = Treatment::where('parent_id',$id)->withTrashed()->with('treatment_image')->get();
                foreach($procedures as $procedure){
                    $procedure->restore();
                }
                $treatment->restore();
                // session()->flash('error', 'Treatment Restore Successfully');
                return response()->json(["data" =>"Restore"]);
            }
        } else {
            abort(403);
        }
    }
}
