<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('habits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('archetype')->nullable(); // Ej. Atleta, Erudito, Creador, Monje
            $table->string('icon', 50)->nullable(); // Emoji o identificador de icono
            $table->string('color', 20)->nullable(); // Color hex o clase CSS

            // Definición de las 3 versiones del hábito (Anti-fricción)
            $table->string('micro_description')->nullable(); // Versión mínima de rescate (evita romper racha)
            $table->string('base_description')->nullable();  // Versión estándar planificada
            $table->string('epic_description')->nullable();  // Versión sobresaliente con bono

            $table->json('frequency_days')->nullable(); // Días de la semana activos [1..7]
            $table->time('target_time')->nullable(); // Hora ideal programada

            // Métricas de Momentum e Inercia
            $table->decimal('momentum', 5, 2)->default(0.00); // Inercia actual (0.00 a 100.00%)
            $table->unsignedInteger('current_streak')->default(0);
            $table->unsignedInteger('longest_streak')->default(0);
            $table->date('last_logged_at')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
            $table->index(['user_id', 'archetype']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habits');
    }
};
