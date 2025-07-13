    <!DOCTYPE html>
    <html lang="es">

    <head>
        <?php
        // Establecer cabeceras para que el navegador lo reconozca como un archivo Excel
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="reporte.xls"');
        header('Cache-Control: max-age=0');
        ?>
        <meta charset="UTF-8">
        <title>Reporte de ventas</title>
        <style>
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
            }
        </style>
    </head>

    <body>

        <table width="100%" border="0">
            <tr style="background-color: #ffffff;">

                <td style="border: 0">
                    <div align="center"><strong>Reporte de ventas de
                            {{ !empty($fechaInicio) ? date('d/m/Y', strtotime($fechaInicio)) : '' }} a
                            {{ !empty($fechaFinal) ? ' - ' . date('d/m/Y', strtotime($fechaFinal)) : '' }}
                        </strong></div>
                </td>
            </tr>
        </table>
        <br>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                    <th>Tipo pago</th>
                    <th>Pago mensual</th>
                    <th>Vendedor</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @php($totalGeneral = 0)
                @foreach ($contratos as $contrato)
                    @php($total = 0)
                    @foreach ($contrato->detalles as $detalle)
                        @php($subtotal = 0)
                        @if (!empty($detalle->quantity) && !empty($detalle->price))
                            @php($subtotal += $detalle->quantity * $detalle->price)
                        @endif
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($contrato->date)) }} </td>
                            <td>{{ $contrato->cliente->name ?? '' }}
                                {{ $contrato->cliente->lastname ?? '' }}</td>
                            <td>{{ $detalle->producto->sku ?? '' }}
                                {{ $detalle->producto->description ?? '' }}
                                {{ $detalle->producto->color ?? '' }}</td>
                            <td>{{ $detalle->quantity }}</td>
                            <td style="text-align: right">${{ $detalle->price }}</td>
                            <td style="text-align: right">
                                ${{ number_format($subtotal, 2) }}
                            </td>

                            <td>{{ $contrato->payment_type }}</td>
                            <td style="text-align: right">${{ $contrato->monthly_payment }}</td>
                            <td>
                                {{ $contrato->vendedores->map(fn($v) => $v->name . ' ' . $v->last_name)->implode(', ') }}
                            </td>
                            <td>{{ $contrato->estado->description ?? '' }}</td>
                        </tr>
                        @php($total += $subtotal)
                    @endforeach
                    <tr>
                        <td colspan="5" style="text-align: right"><strong>TOTAL CONTRATO
                                {{ $contrato->number }}</strong>
                        </td>
                        <td style="text-align: right">
                            <strong>${{ number_format($total, 2) }}</strong>
                        </td>
                        <td colspan="4"></td>
                    </tr>
                    @php($totalGeneral += $total)
                @endforeach

                <tr>
                    <td colspan="5" style="text-align: right; background-color: #f8f9fa;">
                        <strong>TOTAL
                            GENERAL</strong>
                    </td>
                    <td style="text-align: right; background-color: #f8f9fa;">
                        <strong>${{ number_format($totalGeneral, 2) }}</strong>
                    </td>
                    <td colspan="4" style="background-color: #f8f9fa;"></td>
                </tr>
            </tbody>
        </table>

    </body>

    </html>
