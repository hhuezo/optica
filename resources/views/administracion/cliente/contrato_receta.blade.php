<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Receta</title>
</head>

<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
    }

    .Estilo1 {
        font-size: 16px;
        font-weight: bold;
    }
</style>

<body>
    <table width="100%" border="0">
        <tr>
            <td colspan="2">
                <div align="center"><img src="{{ asset('assets/images/logonew.jpg') }}" width="300" height="125" />
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <br />
                <div>
                    <div align="center" class="Estilo1">RECETA PARA LENTES</div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="64%">
                <div>Paciente: {{ $contrato->cliente->name ?? '' }} {{ $contrato->cliente->lastname ?? '' }}
                    <div></div>
                </div>
            </td>
            <td width="36%">
                <div>Edad: _______________</div>
            </td>
        </tr>
        <tr>
            <td>
                <di<div>
                    Fecha:
                    {{ $contrato->date ? date('d/m/Y', strtotime($contrato->date)) : '' }}
                    </div>
            </td>
            <td>
                <div>Teléfono: {{ $contrato->cliente->phone ?? '' }}</div>
            </td>
        </tr>
    </table>
    <br />
    <table width="100%" border="1" cellspacing="0">
        <tr>
            <td>&nbsp;</td>
            <td>
                <div>
                    <div align="center">ESFERA</div>
                </div>
            </td>
            <td>
                <div>
                    <div align="center">CILINDRO</div>
                </div>
            </td>
            <td>
                <div>
                    <div align="center">EJE</div>
                </div>
            </td>
            <td>
                <div>
                    <div align="center">BASE</div>
                </div>
            </td>
            <td>
                <div>
                    <div align="center">PRISMA</div>
                </div>
            </td>
            <td>
                <div>
                    <div align="center">ADICI&Oacute;N</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div>
                    <div align="center">O.D.</div>
                </div>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <div>
                    <div align="center">O.I.</div>
                </div>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
    <br />
    <table width="100%" border="0">
        <tr>
            <td>
                <div>D.P.: __________________________________</div>
            </td>
            <td>
                <div>A.O.: _________________________</div>
            </td>
        </tr>
        <tr>
            <td>
                <div>Clase de lente: _________________________</div>
            </td>
            <td>
                <div>Aro: __________________________</div>
            </td>
        </tr>
        <tr>
            <td>
                <div>Color de lente: _________________________</div>
            </td>
            <td>
                <div>Medidas: _______________________</div>
            </td>
        </tr>
    </table>
    <br />
    <p align="right">F. ______________________</p>
    <br />
    <table width="100%" border="0">
        <tr>
            <td>
                <div>Ultimo RX: ____________</div>
            </td>
            <td colspan="2">
                <div>Problemas de visi&oacute;n: Cercana <input type="checkbox" name="checkbox" /> </div>
            </td>
            <td colspan="2">
                <div>lejajana <input type="checkbox" name="checkbox" /></div>
            </td>
        </tr>
        <tr>
            <td>
                <div>Padecimientos:</div>
            </td>
            <td>
                <div>Diabetes ______</div>
            </td>
            <td>
                <div>Hipertensi&oacute;n ______</div>
            </td>
            <td>
                <div>Enrojecimiento ______</div>
            </td>
            <td>
                <div>Ardor _____</div>
            </td>
        </tr>
        <tr>
            <td>
                <div>Graduaci&oacute;n Anterior:</div>
            </td>
            <td colspan="3">
                <div>Ojo derecho _________________________</div>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="3">
                <div>Ojo Izquierdo __________________________</div>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5">
                <div>Otras observaciones </div>
                <div></div>
            </td>
        </tr>
    </table>
    <br />
    <table width="100%" border="1" cellspacing="0">
        <tr>
            <td>
                <div>
                    <div align="center">Fecha</div>
                </div>
            </td>
            <td>
                <div>
                    <div align="center">Descripci&oacute;n</div>
                </div>
            </td>
            <td>
                <div>
                    <div align="center">Cargo</div>
                </div>
            </td>
            <td>
                <div>
                    <div align="center">Abono</div>
                </div>
            </td>
            <td>
                <div>
                    <div align="center">Saldo</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>

</body>

</html>
