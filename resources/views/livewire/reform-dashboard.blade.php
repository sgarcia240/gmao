<div class="max-w-7xl mx-auto space-y-6 pb-24">

    <!-- CABECERA EJECUTIVA DE ALTO IMPACTO -->
    <div class="relative overflow-hidden rounded-3xl bg-[#070b14] p-6 sm:p-8 text-white shadow-xl border border-white/10">
        <!-- Destellos ambientales -->
        <div class="pointer-events-none absolute -top-24 -right-24 w-80 h-80 rounded-full bg-cyan-500/15 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 left-1/3 w-80 h-80 rounded-full bg-blue-600/15 blur-3xl"></div>

        <div class="relative z-10 flex flex-col sm:flex-row justify-between sm:items-center gap-5">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-cyan-500/10 border border-cyan-400/20 text-cyan-300 font-mono-tech text-[11px] uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                    Gestión de Campo & Acopio
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Mis Obras y Reformas
                </h1>
                <p class="text-xs sm:text-sm text-slate-400">
                    Toma de datos técnicos en planta, evidencia fotográfica, comparativa de ofertas y control de material.
                </p>
            </div>

            <button wire:click="$set('showNewModal', true)"
                    class="w-full sm:w-auto px-5 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-[0.99] text-white text-sm font-bold rounded-2xl shadow-lg shadow-cyan-500/20 transition flex items-center justify-center gap-2.5 cursor-pointer shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Nueva Toma de Datos / Obra</span>
            </button>
        </div>
    </div>

    <!-- NOTIFICACIÓN DE ESTADO -->
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

    <!-- MODAL NUEVA COMUNIDAD / ASCENSOR -->
    @if($showNewModal)
        <div class="fixed inset-0 z-50 bg-slate-950/75 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4">
            <div class="bg-white w-full max-w-md p-6 sm:p-7 rounded-t-3xl sm:rounded-3xl shadow-2xl border border-slate-200/80 space-y-5">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">Nueva Comunidad / Ascensor</h3>
                            <p class="text-xs text-slate-400">Alta rápida de expediente de reforma</p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('showNewModal', false)" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="createNewWorkOrder" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Dirección de la Comunidad *</label>
                        <input type="text" wire:model="newAddress" placeholder="Ej. Calle Diagonal 215"
                               class="w-full px-4 py-3 text-base rounded-xl border border-slate-200 bg-slate-50/70 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Número RAE (Opcional)</label>
                        <input type="text" wire:model="newRae" placeholder="Ej. RAE26425"
                               class="w-full px-4 py-3 text-base font-mono-tech rounded-xl border border-slate-200 bg-slate-50/70 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                    </div>
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <button type="button" wire:click="$set('showNewModal', false)"
                                class="py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="py-3.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-600/20 transition">
                            Crear Obra
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- SELECTOR RÁPIDO PARA MÓVIL -->
    <div class="lg:hidden bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
        <div class="flex items-center justify-between gap-2">
            <label class="font-mono-tech text-[11px] font-bold uppercase tracking-wider text-slate-500">
                Obra Activa ({{ $activeOts->count() }})
            </label>
            <input type="text" wire:model.live.debounce.300ms="searchOt" placeholder="Filtrar calle o RAE..."
                   class="w-40 px-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
        </div>
        <select wire:change="selectOt($event.target.value)"
                class="w-full px-4 py-3 text-sm font-bold text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">
            @forelse($activeOts as $ot)
                <option value="{{ $ot->id }}" {{ $selectedOtId == $ot->id ? 'selected' : '' }}>
                    OT #{{ $ot->id }} — {{ $ot->elevator->location->address ?? $ot->elevator->location->name ?? 'Sin dirección' }} ({{ $ot->material_progress }}%)
                </option>
            @empty
                <option value="">No hay obras creadas</option>
            @endforelse
        </select>
    </div>

    <!-- LAYOUT PRINCIPAL EN DOS COLUMNAS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- COLUMNA LATERAL DE EXPEDIENTES (Desktop) -->
        <div class="hidden lg:block lg:col-span-4 bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
            <div class="relative">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text"
                       wire:model.live.debounce.300ms="searchOt"
                       placeholder="Filtrar por calle o RAE..."
                       class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50/80 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
            </div>

            <div class="flex items-center justify-between px-1">
                <h2 class="font-mono-tech text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    Obras Activas
                </h2>
                <span class="font-mono-tech text-xs font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-700">
                    {{ $activeOts->count() }}
                </span>
            </div>

            <div class="space-y-2.5 max-h-[68vh] overflow-y-auto pr-1">
                @forelse($activeOts as $ot)
                    @php $progress = $ot->material_progress; @endphp
                    <button wire:click="selectOt({{ $ot->id }})"
                            class="group w-full text-left p-4 rounded-2xl border transition-all duration-200 cursor-pointer {{ $selectedOtId === $ot->id ? 'bg-gradient-to-br from-blue-50/90 to-cyan-50/40 border-blue-500/60 shadow-sm ring-1 ring-blue-500/30' : 'bg-white border-slate-200/80 hover:border-slate-300 hover:bg-slate-50/60' }}">
                        <div class="flex justify-between items-start gap-2.5">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-mono-tech text-[11px] font-bold px-2 py-0.5 rounded-md {{ $selectedOtId === $ot->id ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-slate-200/80' }}">
                                        OT #{{ $ot->id }}
                                    </span>
                                    @if($ot->elevator->rae_code ?? null)
                                        <span class="font-mono-tech text-[11px] text-slate-400 truncate">{{ $ot->elevator->rae_code }}</span>
                                    @endif
                                </div>
                                <h3 class="font-bold text-sm text-slate-900 truncate">
                                    {{ $ot->elevator->location->address ?? $ot->elevator->location->name ?? 'Sin dirección' }}
                                </h3>
                            </div>
                            <span class="font-mono-tech px-2.5 py-1 text-xs font-bold rounded-full shrink-0 {{ $progress === 100 ? 'bg-emerald-500/10 text-emerald-700 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-700 border border-amber-500/20' }}">
                                {{ $progress }}%
                            </span>
                        </div>

                        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3 overflow-hidden">
                            <div class="h-1.5 rounded-full transition-all duration-500 {{ $progress === 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-blue-600 to-cyan-500' }}"
                                 style="width: {{ max($progress, 4) }}%"></div>
                        </div>
                    </button>
                @empty
                    <div class="text-center py-10 px-4 rounded-2xl border border-dashed border-slate-200">
                        <p class="text-sm font-medium text-slate-400">No hay obras registradas.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- PANEL CENTRAL DE LA OBRA ACTIVA -->
        @if($currentOt)
            @php $currentProgress = $currentOt->material_progress; @endphp
            <div class="lg:col-span-8 space-y-5">

                <!-- TARJETA ESTADO Y NAVEGACIÓN EN 3 PASOS -->
                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-sm">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-1.5">
                                <span class="font-mono-tech text-xs font-bold px-2.5 py-0.5 rounded-lg bg-slate-900 text-cyan-400">
                                    OT #{{ $currentOt->id }}
                                </span>
                                <span class="font-mono-tech text-xs font-medium px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-600">
                                    RAE: {{ $currentOt->elevator->rae_code ?? 'S/N' }}
                                </span>
                            </div>
                            <h2 class="text-lg sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-snug">
                                {{ $currentOt->elevator->location->address ?? $currentOt->elevator->location->name ?? 'Comunidad' }}
                            </h2>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                            <a href="/work-orders/{{ $currentOt->id }}/pdf/stream" target="_blank"
                               class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                <span>Informe PDF</span>
                            </a>

                            <div class="text-right">
                                <span class="block font-mono-tech text-[10px] uppercase tracking-wider text-slate-400">Acopio</span>
                                <span class="text-2xl font-black tracking-tight {{ $currentProgress === 100 ? 'text-emerald-600' : 'text-blue-600' }}">
                                    {{ $currentProgress }}%
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Barra Progreso Material -->
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden mb-5">
                        <div class="h-2.5 rounded-full transition-all duration-500 {{ $currentProgress === 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-blue-600 to-cyan-500' }}"
                             style="width: {{ max($currentProgress, 4) }}%"></div>
                    </div>

                    <!-- Pestañas de Flujo en 3 Pasos -->
                    <div class="grid grid-cols-3 gap-2 p-1.5 bg-slate-100/90 rounded-2xl border border-slate-200/60">
                        <button wire:click="$set('activeSection', 'survey')"
                                class="py-3 px-2 text-xs sm:text-sm font-bold rounded-xl transition-all flex flex-col sm:flex-row items-center justify-center gap-1.5 cursor-pointer {{ $activeSection === 'survey' ? 'bg-white text-blue-600 shadow-sm ring-1 ring-slate-200/70' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                            </svg>
                            <span>1. Datos</span>
                        </button>

                        <button wire:click="$set('activeSection', 'photos_quotes')"
                                class="py-3 px-2 text-xs sm:text-sm font-bold rounded-xl transition-all flex flex-col sm:flex-row items-center justify-center gap-1.5 cursor-pointer {{ $activeSection === 'photos_quotes' ? 'bg-white text-blue-600 shadow-sm ring-1 ring-slate-200/70' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                            </svg>
                            <span>2. Fotos ({{ $currentOt->photos->count() }})</span>
                        </button>

                        <button wire:click="$set('activeSection', 'material')"
                                class="py-3 px-2 text-xs sm:text-sm font-bold rounded-xl transition-all flex flex-col sm:flex-row items-center justify-center gap-1.5 cursor-pointer {{ $activeSection === 'material' ? 'bg-white text-blue-600 shadow-sm ring-1 ring-slate-200/70' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            <span>3. Material</span>
                        </button>
                    </div>
                </div>

                <!-- PASO 1: TOMA DE DATOS TÉCNICA -->
                @if($activeSection === 'survey')
                    <div class="bg-white p-5 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm">
                        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-mono-tech font-bold text-sm">
                                01
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900">Toma de Datos Técnica</h3>
                                <p class="text-xs text-slate-400">Especificaciones mecánicas y eléctricas de la instalación</p>
                            </div>
                        </div>

                        <form wire:submit.prevent="saveSurvey" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Paradas / Alturas</label>
                                <input type="number" inputmode="numeric" wire:model="stops" placeholder="Ej. 6"
                                       class="w-full px-4 py-3 text-base rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Tipo de Máquina</label>
                                <input type="text" wire:model="machineType" placeholder="Ej. Gearless / 2 vel..."
                                       class="w-full px-4 py-3 text-base rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Cuadro de Maniobra</label>
                                <input type="text" wire:model="controllerType" placeholder="Ej. Relés / Carlos Silva..."
                                       class="w-full px-4 py-3 text-base rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Tipo de Puertas</label>
                                <input type="text" wire:model="doorType" placeholder="Ej. Semiautomáticas / Automáticas..."
                                       class="w-full px-4 py-3 text-base rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Botoneras</label>
                                <input type="text" wire:model="pushButtonsType" placeholder="Ej. Superficie / Empotradas..."
                                       class="w-full px-4 py-3 text-base rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Observaciones (Hueco, Foso, DTU)</label>
                                <textarea wire:model="technicalNotes" rows="3" placeholder="Detalles de la instalación..."
                                          class="w-full px-4 py-3 text-base rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition"></textarea>
                            </div>
                            <div class="sm:col-span-2 pt-2">
                                <button type="submit"
                                        class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white text-sm font-bold rounded-xl shadow-md shadow-blue-600/20 transition flex items-center justify-center gap-2 cursor-pointer">
                                    <span>Guardar y Pasar a Fotos / Ofertas</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- PASO 2: FOTOS DIRECTAS Y OFERTAS -->
                @if($activeSection === 'photos_quotes')
                    <!-- Cámara y Galería -->
                    <div class="bg-white p-5 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm">
                        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                            <div class="w-9 h-9 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center font-mono-tech font-bold text-sm">
                                02A
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900">Fotografías de la OT</h3>
                                <p class="text-xs text-slate-400">Captura máquina, cuadro o placas de características en campo</p>
                            </div>
                        </div>

                        <form wire:submit.prevent="uploadOtPhoto"
                            x-data="{ converting: false, uploading: false }"
                            x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false"
                            x-on:livewire-upload-error="uploading = false"
                            class="space-y-4 bg-slate-50/90 p-4 sm:p-5 rounded-2xl border border-slate-200/80 mb-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">1. Hacer Foto / Elegir</label>
                                    <input type="file"
                                        accept="image/*,.heic,.heif"
                                        required
                                        @change="
                                            async (e) => {
                                                let file = e.target.files[0];
                                                if (!file) return;

                                                const isHeic = file.name.toLowerCase().endsWith('.heic') ||
                                                                file.name.toLowerCase().endsWith('.heif') ||
                                                                file.type === 'image/heic' ||
                                                                file.type === 'image/heif';

                                                if (isHeic && typeof heic2any !== 'undefined') {
                                                    converting = true;
                                                    try {
                                                        const converted = await heic2any({
                                                            blob: file,
                                                            toType: 'image/jpeg',
                                                            quality: 0.85
                                                        });
                                                        const blob = Array.isArray(converted) ? converted[0] : converted;
                                                        const jpgName = file.name.replace(/\.(heic|heif)$/i, '.jpg');
                                                        file = new File([blob], jpgName, { type: 'image/jpeg' });
                                                    } catch (err) {
                                                        console.error('Error convirtiendo HEIC:', err);
                                                    } finally {
                                                        converting = false;
                                                    }
                                                }

                                                $wire.upload('otPhoto', file);
                                            }
                                        "
                                        class="w-full text-sm text-slate-600 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">

                                    <!-- Indicador de conversión HEIC -> JPG -->
                                    <div x-show="converting" x-cloak class="mt-2 text-xs font-semibold text-amber-600 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                                        Optimizando formato Apple (.HEIC) a JPG...
                                    </div>

                                    <!-- Indicador de subida a Livewire -->
                                    <div x-show="uploading" x-cloak class="mt-2 text-xs font-semibold text-blue-600 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-ping"></span>
                                        Cargando fotografía...
                                    </div>

                                    @error('otPhoto')
                                        <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">2. ¿Qué elemento es?</label>
                                    <select wire:model="photoTag"
                                            class="w-full px-3.5 py-2.5 text-base rounded-xl border border-slate-200 bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">
                                        <option value="Máquina">Máquina</option>
                                        <option value="Cuadro Maniobra">Cuadro Maniobra</option>
                                        <option value="Botoneras">Botoneras</option>
                                        <option value="Puertas">Puertas</option>
                                        <option value="DTU / Cuadro Eléctrico">DTU / Cuadro Eléctrico</option>
                                        <option value="Foso / Hueco">Foso / Hueco</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <input type="text" wire:model="photoNotes" placeholder="Nota opcional (Ej. Placa motor, medidas...)"
                                    class="w-full px-4 py-2.5 text-base rounded-xl border border-slate-200 bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">
                            </div>

                            <button type="submit"
                                    :disabled="converting || uploading"
                                    :class="(converting || uploading) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700 active:scale-[0.99] cursor-pointer'"
                                    class="w-full py-3.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-600/20 transition">
                                Subir Fotografía a la OT
                            </button>
                        </form>

                        <!-- Cuadrícula de Fotos -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5">
                            @forelse($currentOt->photos as $photo)
                                <div class="group rounded-2xl overflow-hidden border border-slate-200/90 bg-white shadow-xs hover:shadow-md transition">
                                    <a href="{{ asset('storage/' . $photo->photo_path) }}" target="_blank" class="block overflow-hidden">
                                        <img src="{{ asset('storage/' . $photo->photo_path) }}" class="w-full h-36 object-cover group-hover:scale-105 transition duration-300">
                                    </a>
                                    <div class="p-3 flex justify-between items-center bg-white">
                                        <div class="truncate pr-2">
                                            <span class="font-mono-tech text-[10px] font-bold uppercase text-blue-600 block">{{ $photo->component_tag }}</span>
                                            <span class="text-xs text-slate-600 truncate block">{{ $photo->notes ?: 'Sin nota' }}</span>
                                        </div>
                                        <button wire:click="deletePhoto({{ $photo->id }})"
                                                class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 transition shrink-0" title="Eliminar foto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 sm:col-span-3 text-center py-8 rounded-2xl border border-dashed border-slate-200">
                                    <p class="text-xs font-medium text-slate-400">Sin fotografías adjuntas en esta OT.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Ofertas de Proveedores -->
                    <div class="bg-white p-5 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm">
                        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-mono-tech font-bold text-sm">
                                02B
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900">Ofertas de Proveedores</h3>
                                <p class="text-xs text-slate-400">Registra presupuestos recibidos y adjudica la oferta ganadora</p>
                            </div>
                        </div>

                        <form wire:submit.prevent="addQuote"
                            x-data="{ convertingQuote: false, uploadingQuote: false }"
                            x-on:livewire-upload-start="uploadingQuote = true"
                            x-on:livewire-upload-finish="uploadingQuote = false"
                            x-on:livewire-upload-error="uploadingQuote = false"
                            class="space-y-4 bg-slate-50/90 p-4 sm:p-5 rounded-2xl border border-slate-200/80 mb-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <input type="text" wire:model="quoteProvider" placeholder="Proveedor (Ej. Wittur)"
                                    class="w-full px-4 py-3 text-base rounded-xl border border-slate-200 bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20" required>
                                <input type="number" step="0.01" inputmode="decimal" wire:model="quoteAmount" placeholder="Importe (€)"
                                    class="w-full px-4 py-3 text-base font-mono-tech rounded-xl border border-slate-200 bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20" required>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Adjuntar PDF o foto del presupuesto (Opcional)</label>
                                <input type="file"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.heic,.heif,image/*"
                                    @change="
                                        async (e) => {
                                            let file = e.target.files[0];
                                            if (!file) return;

                                            const isHeic = file.name.toLowerCase().endsWith('.heic') ||
                                                            file.name.toLowerCase().endsWith('.heif') ||
                                                            file.type === 'image/heic' ||
                                                            file.type === 'image/heif';

                                            if (isHeic && typeof heic2any !== 'undefined') {
                                                convertingQuote = true;
                                                try {
                                                    const converted = await heic2any({
                                                        blob: file,
                                                        toType: 'image/jpeg',
                                                        quality: 0.85
                                                    });
                                                    const blob = Array.isArray(converted) ? converted[0] : converted;
                                                    const jpgName = file.name.replace(/\.(heic|heif)$/i, '.jpg');
                                                    file = new File([blob], jpgName, { type: 'image/jpeg' });
                                                } catch (err) {
                                                    console.error('Error convirtiendo HEIC en oferta:', err);
                                                } finally {
                                                    convertingQuote = false;
                                                }
                                            }

                                            $wire.upload('quoteFile', file);
                                        }
                                    "
                                    class="w-full text-sm text-slate-600 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">

                                <!-- Indicador de conversión HEIC -> JPG -->
                                <div x-show="convertingQuote" x-cloak class="mt-2 text-xs font-semibold text-amber-600 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                                    Optimizando foto del presupuesto (.HEIC) a JPG...
                                </div>

                                <!-- Indicador de subida a Livewire -->
                                <div x-show="uploadingQuote" x-cloak class="mt-2 text-xs font-semibold text-blue-600 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-blue-600 animate-ping"></span>
                                    Subiendo documento, espera un segundo antes de guardar...
                                </div>

                                @error('quoteFile')
                                    <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                    :disabled="convertingQuote || uploadingQuote"
                                    :class="(convertingQuote || uploadingQuote) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-indigo-700 active:scale-[0.99] cursor-pointer'"
                                    class="w-full py-3.5 bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-md shadow-indigo-600/20 transition">
                                + Guardar Oferta
                            </button>
                        </form>

                        <div class="space-y-3">
                            @forelse($currentOt->quotes as $quote)
                                <div class="p-4 rounded-2xl border flex flex-col sm:flex-row justify-between sm:items-center gap-3 transition {{ $quote->is_selected ? 'bg-emerald-50/70 border-emerald-500/60 shadow-xs' : 'bg-white border-slate-200/90' }}">
                                    <div>
                                        <div class="flex items-center gap-2.5 flex-wrap">
                                            <span class="font-extrabold text-sm text-slate-900">{{ $quote->provider_name }}</span>
                                            <span class="font-mono-tech font-bold text-sm px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700">
                                                {{ number_format($quote->amount, 2) }} €
                                            </span>
                                            @if($quote->is_selected)
                                                <span class="px-2.5 py-0.5 bg-emerald-600 text-white font-mono-tech text-[10px] font-bold rounded-full uppercase tracking-wider">
                                                    ✓ Adjudicado
                                                </span>
                                            @endif
                                        </div>

                                        @if($quote->file_path)
                                            <div class="flex items-center gap-3 mt-2">
                                                <a href="{{ asset('storage/' . $quote->file_path) }}" target="_blank"
                                                   class="inline-flex items-center gap-1.5 text-xs text-indigo-600 hover:text-indigo-800 font-bold">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                    </svg>
                                                    Ver presupuesto adjunto
                                                </a>

                                                <button type="button"
                                                        wire:click="confirmDeleteQuoteFile({{ $quote->id }})"
                                                        title="Eliminar documento adjunto"
                                                        class="inline-flex items-center gap-1 text-xs font-semibold text-rose-500 hover:text-rose-700 transition">
                                                    <span>Quitar archivo</span>
                                                </button>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">
                                        @if(!$quote->is_selected)
                                            <button wire:click="selectWinningQuote({{ $quote->id }})"
                                                    class="flex-1 sm:flex-initial px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition cursor-pointer">
                                                Seleccionar Oferta
                                            </button>
                                        @endif

                                        <button type="button"
                                                wire:click="confirmDeleteQuote({{ $quote->id }})"
                                                title="Eliminar oferta"
                                                aria-label="Eliminar oferta"
                                                class="p-2.5 text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 rounded-xl transition cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 rounded-2xl border border-dashed border-slate-200">
                                    <p class="text-xs font-medium text-slate-400">Sin ofertas cargadas todavía.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif

                <!-- PASO 3: CHECKLIST PARTIDAS, ACOPIO Y MONTAJE -->
                @if($activeSection === 'material')
                    <div class="bg-white p-5 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm">
                        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                            <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-mono-tech font-bold text-sm">
                                03
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900">Partidas a Cambiar y Acopio</h3>
                                <p class="text-xs text-slate-400">Marca las partidas incluidas y confirma la recepción en almacén u obra</p>
                            </div>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @foreach($currentOt->reformItems as $item)
                                <div class="py-3.5 flex items-center justify-between gap-3">
                                    <label class="flex items-center gap-3.5 cursor-pointer flex-1 py-1">
                                        <input type="checkbox" wire:click="toggleSelected({{ $item->id }})" {{ $item->is_selected ? 'checked' : '' }}
                                               class="w-5 h-5 text-blue-600 rounded-lg border-slate-300 focus:ring-blue-600/25 shrink-0">
                                        <span class="text-sm transition {{ $item->is_selected ? 'font-bold text-slate-900' : 'text-slate-400 line-through' }}">
                                            {{ $item->component_name }}
                                        </span>
                                    </label>

                                    @if($item->is_selected)
                                        <button wire:click="toggleReceived({{ $item->id }})"
                                                class="px-3.5 py-2 rounded-xl text-xs font-bold transition shrink-0 cursor-pointer {{ $item->material_received ? 'bg-emerald-500/10 text-emerald-700 border border-emerald-500/30' : 'bg-amber-500/10 text-amber-700 border border-amber-500/30' }}">
                                            {{ $item->material_received ? '✓ Recibido' : '⏳ Pendiente' }}
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white p-5 sm:p-7 rounded-3xl border {{ $currentProgress === 100 ? 'border-emerald-500/60 ring-1 ring-emerald-500/30' : 'border-slate-200/80' }} shadow-sm">
                        <div class="flex flex-wrap justify-between items-center gap-2 mb-5 pb-4 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900">Planificación de Montaje</h3>
                                <p class="text-xs text-slate-400">Asignación de equipo montador y fecha de arranque</p>
                            </div>
                            @if($currentOt->budget_provider)
                                <span class="font-mono-tech text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-xl">
                                    {{ $currentOt->budget_provider }} · {{ number_format($currentOt->accepted_budget_amount, 2) }} €
                                </span>
                            @endif
                        </div>

                        <form wire:submit.prevent="savePlanning" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Montador / Operario</label>
                                <input type="text" wire:model="installerName" placeholder="Nombre del montador"
                                       class="w-full px-4 py-3 text-base rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Fecha Inicio Obra</label>
                                <input type="date" wire:model="scheduledDate"
                                       class="w-full px-4 py-3 text-base rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20">
                            </div>
                            <div class="sm:col-span-2 pt-2">
                                <button type="submit"
                                        class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] text-white text-sm font-bold rounded-xl shadow-md shadow-emerald-600/20 transition cursor-pointer">
                                    Guardar Planificación
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

            </div>
        @endif
    </div>

    <!-- MODAL PERSONALIZADO DE BORRADO DE OFERTA / DOCUMENTO -->
    @if($showDeleteQuoteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-200/80 p-6 sm:p-7">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-rose-50 text-rose-600 border border-rose-200/80 rounded-2xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">
                            {{ $quoteDeleteMode === 'file' ? 'Eliminar documento adjunto' : 'Eliminar oferta de proveedor' }}
                        </h3>
                        @if($quoteDeleteMode === 'file')
                            <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                                ¿Estás seguro de que quieres eliminar el documento adjunto de la oferta de <strong class="text-slate-900">{{ $quoteToDeleteLabel }}</strong>? Los datos económicos de la oferta se conservarán.
                            </p>
                        @else
                            <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                                ¿Estás seguro de que quieres eliminar de forma permanente la oferta de <strong class="text-slate-900">{{ $quoteToDeleteLabel }}</strong> junto con su documento adjunto?
                            </p>
                        @endif
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button"
                            wire:click="cancelDeleteQuoteModal"
                            class="px-4 py-2.5 text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                        Cancelar
                    </button>
                    <button type="button"
                            wire:click="executeQuoteDeletion"
                            wire:loading.attr="disabled"
                            class="px-4 py-2.5 text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md shadow-rose-600/20 transition active:scale-95">
                        {{ $quoteDeleteMode === 'file' ? 'Sí, eliminar documento' : 'Sí, eliminar oferta' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>