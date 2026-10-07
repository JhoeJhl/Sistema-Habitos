<x-app-layout>
    <!-- Welcome & Metrics Overview -->
    <div class="mb-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
                <p class="text-xs uppercase tracking-wider text-indigo-400 font-semibold mb-1">
                    {{ now()->translatedFormat('l, d \d\e F') }}
                </p>
                <h1 class="text-3xl font-bold tracking-tight text-white">
                    Panel de Hábitos & Momentum
                </h1>
                <p class="text-sm text-slate-400 mt-1">
                    Construye identidad mediante la inercia diaria. La constancia mínima siempre supera al abandono total.
                </p>
            </div>

            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-xs text-slate-300">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Usuario: <strong class="text-white">{{ $user->name }}</strong></span>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Global Momentum Card -->
            <div class="bg-gradient-to-br from-slate-900 to-[#121929] border border-slate-800/90 rounded-2xl p-5 shadow-lg relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-500/10 rounded-full blur-xl group-hover:bg-indigo-500/20 transition-all"></div>
                <div class="flex items-center justify-between text-slate-400 text-xs font-medium mb-3">
                    <span>MOMENTUM PROMEDIO</span>
                    <span class="text-indigo-400">⚡ Inercia</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-white tracking-tight">{{ $averageMomentum }}%</span>
                    <span class="text-xs font-semibold {{ $averageMomentum >= 50 ? 'text-emerald-400' : 'text-amber-400' }}">
                        {{ $averageMomentum >= 50 ? 'En aceleración' : 'Construyendo base' }}
                    </span>
                </div>
                <div class="w-full bg-slate-800/80 rounded-full h-2 mt-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-violet-500 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, $averageMomentum) }}%"></div>
                </div>
            </div>

            <!-- Today's Execution -->
            <div class="bg-gradient-to-br from-slate-900 to-[#121929] border border-slate-800/90 rounded-2xl p-5 shadow-lg relative overflow-hidden group">
                <div class="flex items-center justify-between text-slate-400 text-xs font-medium mb-3">
                    <span>CUMPLIMIENTO DE HOY</span>
                    <span class="text-emerald-400">🎯 Diario</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-white tracking-tight">{{ $completedTodayCount }} / {{ $totalHabits }}</span>
                    <span class="text-xs text-slate-400">hábitos</span>
                </div>
                <div class="w-full bg-slate-800/80 rounded-full h-2 mt-3 overflow-hidden">
                    @php $completionRate = $totalHabits > 0 ? ($completedTodayCount / $totalHabits) * 100 : 0; @endphp
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-2 rounded-full transition-all duration-500" style="width: {{ $completionRate }}%"></div>
                </div>
            </div>

            <!-- Top Streak -->
            <div class="bg-gradient-to-br from-slate-900 to-[#121929] border border-slate-800/90 rounded-2xl p-5 shadow-lg relative overflow-hidden group">
                <div class="flex items-center justify-between text-slate-400 text-xs font-medium mb-3">
                    <span>RACHA MÁS ALTA</span>
                    <span class="text-orange-400">🔥 Foco</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-white tracking-tight">{{ $topStreak }}</span>
                    <span class="text-xs text-orange-400 font-semibold">días seguidos</span>
                </div>
                <p class="text-xs text-slate-500 mt-3">Racha activa más larga entre tus hábitos</p>
            </div>

            <!-- Identity Archetypes -->
            <div class="bg-gradient-to-br from-slate-900 to-[#121929] border border-slate-800/90 rounded-2xl p-5 shadow-lg relative overflow-hidden group">
                <div class="flex items-center justify-between text-slate-400 text-xs font-medium mb-3">
                    <span>ARQUETIPOS DE IDENTIDAD</span>
                    <span class="text-purple-400">🧬 Perfil</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-white tracking-tight">{{ $archetypes->count() }}</span>
                    <span class="text-xs text-purple-400 font-semibold">pilares activos</span>
                </div>
                <p class="text-xs text-slate-500 mt-3">Áreas que estás desarrollando</p>
            </div>
        </div>
    </div>

    <!-- Archetype Filter Pills -->
    <div class="flex items-center gap-2 pb-6 overflow-x-auto no-scrollbar border-b border-slate-800/60 mb-8">
        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mr-2">Filtrar:</span>
        <a href="{{ route('habits.index') }}" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-medium transition-all duration-150 {{ ! $selectedArchetype ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'bg-slate-900 border border-slate-800 text-slate-400 hover:text-white hover:border-slate-700' }}">
            Todos ({{ $totalHabits }})
        </a>
        @foreach ($archetypes as $arch)
            <a href="{{ route('habits.index', ['archetype' => $arch]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-medium transition-all duration-150 {{ $selectedArchetype === $arch ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'bg-slate-900 border border-slate-800 text-slate-400 hover:text-white hover:border-slate-700' }}">
                {{ $arch }}
            </a>
        @endforeach
    </div>

    <!-- Habits Grid -->
    @if ($habits->isEmpty())
        <div class="bg-[#0f1422] border border-slate-800 rounded-3xl p-12 text-center max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-3xl flex items-center justify-center mx-auto mb-4">
                ✨
            </div>
            <h3 class="text-lg font-bold text-white mb-2">No hay hábitos en esta categoría</h3>
            <p class="text-sm text-slate-400 mb-6">Comienza registrando tu primer hábito con los 3 niveles anti-fricción para empezar a construir inercia.</p>
            <button type="button" onclick="document.getElementById('modal-create-habit').classList.remove('hidden')" 
                    class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold shadow-lg shadow-indigo-600/25 transition-all">
                Crear Mi Primer Hábito
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($habits as $habit)
                @php
                    $todayLog = $habit->todayLog;
                    $isCompletedToday = $todayLog && $todayLog->effort_level !== \App\Enums\EffortLevel::MISSED;
                    $isMissedToday = $todayLog && $todayLog->effort_level === \App\Enums\EffortLevel::MISSED;
                @endphp

                <div class="bg-gradient-to-b from-[#111625] to-[#0c101d] border border-slate-800/90 hover:border-slate-700/80 rounded-2xl p-6 transition-all duration-200 shadow-xl flex flex-col justify-between">
                    <div>
                        <!-- Header Card -->
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-slate-800/80 border border-slate-700/50 flex items-center justify-center text-2xl shadow-inner">
                                    {{ $habit->icon ?? '🎯' }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white tracking-tight">{{ $habit->name }}</h3>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        @if ($habit->archetype)
                                            <span class="text-xs px-2 py-0.5 rounded-md bg-purple-500/15 border border-purple-500/30 text-purple-300 font-medium">
                                                {{ $habit->archetype }}
                                            </span>
                                        @endif
                                        @if ($habit->target_time)
                                            <span class="text-xs text-slate-500 flex items-center gap-1">
                                                ⏰ {{ \Carbon\Carbon::parse($habit->target_time)->format('H:i') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Streak Badge -->
                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-orange-500/10 border border-orange-500/25 text-orange-400 font-semibold text-xs">
                                <span>🔥</span>
                                <span>{{ $habit->current_streak }}d</span>
                            </div>
                        </div>

                        @if ($habit->description)
                            <p class="text-xs text-slate-400 mb-5 leading-relaxed">
                                {{ $habit->description }}
                            </p>
                        @endif

                        <!-- Momentum Bar -->
                        <div class="bg-slate-900/80 rounded-xl p-3.5 border border-slate-800/70 mb-5">
                            <div class="flex items-center justify-between text-xs mb-2">
                                <span class="font-medium text-slate-300">{{ $habit->momentumStatus() }}</span>
                                <span class="font-extrabold text-white text-sm">{{ $habit->momentum }}%</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-2.5 overflow-hidden">
                                @php
                                    $barGradient = match(true) {
                                        $habit->momentum >= 75 => 'from-indigo-500 via-purple-500 to-pink-500',
                                        $habit->momentum >= 40 => 'from-emerald-500 to-teal-400',
                                        default => 'from-amber-500 to-orange-500'
                                    };
                                @endphp
                                <div class="bg-gradient-to-r {{ $barGradient }} h-2.5 rounded-full transition-all duration-500" style="width: {{ min(100, $habit->momentum) }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Effort Level Buttons (The Anti-Friction Engine) -->
                    <div class="pt-2 border-t border-slate-800/60">
                        @if ($todayLog)
                            <!-- Already Logged Today -->
                            <div class="bg-slate-900/90 border {{ $isMissedToday ? 'border-rose-500/30 bg-rose-950/20' : 'border-indigo-500/30 bg-indigo-950/20' }} rounded-xl p-3.5 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-lg">{{ $isMissedToday ? '❌' : '✅' }}</span>
                                    <div>
                                        <div class="text-xs font-bold text-white flex items-center gap-2">
                                            <span>Completado hoy: {{ $todayLog->effort_level->label() }}</span>
                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-800 text-slate-300 font-normal">
                                                {{ $todayLog->momentum_delta > 0 ? '+' : '' }}{{ $todayLog->momentum_delta }}%
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-slate-400">
                                            @if($todayLog->perceived_energy) Energía: {{ $todayLog->perceived_energy->label() }} {{ $todayLog->perceived_energy->icon() }} • @endif
                                            Registrado a las {{ $todayLog->updated_at->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('habits.uncheck', [$habit, now()->toDateString()]) }}" onsubmit="return confirm('¿Deshacer el registro de hoy?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-slate-400 hover:text-rose-400 transition-colors px-2 py-1 rounded hover:bg-slate-800 cursor-pointer" title="Deshacer registro">
                                        Deshacer
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- Pending Today: 3 Effort Level Options -->
                            <div>
                                <p class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold mb-2.5 flex items-center justify-between">
                                    <span>¿Cómo lo cumpliste hoy?</span>
                                    <span class="text-[10px] text-slate-500 font-normal">Elige según tu día</span>
                                </p>

                                <div class="grid grid-cols-3 gap-2">
                                    <!-- Micro Option -->
                                    <form method="POST" action="{{ route('habits.checkin', $habit) }}">
                                        @csrf
                                        <input type="hidden" name="effort_level" value="micro">
                                        <button type="submit" 
                                                class="w-full text-left p-2.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/25 hover:border-amber-500/50 transition-all group cursor-pointer flex flex-col justify-between h-full">
                                            <div>
                                                <div class="flex items-center justify-between text-amber-300 font-bold text-xs mb-1">
                                                    <span>Micro</span>
                                                    <span class="text-[10px] font-mono opacity-80">+5%</span>
                                                </div>
                                                <p class="text-[10px] text-slate-300 line-clamp-2 leading-tight">
                                                    {{ $habit->micro_description ?? 'Versión mínima de rescate.' }}
                                                </p>
                                            </div>
                                            <span class="text-[9px] text-amber-400 mt-2 font-medium">Salva inercia →</span>
                                        </button>
                                    </form>

                                    <!-- Base Option -->
                                    <form method="POST" action="{{ route('habits.checkin', $habit) }}">
                                        @csrf
                                        <input type="hidden" name="effort_level" value="base">
                                        <button type="submit" 
                                                class="w-full text-left p-2.5 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/25 hover:border-emerald-500/50 transition-all group cursor-pointer flex flex-col justify-between h-full">
                                            <div>
                                                <div class="flex items-center justify-between text-emerald-300 font-bold text-xs mb-1">
                                                    <span>Base</span>
                                                    <span class="text-[10px] font-mono opacity-80">+12%</span>
                                                </div>
                                                <p class="text-[10px] text-slate-300 line-clamp-2 leading-tight">
                                                    {{ $habit->base_description ?? 'Versión estándar planificada.' }}
                                                </p>
                                            </div>
                                            <span class="text-[9px] text-emerald-400 mt-2 font-medium">Estándar →</span>
                                        </button>
                                    </form>

                                    <!-- Epic Option -->
                                    <form method="POST" action="{{ route('habits.checkin', $habit) }}">
                                        @csrf
                                        <input type="hidden" name="effort_level" value="epic">
                                        <button type="submit" 
                                                class="w-full text-left p-2.5 rounded-xl bg-purple-500/10 hover:bg-purple-500/20 border border-purple-500/25 hover:border-purple-500/50 transition-all group cursor-pointer flex flex-col justify-between h-full">
                                            <div>
                                                <div class="flex items-center justify-between text-purple-300 font-bold text-xs mb-1">
                                                    <span>Épico</span>
                                                    <span class="text-[10px] font-mono opacity-80">+20%</span>
                                                </div>
                                                <p class="text-[10px] text-slate-300 line-clamp-2 leading-tight">
                                                    {{ $habit->epic_description ?? 'Sesión superior con bonus.' }}
                                                </p>
                                            </div>
                                            <span class="text-[9px] text-purple-400 mt-2 font-medium">Impulso plus →</span>
                                        </button>
                                    </form>
                                </div>

                                <!-- Missed Check-in / Honest Failure -->
                                <div class="flex justify-end mt-2">
                                    <form method="POST" action="{{ route('habits.checkin', $habit) }}" onsubmit="return confirm('¿Registrar este hábito como no cumplido hoy?')">
                                        @csrf
                                        <input type="hidden" name="effort_level" value="missed">
                                        <button type="submit" class="text-[11px] text-slate-500 hover:text-rose-400 transition-colors flex items-center gap-1 cursor-pointer">
                                            <span>Registrar día sin hacer (-15% fricción)</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Modal: Crear Nuevo Hábito -->
    <div id="modal-create-habit" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm hidden">
        <div class="bg-[#111726] border border-slate-800 rounded-3xl max-w-xl w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800 mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-xl">
                        ✨
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Diseñar Nuevo Hábito</h3>
                        <p class="text-xs text-slate-400">Configura tus 3 versiones anti-fricción</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('modal-create-habit').classList.add('hidden')" class="text-slate-400 hover:text-white text-2xl cursor-pointer">&times;</button>
            </div>

            <form method="POST" action="{{ route('habits.store') }}" class="space-y-4">
                @csrf

                <!-- Nombre -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Nombre del Hábito *</label>
                    <input type="text" name="name" required placeholder="Ej. Entrenamiento de Fuerza, Lectura Técnica..." 
                           class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Arquetipo -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Arquetipo</label>
                        <input type="text" name="archetype" placeholder="Atleta, Erudito..." 
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                    </div>
                    <!-- Icono Emoji -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Emoji / Icono</label>
                        <input type="text" name="icon" placeholder="⚡, 📚, 🏋️..." maxlength="10"
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                    </div>
                    <!-- Hora sugerida -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Hora sugerida</label>
                        <input type="time" name="target_time" 
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <!-- Las 3 Versiones Anti-Fricción -->
                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4 space-y-3.5 mt-2">
                    <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider flex items-center gap-1.5">
                        <span>🛡️</span> Las 3 Versiones Anti-Fricción
                    </p>

                    <!-- Micro -->
                    <div>
                        <label class="block text-xs font-semibold text-amber-300 mb-1">
                            1. Versión Micro (Días difíciles / 2-3 min) *
                        </label>
                        <input type="text" name="micro_description" required placeholder="Ej. 5 flexiones o 1 serie corta en casa"
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-amber-500">
                        <p class="text-[10px] text-slate-500 mt-0.5">La versión ridículamente fácil que harás incluso con 0 energía para salvar la inercia.</p>
                    </div>

                    <!-- Base -->
                    <div>
                        <label class="block text-xs font-semibold text-emerald-300 mb-1">
                            2. Versión Base (Estándar planificado) *
                        </label>
                        <input type="text" name="base_description" required placeholder="Ej. 40 minutos en el gimnasio o rutina completa"
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-emerald-500">
                        <p class="text-[10px] text-slate-500 mt-0.5">El objetivo normal que esperas cumplir en un día estándar.</p>
                    </div>

                    <!-- Épico -->
                    <div>
                        <label class="block text-xs font-semibold text-purple-300 mb-1">
                            3. Versión Épica (Días de máxima energía / Bono) *
                        </label>
                        <input type="text" name="epic_description" required placeholder="Ej. 1h 15m con levantamiento pesado y cardio"
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-purple-500">
                        <p class="text-[10px] text-slate-500 mt-0.5">Cuando tienes tiempo y alta energía, ganas impulso extra de momentum.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Descripción o Intención (Opcional)</label>
                    <textarea name="description" rows="2" placeholder="¿Por qué es importante este hábito para tu identidad?"
                              class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
                    <button type="button" onclick="document.getElementById('modal-create-habit').classList.add('hidden')" 
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:text-white transition-colors cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30 transition-all cursor-pointer">
                        Guardar Hábito
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
