<div class="max-w-7xl mx-auto space-y-6 pb-20">

    <!-- NAVEGACIÓN SUPERIOR / VOLVER -->
    <div class="flex items-center justify-between">
        <a href="{{ route('elevators.index') }}"
           class="group inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200/80 text-xs sm:text-sm font-bold text-slate-600 hover:text-slate-900 hover:border-slate-300 shadow-2xs transition">
            <svg class="w-4 h-4 text-slate-400 group-hover:-translate-x-0.5 group-hover:text-blue-600 transition-transform" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Volver al parque de ascensores</span>
        </a>
    </div>

    <!-- BANNER PRINCIPAL DEL ASCENSOR (CENTRO DE CONTROL) -->
    <div class="relative overflow-hidden rounded-3xl bg-[#070b14] p-6 sm:p-8 text-white shadow-xl border border-white/10">
        <!-- Efectos luminosos ambientales -->
        <div class="pointer-events-none absolute -top-24 -right-24 w-80 h-80 rounded-full bg-cyan-500/15 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 left-1/4 w-80 h-80 rounded-full bg-blue-600/15 blur-3xl"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <!-- Identificación del aparato -->
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-cyan-400 p-[1px] shadow-lg shadow-cyan-500/20 shrink-0">
                    <div class="w-full h-full bg-[#0b1120] rounded-[15px] flex items-center justify-center">
                        <svg class="w-7 h-7 text-cyan-400" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V10h4v11m-4 0h4"/>
                        </svg>
                    </div>
                </div>

                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2.5 mb-1.5">
                        <h1 class="text-2xl sm:text-3xl font-extrabold font-mono-tech text-cyan-400 tracking-tight truncate">
                            {{ $elevator->rae_code }}
                        </h1>

                        @if($elevator->status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-400/25">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                En Servicio
                            </span>
                        @elseif($elevator->status === 'maintenance')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-amber-500/15 text-amber-300 border border-amber-400/25">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                En Mantenimiento
                            </span>
                        @elseif($elevator->status === 'stopped')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-rose-500/15 text-rose-300 border border-rose-400/25">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-ping"></span>
                                Parado por Avería
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-white/10 text-slate-300 border border-white/15">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                Fuera de Servicio
                            </span>
                        @endif
                    </div>

                    <p class="text-lg sm:text-xl font-bold text-white truncate">
                        {{ $elevator->location->building_name ?? $elevator->building_name ?? 'Comunidad sin nombre' }}
                    </p>
                    <p class="text-xs sm:text-sm text-slate-400 mt-0.5 flex items-center gap-1.5 truncate">
                        <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <span class="truncate">{{ $elevator->location->address ?? $elevator->address ?? 'Dirección no registrada' }}</span>
                    </p>
                </div>
            </div>

            <!-- Tarjetas Glassmorphism de Métricas Rápidas -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 lg:min-w-[420px]">
                <div class="p-3.5 rounded-2xl bg-white/[0.04] border border-white/10 backdrop-blur-xs">
                    <span class="font-mono-tech text-[10px] uppercase tracking-wider text-slate-400 block">Marca / Modelo</span>
                    <span class="text-xs sm:text-sm font-bold text-white mt-1 block truncate">
                        {{ $elevator->brand ?? 'S/D' }} {{ $elevator->model }}
                    </span>
                </div>

                <div class="p-3.5 rounded-2xl bg-white/[0.04] border border-white/10 backdrop-blur-xs">
                    <span class="font-mono-tech text-[10px] uppercase tracking-wider text-slate-400 block">Capacidad</span>
                    <span class="font-mono-tech text-xs sm:text-sm font-bold text-cyan-300 mt-1 block">
                        {{ $elevator->stops }} Par · {{ $elevator->capacity_kg }} kg
                    </span>
                </div>

                <div class="col-span-2 sm:col-span-1 p-3.5 rounded-2xl bg-white/[0.04] border border-white/10 backdrop-blur-xs">
                    <span class="font-mono-tech text-[10px] uppercase tracking-wider text-slate-400 block">Próxima ITE</span>
                    @php
                        $iteExpired = $elevator->next_inspection_date && \Carbon\Carbon::parse($elevator->next_inspection_date)->isPast();
                    @endphp
                    <span class="font-mono-tech text-xs sm:text-sm font-bold mt-1 block {{ $iteExpired ? 'text-rose-400' : 'text-emerald-300' }}">
                        {{ $elevator->next_inspection_date ? \Carbon\Carbon::parse($elevator->next_inspection_date)->format('d/m/Y') : 'Pendiente' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- NAVEGACIÓN DE PESTAÑAS SEGMENTADA -->
    <div class="bg-white p-1.5 rounded-2xl border border-slate-200/80 shadow-2xs inline-flex flex-wrap sm:flex-nowrap gap-1.5 w-full sm:w-auto">
        <button wire:click="setTab('work_orders')"
                class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer {{ $activeTab === 'work_orders' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
            <span>Órdenes de Trabajo</span>
            <span class="font-mono-tech text-[11px] px-2 py-0.5 rounded-md {{ $activeTab === 'work_orders' ? 'bg-cyan-500/20 text-cyan-300' : 'bg-slate-100 text-slate-600' }}">
                {{ $elevator->workOrders?->count() ?? 0 }}
            </span>
        </button>

        <button wire:click="setTab('invoices')"
                class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer {{ $activeTab === 'invoices' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
            <span>Facturación</span>
            <span class="font-mono-tech text-[11px] px-2 py-0.5 rounded-md {{ $activeTab === 'invoices' ? 'bg-cyan-500/20 text-cyan-300' : 'bg-slate-100 text-slate-600' }}">
                {{ $elevator->invoices?->count() ?? 0 }}
            </span>
        </button>

        <button wire:click="setTab('tech_specs')"
                class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer {{ $activeTab === 'tech_specs' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
            <span>Ficha Técnica Completa</span>
        </button>
    </div>

    <!-- PESTAÑA 1: ÓRDENES DE TRABAJO -->
    @if($activeTab === 'work_orders')
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-extrabold text-slate-900">Historial de Intervenciones</h2>
                <span class="font-mono-tech text-xs text-slate-400">Ordenadas por fecha</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[680px]">
                    <thead>
                        <tr class="bg-slate-50/90 font-mono-tech text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200/80">
                            <th class="py-3.5 px-5">Nº OT</th>
                            <th class="py-3.5 px-5">Tipo</th>
                            <th class="py-3.5 px-5">Descripción / Avería</th>
                            <th class="py-3.5 px-5">Técnico</th>
                            <th class="py-3.5 px-5">Fecha</th>
                            <th class="py-3.5 px-5">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm whitespace-nowrap">
                        @forelse($elevator->workOrders ?? [] as $order)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="py-4 px-5">
                                    <span class="font-mono-tech text-xs font-bold px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-200/70">
                                        #{{ $order->code ?? $order->id }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 font-semibold text-slate-800 capitalize">{{ $order->type ?? 'Correctivo' }}</td>
                                <td class="py-4 px-5 text-slate-600 max-w-xs truncate">{{ $order->description ?? 'Mantenimiento de rutina' }}</td>
                                <td class="py-4 px-5 text-slate-700 font-medium">{{ $order->technician->name ?? 'Sin asignar' }}</td>
                                <td class="py-4 px-5 font-mono-tech text-xs text-slate-500">{{ optional($order->created_at)->format('d/m/Y') }}</td>
                                <td class="py-4 px-5">
                                    @php $st = strtolower($order->status ?? 'pendiente'); @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full {{ in_array($st, ['completed', 'completada', 'closed', 'cerrada']) ? 'bg-emerald-500/10 text-emerald-700 border border-emerald-500/20' : (in_array($st, ['in_progress', 'en_curso', 'en curso']) ? 'bg-amber-500/10 text-amber-700 border border-amber-500/20' : 'bg-slate-100 text-slate-700 border border-slate-200') }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ in_array($st, ['completed', 'completada', 'closed', 'cerrada']) ? 'bg-emerald-500' : (in_array($st, ['in_progress', 'en_curso', 'en curso']) ? 'bg-amber-500' : 'bg-slate-400') }}"></span>
                                        {{ ucfirst($order->status ?? 'Pendiente') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 px-6 text-center text-sm font-medium text-slate-400">
                                    No hay órdenes de trabajo asociadas a este ascensor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- PESTAÑA 2: FACTURAS -->
    @if($activeTab === 'invoices')
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-extrabold text-slate-900">Facturación Asociada a la Finca</h2>
                <span class="font-mono-tech text-xs text-slate-400">Importes en EUR (€)</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[680px]">
                    <thead>
                        <tr class="bg-slate-50/90 font-mono-tech text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200/80">
                            <th class="py-3.5 px-5">Nº Factura</th>
                            <th class="py-3.5 px-5">Fecha Emisión</th>
                            <th class="py-3.5 px-5">Concepto</th>
                            <th class="py-3.5 px-5">Total Base</th>
                            <th class="py-3.5 px-5">Total (+IVA)</th>
                            <th class="py-3.5 px-5">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm whitespace-nowrap">
                        @forelse($this->clientInvoices as $invoice)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="py-4 px-5">
                                    <span class="font-mono-tech text-xs font-bold px-2.5 py-1 rounded-lg bg-slate-900 text-cyan-400">
                                        {{ $invoice->invoice_number }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 font-mono-tech text-xs text-slate-500">{{ optional($invoice->created_at)->format('d/m/Y') }}</td>
                                <td class="py-4 px-5 text-slate-700 font-medium max-w-xs truncate">{{ $invoice->concept ?? 'Mantenimiento mensual' }}</td>
                                <td class="py-4 px-5 font-mono-tech text-slate-600">{{ number_format($invoice->subtotal ?? 0, 2) }} €</td>
                                <td class="py-4 px-5 font-mono-tech font-extrabold text-slate-900">{{ number_format($invoice->total ?? 0, 2) }} €</td>
                                <td class="py-4 px-5">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Cobrada
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 px-6 text-center text-sm font-medium text-slate-400">
                                    No se han emitido facturas para el cliente de esta finca.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- PESTAÑA 3: FICHA TÉCNICA COMPLETA -->
    @if($activeTab === 'tech_specs')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Bloque Mecánico y Eléctrico -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-6 sm:p-7">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Datos Mecánicos y Eléctricos</h3>
                        <p class="text-xs text-slate-400">Parámetros nominales homologados</p>
                    </div>
                </div>

                <dl class="divide-y divide-slate-100 text-sm">
                    <div class="py-3 flex justify-between items-center gap-4">
                        <dt class="text-slate-500 font-medium">Marca / Fabricante</dt>
                        <dd class="font-bold text-slate-900">{{ $elevator->brand ?? 'N/D' }}</dd>
                    </div>
                    <div class="py-3 flex justify-between items-center gap-4">
                        <dt class="text-slate-500 font-medium">Modelo</dt>
                        <dd class="font-bold text-slate-900">{{ $elevator->model ?? 'N/D' }}</dd>
                    </div>
                    <div class="py-3 flex justify-between items-center gap-4">
                        <dt class="text-slate-500 font-medium">Carga Nominal</dt>
                        <dd class="font-mono-tech font-bold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg text-xs">{{ $elevator->capacity_kg }} kg</dd>
                    </div>
                    <div class="py-3 flex justify-between items-center gap-4">
                        <dt class="text-slate-500 font-medium">Nº de Paradas</dt>
                        <dd class="font-mono-tech font-bold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg text-xs">{{ $elevator->stops }} paradas</dd>
                    </div>
                </dl>
            </div>

            <!-- Bloque Titular y Ubicación -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-6 sm:p-7">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Titular y Ubicación</h3>
                        <p class="text-xs text-slate-400">Datos de emplazamiento y control reglamentario</p>
                    </div>
                </div>

                <dl class="divide-y divide-slate-100 text-sm">
                    <div class="py-3 flex justify-between items-center gap-4">
                        <dt class="text-slate-500 font-medium">Cliente / Contratante</dt>
                        <dd class="font-bold text-slate-900 text-right">{{ $elevator->location->client->name ?? 'Sin cliente asignado' }}</dd>
                    </div>
                    <div class="py-3 flex justify-between items-center gap-4">
                        <dt class="text-slate-500 font-medium">Edificio</dt>
                        <dd class="font-bold text-slate-900 text-right">{{ $elevator->location->building_name ?? $elevator->building_name ?? 'N/D' }}</dd>
                    </div>
                    <div class="py-3 flex justify-between items-center gap-4">
                        <dt class="text-slate-500 font-medium">Dirección</dt>
                        <dd class="font-bold text-slate-900 text-right">{{ $elevator->location->address ?? $elevator->address ?? 'N/D' }}</dd>
                    </div>
                    <div class="py-3 flex justify-between items-center gap-4">
                        <dt class="text-slate-500 font-medium">Próxima Inspección ITE</dt>
                        <dd class="font-mono-tech font-bold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg text-xs">
                            {{ $elevator->next_inspection_date ? \Carbon\Carbon::parse($elevator->next_inspection_date)->format('d/m/Y') : 'N/D' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    @endif

</div>