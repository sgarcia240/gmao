<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <title>{{ config('app.name', 'GMAO Reformas') }}</title>
    <!-- Favicon de la pestaña del navegador -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=1">
        <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}?v=1">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts, Tailwind y Livewire -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-100" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex flex-col md:flex-row relative">
        
        <!-- BACKDROP OSCURO PARA MÓVIL -->
        <div x-show="sidebarOpen" 
             x-cloak 
             @click="sidebarOpen = false" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/80 z-40 md:hidden"></div>

        <!-- SIDEBAR LATERAL RESPONSIVO -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col justify-between transition-transform duration-300 ease-in-out md:static md:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div>
                <!-- Brand / Logo -->
                <div class="h-16 flex items-center px-6 bg-slate-950 border-b border-slate-800/60 justify-between">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 min-w-0">
                        <img src="{{ asset('images/logo.png') }}"
                            alt="{{ config('app.name') }}"
                            class="h-9 w-auto max-w-[140px] object-contain rounded-lg shrink-0">
                        <span class="font-bold text-white tracking-wide text-lg truncate">
                            {{ config('app.name', 'Tu Nombre de App') }}
                        </span>
                    </a>
                    <!-- Botón para cerrar en móvil -->
                    <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white p-1 rounded-lg focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links (Adaptado a uso personal de Reformas) -->
                <nav class="p-4 space-y-1">
                    <div class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Gestión Personal
                    </div>

                    <!-- Tablero Principal: Reformas, Toma de Datos y Acopio -->
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Mis Obras y Reformas
                    </a>

                    <!-- Histórico Órdenes de Trabajo -->
                    @if(Route::has('work-orders.index'))
                    <a href="{{ route('work-orders.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('work-orders.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Listado de OTs
                    </a>
                    @endif

                    <!-- Parque de Ascensores / Comunidades -->
                    @if(Route::has('elevators.index'))
                    <a href="{{ route('elevators.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('elevators.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Comunidades / Ascensores
                    </a>
                    @endif
                    <!-- Analíticas (si deseas conservar la vista anterior) -->
                    @if(Route::has('analytics'))
                    <a href="{{ route('analytics') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('analytics') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        Estadísticas
                    </a>
                    @endif
                   <!-- Sección Configuración (Solo Admin y Supervisor) -->
                    @if(auth()->check() && auth()->user()->hasRole('admin', 'supervisor'))
                    <div class="px-3 pt-4 pb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Configuración
                    </div>

                    <!-- Usuarios / Personal -->
                    <a href="{{ route('users.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('users.index') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Técnicos & Usuarios
                    </a>
                    @endif
                    
                </nav>
            </div>

            <!-- Pie del Sidebar con Estado de Copia de Seguridad -->
            <div class="p-4 border-t border-slate-800/60">
                <div class="px-3 py-2 bg-slate-800/50 rounded-lg text-xs text-slate-400">
                    <span class="block font-semibold text-slate-300">Backup Automático</span>
                    <span>Neon DB + Fotos (03:00h)</span>
                </div>
            </div>
        </aside>

        <!-- Área de contenido principal -->
        <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-y-auto">
            
            <!-- Topbar / Cabecera superior sticky -->
            <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <!-- Botón Hamburguesa Móvil -->
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="md:hidden p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none"
                            aria-label="Abrir menú">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <span class="text-xs font-semibold px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full whitespace-nowrap">Sistema Online</span>
                </div>

                <div class="flex items-center gap-3">
                    <div class="relative ms-3" x-data="{ open: false }">
                        <!-- Botón interactivo con Avatar, Nombre y Flecha -->
                        <button @click="open = !open" 
                                title="Haz clic para ver opciones o cerrar sesión"
                                class="flex items-center gap-2.5 px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:bg-gray-100 rounded-xl border border-gray-200 transition duration-150 ease-in-out cursor-pointer shadow-sm">
                            
                            <!-- Avatar con la inicial del usuario -->
                            <div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            </div>

                            <!-- Nombre del usuario -->
                            <span class="font-semibold text-gray-800 hidden sm:inline">{{ Auth::user()->name ?? 'Usuario' }}</span>

                            <!-- Flecha desplegable -->
                            <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" 
                                 :class="{ 'rotate-180': open }" 
                                 fill="none" 
                                 stroke="currentColor" 
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Menú Desplegable animado -->
                        <div x-show="open" 
                             @click.outside="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 z-50">
                            
                            @auth
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-xs text-gray-400 font-medium">Sesión iniciada como</p>
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->email }}</p>
                                </div>
                            @endauth

                            <!-- Descarga manual de respaldo ZIP si la necesitas en cualquier momento -->
                            @if(Route::has('backup.download'))
                                <a href="{{ route('backup.download') }}" class="w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 font-medium transition flex items-center gap-2.5 border-b border-gray-100">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Descargar Backup ZIP
                                </a>
                            @endif

                            <!-- Opción de Cierre de Sesión -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-semibold transition flex items-center gap-2.5 cursor-pointer">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <main class="p-4 sm:p-6 lg:p-8">
                {{ $slot ?? '' }}
            </main>

        </div>
    </div>

    @livewireScripts
</body>
</html>