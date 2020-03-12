<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Resources\TreatmentResource;
use App\Models\Admin\TreatmentImages;

class TreatmentApiController extends Controller
{
    public function index()
    {
        if( Auth::user()->can('view_treatment') ) {

            $treatments = Treatment::orderBy('updated_at', 'DESC')->with('treatment_image')->get();
            if ($treatments) {
                return TreatmentResource::collection($treatments);
            }else{
                return response()->json(['error'=>"Please enter valid treatment id"], 200);
            }
        } else {
            return response()->json(['error'=>"User have no rights to access this page"], 200);
        }
    }

    public function store(Request $request)
    {
        if( Auth::user()->can('create_treatment') ) {
            $validate = $request->validate([
                'treatment_name'    => 'required|min:3',
                'message'           => 'required',
                'link_description'  => 'required',
                'headline'          => 'required',
                // 'picture' => 'image|required|mimes:jpeg,png,jpg,',
                'article'           => 'required|string',
                'meta_title'        => 'string|nullable',
                'meta_description'  => 'string|nullable',
                'url'         => 'string|nullable',
                'is_active'         => 'string|nullable',
                'show_in_menu'      => 'string|nullable',
            ]);
            $treatment = Treatment::create([
                'name'              => $request->input('treatment_name'),
                'message'           => $request->input('message'),
                'link_description'  => $request->input('link_description'),
                'headline'          => $request->input('headline'),
                'article'           => $request->input('article'),
                'meta_title'        => $request->input('meta_title'),
                'meta_description'  => $request->input('meta_description'),
                'url'         => $request->input('url'),
                'is_active'         => $request->input('is_active'),
                'show_in_menu'      => $request->input('show_in_menu'),
            ]);

            $destinationPath = '/backend/uploads/treatments/';
            $images = $request->file('picture');

            foreach ($images as $image) {
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $location = public_path($destinationPath . $filename);
                Image::make($image)->save($location);
                if ($treatment) {
                    TreatmentImages::create([
                    'treatment_id'  => $treatment->id,
                    'picture'       => $filename,
                ]);
                }
            }
            return TreatmentResource::make($treatment);
        } else {
            return response()->json(['error'=>"User have no rights to access this page"], 200);
        }
    }

    public function show($id)
    {
        if( Auth::user()->can('edit_treatment') ) {

            $treatment = Treatment::where('id', $id)->with('treatment_image')->first();

            if ($treatment) {
                return TreatmentResource::make($treatment);
            }else{
                return response()->json(['error'=>"Please enter valid treatment id"], 200);
            }
        } else {
            return response()->json(['error'=>"User have no rights to access this page"], 200);
        }
    }

    public function updates(Request $request, $id)
    {
        if( Auth::user()->can('edit_treatment') ) {

                $validate = $request->validate([
                    'budget'           => 'required|string',
                ]);
                $treatment = Treatment::find($id)->get();
                if ($treatment) {
                    $treatment = Treatment::where('id', $id)->update([
                        'budget'           => $request->input('budget'),
                    ]);
                    return TreatmentResource::make($treatment);
                }else{
                    return response()->json(['error'=>"Please enter valid treatment id"], 200);
                }

        } else {
            return response()->json(['error'=>"User have no rights to access this page"], 200);
        }
    }
    public function destroy($id)
    {
        if( Auth::user()->can('delete_treatment') ) {

            $treatment = Treatment::where('id',$id)->with('treatment_image')->first();
            if ($treatment) {
                if ($treatment->treatment_image) {
                    foreach ($treatment->treatment_image as $image) {
                        $image_path = public_path() . "/backend/uploads/treatments/" . $image->picture;
                        File::delete($image_path);
                    }
                }
                $treatment->delete();
                $massage = "Treatment Deleted Successfully";
                return response()->json([$massage], 200);
            }else{
                return response()->json(['error'=>"Please enter valid treatment id"], 200);
            }

        }else {
            return response()->json(['error'=>"User have no rights to access this page"], 200);
        }
    }
}
