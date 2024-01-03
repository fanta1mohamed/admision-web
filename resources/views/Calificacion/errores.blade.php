<!DOCTYPE html>
<html>
<head>
    <title>PDF - ERRORES</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            }

            /* Estilo para las celdas de encabezado */
            th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            }

            /* Estilo alterno para filas para mejorar la legibilidad */
            tr:nth-child(even) {
            background-color: #f2f2f2;
            }

            /* Estilo al pasar el ratón sobre las filas */
            tr:hover {
            background-color: #e5e5e5;
            }
    </style>

</head>
<body style="font-family: Helvetica, sans-serif; margin-top:-3px">

    <div>
        <table style="width:100%; border:none;">
            <tr  style="border:none;">
                <td rowspan="1" align="left" style="border:none; width:10px;">
                    <div align="center" rowspan="1" style="border:none;" > <img src="{{ public_path('imagenes/logotiny.png')}}"  width="65"></div>
                </td>
                <td align="center" style="border:none; font-size:12pt; font-weight:700; text-align:left; margin-left:-50px;">
                    <div>UNIVERSIDAD NACIONAL DEL ALTIPLANO</div>
                    <div>VICERECTORADO ACADÉMICO</div>
                    <div>DIRECCIÓN DE ADMISIÓN</div>
                </td>
                {{-- <td align="rigth" rowspan="1" style="border:none;"> <img src="{{ public_path('imagenes/logoDAD.jpg')}}"  width="70"></td> --}}
            </tr>
        </table>
    </div>
    <div style="border-bottom: solid 1px #000000;">
    </div>

    <div style="width: 100%; text-align:center; margin-top:20px;">
        REPORTE DE ERRORES - FICHAS DE IDENTIFICACIÓN 
    </div>

    <div style="margin-top:20px;">
        <table style="width: 100%" class="l-table">
            <thead>
                <tr>
                    <th>Archivo</th>
                    <th>N° Lectura</th>
                    <th>Litho</th>
                    <th>Observación</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>IDE101.dat</td>
                    <td>000067</td>
                    <td>7837372</td>
                    <td>
                        <div style="max-width: 200px;">
                            <span style="background: #e3e3e3; border-radius:4px; padding:4px;">Sin tipo</span>
                            <span style="background: #e3e3e3; border-radius:4px; padding:4px;">DNI Erroneo</span>
                            <span style="background: #e3e3e3; border-radius:4px; padding:4px;">sin Aula</span>
                            <span style="background: #e3e3e3; border-radius:4px; padding:4px;">Sin tipo</span>
                            <span style="background: #e3e3e3; border-radius:4px; padding:4px;">Sin tipo</span>
                        </div>
                    </td>
                </tr>
            </tbody>

        </table>
    </div>
    



</body>
</html>