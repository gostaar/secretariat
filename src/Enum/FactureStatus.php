<?php
namespace App\Enum;

enum FactureStatus: string
{
    case PAYE = 'payé';
    case NON_PAYE = 'non-payé';
    case ANNULE = 'annulé';
    case PAYE_PARTIELLEMENT = 'payé partiellement';
    case EN_ATTENTE = 'en attente de paiement';

    /**
     * Méthode statique pour obtenir toutes les valeurs possibles de l'énumération.
     */
    public static function getValues(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
