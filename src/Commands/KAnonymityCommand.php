<?php

namespace JeffersonGoncalves\Lgpd\Commands;

use JeffersonGoncalves\Lgpd\Lgpd;
use JeffersonGoncalves\Privacy\Commands\KAnonymityCommand as BaseKAnonymityCommand;

class KAnonymityCommand extends BaseKAnonymityCommand
{
    protected $signature = 'lgpd:k-anonymity
        {model : Eloquent model class}
        {columns* : Quasi-identifier columns}
        {--k=5 : Minimum group size}
        {--sensitive= : Sensitive column for l-diversity}';

    protected string $privacy = Lgpd::class;
}
