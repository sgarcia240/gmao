<?php

namespace App\Exports;

use App\Models\WorkOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WorkOrdersExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $dateRange;

    public function __construct(int $dateRange = 30)
    {
        $this->dateRange = $dateRange;
    }

    public function query()
    {
        $startDate = now()->subDays($this->dateRange);

        return WorkOrder::query()
            ->with(['elevator.client', 'technician'])
            ->where('created_at', '>=', $startDate)
            ->latest();
    }

    public function headings(): array
    {
        return [
            'ID Orden',
            'Cliente',
            'Ascensor / RAE',
            'Tipo',
            'Prioridad',
            'Estado',
            'Categoría Avería',
            'Descripción del Problema',
            'Trabajo Realizado',
            'Técnico Asignado',
            'Fecha Inicio',
            'Fecha Finalización',
        ];
    }

    public function map($workOrder): array
    {
        return [
            $workOrder->id,
            $workOrder->elevator->client->name ?? 'N/A',
            $workOrder->elevator->rae_number ?? $workOrder->elevator->location_name ?? 'N/A',
            ucfirst($workOrder->type),
            strtoupper($workOrder->priority),
            ucfirst($workOrder->status),
            $workOrder->failure_category ?? 'Sin categoría',
            $workOrder->issue_description,
            $workOrder->work_done ?? '-',
            $workOrder->technician->name ?? 'Sin asignar',
            $workOrder->started_at ? date('d/m/Y H:i', strtotime($workOrder->started_at)) : '-',
            $workOrder->completed_at ? date('d/m/Y H:i', strtotime($workOrder->completed_at)) : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F2937']
                ],
            ],
        ];
    }
}