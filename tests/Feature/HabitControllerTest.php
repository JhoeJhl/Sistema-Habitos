<?php

use App\Enums\EffortLevel;
use App\Models\Habit;
use App\Models\User;

test('habits dashboard renders successfully with seeded data', function () {
    $user = User::factory()->create();
    $habit = Habit::factory()->create([
        'user_id' => $user->id,
        'name' => 'Lectura Técnica',
        'archetype' => 'Erudito',
    ]);

    $response = $this->actingAs($user)->get(route('habits.index'));

    $response->assertOk();
    $response->assertSee('Panel de Hábitos', false);
    $response->assertSee('Lectura Técnica');
    $response->assertSee('Erudito');
});

test('can check in a habit with micro effort level from web', function () {
    $user = User::factory()->create();
    $habit = Habit::factory()->create([
        'user_id' => $user->id,
        'momentum' => 10.0,
        'current_streak' => 1,
    ]);

    $response = $this->actingAs($user)->post(route('habits.checkin', $habit), [
        'effort_level' => 'micro',
    ]);

    $response->assertRedirect(route('habits.index'));
    $response->assertSessionHas('success');

    $habit->refresh();
    expect($habit->momentum)->toBe(15.0)
        ->and($habit->todayLog)->not->toBeNull()
        ->and($habit->todayLog->effort_level)->toBe(EffortLevel::MICRO);
});

test('can create a new habit from web', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('habits.store'), [
        'name' => 'Práctica de Piano',
        'archetype' => 'Artista',
        'icon' => '🎹',
        'micro_description' => 'Tocar 1 escala de 2 minutos',
        'base_description' => '20 minutos de repertorio',
        'epic_description' => '45 minutos con metrónomo y grabación',
    ]);

    $response->assertRedirect(route('habits.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('habits', [
        'name' => 'Práctica de Piano',
        'archetype' => 'Artista',
    ]);
});
