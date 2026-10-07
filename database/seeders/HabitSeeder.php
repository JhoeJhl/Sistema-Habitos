<?php

namespace Database\Seeders;

use App\Enums\EffortLevel;
use App\Enums\EnergyLevel;
use App\Models\Habit;
use App\Models\HabitLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class HabitSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Jhoe Developer',
            'email' => 'jhoe@example.com',
        ]);

        $habitsData = [
            [
                'name' => 'Entrenamiento de Fuerza',
                'description' => 'Construir masa muscular y mantener salud postural.',
                'archetype' => 'Atleta',
                'icon' => '🏋️',
                'color' => '#EF4444',
                'micro_description' => '2 series de 10 flexiones o sentadillas en casa (Anti-fricción: 3 min).',
                'base_description' => 'Rutina en gimnasio o calistenia de 45 minutos.',
                'epic_description' => 'Sesión completa de 1h 15m con levantamiento pesado y estiramiento.',
                'target_time' => '07:30:00',
                'momentum' => 45.0,
                'current_streak' => 3,
                'longest_streak' => 7,
                'last_logged_at' => now()->toDateString(),
            ],
            [
                'name' => 'Lectura Técnica & Arquitectura',
                'description' => 'Lectura de libros de ingeniería de software, arquitectura o diseño.',
                'archetype' => 'Erudito',
                'icon' => '📚',
                'color' => '#3B82F6',
                'micro_description' => 'Leer 2 páginas o 1 artículo corto guardado.',
                'base_description' => 'Lectura activa de 20 minutos tomando notas en borrador.',
                'epic_description' => 'Lectura profunda de 45 minutos y sintetizar un concepto clave.',
                'target_time' => '21:00:00',
                'momentum' => 65.0,
                'current_streak' => 5,
                'longest_streak' => 12,
                'last_logged_at' => now()->toDateString(),
            ],
            [
                'name' => 'Deep Work en Proyecto Personal',
                'description' => 'Desarrollar funcionalidades sin distracciones ni redes sociales.',
                'archetype' => 'Creador',
                'icon' => '💻',
                'color' => '#8B5CF6',
                'micro_description' => 'Abrir el editor y resolver 1 commit o refactor de 10 minutos.',
                'base_description' => '1 bloque de 45 minutos en flujo de desarrollo.',
                'epic_description' => '2 bloques de 45 minutos (90 min) completando un feature completo.',
                'target_time' => '18:00:00',
                'momentum' => 30.0,
                'current_streak' => 2,
                'longest_streak' => 4,
                'last_logged_at' => now()->subDay()->toDateString(),
            ],
            [
                'name' => 'Desconexión Digital & Sueño',
                'description' => 'Dejar pantallas y preparar el descanso antes de las 23:00.',
                'archetype' => 'Monje',
                'icon' => '🌙',
                'color' => '#10B981',
                'micro_description' => 'Modo avión en el teléfono 15 minutos antes de dormir.',
                'base_description' => 'Sin pantallas desde las 22:30; luz tenue y lectura relajante.',
                'epic_description' => 'Sin pantallas desde las 22:00, estiramientos y meditación previa.',
                'target_time' => '22:30:00',
                'momentum' => 20.0,
                'current_streak' => 1,
                'longest_streak' => 3,
                'last_logged_at' => null,
            ],
        ];

        foreach ($habitsData as $data) {
            $habit = Habit::create(array_merge($data, [
                'user_id' => $user->id,
                'frequency_days' => [1, 2, 3, 4, 5, 6, 7],
                'is_active' => true,
            ]));

            // Generar un log reciente para demostrar historial
            if ($habit->last_logged_at) {
                HabitLog::create([
                    'habit_id' => $habit->id,
                    'user_id' => $user->id,
                    'logged_date' => $habit->last_logged_at,
                    'effort_level' => EffortLevel::BASE,
                    'momentum_delta' => EffortLevel::BASE->defaultMomentumDelta(),
                    'momentum_snapshot' => $habit->momentum,
                    'perceived_energy' => EnergyLevel::MEDIUM,
                    'notes' => 'Registro inicial de prueba para verificar relaciones y modelo.',
                    'completed_at' => now(),
                ]);
            }
        }
    }
}
