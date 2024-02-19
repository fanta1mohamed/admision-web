<template>
<LayoutExVocacional>

    <div v-if="ex == 0" style="margin:auto; min-height: calc(100vh - 160px); display: flex; justify-content: center; align-items: center;">
      <a-card style="min-width: 320px;">
          <div style="margin-bottom: 7px;"><label>N° Documento</label></div>
          <a-form-item>
            <a-input v-model:value="dni" :maxlength="12" placeholder="N° Documento"/>
          </a-form-item>

          <div style="margin-bottom: 7px;"><label>Codigo</label></div>
          <a-form-item>
            <a-input v-model:value="codigo" :maxlength="12" placeholder="codigo"/>
          </a-form-item>

          <div style="display: flex; justify-content: center; margin-top: 20px;">
            <a-button type="primary" @click="getDatos()">Iniciar Examen</a-button>
          </div>
      </a-card>
    </div>


    <div v-if="ex == 1" class="flex align-center justify-center">
        <div v-if="nropregunta < 20" class="align-center justify-center mt-7" style="background: #00000008; border-radius: 8px; padding: 20px;">
            <div class="mb-3">
                <!-- {{ datos }} -->
                <!-- <div class="flex justify-end" style="margin-bottom: -25px; width: 800px;">
                    <h1 v-if="tiempo > 16" style="font-size: 2.2rem; font-weight: bold;">00:00:{{ tiempo  }} </h1>
                        <h1 v-else style="font-size: 2.2rem; font-weight: bold;">00:00:0{{ tiempo  }} </h1>
                </div> -->
                <!-- <a-progress :percent="(tiempo * 1.666666666667).toFixed(0)" :strokeWidth="15" :showInfo="false" strokeColor="navy" /> -->
                <a-progress :percent="parseFloat((tiempo * 1.666666666667).toFixed(0))" :strokeWidth="15" :showInfo="false" strokeColor="navy" />
                
            </div>

            <div v-if="preguntas[nropregunta]" class="pl-6 pr-6 pt-4 pb-4" style="border-radius: 5px; color: white; 
                font-size: 1.2rem; box-shadow: 1px 2px 2px 1px #a3a1a143; background: navy; max-width: 800px;">
                {{ preguntas[nropregunta].pregunta }}
             </div>

             <div v-for="respuesta in alternativas" :key="respuesta.ide">
                <a-radio-group v-if="tiempo > 0" v-model:value="respuestas[respuesta.ide]">
                    <div class="mt-4 p-3" style="border-radius: 5px; background: white; color: gray; font-size: 1rem; border: #a3a1a143 1px solid; max-width: 800px;">
                        <a-radio :value="respuesta" style="width: 800px;"><span style="font-size: 1.4rem;">{{ respuesta.respuesta }} </span></a-radio>
                    </div>
                </a-radio-group>            
                <a-radio-group v-else v-model:value="respuestas[respuesta.ide]" disabled>
                    <div class="mt-4 p-3" style="border-radius: 5px; background: white; color: gray; font-size: 1rem; border: #a3a1a143 1px solid; max-width: 800px;">
                        <a-radio :value="respuesta" style="width: 800px;"><span style="font-size: 1.4rem;">{{ respuesta.respuesta }} </span></a-radio>
                    </div>
                </a-radio-group>
            </div>

            <div class="flex justify-between mt-4">
                <div>
                    <span style="font-size: 1.2rem;"> pregunta {{nropregunta + 1}}/20 </span>
                </div>
                
                <div>
                    <!-- <a-button style="background: navy; padding: 10px 10px; height: 40px; width: 200px; border-radius: 5px; color: white;" :loading="loading" @click="guardar(preguntas[nropregunta].id_pregunta, respuestas[index].id)">Siguiente</a-button> -->
                    <a-button style="background: navy; padding: 10px 10px; height: 40px; width: 200px; border-radius: 5px; color: white;" :loading="loading" @click="guardar(preguntas[nropregunta].id_pregunta, respuestas[getSelectedAnswerIndex()])">Siguiente</a-button>

                </div>
            </div>

        </div>
    </div>

    <div v-if="nropregunta == 20" style="display: flex; justify-content:center;">

        <a-card style="width: 380px; border-radius:2px;">
            <div class="flex justify-center mt-4 mb-3">
                <span style="color: #264d79; opacity: 1;">
                <svg xmlns="http://www.w3.org/2000/svg" width="82" height="82" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </span>
            </div>
            <div class="flex justify-center mt-1 mb-1">
                <span style="font-size: 2.3rem;">Buen Trabajo</span>
            </div>
            <div class="flex justify-center" style="text-align: center;">
                <span style="font-size: 1.1rem;">FINALIZÓ CON EXITO SU EXAMEN VOCACIONAL</span>
            </div>

            <div class="flex justify-center mt-4 mb-3"> 
                <a-button type="primary" style="background:#264d79; width:160px;" @click="cerrarmodal()">ACEPTAR</a-button>
            </div>
        </a-card>

    </div>

    <div v-if="ex == 2" style="display: flex; justify-content:center; align-items:center; height:calc(100vh - 150px);">
        <a-card style="width: 380px; border-radius:2px;">
            <div class="flex justify-center mt-4 mb-3">
                <span style="color: #264d79; opacity: 1;">
                <svg xmlns="http://www.w3.org/2000/svg" width="82" height="82" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </span>
            </div>
            <div class="flex justify-center mt-1 mb-1">
                <span style="font-size: 2.3rem;">Buen Trabajo</span>
            </div>
            <div class="flex justify-center" style="text-align: center;">
                <span style="font-size: 1.1rem;">FINALIZÓ CON EXITO SU EXAMEN VOCACIONAL</span>
            </div>

            <div class="flex justify-center mt-4 mb-3"> 
                <a-button type="primary" style="background:#264d79; width:160px;" @click="cerrarmodal()">ACEPTAR</a-button>
            </div>
            
        </a-card>
    </div>


