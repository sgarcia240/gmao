<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 24px 28px;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 0;
            line-height: 1.45;
        }
        .mono {
            font-family: 'DejaVu Sans Mono', monospace;
        }

        /* Cabecera ejecutiva oscura */
        .header-wrapper {
            width: 100%;
            background-color: #070b14;
            color: #ffffff;
            border-radius: 10px;
            border-bottom: 4px solid #06b6d4;
            margin-bottom: 22px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            padding: 20px 22px;
            vertical-align: top;
        }
        .brand-badge {
            display: inline-block;
            font-size: 8.5px;
            font-weight: bold;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #22d3ee;
            background-color: #0f172a;
            border: 1px solid #1e293b;
            padding: 3px 8px;
            border-radius: 4px;
            margin-bottom: 6px;
        }
        .company-title {
            font-size: 17px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: -0.3px;
            margin-bottom: 4px;
        }
        .company-meta {
            font-size: 9.5px;
            color: #94a3b8;
            line-height: 1.5;
        }
        .invoice-label {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #ffffff;
            margin-bottom: 6px;
        }
        .invoice-number-pill {
            display: inline-block;
            background-color: #0f172a;
            border: 1px solid #334155;
            color: #22d3ee;
            font-size: 11px;
            font-weight: bold;
            padding: 4px 10px;
            border-radius: 5px;
            margin-bottom: 8px;
        }
        .invoice-dates {
            font-size: 9.5px;
            color: #cbd5e1;
            line-height: 1.6;
        }
        .invoice-dates strong {
            color: #94a3b8;
            font-weight: normal;
        }

        /* Caja de datos del cliente */
        .client-box {
            width: 100%;
            border-collapse: collapse;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #2563eb;
            border-radius: 6px;
            margin-bottom: 22px;
        }
        .client-box td {
            padding: 13px 16px;
        }
        .section-eyebrow {
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #2563eb;
            margin-bottom: 5px;
        }
        .client-name {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 3px;
        }
        .client-details {
            font-size: 10px;
            color: #475569;
        }

        /* Tabla de líneas de factura */
        .lines-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .lines-table th {
            background-color: #0f172a;
            color: #f8fafc;
            padding: 9px 12px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            border-bottom: 2px solid #06b6d4;
        }
        .lines-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
            font-size: 10.5px;
        }
        .lines-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .item-desc {
            font-weight: bold;
            color: #0f172a;
        }
        .wo-tag {
            display: inline-block;
            margin-top: 3px;
            font-size: 8.5px;
            font-weight: bold;
            color: #0369a1;
            background-color: #e0f2fe;
            padding: 1px 6px;
            border-radius: 3px;
        }

        /* Bloque de totales */
        .summary-wrapper {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .notes-cell {
            width: 55%;
            vertical-align: top;
            padding-right: 20px;
        }
        .notes-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 9px;
            color: #64748b;
        }
        .totals-cell {
            width: 45%;
            vertical-align: top;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        .totals-table td {
            padding: 8px 12px;
            font-size: 10.5px;
            border-bottom: 1px solid #e2e8f0;
        }
        .totals-table td.label {
            color: #475569;
            text-align: left;
            font-weight: bold;
        }
        .totals-table td.amount {
            color: #0f172a;
            text-align: right;
            font-weight: bold;
        }
        .totals-table tr.grand-total td {
            background-color: #070b14;
            color: #ffffff;
            border-bottom: none;
            padding: 11px 12px;
            font-size: 12.5px;
        }
        .totals-table tr.grand-total td.amount {
            color: #22d3ee;
            font-size: 13.5px;
        }

        /* Pie de página legal */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            text-align: center;
            font-size: 8.5px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <!-- CABECERA EJECUTIVA -->
    <div class="header-wrapper">
        <table class="header-table">
            <tr>
                <td width="56%">
                    <div class="brand-badge">TRACKHELM ENTERPRISE GMAO</div>
                    <div class="company-title">GMAO ASCENSORES S.L.</div>
                    <div class="company-meta">
                        CIF: <span class="mono" style="color: #cbd5e1;">B-12345678</span><br>
                        Calle Mantenimiento 45, Planta 2<br>
                        Tel: 900 100 200 &nbsp;|&nbsp; info@gmao-ascensores.es
                    </div>
                </td>
                <td width="44%" style="text-align: right;">
                    <div class="invoice-label">FACTURA</div>
                    <div class="invoice-number-pill mono">{{ $invoice->invoice_number }}</div>
                    <div class="invoice-dates">
                        <strong>Fecha emisión:</strong> <span class="mono">{{ $invoice->issue_date->format('d/m/Y') }}</span><br>
                        <strong>Vencimiento:</strong> <span class="mono">{{ $invoice->due_date->format('d/m/Y') }}</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- DATOS FISCALES DEL CLIENTE -->
    <table class="client-box">
        <tr>
            <td>
                <div class="section-eyebrow">Datos Fiscales del Cliente</div>
                <div class="client-name">{{ $invoice->client->name ?? 'N/A' }}</div>
                <div class="client-details">
                    <strong>CIF / NIF:</strong> <span class="mono">{{ $invoice->client->cif_nif ?? 'N/A' }}</span>
                    &nbsp;&nbsp;&bull;&nbsp;&nbsp;
                    <strong>Dirección:</strong> {{ $invoice->client->address ?? 'N/A' }}
                </div>
            </td>
        </tr>
    </table>

    <!-- LÍNEAS DE FACTURACIÓN -->
    <table class="lines-table">
        <thead>
            <tr>
                <th width="52%">Descripción del Servicio / Intervención</th>
                <th width="12%" style="text-align: center;">Cant.</th>
                <th width="18%" style="text-align: right;">Precio Un.</th>
                <th width="18%" style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>
                        <div class="item-desc">{{ $item->description }}</div>
                        @if($item->work_order_id)
                            <span class="wo-tag mono">OT #{{ $item->work_order_id }}</span>
                        @endif
                    </td>
                    <td class="mono" style="text-align: center; color: #334155;">{{ $item->quantity }}</td>
                    <td class="mono" style="text-align: right; color: #334155;">{{ number_format($item->unit_price, 2, ',', '.') }} €</td>
                    <td class="mono" style="text-align: right; font-weight: bold; color: #0f172a;">{{ number_format($item->total_price, 2, ',', '.') }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- RESUMEN DE TOTALES Y NOTAS -->
    <table class="summary-wrapper">
        <tr>
            <td class="notes-cell">
                <div class="notes-box">
                    <strong style="color: #334155;">Información de pago y registro:</strong><br>
                    Documento emitido electrónicamente mediante TrackHelm Enterprise GMAO. Para cualquier consulta administrativa sobre esta factura, indique la referencia <span class="mono">{{ $invoice->invoice_number }}</span>.
                </div>
            </td>
            <td class="totals-cell">
                <table class="totals-table">
                    <tr>
                        <td class="label">Base Imponible</td>
                        <td class="amount mono">{{ number_format($invoice->subtotal, 2, ',', '.') }} €</td>
                    </tr>
                    <tr>
                        <td class="label">IVA ({{ number_format($invoice->tax_rate, 0) }}%)</td>
                        <td class="amount mono">{{ number_format($invoice->tax_amount, 2, ',', '.') }} €</td>
                    </tr>
                    <tr class="grand-total">
                        <td class="label" style="color: #ffffff;">TOTAL FACTURA</td>
                        <td class="amount mono">{{ number_format($invoice->total, 2, ',', '.') }} €</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- PIE DE PÁGINA -->
    <div class="footer">
        GMAO ASCENSORES S.L. &bull; CIF B-12345678 &bull; Documento generado por TrackHelm Enterprise GMAO 4.0
    </div>

</body>
</html>