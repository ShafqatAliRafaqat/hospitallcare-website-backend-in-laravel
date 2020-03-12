<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Status;

class StatusController extends Controller
{

    public function index()                                                                 // admin can view all status data
    {
        if(Auth::user()->can('view_status')) {

            $status = Status::all();

            return view('adminpanel.status.index', compact('status'));
        } else {
            abort('403');
        }
    }
    public function show_deleted()                                                  // Admin can view all deleled data
    {
        if(Auth::user()->can('view_status')) {

            $status = Status::onlyTrashed()->get();

            return view('adminpanel.status.soft_deleted', compact('status'));
        } else {
            abort('403');
        }
    }

    public function create()                                                            // admin can create new status
    {
        if ( Auth::user()->can('create_status') ) {

            return view('adminpanel.status.create');
        } else {
            abort(403);
        }
    }

    public function store(Request $request)                                                 // admin can store status data
    {
        if ( Auth::user()->can('create_status') ) {

            $validate = $request->validate([
                'name' => 'required|min:3',
                'active' => 'nullable',
            ]);

            $status = DB::table('status')->insert($validate);

            if ($status) {

                session()->flash('success', 'Status Created Successfully');

                return redirect()->route('status.index');
            }
        } else {
            abort(403);
        }
    }

    public function edit($id)                                                           // admin can edit status data
    {
        if ( Auth::user()->can('create_status') ) {

            $status = DB::table('status')->where('id', $id)->first();

            return view('adminpanel.status.edit', compact('status'));

        } else {
            abort(403);
        }
    }

    public function update(Request $request, $id)                                       // admin can update status
    {
        if ( Auth::user()->can('edit_status') ) {

            $validate = $request->validate([
                'name' => 'required|min:3',
                'active' => 'nullable',
            ]);

            $status = DB::table('status')->where('id', $id)->update($validate);

            if ($status) {

                session()->flash('success', 'Status Updated Successfully');
                return redirect()->route('status.index');
            }
        } else {
            abort(403);
        }
    }
    public function destroy($id)                                                    // admin can soft delete
    {
        if ( Auth::user()->can('edit_status') ) {

            $status = Status::where('id', $id)->delete();

            session()->flash('success', 'Status Deleted Successfully');

            return redirect()->route('status.index');
        } else {
            abort(403);
        }
    }
    public function per_delete($id)                                        // Admin can delete status permanently
    {
        if ( Auth::user()->can('edit_status') ) {
            $status = Status::where('id', $id)->withTrashed()->forcedelete();
            session()->flash('success', 'Status Deleted Successfully');
            return redirect()->back();
        } else {
            abort(403);
        }
    }
    public function restore($id)                                                // admin can restore status
    {
        if ( Auth::user()->can('edit_status') ) {

            $status = Status::where('id', $id)->withTrashed()->restore();
            session()->flash('success', 'Status Restore Successfully');
            return redirect()->back();
        } else {
            abort(403);
        }
    }
}
