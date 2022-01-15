<?php

namespace Pramix\XCommunication;

use Illuminate\Support\ServiceProvider;

class XCommunicationServiceProvider extends ServiceProvider {

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'xcommunication');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'xcommunication');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->mergeConfigFrom(__DIR__ . '/config/main.php', 'xcommunication');

        $this->publishes([
            __DIR__ . '/assets' => public_path('pramix/xcommunication'),
                ], 'public');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
