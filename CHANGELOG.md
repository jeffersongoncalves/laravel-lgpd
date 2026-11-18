# Changelog

All notable changes to this project will be documented in this file.

## 2.0.0 - 2026-11-18

### Breaking

- The engine moves to [laravel-privacy](https://github.com/jeffersongoncalves/laravel-privacy), the base shared by the LGPD, GDPR, CCPA, US privacy and Quebec suites. Every class of this package keeps its name and now extends its `JeffersonGoncalves\Privacy` base. If you extend one of them, check your overrides.

### Unchanged

- Config keys, tables, migrations, commands, events and the `#[PersonalData]` declarations: apps need no change.

### Added

- Suites installed side by side (e.g. LGPD and GDPR) only see their own declarations and events.

## 1.2.0 - 2026-11-16

- `Lgpd::kAnonymity($source, $quasiIdentifiers, k: 5, sensitive: null)`: measures k-anonymity over quasi-identifier columns and, with a sensitive column, l-diversity. Returns rows, groups, k, rows_below_k and l. Values go through the model, so casts apply; unavailable columns are rejected.
- `Generalize::prefix()`, `range()` and `date()`: closures that coarsen quasi-identifiers (ZIP prefix, number buckets, year/month).
- `php artisan lgpd:k-anonymity {model} {columns*} --k=5 --sensitive=`: exits 1 below the target k, for CI or before an export.

## 1.1.0 - 2026-11-16

- `anonymizePersonalData()` dispatches `PersonalDataAnonymized($model, $columns)` after saving, with the columns it changed (`Keep` columns skipped). laravel-lgpd-retention 1.1 uses it to tombstone DSAR erasures.
- A save cancelled by an observer now throws `RuntimeException` instead of silently leaving the data in place.

## 1.0.0 - 2026-11-16

First release: LGPD core for Laravel.\n\n- `#[PersonalData]` attribute to declare personal data per column (category, purpose, legal basis, retention, anonymize strategy)\n- `HasPersonalData` contract + `InteractsWithPersonalData` trait: `exportPersonalData()` / `anonymizePersonalData()`\n- Anonymize strategies: Nullify, Hash (keyed HMAC), Mask, Keep\n- `Lgpd::inventory()` + `php artisan lgpd:inventory [--json]`

## [Unreleased]
