<?php

namespace Database\Factories;

use App\Models\Habit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Habit>
 */
class HabitFactory extends Factory
{
    protected $model = Habit::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->randomElement([
                'Lectura técnica',
                'Entrenamiento de fuerza',
                'Meditación matutina',
                'Caminar 10.000 pasos',
                'Escribir diario de reflexión',
                'Práctica de código',
            ]),
            'description' => fake()->sentence(),
            'archetype' => fake()->randomElement(['Atleta', 'Erudito', 'Monje', 'Creador']),
            'icon' => fake()->randomElement(['⚡', '📚', '🏋️', '🧘', '💻', '🎯']),
            'color' => fake()->hexColor(),
            'micro_description' => 'Versión mínima: 5 minutos o 1 repetición rápida para mantener la inercia.',
            'base_description' => 'Versión base: 30 minutos cumpliendo el objetivo principal.',
            'epic_description' => 'Versión épica: 1 hora con máxima concentración e intensidad.',
            'frequency_days' => [1, 2, 3, 4, 5, 6, 7],
            'target_time' => '08:00:00',
            'momentum' => 0.00,
            'current_streak' => 0,
            'longest_streak' => 0,
            'last_logged_at' => null,
            'is_active' => true,
        ];
    }
}
