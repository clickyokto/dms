<?php

namespace Pramix\XGRN;

use Illuminate\Support\ServiceProvider;

class GRNServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/views', 'xgrn');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'xgrn');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }
}
