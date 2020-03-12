<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WebService;
class DocsController extends Controller
{
    //
    
    public function index() {
        $app_doc =1;
        if($app_doc === 1){
            return redirect('/');
        }
        $docs = WebService::all()->groupBy('module');
        return view('docs', ['docs' =>$docs]);
    }
    
    public function detail($id) {
        $app_doc =1;
        if($app_doc === 1){
            return redirect('/');
        }
        $doc = WebService::findOrFail($id);
        return view('docs-detail', ['doc' =>$doc]);
    }
}
