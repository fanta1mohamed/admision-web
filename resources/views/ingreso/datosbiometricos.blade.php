<!DOCTYPE html>
<html>
<head>
    <title>Datos biométricos</title>
    <style>
        .div-container {
            width: 100%;
            overflow: hidden;
            /* background: pink; */
            height: 830px;
        }
        .div-container > div {
            width: 50%;
            float: left;
            box-sizing: border-box;
        }
    </style>
</head>
<body style="font-family: 'Gill Sans Extrabold', Helvetica, sans-serif; margin-left:20px; margin-top:105px; margin-right:0px;">
    <div>
        <table style="width: 100%">
            <tr>
                <td align="center"><div style="text-align: center"><span style="font-weight:bold">{{ $data->proceso }}</span></div></td>
            </tr>

            <tr>
                <td align="center"><div style="text-align: center"><span style="font-size: 1.4rem; font-weight:bold">CONSTANCIA DE INGRESO</span></div></td>
            </tr>
        </table>
    </div>

    <div style="text-align: justify">
    
        <p style="line-height: 1.5rem;">
            La Dirección de Admisión de la Universidad Nacional del Altiplano de Puno, conforme al cumplimento del Reglamento General del 
            Examen <span style="font-weight:bold;">{{$data->proceso}}</span>, certifica 
            que <span style="font-weight:bold;">{{$data->paterno}} {{$data->materno}} {{$data->nombres}}</span>, identificado (a) con 
            DNI N° <span style="font-weight:bold;">{{$data->dni}}</span>, es 
            <span style="font-weight:bold;">INGRESANTE APTO</span> al programa de estudios de <span style="font-weight:bold;">{{$data->programa}}</span>, 
            bajo la modalidad <span style="font-weight:bold;">{{$data->modalidad}}</span>, 
            realizado el {{ $date }}. El (la) estudiante en mención, 
            queda expedito(a) para matricularse en el referido programa de estudios.
        </p>

    </div>

    <div>
        <div style="margin-left: 40px;">
            <table>
                <tr align="center">
                    <td rowspan="2" colspan="2">
                        <div style="width: 140px; height:180px; border: solid 1px black; overflow:hidden;">
                            @if (!empty($fins) && file_exists($fins))
                                <img src="{{ $fins}}" alt="Mi imagen" width="150">
                            @else
                                <div style="margin-top:60px; text-align:center; font-size:.7rem;">
                                    Sin Foto
                                </div>
                            @endif
                            <div style="margin-top: -147px; heigth:10px; padding: 0px 0px;">
                                <div style="background: #d9d9d900; text-align:center; " >
                                    <div style="">
                                        <div style="font-size: .5rem; background: #d9d9d977; padding; 0px 5px; font-weight:bold; color:red;">Foto inscripción</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td rowspan="2" colspan="2">
                        <div style="width: 140px; height:180px; border: solid 1px black; overflow:hidden;">
                            @if (!empty($fbio) && file_exists($fbio))
                                <img src="{{ $fbio }}" alt="Mi imagen" width="140">
                            @else
                                <div style="margin-top:60px; text-align:center; font-size:.7rem;">
                                    Sin Foto
                                </div>
                            @endif
                            <div style="margin-top: -147px; heigth:10px; padding: 0px 0px;">
                                <div style="background: #d9d9d900; text-align:center;">
                                    <div style="">
                                        <div style="font-size: .5rem; background: #d9d9d977; padding; 0px 5px; font-weight:bold; color:red;">Foto Biométrico</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td rowspan="2" colspan="1">
                        <div style="width: 69px; height:90px; border: solid 1px black;">
                            @if (!empty($hinsD) && file_exists($hinsD))
                                <img src="{{ $hinsI }}" alt="Mi imagen" width="69">
                            @else
                                <div style="margin-top:35px; text-align:center; font-size:.7rem;">
                                    Sin huella
                                </div>
                            @endif
                            <div style="margin-top: -147px; heigth:10px; padding: 0px 0px;">
                                <div style="background: #d9d9d900; text-align:center; " >
                                    <div style="">
                                        <div style="font-size: .5rem; background: #d9d9d977; padding; 0px 5px; font-weight:bold; color:red;">Huella Ins. ID</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="width: 69px; height:90px; border: solid 1px black;">
                            @if (!empty($hinsI) && file_exists($hinsI))
                                <img src="{{ $hinsI }}" alt="Mi imagen" width="69">
                            @else
                                <div style="margin-top:35px; text-align:center; font-size:.7rem;">
                                    Sin huella
                                </div>
                            @endif
                            <div style="margin-top: -147px; heigth:10px; padding: 0px 0px;">
                                <div style="background: #d9d9d900; text-align:center; " >
                                    <div style="">
                                        <div style="font-size: .5rem; background: #d9d9d977; padding; 0px 5px; font-weight:bold; color:red;">Huella Ins. ID</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td rowspan="2" colspan="2">
                        <div style="width: 140px; height:180px; border: solid 1px black; overflow:hidden;">
                            @if (!empty($hexaI) && file_exists($hexaI))
                                <img src="{{ $hexaI }}" alt="Mi imagen" width="140">
                            @else
                                <div style="margin-top:60px; text-align:center; font-size:.7rem;">
                                    Sin Huella
                                </div>
                            @endif
                            <div style="margin-top: -147px; heigth:10px; padding: 0px 0px;">
                                <div style="background: #d9d9d900; text-align:center; " >
                                    <div style="">
                                        <div style="font-size: .5rem; background: #d9d9d977; padding; 0px 5px; font-weight:bold; color:red;">Foto Biométrico</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td rowspan="2" colspan="1">
                        <div style="width: 69px; height:90px; border: solid 1px black;">
                            @if (!empty($hbioD) && file_exists($hbioD))
                                <img src="{{ $hbioD }}" alt="Mi imagen" width="69">
                            @else
                                <div style="margin-top:35px; text-align:center; font-size:.7rem;">
                                    Sin huella
                                </div>
                            @endif
                            <div style="margin-top: -147px; heigth:10px; padding: 0px 0px;">
                                <div style="background: #d9d9d900; text-align:center; " >
                                    <div style="">
                                        <div style="font-size: .5rem; background: #d9d9d977; padding; 0px 5px; font-weight:bold; color:red;">Huella Ins. ID</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="width: 69px; height:90px; border: solid 1px black;">
                            @if (!empty($hbioI) && file_exists($hbioI))
                                <img src="{{ $hbioI }}" alt="Mi imagen" width="69">
                            @else
                                <div style="margin-top:35px; text-align:center; font-size:.7rem;">
                                    Sin huella
                                </div>
                            @endif
                            <div style="margin-top: -147px; heigth:10px; padding: 0px 0px;">
                                <div style="background: #d9d9d900; text-align:center; " >
                                    <div style="">
                                        <div style="font-size: .5rem; background: #d9d9d977; padding; 0px 5px; font-weight:bold; color:red;">Huella Ins. ID</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    
    <div style="text-align: justify">
        <p style="line-height: 1.5rem;">
            Así mismo, se deja constancia que ha validado su identidad a 
            través del control biométrico y acreditó los documentos personales según 
            los requisitos exigidos en el Art. 24 del Reglamento de Admisión CEPREUNA 2023 – II.
        </p>
    </div>

    <div style="text-align: right;">
        <p style="line-height: 1.5rem;">
            {{$fimp}}
        </p>
    </div>

    <div style="text-align: right;">
        <p style="line-height: 1.5rem;">
            Atentamente,
        </p>
    </div>

    <div style="margin-top:130px;">
        <table>
            <tr>
                <td>
                    <div style="text-align:center; width:380px;">
                        <div>_______________________</div>
                        <div>DIRECTOR DE ADMISIÓN</div>
                    </div>
                </td>
                <td style="width: 50%;">
                    <div style="text-align:center;">
                        <div>______________________</div>
                        <div>INGRESANTE</div>
                    </div>
                </td>
            </tr>
        </table>

    </div>
 
 
</body>
</html>