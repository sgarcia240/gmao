<div class="p-8 bg-slate-50 min-h-screen">
    
    <!-- Navegación superior / Volver -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('elevators.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-800 transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver al listado de ascensores
        </a>
    </div>

    <!-- Banner Principal del Ascensor -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="p-3.5 bg-blue-50 text-blue-600 rounded-2xl border border-blue-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V10h4v11m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold font-mono text-blue-600">{{ $elevator->rae_code }}</h1>
                        @if($elevator->status === 'active')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">En Servicio</span>
                        @elseif($elevator->status === 'maintenance')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">En Mantenimiento</span>
                        @elseif($elevator->status === 'stopped')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-700">Parado por Avería</span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-600">Fuera de Servicio</span>
                        @endif
                    </div>
                    <p class="text-lg font-semibold text-slate-800 mt-1">
                        {{ $elevator->location->building_name ?? $elevator->building_name ?? 'Comunidad sin nombre' }}
                    </p>
                    <p class="text-sm text-slate-500">
                        {{ $elevator->location->address ?? $elevator->address ?? 'Dirección no registrada' }}
                    </p>
                </div>
            </div>

            <!-- Métricas Rápidas -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-6">
                <div>
                    <span class="text-xs font-medium text-slate-400 block">Marca / Modelo</span>
                    <span class="text-sm font-semibold text-slate-800">{{ $elevator->brand ?? 'S/D' }} {{ $elevator->model }}</span>
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-400 block">Capacidad / Paradas</span>
                    <span class="text-sm font-semibold text-slate-800">{{ $elevator->stops }} Paradas · {{ $elevator->capacity_kg }} kg</span>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <span class="text-xs font-medium text-slate-400 block">Próxima ITE</span>
                    <span class="text-sm font-semibold {{ optional($elevator->next_inspection_date)->isPast() ? 'text-rose-600 font-bold' : 'text-slate-800' }}">
                        {{ $elevator->next_inspection_date ? \Carbon\Carbon::parse($elevator->next_inspection_date)->format('d/m/Y') : 'Pendiente' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navegación de Pestañas -->
    <div class="border-b border-slate-200 mb-6 flex gap-6">
        <button wire:click="setTab('work_orders')" 
                class="pb-3 text-sm font-semibold border-b-2 transition-colors {{ $activeTab === 'work_orders' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
            Órdenes de Trabajo ({{ $elevator->workOrders?->count() ?? 0 }})
        </button>
        <button wire:click="setTab('invoices')" 
                class="pb-3 text-sm font-semibold border-b-2 transition-colors {{ $activeTab === 'invoices' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
            Facturación ({{ $elevator->invoices?->count() ?? 0 }})
        </button>
        <button wire:click="setTab('tech_specs')" 
                class="pb-3 text-sm font-semibold border-b-2 transition-colors {{ $activeTab === 'tech_specs' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
            Ficha Técnica Completa
        </button>
    </div>

    <!-- Pestaña 1: Órdenes de Trabajo -->
    @if($activeTab === 'work_orders')
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="p-4">Nº OT</th>
                        <th class="p-4">Tipo</th>
                        <th class="p-4">Descripción / Avería</th>
                        <th class="p-4">Técnico</th>
                        <th class="p-4">Fecha</th>
                        <th class="p-4">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($elevator->workOrders ?? [] as $order)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 font-bold text-blue-600 font-mono">#{{ $order->code ?? $order->id }}</td>
                            <td class="p-4 font-medium text-slate-700 capitalize">{{ $order->type ?? 'Correctivo' }}</td>
                            <td class="p-4 text-slate-600 max-w-xs truncate">{{ $order->description ?? 'Mantenimiento de rutina' }}</td>
                            <td class="p-4 text-slate-600">{{ $order->technician->name ?? 'Sin asignar' }}</td>
                            <td class="p-4 text-slate-500">{{ optional($order->created_at)->format('d/m/Y') }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">
                                    {{ ucfirst($order->status ?? 'Pendiente') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-400">No hay órdenes de trabajo asociadas a este ascensor.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

   <!-- Pestaña 2: Facturas -->
@if($activeTab === 'invoices')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <th class="p-4">Nº Factura</th>
                    <th class="p-4">Fecha Emisión</th>
                    <th class="p-4">Concepto</th>
                    <th class="p-4">Total Base</th>
                    <th class="p-4">Total (+IVA)</th>
                    <th class="p-4">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                <!-- En la pestaña de facturas -->
                @forelse($this->clientInvoices as $invoice)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4 font-bold text-blue-600 font-mono">{{ $invoice->invoice_number }}</td>
                        <td class="p-4 text-slate-500">{{ optional($invoice->created_at)->format('d/m/Y') }}</td>
                        <td class="p-4 text-slate-600 max-w-xs truncate">{{ $invoice->concept ?? 'Mantenimiento mensual' }}</td>
                        <td class="p-4 text-slate-700">{{ number_format($invoice->subtotal ?? 0, 2) }} €</td>
                        <td class="p-4 font-bold text-slate-800">{{ number_format($invoice->total ?? 0, 2) }} €</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Cobrada</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-slate-400">No se han emitido facturas para el cliente de esta finca.</td>
                    </tr>
                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif

    <!-- Pestaña 3: Datos Técnicos -->
    @if($activeTab === 'tech_specs')
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Datos Mecánicos y Eléctricos</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-slate-400">Marca / Fabricante:</dt><dd class="font-semibold text-slate-800">{{ $elevator->brand ?? 'N/D' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-400">Modelo:</dt><dd class="font-semibold text-slate-800">{{ $elevator->model ?? 'N/D' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-400">Carga Nominal:</dt><dd class="font-semibold text-slate-800">{{ $elevator->capacity_kg }} kg</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-400">Nº de Paradas:</dt><dd class="font-semibold text-slate-800">{{ $elevator->stops }}</dd></div>
                </dl>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Titular y Ubicación</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                    <dt class="text-slate-400">Cliente / Contratante:</dt>
                        <dd class="font-semibold text-slate-800">
                            {{ $elevator->location->client->name ?? 'Sin cliente asignado' }}
                        </dd>
                    </div>
                    <div class="flex justify-between"><dt class="text-slate-400">Edificio:</dt><dd class="font-semibold text-slate-800">{{ $elevator->location->building_name ?? $elevator->building_name ?? 'N/D' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-400">Dirección:</dt><dd class="font-semibold text-slate-800">{{ $elevator->location->address ?? $elevator->address ?? 'N/D' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-400">Próxima Inspección ITE:</dt><dd class="font-semibold text-slate-800">{{ $elevator->next_inspection_date ? \Carbon\Carbon::parse($elevator->next_inspection_date)->format('d/m/Y') : 'N/D' }}</dd></div>
                </dl>
            </div>
        </div>
    @endif

</div>