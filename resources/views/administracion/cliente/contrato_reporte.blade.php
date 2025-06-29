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
            padding: 8px;
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
            /*text-align: right;*/
        }

        .table-container {
            width: 48%;
            float: left;
            margin-right: 2%;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
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
            <td class="no-border header-title" style="width: 50%;text-align: right;"  >
                Estado de Cuenta, Contrato No {{ $contrato->number }}
            </td>
        </tr>
    </table>

    <div class="clearfix">
        <!-- Tabla Cliente -->
        <div class="table-container">
            <table>
                <tr>
                    <th colspan="2">Cliente</th>
                </tr>
                <tr>
                    <th>Nombre</th>
                    <td>{{ $contrato->cliente->name ?? '' }} {{ $contrato->cliente->lastname ?? '' }}</td>
                </tr>
                <tr>
                    <th>Documento</th>
                    <td>{{ $contrato->cliente->identification ?? '' }}</td>
                </tr>
                <tr>
                    <th>Teléfono</th>
                    <td>{{ $contrato->cliente->description ?? '' }}</td>
                </tr>
                <tr>
                    <th>Dirección</th>
                    <td>{{ $contrato->cliente->address ?? '' }}</td>
                </tr>
                <tr>
                    <th>Empresa</th>
                    <td>{{ $contrato->cliente->empresa->name ?? '' }}</td>
                </tr>
            </table>
        </div>

        <!-- Tabla Contrato -->
        <div class="table-container" style="margin-right: 0;">
            <table>
                <tr>
                    <th colspan="2">Contrato</th>
                </tr>
                <tr>
                    <th>Tipo pago</th>
                    <td>{{ $contrato->payment_type }}</td>
                </tr>
                <tr>
                    <th>Plazo</th>
                    <td>{{ $contrato->term }} Meses</td>
                </tr>
                <tr>
                    <th>Monto USD</th>
                    <td>{{ number_format($contrato->amount, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <th>Adelanto</th>
                    <td>{{ $contrato->payment_day }}</td>
                </tr>
                <tr>
                    <th>Saldo USD</th>
                    <td>{{ number_format($contrato->remaining, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <th>Fecha</th>
                    <td>{{ $contrato->date ? date('d/m/Y', strtotime($contrato->date)) : '' }}</td>
                </tr>
                <tr>
                    <th>Cuota USD</th>
                    <td>{{ number_format($contrato->monthly_payment, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <th>Estado</th>
                    <td>{{ $contrato->estado->description ?? '' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="no-border header-title">
        Detalle del Contrato:
    </div>
    <table>
        <tr>
            <th>Cant.</th>
            <th>SKU</th>
            <th>Descripción</th>
            <th>Ojo Izq.</th>
            <th>Ojo Der.</th>
            <th>Precio</th>
            <th>Desc.</th>
            <th>Sub Totl.</th>
        </tr>
        @php($total = 0)
        @foreach ($contrato->detalles as $detalle)
            @php($subTotal = $detalle->price * $detalle->quantity - $detalle->discount)
            @php($total += $subTotal)
            <tr>
                <td>{{ $detalle->quantity }}</td>
                <td>{{ $detalle->producto->sku ?? '' }}</td>
                <td>{{ $detalle->producto->description ?? '' }}</td>
                <td>{{ $detalle->left_eye_graduation }}</td>
                <td>{{ $detalle->right_eye_graduation }}</td>
                <td>${{ number_format($detalle->price, 2, '.', ',') }}</td>
                <td>${{ number_format($detalle->discount, 2, '.', ',') }}</td>
                <td>${{ number_format($subTotal, 2, '.', ',') }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="7">Total USD</th>
            <th>${{ number_format($total, 2, '.', ',') }}</th>
        </tr>


    </table>


    <div class="no-border header-title">
        Detalle de Recibos:
    </div>
    <table>
        <tr>
            <th>Número</th>
            <th>Fecha</th>
            <th>Monto</th>
        </tr>
        @php($total = 0)
        @foreach ($contrato->abonos as $abono)
            @php($total += $abono->amount)
            <tr>
                <td>{{ $abono->number }}</td>
                <td>{{ $abono->date ? date('d/m/Y', strtotime($abono->date)) : '' }}</td>
                <td>${{ number_format($abono->amount, 2, '.', ',') }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="2">Total USD</th>
            <th>${{ number_format($total, 2, '.', ',') }}</th>
        </tr>


    </table>

</body>

</html>
