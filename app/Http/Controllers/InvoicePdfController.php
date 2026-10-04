<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePdfController extends Controller
{
    public function stream(Invoice $invoice)
    {
        // Cargar las relaciones necesarias
        $invoice->load(['client', 'items.workOrder']);

        // Cargar la vista Blade y generar el PDF
        $pdf = Pdf::loadView('pdf.invoice-template', compact('invoice'));

        // Mostrar en el navegador
        return $pdf->stream("Factura-{$invoice->invoice_number}.pdf");
    }
}