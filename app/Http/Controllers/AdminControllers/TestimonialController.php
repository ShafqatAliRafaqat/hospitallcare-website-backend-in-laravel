<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Article;
use App\Models\Admin\Center;
use App\Models\Admin\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TestimonialController extends Controller
{

    public function index()
    {
        $testimonials = Testimonial::all();
        
        return view('adminpanel.testimonial.index', compact('testimonials'));
    }

    public function create()
    {
        $centers  = Center::where('is_active',1)->get();
        
        return view('adminpanel.testimonial.create', compact('centers'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'center_id'         => 'sometimes',
            'title'             => 'required|string',
            'article'           => 'required|string',
            'focus_area'        => 'required|string',
            'picture'           => 'image|required|mimes:jpeg,png,jpg',
            'is_active'         => 'nullable',
        ]);

        $destinationPath = public_path('/backend/uploads/testimonials');
        
        if(!File::exists($destinationPath)) {
            
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }
        $originalImage = $request->file('picture');
        $filename      = time().'.'.$originalImage->getClientOriginalExtension();
        $upload        = $originalImage->move($destinationPath, $filename);

        Testimonial::create([
            'center_id'         => $request->input('center_id'),
            'title'             => $request->input('title'),
            'article'           => $request->input('article'),
            'focus_area'        => $request->input('focus_area'),
            'picture'           => $filename,
            'is_active'         => $request->input('is_active'),
        ]);

        session()->flash('success','Testimonial Created Successfully');
        
        return redirect()->route('testimonials.index');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::find($id);
        
        $centers = Center::where('is_active',1)->get();
        
        return view('adminpanel.testimonial.edit', compact('testimonial','centers'));
    }

    public function update(Request $request, $id)
    {

        if ( $request->file('picture') ) {
            $validate = $request->validate([
            'center_id'         => 'sometimes',
            'title'             => 'required|string',
            'article'           => 'required|string',
            'focus_area'        => 'required|string',
            'picture'           => 'image|required|mimes:jpeg,png,jpg',
            'is_active'         => 'nullable',
            // 'meta_title'     => 'string|nullable',
            // 'meta_description=> ' string|nullable',
            // 'url'      => 'string|nullable',
            ]);

            $destinationPath = public_path('/backend/uploads/testimonials');
            $originalImage = $request->file('picture');
            $filename = time().'.'.$originalImage->getClientOriginalExtension();
            $upload = $originalImage->move($destinationPath, $filename);

            Testimonial::where('id', $id)->update([
                'center_id'         => $request->input('center_id'),
                'title'             => $request->input('title'),
                'article'           => $request->input('article'),
                'focus_area'        => $request->input('focus_area'),
                'picture'           => $filename,
                'is_active'         => $request->input('is_active'),
            ]);
            session()->flash('success', 'Testimonial Updated Successfully');
        } else {
            $validate = $request->validate([
                'center_id'         => 'sometimes',
                'title'             => 'required|string',
                'article'           => 'required|string',
                'focus_area'        => 'required|string',
                // 'meta_title'     => 'string|nullable',
                // 'meta_description'=> 'string|nullable',
                // 'url'      => 'string|nullable',
                'is_active'         => 'nullable',
            ]);

            Testimonial::where('id', $id)->update([
                'center_id'         => $request->input('center_id'),
                'title'             => $request->input('title'),
                'article'           => $request->input('article'),
                'focus_area'        => $request->input('focus_area'),
                'is_active'         => $request->input('is_active'),
            ]);

            session()->flash('success', 'Article Updated Successfully');
        }
        return redirect()->route('testimonials.index');
    }
    public function destroy($id)
    {
        $article = Testimonial::find($id);
        
        $image_path =  public_path()."/backend/uploads/testimonials/".$article->picture;
        
        File::delete($image_path);
        
        if ( Testimonial::destroy($id) ) {
            
            session()->flash('error', 'Article Deleted Successfully');
            
            return redirect()->route('testimonials.index');
        }
    }
}
