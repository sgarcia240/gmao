<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl p-6 border border-gray-100 dark:border-gray-700 transition">
        
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Nueva Orden de Trabajo de Mantenimiento</h2>

        <form wire:submit="save" class="space-y-6">
            
            {{-- Cliente y Ascensor (Selects dependientes) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Cliente / Comunidad *</label>
                    <select wire:model.live="client_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        <option value="">-- Seleccionar Cliente --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Ascensor / RAE *</label>
                        {{-- Spinner al actualizar lista de ascensores por cliente --}}
                        <span wire:loading wire:target="client_id" class="text-xs text-indigo-500 animate-pulse flex items-center gap-1">
                            <svg class="animate-spin h-3 w-3" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            Cargando ascensores...
                        </span>
                    </div>
                    
                    <select wire:model="elevator_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-gray-800" {{ empty($client_id) ? 'disabled' : '' }}>
                        <option value="">-- Seleccionar Ascensor --</option>
                        @foreach($elevators as $elevator)
                            <option value="{{ $elevator->id }}">
                                RAE: {{ $elevator->rae_number ?? 'N/D' }} - {{ $elevator->location_name ?? $elevator->model }}
                            </option>
                        @endforeach
                    </select>
                    @error('elevator_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Tipo de Intervención y Prioridad --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Tipo de Mantenimiento *</label>
                    <select wire:model="type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        <option value="preventive">Preventivo (Revisiones)</option>
                        <option value="corrective">Correctivo (Avería/Reparación)</option>
                        <option value="inspection">Inspección Técnica (OCA)</option>
                        <option value="assembly">Montaje / Modernización</option>
                    </select>
                    @error('type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Prioridad *</label>
                    <select wire:model="priority" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        <option value="low">Baja</option>
                        <option value="medium">Normal</option>
                        <option value="high">Alta</option>
                        <option value="urgent">Urgente (Personas atrapadas/Parada)</option>
                    </select>
                    @error('priority') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Fecha Programada *</label>
                    <input type="date" wire:model="scheduled_date" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                    @error('scheduled_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Técnico Asignado --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Técnico Asignado (Opcional)</label>
                <select wire:model="technician_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                    <option value="">-- Asignar más tarde --</option>
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                    @endforeach
                </select>
                @error('technician_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Descripción de la Orden / Trabajo a realizar --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Descripción del Trabajo / Detalle de la Avería *</label>
                <textarea wire:model="issue_description" rows="4" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" placeholder="Escribe los detalles de la revisión o el síntoma reportado por la comunidad..."></textarea>
                @error('issue_description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Botones de Acción --}}
            <div class="flex justify-end items-center space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('work-orders.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancelar
                </a>
                <button type="submit" wire:loading.attr="disabled" wire:target="save" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white rounded-lg text-sm font-semibold shadow transition">
                    <span wire:loading.remove wire:target="save">Guardar Orden de Trabajo</span>
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