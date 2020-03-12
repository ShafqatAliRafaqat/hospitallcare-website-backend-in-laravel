<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Diagnostics;
use App\Models\Admin\Lab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\LabServices;

class LabController extends Controller
{
    /** @var LabServices */
    private $service;

    public function __construct()
    {
        $this->service = new LabServices();
    }

    public function index()                                                 // Show All lab in table
    {
        if( Auth::user()->can('view_medical_centers') ){
            $labs = Lab::all();

        return view('adminpanel.labs.index', compact('labs'));
        } else {
            abort(403);
        }
    }
    public function show_deleted()                                                 // Show All lab in table
    {
        if( Auth::user()->can('view_medical_centers') ){
            $labs = Lab::onlyTrashed()->get();

        return view('adminpanel.labs.soft_deleted', compact('labs'));
        } else {
            abort(403);
        }
    }

    public function create()                                                // Create new lab
    {
        if( Auth::user()->can('create_medical_center') ){
            $diagnostics    =   Diagnostics::all();
            return view('adminpanel.labs.create',compact('diagnostics'));
        } else {
            abort(403);
        }
    }

    public function store(Request $request)                                 // Store new lab data
    {
        if( Auth::user()->can('create_medical_center') ){
            $input = $request->all();
            $labs = $this->service->create($input);
            session()->flash('success', 'Lab Created Successfully');
            return redirect()->route('labs.index');
        } else {
            abort(403);
        }
    }

    public function show($id)                                                           // Show Single lab using id
    {
        if( Auth::user()->can('view_medical_centers') ){
            $labs = Lab::where('id',$id)->with('diagnostic')->withTrashed()->first();
            // dd($labs->diagnostic[0]->pivot->cost);
            return view('adminpanel.labs.show', compact('labs'));
        } else {
            abort(403);
        }
    }

    public function edit($id)                                                         // Show edit form
    {
        if( Auth::user()->can('edit_medical_center') ){
            $labs           = Lab::where('id',$id)->with('diagnostic')->first();
            $diagnostics    = Diagnostics::all();
            // dd($labs->diagnostic,$diagnostics);
            return view('adminpanel.labs.edit', compact('labs','diagnostics'));
        } else {
            abort(403);
        }
    }

    public function update(Request $request, $id)                                       // update lab data
    {
        if( Auth::user()->can('edit_medical_center') ){
            $input = $request->all();
            $labs = $this->service->update($input,$id);

                session()->flash('success', 'Labs Updated Successfully');
                return redirect()->route('labs.index');
        } else {
            abort(403);
        }
    }

    public function destroy($id)                                                                // Delete lab by using id
    {
        if( Auth::user()->can('delete_medical_center') ){
        $labs = Lab::findorfail($id);
        $labs->deleted_by   =   Auth::user()->id;
        $labs->save();
            if(Lab::destroy($id)){                                                               //DELETING image from Storage
            session()->flash('success', 'Lab Deleted Successfully');
            return redirect()->route('labs.index');
      }
      } else {
            abort(403);
        }

    }
    public function per_delete($id)                                                                // permanent delete data
    {
        if( Auth::user()->can('delete_medical_center') ){
        $labs = Lab::where('id',$id)->withTrashed()->forcedelete();
            if($labs){                                                               //DELETING image from Storage
            $delete_diagnostics     =   DB::table('lab_diagnostics')
                                        ->where('lab_id', $id)
                                        ->delete();
            session()->flash('success', 'Lab Deleted Successfully');
            return redirect()->back();
      }
      } else {
            abort(403);
        }

    }
    public function restore($id)                                                                // restore deleted data
    {
        if( Auth::user()->can('delete_medical_center') ){
        $labs = Lab::where('id',$id)->withTrashed()->restore();
            session()->flash('success', 'Lab Restore Successfully');
            return redirect()->back();
      } else {
            abort(403);
        }

    }

}
