<div>
    <!-- CARGA DE APEXCHARTS CON DIRECTIVA NATIVA DE LIVEWIRE 3 -->
    @assets
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endassets

    <div class="max-w-7xl mx-auto space-y-6 pb-20">

        <!-- CABECERA EJECUTIVA DE TELEMETRÍA -->
        <div class="relative overflow-hidden rounded-3xl bg-[#070b14] p-6 sm:p-8 text-white shadow-xl border border-white/10">
            <div class="pointer-events-none absolute -top-24 -right-24 w-80 h-80 rounded-full bg-cyan-500/15 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 left-1/3 w-80 h-80 rounded-full bg-blue-600/15 blur-3xl"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-1.5">
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-cyan-500/10 border border-cyan-400/20 text-cyan-300 font-mono-tech text-[11px] uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                        Telemetría & Rendimiento
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                        Panel de Control & Analíticas
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-400">
                        Indicadores clave de disponibilidad del parque de ascensores y evolución operativa de órdenes de trabajo.
                    </p>
                </div>

                <div class="self-start sm:self-auto">
                    <span class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-mono-tech font-bold rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-400/25">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                        </span>
                        PANEL ACTIVO
                    </span>
                </div>
            </div>
        </div>

        <!-- TARJETAS KPI -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">

            <!-- KPI 1: Órdenes Totales -->
            <div class="group relative overflow-hidden bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-blue-600 to-cyan-400"></div>
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-mono-tech text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">
                            Órdenes Totales
                        </p>
                        <h3 class="text-3xl sm:text-4xl font-extrabold font-mono-tech text-slate-900 mt-2 tracking-tight">
                            {{ $totalWorkOrders ?? 0 }}
                        </h3>
                        <div class="mt-2.5 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-mono-tech text-[11px] font-bold">
                            <span>+{{ $thisMonthOrders }} este mes</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- KPI 2: Pendientes -->
            <div class="group relative overflow-hidden bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-amber-500 to-orange-400"></div>
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-mono-tech text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">
                            Pendientes
                        </p>
                        <h3 class="text-3xl sm:text-4xl font-extrabold font-mono-tech text-slate-900 mt-2 tracking-tight">
                            {{ $pendingOrders ?? 0 }}
                        </h3>
                        <div class="mt-2.5 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-amber-50 text-amber-700 font-mono-tech text-[11px] font-bold">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            <span>Atención requerida</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- KPI 3: Completadas -->
            <div class="group relative overflow-hidden bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-400"></div>
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-mono-tech text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">
                            Completadas
                        </p>
                        <h3 class="text-3xl sm:text-4xl font-extrabold font-mono-tech text-slate-900 mt-2 tracking-tight">
                            {{ $completedOrders ?? 0 }}
                        </h3>
                        <div class="mt-2.5 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 font-mono-tech text-[11px] font-bold">
                            <span>Resolución {{ $completionRate }}%</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- KPI 4: Ascensores -->
            <div class="group relative overflow-hidden bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-slate-800 to-slate-600"></div>
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-mono-tech text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">
                            Ascensores
                        </p>
                        <h3 class="text-3xl sm:text-4xl font-extrabold font-mono-tech text-slate-900 mt-2 tracking-tight">
                            {{ $totalElevators ?? 0 }}
                        </h3>
                        <div class="mt-2.5 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-700 font-mono-tech text-[11px] font-bold">
                            <span>Parque activo</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-slate-900 text-cyan-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        <!-- SECCIÓN DE GRÁFICOS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- GRÁFICO DE ÁREA: EVOLUCIÓN -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-5 sm:p-7 border border-slate-200/80 shadow-sm flex flex-col justify-between overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-5 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base sm:text-lg">Evolución de Intervenciones</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Comparativa mensual: mantenimientos preventivos vs. reparaciones correctivas</p>
                    </div>
                    <span class="font-mono-tech text-[11px] font-bold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 self-start sm:self-auto">
                        HISTÓRICO MENSUAL
                    </span>
                </div>
                <div class="w-full overflow-hidden">
                    <div wire:ignore id="area-chart" class="w-full min-h-[300px] sm:min-h-[320px]"></div>
                </div>
            </div>

            <!-- GRÁFICO DE DONA: FLOTA -->
            <div class="bg-white rounded-3xl p-5 sm:p-7 border border-slate-200/80 shadow-sm flex flex-col justify-between overflow-hidden">
                <div class="mb-5 pb-4 border-b border-slate-100">
                    <h3 class="font-extrabold text-slate-900 text-base sm:text-lg">Estado de la Flota</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Distribución operativa del parque instalado</p>
                </div>
                <div class="w-full overflow-hidden my-auto">
                    <div wire:ignore id="donut-chart" class="w-full min-h-[280px] sm:min-h-[300px]"></div>
                </div>
            </div>

        </div>

    </div>

    <!-- SCRIPT DE INICIALIZACIÓN DE GRÁFICAS -->
    @script
    <script>
        let areaChartInstance = null;
        let donutChartInstance = null;

        function initCharts() {
            const areaEl = document.getElementById('area-chart');
            const donutEl = document.getElementById('donut-chart');

            if (!areaEl || !donutEl || typeof ApexCharts === 'undefined') return;

            const months = @js($chartMonths);
            const preventive = @js($preventiveData);
            const corrective = @js($correctiveData);
            const distribution = @js($elevatorDistribution);

            if (areaChartInstance) areaChartInstance.destroy();
            if (donutChartInstance) donutChartInstance.destroy();

            areaChartInstance = new ApexCharts(areaEl, {
                chart: {
                    type: 'area',
                    height: 320,
                    fontFamily: 'Plus Jakarta Sans, sans-serif',
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                series: [
                    { name: 'Preventivo', data: preventive },
                    { name: 'Correctivo', data: corrective }
                ],
                xaxis: {
                    categories: months,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#64748b', fontSize: '12px', fontWeight: 600 } }
                },
                yaxis: {
                    labels: { style: { colors: '#64748b', fontSize: '12px', fontFamily: 'JetBrains Mono, monospace' } }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4
                },
                colors: ['#0284c7', '#f43f5e'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.32,
                        opacityTo: 0.04,
                        stops: [0, 95, 100]
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    fontWeight: 600,
                    markers: { radius: 12 }
                }
            });
            areaChartInstance.render();

            donutChartInstance = new ApexCharts(donutEl, {
                chart: {
                    type: 'donut',
                    height: 300,
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                series: distribution,
                labels: ['Operativos', 'Mantenimiento', 'Fuera de Servicio'],
                colors: ['#10b981', '#f59e0b', '#f43f5e'],
                stroke: { width: 3, colors: ['#ffffff'] },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '72%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'TOTAL FLOTA',
                                    fontSize: '11px',
                                    fontFamily: 'JetBrains Mono, monospace',
                                    color: '#64748b',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: { enabled: false },
                legend: {
                    position: 'bottom',
                    fontWeight: 600,
                    markers: { radius: 12 }
                }
            });
            donutChartInstance.render();
        }

        initCharts();
    </script>
    @endscript
</div>