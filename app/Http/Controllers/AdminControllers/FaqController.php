<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faqs = DB::table('faqs as f')->join('treatments as t','t.id','f.treatment_id')->get();
        return view('adminpanel.faqs.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ( Auth::user()->can('create_customer') ) {
          $centers    = DB::table('medical_centers as mc')->where('is_active',1)->get();
          $status     = DB::table('status')->where('active', 1)->get();
          $procedures = DB::table('treatments')->where('is_active', 1)->get();
         return view('adminpanel.testing.create', compact('treatments','procedures'));
      }
        // $treatments = DB::table('treatments')->whereIsActive(1)->get();
        // return view('adminpanel.testing.create', compact('treatments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [];
        $array = $request->input('procedure_id');
        foreach ($array as $treatments) {
        $result = DB::table('medical_centers as mc')
        ->LEFTJOIN('center_treatments as ct','ct.med_centers_id','mc.id')
        ->LEFTJOIN('treatments as t','ct.treatments_id','t.id')
        ->WHERE('ct.treatments_id',$treatments)
        ->select('mc.id as id','mc.center_name as name','t.id as center_treatments')
        ->get();


        // foreach ($result as $results) {
        //     foreach($results as $value){
        //         if(!in_array($value, $data)){
        //         $data[]=$value;
        //         }
        //     }

        foreach ($result as $results) {
        $data[$results->id] = $results->name;
            }


        //     echo $results->name.'<br>';
        // }
        }
        $unique = array_unique($data);
        // dd($data);
        foreach($unique as $key => $value) {
          echo "$key is $value";
        }

        // return view('adminpanel.templates.options', compact('result','data'));

        // dd($data);

        // $validate = $request->validate([
        //     'treatment_id'      => 'requried|exists:treatments,id',
        //     'title'             => 'required|string',
        //     'article'           => 'required|string',
        //     'picture'           => 'image|required|mimes:jpeg,png,jpg,gif,svg',
        //     'meta_title'        => 'string|nullable',
        //     'meta_description'  => 'string|nullable',
        //     'url'         => 'string|nullable',
        //     'is_active'         => 'nullable',
        // ]);

        /*
            Defining th uploading path if not exist create new
        */
        // $destinationPath = public_path('/backend/uploads/faqs');
        // if(!File::exists($destinationPath)) {
        //     File::makeDirectory($destinationPath, $mode = 0777, true, true);
        // }

        /*
            Uploading the Image to folder
        */
        // $originalImage = $request->file('picture');
        // $filename      = time().'.'.$originalImage->getClientOriginalExtension();
        // $upload        = $originalImage->move($destinationPath, $filename);

        /*
            Saving the data to database in articles table
        */
        // Faq::create([
        //     'treatment_id'      => $request->input('treatment_id'),
        //     'title'             => $request->input('title'),
        //     'article'           => $request->input('article'),
        //     'picture'           => $filename,
        //     'meta_title'        => $request->input('meta_title'),
        //     'meta_description'  => $request->input('meta_description'),
        //     'url'         => $request->input('url'),
        //     'is_active'         => $request->input('is_active'),
        // ]);

        /*
            Returning the success message on successfull uploading
        */
        // session()->flash('success','FAQ Created Successfully');
        // return redirect()->route('faqs.index');

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
        /*
            Fetching the Artcile Through eloquent Method
        */
        $faq = Faq::find($id);
        /*
            Fetching the Center Through eloquent Method
        */
        $treatments = DB::table('treatments')->where('is_active',1)->get();
        /*
            Passing the article & centers to edit view in adminpanel/articles/edit
        */
        return view('adminpanel.faqs.edit', compact('faq','treatments'));
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
        if ( $request->file('picture') ) {
            $validate = $request->validate([
                'treatment_id'      => 'requried|exists:treatments,id',
                'title'             => 'required|string',
                'article'           => 'required|string',
                'picture'           => 'image|required|mimes:jpeg,png,jpg,gif,svg',
                'meta_title'        => 'string|nullable',
                'meta_description'  => 'string|nullable',
                'url'         => 'string|nullable',
                'is_active'         => 'nullable',
            ]);

            $destinationPath = public_path('/backend/uploads/faqs');
            $originalImage = $request->file('picture');
            $filename = time().'.'.$originalImage->getClientOriginalExtension();
            $upload = $originalImage->move($destinationPath, $filename);

            Faq::where('id', $id)->update([
                'treatment_id'      => $request->input('treatment_id'),
                'title'             => $request->input('title'),
                'article'           => $request->input('article'),
                'picture'           => $request->input('picture'),
                'meta_title'        => $request->input('meta_title'),
                'meta_description'  => $request->input('meta_description'),
                'url'         => $request->input('url'),
                'is_active'         => $request->input('is_active'),
            ]);
            session()->flash('success', 'Faq Updated Successfully');
        } else {
            $validate = $request->validate([
                'treatment_id'      => 'requried|exists:treatments,id',
                'title'             => 'required|string',
                'article'           => 'required|string',
                'meta_title'        => 'string|nullable',
                'meta_description'  => 'string|nullable',
                'url'         => 'string|nullable',
                'is_active'         => 'nullable',
            ]);

            Faq::where('id', $id)->update([
                'treatment_id'      => $request->input('treatment_id'),
                'title'             => $request->input('title'),
                'article'           => $request->input('article'),
                'meta_title'        => $request->input('meta_title'),
                'meta_description'  => $request->input('meta_description'),
                'url'         => $request->input('url'),
                'is_active'         => $request->input('is_active'),
            ]);
            session()->flash('success', 'Faq Updated Successfully');
        }
        return redirect()->route('faqs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /*
            Finding the Artcile Through eloquent Method of given id
        */
        $faq = Faq::find($id);

        /*
            Removing the associated images from the folder of associated article
        */
        $image_path =  public_path()."/backend/uploads/faqs/".$faq->picture;
        File::delete($image_path);

        /*
            If Article is successfully removed redirect to index page of artcile folder
            with success message
        */

        if ( Faq::destroy($id) ) {
            session()->flash('success', 'Faq Deleted Successfully');
            return redirect()->route('faqs.index');
        }
    }
}
