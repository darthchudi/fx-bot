<?php

namespace App\Providers;

use Codebird\Codebird;
use App\Services\Twitter\TwitterService;
use App\Services\Twitter\CodeBirdTwitterService;
use Illuminate\Support\ServiceProvider;

class TwitterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TwitterService::class, function ($app){
            return new CodeBirdTwitterService;
        });
    }
}
