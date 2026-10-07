<?php

namespace App\Http\Controllers;

use App\Enums\EffortLevel;
use App\Enums\EnergyLevel;
use App\Models\Habit;
use App\Models\User;
use App\Services\HabitMomentumService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HabitController extends Controller
{
    /**
     * Obtiene el usuario activo o crea el de desarrollo por defecto.
     */
    protected function resolveUser(): User
    {
        return auth()->user() ?? User::first() ?? User::factory()->create([
            'name' => 'Jhoe Developer',
            'email' => 'jhoe@example.com',
        ]);
    }

    /**
     * Muestra el panel interactivo de hábitos y momentum.
     */
    public function index(Request $request): View
    {
        $user = $this->resolveUser();
        $selectedArchetype = $request->query('archetype');

        $habitsQuery = $user->habits()
            ->where('is_active', true)
            ->with(['todayLog'])
            ->orderByDesc('momentum');

        if ($selectedArchetype) {
            $habitsQuery->where('archetype', $selectedArchetype);
        }

        $habits = $habitsQuery->get();

        // Arquetipos disponibles para filtro
        $archetypes = $user->habits()
            ->whereNotNull('archetype')
            ->distinct()
            ->pluck('archetype');

        // Métricas globales de la sesión
        $totalHabits = $habits->count();
        $averageMomentum = $totalHabits > 0 ? round($habits->avg('momentum'), 1) : 0;
        $completedTodayCount = $habits->filter(fn ($h) => $h->todayLog !== null && $h->todayLog->effort_level !== EffortLevel::MISSED)->count();
        $topStreak = $habits->max('current_streak') ?? 0;

        return view('habits.index', [
            'user' => $user,
            'habits' => $habits,
            'archetypes' => $archetypes,
            'selectedArchetype' => $selectedArchetype,
            'totalHabits' => $totalHabits,
            'averageMomentum' => $averageMomentum,
            'completedTodayCount' => $completedTodayCount,
            'topStreak' => $topStreak,
            'effortLevels' => EffortLevel::cases(),
            'energyLevels' => EnergyLevel::cases(),
        ]);
    }

    /**
     * Registra un nuevo hábito en el sistema con sus 3 niveles anti-fricción.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $this->resolveUser();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'archetype' => ['nullable', 'string', 'max:50'],
            'icon' => ['nullable', 'string', 'max:20'],
            'color' => ['nullable', 'string', 'max:30'],
            'micro_description' => ['required', 'string', 'max:255'],
            'base_description' => ['required', 'string', 'max:255'],
            'epic_description' => ['required', 'string', 'max:255'],
            'target_time' => ['nullable', 'date_format:H:i'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $user->habits()->create(array_merge($validated, [
            'momentum' => 0.00,
            'current_streak' => 0,
            'longest_streak' => 0,
            'is_active' => true,
        ]));

        return redirect()->route('habits.index')->with('success', '¡Nuevo hábito creado exitosamente!');
    }

    /**
     * Registra el esfuerzo diario (Micro, Base, Épico o Missed) usando el motor de Momentum.
     */
    public function checkin(Request $request, Habit $habit, HabitMomentumService $service): RedirectResponse
    {
        $validated = $request->validate([
            'effort_level' => ['required', 'string', 'in:micro,base,epic,missed'],
            'perceived_energy' => ['nullable', 'string', 'in:low,medium,high'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'date' => ['nullable', 'date'],
        ]);

        $effortLevel = EffortLevel::from($validated['effort_level']);
        $energyLevel = ! empty($validated['perceived_energy']) ? EnergyLevel::from($validated['perceived_energy']) : null;

        $service->logHabit(
            $habit,
            $effortLevel,
            $energyLevel,
            $validated['notes'] ?? null,
            $validated['date'] ?? null
        );

        $msg = match ($effortLevel) {
            EffortLevel::MICRO => '¡Inercia salvada! La versión micro mantiene tu hábito vivo.',
            EffortLevel::BASE => '¡Objetivo base completado! Gran paso en tu momentum.',
            EffortLevel::EPIC => '⚡ ¡Rendimiento épico! Ganaste un impulso superior de momentum.',
            EffortLevel::MISSED => 'Día registrado como fallo. Tu inercia amortigua el impacto.',
        };

        return redirect()->route('habits.index')->with('success', $msg);
    }

    /**
     * Deshace el registro de una fecha para un hábito.
     */
    public function uncheck(Habit $habit, string $date, HabitMomentumService $service): RedirectResponse
    {
        $service->removeLog($habit, $date);

        return redirect()->route('habits.index')->with('success', 'Registro revertido correctamente.');
    }

    /**
     * Elimina un hábito.
     */
    public function destroy(Habit $habit): RedirectResponse
    {
        $habit->delete();

        return redirect()->route('habits.index')->with('success', 'Hábito eliminado correctamente.');
    }
}
