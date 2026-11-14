<div class="max-w-7xl mx-auto space-y-4 pb-24">
    
    <!-- Cabecera Compacta Móvil -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row justify-between sm:items-center gap-3">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 leading-tight">Mis Obras y Reformas</h1>
            <p class="text-xs text-slate-500">Toma de datos, fotos, ofertas y material</p>
        </div>
        <button wire:click="$set('showNewModal', true)" 
                class="w-full sm:w-auto px-4 py-3.5 bg-blue-600 active:bg-blue-800 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm transition flex items-center justify-center gap-2">
            <span class="text-lg leading-none">+</span>
            <span>Nueva Toma de Datos / Obra</span>
        </button>
    </div>

    @if (session()->has('message'))
        <div class="p-3.5 bg-emerald-50 border border-emerald-300 text-emerald-800 rounded-xl text-sm font-semibold">
            ✓ {{ session('message') }}
        </div>
    @endif

    <!-- Modal Nueva Comunidad (Adaptado a teclado móvil) -->
    @if($showNewModal)
        <div class="fixed inset-0 z-50 bg-slate-900/75 flex items-end sm:items-center justify-center p-0 sm:p-4">
            <div class="bg-white w-full max-w-md p-5 sm:p-6 rounded-t-3xl sm:rounded-2xl shadow-2xl border border-slate-200 space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-900">Nueva Comunidad / Ascensor</h3>
                    <button type="button" wire:click="$set('showNewModal', false)" class="p-2 text-slate-400 hover:text-slate-700 text-xl">✕</button>
                </div>
                <form wire:submit.prevent="createNewWorkOrder" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Dirección de la Comunidad *</label>
                        <input type="text" wire:model="newAddress" placeholder="Ej. Calle Diagonal 215" class="w-full px-3.5 py-3 text-base rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Número RAE (Opcional)</label>
                        <input type="text" wire:model="newRae" placeholder="Ej. RAE26425" class="w-full px-3.5 py-3 text-base rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <button type="button" wire:click="$set('showNewModal', false)" class="py-3.5 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl">Cancelar</button>
                        <button type="submit" class="py-3.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-sm">Crear Obra</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- SELECTOR RÁPIDO PARA MÓVIL (Evita tener que hacer scroll en una lista larga) -->
    <div class="lg:hidden bg-white p-3.5 rounded-2xl border border-slate-200 shadow-sm space-y-2.5">
        <div class="flex items-center justify-between">
            <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Obra Seleccionada ({{ $activeOts->count() }})</label>
            <input type="text" wire:model.live.debounce.300ms="searchOt" placeholder="Filtrar..." class="w-36 px-2.5 py-1 text-sm bg-slate-50 border border-slate-300 rounded-lg">
        </div>
        <select wire:change="selectOt($event.target.value)" class="w-full px-3.5 py-3 text-base font-bold text-slate-900 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500">
            @forelse($activeOts as $ot)
                <option value="{{ $ot->id }}" {{ $selectedOtId == $ot->id ? 'selected' : '' }}>
                    OT #{{ $ot->id }} — {{ $ot->elevator->location->address ?? $ot->elevator->location->name ?? 'Sin dirección' }} ({{ $ot->material_progress }}%)
                </option>
            @empty
                <option value="">No hay obras creadas</option>
            @endforelse
        </select>
    </div>

    <!-- Layout Principal -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
        
        <!-- Columna Lateral (Solo visible en Tablet/PC grande) -->
        <div class="hidden lg:block lg:col-span-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-3">
            <input type="text" 
                   wire:model.live.debounce.300ms="searchOt" 
                   placeholder="🔍 Filtrar por calle o RAE..." 
                   class="w-full px-3 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl">

            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500 px-1">Obras Activas ({{ $activeOts->count() }})</h2>

            <div class="space-y-2 max-h-[68vh] overflow-y-auto pr-1">
                @forelse($activeOts as $ot)
                    @php $progress = $ot->material_progress; @endphp
                    <button wire:click="selectOt({{ $ot->id }})" 
                            class="w-full text-left p-3.5 rounded-xl border transition {{ $selectedOtId === $ot->id ? 'bg-blue-50 border-blue-600 ring-1 ring-blue-600' : 'bg-white border-slate-200 hover:border-blue-400' }}">
                        <div class="flex justify-between items-start gap-2">
                            <div class="min-w-0">
                                <span class="text-xs font-mono font-bold text-blue-600">OT #{{ $ot->id }}</span>
                                <h3 class="font-bold text-sm text-slate-900 truncate">{{ $ot->elevator->location->address ?? $ot->elevator->location->name ?? 'Sin dirección' }}</h3>
                            </div>
                            <span class="px-2 py-0.5 text-xs font-bold rounded-full shrink-0 {{ $progress === 100 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $progress }}%
                            </span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-1.5 mt-2.5">
                            <div class="h-1.5 rounded-full {{ $progress === 100 ? 'bg-emerald-600' : 'bg-blue-600' }}" style="width: {{ $progress }}%"></div>
                        </div>
                    </button>
                @empty
                    <p class="text-sm text-slate-400 text-center py-6">No hay obras registradas.</p>
                @endforelse
            </div>
        </div>

        <!-- Panel Central de la Obra Activa -->
        @if($currentOt)
            @php $currentProgress = $currentOt->material_progress; @endphp
            <div class="lg:col-span-8 space-y-4">
                
                <!-- Tarjeta Estado y Navegación Táctil en 3 Pasos -->
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-start gap-2 mb-3">
                        <div class="min-w-0">
                           <span class="text-xs text-slate-500 font-mono block">OT #{{ $currentOt->id }} • RAE: {{ $currentOt->elevator->rae_code ?? 'S/N' }}</span>
                            <h2 class="text-base sm:text-xl font-bold text-slate-900 leading-snug">{{ $currentOt->elevator->location->address ?? $currentOt->elevator->location->name ?? 'Comunidad' }}</h2>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="/work-orders/{{ $currentOt->id }}/pdf/stream" target="_blank" class="px-3 py-2 bg-slate-100 active:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl border border-slate-200">
                                📄 PDF
                            </a>
                            <span class="text-xl sm:text-2xl font-black {{ $currentProgress === 100 ? 'text-emerald-600' : 'text-blue-600' }}">{{ $currentProgress }}%</span>
                        </div>
                    </div>

                    <!-- Barra Progreso Material -->
                    <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden mb-4">
                        <div class="h-2.5 rounded-full transition-all duration-500 {{ $currentProgress === 100 ? 'bg-emerald-600' : 'bg-blue-600' }}" style="width: {{ max($currentProgress, 4) }}%"></div>
                    </div>

                    <!-- Pestañas Grandes para el Pulgar -->
                    <div class="grid grid-cols-3 gap-1.5 p-1.5 bg-slate-100 rounded-xl">
                        <button wire:click="$set('activeSection', 'survey')" 
                                class="py-3 px-1 text-xs sm:text-sm font-bold rounded-lg transition text-center leading-tight {{ $activeSection === 'survey' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600' }}">
                            📐<span class="block sm:inline sm:ml-1">1. Datos</span>
                        </button>
                        <button wire:click="$set('activeSection', 'photos_quotes')" 
                                class="py-3 px-1 text-xs sm:text-sm font-bold rounded-lg transition text-center leading-tight {{ $activeSection === 'photos_quotes' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600' }}">
                            📸<span class="block sm:inline sm:ml-1">2. Fotos ({{ $currentOt->photos->count() }})</span>
                        </button>
                        <button wire:click="$set('activeSection', 'material')" 
                                class="py-3 px-1 text-xs sm:text-sm font-bold rounded-lg transition text-center leading-tight {{ $activeSection === 'material' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600' }}">
                            📦<span class="block sm:inline sm:ml-1">3. Material</span>
                        </button>
                    </div>
                </div>

                <!-- PASO 1: TOMA DE DATOS TÉCNICA (Inputs 16px sin zoom automático) -->
                @if($activeSection === 'survey')
                    <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">Toma de Datos Técnica</h3>
                        <form wire:submit.prevent="saveSurvey" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Paradas / Alturas</label>
                                <input type="number" inputmode="numeric" wire:model="stops" placeholder="Ej. 6" class="w-full px-3.5 py-3 text-base rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Tipo de Máquina</label>
                                <input type="text" wire:model="machineType" placeholder="Ej. Gearless / 2 vel..." class="w-full px-3.5 py-3 text-base rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Cuadro de Maniobra</label>
                                <input type="text" wire:model="controllerType" placeholder="Ej. Relés / Carlos Silva..." class="w-full px-3.5 py-3 text-base rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Tipo de Puertas</label>
                                <input type="text" wire:model="doorType" placeholder="Ej. Semiautomáticas / Automáticas..." class="w-full px-3.5 py-3 text-base rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Botoneras</label>
                                <input type="text" wire:model="pushButtonsType" placeholder="Ej. Superficie / Empotradas..." class="w-full px-3.5 py-3 text-base rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Observaciones (Hueco, Foso, DTU)</label>
                                <textarea wire:model="technicalNotes" rows="3" placeholder="Detalles de la instalación..." class="w-full px-3.5 py-3 text-base rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                            <div class="sm:col-span-2 pt-1">
                                <button type="submit" class="w-full py-3.5 bg-blue-600 active:bg-blue-800 text-white text-sm font-bold rounded-xl shadow-sm transition">
                                    Guardar y Pasar a Fotos / Ofertas →
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- PASO 2: FOTOS DIRECTAS CON CÁMARA Y OFERTAS -->
                @if($activeSection === 'photos_quotes')
                    <!-- Cámara y Galería -->
                    <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="text-base font-bold text-slate-900 mb-1">Fotografías de la OT</h3>
                        <p class="text-xs text-slate-500 mb-4">Haz foto de la máquina, cuadro o placa con la cámara del móvil.</p>

                        <form wire:submit.prevent="uploadOtPhoto" class="space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200 mb-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">1. Hacer Foto / Elegir</label>
                                    <input type="file" accept="image/*" capture="environment" wire:model="otPhoto" class="w-full text-sm text-slate-600 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">2. ¿Qué elemento es?</label>
                                    <select wire:model="photoTag" class="w-full px-3 py-2.5 text-base rounded-xl border border-slate-300 bg-white">
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
                                <input type="text" wire:model="photoNotes" placeholder="Nota opcional (Ej. Placa motor, medidas...)" class="w-full px-3.5 py-2.5 text-base rounded-xl border border-slate-300 bg-white">
                            </div>
                            <button type="submit" class="w-full py-3.5 bg-blue-600 active:bg-blue-800 text-white text-sm font-bold rounded-xl shadow-sm">
                                Subir Fotografía a la OT
                            </button>
                        </form>

                        <!-- Cuadrícula de Fotos -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @forelse($currentOt->photos as $photo)
                                <div class="rounded-xl overflow-hidden border border-slate-200 bg-slate-50">
                                    <a href="{{ asset('storage/' . $photo->photo_path) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $photo->photo_path) }}" class="w-full h-36 object-cover">
                                    </a>
                                    <div class="p-2 flex justify-between items-center bg-white">
                                        <div class="truncate">
                                            <span class="text-[10px] font-bold uppercase text-blue-600 block">{{ $photo->component_tag }}</span>
                                            <span class="text-xs text-slate-600">{{ $photo->notes ?: 'Sin nota' }}</span>
                                        </div>
                                        <button wire:click="deletePhoto({{ $photo->id }})" class="text-red-500 text-sm font-bold p-1.5">✕</button>
                                    </div>
                                </div>
                            @empty
                                <p class="col-span-2 sm:col-span-3 text-xs text-slate-400 text-center py-4">Sin fotos en esta OT.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Ofertas de Proveedores -->
                    <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="text-base font-bold text-slate-900 mb-1">Ofertas de Proveedores</h3>
                        <p class="text-xs text-slate-500 mb-4">Guarda presupuestos y marca el ganador.</p>

                        <form wire:submit.prevent="addQuote" class="space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200 mb-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <input type="text" wire:model="quoteProvider" placeholder="Proveedor (Ej. Wittur)" class="w-full px-3.5 py-2.5 text-base rounded-xl border border-slate-300 bg-white" required>
                                <input type="number" step="0.01" inputmode="decimal" wire:model="quoteAmount" placeholder="Importe (€)" class="w-full px-3.5 py-2.5 text-base rounded-xl border border-slate-300 bg-white" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1">Adjuntar PDF o foto del presupuesto (Opcional)</label>
                                <input type="file" wire:model="quoteFile" class="w-full text-xs text-slate-600">
                            </div>
                            <button type="submit" class="w-full py-3 bg-indigo-600 active:bg-indigo-800 text-white text-sm font-bold rounded-xl">
                                + Guardar Oferta
                            </button>
                        </form>

                        <div class="space-y-2.5">
                            @forelse($currentOt->quotes as $quote)
                                <div class="p-3.5 rounded-xl border flex flex-col sm:flex-row justify-between sm:items-center gap-3 {{ $quote->is_selected ? 'bg-emerald-50 border-emerald-500' : 'bg-slate-50 border-slate-200' }}">
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-bold text-sm text-slate-900">{{ $quote->provider_name }}</span>
                                            <span class="font-mono font-bold text-sm text-blue-600">{{ number_format($quote->amount, 2) }} €</span>
                                            @if($quote->is_selected)
                                                <span class="px-2 py-0.5 bg-emerald-600 text-white text-[10px] font-bold rounded-full">ELEGIDO</span>
                                            @endif
                                        </div>
                                        @if($quote->file_path)
                                            <a href="{{ asset('storage/' . $quote->file_path) }}" target="_blank" class="text-xs text-indigo-600 font-semibold underline mt-1.5 inline-block">📄 Ver presupuesto adjunto</a>
                                        @endif
                                    </div>
                                    @if(!$quote->is_selected)
                                        <button wire:click="selectWinningQuote({{ $quote->id }})" class="w-full sm:w-auto px-4 py-2.5 bg-white border border-slate-300 text-xs font-bold rounded-xl">
                                            Seleccionar Esta Oferta
                                        </button>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 text-center py-3">Sin ofertas cargadas.</p>
                            @endforelse
                        </div>
                    </div>
                @endif

                <!-- PASO 3: CHECKLIST PARTIDAS, ACOPIO Y MONTAJE -->
                @if($activeSection === 'material')
                    <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="text-base font-bold text-slate-900 mb-1">Partidas a Cambiar y Acopio</h3>
                        <p class="text-xs text-slate-500 mb-4">Toca la casilla para incluirla y pulsa el botón derecho al recibir el material.</p>

                        <div class="divide-y divide-slate-100">
                            @foreach($currentOt->reformItems as $item)
                                <div class="py-3.5 flex items-center justify-between gap-2">
                                    <label class="flex items-center gap-3 cursor-pointer flex-1 py-1">
                                        <input type="checkbox" wire:click="toggleSelected({{ $item->id }})" {{ $item->is_selected ? 'checked' : '' }} class="w-5 h-5 text-blue-600 rounded border-slate-300 shrink-0">
                                        <span class="text-sm {{ $item->is_selected ? 'font-bold text-slate-900' : 'text-slate-400 line-through' }}">
                                            {{ $item->component_name }}
                                        </span>
                                    </label>

                                    @if($item->is_selected)
                                        <button wire:click="toggleReceived({{ $item->id }})" 
                                                class="px-3.5 py-2.5 rounded-xl text-xs font-bold transition shrink-0 {{ $item->material_received ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-700 border border-slate-300' }}">
                                            {{ $item->material_received ? '✓ Recibido' : '⏳ Pendiente' }}
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white p-4 sm:p-6 rounded-2xl border {{ $currentProgress === 100 ? 'border-emerald-500 ring-1 ring-emerald-500' : 'border-slate-200' }} shadow-sm">
                        <div class="flex flex-wrap justify-between items-center gap-2 mb-4">
                            <h3 class="text-base font-bold text-slate-900">Planificación de Montaje</h3>
                            @if($currentOt->budget_provider)
                                <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-lg">
                                    {{ $currentOt->budget_provider }} ({{ number_format($currentOt->accepted_budget_amount, 2) }} €)
                                </span>
                            @endif
                        </div>

                        <form wire:submit.prevent="savePlanning" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Montador / Operario</label>
                                <input type="text" wire:model="installerName" placeholder="Nombre del montador" class="w-full px-3.5 py-3 text-base rounded-xl border border-slate-300">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Fecha Inicio Obra</label>
                                <input type="date" wire:model="scheduledDate" class="w-full px-3.5 py-3 text-base rounded-xl border border-slate-300">
                            </div>
                            <div class="sm:col-span-2 pt-1">
                                <button type="submit" class="w-full py-3.5 bg-emerald-600 active:bg-emerald-800 text-white text-sm font-bold rounded-xl shadow-sm">
                                    Guardar Planificación
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

            </div>
        @endif
    </div>
</div>