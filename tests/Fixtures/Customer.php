<?php

namespace JeffersonGoncalves\Lgpd\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\Lgpd\Attributes\PersonalData;
use JeffersonGoncalves\Lgpd\Concerns\InteractsWithPersonalData;
use JeffersonGoncalves\Lgpd\Contracts\HasPersonalData;
use JeffersonGoncalves\Lgpd\Enums\AnonymizeStrategy;
use JeffersonGoncalves\Lgpd\Enums\DataCategory;
use JeffersonGoncalves\Lgpd\Enums\LegalBasis;

#[PersonalData('name', purpose: 'Identificação', legalBasis: LegalBasis::Contract, anonymize: AnonymizeStrategy::Mask)]
#[PersonalData('email', legalBasis: LegalBasis::Contract, retention: '5 years', anonymize: AnonymizeStrategy::Hash)]
#[PersonalData('health_notes', category: DataCategory::Sensitive, legalBasis: LegalBasis::Consent)]
#[PersonalData('tax_id', legalBasis: LegalBasis::LegalObligation, anonymize: AnonymizeStrategy::Keep)]
class Customer extends Model implements HasPersonalData
{
    use InteractsWithPersonalData;

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return ['health_notes' => 'encrypted'];
    }
}
