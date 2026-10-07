<?php

use App\Http\Controllers\HabitController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HabitController::class, 'index'])->name('habits.index');
Route::post('/habits', [HabitController::class, 'store'])->name('habits.store');
Route::post('/habits/{habit}/checkin', [HabitController::class, 'checkin'])->name('habits.checkin');
Route::delete('/habits/{habit}/logs/{date}', [HabitController::class, 'uncheck'])->name('habits.uncheck');
Route::delete('/habits/{habit}', [HabitController::class, 'destroy'])->name('habits.destroy');
