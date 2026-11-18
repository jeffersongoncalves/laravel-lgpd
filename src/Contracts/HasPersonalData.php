<?php

namespace JeffersonGoncalves\Lgpd\Contracts;

use JeffersonGoncalves\Lgpd\Attributes\PersonalData;
use JeffersonGoncalves\Privacy\Contracts\HasPersonalData as BaseHasPersonalData;

interface HasPersonalData extends BaseHasPersonalData
{
    /**
     * @return list<PersonalData>
     */
    public static function personalDataFields(): array;

    /**
     * Declared personal data of this record, keyed by column (LGPD art. 18, II/V).
     *
     * @return array<string, mixed>
     */
    public function exportPersonalData(): array;

    /**
     * Apply each column's anonymize strategy and persist (LGPD art. 18, IV/VI).
     */
    public function anonymizePersonalData(): void;
}
