<?php

namespace Isaac\Smstool;

use function foo\func;
use Illuminate\Support\ServiceProvider;

class SmstoolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {




        $this->publishes([

            __DIR__."/../database/migrations" =>base_path("database/migrations"),
            __DIR__."/../config"=>config_path("smstool")


        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Smstool', function(){
            return new Smstool();
        });
    }
}
