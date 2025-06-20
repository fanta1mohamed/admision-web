<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cargo en PDF</title>
    <script>
    function subst() {
        var vars = {};
        var query_strings_from_url = document.location.search.substring(1).split('&');
        for (var query_string in query_strings_from_url) {
            if (query_strings_from_url.hasOwnProperty(query_string)) {
                var temp_var = query_strings_from_url[query_string].split('=', 2);
                vars[temp_var[0]] = decodeURI(temp_var[1]);
            }
        }
        var css_selector_classes = ['page', 'topage'];
        for (var css_class in css_selector_classes) {
            if (css_selector_classes.hasOwnProperty(css_class)) {
                var element = document.getElementsByClassName(css_selector_classes[css_class]);
                for (var j = 0; j < element.length; ++j) {
                    element[j].textContent = vars[css_selector_classes[css_class]];
                }
            }
        }
    }
    </script>
</head>

<body style="border:0; font-family: sans-serif; margin-left:5px" onload="subst()" >
    <table>
        <tr>
            <td valgin="top">
                <div style="width:370px; text-align:left;">
                    <div><span style="font-size:15pt; stroke:#000000;">Universidad Nacional del Altiplano</span></div>
                    <span style="font-size:12pt;">Comisión de Inventario Activos Fijos 2022</span>
                </div>
            </td>
            <td align="center">
                <div style=" width:690px; margin-top:50px;" style="font-weight:bold;">
                    <div><span style="font-size:17pt;"> FORMATO DE FICHA DE LEVANTAMIENTO DE INFORMACIÓN </span></div>
                    <span style="text-align:center; font-size:14pt;">INVENTARIO PATRIMONIAL 2022</span>
                </div>
            </td>
            <td align="right" valgin="top">
                <div style="width: 270px;">
                    <div><span style="font-size:13pt;">Pag. </span><span class="page" style="font-size:13pt;"></span> de <span class="topage" style="font-size:13pt;"></span></div>
                    <span style="font-size:12pt;">&nbsp;</span>
                </div>
            </td>
        </tr>
    </table>
    <div style="height: 10px; width:100%;">
        <span></span>
</div>
    <table style="width:100%; margin-right:10px;">
        <tr>
            <td style="width: 300px;">
                <div style="">
                    <span style="text-align:center; font-size:13pt;">Dependencia</span>                        
                </div>
            </td>
            <td colspan="2" width="540px" style=" white-space: nowrap; text-overflow: ellipsis; max-width: 550px;">               
                <div style="text-align: left; overflow:hidden;">:<span style="font-size:13pt;"> @foreach ($oficina as $o){{ $o->nombre }} - {{ $o->dependencia }}  @endforeach </span> </div>
            </td>
            <td align="right" width="260px" style="">
                <div style="">
                    <span style="text-align:center; font-size:13pt;">ID: @foreach ($oficina as $o){{ $o->iduoper }}@endforeach @foreach ($responsable as $re) {{ $re->dni }}-{{$num_doc}} @endforeach</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div style="margin-top: 0px;">
                    <span style="text-align:center; font-size:13pt;">[DNI]Apellidos y Nombres</span>
                </div>
            </td>
            <td style="width:570px; white-space: nowrap; text-overflow: ellipsis; max-width: 560px;">
                <div style="text-align: left">
                    <span style="text-align:center; font-size:13pt; overflow:hidden;">
                        @foreach ($responsable as $re)<span>: [{{ $re->dni }}] {{ $re->paterno }} {{ $re->materno }} {{$re->nombres}} </span> @endforeach
                    </span>
                </div>
            </td>
            <td colspan="2" align="right" style="">
                <div style="">
                    <span style="margin-right:0.20cm; font-size:13pt;">TIPO DE VERIFICACIÓN:</span><span style="font-size:13.5pt; margin-right:0.20cm;" > FÍSICA ( X ) </span> <span style="font-size:13.5pt;" > DIGITAL ( &nbsp; ) </span>
                </div>
            </td>
        </tr>
        @if ($responsable2 !== null)
        <tr>
            <td>
            </td>
            <td style="" >
                <div style="text-align: left">
                       <span>: [{{ $responsable2->dni }}] {{ $responsable2->paterno }} {{ $responsable2->materno }} {{$responsable2->nombres}} </span>
                </div>
            </td>
            <td colspan="2" align="right" style=" margin-right:1cm;" >
            </td>
        </tr>
        @endif
    </table>

</body>
</html>