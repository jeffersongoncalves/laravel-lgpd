<?php

namespace JeffersonGoncalves\Lgpd\Enums;

/**
 * Legal bases for processing (LGPD art. 7; sensitive data follows art. 11).
 */
enum LegalBasis: string
{
    case Consent = 'consent';                         // art. 7, I
    case LegalObligation = 'legal_obligation';        // art. 7, II
    case PublicPolicy = 'public_policy';              // art. 7, III
    case Research = 'research';                       // art. 7, IV
    case Contract = 'contract';                       // art. 7, V
    case ExerciseOfRights = 'exercise_of_rights';     // art. 7, VI
    case LifeProtection = 'life_protection';          // art. 7, VII
    case HealthProtection = 'health_protection';      // art. 7, VIII
    case LegitimateInterest = 'legitimate_interest';  // art. 7, IX
    case CreditProtection = 'credit_protection';      // art. 7, X
    case FraudPrevention = 'fraud_prevention';        // art. 11, II, g (sensitive data only)
}
