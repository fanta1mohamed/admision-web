<!DOCTYPE html>
<html>
<head>
    <script type="text/php">
        if (typeof pageNumber !== 'undefined') {
            var pageNumber = '<page>' + pageNumber + '</page>';
            if (pageNumber > 1) {
                var pageCount = '<pagecount>' + pageCount + '</pagecount>';
                pageNumber += ' / ' + pageCount;
            }
            var y = 15;
            pdf.setFontSize(14);
            pdf.text(pageNumber, 270, y);
        }
    </script>
    <title>PDF - ERRORES</title>
    <style>
           #header{
                position: fixed;
                height: 100px;
                width: 100%;
                top: -3cm;
                left: 0cm;

            }
            #footer{
                text-align: center;
                width: 100%;
                position: fixed;
                left: 0cm;
                bottom: -2.5cm;
                background: #cdcdcd;
            }
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
            background-color: #f5f5f5;
            }

            /* Estilo al pasar el ratón sobre las filas */
            tr:hover {
            background-color: #f7f7f7;
            }
    </style>

</head>
<body style="font-family: Helvetica, sans-serif; margin-top:-3px">

    <div>
        <table style="width:100%; border:none;">
            <tr  style="border:none;">
                <td rowspan="1" align="left" style="border:none; width:10px;">
                    <div align="center" rowspan="1" style="margin-top:-10px; border:none;" > <img src="{{ public_path('imagenes/logotiny.png')}}"  width="58"></div>
                </td>
                <td align="center" style="border:none; font-size:10pt; font-weight:700; text-align:left; margin-left:-50px;">
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

    <div style="width: 100%; text-align:center; margin-top:20px; font-weight:bold;">
        INFORME DE OBSERVACIONES
        <div>(Fichas de identificación)</div>
    </div>

    <div style="margin-top: 20px;"><span style="font-size: .8rem; font-weight:bold;">DNI duplicados</span></div>
    <div style="margin-top:10px; font-size: .8rem;">
        <table style="width: 100%" class="l-table">
            <thead>
                <tr>
                    <th><div style="text-align: center;">N°</div></th>
                    <th><div style="text-align: center;">Archivo</div></th>
                    <th><div style="text-align: center;">N° Lectura</div></th>
                    <th><div style="text-align: center;">Litho</div></th>
                    <th><div style="text-align: center;">DNI</div></th>
                    <th><div style="text-align: center;">Observación</div> </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($duplicados_dni as $index=>$item)
                <tr>
                    <td><div style="text-align: center;">{{ $index + 1}}</div> </td>
                    <td><div style="text-align: center;">{{ $item->archivo }}</div> </td>
                    <td><div style="text-align: center;">{{ $item->lectura }}</div> </td>
                    <td><div style="text-align: center;">{{ $item->litho }}</div> </td>
                    <td><div style="text-align: center;">{{ $item->dni }}</div> </td>
                    <td>
                        <div style="max-width: 200px; text-align: center;">
                            {{-- <span style="background: crimson; font-weight:bold; color:white; border-radius:4px; padding:4px;">DNI duplicado</span> --}}
                            <span> DNI duplicado</span>
                        </div>
                    </td>
                </tr>   
                @endforeach
            </tbody>

        </table>
    </div>

    <div id="footer">

    </div>


    <div style="margin-top: 20px;"><span style="font-size: .8rem; font-weight:bold;">Otras observaciones</span></div>
    <div style="margin-top:10px; font-size: .8rem;">
        <table style="width: 100%" class="l-table">
            <thead>
                <tr>
                    <th><div style="text-align: center;">N°</div></th>
                    <th><div style="text-align: center;">Archivo</div></th>
                    <th><div style="text-align: center;">N° Lectura</div></th>
                    <th><div style="text-align: center;">Litho</div></th>
                    <th><div style="text-align: center;">DNI</div></th>
                    <th><div style="text-align: center;">Tip</div></th>
                    <th><div style="text-align: center;">Aula</div></th>
                    <th><div style="text-align: center;">Observación</div> </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($errores as $index=>$item)
                <tr>
                    <td><div style="text-align: center;">{{ $index + 1 }}</div> </td>
                    <td><div style="text-align: center;">{{ $item->archivo}}</div> </td>
                    <td><div style="text-align: center;">{{ $item->lectura}}</div> </td>
                    <td><div style="text-align: center;">{{ $item->litho}}</div> </td>
                    <td><div style="text-align: center;">{{ $item->dni}}</div> </td>
                    <td><div style="text-align: center;">{{ $item->tipo}}</div> </td>
                    <td><div style="text-align: center;">{{ $item->aula}}</div> </td>
                    <td>
                        <div style="max-width: 200px; text-align: center;">
                            @if ($item->tipo == null) <div>Sin tipo </div>@endif
                            @if ($item->aula == null) <div>Sin aula </div>@endif
                            @if ($item->aula != null && $item->vaula == 0 ) <div>Error de aula </div>@endif
                            @if ($item->dni != null && $item->vdni == 0 || $item->len_doc != 8 ) <div>Error de DNI </div>@endif
                            @if ($item->litho == null || $item->vlitho == 0 ) <div>Error de Litho </div>@endif
                            @if ($item->dni == null) <div>Sin dni </div>@endif
                            @if ($item->dni != null && $item->len_doc == 8 && $item->vdni == 1 && $item->dnip == null) <div>DNI no encontrado</div>@endif
                        </div>
                    </td>
                </tr>   
                @endforeach
            </tbody>

        </table>
    </div>
    <div id="footer">

    </div>

    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(70, 100, "Pág $PAGE_NUM de $PAGE_COUNT", $font, 10);
            ');
        }
    </script>



</body>
</html>