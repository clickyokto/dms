<?php

namespace Pramix\XEmailSender;

use Illuminate\Support\ServiceProvider;

class EmailSenderServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
    }


    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'xemail_sender');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'xemail_sender');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        }
}
