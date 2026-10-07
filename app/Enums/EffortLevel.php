<?php

namespace App\Enums;

enum EffortLevel: string
{
    case MICRO = 'micro';
    case BASE = 'base';
    case EPIC = 'epic';
    case MISSED = 'missed';

    /**
     * Nombre legible del nivel de esfuerzo.
     */
    public function label(): string
    {
        return match ($this) {
            self::MICRO => 'Micro (Versión mínima)',
            self::BASE => 'Base (Estándar)',
            self::EPIC => 'Épico (Sobresaliente)',
            self::MISSED => 'No cumplido (Fallo)',
        };
    }

    /**
     * Descripción orientativa de la intención.
     */
    public function description(): string
    {
        return match ($this) {
            self::MICRO => 'Esfuerzo mínimo de rescate para mantener la inercia viva y no perder el hábito.',
            self::BASE => 'Cumplimiento estándar del hábito según lo planificado.',
            self::EPIC => 'Sesión excepcional con esfuerzo o volumen superior al promedio.',
            self::MISSED => 'Día sin ejecución del hábito.',
        };
    }

    /**
     * Impacto por defecto en la inercia / momentum (de -100 a +100).
     */
    public function defaultMomentumDelta(): float
    {
        return match ($this) {
            self::MICRO => 5.0,    // Mantiene la llama encendida y suma levemente
            self::BASE => 12.0,    // Impulso constante y saludable
            self::EPIC => 20.0,    // Bono por sobrecumplimiento
            self::MISSED => -15.0, // Fricción/decaimiento, sin destruir todo a 0
        };
    }

    /**
     * Color distintivo para la interfaz.
     */
    public function color(): string
    {
        return match ($this) {
            self::MICRO => '#F59E0B',  // Ámbar / amarillo
            self::BASE => '#10B981',   // Esmeralda / verde
            self::EPIC => '#8B5CF6',   // Violeta / morado
            self::MISSED => '#EF4444', // Rojo
        };
    }

    /**
     * Indica si cuenta como un día completado para la racha de días activos.
     */
    public function countsForStreak(): bool
    {
        return $this !== self::MISSED;
    }
}
