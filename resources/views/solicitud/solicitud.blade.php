<!DOCTYPE html>
<html>
<head>
    <title>SOLICITUD</title>
    <style>

    *{margin:0;}
    .fondo{
        
        font-family: 'Gill Sans Extrabold', Helvetica, sans-serif;
        *{margin:2cm 2cm; padding:65px 65px 0px 90px; }
    }           

    </style>
</head>
<body class="fondo" >
    <div style="margin-top:0px; background: #ffffff">
        <div>
            <table style="width:100%;">
                <tr style="">
                    <td style="width: 50%"></td>
                    <td style="width: 50%">
                        <p style="text-align: justify; line-height:1.5rem;">
                            <span style="font-weight: bold;">Solicito:</span> Inscripción para postular en el examen {{ $data->proceso }}.</p></td>
                </tr>
            </table>    
        </div>
        <div style="margin-top: 16px;">
            <table>
                <tr style="">
                    <td style="width: 100%">
                        <div style="margin-bottom: 5px;"><span style="font-weight:bold">Dr. Juan Carlos Benavides Huanca</span></div>
                        <div>Director de la Dirección de Admisión</div>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 16px;">
            <table style="width:100%;">
                <tr style="">
                    <td style="width: 50%"></td>
                    <td style="width: 50%">
                        <p style="text-align: justify; line-height:1.5rem;">
                            Yo, <span style="text-transform: uppercase;">{{$data->nombres}} {{$data->primer_apellido }} {{ $data->segundo_apellido }} </span>, 
                            identificado con {{$data->tipo_doc }} numero {{ $data->dni}}, 
                            con domicilio en {{$data->direccion }} de {{ $data->distrito }}, Ante Ud. 
                            respetuosamente me presento y expongo:
                        </span>
                    </td>
                </tr>
            </table>    
        </div>

        <div style="margin-top: 16px;">
            <table>
                <tr style="">
                    <td style="width: 100%">
                        <div style="text-align: justify">
                            <p style="line-height:1.5rem;">
                                Que habiendo culminado mis estudios en el colegio 
                                {{ $data->colegio}} de {{ $data->distrito }} el año {{ $data->egreso }}, 
                                presento mi solicitud para participar en el examen
                                {{ $data->proceso }} que se llevará a cabo los días 22 y 23 de Julio, 
                                donde postularé al programa de estudios de {{ $data->programa }}
                                bajo la modalidad  {{{ $data->modalidad }}}.
                            </p>
                        </div>
                        <div  style="margin-top: 16px;">
                            Adjunto a esta solicitud los siguientes documentos:
                        </div>
                        <div style="padding-left:20px; margin-top: 16px;">
                            <div  style="margin-top: 5px;">1.	Comprobante de pago (origial y copia).</div>
                            <div  style="margin-top: 5px;">2.	Documento de identidad (origial y copia).</div>
                            <div  style="margin-top: 5px;">3.	Certificado de estudios (origial y copia).</div>
                            {{-- <div style="margin-top: 5px;">5.	Constancia de no adeudo a la CEPREUNA (origial y copia).</div> --}}
                            @if($data->id_proceso = 4)
                                <div style="margin-top: 5px;">4.	Constancia de no adeudo a la CEPREUNA</div>
                            @else
                                <div></div>
                            @endif
                        </div>

                        <div style="text-align: justify; margin-top:16px;" >
                            <p style="line-height:1.5rem;">
                                Agradezco su atención y le informo que estáre participanto en la inscripción 
                                10 y 11 para biomédicas, 12 y 13 para ingenierías, 14 y 17 para sociales.
                            </p>
                        </div>

                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 16px;">
            <table style="width: 100%;">
                <tr>
                    <td style=""  >
                        <div style="text-align: right; width: 100%;">
                            Puno, {{$date}}
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top:150px">

            <table style="width:100%; bakcground:orange; margin-top:0px">
                <tr>
                    <td align="center"  style="vertical-align: bottom;">
                        <div style="text-align: center; border-top:1px black solid;">
                            FIRMA
                        </div> 
                        <div><span>{{$data->nombres}} {{$data->primer_apellido }} {{ $data->segundo_apellido }}</span></div>
                        <span>{{ $data->tipo_doc }}: {{ $data->dni}}</span>
                        {{-- <div>966637192</div> --}}
                    </td>  
                    <td style="width: 50%"> 
                        <div style=" margin-left: 160px; height: 150px; text-align:center;">
                            <span style="font-size:6pt;"><?php echo DNS2D::getBarcodeHTML('https://admision.unap.edu.pe/verificar-solicitud/'.$data->dni, 'QRCODE',4,4);?> </span>
                        </div>
                        <div style=" margin-left: 140px; height: text-align:center;">
                            <span style="font-size:6pt;"><?php echo DNS1D::getBarcodeHTML('70757373', 'C128',2.2,44);?> </span>
                        </div>
                    </td> 
                </tr>
            </table>
        </div>

    </div>
</body>
</html>