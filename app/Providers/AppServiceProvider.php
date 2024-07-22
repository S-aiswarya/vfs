<?php

namespace App\Providers;

use App\Services\Google\Gmail;
use App\Services\Whatsapp\Whatsapp;
use App\SpiderMail\SpiderMail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('spider-mailer',function(){
            return new SpiderMail();
       });

       $this->app->bind(Gmail::class, function($app){
            return new Gmail(config('services.imap.host'), config('services.imap.username'), config('services.imap.password'));
       });

       $this->app->bind(Whatsapp::class, function($app){
            return new Whatsapp(config('services.whatsapp.token'), config('services.whatsapp.phone_number_id'));
       });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        if(request()->get('utm_source'))
        {
            session()->put('utm_source', request()->get('utm_source'));
        }
        
        if(request()->get('utm_medium'))
        {
            session()->put('utm_medium', request()->get('utm_medium'));
        }
        
        if(request()->get('utm_campaign'))
        {
            session()->put('utm_campaign', request()->get('utm_campaign'));
        }
        
        if(request()->get('gclid'))
        {
            session()->put('gclid', request()->get('gclid'));
        }

    }
}
