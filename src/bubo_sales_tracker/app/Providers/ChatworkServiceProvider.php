<?php

namespace app\Providers;

use Illuminate\Support\ServiceProvider;

class ChatworkServiceProvider extends ServiceProvider
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
        //
        $this->app->singleton(
            'Chatwork',
            'App\Models\Chatwork'
        );
    }
}
