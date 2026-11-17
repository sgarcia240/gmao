<div class="max-w-7xl mx-auto space-y-6 pb-20">

    <!-- CABECERA EJECUTIVA -->
    <div class="relative overflow-hidden rounded-3xl bg-[#070b14] p-6 sm:p-8 text-white shadow-xl border border-white/10">
        <div class="pointer-events-none absolute -top-24 -right-24 w-80 h-80 rounded-full bg-cyan-500/15 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 left-1/4 w-80 h-80 rounded-full bg-blue-600/15 blur-3xl"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-cyan-500/10 border border-cyan-400/20 text-cyan-300 font-mono-tech text-[11px] uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                    Parque de Elevación · ITE
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Gestión de Ascensores
                </h1>
                <p class="text-xs sm:text-sm text-slate-400">
                    Inventario técnico de aparatos elevadores, control de inspecciones reglamentarias ITE y estado operativo.
                </p>
            </div>

            <button wire:click="openCreateModal"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-5 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-[0.99] text-white font-bold text-sm rounded-2xl shadow-lg shadow-cyan-500/20 transition cursor-pointer shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span>Nuevo Ascensor</span>
            </button>
        </div>
    </div>

    <!-- MENSAJE FLASH -->
    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50/90 backdrop-blur-xs border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-semibold flex items-center gap-3 shadow-xs">
            <span class="w-7 h-7 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </span>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <!-- BARRA DE BÚSQUEDA Y FILTROS -->
    <div class="bg-white rounded-3xl shadow-sm p-4 sm:p-5 border border-slate-200/80 flex flex-col md:flex-row gap-3.5 justify-between">
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Buscar por código RAE, marca o modelo..."
                   class="w-full pl-11 pr-4 py-3 bg-slate-50/80 border border-slate-200 rounded-2xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
        </div>

        <div class="w-full md:w-64">
            <select wire:model.live="statusFilter"
                    class="w-full py-3 px-4 bg-slate-50/80 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                <option value="">Todos los Estados</option>
                <option value="active">Activo</option>
                <option value="maintenance">Mantenimiento</option>
                <option value="stopped">Parado</option>
                <option value="out_of_service">Fuera de Servicio</option>
            </select>
        </div>
    </div>

    <!-- TABLA DE PARQUE DE ASCENSORES -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[760px]">
                <thead>
                    <tr class="bg-slate-50/90 font-mono-tech text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200/80">
                        <th class="py-4 px-5">Código RAE</th>
                        <th class="py-4 px-5">Marca / Modelo</th>
                        <th class="py-4 px-5">Paradas / Carga</th>
                        <th class="py-4 px-5">Estado</th>
                        <th class="py-4 px-5">Próxima ITE</th>
                        <th class="py-4 px-5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm whitespace-nowrap">
                    @forelse($elevators as $elevator)
                        <tr class="group hover:bg-blue-50/30 transition-colors">
                            <td class="py-4 px-5">
                                <a href="{{ route('elevators.show', $elevator->id) }}"
                                   class="inline-flex items-center gap-2 font-mono-tech text-xs font-bold px-3 py-1.5 rounded-xl bg-slate-900 text-cyan-400 hover:bg-blue-600 hover:text-white shadow-xs transition">
                                    <span>{{ $elevator->rae_code }}</span>
                                    <svg class="w-3.5 h-3.5 opacity-75" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                    </svg>
                                </a>
                            </td>

                            <td class="py-4 px-5">
                                <div class="font-bold text-slate-900">{{ $elevator->brand }}</div>
                                <div class="text-xs text-slate-400">{{ $elevator->model ?? 'Modelo estándar' }}</div>
                            </td>

                            <td class="py-4 px-5">
                                <div class="inline-flex items-center gap-2 font-mono-tech text-xs text-slate-700 bg-slate-100/80 px-2.5 py-1 rounded-lg">
                                    <span class="font-bold">{{ $elevator->stops_count }}</span> paradas
                                    <span class="text-slate-300">|</span>
                                    <span class="font-bold">{{ $elevator->max_load_kg }}</span> kg
                                </div>
                            </td>

                            <td class="py-4 px-5">
                                @if($elevator->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Activo
                                    </span>
                                @elseif($elevator->status === 'maintenance')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-amber-500/10 text-amber-700 border border-amber-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Mantenimiento
                                    </span>
                                @elseif($elevator->status === 'stopped')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-rose-500/10 text-rose-700 border border-rose-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                        Parado
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Fuera de Servicio
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-5">
                                @if($elevator->next_ite_date)
                                    @php
                                        $iteDate = \Carbon\Carbon::parse($elevator->next_ite_date);
                                        $isExpired = $iteDate->isPast();
                                        $isSoon = !$isExpired && $iteDate->diffInDays(now()) <= 30;
                                    @endphp
                                    <span class="font-mono-tech text-xs font-semibold px-2.5 py-1 rounded-lg {{ $isExpired ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($isSoon ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'text-slate-600 bg-slate-50') }}">
                                        {{ $iteDate->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="font-mono-tech text-xs text-slate-400">—</span>
                                @endif
                            </td>

                            <td class="py-4 px-5 text-right">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <button wire:click="openEditModal({{ $elevator->id }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-700 bg-white hover:bg-blue-50 hover:text-blue-700 border border-slate-200 hover:border-blue-200 rounded-xl shadow-2xs transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span>Editar</span>
                                    </button>

                                    <button wire:click="confirmDelete({{ $elevator->id }})"
                                            title="Eliminar Ascensor"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-rose-600 bg-rose-50/80 hover:bg-rose-100 border border-rose-200/80 rounded-xl transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>Eliminar</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-6 text-center">
                                <p class="text-sm font-semibold text-slate-500">No hay ascensores registrados con los filtros actuales.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 bg-slate-50/60 border-t border-slate-100">
            {{ $elevators->links() }}
        </div>
    </div>

    <!-- MODAL DE ALTA / EDICIÓN -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/75 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200/80 my-8">

                <!-- Encabezado del Modal -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/60">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm shadow-blue-600/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-extrabold text-slate-900">
                                {{ $isEditing ? 'Editar Ascensor' : 'Nuevo Ascensor' }}
                            </h3>
                            <p class="text-xs text-slate-400">Ficha técnica reglamentaria e identificación RAE</p>
                        </div>
                    </div>

                    <button wire:click="closeModal" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit.prevent="save">
                    <div class="p-6 space-y-5">
                        <!-- Fila 1: Ubicación y Código RAE -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                    Ubicación / Edificio *
                                </label>
                                <select wire:model="location_id"
                                        class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                    <option value="">-- Selecciona --</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name ?? $location->address ?? 'Ubicación #'.$location->id }}</option>
                                    @endforeach
                                </select>
                                @error('location_id') <span class="text-xs font-semibold text-rose-500 block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                    Código RAE *
                                </label>
                                <input type="text" wire:model="rae_code" placeholder="Ej: RAE-2026-003"
                                       class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 font-mono-tech rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                @error('rae_code') <span class="text-xs font-semibold text-rose-500 block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Fila 2: Marca y Modelo -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Marca *</label>
                                <input type="text" wire:model="brand" placeholder="Ej: Otis"
                                       class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                @error('brand') <span class="text-xs font-semibold text-rose-500 block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Modelo</label>
                                <input type="text" wire:model="model" placeholder="Ej: Gen2 Switch"
                                       class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                @error('model') <span class="text-xs font-semibold text-rose-500 block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Fila 3: Paradas y Carga -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Paradas *</label>
                                <input type="number" wire:model="stops_count" min="1"
                                       class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm font-mono-tech focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                @error('stops_count') <span class="text-xs font-semibold text-rose-500 block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Carga (kg) *</label>
                                <input type="number" wire:model="max_load_kg" step="10"
                                       class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm font-mono-tech focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                @error('max_load_kg') <span class="text-xs font-semibold text-rose-500 block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Fila 4: Estado y Fecha ITE -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Estado Operativo</label>
                                <select wire:model="status"
                                        class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                    <option value="active">Activo</option>
                                    <option value="maintenance">Mantenimiento</option>
                                    <option value="stopped">Parado</option>
                                    <option value="out_of_service">Fuera de Servicio</option>
                                </select>
                                @error('status') <span class="text-xs font-semibold text-rose-500 block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Próxima ITE</label>
                                <input type="date" wire:model="next_ite_date"
                                       class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm font-mono-tech focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                @error('next_ite_date') <span class="text-xs font-semibold text-rose-500 block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Botones del Modal -->
                    <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50/80 border-t border-slate-100">
                        <button type="button" wire:click="closeModal"
                                class="px-4 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 rounded-xl transition">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-600/20 transition active:scale-95 cursor-pointer">
                            {{ $isEditing ? 'Guardar Cambios' : 'Crear Ascensor' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif

    <!-- MODAL DE CONFIRMACIÓN DE BORRADO EN CASCADA -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/75 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-200/80 p-6 sm:p-7">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-rose-50 text-rose-600 border border-rose-200/80 rounded-2xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">
                            Eliminar ascensor <span class="font-mono-tech text-rose-600">{{ $elevatorToDeleteRae }}</span>
                        </h3>
                        <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                            Esta acción es irreversible y eliminará de forma permanente el ascensor junto con <strong class="text-slate-900">todo lo relacionado con él</strong>:
                        </p>
                        <ul class="mt-3 text-xs text-slate-700 list-disc list-inside space-y-1.5 bg-rose-50/80 border border-rose-200/70 rounded-2xl p-3.5">
                            <li><strong>{{ $relatedOrdersCount }}</strong> {{ $relatedOrdersCount === 1 ? 'orden de trabajo asociada' : 'órdenes de trabajo asociadas' }}</li>
                            <li>Ficha de toma de datos técnica (medidas y maniobra)</li>
                            <li>Fotos, presupuestos, firmas y materiales de obra</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button"
                            wire:click="cancelDelete"
                            class="px-4 py-2.5 text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                        Cancelar
                    </button>
                    <button type="button"
                            wire:click="deleteElevator"
                            wire:loading.attr="disabled"
                            class="px-4 py-2.5 text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md shadow-rose-600/20 transition active:scale-95 cursor-pointer">
                        Sí, eliminar todo
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>