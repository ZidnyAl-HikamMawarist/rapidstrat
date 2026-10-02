<?php

namespace Rapidstrat;

use Illuminate\Support\ServiceProvider;
use Rapidstrat\Commands\InstallCommand;

class RapidstratServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }
}
