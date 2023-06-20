<template>
    <a-layout style="min-height: 100vh">
      <a-layout>
        <!-- <a-layout-header style="background: #f50000; height: 230px; padding: 0; position: relative;" >
        <div style="border-bottom: 1px solid #00000013;">
            <h1>Header</h1>
        </div>
      </a-layout-header> -->
      <div class="headPre" style="height: 200px;">
        <div class="flex justify-between" >  
            <div class="logoPre">
                <div style="margin-top: -10px;"> <img src="./../../assets/imagenes/logotiny.png" width="45" /> </div>
                <div class="x"> <div class="container-pre"><span style="letter-spacing: 0.1rem;">DIRECCIÓN DE</span></div> <h1 class="logoPreAD" > ADMISIÓN </h1> </div>
                <div style="margin-top: -10px;"> <img src="./../../assets/imagenes/logoDAD.png" width="45" /> </div>
            </div>
            <!-- <div style="padding-top: 10px;">
                <strong style="color: #a410e9; font-size: 1.5rem; 140px; margin-right: 30px;">CEPREUNA</strong>                
            </div> -->
        </div>


        <div class="flex justify-center titulo-pre ">
            <div>  
                <span> Preinscripción </span>
                <div class="flex justify-center"> <hr class="line-pre"> </div>
            </div>
        </div>
      </div>
      <a-layout-content
        style="min-height: 280px; padding: 0px 0px;">
        <!-- style="min-height: 280px; padding: 0px 10px;"> -->
        <div style="border-radius: 10px 10px 0px 0px; box-shadow: 1px 1px 1px 2px #a5a5a53f ; min-height: calc(100vh - 160px); padding: 0px  0px; margin-top: -40px; background: #ffffff; min-height: 280px;">
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
        background-image: linear-gradient(#174dff38, #174dff38), url('./../../assets/imagenes/administración.JPG');
    }
    .logoPre{
        display: flex;
        margin-top: 5px;
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

    .titulo-pre span{
        color: white;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 2.1rem;
    }

    .line-pre{
        width: 70px; border: solid 2px #ff0015e8;
    }



    @media (min-height: 680px), screen and (orientation: portrait) {
        .logoPre img{ width: 26px; }
        .logoPre div{margin-top: -10px;}
        .logoPre span{ font-size: 0.6rem; letter-spacing: 0.001rem !important; }
        .logoPreAD{ font-size: 0.76rem; }
        .logoPre .container-pre{ margin-bottom: -8px; margin-top: 5px; }
        .titulo-pre{ margin-top: 30px;}
        .titulo-pre span{ font-size: 1.3rem;  }
        .line-pre {         width: 50px; border: solid 1px #ff0015e8; }

    }

</style>
  