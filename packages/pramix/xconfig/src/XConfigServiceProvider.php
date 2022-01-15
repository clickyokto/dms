<?php

namespace Pramix\XConfig;


use Illuminate\Support\ServiceProvider;

class XConfigServiceProvider extends ServiceProvider
{

    protected $seeds_path = __DIR__ . '/seeds';
    
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
        $this->loadViewsFrom(__DIR__ . '/views', 'xconfig');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'xconfig');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->mergeConfigFrom(__DIR__ . '/config/main.php', 'xconfig');
    }
}
