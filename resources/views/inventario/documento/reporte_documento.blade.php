<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Documento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 30px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 4px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .no-border {
            border: none !important;
        }

        .header-title {
            font-size: 14px;
            font-weight: bold;
            color: #34495e;
            text-align: right;
        }
    </style>
</head>

<body>

    <!-- Encabezado con logo y título usando tabla -->
    <table class="no-border">
        <tr>
            <td class="no-border" style="width: 50%;">
                <img src="{{ public_path('assets/images/logo.jpg') }}" alt="Logo" style="height: 60px;">
            </td>
            <td class="no-border header-title" style="width: 50%;">
                Reporte de Documento<br>
                N.º {{ $documento->doc_number }}
            </td>
        </tr>
    </table>

    <!-- Primera tabla: Datos generales -->
    <table>
        <tr>
            <th>Tipo Documento</th>
            <td>{{ $documento->tipoDocumento->name ?? '' }}</td>
        </tr>
        <tr>
            <th>Justificación</th>
            <td>{{ $documento->justification }}</td>
        </tr>
        <tr>
            <th>Fecha de creación</th>
            <td>{{ $documento->created_at ? date('d/m/Y H:i', strtotime($documento->created_at)) : '' }}</td>
        </tr>
        <tr>
            <th>Estado</th>
            <td>{{ $documento->estado->description ?? '' }}</td>
        </tr>
    </table>

    <!-- Segunda tabla: Detalles del documento -->
    <table>
        <tr>
            <th>SKU</th>
            <th>Descripción</th>
            <th>Color</th>
            <th>Cantidad</th>
        </tr>
        @foreach ($documento->detalles as $detalle)
            <tr>
                <td>{{ $detalle->producto->sku ?? '' }}</td>
                <td>{{ $detalle->producto->description ?? '' }}</td>
                <td>{{ $detalle->producto->color ?? '' }}</td>
                <td>{{ $detalle->quantity }}</td>
            </tr>
        @endforeach
    </table>

</body>

</html>
