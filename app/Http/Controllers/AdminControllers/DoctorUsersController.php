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
use App\Models\Admin\Doctor;

class DoctorUsersController extends Controller
{
    public function index()
    {
        if ( Auth::user()->can('view_user_management') ) {
            $users = User::where('doctor_id','!=',null)->orderBy('created_at', 'DESC')->with('Doctor','Role')->get();
            return view('adminpanel.doctoruser.index', compact('users'));
        } else {
            abort(403);
        }
    }

    public function create()
    {
        if ( Auth::user()->can('view_user_management') ) {
            $roles  = Role::all();
            $doctor = Doctor::all();
            return view('adminpanel.doctoruser.create', compact('roles','doctor'));
        } else {
            abort(403);
        }
    }

    public function store(Request $request)
    {
        if ( Auth::user()->can('view_user_management') ) {
            $validate = $request->validate([
                'name'              => 'required|string|max:255',
                'email'             => 'nullable|string|email|max:255|unique:users',
                'password'          => 'required|string|min:6|confirmed',
                'doctor_id'         => 'required'
            ]);
            $doctor             = Doctor::where('id',$request->doctor_id)->first();
            $doctor_phone       = $doctor->phone;
            $user               = new User;
            $user->name         = $request->name;
            $user->phone        = $doctor_phone;
            $user->email        = $request->email;
            $user->doctor_id    = $request->doctor_id;
            $user->is_approved  = 1;
            $user->password     = Hash::make($request->password);
            $user->save();

            $doctor = $doctor->update([
                'is_approved'       => 1,
                'phone_verified'    => 1,
            ]);
            $role           = Role::where('name','doctor_admin')->first();
            $role_insert    = DB::table('role_user')->insert([
                    'role_id'=>$role->id,
                    'user_id'=>$user->id
                ]);

            if($user) {
                Session::flash('success','User Created Successfully with '.$role->name.' role');
                return redirect()->route('doctor_users.index');
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
            $doctor = Doctor:: all();
            return view('adminpanel.doctoruser.edit', compact('user', 'roles','role_id','doctor'));
        } else {
            $role_id = 0;
        }
        $user  = User::find($id);
        $roles = Role::all();
       
        return view('adminpanel.doctoruser.edit', compact('user', 'roles','role_id'));
    }
    public function update(Request $request, $id)
    {
        if ( Auth::user()->can('view_user_management') ) {
        $validate = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|string|email|max:255|unique:users,phone,'.$id,
            'password'      => 'sometimes|string|min:6|confirmed',
        ]);
        $doctor             = Doctor::where('id',$request->doctor_id)->first();
        $doctor_phone       = $doctor->phone;

        $user               = User::where('id',$id)->update([
            "name"          => $request->name,
            "email"         => $request->email,
            "phone"         => $doctor_phone,
            "is_approved"   => 1,
            "password"      => Hash::make($request->password),
            "doctor_id"     => $request->doctor_id,
        ]);
        $doctor_update = $doctor->update([
                'is_approved'       => 1,
                'phone_verified'    => 1,
            ]);
        $user_role_id   = $request->user_role_id;
        $role           = Role::where('name','doctor_admin')->first();

        $role_update    = DB::table('role_user')->where(['role_id'=>$user_role_id, 'user_id'=>$id])->update([
            'role_id'   => $role->id, 
            'user_id'   => $id
            ]);

        if($user) {
            Session::flash('success','User Updated Successfully with '.$role->name.' role');
            return redirect()->route('doctor_users.index');
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
            return redirect()->route('doctor_users.index');
        } else {
            abort(403);
        }
    }

}
