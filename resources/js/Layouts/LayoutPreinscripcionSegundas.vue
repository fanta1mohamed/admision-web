<template>
    <a-layout style="min-height: 100vh;">
      <a-layout>
      <div class="headPre" style="height: 230px; margin-top:-26px;">
        <div class="flex justify-between" style="margin-top:30px;">  
            <div class="logoPre">
                <div style="margin-top: -10px;"> <img src="./../../assets/imagenes/logotiny.png" width="45" /> </div>
                <div class="x"> <div class="container-pre"><span style="letter-spacing: 0.23rem;">SEGUNDAS ESPECIALIDADES</span></div> <h1 class="logoPreAD mt-2" > UNA - PUNO </h1> </div>
                <!-- <div style="margin-top: -10px;"> <img src="./../../assets/imagenes/logoDAD.png" width="45" /> </div> -->
            </div>
            <!-- <div style="padding-top: 10px;">
                <strong style="color: #a410e9; font-size: 1.5rem; 140px; margin-right: 30px;">CEPREUNA</strong>                
            </div> -->
        </div>

        <div class="flex justify-center title-proceso-container">
            <span class="title-proceso">{{ props.nombre }}</span>
        </div>    
        
        <div class="flex justify-center titulo-pre">
            <div>
                <!-- <span> Preinscripción </span> -->
                <div class="flex justify-center mt-2">
                    <hr class="line-pre mr-3" style="margin-top:10px;"> 
                        <span style="font-size:1.4rem; text-transform:capitalize;">Preinscripción</span> 
                    <hr class="line-pre ml-3" style="margin-top:10px;"> 
                </div>
            </div>
        </div>
      </div>
      <a-layout-content
        style="min-height: 280px; padding: 0px 0px;">
        <!-- style="min-height: 280px; padding: 0px 10px;"> -->
        <div style="border-radius: 10px 10px 0px 0px; box-shadow: 1px 1px 1px 2px #a5a5a53f ; min-height: calc(100vh - 180px); padding: 0px  0px; margin-top: -40px;  background:#e3e3e3;">
            <slot />
        </div>        
      </a-layout-content>
    </a-layout>
  </a-layout>
</template>
<script setup>
import { defineProps, watch, ref } from 'vue';

const props = defineProps(['nombre']);
</script>
<style scope>
.headPre{
    background-repeat: no-repeat;
    background-size: cover;
    background-image: linear-gradient(#178069b2, #1d7a5793), url('./../../assets/imagenes/f-head-sim.jpg');
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
    margin-bottom: 8px; margin-top: 10px;
}

.titulo-pre div span{
    color: white;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 2.7rem;
}

.line-pre{
    width: 25px; border: solid 2px #ff0015e8;
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
    .logoPre span{ font-size: 0.8rem; letter-spacing: 0.061rem !important; }
    .logoPreAD{ font-size: 1.1rem; }
    .logoPre .container-pre{ margin-bottom: -8px; margin-top: 5px; }
    .titulo-pre{ margin-top: 0px;}
    .titulo-pre div span{ font-size: 1.4rem; }
    .line-pre { width: 30px; border: solid 3px #ff0015e8; }
    .title-proceso { font-size:1.5rem; color:white; font-weight:bold; text-align:center; }
    .title-proceso-container { margin-top: 10px; margin-bottom:0px; }
}

</style>
  