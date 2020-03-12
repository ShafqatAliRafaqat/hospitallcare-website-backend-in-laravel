<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index()
    {
        return redirect()->route('adminDashboard');
        $centers    = DB::table('medical_centers as mc')
        ->LEFTJOIN('center_treatments as ct','ct.med_centers_id','mc.id')
        ->LEFTJOIN('treatments as t','ct.treatments_id','t.id')
        ->where('mc.is_active', 1)
        ->select(DB::raw('GROUP_CONCAT(t.name) as treatment'),DB::raw('GROUP_CONCAT(t.id) as ids'))
        ->get();
        $treatments = DB::table('treatments')->where('parent_id',null)->where('is_active',1)->limit(5)->get();
        $procedures = DB::table('procedures as p')->where('is_active', 1)->get();
        $articles   = DB::table('articles as a')->leftjoin('treatments as t','t.id','a.treatment_id')
                        ->select('a.*', 't.name as name')->where('a.is_active', 1)->get();
        // dd($treatments);
        return view('hospitall.home', compact('treatments','articles'));
    }

    public function about()
    {
        return view('hospitall.about');
    }

    public function promise()
    {
        return view('hospitall.promise');
    }

    public function leadership()
    {
        return view('hospitall.leadership');
    }

    public function claims()
    {
        return view('hospitall.products.claims');
    }

    public function health()
    {
        return view('hospitall.products.health');
    }

    public function concierge()
    {
        return view('hospitall.products.concierge');
    }

    public function tourism()
    {
        return view('hospitall.products.tourism');
    }

    public function csr()
    {
        return view('hospitall.products.csr');
    }

    public function contact()
    {
        return view('hospitall.contact');
    }

    public function all_articles()
    {
        $articles = DB::table('articles')->where('is_active', 1)->paginate(10);
        $links      = $articles->links();
        $pagination = str_replace('class="pagination"','class="pagination theme-colored pull-right xs-pull-center mb-xs-40"', $links);
        return view('hospitall.articles',  compact('articles','pagination'));
    }

    public function blogs()
    {
        $blogs = DB::table('articles')->where('is_active', 1)->orderby('updated_at','Desc')->paginate(10);
        $links      = $blogs->links();
        $pagination = str_replace('class="pagination"','class="pagination theme-colored pull-right xs-pull-center mb-xs-40"', $links);
        return view('hospitall.blogs', compact('blogs','pagination'));
    }

    public function all_procedures()
    {
        $treatments = DB::table('treatments')->where('is_active',1)->paginate(10);
        $links      = $treatments->links();
        $pagination = str_replace('class="pagination"','class="pagination theme-colored pull-right xs-pull-center mb-xs-40"', $links);
        return view('hospitall.procedures', compact('treatments','pagination'));
    }

    public function procedure_detail($id,$slug)
    {
        $treatment = DB::table('treatments')->where('id', $id)->first();

        if ($treatment) {
            $procedures = DB::table('treatments')->where('parent_id', $treatment->id)->orderby('id', 'DESC')
                          ->limit(5)->get();
            $treatments = DB::table('treatments')->where('id','<>',$treatment->id)->where('parent_id', null)->limit(5)->get();
            return view('hospitall.procedure-article', compact('treatment','procedures','treatments'));
        } else {
            abort(404);
        }
    }

    public function sub_procedure($id,$slug)
    {
        $procedure = DB::table('procedures')->where('id',$id)->first();
        if ($procedure) {
            $procedures = DB::table('procedures')->where('id','<>', $procedure->id)->where('treatment_id',$procedure->treatment_id)->get();
            $treatments = DB::table('treatments')->limit(5)->get();
            return view('hospitall.sub-procedure', compact('procedure','procedures','treatments'));
        } else {
            abort(404);
        }
    }

    public function article($id,$slug)
    {
        $article  = DB::table('articles')->where('id', $id)->first();
        if ($article) {
            $articles = DB::table('articles')->where('id','<>',$id)->get();
            return view('hospitall.article', compact('article', 'articles'));
        } else {
            abort(404);
        }

    }

    /* Search Treatment Functions */
    public function search_treatment(Request $request)
    {
        $slug = $request->term;
        $treatment = DB::table('treatments')->where('name','like','%'.$slug.'%')->orderby('id', 'DESC')->get();
        if ( count($treatment) > 0 ) {
            foreach($treatment as $key => $t) {
                $results[] = $t->name;
            }
        } else {
            $results[] = 'No item Found';
        }
        return $results;
    }

    public function search_result($slug)
    {
        $slug = str_replace('-',' ',$slug);
        $treatments = DB::table('treatments')
                      ->where('is_active', 1)
                      ->where(function($w) use($slug){ $w->where('name','like','%'.$slug.'%')->orWhere('article','like','%'.$slug.'%'); })
                      ->paginate(10);
        $links = $treatments->links();
        $pagination = str_replace('class="pagination"','class="pagination theme-colored pull-right xs-pull-center mb-xs-40"', $links);
        return view('hospitall.search-result', compact('treatments','pagination','slug'));
    }

    /* Search Blogs Functions */
    public function search_blogs(Request $request)
    {
        $slug = $request->term;
        $articles = DB::table('articles')->where('title','like','%'.$slug.'%')->get();
        if ( count($articles) > 0 ) {
            foreach($articles as $key => $t) {
                $results[] = $t->title;
            }
        } else {
            $results[] = 'No item Found';
        }
        return $results;
    }

    public function search_blog_result($slug)
    {
        $slug = str_replace('-',' ',$slug);
        $blogs = DB::table('articles')
                      ->where('title','like','%'.$slug.'%')
                      ->orWhere('article','like','%'.$slug.'%')
                      ->paginate(10);
        $links = $blogs->links();
        $pagination = str_replace('class="pagination"','class="pagination theme-colored pull-right xs-pull-center mb-xs-40"', $links);
        return view('hospitall.search-blog-result', compact('blogs','pagination','slug'));
    }


    /* Contact Us Email */
    public function contact_form(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'number'    => 'required',
            'date'      => 'required',
            'time_slot' => 'required',
            'procedure' => 'required',
            'email'     => 'required|email',
        ]);
        $data               = [];
        $data['name']       = $request->name;
        $data['email']      = $request->email;
        $data['number']     = $request->number;
        $data['date']       = $request->date;
        $data['time_slot']  = $request->time_slot;
        $data['procedure']  = $request->procedure;

        $mail = Mail::send('hospitall.emails.contact', $data, function ($message) use ($data){
                    $message->from($data['email'], $data['name']);
                    $message->to('hello@hospitall.tech');
                });
        if ($mail){
            session()->flash('success', 'We have received your Message, Will get back to you soon. Thanks for patience');
            return back();
        } else {
            session()->flash('error', 'Something went wrong try again later');
            return back();
        }
    }
}
