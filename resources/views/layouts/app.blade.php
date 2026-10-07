<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Sistema de Hábitos con Momentum' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#090d16] text-slate-100 font-sans antialiased min-h-screen selection:bg-indigo-500 selection:text-white flex flex-col">

    <!-- Top Navigation Bar -->
    <header class="border-b border-slate-800/80 bg-[#0c1220]/80 backdrop-blur-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-lg shadow-indigo-500/25">
                    <span class="text-xl">⚡</span>
                </div>
                <div>
                    <span class="text-lg font-bold tracking-tight text-white flex items-center gap-2">
                        Momentum <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-400 font-medium border border-indigo-500/30">Antifrágil</span>
                    </span>
                    <p class="text-xs text-slate-400">Sistema de Hábitos por Inercia & Arquetipos</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="button" onclick="document.getElementById('modal-create-habit').classList.remove('hidden')" 
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-medium text-sm shadow-lg shadow-indigo-600/30 transition-all duration-200 hover:scale-[1.02] cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Nuevo Hábito</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 w-full">
            <div class="bg-emerald-950/60 border border-emerald-500/40 rounded-xl p-4 text-emerald-200 text-sm flex items-center justify-between backdrop-blur-sm shadow-lg shadow-emerald-950/50">
                <div class="flex items-center gap-3">
                    <span class="text-xl">✨</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200 text-lg">&times;</button>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 w-full">
            <div class="bg-rose-950/60 border border-rose-500/40 rounded-xl p-4 text-rose-200 text-sm flex flex-col gap-1 backdrop-blur-sm">
                <div class="font-semibold flex items-center gap-2">
                    <span>⚠️</span> Revisa los errores del formulario:
                </div>
                <ul class="list-disc list-inside text-xs text-rose-300">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-[#070a12] py-6 text-center text-xs text-slate-500">
        <p>Sistema de Hábitos con Momentum • Basado en psicología de hábitos atómicos y reducción de fricción</p>
    </footer>

</body>
</html>
