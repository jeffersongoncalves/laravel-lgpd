<?php

namespace JeffersonGoncalves\Lgpd;

use JeffersonGoncalves\Privacy\Generalize as BaseGeneralize;

/**
 * Generalizers for quasi-identifiers: each returns a closure that coarsens a value
 * (null stays null), for Lgpd::kAnonymity() or for building an anonymized dataset.
 */
final class Generalize extends BaseGeneralize {}
