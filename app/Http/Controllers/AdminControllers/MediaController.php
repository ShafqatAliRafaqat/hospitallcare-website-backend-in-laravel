<?php

namespace App\Http\Controllers\AdminControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Media;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MediaController extends Controller
{
    public function index(){
        if ( Auth::user()->can('media_hub') ) {
            $media = Media::orderByDesc('updated_at')->get();
            return view('adminpanel.mediahub.media.index', compact('media'));
        } else {
            abort(403);
        }
    }
    public function show_deleted()
    {
        $media = Media::orderByDesc('updated_at')->onlyTrashed()->get();
        return view('adminpanel.mediahub.media.soft_deleted', compact('media'));
    }
    public function create(){
        if ( Auth::user()->can('media_hub') ) {
            return view('adminpanel.mediahub.media.create');
        } else {
            abort(403);
        }
    }
    public function store(Request $request){
        if ( Auth::user()->can('media_hub') ) {
            $validator = $request->validate([
                'link'          => 'required',
                'title'         => 'required',
                'description'   => 'required',
            ]);
            $media = Media::create([
                'link'              => $request->link,
                'title'             => $request->title,
                'description'       => $request->description,
                'meta_title'        => $request->meta_title,
                'meta_description'  => $request->meta_description,
                'url'         => $request->url,
                'is_active'         => isset($request->is_active)? $request->is_active :0,
                'position'          => $request->position,
                'created_by'        =>   Auth::user()->id,
                'updated_by'        =>   Auth::user()->id,
            ]);
            Session::flash('success','Media Created Successfully !');
            return redirect()->route('media.index');
        } else {
            abort(403);
        }
    }
    public function edit($id){
        if ( Auth::user()->can('media_hub') ) {
            $media = Media::where('id',$id)->first();
            return view('adminpanel.mediahub.media.edit', compact('media'));
        } else {
            abort(403);
        }
    }
    public function show(){
    
    }
    public function update(Request $request , $id){
        if ( Auth::user()->can('media_hub') ) {
            $validator = $request->validate([
                'link'          => 'required',
                'title'         => 'required',
                'description'   => 'required',
            ]);
            $media = Media::where('id',$id)->update([
                'link'              => $request->link,
                'title'             => $request->title,
                'description'       => $request->description,
                'meta_title'        => $request->meta_title,
                'meta_description'  => $request->meta_description,
                'url'         => $request->url,
                'is_active'         => isset($request->is_active)? $request->is_active :0,
                'position'          => $request->position,
                'updated_by'        =>   Auth::user()->id,
            ]);
            Session::flash('success','Media Updated Successfully !');
            return redirect()->route('media.index');
        } else {
            abort(403);
        }
    }
    public function destroy($id){
        if ( Auth::user()->can('media_hub') ) {
            $media = Media::where('id',$id)->update([
                'deleted_by' => Auth::user()->id,
                'deleted_at' => Carbon::now()->toDateTimeString(),
            ]);
            $media = Media::where('id',$id)->delete();
            Session::flash('success','Media Updated Successfully !');
            return redirect()->route('media.index');
        } else {
            abort(403);
        }
    }
    public function per_delete(Request $request)                                                // admin can delete permanently data
    {
        if (Auth::user()->can('delete_treatment')) {
            Media::where('id', $request->id)->withTrashed()->forcedelete();
            return response()->json(["data" =>"Restore"]);
        } else {
            abort(403);
        }
    }
    public function restore(Request $request)                                                                        //admin can restore deleted data
    {
        if (Auth::user()->can('delete_treatment')) {
            Media::where('id', $request->id)->withTrashed()->update([
                'deleted_at' =>null,
            ]);
            return response()->json(["data" =>$request->id]);
        } else {
            abort(403);
        }
    }
}
