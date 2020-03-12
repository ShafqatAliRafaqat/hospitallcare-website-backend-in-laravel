<?php

namespace App\Http\Controllers\AdminControllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Admin\Blog;
use App\Models\Admin\BlogCategory;
use App\Models\Admin\BlogImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class BlogCategoryController extends Controller
{
    public function index(){
        if ( Auth::user()->can('media_hub') ) {
            $blog_categories = BlogCategory::orderByDesc('updated_at')->get();
            return view('adminpanel.mediahub.blogcategory.index', compact('blog_categories'));
        } else {
            abort(403);
        }
    }
    public function show_deleted()
    {
        $blog_categories = BlogCategory::orderByDesc('updated_at')->onlyTrashed()->get();
        return view('adminpanel.mediahub.blogcategory.soft_deleted', compact('blog_categories'));
    }
    public function create(){
        if ( Auth::user()->can('media_hub') ) {
            return view('adminpanel.mediahub.blogcategory.create');
        } else {
            abort(403);
        }
    }
    public function store(Request $request){
        if ( Auth::user()->can('media_hub') ) {
            $validator = $request->validate([
                'name'       => 'required',
                'description'=> 'required'
            ]);
            
            $blog_category = BlogCategory::create([
                'name'          => $request->name,
                'description'   => $request->description,
                'created_by'    =>   Auth::user()->id,
            ]);
            Session::flash('success','Blog Category Created Successfully !');
            return redirect()->route('blogcategory.index');
        } else {
            abort(403);
        }
    }
    public function edit($id){
        if ( Auth::user()->can('media_hub') ) {
            $blog_category = BlogCategory::where('id',$id)->first();
            return view('adminpanel.mediahub.blogcategory.edit', compact('blog_category'));
        } else {
            abort(403);
        }
    }
    public function show(){
    
    }
    public function update(Request $request , $id){
        if ( Auth::user()->can('media_hub') ) {
            $validator = $request->validate([
                'name'       => 'required',
                'description'=> 'required'
            ]);
            $blog_category = BlogCategory::where('id',$id)->update([
                'name'          => $request->name,
                'description'   => $request->description,
                'updated_by'    =>   Auth::user()->id,
            ]);
            Session::flash('success','Blog Category Updated Successfully !');
            return redirect()->route('blogcategory.index');
        } else {
            abort(403);
        }
    }
    public function destroy($id){
        if ( Auth::user()->can('media_hub') ) {
            
            $category   = BlogCategory::where('id',$id)->update([
                'deleted_by' => Auth::user()->id,
                'deleted_at' => Carbon::now()->toDateTimeString(),
            ]);
            $category   = BlogCategory::where('id',$id)->delete();
            Session::flash('success','Blog Category Deleted Successfully !');
            return redirect()->route('blogcategory.index');
        } else {
            abort(403);
        }
    }
    public function per_delete(Request $request)                                                // admin can delete permanently data
    {
        if (Auth::user()->can('delete_treatment')) {
            $blogs      = Blog::where('category_id',$request->id)->withTrashed()->get();
            if (count($blogs)>0) {
                foreach ($blogs as $blog) {
                    $blog_images = BlogImage::where('blog_id',$blog->id)->get();
                    if(count($blog_images)){
                        foreach($blog_images as $image){
                            $image_path = public_path() . "/backend/uploads/blogs/" . $image->picture;
                            File::delete($image_path);
                            $image->delete();
                        }
                    }
                    $blog->forcedelete();
                }
            }
            BlogCategory::where('id',$request->id)->withTrashed()->forcedelete();
            return response()->json(["data" =>"Restore"]);
        } else {
            abort(403);
        }
    }
    public function restore(Request $request)                                                                        //admin can restore deleted data
    {
        if (Auth::user()->can('delete_treatment')) {
            BlogCategory::where('id', $request->id)->withTrashed()->update([
                'deleted_at' =>null,
            ]);
            return response()->json(["data" =>"Restore"]);
        } else {
            abort(403);
        }
    }
}
