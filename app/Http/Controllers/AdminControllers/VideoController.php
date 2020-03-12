<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Center;
use App\Models\Admin\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::all();
        
        return view('adminpanel.videos.index', compact('videos'));
    }

    public function create()
    {
        $centers  = Center::where('is_active',1)->get();
        return view('adminpanel.videos.create', compact('centers'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'center_id'         => 'sometimes',
            'title'             => 'required|string',
            'link'              => 'required|string',
            'article'           => 'required|string',
            'category'          => 'required|string',
            'source'            => 'required|string',
            'picture'           => 'image|required|mimes:jpeg,png,jpg,gif,svg',
            'meta_title'        => 'string|nullable',
            'meta_description'  => 'string|nullable',
            'url'         => 'string|nullable',
            'is_active'         => 'nullable',
        ]);

        $destinationPath = public_path('/backend/uploads/videos');
        
        if(!File::exists($destinationPath)) {
            
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }
        
        $originalImage = $request->file('picture');
        $filename      = time().'.'.$originalImage->getClientOriginalExtension();
        $upload        = $originalImage->move($destinationPath, $filename);

        Video::create([
            'center_id'         => $request->input('center_id'),
            'title'             => $request->input('title'),
            'link'              => $request->input('link'),
            'article'           => $request->input('article'),
            'category'          => $request->input('category'),
            'source'            => $request->input('source'),
            'picture'           => $filename,
            'meta_title'        => $request->input('meta_title'),
            'meta_description'  => $request->input('meta_description'),
            'url'         => $request->input('url'),
            'is_active'         => $request->input('is_active'),
        ]);

        session()->flash('success','Video Created Successfully');
        
        return redirect()->route('videos.index');
    }

    public function edit($id)
    {
        $video = Video::find($id);
        $centers = Center::where('is_active',1)->get();
        
        return view('adminpanel.videos.edit', compact('video','centers'));
    }

    public function update(Request $request, $id)
    {
        if ( $request->file('picture') ) {
            $validate = $request->validate([
                'center_id'         => 'sometimes',
                'title'             => 'required|string',
                'link'              => 'required|string',
                'article'           => 'required|string',
                'category'          => 'required|string',
                'source'            => 'required|string',
                'picture'           => 'image|required|mimes:jpeg,png,jpg,gif,svg',
                'meta_title'        => 'string|nullable',
                'meta_description'  => 'string|nullable',
                'url'         => 'string|nullable',
                'is_active'         => 'nullable',
            ]);

            $destinationPath = public_path('/backend/uploads/articles');
            $originalImage = $request->file('picture');
            $filename = time().'.'.$originalImage->getClientOriginalExtension();
            
            $upload = $originalImage->move($destinationPath, $filename);

            Video::where('id', $id)->update([
                'center_id'         => $request->input('center_id'),
                'title'             => $request->input('title'),
                'link'              => $request->input('link'),
                'article'           => $request->input('article'),
                'category'          => $request->input('category'),
                'source'            => $request->input('source'),
                'picture'           => $filename,
                'meta_title'        => $request->input('meta_title'),
                'meta_description'  => $request->input('meta_description'),
                'url'         => $request->input('url'),
                'is_active'         => $request->input('is_active'),
            ]);
            session()->flash('success', 'Video Updated Successfully');
        } else {
            $validate = $request->validate([
                'center_id'         => 'sometimes',
                'title'             => 'required|string',
                'link'              => 'required|string',
                'article'           => 'required|string',
                'category'          => 'required|string',
                'source'            => 'required|string',
                'meta_title'        => 'string|nullable',
                'meta_description'  => 'string|nullable',
                'url'         => 'string|nullable',
                'is_active'         => 'nullable',
            ]);

            Video::where('id', $id)->update([
                'center_id'         => $request->input('center_id'),
                'title'             => $request->input('title'),
                'link'              => $request->input('link'),
                'article'           => $request->input('article'),
                'category'          => $request->input('category'),
                'source'            => $request->input('source'),
                'meta_title'        => $request->input('meta_title'),
                'meta_description'  => $request->input('meta_description'),
                'url'         => $request->input('url'),
                'is_active'         => $request->input('is_active'),
            ]);
            
            session()->flash('success', 'Video Updated Successfully');
        }
        return redirect()->route('videos.index');
    }

    public function destroy($id)
    {
        $video = Video::find($id);
        $image_path =  public_path()."/backend/uploads/videos/".$video->picture;
        File::delete($image_path);
        
        if ( Video::destroy($id) ) {
            
            session()->flash('error', 'Video Deleted Successfully');
            
            return redirect()->route('videos.index');
        }
    }
}
