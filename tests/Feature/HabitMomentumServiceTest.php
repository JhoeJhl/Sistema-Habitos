<?php

use App\Enums\EffortLevel;
use App\Enums\EnergyLevel;
use App\Models\Habit;
use App\Models\User;
use App\Services\HabitMomentumService;

test('logging micro effort increments momentum gently and initiates streak', function () {
    $user = User::factory()->create();
    $habit = Habit::factory()->create([
        'user_id' => $user->id,
        'momentum' => 0.0,
        'current_streak' => 0,
        'last_logged_at' => null,
    ]);

    $service = new HabitMomentumService;
    $log = $service->logHabit($habit, EffortLevel::MICRO, EnergyLevel::LOW, 'Día pesado, pero salvé con versión micro');

    $habit->refresh();

    expect($habit->momentum)->toBe(5.0)
        ->and($habit->current_streak)->toBe(1)
        ->and($habit->longest_streak)->toBe(1)
        ->and($log->effort_level)->toBe(EffortLevel::MICRO)
        ->and($log->momentum_delta)->toBe(5.0)
        ->and($log->momentum_snapshot)->toBe(5.0);
});

test('logging missed effort reduces momentum without dropping to zero', function () {
    $user = User::factory()->create();
    $habit = Habit::factory()->create([
        'user_id' => $user->id,
        'momentum' => 50.0,
        'current_streak' => 5,
        'last_logged_at' => now()->subDay()->toDateString(),
    ]);

    $service = new HabitMomentumService;
    $service->logHabit($habit, EffortLevel::MISSED);

    $habit->refresh();

    // 50.0 - 15.0 = 35.0 (La inercia amortigua el fallo)
    expect($habit->momentum)->toBe(35.0)
        ->and($habit->current_streak)->toBe(0);
});
