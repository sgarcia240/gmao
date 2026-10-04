<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Nueva Orden de Trabajo de Mantenimiento</h2>

        <form wire:submit="save" class="space-y-6">
            
            {{-- Cliente y Ascensor (Selects dependientes) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cliente / Comunidad</label>
                    <select wire:model.live="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Seleccionar Cliente --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Ascensor / RAE</label>
                    <select wire:model="elevator_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ empty($client_id) ? 'disabled' : '' }}>
                        <option value="">-- Seleccionar Ascensor --</option>
                        @foreach($elevators as $elevator)
                            <option value="{{ $elevator->id }}">
                                RAE: {{ $elevator->rae_number ?? 'N/D' }} - {{ $elevator->location_name ?? $elevator->model }}
                            </option>
                        @endforeach
                    </select>
                    @error('elevator_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Tipo de Intervención y Prioridad --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipo de Mantenimiento</label>
                    <select wire:model="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="preventive">Preventivo (Revisiones)</option>
                        <option value="corrective">Correctivo (Avería/Reparación)</option>
                        <option value="inspection">Inspección Técnica (OCA)</option>
                        <option value="assembly">Montaje / Modernización</option>
                    </select>
                    @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Prioridad</label>
                    <select wire:model="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="low">Baja</option>
                        <option value="medium">Normal</option>
                        <option value="high">Alta</option>
                        <option value="urgent">Urgente (Personas atrapadas/Parada)</option>
                    </select>
                    @error('priority') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha Programada</label>
                    <input type="date" wire:model="scheduled_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('scheduled_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Técnico Asignado --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Técnico Asignado (Opcional)</label>
                <select wire:model="technician_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Asignar más tarde --</option>
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                    @endforeach
                </select>
                @error('technician_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            {{-- Descripción de la Orden / Trabajo a realizar --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Descripción del Trabajo / Detalle de la Avería</label>
                <textarea wire:model="issue_description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Escribe los detalles de la revisión o el síntoma reportado por la comunidad..."></textarea>
                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            {{-- Botones de Acción --}}
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('work-orders.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" wire:loading.attr="disabled" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span wire:loading.remove>Guardar Orden de Trabajo</span>
                    <span wire:loading>Guardando...</span>
                </button>
            </div>

        </form>
    </div>
</div>