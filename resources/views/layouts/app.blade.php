<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#f3f6fb]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/heic2any@0.0.4/dist/heic2any.min.js"></script>
    <title>{{ config('app.name', 'TrackHelm GMAO') }}</title>

    <!-- Favicon de la pestaña del navegador -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=1">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}?v=1">

    <!-- Tipografías modernas: Plus Jakarta Sans + JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|jetbrains-mono:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts, Tailwind y Livewire -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono-tech { font-family: 'JetBrains Mono', monospace; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-[#f3f6fb] selection:bg-blue-600 selection:text-white" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex flex-col md:flex-row relative">

        <!-- BACKDROP OSCURO CON DESENFOQUE PARA MÓVIL -->
        <div x-show="sidebarOpen"
             x-cloak
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-950/75 backdrop-blur-xs z-40 md:hidden"></div>

        <!-- SIDEBAR LATERAL DE ALTO IMPACTO -->
        <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-[#070b14] text-slate-300 flex-shrink-0 flex flex-col justify-between border-r border-white/[0.07] shadow-2xl transition-transform duration-300 ease-in-out md:static md:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <!-- Decoración luminosa superior sutil -->
            <div class="pointer-events-none absolute top-0 left-0 right-0 h-40 bg-gradient-to-b from-blue-600/10 to-transparent"></div>

            <div class="relative z-10">
                <!-- Brand / Logo -->
                <div class="h-20 flex items-center px-6 border-b border-white/[0.07] justify-between">
                    <a href="{{ url('/') }}" class="flex items-center gap-3.5 min-w-0 group">
                        <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-white/[0.04] border border-white/10 p-1.5 shadow-inner group-hover:border-cyan-400/40 transition">
                            <img src="{{ asset('images/logo.png') }}"
                                 alt="{{ config('app.name') }}"
                                 class="h-full w-full object-contain rounded-lg">
                        </div>
                        <div class="min-w-0">
                            <span class="block font-extrabold text-white tracking-tight text-base truncate">
                                {{ config('app.name', 'TrackHelm') }}
                            </span>
                            <span class="block font-mono-tech text-[10px] uppercase tracking-[0.2em] text-cyan-400/90">
                                GMAO Enterprise
                            </span>
                        </div>
                    </a>

                    <!-- Botón cerrar en móvil -->
                    <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white p-1.5 rounded-xl hover:bg-white/5 focus:outline-none transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navegación Principal -->
                <nav class="p-4 space-y-1.5 mt-2">
                    <div class="px-3.5 pb-2 text-[10px] font-mono-tech font-semibold text-slate-500 uppercase tracking-[0.22em]">
                        Operaciones & Planta
                    </div>

                    <!-- Tablero Principal: Reformas, Toma de Datos y Acopio -->
                    <a href="{{ route('dashboard') }}"
                       class="group relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-600/25 via-blue-600/10 to-transparent text-white border border-cyan-500/30 shadow-[0_0_20px_rgba(6,182,212,0.12)]' : 'text-slate-400 hover:bg-white/[0.04] hover:text-white' }}">
                        @if(request()->routeIs('dashboard'))
                            <span class="absolute left-0 top-2.5 bottom-2.5 w-1 rounded-r-full bg-cyan-400 shadow-[0_0_10px_#22d3ee]"></span>
                        @endif
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('dashboard') ? 'bg-cyan-400/15 text-cyan-400' : 'bg-white/[0.03] text-slate-400 group-hover:text-white group-hover:bg-white/[0.07]' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <span class="truncate">Mis Obras y Reformas</span>
                    </a>

                    <!-- Histórico Órdenes de Trabajo -->
                    @if(Route::has('work-orders.index'))
                    <a href="{{ route('work-orders.index') }}"
                       class="group relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('work-orders.*') ? 'bg-gradient-to-r from-blue-600/25 via-blue-600/10 to-transparent text-white border border-cyan-500/30 shadow-[0_0_20px_rgba(6,182,212,0.12)]' : 'text-slate-400 hover:bg-white/[0.04] hover:text-white' }}">
                        @if(request()->routeIs('work-orders.*'))
                            <span class="absolute left-0 top-2.5 bottom-2.5 w-1 rounded-r-full bg-cyan-400 shadow-[0_0_10px_#22d3ee]"></span>
                        @endif
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('work-orders.*') ? 'bg-cyan-400/15 text-cyan-400' : 'bg-white/[0.03] text-slate-400 group-hover:text-white group-hover:bg-white/[0.07]' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="truncate">Listado de OTs</span>
                    </a>
                    @endif

                    <!-- Parque de Ascensores / Comunidades -->
                    @if(Route::has('elevators.index'))
                    <a href="{{ route('elevators.index') }}"
                       class="group relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('elevators.*') ? 'bg-gradient-to-r from-blue-600/25 via-blue-600/10 to-transparent text-white border border-cyan-500/30 shadow-[0_0_20px_rgba(6,182,212,0.12)]' : 'text-slate-400 hover:bg-white/[0.04] hover:text-white' }}">
                        @if(request()->routeIs('elevators.*'))
                            <span class="absolute left-0 top-2.5 bottom-2.5 w-1 rounded-r-full bg-cyan-400 shadow-[0_0_10px_#22d3ee]"></span>
                        @endif
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('elevators.*') ? 'bg-cyan-400/15 text-cyan-400' : 'bg-white/[0.03] text-slate-400 group-hover:text-white group-hover:bg-white/[0.07]' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <span class="truncate">Comunidades / Ascensores</span>
                    </a>
                    @endif

                    <!-- Analíticas -->
                    @if(Route::has('analytics'))
                    <a href="{{ route('analytics') }}"
                       class="group relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('analytics') ? 'bg-gradient-to-r from-blue-600/25 via-blue-600/10 to-transparent text-white border border-cyan-500/30 shadow-[0_0_20px_rgba(6,182,212,0.12)]' : 'text-slate-400 hover:bg-white/[0.04] hover:text-white' }}">
                        @if(request()->routeIs('analytics'))
                            <span class="absolute left-0 top-2.5 bottom-2.5 w-1 rounded-r-full bg-cyan-400 shadow-[0_0_10px_#22d3ee]"></span>
                        @endif
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('analytics') ? 'bg-cyan-400/15 text-cyan-400' : 'bg-white/[0.03] text-slate-400 group-hover:text-white group-hover:bg-white/[0.07]' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                            </svg>
                        </div>
                        <span class="truncate">Estadísticas</span>
                    </a>
                    @endif

                    <!-- Sección Configuración (Solo Admin y Supervisor) -->
                    @if(auth()->check() && auth()->user()->hasRole('admin', 'supervisor'))
                        <div class="px-3.5 pt-6 pb-2 text-[10px] font-mono-tech font-semibold text-slate-500 uppercase tracking-[0.22em]">
                            Administración
                        </div>

                        <a href="{{ route('users.index') }}"
                           class="group relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('users.index') ? 'bg-gradient-to-r from-blue-600/25 via-blue-600/10 to-transparent text-white border border-cyan-500/30 shadow-[0_0_20px_rgba(6,182,212,0.12)]' : 'text-slate-400 hover:bg-white/[0.04] hover:text-white' }}">
                            @if(request()->routeIs('users.index'))
                                <span class="absolute left-0 top-2.5 bottom-2.5 w-1 rounded-r-full bg-cyan-400 shadow-[0_0_10px_#22d3ee]"></span>
                            @endif
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('users.index') ? 'bg-cyan-400/15 text-cyan-400' : 'bg-white/[0.03] text-slate-400 group-hover:text-white group-hover:bg-white/[0.07]' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <span class="truncate">Técnicos & Usuarios</span>
                        </a>
                    @endif
                </nav>
            </div>

            <!-- Pie del Sidebar: Estado de Seguridad / Backup -->
            <div class="relative z-10 p-4 border-t border-white/[0.07]">
                <div class="p-3.5 rounded-2xl bg-gradient-to-br from-white/[0.05] to-white/[0.01] border border-white/[0.08] space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-200 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Backup Automático
                        </span>
                        <span class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_#34d399]"></span>
                    </div>
                    <p class="font-mono-tech text-[11px] text-slate-400">Neon DB + Fotos (03:00h)</p>
                </div>
            </div>
        </aside>

        <!-- ÁREA DE CONTENIDO PRINCIPAL -->
        <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-y-auto">

            <!-- Topbar / Cabecera superior translúcida -->
            <header class="bg-white/80 backdrop-blur-xl border-b border-slate-200/80 h-20 flex items-center justify-between px-4 sm:px-8 sticky top-0 z-30">
                <div class="flex items-center gap-3.5">
                    <!-- Botón Hamburguesa Móvil -->
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="md:hidden p-2.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none transition"
                            aria-label="Abrir menú">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Pill de estado operativo -->
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-700">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="font-mono-tech text-[11px] font-semibold uppercase tracking-wider whitespace-nowrap">Sistema Online</span>
                    </div>
                </div>

                <!-- Perfil de Usuario y Acciones -->
                <div class="flex items-center gap-3">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                title="Haz clic para ver opciones o cerrar sesión"
                                class="flex items-center gap-3 pl-2 pr-3.5 py-1.5 text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-sm transition duration-150 cursor-pointer">
                            
                            <!-- Avatar con degradado corporativo -->
                            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 text-white flex items-center justify-center font-bold text-xs shadow-sm shadow-blue-600/20 shrink-0">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            </div>

                            <div class="hidden sm:block text-left">
                                <span class="block text-xs font-bold text-slate-900 leading-tight">{{ Auth::user()->name ?? 'Usuario' }}</span>
                                <span class="block font-mono-tech text-[10px] text-slate-400 leading-tight">Cuenta Activa</span>
                            </div>

                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                 :class="{ 'rotate-180': open }"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Menú Desplegable -->
                        <div x-show="open"
                             x-cloak
                             @click.outside="open = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="transform opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2.5 w-64 bg-white rounded-2xl shadow-xl shadow-slate-900/10 border border-slate-100 py-2 z-50">
                            
                            @auth
                                <div class="px-4 py-2.5 border-b border-slate-100">
                                    <p class="font-mono-tech text-[10px] uppercase tracking-wider text-slate-400">Sesión iniciada como</p>
                                    <p class="text-sm font-bold text-slate-900 truncate mt-0.5">{{ Auth::user()->email }}</p>
                                </div>
                            @endauth

                            <!-- Editar mi cuenta / Perfil -->
                            @if(Route::has('profile'))
                                <a href="{{ route('profile') }}" class="w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 font-medium transition flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Editar mi cuenta
                                </a>
                            @elseif(Route::has('profile.edit'))
                                <a href="{{ route('profile.edit') }}" class="w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 font-medium transition flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Editar mi cuenta
                                </a>
                            @endif

                            <!-- Descarga manual de respaldo ZIP -->
                            @if(Route::has('backup.download'))
                                <a href="{{ route('backup.download') }}" class="w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 font-medium transition flex items-center gap-2.5 border-b border-slate-100">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Descargar Backup ZIP
                                </a>
                            @endif

                            <!-- Cierre de Sesión -->
                            <form method="POST" action="{{ route('logout') }}" class="pt-1">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 font-semibold transition flex items-center gap-2.5 cursor-pointer">
                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- CONTENIDO DINÁMICO -->
            <main class="p-4 sm:p-6 lg:p-8 flex-1">
                <div class="max-w-7xl mx-auto">
                    {{ $slot ?? '' }}
                </div>
            </main>

        </div>
    </div>

    @livewireScripts
</body>
</html>