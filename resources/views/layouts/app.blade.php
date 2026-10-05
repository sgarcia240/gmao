<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
     
    <title>{{ config('app.name', 'GMAO Elevadores') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts y Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-100" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex flex-col md:flex-row">
        
        <!-- SIDEBAR LATERAL (Metronic Style) -->
        <aside class="w-full md:w-64 bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col justify-between transition-all duration-300">
            <div>
                <!-- Brand / Logo -->
                <div class="h-16 flex items-center px-6 bg-slate-950 border-b border-slate-800/60 justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-600 rounded-lg text-white font-black text-xl leading-none">
                            E
                        </div>
                        <span class="font-bold text-white tracking-wide text-lg">GMAO Elevate</span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1">
                    <div class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Menú Principal
                    </div>

                    <!-- Dashboard / Analíticas -->
                    <a href="{{ route('analytics') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('analytics') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>

                    <!-- Órdenes de Trabajo -->
                   <a href="{{ route('work-orders.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('work-orders.index') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Órdenes de Trabajo
                    </a>
                    <!-- Facturas (Solo Admin y Supervisor) -->
                    @if(auth()->check() && auth()->user()->hasRole('admin', 'supervisor'))
                    <a href="{{ route('invoices.index') }}" 
                         class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('invoices.index') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Facturas</span>
                        </a>
                    @endif
                    <!-- Parque de Ascensores -->
                    <a href="{{ route('elevators.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('elevators.index') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Ascensores
                    </a>
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

           
        </aside>

        <!-- Área de contenido principal -->
<div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
    
    <!-- Topbar / Cabecera superior si la usas -->
    <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6">
        <div class="flex items-center gap-4">
            <span class="text-xs font-semibold px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full">Sistema Online</span>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative ms-3" x-data="{ open: false }">
    <!-- Botón interactivo con Avatar, Nombre y Flecha -->
    <button @click="open = !open" 
            title="Haz clic para ver opciones o cerrar sesión"
            class="flex items-center gap-2.5 px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:bg-gray-100 rounded-xl border border-gray-200 transition duration-150 ease-in-out cursor-pointer shadow-sm">
        
        <!-- Avatar con la inicial del usuario -->
        <div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
        </div>

        <!-- Nombre del usuario -->
        <span class="font-semibold text-gray-800">{{ Auth::user()->name ?? 'Usuario' }}</span>

        <!-- Flecha desplegable (gira 180° cuando el menú se abre) -->
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
         class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 z-50">
        
        <!-- Cabecera del menú -->
        <div class="px-4 py-2 border-b border-gray-100">
            <p class="text-xs text-gray-400 font-medium">Sesión iniciada como</p>
            <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->email ?? 'usuario@empresa.com' }}</p>
        </div>

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
    

    <!-- CONTENIDO DINÁMICO (Aquí se inyectará tu vista) -->
    <main class="p-6">
        {{ $slot ?? '' }}
       
    </main>

</div>
    </div>

</body>
</html>