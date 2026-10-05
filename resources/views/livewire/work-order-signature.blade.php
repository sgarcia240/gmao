<div>
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-200 dark:border-gray-700">
                
                <!-- Encabezado -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Firma Conforme Cliente - OT #{{ $workOrderId }}</h3>
                    <button wire:click="closeModal" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Cuerpo del Modal e Interacción de Firma -->
                <div class="p-6" x-data="signaturePadComponent" x-init="initPad()">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Por favor, pida al cliente que realice su firma dentro del recuadro:</p>

                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg bg-white overflow-hidden">
                        <canvas x-ref="canvas" class="w-full h-48 block cursor-crosshair" style="touch-action: none;"></canvas>
                    </div>

                    <div class="flex justify-between items-center mt-3">
                        <button type="button" @click="clear()" class="text-xs text-red-600 hover:text-red-800 hover:underline font-semibold">
                            Limpiar trazado
                        </button>
                    </div>

                    <!-- Acciones -->
                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg">
                            Cancelar
                        </button>
                        <button type="button" 
                                @click="save()" 
                                wire:loading.attr="disabled"
                                wire:target="saveSignature"
                                class="px-4 py-2 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 disabled:opacity-50 rounded-lg shadow inline-flex items-center gap-2">
                            <span wire:loading.remove wire:target="saveSignature">Guardar Firma</span>
                            <span wire:loading wire:target="saveSignature">Guardando...</span>
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
                    penColor: 'rgb(0, 0, 0)',
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
