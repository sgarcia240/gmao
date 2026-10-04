<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WorkOrderSignature extends Component
{
    public $workOrderId;
    public $showModal = false;

    protected $listeners = ['openSignatureModal' => 'loadModal'];

    public function loadModal($orderId)
    {
        $this->workOrderId = $orderId;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function saveSignature($signatureData)
    {
        if (empty($signatureData)) {
            session()->flash('error', 'La firma es obligatoria.');
            return;
        }

        $workOrder = WorkOrder::findOrFail($this->workOrderId);

        // Decodificar la imagen Base64 del Canvas
        $imageParts = explode(";base64,", $signatureData);
        $imageTypeAux = explode("image/", $imageParts[0]);
        $imageType = $imageTypeAux[1] ?? 'png';
        $imageBase64 = base64_decode($imageParts[1]);

        // Guardar la firma en storage/app/public/signatures/
        $filename = 'signatures/sig_' . $workOrder->id . '_' . Str::random(8) . '.' . $imageType;
        Storage::disk('public')->put($filename, $imageBase64);

        // Si existía una firma previa, eliminar el archivo anterior
        if ($workOrder->client_signature && Storage::disk('public')->exists($workOrder->client_signature)) {
            Storage::disk('public')->delete($workOrder->client_signature);
        }

        // Actualizar el registro en base de datos
        $workOrder->update([
            'client_signature' => $filename,
            'signed_at'        => now(),
        ]);

        $this->showModal = false;
        $this->dispatch('signatureSaved');
        session()->flash('message', '¡Firma capturada y guardada correctamente!');
    }

    public function render()
    {
        return view('livewire.work-order-signature');
    }
}