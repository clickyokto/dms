<?php

namespace Pramix\XBranches;

use Illuminate\Support\ServiceProvider;

class BranchesServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'xbranches');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'xbranches');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');

    }

    public function register()
    {
        //
    }
}
