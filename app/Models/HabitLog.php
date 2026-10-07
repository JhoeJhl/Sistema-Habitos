<?php

namespace App\Models;

use App\Enums\EffortLevel;
use App\Enums\EnergyLevel;
use Database\Factories\HabitLogFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'habit_id',
    'user_id',
    'logged_date',
    'effort_level',
    'momentum_delta',
    'momentum_snapshot',
    'perceived_energy',
    'notes',
    'completed_at',
])]
class HabitLog extends Model
{
    /** @use HasFactory<HabitLogFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'logged_date' => 'date',
            'effort_level' => EffortLevel::class,
            'momentum_delta' => 'float',
            'momentum_snapshot' => 'float',
            'perceived_energy' => EnergyLevel::class,
            'completed_at' => 'datetime',
        ];
    }

    public function habit(): BelongsTo
    {
        return $this->belongsTo(Habit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
