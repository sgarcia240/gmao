<?php

namespace App\Livewire;

use Livewire\Component;

class AnalyticsDashboard extends Component
{
    public $totalWorkOrders = 0;
    public $pendingOrders = 0;
    public $completedOrders = 0;
    public $totalElevators = 0;

    public $chartMonths = ['May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct'];
    public $preventiveData = [18, 24, 20, 28, 25, 32];
    public $correctiveData = [8, 12, 6, 10, 14, 9];
    public $elevatorDistribution = [14, 3, 1];

    public function mount()
    {
        // Aquí puedes realizar tus consultas a la BD si corresponde
    }

    public function render()
    {
        return view('livewire.analytics-dashboard')
            ->layout('layouts.app');
    }
}