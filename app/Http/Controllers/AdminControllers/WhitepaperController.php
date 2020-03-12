<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Article;
use App\Models\Admin\Whitepaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WhitepaperController extends Controller
{
    public function index()
    {
        $whitepapers = Whitepaper::all();
        
        return view('adminpanel.whitepapers.index', compact('whitepapers'));
    }

    
    public function create()
    {
        return view('adminpanel.whitepapers.create');
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'title'             =>  'required|string',
            'description'       =>  'required|string',
            'file'              => 'required|mimes:pdf',
            'meta_title'        => 'string|nullable',
            'meta_description'  => 'string|nullable',
            'url'         => 'string|nullable',
            'is_active'         => 'nullable',
        ]);

        // Checking if Folder Exists, if not create new
        
        $destinationPath = public_path().'/backend/uploads/whitepapers/';
        
        if (!File::exists($destinationPath)){
            
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }

        // Uploading PDF file in FOLDER
        $file = $request->file('file');
        $filename   = time().'.'.$file->getClientOriginalExtension();
        $file->move($destinationPath,$filename);

        $whitepaper                     = new Whitepaper;
        $whitepaper ->title             = $request->input('title');
        $whitepaper ->description       = $request->input('description');
        $whitepaper ->file              = $filename;
        $whitepaper ->is_active         = $request->input('is_active');
        $whitepaper ->meta_title        = $request->input('meta_title');
        $whitepaper ->meta_description  = $request->input('meta_description');
        $whitepaper ->url         =   $request->input('url');
        $whitepaper ->save();
        $whitepaper_id = $whitepaper ->id;
        /*
        Returning the success message on successfull uploading
        */
        session()->flash('success','Whitepaper Added Successfully');
        
        return redirect()->route('whitepaper.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $whitepaper = DB::table('whitepapers')->where('id',$id)->first();
       
        return view('adminpanel.whitepapers.edit',compact('whitepaper'));
    }

    public function update(Request $request, $id)
    {
        if ($request->input('file')) {
            $validate = $request->validate([
            'title'             =>  'required|string',
            'description'       =>  'required|string',
            'file'              => 'required|mimes:pdf',
            'meta_title'        => 'string|nullable',
            'meta_description'  => 'string|nullable',
            'url'         => 'string|nullable',
            'is_active'         => 'nullable',
        ]);


        $whitepaper                     = Whitepaper::find($id);
        $whitepaper ->title             = $request->input('title');
        $whitepaper ->description       = $request->input('description');
        // $whitepaper ->file              = $filename;
        $whitepaper ->is_active         = $request->input('is_active');
        $whitepaper ->meta_title        = $request->input('meta_title');
        $whitepaper ->meta_description  = $request->input('meta_description');
        $whitepaper ->url         =   $request->input('url');
        $whitepaper ->save();
        $whitepaper_id = $whitepaper ->id;
        }

        $destinationPath = public_path().'/backend/uploads/whitepapers/';
        // Uploading PDF file in FOLDER
        $file = $request->file('file');
        $filename   = time().'.'.$file->getClientOriginalExtension();
        $file->move($destinationPath,$filename);
    }

    public function destroy($id)
    {
        $destinationPath = public_path().'/backend/uploads/whitepapers/';
        
        $whitepaper = DB::table('whitepapers')->where('id',$id)->first();
        
        $whitepaper_file = $whitepaper->file;
        
        $deleted = DB::table('whitepapers')->where('id',$id)->delete();
        
        if ($deleted) {
        
            $file_path =  $destinationPath.$whitepaper_file;
        
            File::delete($file_path);
        
            session()->flash('success','Whitepaper Deleted Successfully');
        
            return redirect()->route('whitepaper.index');
        }
    }
}
