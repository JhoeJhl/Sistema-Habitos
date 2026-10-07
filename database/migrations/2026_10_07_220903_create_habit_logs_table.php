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
        Schema::create('habit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('logged_date');

            // Nivel de cumplimiento registrado y variación de inercia
            $table->string('effort_level', 20); // 'micro', 'base', 'epic', 'missed'
            $table->decimal('momentum_delta', 5, 2); // Cuánto sumó o restó (+12.00, -15.00)
            $table->decimal('momentum_snapshot', 5, 2); // Valor resultante de momentum en este momento

            // Datos cualitativos y contexto
            $table->string('perceived_energy', 20)->nullable(); // 'low', 'medium', 'high'
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            // Un solo registro por hábito y por fecha
            $table->unique(['habit_id', 'logged_date']);
            $table->index(['user_id', 'logged_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_logs');
    }
};
