<?php

namespace EderSoares\Laravel\PlugAndPlay;

use EderSoares\Laravel\PlugAndPlay\Commands\PackageClearCommand;
use EderSoares\Laravel\PlugAndPlay\Commands\PackageCreateCommand;
use EderSoares\Laravel\PlugAndPlay\Commands\PackageInstallCommand;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PackageClearCommand::class,
                PackageCreateCommand::class,
                PackageInstallCommand::class,
            ]);
        }
    }
}
