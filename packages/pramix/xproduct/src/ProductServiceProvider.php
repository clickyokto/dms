<?php

namespace Pramix\XProduct;

use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/views', 'xproduct');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'xproduct');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');

    }
}
