<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Comisiones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            /* tamaño de fuente reducido */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: Arial, sans-serif;
        }

        thead {
            background-color: #d8d8d8;
            /* color similar a Bootstrap 'table-info' */
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }

        td:last-child {
            text-align: right;
        }

        .fw-medium {
            font-weight: 500;
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
            font-size: 10px;
        }

        .Estilo2 {
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <table width="100%" border="0">
        <tr style="background-color: #ffffff;">
            <td style="border: 0">
                <img src="{{ public_path('imagenes/logo.jpg') }}" width="100px">
            </td>
            <td style="border: 0;text-align: left">
                <span class="Estilo2">Reporte de Comisiones</span>
            </td>
        </tr>
    </table>



    <table class="sub-head" cellspacing="0" cellspadding="0">
        <tr>
            <th style="text-align:center" colspan="2"><?php echo $vendedor->name . ' ' . $vendedor->last_name; ?></th>
        </tr>
        <tr>
            <th>Porcentaje por venta</th>
            <td>{{ $sales_percentage }}%</td>
        </tr>
        <tr>
            <th>Porcentaje por cobro</th>
            <td>{{ $collection_percentage }}%</td>
        </tr>
        <tr>
            <th>Fecha y hora</th>
            <td><span class="Estilo1">{{ date('d/m/Y H:m') }} </span></td>
        </tr>
    </table>

    <br>
    @php($total_general = 0)
    <span class="Estilo2">Detalle por venta:</span>
    <table>
        <thead>
            <tr>
                <th>Contrato</th>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Monto sin iva</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php($total = 0)
            @if ($ventas->count() > 0)

                @foreach ($ventas as $item)
                    @php($sinIva = $item->amount / 1.13)
                    @php($subtotal = $sinIva * ($sales_percentage / 100))
                    <tr>
                        <td>{{ $item->number }}</td>
                        <td>{{ $item->date }}</td>
                        <td style="text-align: right">
                            ${{ number_format($item->amount, 2) }}
                        </td>
                        <td style="text-align: right">
                            ${{ number_format($sinIva, 2) }}
                        </td>
                        <td style="text-align: right">
                            ${{ number_format($subtotal, 2) }}
                        </td>

                    </tr>
                    @php($total += $subtotal)
                @endforeach
                @php($total_general += $total)
            @else
                <tr>
                    <td colspan="5" class="empty">
                        <div align="center">Contrato vac&iacute;o</div>
                    </td>
                </tr>
            @endif

            <tr>
                <td colspan="4">
                    <div align="center"><strong>TOTAL </strong>
                    </div>
                </td>
                <td style="text-align: right">
                    <strong>${{ number_format($total, 2) }} </strong>
                </td>

            </tr>

        </tbody>
    </table>

    <br />
    <span class="Estilo2">Detalle por cobro:</span>

    <table>
        <thead>
            <tr>
                <th>Recibo</th>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Monto sin iva</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php($total = 0)
            @if ($recaudado->count() > 0)

                @foreach ($recaudado as $item)
                    @php($sinIva = $item->amount / 1.13)
                    @php($subtotal = $sinIva * ($collection_percentage / 100))
                    <tr>
                        <td>{{ $item->number }}</td>
                        <td>{{ $item->date }}</td>
                        <td style="text-align: right">
                            ${{ number_format($item->amount, 2) }}
                        </td>
                        <td style="text-align: right">
                            ${{ number_format($sinIva, 2) }}
                        </td>
                        <td style="text-align: right">
                            ${{ number_format($subtotal, 2) }}
                        </td>

                    </tr>
                    @php($total += $subtotal)
                @endforeach
                @php($total_general += $total)
            @else
                <tr>
                    <td colspan="5" class="empty">
                        <div align="center">Contrato vac&iacute;o</div>
                    </td>
                </tr>
            @endif

            <tr>
                <td colspan="4">
                    <div align="center"><strong>TOTAL </strong>
                    </div>
                </td>
                <td style="text-align: right">
                    <strong>${{ number_format($total, 2) }} </strong>
                </td>

            </tr>

        </tbody>
    </table>

    <br />
    <span class="Estilo2">Detalle por Cobro Adelantado:</span>

    <table>
        <thead>
            <tr>
                <th>Recibo</th>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Monto sin iva</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php($total = 0)
            @if ($ventas_anticipadas->count() > 0)

                @foreach ($ventas_anticipadas as $item)
                    @php($sinIva = $item->amount / 1.13)
                    @php($subtotal = $sinIva * 0.04)
                    <tr>
                        <td>{{ $item->number }}</td>
                        <td>{{ $item->date }}</td>
                        <td style="text-align: right">
                            ${{ number_format($item->amount, 2) }}
                        </td>
                        <td style="text-align: right">
                            ${{ number_format($sinIva, 2) }}
                        </td>
                        <td style="text-align: right">
                            ${{ number_format($subtotal, 2) }}
                        </td>

                    </tr>
                    @php($total += $subtotal)
                @endforeach
                @php($total_general += $total)
            @else
                <tr>
                    <td colspan="5" class="empty">
                        <div align="center">Contrato vac&iacute;o</div>
                    </td>
                </tr>
            @endif

            <tr>
                <td colspan="4"><strong>TOTAL </strong>
                </td>
                <td style="text-align: right">
                    <strong>${{ number_format($total, 2) }} </strong>
                </td>

            </tr>

        </tbody>
    </table>


    <br><br>
    <table class="detail" cellspacing="0" cellspadding="0">
        <tr>
            <th>Total a Pagar USD:</th>
            <td><strong>${{ number_format($total_general, 2) }} </strong></td>
        </tr>
    </table>

</body>

</html>
