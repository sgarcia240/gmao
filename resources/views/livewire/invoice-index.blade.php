<div class="max-w-7xl mx-auto space-y-6 pb-20">

    <!-- CABECERA EJECUTIVA -->
    <div class="relative overflow-hidden rounded-3xl bg-[#070b14] p-6 sm:p-8 text-white shadow-xl border border-white/10">
        <div class="pointer-events-none absolute -top-24 -right-24 w-80 h-80 rounded-full bg-cyan-500/15 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 left-1/4 w-80 h-80 rounded-full bg-blue-600/15 blur-3xl"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-cyan-500/10 border border-cyan-400/20 text-cyan-300 font-mono-tech text-[11px] uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                    Gestión Financiera · Facturación
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Facturación
                </h1>
                <p class="text-xs sm:text-sm text-slate-400">
                    Emisión de facturas vinculadas a órdenes de trabajo, seguimiento de cobros y exportación oficial en PDF.
                </p>
            </div>

            <button wire:click="openCreateModal"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-5 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-[0.99] text-white font-bold text-sm rounded-2xl shadow-lg shadow-cyan-500/20 transition cursor-pointer shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span>Nueva Factura</span>
            </button>
        </div>
    </div>

    <!-- MENSAJE DE CONFIRMACIÓN -->
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
                   placeholder="Buscar por Nº de factura o cliente..."
                   class="w-full pl-11 pr-4 py-3 bg-slate-50/80 border border-slate-200 rounded-2xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
        </div>

        <div class="w-full md:w-64">
            <select wire:model.live="statusFilter"
                    class="w-full py-3 px-4 bg-slate-50/80 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                <option value="">Todos los estados</option>
                <option value="draft">Borrador</option>
                <option value="issued">Emitida</option>
                <option value="paid">Cobrada</option>
                <option value="cancelled">Anulada</option>
            </select>
        </div>
    </div>

    <!-- TABLA PRINCIPAL DE FACTURACIÓN -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[740px]">
                <thead>
                    <tr class="bg-slate-50/90 font-mono-tech text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200/80">
                        <th class="py-4 px-5">Nº Factura</th>
                        <th class="py-4 px-5">Cliente</th>
                        <th class="py-4 px-5">Fecha Emisión</th>
                        <th class="py-4 px-5">Total (+IVA)</th>
                        <th class="py-4 px-5">Estado</th>
                        <th class="py-4 px-5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm whitespace-nowrap">
                    @forelse($invoices as $inv)
                        <tr class="group hover:bg-blue-50/30 transition-colors">
                            <td class="py-4 px-5">
                                <span class="font-mono-tech text-xs font-bold px-3 py-1.5 rounded-xl bg-slate-900 text-cyan-400 shadow-2xs inline-block">
                                    {{ $inv->invoice_number }}
                                </span>
                            </td>

                            <td class="py-4 px-5 font-bold text-slate-900 max-w-[240px] truncate">
                                {{ $inv->client->name ?? 'N/A' }}
                            </td>

                            <td class="py-4 px-5 font-mono-tech text-xs text-slate-500">
                                {{ $inv->issue_date->format('d/m/Y') }}
                            </td>

                            <td class="py-4 px-5">
                                <span class="font-mono-tech font-extrabold text-sm text-slate-900">
                                    {{ number_format($inv->total, 2, ',', '.') }} €
                                </span>
                            </td>

                            <td class="py-4 px-5">
                                @if($inv->status === 'paid')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Cobrada
                                    </span>
                                @elseif($inv->status === 'issued')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-blue-500/10 text-blue-700 border border-blue-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                        Emitida
                                    </span>
                                @elseif($inv->status === 'cancelled')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-rose-500/10 text-rose-700 border border-rose-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Anulada
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Borrador
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-5 text-right">
                                <div class="inline-flex items-center justify-end gap-2">
                                    @if($inv->status !== 'paid')
                                        <button wire:click="markAsPaid({{ $inv->id }})"
                                                title="Marcar como cobrada"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-emerald-700 bg-emerald-50/90 hover:bg-emerald-100 border border-emerald-200/80 rounded-xl transition cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                            </svg>
                                            <span>Cobrar</span>
                                        </button>
                                    @endif

                                    <a href="{{ route('invoices.pdf.stream', $inv->id) }}"
                                       target="_blank"
                                       title="Ver PDF Factura"
                                       class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-700 bg-white hover:bg-slate-900 hover:text-white border border-slate-200 hover:border-slate-900 rounded-xl shadow-2xs transition">
                                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                        </svg>
                                        <span>PDF</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-6 text-center">
                                <p class="text-sm font-semibold text-slate-500">No hay facturas registradas con los filtros actuales.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 bg-slate-50/60 border-t border-slate-100">
            {{ $invoices->links() }}
        </div>
    </div>

    <!-- MODAL DE CREACIÓN DE FACTURA -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/75 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4">
            <div class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200/80 my-8">

                <!-- Encabezado Modal -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/70">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm shadow-blue-600/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-extrabold text-slate-900">Nueva Factura</h3>
                            <p class="text-xs text-slate-400">Configuración de cliente, vencimiento y líneas de detalle</p>
                        </div>
                    </div>

                    <button wire:click="closeModal" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                        <!-- Datos Cabecera Factura -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <div class="sm:col-span-2 md:col-span-3">
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Cliente *</label>
                                <select wire:model="client_id"
                                        class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                    <option value="">-- Seleccionar Cliente --</option>
                                    @foreach($clients as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                @error('client_id') <span class="text-xs font-semibold text-rose-500 block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Fecha Emisión *</label>
                                <input type="date" wire:model="issue_date"
                                       class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 font-mono-tech rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Fecha Vencimiento *</label>
                                <input type="date" wire:model="due_date"
                                       class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 font-mono-tech rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">IVA (%) *</label>
                                <input type="number" step="0.01" wire:model="tax_rate"
                                       class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 font-mono-tech rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                            </div>
                        </div>

                        <!-- Líneas de la Factura -->
                        <div class="pt-5 border-t border-slate-100">
                            <div class="flex justify-between items-center mb-3.5">
                                <div>
                                    <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Líneas de la Factura</h4>
                                    <p class="text-[11px] text-slate-400">Vincula órdenes de trabajo o conceptos libres</p>
                                </div>
                                <button type="button" wire:click="addItem"
                                        class="inline-flex items-center gap-1.5 text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200/70 px-3.5 py-2 rounded-xl font-bold transition cursor-pointer">
                                    <span>+ Añadir Ítem</span>
                                </button>
                            </div>

                            <div class="space-y-3">
                                @foreach($items as $index => $item)
                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-center bg-slate-50/90 p-3.5 rounded-2xl border border-slate-200/80">
                                        <div class="sm:col-span-3">
                                            <label class="block font-mono-tech text-[10px] font-bold text-slate-400 uppercase sm:hidden mb-1">Orden de Trabajo</label>
                                            <select wire:model="items.{{ $index }}.work_order_id"
                                                    class="w-full px-2.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-800 font-mono-tech text-xs focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">
                                                <option value="">-- Sin OT --</option>
                                                @foreach($workOrders as $wo)
                                                    <option value="{{ $wo->id }}">OT #{{ $wo->id }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label class="block font-mono-tech text-[10px] font-bold text-slate-400 uppercase sm:hidden mb-1">Descripción</label>
                                            <input type="text" wire:model="items.{{ $index }}.description" placeholder="Descripción del servicio"
                                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-800 text-xs focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">
                                        </div>

                                        <div class="grid grid-cols-3 sm:grid-cols-6 sm:col-span-6 gap-2 items-center mt-1 sm:mt-0">
                                            <div class="col-span-1 sm:col-span-2">
                                                <label class="block font-mono-tech text-[10px] font-bold text-slate-400 uppercase sm:hidden mb-1">Cant.</label>
                                                <input type="number" wire:model="items.{{ $index }}.quantity" wire:keyup="updateItemTotal({{ $index }})" placeholder="Cant."
                                                       class="w-full px-2.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-800 font-mono-tech text-xs focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">
                                            </div>

                                            <div class="col-span-1 sm:col-span-2">
                                                <label class="block font-mono-tech text-[10px] font-bold text-slate-400 uppercase sm:hidden mb-1">Precio Un.</label>
                                                <input type="number" step="0.01" wire:model="items.{{ $index }}.unit_price" wire:keyup="updateItemTotal({{ $index }})" placeholder="Precio (€)"
                                                       class="w-full px-2.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-800 font-mono-tech text-xs focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">
                                            </div>

                                            <div class="col-span-1 sm:col-span-2 flex items-center justify-end gap-1.5 pl-1">
                                                <span class="font-mono-tech text-xs font-extrabold text-slate-900">
                                                    {{ number_format($item['total_price'] ?? 0, 2) }} €
                                                </span>
                                                @if(count($items) > 1)
                                                    <button type="button" wire:click="removeItem({{ $index }})"
                                                            title="Eliminar línea"
                                                            class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition cursor-pointer">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Pie Modal -->
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 px-6 py-4 bg-slate-50/80 border-t border-slate-100">
                        <button type="button" wire:click="closeModal"
                                class="w-full sm:w-auto px-4 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 rounded-xl transition">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="w-full sm:w-auto px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-600/20 transition active:scale-95 cursor-pointer">
                            Emitir Factura
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>