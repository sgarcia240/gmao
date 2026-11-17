<div>
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/75 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200/80 my-8">

                <!-- ENCABEZADO MODAL -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/70">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-sm shadow-emerald-600/20 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base sm:text-lg font-extrabold text-slate-900">Firma Conforme</h3>
                                <span class="font-mono-tech text-xs font-bold px-2.5 py-0.5 rounded-lg bg-slate-900 text-cyan-400">
                                    OT #{{ $workOrderId }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400">Validación digital de conformidad del cliente</p>
                        </div>
                    </div>

                    <button wire:click="closeModal"
                            type="button"
                            class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- CUERPO DEL MODAL E INTERACCIÓN DE FIRMA -->
                <div class="p-6" x-data="signaturePadComponent" x-init="initPad()">
                    <p class="text-sm font-medium text-slate-600 mb-3">
                        Por favor, pida al cliente o responsable de la finca que realice su firma dentro del recuadro:
                    </p>

                    <div class="relative border-2 border-dashed border-slate-300 hover:border-blue-400 rounded-2xl bg-white overflow-hidden shadow-inner transition-colors">
                        <canvas x-ref="canvas" class="w-full h-48 block cursor-crosshair" style="touch-action: none;"></canvas>

                        <!-- Guía visual inferior (no afecta al canvas exportado) -->
                        <div class="pointer-events-none absolute inset-x-8 bottom-4 border-b border-slate-200 flex justify-between items-end pb-1">
                            <span class="font-mono-tech text-[10px] uppercase tracking-wider text-slate-300">
                                X &nbsp; Firma del titular / autorizado
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-3">
                        <span class="text-[11px] text-slate-400">
                            Compatible con pantalla táctil y ratón
                        </span>

                        <button type="button"
                                @click="clear()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-rose-600 bg-rose-50/80 hover:bg-rose-100 border border-rose-200/70 rounded-xl transition cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            <span>Limpiar trazado</span>
                        </button>
                    </div>

                    <!-- ACCIONES -->
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-5 mt-6 border-t border-slate-100">
                        <button type="button"
                                wire:click="closeModal"
                                class="w-full sm:w-auto px-4 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 rounded-xl transition">
                            Cancelar
                        </button>
                        <button type="button"
                                @click="save()"
                                wire:loading.attr="disabled"
                                wire:target="saveSignature"
                                class="w-full sm:w-auto px-5 py-2.5 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 rounded-xl shadow-md shadow-emerald-600/20 transition active:scale-95 inline-flex items-center justify-center gap-2 cursor-pointer">
                            <svg wire:loading.remove wire:target="saveSignature" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                            <span wire:loading.remove wire:target="saveSignature">Guardar Firma</span>

                            <span wire:loading wire:target="saveSignature" class="inline-flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                Guardando...
                            </span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    @endif
</div>

<!-- Garantiza que el CDN cargue solo una vez en la app -->
@assets
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
@endassets

<!-- Encapsula la lógica de JavaScript en Livewire 3 -->
@script
<script>
    Alpine.data('signaturePadComponent', () => ({
        pad: null,
        initPad() {
            this.$nextTick(() => {
                const canvas = this.$refs.canvas;
                if (!canvas) return;

                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);

                this.pad = new SignaturePad(canvas, {
                    backgroundColor: 'rgb(255, 255, 255)',
                    penColor: 'rgb(15, 23, 42)',
                    minWidth: 1,
                    maxWidth: 2.5
                });
            });
        },
        clear() {
            if (this.pad) {
                this.pad.clear();
            }
        },
        save() {
            if (!this.pad || this.pad.isEmpty()) {
                alert('Por favor, pida al cliente que firme antes de guardar.');
                return;
            }
            const data = this.pad.toDataURL('image/png');
            $wire.saveSignature(data);
        }
    }));
</script>
@endscript