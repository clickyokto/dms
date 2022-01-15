<?php

namespace Pramix\XPayment;

use Illuminate\Support\ServiceProvider;

class PaymentsServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/views', 'xpayment');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'xpayment');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');

    }
}
