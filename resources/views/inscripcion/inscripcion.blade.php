<!DOCTYPE html>
<html>
<head>
    <title>Constancia de inscripción</title>
</head>
<body style="font-family: 'Gill Sans Extrabold', Helvetica, sans-serif; margin-top:125px">
    <div>
        <div>
            <table>
                <tr>
                    <td width="100"></td>
                    <td align="center"><div style="text-align: center"><span style="font-weight:bold"> CONSTANCIA DE INSCRIPCIÓN AL EXAMEN {{$data->proceso}}</span></div></td>
                    <td width="100"></td>
                </tr>
            </table>
        </div>

        <div style="margin-top:6px">
            <table style="font-size:11pt; font-weight:bold; width:100%;">
                <tr style="height:85px; padding:0;">
                    <td style="" width="195px" align="left" valign="top">MODALIDAD</td>
                    <td style="" width="5px" align="left" valign="top">:</td>
                    <td style="" align="left" valign="top"><div style="text-align: justify; font-weight: regular;"><span>{{$data->modalidad}}</span></div></td>
                    <td style="" rowspan="7" align="right"  width="150px" valign="top">
                        <div style="border:solid 1px #d9d9d9; padding:5px; width:125px; overflow-hidden;" >
                            <div style="overflow: hidden; height:150px; width:125px;">
                                <img src="{{ public_path('fotos/inscripcion/70757838.jpg')}} " alt="" height="165">
                                <img src="" alt="" height="150">
                            </div>
                        </div>
                    </td>
                </tr>
                <tr style="margin-bottom:-12px;">
                    <td width="195px" align="left" valign="top"><div style="margin-top: -6px;">PROGRAMA DE ESTUDIOS</div></td>
                    <td width="5px" align="left" valign="top"><div style="margin-top: -6px;">:</div></td>
                    <td align="left" valign="top"><div style="text-align: justify; font-weight: regular; margin-top: -6px;"><span style="text-transform: uppercase;"> {{ $data->programa }} </span></div></td>
                </tr>
                <tr>
                    <td width="195px" align="left" valign="top"><div style="margin-top: -12px;"> NRO. DE DOCUMENTO:</div></td>
                    <td width="5px" align="left" valign="top"><div style="margin-top: -18px;">:</div></td>
                    <td align="left" valign="top"><div style="text-align: justify; font-weight: regular; margin-top: -12px;"><span style="text-transform: uppercase;">70757838</span></div></td>
                </tr>
                <tr>
                    <td width="195px" align="left" valign="top"><div style="margin-top: -18px;"> PRIMER APELLIDO </div></td>
                    <td width="5px" align="left" valign="top"><div style="margin-top: -18px;">:</div></td>
                    <td align="left" valign="top"><div style="text-align: justify; font-weight: regular; margin-top: -18px;"><span style="text-transform: uppercase;"> LUQUE </span></div></td>
                </tr>
                <tr>
                    <td width="195px" align="left" valign="top"><div style="margin-top: -24px;"> SEGUNDO APELLIDO </div></td>
                    <td width="5px" align="left" valign="top"><div style="margin-top: -24px;">:</div></td>
                    <td align="left" valign="top"><div style="text-align: justify; font-weight: regular; margin-top: -24px;"><span style="text-transform: uppercase;"> CUSACANI </span></div></td>
                </tr>
                <tr>
                    <td width="195px" align="left" valign="top"><div style="margin-top: -30px;"> PRENOMBRES </div></td>
                    <td width="5px" align="left" valign="top"><div style="margin-top: -30px;">:</div></td>
                    <td align="left" valign="top"><div style="text-align: justify; font-weight: regular; margin-top: -30px;"><span style="text-transform: uppercase;"> JHON ARIEL </span></div></td>
                </tr>
                <tr>
                    <td width="195px" align="left" valign="top"><div style="margin-top: -36px;"> FECHA DE IMPRESION </div></td>
                    <td width="5px" align="left" valign="top"><div style="margin-top: -36px;">:</div></td>
                    {{-- <td align="left" valign="top"><div style="text-align: justify; font-weight: regular; margin-top: -36px;"><span style="text-transform: uppercase;">{{$fecha}}</span></div></td> --}}
                    <td align="left" valign="top"><div style="text-align: justify; font-weight: regular; margin-top: -36px;"><span style="text-transform: uppercase;"> 12 DE ENERO DEL 2023</span></div></td>
                </tr>
            </table>
        </div>

        <div style="margin-top:-30px">
            <table style="font-size:11pt;  width:100%;">
                <tr style="height:85px; padding:0;">
                    <td>
                        <div>
                            <span style="font-weight:bold">
                                DECLARACIÓN JURADA
                            </span>
                        </div>
                        <div style="text-align: justify;">
                            <p>
                                El que suscribe declara bajo juramento que la información consignada en el proceso
                                de inscripción presencial es verdadera y de mi entera responsabilidad. Conozco y acepto lo 
                                dispuesto en el Reglamento del <span style="font-weight:bold;">EXAMEN DE ADMISIÓN GENERAL 2023-I</span> y acepto la revisión física estricta exhaustiva para el ingreso a la 
                                Ciudad Universitaria a rendir el examen de admisión. En caso de alcanzar una 
                                vacante, me comprometo a cumplir con lo dispuesto en el Reglamento. En señal de 
                                conformidad firmo la presente constancia de inscripción.
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top:0px">
            <table style="font-size:11pt;  width:100%;">
                <tr>
                    <td width="390" style="" valign="top">
                        <div>
                            <span style="font-weight:bold;">
                                DÍA DEL EXAMEN PRESENTAR LO SIGUIENTE CON ESTRICTO CUMPLIMIENTO:
                            </span>
    
                            <div style="margin-top: 16px">
                                <div><span>- </span><span>Documento Nacional de Identidad (original)</span></div>
                                <div><span>- </span><span>Lápiz 2B, borrador y tajador</span></div>
                                <div><span>- </span><span>Constancia de Inscripción </span></div>
                                <div><span>- </span><span>Venir con buzo, cabello recogido, visible el cuello y orejas</span></div>
                                <div><span>- </span><span>Barbijos y tablero serán entregados por la Dirección de Admisión</span></div>
                                <div style="margin-top: 16px"><span style="font-weight:bold">!Importante: de no presentar lo indicado no podrá ingresar a rendir su examen!</span></div>
                            </div>
                        </div>
                    </td>

                    <td valign="top">
                        <div>
                            <div style=" width 80px; height: 95px;">
                                <img src="{{ public_path('huellas/inscripcion/70757838.jpg')}} " alt="" width="75"> 
                            </div>
                            <div style="text-align: center" >Indice Derecho </div>
                            <div style="width 80px; heightpx; margin-top:10px;">
                                <img src="{{ public_path('huellas/inscripcion/70757838x.jpg')}} " alt="" height="95">
                            </div>
                            <div style="text-align: center">Indice Izquierdo</div>
                        </div>
                        
                    </td>

                    <td valign="top">
                        <div>
                            <div style="border:solid 1px black; width 85px; height: 95px; margin-top:-2px;">

                            </div>
                            <div style="text-align: center" >Indice Derecho </div>
                            <div style="border:solid 1px black; width 85px; height: 95px; margin-top:10px;">
                            </div>
                            <div style="text-align: center">Indice Izquierdo</div>
                        </div>
                        
                    </td>

                </tr>

            </table>
        </div>

 
        <div style="margin-top:110px">
            <table style="font-size:11pt;  width:100%;">
                <tr>
                    <td>
 
                    </td>
                    <td>
                        <div style="padding 20px; margin-top:-50px;">
                            <div style="margin-left:20px; margin-right:20px; border-top: 1px solid black; text-align:center; padding-top:5px;">
                                <span>DIRECTOR DE ADMISIÓN</span>
                            </div>
                        </div>
                    </td>
                    <td>
                     </td>
                </tr>

                <tr>
                    <td>
                        <div style="">
                            <div style="margin-left:20px; margin-right:20px; border-top: 1px solid black; text-align:center; padding-top:5px;">
                                <span>INSCRIPTOR</span>
                            </div>
                        </div>
                    </td>
                    <td>
                     </td>
                    <td>
                        <div style="padding 20px;">
                            <div style="margin-left:20px; margin-right:20px; border-top: 1px solid black; text-align:center; padding-top:5px;">
                                <span>POSTULANTE</span>
                            </div>
                        </div>
                    </td>
                </tr>
       
            </table>

        </div>
    </div>
</body>
</html>