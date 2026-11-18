<?php

namespace JeffersonGoncalves\Lgpd\Attributes;

use Attribute;
use JeffersonGoncalves\Lgpd\Enums\AnonymizeStrategy;
use JeffersonGoncalves\Lgpd\Enums\DataCategory;
use JeffersonGoncalves\Lgpd\Enums\LegalBasis;
use JeffersonGoncalves\Privacy\Attributes\PersonalDataAttribute;

/**
 * Declares one column of an Eloquent model as personal data (LGPD art. 5, I/II).
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class PersonalData extends PersonalDataAttribute
{
    public function __construct(
        public string $column,
        public DataCategory $category = DataCategory::Personal,
        public ?string $purpose = null,
        public ?LegalBasis $legalBasis = null,
        public ?string $retention = null,
        AnonymizeStrategy $anonymize = AnonymizeStrategy::Nullify,
    ) {
        $this->anonymize = $anonymize;
    }

    /**
     * @return array{column: string, category: string, purpose: string|null, legal_basis: string|null, retention: string|null, anonymize: string}
     */
    public function toArray(): array
    {
        return [
            'column' => $this->column,
            'category' => $this->category->value,
            'purpose' => $this->purpose,
            'legal_basis' => $this->legalBasis?->value,
            'retention' => $this->retention,
            'anonymize' => $this->anonymize->value,
        ];
    }
}
