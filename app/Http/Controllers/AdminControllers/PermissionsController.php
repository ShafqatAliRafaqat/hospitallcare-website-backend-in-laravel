<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PermissionsController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        
        return view('adminpanel.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('adminpanel.permissions.create');
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name'         => 'required',
            'description'  => 'required|string',
        ]);

        $permission = new Permission;
        $permission->name        = $request->name;
        $permission->description = $request->description;
        $permission->save();

        if($permission) {
            
            Session::flash('success','Permission Created Successfully');
            
            return redirect()->route('permissions.index');
        }
    }

    public function edit($id)
    {
        $permission = Permission::find($id);
        
        return view('adminpanel.permissions.edit',compact('permission'));
    }

    public function update(Request $request, $id)
    {
         $validate = $request->validate([
            'name'         => 'required',
            'description'  => 'required|string',
        ]);

        $permission = Permission::find($id);
        $permission->name        = $request->name;
        $permission->description = $request->description;
        $permission->save();

        if($permission) {
            
            Session::flash('success','Permission Updated Successfully');
            
            return redirect()->route('permissions.index');
        }
    }

    public function destroy($id)
    {
        $permission = Permission::destroy($id);
        
        Session::flash('success','Permission Deleted');
        
        return redirect()->route('permissions.index');
    }
}
