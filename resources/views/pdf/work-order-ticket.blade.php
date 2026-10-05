<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Parte de Trabajo #{{ $workOrder->id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 22px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .title {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            color: #4b5563;
        }
        .section-title {
            background-color: #1e3a8a;
            color: #ffffff;
            padding: 5px 8px;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            margin-top: 15px;
            margin-bottom: 8px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
            border-bottom: 1px solid #e5e7eb;
        }
        .label {
            font-weight: bold;
            color: #4b5563;
            width: 25%;
        }
        .box-content {
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            padding: 10px;
            min-height: 50px;
            border-radius: 4px;
        }
        .signatures-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .signatures-table td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            padding: 10px;
        }
        .signature-box {
            border-top: 1px solid #9ca3af;
            margin-top: 5px;
            padding-top: 5px;
            font-weight: bold;
        }
        .signature-container {
            height: 80px;
            text-align: center;
            vertical-align: bottom;
        }
        .signature-img {
            max-height: 75px;
            max-width: 200px;
            display: inline-block;
        }
        .badge {
            display: inline-block;
            padding: 3px 6px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
            background-color: #e5e7eb;
            color: #1f2937;
        }
    </style>
</head>
<body>

    {{-- Cabecera --}}
    <table class="header-table">
        <tr>
            <td class="logo">ASCENSORES ELEVA, S.L.</td>
            <td class="title">PARTE DE ASISTENCIA TÉCNICA<br><span style="font-size: 12px; color: #6b7280;">N° OT-{{ str_pad($workOrder->id, 5, '0', STR_PAD_LEFT) }}</span></td>
        </tr>
    </table>

    {{-- Datos Generales del Cliente y Ascensor --}}
    <div class="section-title">1. Datos de Localización y Equipo</div>
    <table class="info-table">
        <tr>
            <td class="label">Cliente:</td>
            <td>{{ $workOrder->elevator->client->name ?? 'N/A' }}</td>
            <td class="label">Categoría Avería:</td>
            <td>{{ $workOrder->failure_category ?? 'Sin especificar' }}</td>
        </tr>
        <tr>
            <td class="label">Ubicación / RAE:</td>
            <td>{{ $workOrder->elevator->location_name ?? $workOrder->elevator->rae_number ?? 'N/A' }}</td>
            <td class="label">Tipo Intervención:</td>
            <td><span class="badge">{{ ucfirst($workOrder->type) }}</span></td>
        </tr>
        <tr>
            <td class="label">Técnico Asignado:</td>
            <td>{{ $workOrder->technician->name ?? 'Sin asignar' }}</td>
            <td class="label">Prioridad / Estado:</td>
            <td>
                <span class="badge">{{ strtoupper($workOrder->priority) }}</span>
                <span class="badge">{{ ucfirst($workOrder->status) }}</span>
            </td>
        </tr>
        <tr>
            <td class="label">Fecha Inicio:</td>
            <td>{{ $workOrder->started_at ? date('d/m/Y H:i', strtotime($workOrder->started_at)) : '-' }}</td>
            <td class="label">Fecha Fin:</td>
            <td>{{ $workOrder->completed_at ? date('d/m/Y H:i', strtotime($workOrder->completed_at)) : '-' }}</td>
        </tr>
    </table>

    {{-- Descripción de la Avería / Motivo --}}
    <div class="section-title">2. Descripción del Problema / Motivo de la Visita</div>
    <div class="box-content">
        {{ $workOrder->issue_description }}
    </div>

    {{-- Trabajos Realizados --}}
    <div class="section-title">3. Trabajos Realizados y Observaciones</div>
    <div class="box-content">
        {{ $workOrder->work_done ?: 'No se han registrado detalles sobre el trabajo realizado.' }}
    </div>

    {{-- Firmas --}}
    <table class="signatures-table">
        <tr>
            <td>
                <div class="signature-container"></div>
                <div class="signature-box">Firma del Técnico</div>
            </td>
            <td>
                <div class="signature-container">
                    @if($workOrder->client_signature && file_exists(storage_path('app/public/' . $workOrder->client_signature)))
                        <img src="{{ storage_path('app/public/' . $workOrder->client_signature) }}" class="signature-img" alt="Firma Cliente">
                    @elseif($workOrder->client_signature && file_exists(public_path('storage/' . $workOrder->client_signature)))
                        <img src="{{ public_path('storage/' . $workOrder->client_signature) }}" class="signature-img" alt="Firma Cliente">
                    @endif
                </div>
                <div class="signature-box">Firma / Conformidad del Cliente</div>
            </td>
        </tr>
    </table>

</body>
</html>