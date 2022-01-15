<?php

namespace Pramix\XUser;

use Illuminate\Support\ServiceProvider;

class XUserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'xuser');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'xuser');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
//           $this->mergeConfigFrom(__DIR__.'/config/accounting.php', 'accounting');

        /*$this->publishes([
            _DIR_ . '/assets' => public_path('pramix/xuser'),
                ], 'public');*/
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //include __DIR__.'/routes.php';
        //$this->app->make('Pramix\XUser\XUserController');
    }
}
