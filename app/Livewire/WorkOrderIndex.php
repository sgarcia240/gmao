<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WorkOrder;
use App\Models\Elevator;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class WorkOrderIndex extends Component
{
    use WithPagination;

    // Control del Modal de Edición
    public bool $showModal = false;
    public ?int $editingOrderId = null;

    // Control del Modal de Confirmación de Borrado
    public bool $showDeleteModal = false;
    public ?int $orderToDeleteId = null;
    public string $orderToDeleteElevator = '';

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
        return redirect()->route('dashboard');
    }

    public function openEditModal(int $id)
    {
        $order = WorkOrder::findOrFail($id);

        $this->editingOrderId    = $order->id;
        $this->elevator_id       = $order->elevator_id;
        $this->technician_id     = $order->technician_id ?? '';
        $this->type              = $order->type ?: 'assembly';
        $this->priority          = $order->priority ?: 'normal';
        $this->status            = $order->status ?: 'pending';
        $this->issue_description = $order->issue_description;
        $this->failure_category  = $order->failure_category ?? '';

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->reset(['editingOrderId']);
        $this->showModal = false;
    }

    public function save()
    {
        if (!$this->editingOrderId) {
            abort(403, 'Las órdenes nuevas deben crearse desde la Toma de Datos.');
        }

        if ($this->technician_id === '') {
            $this->technician_id = null;
        }

        $validatedData = $this->validate();

        $order = WorkOrder::findOrFail($this->editingOrderId);
        $order->update($validatedData);

        $this->reset(['editingOrderId']);
        $this->showModal = false;
        session()->flash('message', '¡Orden de trabajo #' . $order->id . ' actualizada correctamente!');
    }

    public function confirmDelete(int $id)
    {
        $user = auth()->user();

        if (!$user->hasRole(User::ROLE_ADMIN) && !$user->hasRole(User::ROLE_SUPERVISOR)) {
            abort(403, 'No tienes permiso para eliminar órdenes de trabajo.');
        }

        $order = WorkOrder::with('elevator')->findOrFail($id);

        $this->orderToDeleteId = $order->id;
        $this->orderToDeleteElevator = $order->elevator?->rae ?? 'Sin RAE asignado';
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->reset(['showDeleteModal', 'orderToDeleteId', 'orderToDeleteElevator']);
    }

    public function deleteOrder()
    {
        $user = auth()->user();

        if (!$user->hasRole(User::ROLE_ADMIN) && !$user->hasRole(User::ROLE_SUPERVISOR)) {
            abort(403, 'No tienes permiso para eliminar órdenes de trabajo.');
        }

        if (!$this->orderToDeleteId) {
            $this->cancelDelete();
            return;
        }

        $id = $this->orderToDeleteId;
        $order = WorkOrder::findOrFail($id);

        // Limpiar registros hijos comprobando antes que la tabla exista (evita SQLSTATE 25P02 en PostgreSQL)
        if (Schema::hasTable('reform_items') && method_exists($order, 'reformItems')) {
            $order->reformItems()->delete();
        }
        if (Schema::hasTable('work_order_photos') && method_exists($order, 'photos')) {
            $order->photos()->delete();
        }
        if (Schema::hasTable('quotes') && method_exists($order, 'quotes')) {
            $order->quotes()->delete();
        }
        if (Schema::hasTable('invoices') && method_exists($order, 'invoices')) {
            $order->invoices()->delete();
        }

        $order->delete();

        $this->cancelDelete();
        session()->flash('message', 'Orden de trabajo #' . $id . ' eliminada correctamente.');
    }

    // Cambio rápido de estado directo desde la tabla
    public function updateStatus($orderId, $newStatus)
    {
        $user = auth()->user();
        $order = WorkOrder::findOrFail($orderId);

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
            'technicians' => User::where('role', User::ROLE_TECHNICIAN)->where('is_active', true)->get(),
        ])->layout('layouts.app');
    }
}