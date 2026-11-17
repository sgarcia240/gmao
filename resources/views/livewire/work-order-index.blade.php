<div class="max-w-7xl mx-auto space-y-6 pb-20">

    <!-- CABECERA EJECUTIVA -->
    <div class="relative overflow-hidden rounded-3xl bg-[#070b14] p-6 sm:p-8 text-white shadow-xl border border-white/10">
        <div class="pointer-events-none absolute -top-24 -right-24 w-80 h-80 rounded-full bg-cyan-500/15 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 left-1/4 w-80 h-80 rounded-full bg-blue-600/15 blur-3xl"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-cyan-500/10 border border-cyan-400/20 text-cyan-300 font-mono-tech text-[11px] uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                    Operaciones de Campo · Partes Técnicos
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Órdenes de Trabajo
                </h1>
                <p class="text-xs sm:text-sm text-slate-400">
                    Histórico y control de incidencias, mantenimientos preventivos, firmas digitales y partes oficiales en PDF.
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-5 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-[0.99] text-white font-bold text-sm rounded-2xl shadow-lg shadow-cyan-500/20 transition shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span>Nueva Toma de Datos</span>
            </a>
        </div>
    </div>

    <!-- ALERTAS FLASH -->
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

    <!-- BUSCADOR Y FILTROS -->
    <div class="bg-white rounded-3xl shadow-sm p-4 sm:p-5 border border-slate-200/80 flex flex-col md:flex-row gap-3.5 justify-between">
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
            </span>
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   aria-label="Buscar por descripción"
                   placeholder="Buscar por descripción, edificio o RAE..."
                   class="w-full pl-11 pr-4 py-3 bg-slate-50/80 border border-slate-200 rounded-2xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
        </div>

        <div class="w-full md:w-64">
            <select wire:model.live="statusFilter"
                    aria-label="Filtrar por estado"
                    class="w-full py-3 px-4 bg-slate-50/80 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                <option value="">Todos los estados</option>
                <option value="pending">Pendiente</option>
                <option value="assigned">Asignada</option>
                <option value="in_progress">En Proceso</option>
                <option value="completed">Completada</option>
                <option value="invoiced">Facturada</option>
            </select>
        </div>
    </div>

    <!-- TABLA DE ÓRDENES DE TRABAJO -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden relative">

        <!-- Spinner Global de Carga -->
        <div wire:loading.flex wire:target="search, statusFilter" class="absolute inset-0 bg-white/70 backdrop-blur-xs z-10 items-center justify-center">
            <div class="flex items-center gap-2.5 px-4 py-2.5 rounded-2xl bg-slate-900 text-white shadow-lg">
                <svg class="animate-spin h-4 w-4 text-cyan-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span class="font-mono-tech text-xs font-bold">Actualizando listado...</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/90 font-mono-tech text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200/80">
                        <th class="py-4 px-5 whitespace-nowrap">ID / Ubicación</th>
                        <th class="py-4 px-5 whitespace-nowrap">Tipo</th>
                        <th class="py-4 px-5 whitespace-nowrap">Prioridad</th>
                        <th class="py-4 px-5 whitespace-nowrap">Descripción</th>
                        <th class="py-4 px-5 text-right whitespace-nowrap">Estado, Acciones & PDF</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($workOrders as $order)
                        <tr wire:key="order-{{ $order->id }}" class="group hover:bg-blue-50/30 transition-colors">
                            <td class="py-4 px-5 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <span class="font-mono-tech text-xs font-bold px-2.5 py-1 rounded-lg bg-slate-900 text-cyan-400 shrink-0">
                                        #{{ $order->id }}
                                    </span>
                                    <div class="min-w-0">
                                        <span class="font-bold text-slate-900 block truncate max-w-[200px]">
                                            {{ $order->elevator->location->building_name ?? 'N/A' }}
                                        </span>
                                        <span class="block font-mono-tech text-[11px] text-slate-400">
                                            RAE: {{ $order->elevator->rae_code ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td class="py-4 px-5 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-blue-500/10 text-blue-700 border border-blue-500/20">
                                    {{ ucfirst($order->type) }}
                                </span>
                            </td>

                            <td class="py-4 px-5 whitespace-nowrap">
                                @if($order->priority === 'person_trapped')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-extrabold rounded-full bg-rose-500/15 text-rose-700 border border-rose-500/30 animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                        Atrapamiento
                                    </span>
                                @elseif($order->priority === 'urgent')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-amber-500/15 text-amber-700 border border-amber-500/25">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Urgente
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Normal
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-5 text-slate-600 min-w-[200px] max-w-[260px]">
                                <span class="block truncate" title="{{ $order->issue_description }}">
                                    {{ Str::limit($order->issue_description, 45) }}
                                </span>
                            </td>

                            <td class="py-4 px-5 whitespace-nowrap text-right">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <!-- Selector de Cambio Rápido de Estado -->
                                    <select wire:change="updateStatus({{ $order->id }}, $event.target.value)"
                                            aria-label="Cambiar estado de orden"
                                            class="text-xs font-bold rounded-xl border-slate-200 bg-slate-50 hover:bg-white text-slate-700 py-2 pl-3 pr-7 shadow-2xs focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition cursor-pointer">
                                        <option value="pending" @selected($order->status === 'pending')>Pendiente</option>
                                        <option value="assigned" @selected($order->status === 'assigned')>Asignada</option>
                                        <option value="in_progress" @selected($order->status === 'in_progress')>En Proceso</option>
                                        <option value="completed" @selected($order->status === 'completed')>Completada</option>
                                        <option value="invoiced" @selected($order->status === 'invoiced')>Facturada</option>
                                    </select>

                                    <!-- Botón Editar con Texto -->
                                    <button wire:click="openEditModal({{ $order->id }})"
                                            title="Editar Orden de Trabajo"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-700 bg-white hover:bg-blue-50 hover:text-blue-700 border border-slate-200 hover:border-blue-200 rounded-xl shadow-2xs transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span>Editar</span>
                                    </button>

                                    <!-- Botón Firma Digital con Texto -->
                                    <button wire:click="$dispatch('openSignatureModal', { orderId: {{ $order->id }} })"
                                            title="{{ $order->client_signature ? 'Firma registrada (Haga clic para cambiar)' : 'Firmar Parte' }}"
                                            class="relative inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold rounded-xl border transition cursor-pointer {{ $order->client_signature ? 'text-emerald-800 bg-emerald-500/15 hover:bg-emerald-500/25 border-emerald-400/40' : 'text-emerald-700 bg-emerald-50/80 hover:bg-emerald-100 border-emerald-200/80' }}">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                        <span>{{ $order->client_signature ? 'Firmado' : 'Firmar' }}</span>
                                        @if($order->client_signature)
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_6px_#10b981]"></span>
                                        @endif
                                    </button>

                                    <!-- Botón Parte de Trabajo PDF con Texto -->
                                    <a href="{{ route('work-orders.pdf.stream', $order->id) }}"
                                       target="_blank"
                                       title="Descargar Parte de Trabajo (PDF)"
                                       class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-700 bg-white hover:bg-slate-900 hover:text-white border border-slate-200 hover:border-slate-900 rounded-xl shadow-2xs transition">
                                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                        </svg>
                                        <span>PDF</span>
                                    </a>

                                    <!-- Botón Eliminar con Texto (Solo Admin / Supervisor) -->
                                    @if(auth()->user()->hasRole(\App\Models\User::ROLE_ADMIN) || auth()->user()->hasRole(\App\Models\User::ROLE_SUPERVISOR))
                                        <button type="button"
                                                wire:click="confirmDelete({{ $order->id }})"
                                                title="Eliminar Orden de Trabajo"
                                                aria-label="Eliminar Orden de Trabajo"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-rose-600 bg-rose-50/80 hover:bg-rose-100 border border-rose-200/80 rounded-xl transition active:scale-95 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span>Eliminar</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center text-sm font-medium text-slate-400">
                                No se encontraron órdenes de trabajo registradas con los filtros actuales.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 bg-slate-50/60 border-t border-slate-100">
            {{ $workOrders->links() }}
        </div>
    </div>

    <!-- MODAL CREACIÓN / EDICIÓN DE ORDEN DE TRABAJO -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/75 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200/80 my-8">

                <!-- Encabezado Modal -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/70">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm shadow-blue-600/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-extrabold text-slate-900">Orden de Trabajo</h3>
                            <p class="text-xs text-slate-400">Asignación de equipo, técnico responsable y prioridad</p>
                        </div>
                    </div>

                    <button wire:click="closeModal" type="button" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Ascensor *</label>
                                <select wire:model="elevator_id"
                                        class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                    <option value="">-- Seleccionar Ascensor --</option>
                                    @foreach($elevators as $elevator)
                                        <option value="{{ $elevator->id }}">
                                            {{ $elevator->rae_code }} ({{ $elevator->location->building_name ?? 'Sin edificio' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('elevator_id') <span class="text-xs font-semibold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Técnico Asignado</label>
                                <select wire:model="technician_id"
                                        class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                    <option value="">-- Sin asignar --</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                    @endforeach
                                </select>
                                @error('technician_id') <span class="text-xs font-semibold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Tipo *</label>
                                <select wire:model="type"
                                        class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 font-semibold rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                    <option value="corrective">Correctivo</option>
                                    <option value="preventive">Preventivo</option>
                                    <option value="inspection">Inspección ITE</option>
                                    <option value="assembly">Montaje / Reforma</option>
                                </select>
                                @error('type') <span class="text-xs font-semibold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Prioridad *</label>
                                <select wire:model="priority"
                                        class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 font-semibold rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                    <option value="normal">Normal</option>
                                    <option value="urgent">Urgente</option>
                                    <option value="person_trapped">Atrapamiento de Persona</option>
                                </select>
                                @error('priority') <span class="text-xs font-semibold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Categoría del Fallo</label>
                                <input type="text" wire:model="failure_category" placeholder="Ej: Puertas, Cuadro, Motor..."
                                       class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                @error('failure_category') <span class="text-xs font-semibold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Estado Inicial *</label>
                                <select wire:model="status"
                                        class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 font-semibold rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                    <option value="pending">Pendiente</option>
                                    <option value="assigned">Asignada</option>
                                    <option value="in_progress">En Proceso</option>
                                    <option value="completed">Completada</option>
                                </select>
                                @error('status') <span class="text-xs font-semibold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Descripción del Problema *</label>
                            <textarea wire:model="issue_description" rows="3" placeholder="Detalla la incidencia..."
                                      class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition"></textarea>
                            @error('issue_description') <span class="text-xs font-semibold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50/80 border-t border-slate-100">
                        <button type="button" wire:click="closeModal"
                                class="px-4 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 rounded-xl transition">
                            Cancelar
                        </button>
                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 rounded-xl shadow-md shadow-blue-600/20 transition cursor-pointer">
                            <span wire:loading.remove wire:target="save">Guardar Orden</span>
                            <span wire:loading wire:target="save" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                Guardando...
                            </span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif

    <!-- COMPONENTE MODAL DE FIRMA DIGITAL -->
    @livewire('work-order-signature')

    <!-- MODAL DE CONFIRMACIÓN DE BORRADO DE ORDEN DE TRABAJO -->
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
                            Eliminar orden de trabajo <span class="font-mono-tech text-rose-600">#{{ $orderToDeleteId }}</span>
                        </h3>
                        <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                            ¿Estás seguro de que quieres eliminar de forma permanente la orden <strong class="text-slate-900">#{{ $orderToDeleteId }}</strong> (Ascensor RAE: <strong class="font-mono-tech text-slate-900">{{ $orderToDeleteElevator }}</strong>)?
                        </p>
                        <ul class="mt-3 text-xs text-slate-700 list-disc list-inside space-y-1.5 bg-rose-50/80 border border-rose-200/70 rounded-2xl p-3.5">
                            <li>Se eliminarán sus partidas de reforma, fotografías y presupuestos asociados.</li>
                            <li>El ascensor y su ficha técnica se conservarán intactos.</li>
                            <li>Esta acción no se puede deshacer.</li>
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
                            wire:click="deleteOrder"
                            wire:loading.attr="disabled"
                            class="px-4 py-2.5 text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md shadow-rose-600/20 transition active:scale-95 cursor-pointer">
                        Sí, eliminar OT
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>