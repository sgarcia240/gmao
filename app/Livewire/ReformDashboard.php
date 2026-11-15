<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Elevator;
use App\Models\ElevatorSurvey;
use App\Models\WorkOrder;
use App\Models\ReformItem;
use App\Models\WorkOrderPhoto;
use App\Models\WorkOrderQuote;
use App\Models\Location;

class ReformDashboard extends Component
{
    use WithFileUploads;

    public $selectedOtId;
    public $activeSection = 'survey'; // 'survey', 'photos_quotes', 'material'
    public $searchOt = '';

    // 1. Toma de Datos Técnica
    public $stops = '';
    public $machineType = '';
    public $controllerType = '';
    public $doorType = '';
    public $pushButtonsType = '';
    public $technicalNotes = '';

    // 2. Fotos y Ofertas de Proveedores
    public $otPhoto;
    public $photoTag = 'Máquina';
    public $photoNotes = '';

    public $quoteProvider = '';
    public $quoteAmount = '';
    public $quoteFile;
    public $quoteNotes = '';

    // 3. Planificación y Montaje
    public $installerName = '';
    public $scheduledDate = '';

    // Modal Nueva Comunidad / Obra
    public $showNewModal = false;
    public $newAddress = '';
    public $newRae = '';
    // Modal personalizado de borrado de Oferta / Documento Anexo
    public bool $showDeleteQuoteModal = false;
    public ?int $quoteToDeleteId = null;
    public string $quoteDeleteMode = 'quote'; // 'quote' (oferta completa) | 'file' (solo documento anexo)
    public string $quoteToDeleteLabel = '';

    public array $defaultComponents = [
        'Máquina',
        'Cuadro de Maniobra',
        'Botoneras (Cabina y Piso)',
        'DTU (Magnetotérmicos y Diferenciales)',
        'Puertas de Cabina',
        'Puertas de Piso',
        'Cableado e Instalación de Hueco',
        'Limitador y Paracaídas',
    ];

    public function mount()
    {
        $firstOt = WorkOrder::latest()->first();
        if ($firstOt) {
            $this->selectOt($firstOt->id);
        }
    }

    public function selectOt($id)
    {
        $this->selectedOtId = $id;
        $ot = WorkOrder::with('elevator.survey')->find($id);

        if ($ot) {
            $this->installerName = $ot->installer_name ?? '';
            $this->scheduledDate = $ot->scheduled_date ?? '';

            $survey = $ot->elevator->survey ?? null;
            $this->stops = $survey->stops ?? '';
            $this->machineType = $survey->machine_type ?? '';
            $this->controllerType = $survey->controller_type ?? '';
            $this->doorType = $survey->door_type ?? '';
            $this->pushButtonsType = $survey->push_buttons_type ?? '';
            $this->technicalNotes = $survey->technical_notes ?? '';

            if ($ot->reformItems()->count() === 0) {
                foreach ($this->defaultComponents as $comp) {
                    ReformItem::create([
                        'work_order_id' => $ot->id,
                        'component_name' => $comp,
                        'is_selected' => false,
                        'material_received' => false,
                    ]);
                }
            }
        }
    }

