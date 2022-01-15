<?php

namespace Pramix\XInvoice;

use Illuminate\Support\ServiceProvider;

class InvoiceServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/views', 'xinvoice');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'xinvoice');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
//        $this->mergeConfigFrom(__DIR__ . '/config/main.php', 'xinvoice');
//
//        $this->publishes([
//            __DIR__ . '/assets' => public_path('pramix/xinvoice'),
//        ], 'public');
    }
}