</LayoutExVocacional>
</template>
    
<script setup>
import LayoutExVocacional from '@/Layouts/LayoutExVocacional.vue';
import {ref, watch, onMounted } from 'vue';

const visible = ref(true)
const cerrarmodal = () => {
    datos.value = [];
    dni.value = null;
    codigo.value = null;
    preguntas.value = [];
    alternativas.value = [];
    ex.value = 0;
}

const preguntas = ref([]);
const nropregunta = ref(0)
const alternativas = ref([]);

const respuestas = ref([])

const dni = ref('')
const codigo = ref('')
const datos = ref(null)
const ex = ref(0)

const getDatos = async () => {
    let res = await axios.post("get-datos-examen2",{ dni: dni.value, codigo: codigo.value });
    datos.value = res.data.datos;
    if(res.data.estado == true){
        if (res.data.datos) {
            ex.value = 1;
            tiempo.value = 60;
            getPreguntas();
        }
    }
    else{
        ex.value = 2;
    }
    
}

const loading = ref(false);
const getPreguntas = async () => {
  let res = await axios.post("get-preguntas2",{ postulante: datos.value[0].id, codigo: codigo.value });
  preguntas.value = res.data.datos;   
  if(nropregunta.value === 0){
    getAlternativas(0)
  }
}
const ti = 1000;
const getAlternativas = async (preg) => {
    if(preguntas != []){
        loading.value = true;
        let res = await axios.post("get-alternativas2",{ id_pregunta: preguntas.value[preg].id_pregunta });
        alternativas.value = res.data.datos;   
        tiempo.value = 60;
        iniciarContador();
        loading.value = false;

    }  
}

watch(nropregunta, ( newValue, oldValue ) => {
    getAlternativas(newValue) 
})


const contadorIniciado = ref(false)
const tiempo = ref(60);

const iniciarContador = () => {
  const intervalo = setInterval(() => {
    if (tiempo.value <= 0) {
      clearInterval(intervalo);
    } else {
      tiempo.value --; 
    }
  }, 2600+(nropregunta.value * 1000));
};

const guardar = async (pregunta, respuesta) => {
    if(respuesta){
        loading.value = true;
        let res = await axios.post("save-respuesta", {
            dni: dni.value,
            id_vocacional: datos.value[0].id_vocacional,
            pregunta: pregunta,
            postulante: datos.value[0].id,
            respuesta: respuesta.id,
            nro: nropregunta.value + 1
            });
        nropregunta.value = res.data.nro;
        loading.value = false;
        respuestas.value = [];
        tiempo.value = 60;
  }
  else{
        loading.value = true;
        let res = await axios.post("save-respuesta", {
            dni: dni.value,
            id_vocacional: datos.value[0].id_vocacional,
            pregunta: pregunta,
            postulante: datos.value[0].id,
            nro: nropregunta.value + 1
        });
        nropregunta.value = res.data.nro;
        loading.value = false;
        respuestas.value = [];
        tiempo.value = 60;
  }

}
onMounted(() => {
  iniciarContador();
});

const getSelectedAnswerIndex = () => {
    for (const key in respuestas.value) {
        if (respuestas.value[key]) {
            return key;
        }
    }
    return -1;
}

</script>


