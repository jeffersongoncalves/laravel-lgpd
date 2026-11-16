<?php

namespace JeffersonGoncalves\Lgpd;

use JeffersonGoncalves\Lgpd\Commands\InventoryCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LgpdServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('lgpd')
            ->hasConfigFile()
            ->hasCommand(InventoryCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(Lgpd::class);
    }
}
