<?php

namespace App\Services;

use App\Enums\EffortLevel;
use App\Enums\EnergyLevel;
use App\Models\Habit;
use App\Models\HabitLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HabitMomentumService
{
    /**
     * Registra o actualiza el check-in de un hábito calculando inercia y racha.
     */
    public function logHabit(
        Habit $habit,
        EffortLevel $effortLevel,
        ?EnergyLevel $energyLevel = null,
        ?string $notes = null,
        ?string $date = null
    ): HabitLog {
        $loggedDate = $date ? Carbon::parse($date)->toDateString() : now()->toDateString();

        return DB::transaction(function () use ($habit, $effortLevel, $energyLevel, $notes, $loggedDate) {
            $delta = $effortLevel->defaultMomentumDelta();

            // Consultar si ya existía un log en esa fecha para ajustar correctamente el delta
            $existingLog = $habit->logs()->whereDate('logged_date', $loggedDate)->first();
            $baseMomentum = (float) $habit->momentum;

            if ($existingLog) {
                // Revertir el delta anterior si se está actualizando el log del mismo día
                $baseMomentum = max(0.0, min(100.0, $baseMomentum - (float) $existingLog->momentum_delta));
            }

            $newMomentum = max(0.0, min(100.0, $baseMomentum + $delta));

            // Calcular racha
            $newStreak = $habit->current_streak;
            if ($effortLevel->countsForStreak()) {
                if ($habit->last_logged_at && ! $existingLog) {
                    $lastDate = Carbon::parse($habit->last_logged_at);
                    $currentDate = Carbon::parse($loggedDate);
                    $diffInDays = $lastDate->diffInDays($currentDate, false);

                    if ($diffInDays === 1) {
                        $newStreak += 1;
                    } elseif ($diffInDays > 1) {
                        // Nueva racha, pero el momentum previo amortigua el progreso
                        $newStreak = 1;
                    }
                } elseif (! $habit->last_logged_at) {
                    $newStreak = 1;
                }
            } else {
                $newStreak = 0;
            }

            $longestStreak = max((int) $habit->longest_streak, $newStreak);

            // Crear o actualizar el registro
            $log = HabitLog::updateOrCreate(
                [
                    'habit_id' => $habit->id,
                    'logged_date' => $loggedDate,
                ],
                [
                    'user_id' => $habit->user_id,
                    'effort_level' => $effortLevel,
                    'momentum_delta' => $delta,
                    'momentum_snapshot' => $newMomentum,
                    'perceived_energy' => $energyLevel,
                    'notes' => $notes,
                    'completed_at' => now(),
                ]
            );

            // Actualizar estado del hábito
            $habit->update([
                'momentum' => $newMomentum,
                'current_streak' => $newStreak,
                'longest_streak' => $longestStreak,
                'last_logged_at' => $loggedDate,
            ]);

            return $log;
        });
    }

    /**
     * Elimina el registro de una fecha y revierte el cálculo.
     */
    public function removeLog(Habit $habit, string $date): void
    {
        DB::transaction(function () use ($habit, $date) {
            $log = $habit->logs()->whereDate('logged_date', $date)->first();
            if (! $log) {
                return;
            }

            $newMomentum = max(0.0, min(100.0, (float) $habit->momentum - (float) $log->momentum_delta));
            $log->delete();

            $latestLog = $habit->logs()->latest('logged_date')->first();

            $habit->update([
                'momentum' => $newMomentum,
                'current_streak' => max(0, $habit->current_streak - 1),
                'last_logged_at' => $latestLog?->logged_date,
            ]);
        });
    }
}
