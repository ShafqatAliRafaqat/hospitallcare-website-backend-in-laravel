<?php

namespace App\Http\Controllers\AdminControllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Organization;
use App\Models\Admin\Center;

class CenterUsersController extends Controller
{
    public function index()
    {
        if ( Auth::user()->can('view_user_management') ) {
            
            $users = User::where('medical_center_id','!=',null)->orderBy('created_at', 'DESC')->with('Center','Role')->get();
            
            return view('adminpanel.centeruser.index', compact('users'));
        } else {
            abort(403);
        }
    }

    public function create()
    {
        if ( Auth::user()->can('view_user_management') ) {
            $roles  = Role  ::all();
            $center = Center::all();
            return view('adminpanel.centeruser.create', compact('roles','center'));
        } else {
            abort(403);
        }
    }

    public function store(Request $request)
    {
        if ( Auth::user()->can('view_user_management') ) {
            $validate = $request->validate([
                'name'              => 'required|string|max:255',
                'email'             => 'required|string|email|max:255|unique:users',
                'password'          => 'required|string|min:6|confirmed',
                'user_role'         => 'required|exists:roles,id',
                'medical_center_id' => 'required'
            ]);


            $user           = new User;
            $user->name     = $request->name;
            $user->email    = $request->email;
            $user->medical_center_id = $request->medical_center_id;
            $user->password  = Hash::make($request->password);
            $user->save();

            $role_name = Role::find($request->user_role)->name;
            $role      = DB::table('role_user')->insert([
                'role_id'=>$request->user_role,
                'user_id'=>$user->id
                ]);

            if($user) {
                Session::flash('success','User Created Successfully with '.$role_name.' role');
                return redirect()->route('center_users.index');
            }
        } else {
            abort(403);
        }
    }

    public function edit($id)
    {
        if ( Auth::user()->can('view_user_management') ) {
            $role_id = User::where('users.id',$id)
                          ->join('role_user as ru','ru.user_id','users.id')
                          ->join('roles as r','r.id','ru.role_id')
                          ->select('ru.role_id')
                          ->first();
            if ($role_id) {
                $role_id = $role_id->role_id;
            } else {
                $role_id = 0;
            }
            $user   = User  :: find($id);
            $roles  = Role  :: all();
            $center = Center:: all();
            return view('adminpanel.centeruser.edit', compact('user', 'roles','role_id','center'));
        } else {
            $role_id = 0;
        }
        $user  = User::find($id);
        $roles = Role::all();
        return view('adminpanel.centeruser.edit', compact('user', 'roles','role_id'));
    }
    public function update(Request $request, $id)
    {
        if ( Auth::user()->can('view_user_management') ) {
        $validate = $request->validate([
            'name'        => 'required|string|max:255',
            'user_role'   => 'required|exists:roles,id',
        ]);

        $user           = User::find($id);
        $user->name     = $request->name;
        if ($request->password) {
            $validate = $request->validate([
                'password'    => 'sometimes|string|min:6|confirmed',
            ]);
               $user->password = Hash::make($request->password);
            }
            $user->medical_center_id     = $request->medical_center_id;
            $user->save();

            $user_role_id = $request->user_role_id;
            $role_name    = Role::find($request->user_role)->name;

            $role = DB::table('role_user')
                    ->where(['role_id'=>$user_role_id,'user_id'=>$user->id])
                    ->update(['role_id'=>$request->user_role,'user_id'=>$user->id]);

            if($user) {
                Session::flash('success','User Updated Successfully with '.$role_name.' role');
                return redirect()->route('center_users.index');
            }
        } else {
            abort(403);
        }
    }
    public function destroy($id)
    {
        if ( Auth::user()->can('view_user_management') ) {
            User::destroy($id);
            Session::flash('success', 'User Deleted');
            return redirect()->route('center_users.index');
        } else {
            abort(403);
        }
    }

}
