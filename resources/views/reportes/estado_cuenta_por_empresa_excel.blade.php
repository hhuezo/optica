<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    // Establecer cabeceras para que el navegador lo reconozca como un archivo Excel
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="estado_cuenta.xls"');
    header('Cache-Control: max-age=0');
    ?>
    <meta charset="UTF-8">
    <title>Estado de cuenta</title>
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
    </style>
</head>

<body>

    <table width="100%" border="0">
        <tr style="background-color: #ffffff;">
            {{-- <td style="border: 0">
                <div align="center"><img src="{{ public_path('imagenes/logo.jpg') }}" width="100px"> </div>
            </td> --}}
            <td style="border: 0">
                <div align="center"><strong>ESTADO DE CUENTA DE {{ strtoupper($empresa->name) }}</strong></div>
            </td>
        </tr>
    </table>

    <br>
    <span class="Estilo1">Fecha y hora: {{ date('d/m/Y H:m') }} </span>
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Codigo de empleado</th>
                <th>Monto</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @php($total_monto = 0)
            @php($total_saldo = 0)
            @foreach ($clientes as $cliente)
                <tr>
                    <td>
                        {{ $cliente->name }}
                    </td>
                    <td>
                        {{ $cliente->employee_code }}
                    </td>

                    <td>
                        ${{ number_format($cliente->amount, 2) }}
                    </td>
                    <td style="text-align: right">
                        ${{ number_format($cliente->remaining, 2) }}
                    </td>

                </tr>
                @php($total_monto += $cliente->amount)
                @php($total_saldo += $cliente->remaining)
            @endforeach
            <tr>
                <td colspan="2">
                    <strong>TOTAL </strong>
                </td>
                <td style="text-align: right">
                    <strong>${{ number_format($total_monto, 2) }} </strong>
                </td>

                <td style="text-align: right">
                    <strong>${{ number_format($total_saldo, 2) }} </strong>
                </td>

            </tr>
        </tbody>
    </table>

</body>

</html>
