<!DOCTYPE html>
<html>
<head>
    <title>SOLICITUD</title>
    <style>
    .{
        margin: 0px;
        padding: 0px;
    }
        
    .fondo{
        font-family: 'Gill Sans Extrabold', Helvetica, sans-serif;
        *{margin:2cm 2cm; padding:65px 65px 0px 90px; }
    }           

    </style>
</head>
<body>
<div style="position: relative;">
        <img src="{{ $fondo }}" alt="foto" width="97%"> 
</div>
<div class="fondo" style="position: absolute; top:0px;">
    <div style="margin-top:0px;"> 
        <div style="margin:auto; width:400px; text-align:center; margin-bottom:14px; font-size:9pt; font-style: italic;">
            <span>
                “Año del Bicentenario, de la consolidación de nuestra Independencia, y de la conmemoración de las heroicas batallas de Junín y Ayacucho”
            </span>
        </div>
        <div>
            <table style="width:100%;">
                <tr style="">
                    <td style="width: 50%"></td>
                    <td style="width: 50%">
                        <p style="text-align: justify; line-height:1.5rem;">
                        <span style="font-weight: bold;">SOLICITO:</span> Inscripción para postular en el <span style="font-weight: bold;"> EXAMEN {{ $data->proceso }}.</span> </p></td>
                </tr>
            </table>    
        </div>
        <div style="margin-top: 14px;">
            <table>
                <tr style="">
                    <td style="width: 100%">
                        <div style="margin-bottom: 5px;"><span style="font-weight:bold">Dr. Juan Carlos Benavides Huanca</span></div>
                        <div> <span style="font-weight:bold; font-size:10pt;">Director de la Dirección de Admisión de la Universidad Nacional del Altiplano – Puno</span></div>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 12px;">
            <table style="width:100%;">
                <tr style="">
                    <td style="width: 50%"></td>
                    <td style="width: 50%">
                        <p style="text-align: justify; line-height:1.5rem;">
                            Yo, <span style="text-transform: uppercase;">{{$data->nombres}} {{$data->primer_apellido}} {{ $data->segundo_apellido }}</span>, 
                            identificado(a) con DNI N° {{ $data->dni}}, 
                            con domicilio en {{$data->direccion }} del distrito de {{ $data->distrito }}, ante usted 
                            me presento y expongo:
                        </span>
                    </td>
                </tr>
            </table>    
        </div>

        <div style="margin-top: 12px;">
            <table>
                <tr style="">
                    <td style="width: 100%">
                        <div style="text-align: justify">
                            <p style="line-height:1.5rem;">
                                Que, habiendo culminado mis estudios en el la Institución 
                                Educativa Secundaria 
                                {{ $data->colegio}} en el año {{ $data->egreso }}, 
                                <span style="font-weight:bold">
                                SOLICITO la inscripción para postular en el EXAMEN
                                {{ $data->proceso }}
                                </span> que se llevará a cabo los días {{ $data->fecha_examen }}
                                del año en curso. Asimismo, pongo a vuestro conocimiento que 
                                postulo al programa de estudios de <span style="font-weight: bold;"> {{ $data->programa }}</span> 
                                bajo la modalidad  <span style="font-weight: bold;">{{ $data->modalidad }}</span>.
                            </p>
                        </div>
                        <div  style="margin-top: 14px;">
                            Adjunto a esta solicitud los siguientes documentos:
                        </div>
                        <div style="padding-left:20px; margin-top: 14px;">
                            <div  style="margin-top: 5px;">1.	Comprobante de pago.</div>
                            <div  style="margin-top: 5px;">2.	Documento de identidad (<span style="color:red;">*</span>original y copia).</div>
                            <div  style="margin-top: 5px;">3.	Certificado de estudios (<span style="color:red;">*</span>original y copia).</div> 
                            <div  style="margin-top: 5px;">4.	Constancia de no adeudo al CEPREUNA.</div> 
                        </div>
                        <div style="margin-top:10px; margin-bottom:-14px;">
                            Nota (<span style="color:red;">*</span> Solo para verificación</span>)
                        </div>

                        <div style="margin-top: 14px; text-align:justify;">
                            <P style="line-height:1.5rem;">
 
                            @if($carreras_previas != null)
                                Adicionalmente, deseo informar que estoy postulando a mi
                                @if(count($carreras_previas) == 1)
                                    segunda carrera
                                @elseif( count($carreras_previas) == 2 )
                                    tercera carrera
                                @elseif( count($carreras_previas) == 3 )
                                    cuarta carrera
                                @elseif( count($carreras_previas) == 4 )
                                    quinta carrera
                                @else
                                @endif 
                                en esta prestigiosa universidad. 
                                He tenido ingresos previos en las siguientes carreras:                             
                                @foreach ($carreras_previas as $index=>$item)
                                    <div style="margin-top: 8px; margin-left:20px;">
                                        {{$index + 1}}. {{ $item->nombre }} - CODIGO: <span style="font-weight: bold;">{{ $item->codigo }} </span>
                                    </div>                                
                                @endforeach
                            @else
                            @endif
                        </div>

                        <div style="text-align: justify; margin-top:12px;" >
                                                    
                            @if( $data->id_modalidad_proceso == 1)
                            <p style="line-height:1.5rem;">
                                Agradezco su atención a la presente y me comprometo a cumplir con la inscripción presencial 
                                según el último digito de mi DNI, tal como se encuentra especificado en el cronograma.
                            </p>
                            
                            @else 
                            
                            <p style="line-height:1.5rem;">
                                Agradezco su atención a la presente y me comprometo a cumplir con la inscripción presencial 
                                según como se encuentra especificado en el cronograma.
                            </p>
                            {{-- <p style="line-height:1.5rem;">
                                Agradezco su atención y me comprometo a realizar la inscripción presencial de acuerdo con el 
                                área del programa que he seleccionado, siguiendo rigurosamente las fechas indicadas en el cronograma. 
                                Estoy consciente de la importancia de este proceso y aseguro cumplir con todos los requisitos establecidos. 
                                Quedo a disposición para cualquier información adicional que se requiera. Gracias.
                            </p> --}}
                            @endif 

                        </div>


                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 14px;">
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

        <div style="margin-top:108px">  

            <table style="width:100%; bakcground:orange; margin-top:0px">
                <tr>
                    <td align="center"  style="vertical-align: bottom;">
                        <div style="text-align: center;">

                            <div style="margin-bottom: 5px;">_______________________________</div>
                            <div style="margin-bottom: 5px;"><span style="text-transform:uppercase">Nombre: ___________________</span></div>
                            {{-- <div><span style="text-transform:uppercase">{{$data->nombres}} {{$data->primer_apellido }} {{ $data->segundo_apellido }}</span></div> --}}
                            <span> DNI N.° {{ $data->dni}}</span>
                        </div>

                        {{-- <div>966637192</div> --}}
                    </td>  
                    {{-- <td style="width: 50%"> 
                        <div style=" margin-left: 160px; height: 150px; text-align:center;">
                            <span style="font-size:6pt;"><?php echo DNS2D::getBarcodeHTML('https://admision.unap.edu.pe/verificar-solicitud/'.$data->dni, 'QRCODE',4,4);?> </span>
                        </div>
                        <div style=" margin-left: 140px; height: text-align:center;">
                            <span style="font-size:6pt;"><?php echo DNS1D::getBarcodeHTML($data->dni, 'C128',2.2,44);?> </span>
                        </div>
                    </td>  --}}
                </tr>
            </table>
        </div>

    </div>
</div>
</body>
</html>