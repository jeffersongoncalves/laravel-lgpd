# Changelog

All notable changes to this project will be documented in this file.

## 1.0.0 - 2026-11-16

First release: LGPD core for Laravel.\n\n- `#[PersonalData]` attribute to declare personal data per column (category, purpose, legal basis, retention, anonymize strategy)\n- `HasPersonalData` contract + `InteractsWithPersonalData` trait: `exportPersonalData()` / `anonymizePersonalData()`\n- Anonymize strategies: Nullify, Hash (keyed HMAC), Mask, Keep\n- `Lgpd::inventory()` + `php artisan lgpd:inventory [--json]`

## [Unreleased]
