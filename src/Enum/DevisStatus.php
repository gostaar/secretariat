<?php
namespace App\Enum;

enum DevisStatus: string
{
    case ACCEPTE = 'accepté';
    case REFUSE = 'refusé';
    case EXIPRE = 'expiré';
    case EN_ATTENTE = 'en attente de paiement';

    /**
     * Méthode statique pour obtenir toutes les valeurs possibles de l'énumération.
     */
    public static function getValues(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}