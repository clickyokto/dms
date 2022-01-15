<?php

namespace Pramix\XPurchaseOrder;

use Illuminate\Support\ServiceProvider;

class PurchaseOrderServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/views', 'xpurchase_order');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'xpurchase_order');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
//        $this->mergeConfigFrom(__DIR__ . '/config/main.php', 'xpurchase_order');
//
//        $this->publishes([
//            __DIR__ . '/assets' => public_path('pramix/xpurchase_order'),
//        ], 'public');
    }
}
