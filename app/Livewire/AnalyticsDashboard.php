<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\WorkOrder;
use App\Models\Elevator;
use Carbon\Carbon;

class AnalyticsDashboard extends Component
{
    public int $totalWorkOrders = 0;
    public int $pendingOrders = 0;
    public int $completedOrders = 0;
    public int $totalElevators = 0;
    public int $completionRate = 0;
    public int $thisMonthOrders = 0;

    public array $chartMonths = [];
    public array $preventiveData = [];
    public array $correctiveData = [];
    public array $elevatorDistribution = [0, 0, 0];

    public function mount()
    {
        $this->loadMetrics();
    }

    public function loadMetrics()
    {
        // 1. Contadores reales de Órdenes de Trabajo
        $this->totalWorkOrders = WorkOrder::count();

        $this->pendingOrders = WorkOrder::whereIn('status', [
            'pending', 'in_progress', 'assigned', 'pendiente', 'en_proceso'
        ])->count();

        $this->completedOrders = WorkOrder::whereIn('status', [
            'completed', 'closed', 'finished', 'completada', 'cerrada'
        ])->count();

        $this->thisMonthOrders = WorkOrder::whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ])->count();

        $this->completionRate = $this->totalWorkOrders > 0
            ? (int) round(($this->completedOrders / $this->totalWorkOrders) * 100)
            : 0;

        // 2. Parque total y distribución de ascensores
        $this->totalElevators = Elevator::count();

        $operative = Elevator::whereIn('status', ['active', 'operative', 'operativo'])->count();
        $maintenance = Elevator::whereIn('status', ['maintenance', 'mantenimiento'])->count();
        $outOfService = Elevator::whereIn('status', ['inactive', 'out_of_service', 'fuera_de_servicio', 'stopped'])->count();

        // Si existen ascensores sin estado o con otro estado, sumarlos a operativos para que cuadre el total
        $accounted = $operative + $maintenance + $outOfService;
        if ($this->totalElevators > $accounted) {
            $operative += ($this->totalElevators - $accounted);
        }

        $this->elevatorDistribution = [$operative, $maintenance, $outOfService];

        // 3. Evolución de intervenciones de los últimos 6 meses (compatible con PostgreSQL)
        $months = [];
        $preventive = [];
        $corrective = [];
        $monthNames = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic',
        ];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $months[] = $monthNames[$date->month] . ' ' . $date->format('y');

            $preventive[] = WorkOrder::whereBetween('created_at', [$start, $end])
                ->whereIn('type', ['preventive', 'preventivo', 'maintenance', 'inspection'])
                ->count();

            // Incluye correctivos y reformas/montajes ('assembly')
            $corrective[] = WorkOrder::whereBetween('created_at', [$start, $end])
                ->whereNotIn('type', ['preventive', 'preventivo', 'maintenance', 'inspection'])
                ->count();
        }

        $this->chartMonths = $months;
        $this->preventiveData = $preventive;
        $this->correctiveData = $corrective;
    }

    public function render()
    {
        return view('livewire.analytics-dashboard')
            ->layout('layouts.app');
    }
}