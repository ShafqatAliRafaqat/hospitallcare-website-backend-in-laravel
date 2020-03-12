<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Article;
use App\Models\Admin\Center;
use App\Models\Admin\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $articles = Article::all();
        return view('adminpanel.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $treatments  = Treatment::where('is_active',1)->get();
        return view('adminpanel.articles.create', compact('treatments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*
            Validating the Requested Parameters
        */
        $validate = $request->validate([
            'treatment_id'      => 'required|exists:treatments,id',
            'title'             => 'required|string',
            'article'           => 'required|string',
            'category'          => 'required|string',
            'picture'           => 'required|image',
            'meta_title'        => 'string|nullable',
            'meta_description'  => 'string|nullable',
            'url'         => 'string|nullable',
            'is_active'         => 'nullable',
        ]);


        /*
            Defining th uploading path if not exist create new
        */
        $destinationPath = '/backend/uploads/articles/';
        if(!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }
        /*
            Uploading the Image to folder
        */
        $image       = $request->file('picture');
        $filename    = time().'.'.$image->getClientOriginalExtension();
        $resizeName  = '540x370-'.$filename;
        $resizeName2 = '80x55-'.$filename;
        $location    = public_path($destinationPath.$filename);
        $resizeLoc   = public_path($destinationPath.$resizeName);
        $resizeLoc2  = public_path($destinationPath.$resizeName2);
        Image::make($image)->save($location);
        Image::make($image)->resize(540,370)->save($resizeLoc);
        Image::make($image)->resize(80,55)->save($resizeLoc2);
        /*
            Saving the data to database in articles table
        */
        Article::create([
            'treatment_id'      => $request->input('treatment_id'),
            'title'             => $request->input('title'),
            'article'           => $request->input('article'),
            'category'          => $request->input('category'),
            'picture'           => $filename,
            'meta_title'        => $request->input('meta_title'),
            'meta_description'  => $request->input('meta_description'),
            'url'         => $request->input('url'),
            'is_active'         => $request->input('is_active'),
        ]);
        /*
            Returning the success message on successfull uploading
        */
        session()->flash('success','Article Created Successfully');
        return redirect()->route('articles.index');
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
        $article = Article::find($id);
        /*
            Fetching the Center Through eloquent Method
        */
        $treatments = Treatment::where('is_active',1)->get();
        /*
            Passing the article & centers to edit view in adminpanel/articles/edit
        */
        return view('adminpanel.articles.edit', compact('article','treatments'));
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
                'treatment_id'      => 'required|exists:treatments,id',
                'title'             => 'required|string',
                'article'           => 'required|string',
                'category'          => 'required|string',
                'picture'           => 'required|image|max:2048',
                'meta_title'        => 'string|nullable',
                'meta_description'  => 'string|nullable',
                'url'         => 'string|nullable',
                'is_active'         => 'nullable',
            ]);

            $destinationPath = '/backend/uploads/articles/';
            /*
                Uploading the Image to folder
            */
            $image       = $request->file('picture');
            $filename    = time().'.'.$image->getClientOriginalExtension();
            $resizeName  = '540x370-'.$filename;
            $resizeName2 = '80x55-'.$filename;
            $location    = public_path($destinationPath.$filename);
            $resizeLoc   = public_path($destinationPath.$resizeName);
            $resizeLoc2  = public_path($destinationPath.$resizeName2);
            Image::make($image)->save($location);
            Image::make($image)->resize(540,370)->save($resizeLoc);
            Image::make($image)->resize(80,55)->save($resizeLoc2);
            $article = Article::find($id);
            $image_path =  public_path()."/backend/uploads/articles/".$article->picture;
            $imageMedium = public_path()."/backend/uploads/articles/540x370-".$article->picture;
            $imageSmall = public_path()."/backend/uploads/articles/80x55-".$article->picture;
            File::delete($image_path);
            File::delete($imageMedium);
            File::delete($imageSmall);

            Article::where('id', $id)->update([
                'treatment_id'      => $request->input('treatment_id'),
                'title'             => $request->input('title'),
                'article'           => $request->input('article'),
                'category'          => $request->input('category'),
                'picture'           => $filename,
                'meta_title'        => $request->input('meta_title'),
                'meta_description'  => $request->input('meta_description'),
                'url'         => $request->input('url'),
                'is_active'         => $request->input('is_active'),
            ]);
            session()->flash('success', 'Article Updated Successfully');
        } else {
            $validate = $request->validate([
                'treatment_id'      => 'required|exists:treatments,id',
                'title'             => 'required|string',
                'article'           => 'required|string',
                'category'          => 'required|string',
                'meta_title'        => 'string|nullable',
                'meta_description'  => 'string|nullable',
                'url'         => 'string|nullable',
                'is_active'         => 'nullable',
            ]);

            Article::where('id', $id)->update([
                'treatment_id'      => $request->input('treatment_id'),
                'title'             => $request->input('title'),
                'article'           => $request->input('article'),
                'category'          => $request->input('category'),
                'meta_title'        => $request->input('meta_title'),
                'meta_description'  => $request->input('meta_description'),
                'url'         => $request->input('url'),
                'is_active'         => $request->input('is_active'),
            ]);
            session()->flash('success', 'Article Updated Successfully');
        }
        return redirect()->route('articles.index');
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
        $article = Article::find($id);

        /*
            Removing the associated images from the folder of associated article
        */
        $image_path =  public_path()."/backend/uploads/articles/".$article->picture;
        File::delete($image_path);

        /*
            If Article is successfully removed redirect to index page of artcile folder
            with success message
        */

        if ( Article::destroy($id) ) {
            session()->flash('success', 'Article Deleted Successfully');
            return redirect()->route('articles.index');
        }
    }
}
