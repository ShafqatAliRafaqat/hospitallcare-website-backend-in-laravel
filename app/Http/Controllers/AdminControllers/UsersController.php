<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Organization;
use App\Role;
use App\Models\Admin\UserImage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ( Auth::user()->can('view_user_management') ) {
            $users = User::where('organization_id','!=',null)->orderBy('created_at', 'DESC')->with('Organization','Role','UserImage')->get();
            return view('adminpanel.users.index', compact('users'));
        } else {
            abort(403);
        }
    }
    public function show_deleted()
    {
        if ( Auth::user()->can('view_user_management') ) {

            $users = User::where('organization_id','!=',null)->orderBy('created_at', 'DESC')->with('Organization','Role')->onlyTrashed()->get();

            return view('adminpanel.users.soft_deleted', compact('users'));
        } else {
            abort(403);
        }
    }

    public function create()
    {
        if ( Auth::user()->can('view_user_management') ) {
            $roles = Role::all();
            $organizations = Organization::all();
            return view('adminpanel.users.create', compact('roles','organizations'));
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
                'organization_id'   => 'required|exists:organizations,id'
            ]);
            $user                   =   new User;
            $user->name             =   $request->name;
            $user->email            =   $request->email;
            $user->organization_id  =   $request->organization_id;
            $user->password         =   Hash::make($request->password);
            $user->save();
            $role_name              =   Role::find($request->user_role)->name;
            $role                   =   DB::table('role_user')->insert([
                        'role_id'   =>  $request->user_role,
                        'user_id'   =>  $user->id
            ]);
             $destinationPath = '/backend/uploads/users/';                                    // Defining th uploading path if not exist create new
             $image       = $request->file('picture');
             if ($request->file('picture')) {
                if(!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, $mode = 0777, true, true);
                }
                $user_name  =   $request->name;
                $filename   =   str_slug($user_name).'-'.time().'.'.$image->getClientOriginalExtension();
                $table      =   "users_images";
                $id_name    =   "user_id";
                $user_id    =   $user->id;
                $insert_images = insert_images($user_id, $destinationPath,$table,$id_name, $filename,$image);
            }
            if($user) {
                Session::flash('success','User Created Successfully with '.$role_name.' role');
                return redirect()->route('users.index');
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

            $user           = User::where('id',$id)->with('UserImage')->first();
            $roles          = Role::all();
            $organizations  = Organization::all();
            return view('adminpanel.users.edit', compact('user', 'roles','role_id','organizations'));
        } else {
            abort(403);
        }

        return view('adminpanel.users.edit', compact('user', 'roles','role_id'));
    }

    public function update(Request $request, $id)
    {
        if ( Auth::user()->can('view_user_management') ) {
            $validate = $request->validate([

                'name'     => 'required|string|max:255',
                'user_role'=> 'required|exists:roles,id',
            ]);
            $user       = User::find($id);
            $user->name = $request->name;
            if ($request->password) {
                $validate = $request->validate([
                    'password'    => 'sometimes|string|min:6|confirmed',
                ]);
               $user->password = Hash::make($request->password);
            }
            $user->organization_id = $request->organization_id;
            $user->save();

            $user_role_id = $request->user_role_id;
            $role_name    = Role::find($request->user_role)->name;

            $role = DB::table('role_user')->where(['role_id'=>$user_role_id,'user_id'=>$user->id])->update([
                'role_id'=>$request->user_role,
                'user_id'=>$user->id
            ]);
             $destinationPath = '/backend/uploads/users/';                          // Defining th uploading path if not exist create new
             $image       = $request->file('picture');
             if ($request->file('picture')) {
                $user_name      =   $request->name;
                if($image != null){                                                                             // Delete all images first
                $table      =   "users_images";
                $id_name    =   "user_id";
                $delete_images = delete_images($id,$destinationPath,$table,$id_name);

                $filename    = str_slug($user_name).'-'.time().'.'.$image->getClientOriginalExtension(); // then insert images
                $table      =   "users_images";
                $id_name    =   "user_id";
                $insert_images = insert_images($id, $destinationPath,$table,$id_name, $filename,$image);
            }
            if(!($request->has('picture'))){
                $table      =   "users_images";
                $id_name    =   "user_id";
                $delete_images = delete_images($id,$destinationPath,$table,$id_name);
            }
            }
            if($user) {
                Session::flash('success','User Updated Successfully with '.$role_name.' role');
                return redirect()->route('users.index');
            }
        } else {
            abort(403);
        }
    }

    public function destroy($id)                                                    // admin can delete data
    {
        if ( Auth::user()->can('view_user_management') ) {
            User::where('id',$id)->delete();
            Session::flash('success', 'User Deleted Successfully');
            return redirect()->back();
        } else {
            abort(403);
        }
    }
    public function per_delete($id)                                                    // admin can delete permanently
    {
        if ( Auth::user()->can('view_user_management') ) {
            User::where('id',$id)->withTrashed()->forcedelete();
            Session::flash('success', 'User Deleted Successfully');
            return redirect()->back();
        } else {
            abort(403);
        }
    }
    public function restore($id)                                                        // admin can restore all deleted data
    {
        if ( Auth::user()->can('view_user_management') ) {
            User::where('id',$id)->withTrashed()->restore();
            Session::flash('success', 'User Restore Successfully');
            return redirect()->route('users.index');
        } else {
            abort(403);
        }
    }

}
