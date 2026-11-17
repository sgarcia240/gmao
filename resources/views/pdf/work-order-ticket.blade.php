<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Parte de Trabajo #{{ $workOrder->id }}</title>
    <style>
        @page {
            margin: 24px 28px;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.45;
            margin: 0;
            padding: 0;
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
            margin-bottom: 20px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            padding: 18px 22px;
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
            margin-bottom: 3px;
        }
        .company-sub {
            font-size: 9.5px;
            color: #94a3b8;
        }
        .doc-title {
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.6px;
            color: #ffffff;
            margin-bottom: 7px;
        }
        .ot-number-pill {
            display: inline-block;
            background-color: #0f172a;
            border: 1px solid #334155;
            color: #22d3ee;
            font-size: 11px;
            font-weight: bold;
            padding: 4px 10px;
            border-radius: 5px;
        }

        /* Títulos de sección */
        .section-bar {
            width: 100%;
            border-collapse: collapse;
            background-color: #0f172a;
            color: #ffffff;
            border-left: 4px solid #06b6d4;
            border-radius: 4px;
            margin-top: 16px;
            margin-bottom: 8px;
        }
        .section-bar td {
            padding: 6px 12px;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        /* Tabla de información técnica */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e2e8f0;
            margin-bottom: 12px;
        }
        .info-table td {
            padding: 7px 10px;
            vertical-align: middle;
            border: 1px solid #e2e8f0;
            font-size: 10.5px;
        }
        .info-table td.label {
            font-weight: bold;
            color: #475569;
            background-color: #f8fafc;
            width: 21%;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .info-table td.value {
            color: #0f172a;
            width: 29%;
        }

        /* Cajas de contenido técnico */
        .box-content {
            border: 1px solid #cbd5e1;
            border-left: 4px solid #2563eb;
            background-color: #f8fafc;
            padding: 12px 14px;
            min-height: 58px;
            border-radius: 5px;
            color: #1e293b;
            font-size: 10.5px;
            line-height: 1.5;
        }

        /* Insignias */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            font-size: 8.5px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
            background-color: #e2e8f0;
            color: #1e293b;
        }
        .badge-blue {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        /* Bloque de firmas */
        .signatures-table {
            width: 100%;
            margin-top: 22px;
            border-collapse: separate;
            border-spacing: 14px 0;
        }
        .signatures-table td {
            width: 50%;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background-color: #ffffff;
            padding: 10px 14px;
            text-align: center;
            vertical-align: bottom;
        }
        .signature-container {
            height: 82px;
            text-align: center;
            vertical-align: middle;
        }
        .signature-img {
            max-height: 76px;
            max-width: 210px;
            display: inline-block;
        }
        .signature-box {
            border-top: 1px dashed #94a3b8;
            margin-top: 6px;
            padding-top: 6px;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #334155;
        }
        .signature-sub {
            font-size: 8.5px;
            font-weight: normal;
            text-transform: none;
            color: #64748b;
            margin-top: 2px;
        }

        /* Pie de página */
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

    {{-- CABECERA EJECUTIVA --}}
    <div class="header-wrapper">
        <table class="header-table">
            <tr>
                <td width="55%">
                    <div class="brand-badge">TRACKHELM ENTERPRISE GMAO</div>
                    <div class="company-title">ASCENSORES ELEVA, S.L.</div>
                    <div class="company-sub">Servicio Técnico Oficial de Mantenimiento y Conservación</div>
                </td>
                <td width="45%" style="text-align: right;">
                    <div class="doc-title">PARTE DE ASISTENCIA TÉCNICA</div>
                    <div class="ot-number-pill mono">N° OT-{{ str_pad($workOrder->id, 5, '0', STR_PAD_LEFT) }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- 1. DATOS GENERALES DEL CLIENTE Y ASCENSOR --}}
    <table class="section-bar">
        <tr>
            <td>01 &bull; Datos de Localización y Equipo</td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td class="label">Cliente</td>
            <td class="value"><strong>{{ $workOrder->elevator->client->name ?? 'N/A' }}</strong></td>
            <td class="label">Categoría Avería</td>
            <td class="value">{{ $workOrder->failure_category ?? 'Sin especificar' }}</td>
        </tr>
        <tr>
            <td class="label">Ubicación / RAE</td>
            <td class="value mono" style="font-size: 10px;">
                {{ $workOrder->elevator->location_name ?? $workOrder->elevator->rae_number ?? 'N/A' }}
            </td>
            <td class="label">Tipo Intervención</td>
            <td class="value">
                <span class="badge badge-blue">{{ ucfirst($workOrder->type) }}</span>
            </td>
        </tr>
        <tr>
            <td class="label">Técnico Asignado</td>
            <td class="value">{{ $workOrder->technician->name ?? 'Sin asignar' }}</td>
            <td class="label">Prioridad / Estado</td>
            <td class="value">
                @php
                    $prioClass = match($workOrder->priority) {
                        'person_trapped' => 'badge-danger',
                        'urgent' => 'badge-warning',
                        default => '',
                    };
                    $statusClass = in_array($workOrder->status, ['completed', 'invoiced']) ? 'badge-success' : 'badge-blue';
                @endphp
                <span class="badge {{ $prioClass }}">{{ strtoupper($workOrder->priority) }}</span>
                <span class="badge {{ $statusClass }}">{{ ucfirst($workOrder->status) }}</span>
            </td>
        </tr>
        <tr>
            <td class="label">Fecha Inicio</td>
            <td class="value mono" style="font-size: 10px;">
                {{ $workOrder->started_at ? date('d/m/Y H:i', strtotime($workOrder->started_at)) : '-' }}
            </td>
            <td class="label">Fecha Fin</td>
            <td class="value mono" style="font-size: 10px;">
                {{ $workOrder->completed_at ? date('d/m/Y H:i', strtotime($workOrder->completed_at)) : '-' }}
            </td>
        </tr>
    </table>

    {{-- 2. DESCRIPCIÓN DE LA AVERÍA / MOTIVO --}}
    <table class="section-bar">
        <tr>
            <td>02 &bull; Descripción del Problema / Motivo de la Visita</td>
        </tr>
    </table>
    <div class="box-content">
        {{ $workOrder->issue_description }}
    </div>

    {{-- 3. TRABAJOS REALIZADOS --}}
    <table class="section-bar">
        <tr>
            <td>03 &bull; Trabajos Realizados y Observaciones</td>
        </tr>
    </table>
    <div class="box-content">
        {{ $workOrder->work_done ?: 'No se han registrado detalles sobre el trabajo realizado.' }}
    </div>

    {{-- FIRMAS --}}
    <table class="signatures-table">
        <tr>
            <td>
                <div class="signature-container"></div>
                <div class="signature-box">
                    Firma del Técnico
                    <div class="signature-sub">{{ $workOrder->technician->name ?? 'Operario autorizado' }}</div>
                </div>
            </td>
            <td>
                <div class="signature-container">
                    @if($workOrder->client_signature && file_exists(storage_path('app/public/' . $workOrder->client_signature)))
                        <img src="{{ storage_path('app/public/' . $workOrder->client_signature) }}" class="signature-img" alt="Firma Cliente">
                    @elseif($workOrder->client_signature && file_exists(public_path('storage/' . $workOrder->client_signature)))
                        <img src="{{ public_path('storage/' . $workOrder->client_signature) }}" class="signature-img" alt="Firma Cliente">
                    @endif
                </div>
                <div class="signature-box">
                    Firma / Conformidad del Cliente
                    <div class="signature-sub">Titular / Representante de la finca</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- PIE DE PÁGINA --}}
    <div class="footer">
        ASCENSORES ELEVA, S.L. &bull; Documento técnico emitido por TrackHelm Enterprise GMAO 4.0
    </div>

</body>
</html>