    public function createNewWorkOrder()
    {
        $this->validate([
            'newAddress' => 'required|string|min:3',
            'newRae' => 'nullable|string',
        ]);

        // 0. Asegurar un client_id válido para mantener la integridad con tu esquema original
        $clientId = \Illuminate\Support\Facades\DB::table('clients')->value('id');
        if (!$clientId) {
            $clientId = \Illuminate\Support\Facades\DB::table('clients')->insertGetId([
                'name' => 'Comunidades / Uso Personal',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 1. Crear o recuperar la Ubicación / Comunidad en locations
        $location = Location::firstOrCreate(
            ['address' => $this->newAddress],
            [
                'client_id' => $clientId,
                'building_name' => $this->newAddress,
                'city' => '-',
            ]
        );

       $raeCode = trim($this->newRae) !== '' ? trim($this->newRae) : 'S/N-' . rand(1000, 9999);

        // 2. Recuperar el ascensor si ese RAE ya existe, o crearlo nuevo si no existe
        $elevator = Elevator::firstOrCreate(
            ['rae_code' => $raeCode],
            [
                'location_id' => $location->id,
                'brand' => 'Por definir',
                'model' => 'Por definir',
                'stops_count' => 0,
                'max_load_kg' => 0,
                'type_maneuver' => 'Por definir',
                'status' => 'active',
            ]
        );

        // 3. Crear la OT vinculada al ascensor
        $ot = WorkOrder::create([
            'elevator_id'       => $elevator->id,
            'type'              => 'assembly',
            'priority'          => 'normal',
            'status'            => 'pending',
            'issue_description' => 'Reforma / Modernización',
        ]);

        $this->reset(['newAddress', 'newRae', 'showNewModal']);
        $this->selectOt($ot->id);
        $this->activeSection = 'survey';
        session()->flash('message', 'Comunidad creada. Rellena la toma de datos.');
    }

    public function saveSurvey()
    {
        $ot = WorkOrder::findOrFail($this->selectedOtId);

        ElevatorSurvey::updateOrCreate(
            ['elevator_id' => $ot->elevator_id],
            [
                'stops' => $this->stops ?: null,
                'machine_type' => $this->machineType,
                'controller_type' => $this->controllerType,
                'door_type' => $this->doorType,
                'push_buttons_type' => $this->pushButtonsType,
                'technical_notes' => $this->technicalNotes,
            ]
        );

        session()->flash('message', 'Toma de datos guardada.');
        $this->activeSection = 'photos_quotes';
    }

    public function uploadOtPhoto()
    {
        $this->validate([
            'otPhoto' => 'required|image|max:8192',
        ]);

        $path = $this->otPhoto->store('ot-photos', 'public');

        WorkOrderPhoto::create([
            'work_order_id' => $this->selectedOtId,
            'photo_path' => $path,
            'component_tag' => $this->photoTag,
            'notes' => $this->photoNotes,
        ]);

        $this->reset(['otPhoto', 'photoNotes']);
        session()->flash('message', 'Foto guardada en la OT.');
    }

    public function deletePhoto($photoId)
    {
        $photo = WorkOrderPhoto::findOrFail($photoId);
        Storage::disk('public')->delete($photo->photo_path);
        $photo->delete();
    }

    public function addQuote()
    {
        $this->validate([
            'quoteProvider' => 'required|string',
            'quoteAmount' => 'required|numeric|min:0',
            'quoteFile' => 'nullable|file|max:10240',
        ]);

        $filePath = $this->quoteFile ? $this->quoteFile->store('ot-quotes', 'public') : null;

        WorkOrderQuote::create([
            'work_order_id' => $this->selectedOtId,
            'provider_name' => $this->quoteProvider,
            'amount' => $this->quoteAmount,
            'file_path' => $filePath,
            'notes' => $this->quoteNotes,
        ]);

        $this->reset(['quoteProvider', 'quoteAmount', 'quoteFile', 'quoteNotes']);
        session()->flash('message', 'Oferta añadida.');
    }

    public function selectWinningQuote($quoteId)
    {
        WorkOrderQuote::where('work_order_id', $this->selectedOtId)->update(['is_selected' => false]);
        $quote = WorkOrderQuote::findOrFail($quoteId);
        $quote->update(['is_selected' => true]);

        WorkOrder::where('id', $this->selectedOtId)->update([
            'budget_provider' => $quote->provider_name,
            'accepted_budget_amount' => $quote->amount,
        ]);

        session()->flash('message', "Oferta de {$quote->provider_name} seleccionada.");
    }

    public function toggleSelected($itemId)
    {
        $item = ReformItem::findOrFail($itemId);
        $item->update([
            'is_selected' => !$item->is_selected,
            'material_received' => !$item->is_selected ? $item->material_received : false,
        ]);
    }

    public function toggleReceived($itemId)
    {
        $item = ReformItem::findOrFail($itemId);
        if (!$item->is_selected) return;

        $newState = !$item->material_received;
        $item->update([
            'material_received' => $newState,
            'received_at' => $newState ? now() : null,
        ]);
    }

    public function savePlanning()
    {
        WorkOrder::where('id', $this->selectedOtId)->update([
            'installer_name' => $this->installerName,
            'scheduled_date' => $this->scheduledDate ?: null,
        ]);

        session()->flash('message', 'Montador y fecha guardados.');
    }

    public function render()
    {
        $activeOts = WorkOrder::with(['elevator.location', 'reformItems'])
            ->when(strlen($this->searchOt) > 1, function ($q) {
                $q->whereHas('elevator', function ($sub) {
                    $sub->where('rae_code', 'like', "%{$this->searchOt}%")
                        ->orWhereHas('location', function ($loc) {
                            $loc->where('address', 'like', "%{$this->searchOt}%")
                                ->orWhere('name', 'like', "%{$this->searchOt}%");
                        });
                });
            })
            ->latest()
            ->get();

        $currentOt = $this->selectedOtId
            ? WorkOrder::with(['elevator.location', 'elevator.survey', 'reformItems', 'photos', 'quotes'])->find($this->selectedOtId)
            : null;

        return view('livewire.reform-dashboard', [
            'activeOts' => $activeOts,
            'currentOt' => $currentOt,
        ]);
    }
    public function deleteQuote($quoteId)
    {
        $quote = WorkOrderQuote::findOrFail($quoteId);

        if ($quote->file_path) {
            Storage::disk('public')->delete($quote->file_path);
        }

        // Si la oferta borrada era la ganadora seleccionada, limpiar el presupuesto aceptado en la OT
        if ($quote->is_selected) {
            WorkOrder::where('id', $this->selectedOtId)->update([
                'budget_provider' => null,
                'accepted_budget_amount' => null,
            ]);
        }

        $quote->delete();
        session()->flash('message', 'Oferta eliminada correctamente.');
    }

    public function deleteQuoteFile($quoteId)
    {
        $quote = WorkOrderQuote::findOrFail($quoteId);

        if ($quote->file_path) {
            Storage::disk('public')->delete($quote->file_path);
            $quote->update(['file_path' => null]);
            session()->flash('message', 'Documento adjunto eliminado.');
        }
    }
    public function confirmDeleteQuote($quoteId)
    {
        $quote = WorkOrderQuote::findOrFail($quoteId);

        $this->quoteToDeleteId = $quote->id;
        $this->quoteDeleteMode = 'quote';
        $this->quoteToDeleteLabel = $quote->provider_name . ' (' . number_format((float) $quote->amount, 2, ',', '.') . ' €)';
        $this->showDeleteQuoteModal = true;
    }

    public function confirmDeleteQuoteFile($quoteId)
    {
        $quote = WorkOrderQuote::findOrFail($quoteId);

        $this->quoteToDeleteId = $quote->id;
        $this->quoteDeleteMode = 'file';
        $this->quoteToDeleteLabel = $quote->provider_name;
        $this->showDeleteQuoteModal = true;
    }

    public function cancelDeleteQuoteModal()
    {
        $this->reset(['showDeleteQuoteModal', 'quoteToDeleteId', 'quoteDeleteMode', 'quoteToDeleteLabel']);
    }

    public function executeQuoteDeletion()
    {
        if (!$this->quoteToDeleteId) {
            $this->cancelDeleteQuoteModal();
            return;
        }

        $quote = WorkOrderQuote::findOrFail($this->quoteToDeleteId);

        if ($this->quoteDeleteMode === 'file') {
            if ($quote->file_path) {
                Storage::disk('public')->delete($quote->file_path);
                $quote->update(['file_path' => null]);
            }
            $this->cancelDeleteQuoteModal();
            session()->flash('message', 'Documento adjunto eliminado correctamente.');
            return;
        }

        // Modo 'quote': eliminar archivo físico, limpiar OT si era la oferta ganadora y borrar el registro
        if ($quote->file_path) {
            Storage::disk('public')->delete($quote->file_path);
        }

        if ($quote->is_selected) {
            WorkOrder::where('id', $this->selectedOtId)->update([
                'budget_provider' => null,
                'accepted_budget_amount' => null,
            ]);
        }

        $quote->delete();

        $this->cancelDeleteQuoteModal();
        session()->flash('message', 'Oferta eliminada correctamente.');
    }
}
