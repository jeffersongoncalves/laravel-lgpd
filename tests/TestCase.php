<?php

namespace JeffersonGoncalves\Lgpd\Tests;

use JeffersonGoncalves\Lgpd\LgpdServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LgpdServiceProvider::class,
        ];
    }
}
