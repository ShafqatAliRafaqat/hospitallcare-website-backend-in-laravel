<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        if(DB::connection()->getDatabaseName()) {
            $menutreatments = DB::table('treatments')->where('show_in_menu', 1)->where('is_active', 1)->orderby('name','ASC')->get();
            $menuarticles = DB::table('articles')->where('is_active', 1)->orderby('id', 'DESC')->limit(5)->get();
            $setting = DB::table('settings')->first();
            //Sharing is caring
            View::share(['menutreatments'=> $menutreatments,'setting'=> $setting, 'menuarticles' => $menuarticles]);
        } else {
            $treatments   = [];
            $articles = [];
            $setting      = '';
            View::share(['treatments'=> $treatments, 'setting' => $setting,'articles' => $articles ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
