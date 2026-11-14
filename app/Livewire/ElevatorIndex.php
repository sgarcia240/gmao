<?php

namespace App\Livewire;

use App\Models\Elevator;
use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ElevatorIndex extends Component
{
    use WithPagination;

    // Filtros y búsqueda
    public $search = '';
    public $statusFilter = '';

    // Estado del modal
    public $showModal = false;
    public $isEditing = false;
    public $elevatorId = null;

    // Campos del formulario
    public $location_id = null; // 👈 Campo obligatorio en la BBDD
    public string $rae_code = '';
    public string $brand = '';
    public string $model = '';
    public ?int $stops_count = 4;
    public ?int $max_load_kg = 450;
    public string $status = 'active';
    public ?string $next_ite_date = null;
    // Control del modal de confirmación de borrado
    public bool $showDeleteModal = false;
    public ?int $elevatorToDeleteId = null;
    public string $elevatorToDeleteRae = '';
    public int $relatedOrdersCount = 0;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    protected function rules()
    {
        return [
            'location_id'   => 'required|exists:locations,id',
            'rae_code'      => [
                'required',
                Rule::unique('elevators', 'rae_code')->ignore($this->elevatorId),
            ],
            'brand'         => 'required|string|max:100',
            'model'         => 'nullable|string|max:100',
            'stops_count'   => 'required|integer|min:1',
            'max_load_kg'   => 'required|integer|min:50',
            'status'        => 'required|in:active,maintenance,stopped,out_of_service',
            'next_ite_date' => 'nullable|date',
        ];
    }

    public function openCreateModal()
    {
        if (auth()->user()->hasRole(User::ROLE_TECHNICIAN)) {
            abort(403, 'No tienes permisos para registrar o modificar ascensores.');
        }

        $this->resetFields();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        if (auth()->user()->hasRole(User::ROLE_TECHNICIAN)) {
            abort(403, 'No tienes permisos para registrar o modificar ascensores.');
        }

        $elevator = Elevator::findOrFail($id);
        $this->elevatorId    = $elevator->id;
        $this->location_id   = $elevator->location_id;
        $this->rae_code      = $elevator->rae_code;
        $this->brand         = $elevator->brand;
        $this->model         = $elevator->model;
        $this->stops_count   = $elevator->stops_count;
        $this->max_load_kg   = $elevator->max_load_kg;
        $this->status        = $elevator->status;
        
        $this->next_ite_date = $elevator->next_ite_date 
            ? Carbon::parse($elevator->next_ite_date)->format('Y-m-d') 
            : null;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->elevatorId    = null;
        $this->location_id   = null;
        $this->rae_code      = '';
        $this->brand         = '';
        $this->model         = '';
        $this->stops_count   = 4;
        $this->max_load_kg   = 450;
        $this->status        = 'active';
        $this->next_ite_date = null;
        $this->resetValidation();
    }

    public function save()
    {
        if (auth()->user()->hasRole(User::ROLE_TECHNICIAN)) {
            abort(403);
        }

        $validatedData = $this->validate();

        Elevator::updateOrCreate(
            ['id' => $this->elevatorId],
            $validatedData
        );

        session()->flash('message', $this->isEditing ? 'Ascensor actualizado con éxito.' : 'Ascensor creado correctamente.');
        $this->closeModal();
    }

    public function render()
    {
        $user = auth()->user();

        $elevators = Elevator::query()
            ->with('location')
            ->when($user->hasRole(User::ROLE_TECHNICIAN), function ($query) use ($user) {
                $query->whereHas('workOrders', function ($q) use ($user) {
                    $q->where('technician_id', $user->id);
                });
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('rae_code', 'like', '%' . $this->search . '%')
                      ->orWhere('brand', 'like', '%' . $this->search . '%')
                      ->orWhere('model', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.elevator-index', [
            'elevators' => $elevators,
            'locations' => Location::all(), // 👈 Enviamos las ubicaciones a la vista
        ])->layout('layouts.app');
    }
    public function confirmDelete(int $id)
    {
        $elevator = Elevator::withCount('workOrders')->findOrFail($id);

        $this->elevatorToDeleteId = $elevator->id;
        $this->elevatorToDeleteRae = $elevator->rae_code ?? 'S/N';
        $this->relatedOrdersCount = $elevator->work_orders_count;
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->reset(['showDeleteModal', 'elevatorToDeleteId', 'elevatorToDeleteRae', 'relatedOrdersCount']);
    }

    public function deleteElevator()
    {
        if (!$this->elevatorToDeleteId) {
            return;
        }

        $rae = $this->elevatorToDeleteRae;

        DB::transaction(function () {
            $elevator = Elevator::with('workOrders')->findOrFail($this->elevatorToDeleteId);

            // 1. Eliminar cada OT vinculada y todos sus registros hijos
            foreach ($elevator->workOrders as $order) {
                if (method_exists($order, 'reformItems')) {
                    $order->reformItems()->delete();
                }
                if (method_exists($order, 'photos')) {
                    $order->photos()->delete();
                }
                if (method_exists($order, 'quotes')) {
                    $order->quotes()->delete();
                }
                $order->delete();
            }

            // 2. Eliminar la toma de datos técnica (survey) si existe
            if (method_exists($elevator, 'survey') && $elevator->survey) {
                $elevator->survey()->delete();
            }

            // 3. Eliminar el ascensor
            $elevator->delete();
        });

        $this->cancelDelete();
        session()->flash('message', "Ascensor {$rae} y toda su documentación vinculada eliminados correctamente.");
    }
}