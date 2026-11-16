<?php

namespace JeffersonGoncalves\Lgpd\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static list<class-string> models()
 * @method static list<array<string, mixed>> inventory()
 *
 * @see \JeffersonGoncalves\Lgpd\Lgpd
 */
class Lgpd extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JeffersonGoncalves\Lgpd\Lgpd::class;
    }
}
