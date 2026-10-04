<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Models\Client;
use App\Models\Elevator;
use App\Models\WorkOrder;
use App\Models\User;

class CreateWorkOrder extends Component
{
    public $client_id = '';

    #[Rule('required|exists:elevators,id')]
    public $elevator_id = '';

    #[Rule('nullable|exists:users,id')]
    public $technician_id = '';

    #[Rule('required|string')]
    public $type = 'preventive';

    #[Rule('required|string')]
    public $priority = 'medium';

    #[Rule('required|string')]
    public $status = 'pending';

    #[Rule('nullable|string')]
    public $failure_category = '';

    #[Rule('required|string|min:5')]
    public $issue_description = '';

    public $elevators = [];

    public function updatedClientId($value)
    {
        $this->elevator_id = '';
        $this->elevators = $value ? Elevator::where('client_id', $value)->get() : [];
    }

    public function save()
    {
        $this->validate();

        $workOrder = WorkOrder::create([
            'elevator_id' => $this->elevator_id,
            'technician_id' => $this->technician_id ?: null,
            'type' => $this->type,
            'priority' => $this->priority,
            'status' => $this->status,
            'failure_category' => $this->failure_category ?: null,
            'issue_description' => $this->issue_description,
            'started_at' => now(),
        ]);

        session()->flash('message', 'Orden de trabajo #' . $workOrder->id . ' creada correctamente.');

        return redirect()->route('work-orders.index');
    }

    public function render()
    {
        return view('livewire.create-work-order', [
            'clients' => Client::orderBy('name')->get(),
            'technicians' => User::all(),
        ]);
    }
}