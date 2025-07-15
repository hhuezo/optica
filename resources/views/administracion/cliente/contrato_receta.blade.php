<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Documento sin t&iacute;tulo</title>
    <style type="text/css">
        <!--
        .Estilo2 {
            font-size: 16px;
            font-weight: bold;
        }

        .body {
            font-family: Arial, Helvetica, sans-serif;
        }

        .Estilo3 {
            font-size: 18px;
            font-weight: bold;
        }
        -->
    </style>
</head>



<body>
    <table width="100%" border="0">
        <tr>
            <td>
                <div align="center">
                    <p class="Estilo3">Dr. Rogelio Am&iacute;lcar Ch&aacute;vez Parada</p>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div>
                    <div align="center" class="Estilo2">OFTALMOLOGO</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div>
                    <div align="center">Doctorado: Universidad de El Salvador, Post-Grado: Hospital I.S.S.S.</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div>
                    <div align="center">Y universidad de Puerto Rico</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div>
                    <div align="center">59 Av. Sur y Alameda Roosevelt centro comercial</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div>
                    <div align="center">Peri-Roosevelt, Local #7 San Salvador</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div align="center"></div>
            </td>
        </tr>
        <tr>
            <td>
                <div>
                    <div align="center" class="Estilo2">ENFERMEDADES Y CIRUGIA DE LOS OJOS</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div>
                    <div align="center" class="Estilo2">LENTES DE CONTACTO</div>
                </div>
            </td>
        </tr>
    </table>
    <br />
    <table width="100%" border="0">
        <tr>
            <td class="Estilo2">
                <div>
                    <div align="right">FECHA: {{ $contrato->date ? date('d/m/Y', strtotime($contrato->date)) : '' }}
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="Estilo2">
                <div>PACIENTE: {{ $contrato->cliente->name ?? '' }} {{ $contrato->cliente->lastname ?? '' }}</div>
            </td>
        </tr>
    </table>
    <br />
    <table width="100%" border="1" cellspacing="0">
        <tr>
            <td>&nbsp;</td>
            <td class="Estilo2">
                <div>
                    <div align="center">ESF.</div>
                </div>
            </td>
            <td class="Estilo2">
                <div>
                    <div align="center">CIL.</div>
                </div>
            </td>
            <td class="Estilo2">
                <div>
                    <div align="center">EJE</div>
                </div>
            </td>
            <td class="Estilo2">
                <div>
                    <div align="center">ADICION</div>
                </div>
            </td>
        </tr>
        @foreach ($contrato->detalles as $detalle)
            <tr>
                <td class="Estilo2">
                    <div>
                        <div align="center">O.D.</div>
                    </div>
                </td>
                <td>{{ $detalle->right_eye_sphere }}</td>
                <td>{{ $detalle->right_eye_cylinder }}</td>
                <td>{{ $detalle->right_eye_axis }}</td>
                <td>{{ $detalle->right_eye_addition }}</td>
            </tr>

            <tr>
                <td class="Estilo2">
                    <div>
                        <div align="center">O.I.</div>
                    </div>
                </td>
                 <td>{{ $detalle->left_eye_sphere }}</td>
                <td>{{ $detalle->left_eye_cylinder }}</td>
                <td>{{ $detalle->left_eye_axis }}</td>
                <td>{{ $detalle->left_eye_addition }}</td>
            </tr>
        @endforeach


    </table>

    <br /><br />
    <table width="100%" border="0">
        <tr>
            <td>
                <div><strong>OBSERVACIONES:</strong> {{ $contrato->observation }}</div>
            </td>
        </tr>
        <tr>
            <td class="Estilo2">&nbsp;</td>
        </tr>
        <tr>
            <td>
                <div>
                    <div><strong>DIAGNOSTICO:</strong> {{ $contrato->diagnostic }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div align="right"><br />
        <br />
        <img src="{{ public_path('assets/images/firma.png') }}" width="272" height="140" />
    </div>
</body>

</html>
