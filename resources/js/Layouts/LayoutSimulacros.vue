<template>
    <a-layout style="min-height: 100vh">
      <a-layout>        
      <div class="headPre" style="height: 200px; margin-top: -20px;">
        <div class="logoPre"><div style="height: 60px;"></div></div>
        <div class="flex justify-center titulo-pre ">
            <div>  
                <span> Examen simulacro </span>
                <div class="flex justify-center"> <hr class="line-pre"> <div class="mr-2 ml-2" style="margin-top: -10px;"><span style="font-size: 1rem;"> Formulario </span></div><hr class="line-pre"> </div>
            </div>
        </div>
      </div>
      <div class="aviso-container">
          <div class="flex mx-2" style="height: 40px; align-items:center; justify-content: center; text-align: center;">
            <span class="aviso-pre">NO OTORGA  ADMISIÓN DIRECTA NI VACANTES A LA UNA PUNO</span>
          </div>
      </div> 


      <a-layout-content
        style="min-height: 20px; padding: 0px 0px;">
        <div style="border-radius: 10px 10px 0px 0px; box-shadow: 1px 1px 1px 2px #a5a5a53f ;  padding: 0px  0px; margin-top: -0px; background: #e3e3e3; min-height: 280px;">
            <slot />
            <div class="aviso-container2">
                <div class="flex ml-2 mr-2" style="height: 46px; align-items: center; justify-content: center; text-align: center;">
                  <span class="aviso-pre" style="color:#2e2e2e;">© 2023 OTI - UNA PUNO. TODOS LOS DERECHOS RESERVADOS.</span>
                </div>
            </div> 
        </div>        
      </a-layout-content>
    </a-layout>
  </a-layout>
</template>
  
<style scoped>
.custom-scrollbar {
  overflow-y: auto;
  scrollbar-width: thin;
  scrollbar-color: #888 #f1f1f1;
}
.custom-scrollbar::-webkit-scrollbar {
  width: 2px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background-color: #664775;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #476175;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: #a588eb7a;
}
.headPre{
    background-repeat: no-repeat;
    background-size: cover; 
    background-image: linear-gradient(#6102a07a, #58178d7a), url('./../../assets/imagenes/f-head-sim.jpg');
}
.logoPre{
    display: flex;
    margin-top: 10px;
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
.line-pre{ width: 30px; border: solid 2px #ff006a;}
.aviso-pre { color: white; font-size: 0.9;}
.aviso-container { background: black; margin-top: -40px;}
.aviso-container2 { background: #e3e3e3; margin-top: 15px; border-top: 1px solid #cdcdcdd9;}

@media (min-height: 680px), screen and (orientation: portrait) {
    .logoPre img{ width: 26px; }
    .logoPre div{margin-top: -10px;}
    .logoPre span{ font-size: 0.6rem; letter-spacing: 0.001rem !important; }
    .logoPreAD{ font-size: 0.76rem; }
    .logoPre .container-pre{ margin-bottom: -8px; margin-top: 5px; }
    .titulo-pre{ margin-top: 30px;}
    .titulo-pre span{ font-size: 1.7rem;  }
    .aviso-pre{ font-size: 0.8rem;  }
    .aviso-container { height: 40px;}
    .line-pre { border: solid 1px #ff0015e8; }
}
</style>
  