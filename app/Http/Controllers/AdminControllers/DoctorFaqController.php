<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\DoctorFaq;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DoctorFaqController extends Controller
{
    public function doctor_faqs($doctor_id)
    {
        $faqs   =   DoctorFaq::where('doctor_id',$doctor_id)->get();
        return view('adminpanel.doctorfaqs.index',compact('faqs','doctor_id'));
    }
    public function store_faqs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question'      => 'required',
            'answer'        => 'required',
            'is_active'     => 'nullable',
        ]);
        if ($validator->fails()) {
            session()->flash('error', 'Please Fill out all the Fields');
            return back();
        }
        $answer                 =   $request->answer;
        $question               =   $request->question;
        $is_active              =   $request->is_active;
        $store              =   DB::table('doctor_faqs')->insert([
            'doctor_id'     =>  $request->doctor_id,
            'question'      =>  $question,
            'answer'        =>  $answer,
            'is_active'     =>  $is_active,
            'created_by'    =>  Auth::user()->id,
        ]);
       if ($store) {
            session()->flash('success', 'FAQs saved Successfully!');
            return back();
        } else {
            session()->flash('error', 'Could not Save!');
            return back();
        }
    }
    public function edit($id)
    {
        $faq    =   DoctorFaq::where('id',$id)->first();
        return view('adminpanel.doctorfaqs.edit',compact('faq'));
    }
    public function update(Request $request ,$id)
    {
        // dd($request->all());
        $faq    =   DoctorFaq::where('id',$id)->first();
        if ($faq) {
            $update     =   DB::table('doctor_faqs')->where('id',$id)->update([
                'question'      =>  $request->question,
                'answer'        =>  $request->answer,
                'is_active'     =>  isset($request->is_active) ? $request->is_active : 0,
                'updated_by'    =>  Auth::user()->id,
                'updated_at'    =>  Carbon::now()->toDateTimeString(),
            ]);
            session()->flash('success', 'FAQs Updated Successfully!');
            return redirect()->route('doctor_faqs',$faq->doctor_id);
        } else {
            session()->flash('error', 'FAQs Updated Successfully!');
            return back();
        }
    }
    public function delete_faqs(Request $request,$id)
    {
        $delete     =   DoctorFaq::where('id',$request->id)->delete();
        if ($delete) {
            return response()->json(["data" =>"Deleted"]);
        } else {
            abort(403);
        }
    }
}
