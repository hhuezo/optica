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
                <div align="center"><img src="{{ public_path('imagenes/logo.jpg') }}"> </div>
            </td> --}}
            <td style="border: 0">
                <div align="center"><strong>Estado de cuenta por empresa</strong></div>
            </td>
        </tr>
    </table>

    <br>
    <span class="Estilo1">Fecha y hora: {{ date('d/m/Y H:m') }} </span>
    <table>
        <thead>
            <tr>
                <th>Empresa</th>
                <th>Monto</th>
                <th>Remanente</th>
            </tr>
        </thead>
        <tbody>
            @php($total = 0)
            @foreach ($empresas as $empresa)
                <tr>
                    <td>
                        {{ $empresa->name }}
                    </td>
                    <td>
                        ${{ number_format($empresa->amount, 2) }}
                    </td>
                    <td style="text-align: right">
                        ${{ number_format($empresa->remaining, 2) }}
                    </td>

                </tr>
                @php($total += $empresa->remaining)
            @endforeach
            <tr>
                <td colspan="2">
                    <strong>TOTAL </strong>
                </td>
                <td style="text-align: right">
                    <strong>${{ number_format($total, 2) }} </strong>
                </td>

            </tr>
        </tbody>
    </table>

</body>

</html>
