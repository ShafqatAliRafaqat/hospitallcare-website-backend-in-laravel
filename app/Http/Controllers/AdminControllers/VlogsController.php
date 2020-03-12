<?php

namespace App\Http\Controllers\AdminControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Vlogs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class VlogsController extends Controller
{
    public function index(){
        if ( Auth::user()->can('media_hub') ) {
            $vlogs = Vlogs::orderByDesc('updated_at')->get();
            return view('adminpanel.mediahub.vlogs.index', compact('vlogs'));
        } else {
            abort(403);
        }
    }
    public function show_deleted()
    {
        $vlogs = Vlogs::orderByDesc('updated_at')->onlyTrashed()->get();
        return view('adminpanel.mediahub.vlogs.soft_deleted', compact('vlogs'));
    }
    public function create(){
        if ( Auth::user()->can('media_hub') ) {
            return view('adminpanel.mediahub.vlogs.create');
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
            $media = Vlogs::create([
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
            Session::flash('success','Vlog Created Successfully !');
            return redirect()->route('vlogs.index');
        } else {
            abort(403);
        }
    }
    public function edit($id){
        if ( Auth::user()->can('media_hub') ) {
            $vlogs = Vlogs::where('id',$id)->first();
            return view('adminpanel.mediahub.vlogs.edit', compact('vlogs'));
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
            $media = Vlogs::where('id',$id)->update([
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
            Session::flash('success','Vlog Updated Successfully !');
            return redirect()->route('vlogs.index');
        } else {
            abort(403);
        }
    }
    public function destroy($id){
        if ( Auth::user()->can('media_hub') ) {
            $Vlogs = Vlogs::where('id',$id)->update([
                'deleted_by' => Auth::user()->id,
                'deleted_at' => Carbon::now()->toDateTimeString(),
            ]);
            $Vlogs = Vlogs::where('id',$id)->delete();
            Session::flash('success','Vlog Updated Successfully !');
            return redirect()->route('vlogs.index');
        } else {
            abort(403);
        }
    }
    public function per_delete(Request $request)                                                // admin can delete permanently data
    {
        if (Auth::user()->can('delete_treatment')) {
            Vlogs::where('id', $request->id)->withTrashed()->forcedelete();
            return response()->json(["data" =>"Restore"]);
        } else {
            abort(403);
        }
    }
    public function restore(Request $request)                                                                        //admin can restore deleted data
    {
        if (Auth::user()->can('delete_treatment')) {
            Vlogs::where('id', $request->id)->withTrashed()->update([
                'deleted_at' =>null,
            ]);
            return response()->json(["data" =>"Restore"]);
        } else {
            abort(403);
        }
    }
}
