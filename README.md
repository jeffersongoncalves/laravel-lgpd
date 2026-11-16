# Laravel LGPD

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-lgpd.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-lgpd)
[![Tests](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-lgpd/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-lgpd/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-lgpd.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-lgpd)

Core of a toolkit for the Brazilian General Data Protection Law (LGPD, Lei 13.709/2018) in Laravel. You declare which columns of your Eloquent models hold personal data. With those declarations the package can:

- build the **personal data inventory** (data mapping that feeds the ROPA, art. 37);
- **export** a record's personal data (data subject access and portability, art. 18, II/V);
- **anonymize** a record, choosing a strategy per column (art. 18, IV/VI).

> This package gives you tooling. It does not make you compliant on its own. You still need legal review for legal bases, retention periods and your privacy notice.

## Installation

```bash
composer require jeffersongoncalves/laravel-lgpd
php artisan vendor:publish --tag="lgpd-config"
```

## Declaring personal data

```php
use JeffersonGoncalves\Lgpd\Attributes\PersonalData;
use JeffersonGoncalves\Lgpd\Concerns\InteractsWithPersonalData;
use JeffersonGoncalves\Lgpd\Contracts\HasPersonalData;
use JeffersonGoncalves\Lgpd\Enums\{AnonymizeStrategy, DataCategory, LegalBasis};

#[PersonalData('name', purpose: 'Account identification', legalBasis: LegalBasis::Contract, anonymize: AnonymizeStrategy::Mask)]
#[PersonalData('email', legalBasis: LegalBasis::Contract, retention: '5 years', anonymize: AnonymizeStrategy::Hash)]
#[PersonalData('health_notes', category: DataCategory::Sensitive, legalBasis: LegalBasis::Consent)]
#[PersonalData('tax_id', legalBasis: LegalBasis::LegalObligation, anonymize: AnonymizeStrategy::Keep)]
class User extends Authenticatable implements HasPersonalData
{
    use InteractsWithPersonalData;
}
```

Register the model in `config/lgpd.php`:

```php
'models' => [
    App\Models\User::class,
],
```

### Anonymize strategies

| Strategy | Result |
|---|---|
| `Nullify` (default) | `null` |
| `Hash` | HMAC-SHA256 keyed by `LGPD_HASH_KEY` (falls back to `APP_KEY`). It gives a stable pseudonym, so unique indexes keep working |
| `Mask` | `Maria Silva` → `M**********` |
| `Keep` | untouched, for data you must keep by law (e.g. tax records) |

Encrypted casts (`'encrypted'`) work as expected: the value is decrypted, then anonymized, then encrypted again.

## Usage

```php
use JeffersonGoncalves\Lgpd\Facades\Lgpd;

Lgpd::inventory();                 // one row per declared column
$user->exportPersonalData();       // ['name' => ..., 'email' => ..., ...]
$user->anonymizePersonalData();    // applies each strategy and saves
```

```bash
php artisan lgpd:inventory          # table
php artisan lgpd:inventory --json   # machine readable
```

## Testing

```bash
composer test
```

## Changelog

See [CHANGELOG](CHANGELOG.md).

## License

The MIT License (MIT). See [License File](LICENSE.md).
