<?php

namespace Pramix\XCart;

use Illuminate\Support\ServiceProvider;

class CartsServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/views', 'xcart');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'xcart');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');

    }
}
