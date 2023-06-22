<!DOCTYPE html>
<html>
<head>
    <title>Constancia de ingreso</title>
</head>
<body style="font-family: 'Gill Sans Extrabold', Helvetica, sans-serif; margin-top:125px; margin-left:60px; margin-right:40px;">
    <div>
        <div>
            <table style="width: 100%">
                <tr>
                    <td align="center"><div style="text-align: center"><span style="font-weight:bold">CEPREUNA 2023-II</span></div></td>
                </tr>

                <tr>
                    <td align="center"><div style="text-align: center"><span style="font-size: 1.4rem; font-weight:bold">CONSTANCIA DE INGRESO</span></div></td>
                </tr>
            </table>
        </div>

        <div style="margin-top:30px">
            <table style="width: 100%">
                <tr >
                    <td style="">
                        <div style="text-align: right">
                            <span style="">
                                Fecha: Puno, 24 de Julio de 2023
                            </span>
                        </div>
                    </td>

                </tr>
            </table>
        </div>

        <div style="margin-top:15px">
            <table style="width: 100%">
                <tr >
                    <td align="center">
                        <div style="text-align: justify">
                            <span style="line-height:1.4rem;">
                                La Dirección de admisión de la Universidad Nacional del Altiplano de Puno,
                                conforme al cumplimento del reglamento general del Examen CEPREUNA 2023-II,
                                certifica que Jhon Ariel Luque Cusacani, con DNI N° 70757838, 
                                es ingresante al programa de estudios de INGENIERÍA DE SISTEMAS, 
                                bajo la modalidad CEPREUNA, en el examen realizado el 23 de Julio del 2023.
                            </span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div style="text-align: justify; margin-top:15px;">
                            <span style="line-height:1.4rem;">
                                El ingresante está ubicado en el puesto número 15 
                                entre los ingresantes al programa de estudios de Ingeniería de Sistemas,
                                Así mismo, se adjuntan 
                                los datos biométricos del estudiante registrados en la etapa de inscripción, 
                                ingreso el día del examen y control biométrico. Estos datos garantizan 
                                la autenticidad y seguridad de su identificación en nuestra universidad.
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="text-align: justify; margin-top:15px;">
                            <span style="line-height:1.4rem;">
                                La Universidad Nacional del Altiplano de Puno felicita al estudiante por su destacado logro
                                 y le da una cálida bienvenida a nuestra prestigiosa universidad. 
                                Le deseamos mucho éxito en su trayectoria universitaria.
                            </span>
                        </div>
                    </td>

                </tr>


            </table>
        </div>

        <div style="margin-top:60px">
            <table style="font-size:11pt; width:100%;">
                <tr style="height:85px; padding:0;">
                    <td>
                        <div style="text-align: center">
                            <span>Atentamente,</span>
                        </div>
                    </td>

                </tr>
            </table>
        </div>

        <div style="margin-top:60px">

            <table style="width:100%; bakcground:orange; margin-top:0px">
                <tr>
                    <td align="center"  style="vertical-align: bottom;">
                        <div style="text-align: center; border-top:1px black solid;">
                        </div> 
                        <div><span style="font-size: 10pt;"> DR. JUAN CARLOS BENAVIDES HUANCA</span></div>
                        <div><span style="font-size: 9pt;"> Director de la Dirección de Admisión</span></div>
                        {{-- <div>966637192</div> --}}
                    </td>  
                    <td style="width: 50%"> 
                        <div style=" margin-left: 160px; height: 150px; text-align:center;">
                            <span style="font-size:6pt;"><?php echo DNS2D::getBarcodeHTML('https://admision.unap.edu.pe/verificar-solicitud/70757838', 'QRCODE',4,4);?> </span>
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