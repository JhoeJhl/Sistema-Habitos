<?php

use App\Enums\EffortLevel;
use App\Enums\EnergyLevel;
use App\Models\Habit;
use App\Models\HabitLog;
use App\Models\User;

test('a user can have habits and habit logs', function () {
    $user = User::factory()->create();

    $habit = Habit::factory()->create([
        'user_id' => $user->id,
        'name' => 'Lectura de código',
        'micro_description' => '1 commit revisado',
        'base_description' => '20 min leyendo código ajeno',
        'epic_description' => '45 min con notas de arquitectura',
    ]);

    $log = HabitLog::factory()->create([
        'habit_id' => $habit->id,
        'user_id' => $user->id,
        'effort_level' => EffortLevel::BASE,
        'perceived_energy' => EnergyLevel::HIGH,
        'momentum_delta' => EffortLevel::BASE->defaultMomentumDelta(),
        'momentum_snapshot' => 12.0,
    ]);

    expect($user->habits)->toHaveCount(1)
        ->and($user->habits->first()->name)->toBe('Lectura de código')
        ->and($habit->logs)->toHaveCount(1)
        ->and($habit->logs->first()->effort_level)->toBe(EffortLevel::BASE)
        ->and($habit->logs->first()->perceived_energy)->toBe(EnergyLevel::HIGH)
        ->and($habit->isLoggedOn(now()))->toBeTrue();
});

test('effort level returns correct default momentum deltas and streak rules', function () {
    expect(EffortLevel::MICRO->defaultMomentumDelta())->toBe(5.0)
        ->and(EffortLevel::BASE->defaultMomentumDelta())->toBe(12.0)
        ->and(EffortLevel::EPIC->defaultMomentumDelta())->toBe(20.0)
        ->and(EffortLevel::MISSED->defaultMomentumDelta())->toBe(-15.0)
        ->and(EffortLevel::MICRO->countsForStreak())->toBeTrue()
        ->and(EffortLevel::MISSED->countsForStreak())->toBeFalse();
});
