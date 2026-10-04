<div>
    <!-- CARGA DE APEXCHARTS CON DIRECTIVA NATIVA DE LIVEWIRE 3 -->
    @assets
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endassets

    <div class="p-6 space-y-6 bg-slate-50 min-h-screen">

        <!-- ENCABEZADO -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Panel de Control & Analíticas</h2>
                <p class="text-sm text-slate-500 mt-1">Resumen general del estado del parque de ascensores y órdenes de trabajo.</p>
            </div>
            <div>
                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                    Panel Activo
                </span>
            </div>
        </div>

        <!-- TARJETAS KPI -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Órdenes Totales</p>
                    <h3 class="text-3xl font-extrabold text-slate-800 mt-2">{{ $totalWorkOrders ?? 0 }}</h3>
                    <span class="inline-flex items-center text-xs font-medium text-emerald-600 mt-1">+12% este mes</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pendientes</p>
                    <h3 class="text-3xl font-extrabold text-slate-800 mt-2">{{ $pendingOrders ?? 0 }}</h3>
                    <span class="inline-flex items-center text-xs font-medium text-amber-600 mt-1">Atención requerida</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Completadas</p>
                    <h3 class="text-3xl font-extrabold text-slate-800 mt-2">{{ $completedOrders ?? 0 }}</h3>
                    <span class="inline-flex items-center text-xs font-medium text-emerald-600 mt-1">Eficiencia 95%</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Ascensores</p>
                    <h3 class="text-3xl font-extrabold text-slate-800 mt-2">{{ $totalElevators ?? 0 }}</h3>
                    <span class="inline-flex items-center text-xs font-medium text-slate-500 mt-1">Parque activo</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
        </div>

        <!-- SECCIÓN DE GRÁFICOS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- GRÁFICO DE ÁREA -->
            <div class="lg:col-span-2 bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
                <div class="mb-4">
                    <h3 class="font-bold text-slate-800 text-lg">Evolución de Intervenciones</h3>
                    <p class="text-xs text-slate-500">Mantenimientos preventivos vs. reparaciones correctivas</p>
                </div>
                <div wire:ignore id="area-chart" style="min-height: 320px; width: 100%;"></div>
            </div>

            <!-- GRÁFICO DE DONA -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div class="mb-4">
                    <h3 class="font-bold text-slate-800 text-lg">Estado de la Flota</h3>
                    <p class="text-xs text-slate-500">Distribución de los ascensores</p>
                </div>
                <div wire:ignore id="donut-chart" style="min-height: 300px; width: 100%;"></div>
            </div>

        </div>

    </div>

    <!-- SCRIPT DE INICIALIZACIÓN -->
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
                chart: { type: 'area', height: 320, toolbar: { show: false } },
                stroke: { curve: 'smooth', width: 3 },
                series: [
                    { name: 'Preventivo', data: preventive },
                    { name: 'Correctivo', data: corrective }
                ],
                xaxis: { categories: months },
                colors: ['#2563EB', '#EF4444'],
                fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } }
            });
            areaChartInstance.render();

            donutChartInstance = new ApexCharts(donutEl, {
                chart: { type: 'donut', height: 300 },
                series: distribution,
                labels: ['Operativos', 'Mantenimiento', 'Fuera de Servicio'],
                colors: ['#10B981', '#F59E0B', '#EF4444'],
                legend: { position: 'bottom' }
            });
            donutChartInstance.render();
        }

        initCharts();
    </script>
    @endscript
</div>