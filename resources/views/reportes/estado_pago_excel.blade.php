<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Estados de pago</title>

    <?php
    // Establecer cabeceras para que el navegador lo reconozca como un archivo Excel
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="estado_cuenta.xls"');
    header('Cache-Control: max-age=0');
    ?>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }

        thead {
            background-color: #d8d8d8;
            /* color similar a Bootstrap 'table-info' */
        }

        th,
        td {
            border: 1px solid #ccc;
            text-align: left;
        }

        td:last-child {
            text-align: right;
        }



        .top-category-name {
            display: inline-block;
        }

        tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        .Estilo1 {
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
        }

        body,
        table,
        th,
        td {
            font-size: 10px;
            /* Puedes ajustar a 9px o 8px si deseas aún más pequeño */
        }

        .text-end {
            text-align: right;
        }
    </style>
</head>

<body>

    <table width="100%" border="0">
        <tr style="background-color: #ffffff;">
            {{-- <td style="border: 0">
                <div align="center"><img src="{{ public_path('imagenes/logo.jpg') }}" width="100px"> </div>
            </td> --}}
            <td style="border: 0" colspan="12">
                <div align="center"><strong>Estado de pagos</strong></div>
            </td>
        </tr>
    </table>

    <br>
    <span class="Estilo1">Fecha y hora: {{ date('d/m/Y H:m') }} </span>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha servicio</th>
                <th>Cliente</th>
                <th>Cantidad</th>
                <th>Vendedor</th>
                <th>Fecha último pago</th>
                <th>Sin vencer</th>
                <th>30 dias</th>
                <th>60 dias</th>
                <th>90 dias</th>
                <th>120 dias</th>
                <th>Más dias</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($registros as $item)
                <tr>
                    <td>{{ $item->number }}</td>
                    <td>{{ $item->date }}</td>
                    <td>{{ $item->client }}</td>
                    <td class="text-end">
                        {{ $item->amount !== null ? '$' . number_format($item->amount, 2) : '' }}</td>
                    <td>{{ $item->seller }}</td>
                    <td>
                        {{ $item->fecha_ultimo_recibo ? date('d/m/Y', strtotime($item->fecha_ultimo_recibo)) : '' }}
                    </td>

                    <td class="text-end">
                        @if ($item->dias_desde_ultimo_pago < 30)
                            {{ $item->monthly_payment !== null ? '$' . number_format($item->monthly_payment, 2) : '' }}
                        @endif
                    </td>
                    <td class="text-end">
                        @if ($item->dias_desde_ultimo_pago >= 30 && $item->dias_desde_ultimo_pago < 60)
                            {{ $item->monthly_payment !== null ? '$' . number_format($item->monthly_payment, 2) : '' }}
                        @endif
                    </td>

                    <td class="text-end">
                        @if ($item->dias_desde_ultimo_pago >= 60 && $item->dias_desde_ultimo_pago < 90)
                            {{ $item->monthly_payment !== null ? '$' . number_format($item->monthly_payment, 2) : '' }}
                        @endif
                    </td>

                    <td class="text-end">
                        @if ($item->dias_desde_ultimo_pago >= 90 && $item->dias_desde_ultimo_pago < 120)
                            {{ $item->monthly_payment !== null ? '$' . number_format($item->monthly_payment, 2) : '' }}
                        @endif
                    </td>
                    <td class="text-end">
                        @if ($item->dias_desde_ultimo_pago == 120)
                            {{ $item->monthly_payment !== null ? '$' . number_format($item->monthly_payment, 2) : '' }}
                        @endif
                    </td>

                    <td class="text-end">
                        @if ($item->dias_desde_ultimo_pago > 120)
                            {{ $item->monthly_payment !== null ? '$' . number_format($item->monthly_payment, 2) : '' }}
                        @endif
                    </td>


                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
