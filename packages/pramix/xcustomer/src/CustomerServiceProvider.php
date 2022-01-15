<?php

namespace Pramix\XCustomer;

use Illuminate\Support\ServiceProvider;

class CustomerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'xcustomer');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'xcustomer');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');

    }

    public function register()
    {
        //
    }
}
