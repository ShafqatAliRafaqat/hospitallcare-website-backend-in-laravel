<?php

namespace App\Http\Controllers\ApiControllers;

use App\Organization;
use App\User;
use App\Http\Controllers\Controller;
use App\Models\Admin\Treatment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Admin\Customer;
use App\Http\Resources\OrganizationResource;

class OrganizationApiController extends Controller
{
    public function index()                                                 // Admin can view All the list of organizations
    {
        if ( Auth::user()->can('organizations') ) {
            
            $organization =Organization:: all();
          
            return OrganizationResource::collection($organization);            
      } else {
          abort(403);
      }
      
    }
    

    public function store(Request $request)                                 // Admin can store new organization 
    {
        if ( Auth::user()->can('organizations') ) {
          $validate = $request->validate([
                'name'     => 'required|min:3',
                'phone'    => 'required',
                'address'  => 'sometimes',
                ]);
  
            $organization = Organization::create([
                'name'      => $request->name,
                'phone'     => $request->phone,
                'address'   => $request->address,
            ]);
            return OrganizationResource::make($organization) ;        
        } else {
            abort(403);
        }
    }
    public function show($id)                                           // Admin can view All the details of organization
    {
        if ( Auth::user()->can('organizations') ) {
        $organization  = Organization::findOrFail($id);
        
        return OrganizationResource::make($organization);        
    } else {
        abort(403);
    }
    }

    public function updates(Request $request, $id)                       // Admin can Update organization 
    {
        if ( Auth::user()->can('organizations') ) {

          $validate = $request->validate([
              'name'      => 'required|min:3',
              'phone'     => 'required',
              'address'   => 'sometimes',
              ]); 
              $organization= Organization::findorfail($id);
              $updated = $organization->update([
              'name'      => $request->name,
              'phone'     => $request->phone,
              'address'   => $request->address,
              ]);
              return OrganizationResource::make($organization);        
            } else {
                abort(403);
            }
    }
    public function destroy($id)                                            // admin can delete organization
    {
        if ( Auth::user()->can('organizations') ) {

        $organization = DB::table('organizations')->where('id', $id)->delete();
        $massage = "organization Deleted";
        return response()->json([$massage], 200);        
    } else {
        abort(403);
    }
    }
}
