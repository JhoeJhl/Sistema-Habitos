<?php

namespace Database\Factories;

use App\Enums\EffortLevel;
use App\Enums\EnergyLevel;
use App\Models\Habit;
use App\Models\HabitLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HabitLog>
 */
class HabitLogFactory extends Factory
{
    protected $model = HabitLog::class;

    public function definition(): array
    {
        $effort = fake()->randomElement(EffortLevel::cases());
        $delta = $effort->defaultMomentumDelta();

        return [
            'habit_id' => Habit::factory(),
            'user_id' => fn (array $attributes) => Habit::find($attributes['habit_id'])?->user_id ?? User::factory(),
            'logged_date' => now()->toDateString(),
            'effort_level' => $effort,
            'momentum_delta' => $delta,
            'momentum_snapshot' => max(0, min(100, 50.0 + $delta)),
            'perceived_energy' => fake()->randomElement(EnergyLevel::cases()),
            'notes' => fake()->optional()->sentence(),
            'completed_at' => now(),
        ];
    }
}
