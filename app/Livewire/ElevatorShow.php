<?php

namespace App\Livewire;

use App\Models\Elevator;
use Livewire\Component;
use Livewire\Attributes\Computed;

class ElevatorShow extends Component
{
    public Elevator $elevator;
    public string $activeTab = 'work_orders'; // 'work_orders', 'invoices', 'tech_specs'

    public function mount(Elevator $elevator)
    {
        $this->elevator = $elevator->load([
            'location.client',
            'workOrders' => fn($q) => $q->latest(),
        ]);
    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    #[Computed]
    public function clientInvoices()
    {
        return $this->elevator->location?->client?->invoices ?? collect();
    }

    public function render()
    {
        return view('livewire.elevator-show')->layout('layouts.app');
    }
}