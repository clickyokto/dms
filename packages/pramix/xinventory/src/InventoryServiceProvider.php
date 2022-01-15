<?php

namespace Pramix\XInventory;

use Illuminate\Support\ServiceProvider;

class InventoryServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/views', 'xinventory');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'xinventory');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->mergeConfigFrom(__DIR__ . '/config/main.php', 'xinventory');
//
//        $this->publishes([
//            __DIR__ . '/assets' => public_path('pramix/xinvoice'),
//        ], 'public');
    }
}
