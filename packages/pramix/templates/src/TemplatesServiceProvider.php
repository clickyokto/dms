<?php

namespace Pramix\Templates;

use Illuminate\Support\ServiceProvider;

class TemplatesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

         $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'templates');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'templates');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
//        $this->mergeConfigFrom(__DIR__ . '/config/templates.php', 'templates');
//
//        $this->publishes([
//            __DIR__ . '/assets' => public_path('pramix/templates'),
//                ], 'public');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
