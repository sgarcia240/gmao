<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;

class WorkOrderPdfController extends Controller
{
    public function download($id)
    {
        // Cargar orden con las relaciones jerárquicas correctas (elevator -> location -> client)
        $workOrder = WorkOrder::with(['elevator.location.client', 'technician'])->findOrFail($id);

        // Cargar vista HTML y ajustar formato
        $pdf = DomPdf::loadView('pdf.work-order-ticket', compact('workOrder'))
            ->setPaper('a4', 'portrait');

        $filename = 'parte-trabajo-OT-' . str_pad($workOrder->id, 5, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }

    public function stream($id)
    {
        $workOrder = WorkOrder::with(['elevator.location.client', 'technician'])->findOrFail($id);

        $pdf = DomPdf::loadView('pdf.work-order-ticket', compact('workOrder'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('parte-trabajo-OT-' . str_pad($workOrder->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }
}