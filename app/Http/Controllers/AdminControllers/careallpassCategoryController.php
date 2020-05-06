<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\CareallPassCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class careallpassCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
 * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories     =   CareallPassCategory::all();
        return view('adminpanel.careallpasscategory.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('adminpanel.careallpasscategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $validate = $request->validate([
                'name'          => 'required',
                'description'   => 'required',
                'amount'        => 'required',
                'treatments'    => 'required',
                'diagnostics'   => 'required',
                'is_active'        => 'nullable',
            ]);

            $categories = CareallPassCategory::insert($validate);

            if ($categories) {
                session()->flash('success', 'Category Created Successfully');
                return redirect()->route('pass_categories.index');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category       =   CareallPassCategory::where('id',$id)->first();
        return view('adminpanel.careallpasscategory.edit', compact('category'));
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
            $validate = $request->validate([
                'name'          => 'required',
                'description'   => 'required',
                'amount'        => 'required',
                'treatments'    => 'required',
                'diagnostics'   => 'required',
                'is_active'     => 'nullable',
            ]);
            $categories = CareallPassCategory::where('id',$id)->update($validate);
            session()->flash('success', 'Category Updated Successfully');
            return redirect()->route('pass_categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categories = CareallPassCategory::where('id',$id)->delete();
        session()->flash('success', 'Category Deleted Successfully');
        return redirect()->route('pass_categories.index');
    }
    public function show_deleted()
    {
        $category   = CareallPassCategory::onlyTrashed()->get();
        return view('adminpanel.careallpasscategory.soft_delete', compact('category'));
    }
    public function restore($id)
    {
        $category   = CareallPassCategory::where('id',$id)->restore();
        session()->flash('success', 'Category Restored Successfully');
        return redirect()->route('show_pass_deleted');
    }
    public function per_delete($id)
    {
        $category   = CareallPassCategory::where('id',$id)->forcedelete();
        session()->flash('success', 'Category Deleted Successfully');
        return redirect()->route('show_pass_deleted');
    }
}
