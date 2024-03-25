<template>
    <a-layout style="min-height: 100vh;">
      <a-layout>
        <!-- <a-layout-header style="background: #f50000; height: 230px; padding: 0; position: relative;" >
        <div style="border-bottom: 1px solid #00000013;">
            <h1>Header</h1>
        </div>
      </a-layout-header> -->
      <div class="headPre" style="height: 160px; margin-top:0px;">
        <div class="flex justify-between mb-2" style="margin-top:35px;">  
            <div class="logoPre">
                <div style="margin-top: -10px;"> <img src="./../../assets/imagenes/logotiny.png" width="45" /> </div>
                <div class="x"> <div class="container-pre"><span style="letter-spacing: 0.15rem;">DIRECCIÓN DE</span></div> <h1 class="logoPreAD" > ADMISIÓN </h1> </div>
                <div style="margin-top: -10px;"> <img src="./../../assets/imagenes/logoDAD.png" width="45" /> </div>
            </div>
            <!-- <div style="padding-top: 10px;">
                <strong style="color: #a410e9; font-size: 1.5rem; 140px; margin-right: 30px;">CEPREUNA</strong>                
            </div> -->
        </div>

        <div class="flex justify-center title-proceso-container">
            <span class="title-proceso">EXAMEN GENERAL 2024-I</span>
        </div>    
        <div class="flex justify-center titulo-pre">
            <div>  
                <!-- <span> Preinscripción </span> -->
                <div class="flex justify-center">
                    <hr class="line-pre mr-3" style="margin-top:18px;"> 
                        <span style="font-size:1.4rem; text-transform:capitalize;"> Puntaje individual</span> 
                    <hr class="line-pre ml-3" style="margin-top:18px;"> 
                </div>
            </div>
        </div>
      </div>
      <div style="background: #267ec5ea; height: 30px; width: 100%;">

      </div>
      <a-layout-content
        style="min-height: 280px; padding: 0px 0px;">
        <!-- style="min-height: 280px; padding: 0px 10px;"> -->
        <div style="border-radius: 10px 10px 0px 0px; box-shadow: 1px 1px 1px 2px #a5a5a53d; min-height: calc(100vh - 200px); padding: 0px  0px;  background:#e1e1e1;">
            <slot />
        </div>        
      </a-layout-content>
    </a-layout>
  </a-layout>
</template>
  
<style scope>
    .headPre{
        background-repeat: no-repeat;
        background-size: cover;
        /* background-image: linear-gradient(#3076b8b2, #22428893), url('./../../assets/imagenes/f-head-sim.jpg'); */
        background-image: linear-gradient(#008cdd71, #0b429667), url('./../../assets/imagenes/fondo-resultados.png');
    }
    .logoPre{
        display: flex;
        margin-top: -30px;
        margin-left: 10px;
        justify-content: left;
        align-items: center;
        color: white;

    }
    .logoPreAD {
        font-weight: 700; color: white; font-size: 1.6rem;

    }
    .container-pre{
        margin-bottom: -8px; margin-top: 10px;
    }

    .titulo-pre div span{
        color: white;

        font-weight: bold;
        text-transform: uppercase;
        font-size: 2.7rem;
    }

    .line-pre{
        width: 25px; border: solid 2px crimson;
    }

    .title-proceso {
        font-size:2.6rem; color:white; font-weight:bold;
    }

    .title-proceso-container {
        margin-top: -30px;
    }

    @media (min-height: 680px), screen and (orientation: portrait) {
        .logoPre img{ width: 36px; }
        .logoPre div{margin-top: -0px;}
        .logoPre span{ font-size: 0.8rem; letter-spacing: 0.04rem !important; }
        .logoPreAD{ font-size: 1.3rem; }
        .logoPre .container-pre{ margin-bottom: -8px; margin-top: 5px; }
        .titulo-pre{ margin-top: 0px;}
        .titulo-pre div span{ font-size: 1.5rem;  }
        .line-pre { width: 30px; border: solid 3px #ff0015e8; }
        .title-proceso { font-size:1.6rem; color:white; font-weight:bold; }
        .title-proceso-container { margin-top: 10px; margin-bottom:0px; }
    }

    @media (max-width: 480px) , screen and (orientation: portrait) {
        .title-proceso { font-size:1.4rem; color:white; font-weight:bold; }
    }

    @media (min-width: 1380px) , screen and (orientation: portrait) {
        .title-proceso { font-size:2.7rem; color:white; font-weight:bold; }
    }

</style>