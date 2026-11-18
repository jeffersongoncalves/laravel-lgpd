# Laravel LGPD

![Laravel LGPD](https://raw.githubusercontent.com/jeffersongoncalves/laravel-lgpd/main/art/jeffersongoncalves-laravel-lgpd.png)

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

`anonymizePersonalData()` fires `JeffersonGoncalves\Lgpd\Events\PersonalDataAnonymized` with the model and the columns it changed. Columns marked `Keep` are not in that list. [laravel-lgpd-retention](https://github.com/jeffersongoncalves/laravel-lgpd-retention) listens to it to write tombstones. With those, it can erase again any data that a backup restore brings back.

```bash
php artisan lgpd:inventory          # table
php artisan lgpd:inventory --json   # machine readable
```

## k-anonymity

Before you share or publish a dataset as anonymized (art. 12), check that nobody can be singled out by combining quasi-identifiers such as ZIP code, birth date and gender. A dataset is *k*-anonymous when every combination of those values is shared by at least *k* rows. It is *l*-diverse when each of those groups also holds at least *l* distinct sensitive values.

```php
use JeffersonGoncalves\Lgpd\Facades\Lgpd;
use JeffersonGoncalves\Lgpd\Generalize;

Lgpd::kAnonymity(Patient::class, [
    'zip' => Generalize::prefix(5),         // 01310-100 → 01310
    'birth_date' => Generalize::date('Y'),  // 1990-03-01 → 1990
    'age' => Generalize::range(10),         // 34 → 30-39
    'gender',                               // as stored
], k: 5, sensitive: 'diagnosis');

// ['rows' => 1200, 'groups' => 87, 'k' => 3, 'rows_below_k' => 14, 'l' => 1]
```

`k` is the smallest group, `rows_below_k` counts the rows you still need to generalize further or suppress, and `l` is the smallest number of distinct sensitive values in a group. It accepts a model class or a query builder. Values are read through the model, so casts apply. The generalizers are plain closures, so you can also use them to build the dataset you export.

```bash
php artisan lgpd:k-anonymity "App\Models\Patient" zip birth_date --k=5 --sensitive=diagnosis
```

The command fails (exit code 1) when the data is below the target *k*, so it can run in CI or before a scheduled export. It scans every row, so run it off-peak on big tables.

## Upgrading to 2.0

2.0 runs on [laravel-privacy](https://github.com/jeffersongoncalves/laravel-privacy), the engine shared by the
LGPD, GDPR, CCPA, US privacy and Quebec suites. The classes, config keys, tables, commands and events of this package
keep their names: your code, config and data need no change. Suites installed side by side never see each other's
events. If you extend a class of this package, it now inherits from its `JeffersonGoncalves\Privacy` base.

## Testing

```bash
composer test
```

## Changelog

See [CHANGELOG](CHANGELOG.md).

## License

The MIT License (MIT). See [License File](LICENSE.md).
