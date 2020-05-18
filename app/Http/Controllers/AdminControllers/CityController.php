<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities     =   City::orderBy('is_active', 1)->orderBy('name', 'ASC')->get();
        return view('adminpanel.city.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Edit blade of an area
        $area   =   DB::table('city_areas as ca')->where('id',$id)->first();
        return  view('adminpanel.city.edit_area', compact('area'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //showing Areas of a City
        $city   =   City::where('id',$id)->first();
        $areas  =   DB::table('city_areas as ca')->where('city_id',$id)->get();
        return view('adminpanel.city.areas', compact('city','areas'));
    }

    public function store_area(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'area'          => 'required',
            'slug'          => 'required',
            'is_active'     => 'nullable',
        ]);
        if ($validator->fails()) {
            session()->flash('error', 'Please Fill out all the Fields');
            return back();
        }
        $city_id        =   $request->city_id;
        $area           =   $request->area;
        $slug           =   $request->slug;
        $is_active      =   $request->is_active;
        $i  =   0;
        foreach ($area as $a) {
        $store          =   DB::table('city_areas')->insert([
            'city_id'       =>  $city_id,
            'name'          =>  $a,
            'slug'          =>  $slug[$i],
            'is_active'     =>  $is_active[$i],
        ]);
        $i++;
        }

       if ($store) {
            session()->flash('success', 'Areas saved Successfully!');
            return redirect()->route('city.edit',$city_id);
        } else {
            session()->flash('error', 'Could not Save!');
            return redirect()->route('city.edit',$city_id);

        }
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
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'slug'          => 'required',
            'is_active'     => 'nullable',
        ]);
        if ($validator->fails()) {
            session()->flash('error', 'Please Fill out all the Fields');
            return back();
        }
        $name           =   $request->name;
        $slug           =   $request->slug;
        $is_active      =   isset($request->is_active) ? $request->is_active : 0;
        $update          =   DB::table('city_areas')->where('id',$id)->update([
            'name'          =>  $name,
            'slug'          =>  $slug,
            'is_active'     =>  $is_active,
        ]);
        $city   =   DB::table('city_areas')->where('id',$id)->first();
       if ($update) {
            session()->flash('success', 'Area is Updated Successfully!');
            return redirect()->route('city.edit',$city->city_id);
        } else {
            session()->flash('error', 'Could not Update!');
            return redirect()->route('city.edit',$city->city_id);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $area = DB::table('city_areas')->where('id', $id)->delete();
        session()->flash('success', 'Area Deleted Successfully');
        return redirect()->back();
    }
}
