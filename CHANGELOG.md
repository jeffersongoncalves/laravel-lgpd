# Changelog

All notable changes to this project will be documented in this file.

## 1.1.0 - 2026-11-16

- `anonymizePersonalData()` dispatches `PersonalDataAnonymized($model, $columns)` after saving, with the columns it changed (`Keep` columns skipped). laravel-lgpd-retention 1.1 uses it to tombstone DSAR erasures.
- A save cancelled by an observer now throws `RuntimeException` instead of silently leaving the data in place.

## 1.0.0 - 2026-11-16

First release: LGPD core for Laravel.\n\n- `#[PersonalData]` attribute to declare personal data per column (category, purpose, legal basis, retention, anonymize strategy)\n- `HasPersonalData` contract + `InteractsWithPersonalData` trait: `exportPersonalData()` / `anonymizePersonalData()`\n- Anonymize strategies: Nullify, Hash (keyed HMAC), Mask, Keep\n- `Lgpd::inventory()` + `php artisan lgpd:inventory [--json]`

## [Unreleased]
