<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Center;
use App\Models\Admin\Package;
use App\Models\Admin\Procedure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PackageController extends Controller
{

    public function index()
    {
        $packages = Package::join('medical_centers as c','c.id','packages.center_id')
                           ->join('procedures as p','p.id','packages.procedure_id')
                           ->select('packages.*','c.center_name','p.name as procedure')
                           ->get();
        return view('adminpanel.package.index', compact('packages'));
    }

    public function create()
    {
        $centers     = Center::where('is_active',1)->get();
        $procedures  = Procedure::where('is_active',1)->get();
        return view('adminpanel.package.create', compact('centers','procedures'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'center_id'         => 'required',
            'procedure_id'      => 'required|string',
            'name'              => 'required|string',
            'price'             => 'required|string',
            'show_price'        => 'nullable',
            'picture'           => 'image|required|mimes:jpeg,png,jpg,gif,svg',
            'article'           => 'required|string',
            'meta_title'        => 'string|nullable',
            'meta_description'  => 'string|nullable',
            'url'         => 'string|nullable',
            'is_active'         => 'nullable',
        ]);

        $destinationPath = public_path('/backend/uploads/packages');
        
        if(!File::exists($destinationPath)) {
            
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }
        
        $originalImage = $request->file('picture');
        $filename      = time().'.'.$originalImage->getClientOriginalExtension();
        $upload        = $originalImage->move($destinationPath, $filename);

        Package::create([
            'center_id'         => $request->input('center_id'),
            'procedure_id'      => $request->input('procedure_id'),
            'package_name'      => $request->input('name'),
            'price'             => $request->input('price'),
            'show_price'        => $request->input('show_price'),
            'picture'           => $filename,
            'article'           => $request->input('article'),
            'meta_title'        => $request->input('meta_title'),
            'meta_description'  => $request->input('meta_description'),
            'url'         => $request->input('url'),
            'is_active'         => $request->input('is_active')
        ]);

        session()->flash('success','Package Created Successfully');
        return redirect()->route('packages.index');
    }
    
    public function edit($id)
    {
        $package = Package::find($id);
        $centers = Center::where('is_active',1)->get();
        $procedures  = Procedure::where('is_active',1)->get();
        return view('adminpanel.package.edit', compact('package','centers','procedures'));
    }

    public function update(Request $request, $id)
    {
        if ( $request->file('picture') ) {
            $validate = $request->validate([
                'center_id'         => 'required',
                'procedure_id'      => 'required|string',
                'name'              => 'required|string',
                'price'             => 'required|string',
                'show_price'        => 'nullable',
                'picture'           => 'image|required|mimes:jpeg,png,jpg,gif,svg',
                'article'           => 'required|string',
                'meta_title'        => 'string|nullable',
                'meta_description'  => 'string|nullable',
                'url'         => 'string|nullable',
                'is_active'         => 'nullable',
            ]);

            $destinationPath = public_path('/backend/uploads/articles');
            $originalImage   = $request->file('picture');
            $filename        = time().'.'.$originalImage->getClientOriginalExtension();
            $upload          = $originalImage->move($destinationPath, $filename);

            Package::where('id', $id)->update([
                'center_id'         => $request->input('center_id'),
                'procedure_id'      => $request->input('procedure_id'),
                'package_name'      => $request->input('name'),
                'price'             => $request->input('price'),
                'show_price'        => $request->input('show_price'),
                'picture'           => $filename,
                'article'           => $request->input('article'),
                'meta_title'        => $request->input('meta_title'),
                'meta_description'  => $request->input('meta_description'),
                'url'         => $request->input('url'),
                'is_active'         => $request->input('is_active')
            ]);
            
            session()->flash('success', 'Article Updated Successfully');
        } else {
            $validate = $request->validate([
                'center_id'         => 'required',
                'procedure_id'      => 'required|string',
                'name'              => 'required|string',
                'price'             => 'required|string',
                'show_price'        => 'nullable',
                'article'           => 'required|string',
                'meta_title'        => 'string|nullable',
                'meta_description'  => 'string|nullable',
                'url'         => 'string|nullable',
                'is_active'         => 'nullable',
            ]);

            Package::where('id', $id)->update([
                'center_id'         => $request->input('center_id'),
                'procedure_id'      => $request->input('procedure_id'),
                'package_name'      => $request->input('name'),
                'price'             => $request->input('price'),
                'show_price'        => $request->input('show_price'),
                'article'           => $request->input('article'),
                'meta_title'        => $request->input('meta_title'),
                'meta_description'  => $request->input('meta_description'),
                'url'         => $request->input('url'),
                'is_active'         => $request->input('is_active')
            ]);
            session()->flash('success', 'Package Updated Successfully');
        }
        return redirect()->route('packages.index');
    }

    public function destroy($id)
    {
        $package    = Package::find($id);
        $image_path =  public_path()."/backend/uploads/packages/".$package->picture;
        
        File::delete($image_path);
        
        if ( Package::destroy($id) ) {
            
            session()->flash('error', 'Package Deleted Successfully');
            
            return redirect()->route('packages.index');
        }
    }
}
