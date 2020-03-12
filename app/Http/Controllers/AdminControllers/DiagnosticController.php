<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Diagnostics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiagnosticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $diagnostics    =   Diagnostics::orderBy('updated_at','DESC')->get();
        return view('adminpanel.diagnostics.index',compact('diagnostics'));
    }
    public function show_deleted()
    {
        $diagnostics    =   Diagnostics::onlyTrashed()->get();
        return view('adminpanel.diagnostics.soft_deleted',compact('diagnostics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('adminpanel.diagnostics.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->can('create_treatment')) {
            $validate = $request->validate([
                'name'              => 'required',
                'description'       => 'required',
                'is_active'         => 'string|nullable',
                'is_common'         => 'string|nullable',
            ]);
            $diagnostics = Diagnostics::create([
                'name'              => $request->input('name'),
                'description'       => $request->input('description'),
                'is_active'         => $request->input('is_active'),
                'is_common'         => $request->input('is_common'),
                'created_by'        => Auth::user()->id,

            ]);
            session()->flash('success', 'Diagnostic Created Successfully');
            return redirect()->route('diagnostics.index');
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = DB::table('labs as l')
                ->LEFTJOIN('lab_diagnostics as ld','ld.lab_id','l.id')
                ->LEFTJOIN('diagnostics as d','ld.diagnostic_id','d.id')
                ->WHERE('ld.diagnostic_id',$id)
                // ->where('mc.is_active',1)
                ->select('l.id as id','l.name as name')
                ->get();
        dd($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $diagnostics    =   Diagnostics::findOrFail($id);
        return view('adminpanel.diagnostics.edit',compact('diagnostics'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit_treatment')) {
            $validate = $request->validate([
                'name'              => 'required',
                'description'       => 'required',
                'is_active'         => 'string|nullable',
                'is_common'         => 'string|nullable',
            ]);
            $diagnostics = Diagnostics::findOrFail($id)->update([
                'name'              => $request->input('name'),
                'description'       => $request->input('description'),
                'is_active'         => $request->input('is_active'),
                'is_common'         => $request->input('is_common'),
                'updated_by'        => Auth::user()->id,
            ]);
            session()->flash('success', 'Diagnostic Updated Successfully');
            return redirect()->route('diagnostics.index');
        } else {
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)                                                // Soft Delete
    {
        if (Auth::user()->can('delete_treatment')) {
            if(Diagnostics::destroy($id)){
                session()->flash('error', 'Diagnostic Deleted Successfully');
                return redirect()->route('diagnostics.index');
            }
        } else {
            abort(403);
        }
    }
    public function per_delete($id)                                                 // Permanent Delete data
    {
        if (Auth::user()->can('delete_treatment')) {
            $diagnostic = Diagnostics::where('id',$id)->withTrashed()->forcedelete();
                session()->flash('error', 'Diagnostic Deleted Successfully');
                return redirect()->back();
        } else {
            abort(403);
        }
    }
    public function restore($id)                                                    // Restore Deleted Data
    {
        if (Auth::user()->can('delete_treatment')) {
            $diagnostic = Diagnostics::where('id',$id)->withTrashed()->restore();
                session()->flash('error', 'Diagnostic Restore Successfully');
                return redirect()->route('diagnostics.index');
        } else {
            abort(403);
        }
    }
}
