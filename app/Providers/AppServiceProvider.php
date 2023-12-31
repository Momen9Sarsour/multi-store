<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
       // App::setLocale(request('locale','en'));
        Validator::extend('filter',function($attribute,$value,$params){
            return ! in_array(strtolower($value), $params);
           },'The value is prohipted!');
        Paginator::useBootstrap();
    }
}
