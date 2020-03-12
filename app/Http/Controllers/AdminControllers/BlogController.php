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
use Intervention\Image\Facades\Image;
class BlogController extends Controller
{
    public function index(){
        if ( Auth::user()->can('media_hub') ) {
            $blogs = Blog::orderByDesc('updated_at')->with('blog_images','blog_category')->get();
            return view('adminpanel.mediahub.blogs.index', compact('blogs'));
        } else {
            abort(403);
        }
    }
    public function show_deleted()
    {
        $blogs = Blog::orderByDesc('updated_at')->onlyTrashed()->with('blog_images','blog_category')->get();
        return view('adminpanel.mediahub.blogs.soft_deleted', compact('blogs'));
    }
    public function create(){
        if ( Auth::user()->can('media_hub') ) {
            $categories = BlogCategory::orderByDesc('updated_at')->get();
            return view('adminpanel.mediahub.blogs.create', compact('categories'));
        } else {
            abort(403);
        }
    }
    public function store(Request $request){
        if ( Auth::user()->can('media_hub') ) {
            $validator = $request->validate([
                'category_id'   => 'required',
                'title'         => 'required',
                'description'   => 'required',
            ]);
            $blog = Blog::create([
                'category_id'       => $request->category_id,
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
            $destinationPath = '/backend/uploads/blogs/';
            $images = $request->file('picture');
            $i = 0;
            foreach ($images as $image) {
                $filename = time().$i. '.' . $image->getClientOriginalExtension();

                $location = public_path('backend/uploads/blogs/' . $filename);
                Image::make($image)->save($location);
                if ($blog) {
                    BlogImage::create([
                        'blog_id'  => $blog->id,
                        'picture'       => $filename,
                    ]);
                }
                $i++;
            }
            Session::flash('success','Blog Created Successfully !');
            return redirect()->route('blogs.index');
        } else {
            abort(403);
        }
    }
    public function edit($id){
        if ( Auth::user()->can('media_hub') ) {
            $categories = BlogCategory::orderByDesc('updated_at')->get();
            $blog       = Blog::where('id',$id)->first();
            $blog_images= BlogImage::where('blog_id',$id)->get();
            return view('adminpanel.mediahub.blogs.edit', compact('categories','blog','blog_images'));
        } else {
            abort(403);
        }
    }
    public function show(){
    
    }
    public function update(Request $request , $id){
        if ( Auth::user()->can('media_hub') ) {
            $validator = $request->validate([
                'category_id'   => 'required',
                'title'         => 'required',
                'description'   => 'required',
            ]);
            $blog = Blog::where('id',$id)->update([
                'category_id'       => $request->category_id,
                'title'             => $request->title,
                'description'       => $request->description,
                'meta_title'        => $request->meta_title,
                'meta_description'  => $request->meta_description,
                'url'         => $request->url,
                'is_active'         => isset($request->is_active)? $request->is_active :0,
                'position'          => $request->position,
                'updated_by'        =>   Auth::user()->id,
            ]);
            $destinationPath = public_path('/backend/uploads/blogs/');
            $pic = $request->picture;
                if($pic != null){
                    $old_blog_images = BlogImage::where('blog_id', $id)->whereNotIn('picture',$pic)->get();
                }else{
                $old_blog_images = blogImage::where('blog_id', $id)->get();
                }
            if (count($old_blog_images)>0) {
                    foreach ($old_blog_images as $blog_image) {
                        $oldImage = $blog_image->picture;
                        $oldImageLoc = public_path($destinationPath . $oldImage);
                        File::delete($oldImageLoc);
                        $blog_image->delete();
                    }
            }
            if ($request->file('picture')) {
            $originalImages = $request->file('picture');
            if ($originalImages) {
                $i = 0;
                foreach ($originalImages as $image) {
                    $filename = time().$i. '.' . $image->getClientOriginalExtension();

                    $location = public_path('backend/uploads/blogs/' . $filename);
                    Image::make($image)->save($location);
                    if ($blog) {
                        blogImage::create([
                        'blog_id'  => $id,
                        'picture'  => $filename,
                    ]);
                    }
                    $i++;
                }
            }
        }
        Session::flash('success','Blog Updated Successfully !');
        return redirect()->route('blogs.index');
        } else {
            abort(403);
        }
    }
    public function destroy($id){
        if ( Auth::user()->can('media_hub') ) {
            $blog = Blog::where('id',$id)->update([
                'deleted_by' => Auth::user()->id,
                'deleted_at' => Carbon::now()->toDateTimeString(),
            ]);
            $blog = Blog::where('id',$id)->delete();
            Session::flash('success','Blog Deleted Successfully !');
            return redirect()->route('blogs.index');
        } else {
            abort(403);
        }
    }
    public function per_delete(Request $request)                                                // admin can delete permanently data
    {
        if (Auth::user()->can('delete_treatment')) {
            $blog_images = BlogImage::where('blog_id',$request->id)->get();
            if(count($blog_images)){
                foreach($blog_images as $image){
                    $image_path = public_path() . "/backend/uploads/blogs/" . $image->picture;
                    File::delete($image_path);
                    $image->delete();
                }
            }
            $blog = Blog::where('id',$request->id)->withTrashed()->forcedelete();
            return response()->json(["data" =>"Restore"]);
        } else {
            abort(403);
        }
    }
    public function restore(Request $request)                                                                        //admin can restore deleted data
    {
        if (Auth::user()->can('delete_treatment')) {
            Blog::where('id', $request->id)->withTrashed()->update([
                'deleted_at' =>null,
            ]);
            return response()->json(["data" =>"Restore"]);
        } else {
            abort(403);
        }
    }
}
