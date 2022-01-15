<?php

namespace Pramix\XReports;

use Illuminate\Support\ServiceProvider;

class ReportsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'xreports');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'xreports');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
//        $this->mergeConfigFrom(__DIR__ . '/config/main.php', 'xquotation');
//
//        $this->publishes([
//            __DIR__ . '/assets' => public_path('pramix/xquotation'),
//        ], 'public');
    }
}
