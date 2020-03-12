<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CenterController extends Controller
{
	public function article(Request $request)
	{
		$validator = $request->validate([
			'type'    => 'required|in:profile,details,treatments,qualification,testimonials,awards,locations',
			'article' => 'required'
		]);

		$type    = $request->input('type');
		$article = $request->input('article');
		$id      = $request->input('center_id');
		$fetch = DB::table('center_details')->where(['type' => $type, 'center_id'=>$id])->first();

		if ( !$fetch ) {
			$insert = DB::table('center_details')
		            ->insert(['center_id'=>$id,'article'=>$article,'type'=>$type]);
			Session::flash('success', ucfirst($type).' Article Created');
			return redirect()->route('medical.index');
		} else {
			Session::flash('error', 'You already have '.ucfirst($type).' Article');
			return back();
		}
	}

	public function edit_article(Request $request,$id)
	{
		DB::table('center_details')->where('id', $id)
		->update( ['article' => $request->input('article') ]);

		Session::flash('success', 'Article updated Successfully');
		return back();
	}
}
