<?php

namespace Pramix\XMedia;

use Illuminate\Support\ServiceProvider;

class XMediaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
         $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'xmedia');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'xmedia');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->mergeConfigFrom(__DIR__ . '/config/main.php', 'xmedia');

        $this->publishes([
            __DIR__ . '/assets' => public_path('pramix/xmedia'),
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
