<?php

namespace JeffersonGoncalves\Lgpd\Commands;

use JeffersonGoncalves\Lgpd\Lgpd;
use JeffersonGoncalves\Privacy\Commands\InventoryCommand as BaseInventoryCommand;

class InventoryCommand extends BaseInventoryCommand
{
    protected $signature = 'lgpd:inventory {--json : Output as JSON}';

    protected string $privacy = Lgpd::class;
}
