<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\HabitFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'user_id',
    'name',
    'description',
    'archetype',
    'icon',
    'color',
    'micro_description',
    'base_description',
    'epic_description',
    'frequency_days',
    'target_time',
    'momentum',
    'current_streak',
    'longest_streak',
    'last_logged_at',
    'is_active',
])]
class Habit extends Model
{
    /** @use HasFactory<HabitFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'frequency_days' => 'array',
            'momentum' => 'float',
            'current_streak' => 'integer',
            'longest_streak' => 'integer',
            'last_logged_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(HabitLog::class)->orderByDesc('logged_date');
    }

    /**
     * Registro correspondiente al día de hoy.
     */
    public function todayLog(): HasOne
    {
        return $this->hasOne(HabitLog::class)->whereDate('logged_date', now()->toDateString());
    }

    /**
     * Comprueba si el hábito ya fue registrado en una fecha específica.
     */
    public function isLoggedOn(CarbonInterface|string $date): bool
    {
        $dateString = is_string($date) ? $date : $date->toDateString();

        return $this->logs()->whereDate('logged_date', $dateString)->exists();
    }

    /**
     * Estado textual según el porcentaje de Momentum.
     */
    public function momentumStatus(): string
    {
        return match (true) {
            $this->momentum >= 85.0 => '🔥 En llamas (Imparable)',
            $this->momentum >= 60.0 => '⚡ Gran inercia',
            $this->momentum >= 30.0 => '🌱 En ritmo',
            $this->momentum > 0.0 => '⚠️ Fricción baja',
            default => '🧊 Inercia en cero',
        };
    }
}
