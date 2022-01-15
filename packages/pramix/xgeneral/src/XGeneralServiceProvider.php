<?php

namespace Pramix\XGeneral;


use Illuminate\Support\ServiceProvider;

class XGeneralServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
         $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'xgeneral');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'xgeneral');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->mergeConfigFrom(__DIR__ . '/config/main.php', 'xgeneral');

        $this->publishes([
            __DIR__ . '/assets' => public_path('pramix/xgeneral'),
                ], 'public');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
