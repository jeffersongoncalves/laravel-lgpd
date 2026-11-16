<?php

namespace JeffersonGoncalves\Lgpd;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LgpdServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-lgpd')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations();
    }
}
