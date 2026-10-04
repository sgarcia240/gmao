<div class="p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
    
    <!-- Encabezado y Acción Principal -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Órdenes de Trabajo</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Gestión de incidencias, mantenimientos y partes técnicos</p>
        </div>
        <button wire:click="openCreateModal" 
                class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition duration-150">
            <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva Orden
        </button>
    </div>

    <!-- Alertas Flash -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 text-sm text-green-800 bg-green-100 rounded-lg dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800">
            {{ session('message') }}
        </div>
    @endif

    <!-- Buscador y Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 mb-6 grid grid-cols-1 md:grid-cols-3 gap-4 border border-gray-100 dark:border-gray-700">
        <div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por descripción..." class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div>
            <select wire:model.live="statusFilter" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">-- Todos los estados --</option>
                <option value="pending">Pendiente</option>
                <option value="assigned">Asignada</option>
                <option value="in_progress">En Proceso</option>
                <option value="completed">Completada</option>
                <option value="invoiced">Facturada</option>
            </select>
        </div>
    </div>

    <!-- Tabla de Órdenes con Botón PDF, Firma y Facturación -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden border border-gray-100 dark:border-gray-700">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
                    <th class="p-4">ID / Ubicación</th>
                    <th class="p-4">Tipo</th>
                    <th class="p-4">Prioridad</th>
                    <th class="p-4">Descripción</th>
                    <th class="p-4 text-center">Acciones & PDF</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                @forelse($workOrders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="p-4 font-medium text-gray-900 dark:text-white">
                            #{{ $order->id }} - {{ $order->elevator->location->building_name ?? 'N/A' }}
                            <span class="block text-xs text-gray-400 font-mono">RAE: {{ $order->elevator->rae_code ?? 'N/A' }}</span>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                {{ ucfirst($order->type) }}
                            </span>
                        </td>
                        <td class="p-4">
                            @if($order->priority === 'person_trapped')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300 animate-pulse">Atrapamiento</span>
                            @elseif($order->priority === 'urgent')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300">Urgente</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Normal</span>
                            @endif
                        </td>
                        <td class="p-4 text-gray-600 dark:text-gray-300">
                            {{ Str::limit($order->issue_description, 45) }}
                        </td>
                        <td class="p-4">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Selector de Cambio Rápido -->
                                <select wire:change="updateStatus({{ $order->id }}, $event.target.value)" class="text-xs rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white py-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="pending" @selected($order->status === 'pending')>Pendiente</option>
                                    <option value="assigned" @selected($order->status === 'assigned')>Asignada</option>
                                    <option value="in_progress" @selected($order->status === 'in_progress')>En Proceso</option>
                                    <option value="completed" @selected($order->status === 'completed')>Completada</option>
                                    <option value="invoiced" @selected($order->status === 'invoiced')>Facturada</option>
                                </select>

                                <!-- Botón Capturar Firma Digital -->
                                <button wire:click="$dispatch('openSignatureModal', { orderId: {{ $order->id }} })" 
                                        title="{{ $order->client_signature ? 'Firma registrada (Haga clic para cambiar)' : 'Firmar Parte' }}" 
                                        class="relative p-1.5 text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg border border-emerald-200 dark:border-emerald-800 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                    @if($order->client_signature)
                                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                        </span>
                                    @endif
                                </button>

                                <!-- Botón Descarga/Visualización Parte de Trabajo PDF -->
                                <a href="{{ route('work-orders.pdf.stream', $order->id) }}" 
                                   target="_blank" 
                                   title="Descargar Parte de Trabajo (PDF)" 
                                   class="p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/30 rounded-lg border border-red-200 dark:border-red-800 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 01.293-.707l-5.414-5.414A1 1 0 0013.172 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m0 0l-2-2m2 2l2-2"/>
                                    </svg>
                                </a>

                                <!-- Botón Generar Factura (Inicia la facturación con los datos de esta OT) -->
                                @if($order->status === 'completed')
                                    <a href="{{ route('invoices.index', $order->id) }}" 
                                       title="Crear Factura desde esta OT" 
                                       class="p-1.5 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg border border-indigo-200 dark:border-indigo-800 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500 dark:text-gray-400">No se encontraron órdenes de trabajo registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $workOrders->links() }}
        </div>
    </div>

    <!-- MODAL CREACIÓN INTERACTIVO -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="relative w-full max-w-2xl bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-200 dark:border-gray-700">
                
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Nueva Orden de Trabajo</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase mb-1">Ascensor *</label>
                                <select wire:model="elevator_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                    <option value="">-- Seleccionar Ascensor --</option>
                                    @foreach($elevators as $elevator)
                                        <option value="{{ $elevator->id }}">
                                            {{ $elevator->rae_code }} ({{ $elevator->location->building_name ?? 'Sin edificio' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('elevator_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase mb-1">Técnico Asignado</label>
                                <select wire:model="technician_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                    <option value="">-- Sin asignar --</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                    @endforeach
                                </select>
                                @error('technician_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase mb-1">Tipo *</label>
                                <select wire:model="type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                    <option value="corrective">Correctivo</option>
                                    <option value="preventive">Preventivo</option>
                                    <option value="inspection">Inspección ITE</option>
                                    <option value="assembly">Montaje / Reforma</option>
                                </select>
                                @error('type') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase mb-1">Prioridad *</label>
                                <select wire:model="priority" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                    <option value="normal">Normal</option>
                                    <option value="urgent">Urgente</option>
                                    <option value="person_trapped">Atrapamiento de Persona</option>
                                </select>
                                @error('priority') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase mb-1">Categoría del Fallo</label>
                                <input type="text" wire:model="failure_category" placeholder="Ej: Puertas, Cuadro, Motor..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                @error('failure_category') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase mb-1">Estado Inicial *</label>
                                <select wire:model="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                    <option value="pending">Pendiente</option>
                                    <option value="assigned">Asignada</option>
                                    <option value="in_progress">En Proceso</option>
                                    <option value="completed">Completada</option>
                                </select>
                                @error('status') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase mb-1">Descripción del Problema *</label>
                            <textarea wire:model="issue_description" rows="3" placeholder="Detalla la incidencia..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm"></textarea>
                            @error('issue_description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow">
                            Guardar Orden
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif

    <!-- COMPONENTE MODAL DE FIRMA DIGITAL -->
    @livewire('work-order-signature')
</div>