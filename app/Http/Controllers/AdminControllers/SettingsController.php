<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = DB::table('settings')->first();
        return view('adminpanel.settings.index', compact('settings'));
    }

    public function create()
    {
        return view('adminpanel.settings.create');
    }

    public function store(Request $request)
    {
        $setting = DB::table('settings')->insert([
            'mobile'    => $request->input('mobile'),
            'email'     => $request->input('email'),
            'address'   => $request->input('address'),
            'facebook'  => $request->input('facebook'),
            'twitter'   => $request->input('twitter'),
            'skype'     => $request->input('skype'),
            'youtube'   => $request->input('youtube'),
            'instagram' => $request->input('instagram'),
            'pinterest' => $request->input('pinterest'),
            'google_plus'    => $request->input('google_plus')
        ]);

        session()->flash('success', 'Setting Created');
        return redirect()->route('settings.index');
    }

    public function edit($id)
    {
        $setting = DB::table('settings')->where('id', $id)->first();
        return view('adminpanel.settings.edit', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $setting = DB::table('settings')->where('id', $id)->update([
            'mobile'      => $request->input('mobile'),
            'email'       => $request->input('email'),
            'address'     => $request->input('address'),
            'facebook'    => $request->input('facebook'),
            'twitter'     => $request->input('twitter'),
            'skype'       => $request->input('skype'),
            'youtube'     => $request->input('youtube'),
            'instagram'   => $request->input('instagram'),
            'pinterest'   => $request->input('pinterest'),
            'google_plus' => $request->input('google_plus')
        ]);

        session()->flash('success', 'Setting Updated');
        return redirect()->route('settings.index');
    }

}
