<div class="p-4 sm:p-6 lg:p-8 bg-gray-100 dark:bg-gray-900 min-h-screen space-y-4 sm:space-y-6">
    
    <!-- Encabezado y acción principal -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">Facturación</h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Emisión y control de facturas</p>
        </div>
        <button wire:click="openCreateModal" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
            <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nueva Factura
        </button>
    </div>

    <!-- Mensaje de confirmación -->
    @if (session()->has('message'))
        <div class="p-4 text-xs sm:text-sm text-green-800 bg-green-100 rounded-lg dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800">
            {{ session('message') }}
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 border border-gray-100 dark:border-gray-700">
        <div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por Nº o cliente..." class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-xs sm:text-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div>
            <select wire:model.live="statusFilter" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-xs sm:text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">-- Todos los estados --</option>
                <option value="draft">Borrador</option>
                <option value="issued">Emitida</option>
                <option value="paid">Cobrada</option>
                <option value="cancelled">Anulada</option>
            </select>
        </div>
    </div>

    <!-- Tabla Principal -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
                        <th class="p-4">Nº Factura</th>
                        <th class="p-4">Cliente</th>
                        <th class="p-4">Fecha Emisión</th>
                        <th class="p-4">Total</th>
                        <th class="p-4">Estado</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-xs sm:text-sm whitespace-nowrap">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                            <td class="p-4 font-bold text-indigo-600 dark:text-indigo-400">
                                {{ $inv->invoice_number }}
                            </td>
                            <td class="p-4 font-medium text-gray-900 dark:text-white max-w-[200px] truncate">
                                {{ $inv->client->name ?? 'N/A' }}
                            </td>
                            <td class="p-4 text-gray-600 dark:text-gray-300">
                                {{ $inv->issue_date->format('d/m/Y') }}
                            </td>
                            <td class="p-4 font-bold text-gray-900 dark:text-white">
                                {{ number_format($inv->total, 2, ',', '.') }} €
                            </td>
                            <td class="p-4">
                                @if($inv->status === 'paid')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">Cobrada</span>
                                @elseif($inv->status === 'issued')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">Emitida</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Borrador</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if($inv->status !== 'paid')
                                        <button wire:click="markAsPaid({{ $inv->id }})" title="Marcar como cobrada" class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg border border-green-200 dark:border-green-800 dark:hover:bg-green-900/30 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    @endif
                                    <a href="{{ route('invoices.pdf.stream', $inv->id) }}" target="_blank" title="Ver PDF Factura" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg border border-red-200 dark:border-red-800 dark:hover:bg-red-900/30 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 01.293-.707l-5.414-5.414A1 1 0 0013.172 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-500 dark:text-gray-400">No hay facturas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $invoices->links() }}
        </div>
    </div>

    <!-- Modal de Creación -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4">
            <div class="relative w-full max-w-4xl bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-200 dark:border-gray-700 my-8">
                <div class="flex items-center justify-between px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">Nueva Factura</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="p-4 sm:p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <div class="sm:col-span-2 md:col-span-3">
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase mb-1">Cliente *</label>
                                <select wire:model="client_id" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white text-xs sm:text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">-- Seleccionar Cliente --</option>
                                    @foreach($clients as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                @error('client_id') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase mb-1">Fecha Emisión *</label>
                                <input type="date" wire:model="issue_date" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white text-xs sm:text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase mb-1">Fecha Vencimiento *</label>
                                <input type="date" wire:model="due_date" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white text-xs sm:text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase mb-1">IVA (%) *</label>
                                <input type="number" step="0.01" wire:model="tax_rate" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white text-xs sm:text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-300 uppercase">Líneas de la Factura</h4>
                                <button type="button" wire:click="addItem" class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300 px-3 py-1.5 rounded-md font-semibold transition">+ Añadir Ítem</button>
                            </div>

                            <div class="space-y-3">
                                @foreach($items as $index => $item)
                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center bg-gray-50 dark:bg-gray-700/40 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                                        <div class="sm:col-span-3">
                                            <label class="block text-[10px] text-gray-400 uppercase sm:hidden mb-1">Orden de Trabajo</label>
                                            <select wire:model="items.{{ $index }}.work_order_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-xs">
                                                <option value="">-- Sin OT --</option>
                                                @foreach($workOrders as $wo)
                                                    <option value="{{ $wo->id }}">OT #{{ $wo->id }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label class="block text-[10px] text-gray-400 uppercase sm:hidden mb-1">Descripción</label>
                                            <input type="text" wire:model="items.{{ $index }}.description" placeholder="Descripción" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-xs">
                                        </div>
                                        <div class="grid grid-cols-3 sm:grid-cols-6 sm:col-span-6 gap-2 items-center mt-2 sm:mt-0">
                                            <div class="col-span-1 sm:col-span-2">
                                                <label class="block text-[10px] text-gray-400 uppercase sm:hidden mb-1">Cant.</label>
                                                <input type="number" wire:model="items.{{ $index }}.quantity" wire:keyup="updateItemTotal({{ $index }})" placeholder="Cant." class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-xs">
                                            </div>
                                            <div class="col-span-1 sm:col-span-2">
                                                <label class="block text-[10px] text-gray-400 uppercase sm:hidden mb-1">Precio Un.</label>
                                                <input type="number" step="0.01" wire:model="items.{{ $index }}.unit_price" wire:keyup="updateItemTotal({{ $index }})" placeholder="Precio Un." class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-xs">
                                            </div>
                                            <div class="col-span-1 sm:col-span-2 flex items-center justify-between pl-1">
                                                <span class="text-xs font-bold text-gray-800 dark:text-white">{{ number_format($item['total_price'] ?? 0, 2) }}€</span>
                                                @if(count($items) > 1)
                                                    <button type="button" wire:click="removeItem({{ $index }})" class="text-red-500 hover:text-red-700 p-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3 px-4 sm:px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" wire:click="closeModal" class="w-full sm:w-auto px-4 py-2 text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition">Cancelar</button>
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 text-xs sm:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition">Emitir Factura</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>