<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; margin: 20px; }
        .header-table { width: 100%; margin-bottom: 25px; }
        .company-title { font-size: 20px; font-weight: bold; color: #1e3a8a; }
        .invoice-title { font-size: 22px; font-weight: bold; text-align: right; color: #1e40af; }
        .box { border: 1px solid #e5e7eb; padding: 12px; border-radius: 6px; background-color: #f9fafb; }
        .lines-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .lines-table th { background-color: #1e3a8a; color: white; padding: 8px; text-align: left; font-size: 11px; }
        .lines-table td { padding: 8px; border-bottom: 1px solid #e5e7eb; }
        .totals-table { width: 40%; float: right; margin-top: 20px; border-collapse: collapse; }
        .totals-table td { padding: 6px; text-align: right; }
        .totals-table .total-row { font-size: 14px; font-weight: bold; background-color: #f3f4f6; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td width="50%">
                <div class="company-title">GMAO ASCENSORES S.L.</div>
                <div>CIF: B-12345678</div>
                <div>Calle Mantenimiento 45, Planta 2</div>
                <div>Tel: 900 100 200 | info@gmao-ascensores.es</div>
            </td>
            <td width="50%" style="text-align: right;">
                <div class="invoice-title">FACTURA</div>
                <div><strong>Nº:</strong> {{ $invoice->invoice_number }}</div>
                <div><strong>Fecha:</strong> {{ $invoice->issue_date->format('d/m/Y') }}</div>
                <div><strong>Vencimiento:</strong> {{ $invoice->due_date->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <div class="box">
        <strong>DATOS DEL CLIENTE:</strong><br>
        <strong>Razón Social:</strong> {{ $invoice->client->name ?? 'N/A' }}<br>
        <strong>CIF/NIF:</strong> {{ $invoice->client->cif_nif ?? 'N/A' }}<br>
        <strong>Dirección:</strong> {{ $invoice->client->address ?? 'N/A' }}
    </div>

    <table class="lines-table">
        <thead>
            <tr>
                <th>DESCRIPCIÓN</th>
                <th style="text-align: center;">CANT.</th>
                <th style="text-align: right;">PRECIO UN.</th>
                <th style="text-align: right;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>
                        {{ $item->description }}
                        @if($item->work_order_id)
                            <br><small style="color: #6b7280;">(OT #{{ $item->work_order_id }})</small>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->unit_price, 2, ',', '.') }} €</td>
                    <td style="text-align: right;">{{ number_format($item->total_price, 2, ',', '.') }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td><strong>Base Imponible:</strong></td>
            <td>{{ number_format($invoice->subtotal, 2, ',', '.') }} €</td>
        </tr>
        <tr>
            <td><strong>IVA ({{ number_format($invoice->tax_rate, 0) }}%):</strong></td>
            <td>{{ number_format($invoice->tax_amount, 2, ',', '.') }} €</td>
        </tr>
        <tr class="total-row">
            <td><strong>TOTAL FACTURA:</strong></td>
            <td>{{ number_format($invoice->total, 2, ',', '.') }} €</td>
        </tr>
    </table>

</body>
</html>