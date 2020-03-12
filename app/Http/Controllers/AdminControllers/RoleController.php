<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{
    public function index()
    {
        if ( Auth::user()->can('view_user_management') ) {

            $roles = Role::all();

            return view('adminpanel.roles.index', compact('roles'));
        } else {
            abort(403);
        }
    }

    public function create()
    {
        if ( Auth::user()->can('view_user_management') ) {

            $permissions = DB::table('permissions')->get();

            return view('adminpanel.roles.create', compact('permissions'));
        } else {
            abort(403);
        }
    }

    public function store(Request $request)
    {
	if ( Auth::user()->can('view_user_management') ) {

        $validate = $request->validate([
	        'role_name'         => 'required',
	        'role_description'  => 'required|string',
	    ]);

        $role              = new Role;
        $role->name        = $request->role_name;
        $role->description = $request->role_description;
        $role->save();

            foreach($request->permission as $p) {

                $permision_check = DB::table('permission_role')->where(['role_id'=>$role->id,'permission_id'=>$p])->first();

                if (!$permision_check) {

                    $permission_role = DB::table('permission_role')->insert(['role_id'=>$role->id,'permission_id'=>$p]);
                }
            }

            if($role) {

                Session::flash('success','Role Created Successfully');

                return redirect()->route('roles.index');
            }
        } else {
            abort(403);
        }
    }


    public function edit($id)
    {
    if ( Auth::user()->can('view_user_management') ) {

        $data = [];
        $role            = Role::find($id);
        $permissions     = DB::table('permissions')->get();
        $permission_role = DB::table('permission_role')->where('role_id', $id)->select('permission_id')->get();

        foreach($permission_role as $pr) {
            $data[] = $pr->permission_id;
        }

        if(!$role) {
            abort(404);
        }

        return view('adminpanel.roles.edit', compact('role','permissions','data'));

    } else {
            abort(403);
        }
    }

    public function update(Request $request, $id)
    {
	if ( Auth::user()->can('view_user_management') ) {
        $validate = $request->validate([
            'role_name'         => 'required',
            'role_description'  => 'required|string',
            'permission'        => 'required|array',
            'permission.*'      => 'required|exists:permissions,id'
        ],[
            'permission.required' => 'Permission is required'
        ]);

        $role = Role::find($id);

        if($role) {

            $permissions       = DB::table('permission_role')->where('role_id', $id)->delete();
            $role->name        = $request->role_name;
            $role->description = $request->role_description;
            $role->save();

            foreach($request->permission as $p) {

                $permision_check = DB::table('permission_role')->where(['role_id'=>$role->id,'permission_id'=>$p])->first();

                if (!$permision_check) {

                    $permission_role = DB::table('permission_role')->insert(['role_id'=>$role->id,'permission_id'=>$p]);
                }
            }

        Session::flash('success','Role Updated Successfully');

        return redirect()->route('roles.index');
        }
	} else {
            abort(403);
        }
    }
    public function destroy($id)
    {
        if ( Auth::user()->can('view_user_management') ) {

            $role = DB::table('roles')->where('id',$id)->delete();
            return redirect()->route('roles.index');
        } else {
            abort(403);
        }
    }

}
