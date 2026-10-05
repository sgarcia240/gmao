<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WorkOrder;
use App\Models\Elevator;
use App\Models\User;

class WorkOrderIndex extends Component
{
    use WithPagination;

    // Control del Modal de Creación
    public bool $showModal = false;

    // Campos del Formulario
    public $elevator_id = '';
    public $technician_id = '';
    public $type = 'corrective';
    public $priority = 'normal';
    public $status = 'pending';
    public $issue_description = '';
    public $failure_category = '';

    // Filtros de búsqueda
    public $search = '';
    public $statusFilter = '';

    protected function rules()
    {
        return [
            'elevator_id'       => 'required|exists:elevators,id',
            'technician_id'     => 'nullable|exists:users,id',
            'type'              => 'required|in:corrective,preventive,inspection,assembly',
            'priority'          => 'required|in:normal,urgent,person_trapped',
            'status'            => 'required|in:pending,assigned,in_progress,completed,invoiced',
            'issue_description' => 'required|string|min:5',
            'failure_category'  => 'nullable|string|max:100',
        ];
    }

    public function openCreateModal()
    {
        $this->resetErrorBag();
        $this->reset(['elevator_id', 'technician_id', 'type', 'priority', 'status', 'issue_description', 'failure_category']);
        $this->type = 'corrective';
        $this->priority = 'normal';
        $this->status = 'pending';
        
        // Si el usuario que crea la orden es un técnico, se asigna automáticamente a sí mismo
        if (auth()->user()->hasRole(User::ROLE_TECHNICIAN)) {
            $this->technician_id = auth()->id();
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function save()
    {
        $validatedData = $this->validate();

        WorkOrder::create($validatedData);

        $this->showModal = false;
        session()->flash('message', '¡Orden de trabajo creada correctamente!');
    }

    // Cambio rápido de estado directo desde la tabla
    public function updateStatus($orderId, $newStatus)
    {
        $user = auth()->user();
        $order = WorkOrder::findOrFail($orderId);

        // Verificación de seguridad extra: si es técnico, solo puede actualizar sus propias órdenes
        if ($user->hasRole(User::ROLE_TECHNICIAN) && $order->technician_id !== $user->id) {
            abort(403, 'No tienes permiso para modificar esta orden de trabajo.');
        }

        $order->update(['status' => $newStatus]);

        session()->flash('message', 'Estado de la orden #' . $orderId . ' actualizado correctamente.');
    }

    public function render()
    {
        $user = auth()->user();

        $query = WorkOrder::with(['elevator.location', 'technician']);

        // Data Scoping: Si es técnico, filtrar solo sus órdenes
        if ($user?->hasRole(User::ROLE_TECHNICIAN)) {
            $query->where('technician_id', $user->id);
        }

        if ($this->search) {
            $query->where('issue_description', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.work-order-index', [
            'workOrders'  => $query->latest()->paginate(10),
            'elevators'   => Elevator::with('location')->get(),
            // Filtrar selector de técnicos solo a técnicos activos
            'technicians' => User::where('role', User::ROLE_TECHNICIAN)->where('is_active', true)->get(),
        ])->layout('layouts.app');
    }
}