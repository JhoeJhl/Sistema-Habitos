<?php

namespace App\Enums;

enum EnergyLevel: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';

    public function label(): string
    {
        return match ($this) {
            self::LOW => 'Baja',
            self::MEDIUM => 'Media',
            self::HIGH => 'Alta',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::LOW => '🪫',
            self::MEDIUM => '⚡',
            self::HIGH => '🔋',
        };
    }
}
