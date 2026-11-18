<?php

namespace JeffersonGoncalves\Lgpd;

use JeffersonGoncalves\Lgpd\Contracts\HasPersonalData;
use JeffersonGoncalves\Privacy\Privacy;

class Lgpd extends Privacy
{
    protected string $config = 'lgpd';

    protected string $contract = HasPersonalData::class;
}
