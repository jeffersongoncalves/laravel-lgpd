<?php

namespace JeffersonGoncalves\Lgpd\Enums;

enum DataCategory: string
{
    /** LGPD art. 5, I. */
    case Personal = 'personal';

    /** LGPD art. 5, II — racial/ethnic origin, religion, politics, health, sex life, genetic, biometric. */
    case Sensitive = 'sensitive';
}
