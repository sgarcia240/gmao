<div class="p-4 sm:p-6 lg:p-8 bg-slate-50 min-h-screen space-y-4 sm:space-y-6">

    <!-- Mensaje Flash -->
    @if (session()->has('message'))
        <div class="p-3 sm:p-4 text-xs sm:text-sm text-emerald-800 bg-emerald-100 rounded-xl border border-emerald-200">
            {{ session('message') }}
        </div>
    @endif

    <!-- Cabecera de la vista -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">Gestión de Ascensores</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5 sm:mt-1">Parque de ascensores, inspecciones ITE y estado operacional.</p>
        </div>
        
        <!-- Botón que activa la creación -->
        <button wire:click="openCreateModal" 
                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-xl shadow-sm transition active:scale-95">
            <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Ascensor
        </button>
    </div>

    <!-- Buscador y Filtros -->
    <div class="bg-white rounded-2xl shadow-sm p-3.5 sm:p-4 border border-slate-100 flex flex-col md:flex-row gap-3 sm:gap-4 justify-between">
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por código RAE, marca o modelo..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 transition">
        </div>
        <div class="w-full md:w-64">
            <select wire:model.live="statusFilter" class="w-full py-2 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 text-slate-600 transition">
                <option value="">Todos los Estados</option>
                <option value="active">Activo</option>
                <option value="maintenance">Mantenimiento</option>
                <option value="stopped">Parado</option>
                <option value="out_of_service">Fuera de Servicio</option>
            </select>
        </div>
    </div>

    <!-- Tabla Responsiva -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-slate-50 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="p-4">CÓDIGO RAE</th>
                        <th class="p-4">MARCA / MODELO</th>
                        <th class="p-4">PARADAS / CARGA</th>
                        <th class="p-4">ESTADO</th>
                        <th class="p-4">PRÓXIMA ITE</th>
                        <th class="p-4 text-right">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm whitespace-nowrap">
                    @forelse($elevators as $elevator)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4">
                                <a href="{{ route('elevators.show', $elevator->id) }}" 
                                class="font-bold text-blue-600 hover:text-blue-800 hover:underline font-mono inline-flex items-center gap-1">
                                    <span>{{ $elevator->rae_code }}</span>
                                </a>
                            </td>
                            <td class="p-4">
                                <div class="font-medium text-slate-800">{{ $elevator->brand }}</div>
                                <div class="text-xs text-slate-400">{{ $elevator->model ?? '-' }}</div>
                            </td>
                            <td class="p-4 text-slate-600">
                                {{ $elevator->stops_count }} paradas · {{ $elevator->max_load_kg }} kg
                            </td>
                            <td class="p-4">
                                @if($elevator->status === 'active')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Activo</span>
                                @elseif($elevator->status === 'maintenance')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">Mantenimiento</span>
                                @elseif($elevator->status === 'stopped')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-700">Parado</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-600">Fuera de Servicio</span>
                                @endif
                            </td>
                            <td class="p-4 text-slate-600">
                              {{ $elevator->next_ite_date ? \Carbon\Carbon::parse($elevator->next_ite_date)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="p-4 text-right">
                                <button wire:click="openEditModal({{ $elevator->id }})" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 hover:text-blue-800 border border-blue-200/70 rounded-lg shadow-sm transition-all duration-150 active:scale-95">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span>Editar</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-400">No hay ascensores registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-slate-100">
            {{ $elevators->links() }}
        </div>
    </div>

    <!-- MODAL DE ALTA / EDICIÓN -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4">
            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100 my-8">
                
                <!-- Encabezado del Modal -->
                <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-slate-100">
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">{{ $isEditing ? 'Editar Ascensor' : 'Nuevo Ascensor' }}</h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit.prevent="save">
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1">Ubicación / Edificio</label>
                        <select wire:model="location_id" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="">-- Selecciona una ubicación --</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name ?? $location->address ?? 'Ubicación #'.$location->id }}</option>
                            @endforeach
                        </select>
                        @error('location_id') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="p-5 sm:p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Código RAE *</label>
                            <input type="text" wire:model="rae_code" placeholder="Ej: RAE-2026-003" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                            @error('rae_code') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Marca *</label>
                                <input type="text" wire:model="brand" placeholder="Ej: Otis" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                @error('brand') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Modelo</label>
                                <input type="text" wire:model="model" placeholder="Ej: Gen2 Switch" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Paradas *</label>
                                <input type="number" wire:model="stops_count" min="1" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                @error('stops_count') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Carga (kg) *</label>
                                <input type="number" wire:model="max_load_kg" step="10" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                @error('max_load_kg') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Estado Operativo</label>
                                <select wire:model="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="active">Activo</option>
                                    <option value="maintenance">Mantenimiento</option>
                                    <option value="stopped">Parado</option>
                                    <option value="out_of_service">Fuera de Servicio</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Próxima ITE</label>
                                <input type="date" wire:model="next_ite_date" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Botones del Modal -->
                    <div class="flex items-center justify-end gap-3 px-5 sm:px-6 py-4 bg-slate-50 border-t border-slate-100">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition active:scale-95">
                            {{ $isEditing ? 'Guardar Cambios' : 'Crear Ascensor' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif

</div>