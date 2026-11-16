<?php

namespace JeffersonGoncalves\Lgpd\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JeffersonGoncalves\Lgpd\Lgpd
 */
class Lgpd extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-lgpd';
    }
}
