<template>
    <Head title="Formulario de inscripción" />
    <AuthenticatedLayout>
        <div class="flex justify-center" style="">
            <div style="width: 100%; background: #cdcdcdc; max-width: 1000px; margin-top:20px; margin-bottom:-0px; background:white; border:solid 1px #d9d9d99d;">
                <div class="flex justify-center;" style="align-items:center; min-height: calc(100vh - 270px);">
                    <div style="width:100%">
                        <div class="flex pb-4" style="justify-content:center; ">
                            <span style="font-weight:bold; font-size:1.1rem;">
                                Consultar puntaje
                            </span>
                        </div>
                        <div class="flex mb-6 mx-4" style="justify-content:center;">
                            <div class="flex" style="width:100%; max-width: 700px;">
                                <a-input v-model:value="dni" :maxlength="8" style="width: 100%;"
                                    placeholder="Ingresar DNI"/>
                            </div>
                        </div>

                        <div v-if="resultado !== null " class="flex justify-center" >
                            <div class="p-4 px-5" style="border:1px solid #d9d9d9; border-radius: 8px; min-width: 380px">
                                <div class="flex justify-between" >
                                    <div><span style="font-size: 1.2rem; font-weight: bold;">Examen simulacro</span></div> 
                                    <div><span style="font-size: 1.2rem; font-weight: bold; ">11-11-23</span></div> 
                                </div>
                                <div class="flex justify-center">
                                    <span style="font-size: 3.3rem; font-weight: bold;"> {{ resultado.puntaje }} </span>
                                    
                                </div>
                                <div class="flex justify-center">
                                    <span style="font-size: 1.5rem; font-weight: bold; ">
                                        Puesto: {{ resultado.puesto_programa }}
                                    </span>
                                </div>
                        
                                <div class="flex justify-center mb-2 mt-3">
                                    <div v-if="resultado.area === 'INGENIERÍAS'" class="mr-3"> <a-button @click="descargarIngenierias" type="primary" style="width:100%; background:none; color: #340691; border: 1px solid #340691; border-radius:4px;">Descargar examen</a-button> </div>                                    
                                    <div v-if="resultado.area === 'SOCIALES'" class="mr-3"> <a-button @click="descargarSociales" type="primary" style="width:100%; background:none; color: #340691; border: 1px solid #340691; border-radius:4px;">Descargar examen</a-button> </div>
                                    <div v-if="resultado.area === 'BIOMÉDICAS'" class="mr-3"> <a-button @click="descargarBiomedicas" type="primary" style="width:100%; background:none; color: #340691; border: 1px solid #340691; border-radius:4px;">Descargar examen</a-button> </div>
                                    <div> <a-button @click="nuevaConsulta" type="primary" style="width:100%; background:#340691; border-radius:4px; border: none;">Otra Consulta</a-button></div>
                                </div>
                            </div>
                        </div>

                        


                        <a-row>
                            <!-- <div class="flex justify-center" style="width:100%; ">
                                <a-button type="primary" :loading="pagosloading"
                                    style="width:180px; background:#340691; border-radius:4px; border: none;"
                                    @click="enviarPago()"> Continuar </a-button>
                            </div> -->
                        </a-row>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
        
<script setup>
import AuthenticatedLayout from '@/Layouts/LayoutSimulacros.vue'
import { watch, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
const baseUrl = window.location.origin;
 
const dni = ref("");
const resultado = ref(null);


const getPuntaje = async () => {
    axios.post("/get-puntaje-simulacro",{ dni:dni.value })
    .then((response) => {
        resultado.value = response.data.datos;
    })
    .catch((error) => {
        if (error.response) {
            console.error('Error de servidor:', error.response.data);
        } else if (error.request) {
            console.error('Error de red:', error.request);
            } else { console.error('Error de configuración:', error.message); }
  });
}

const descargarSociales = () => {
    window.open(baseUrl+'/descargar-sociales', '_blank');
}
const descargarIngenierias = () => {
    window.open(baseUrl+'/descargar-ingenierias', '_blank');
}
const descargarBiomedicas = () => {
    window.open(baseUrl+'/descargar-biomedicas', '_blank');
}

const nuevaConsulta = () => {
    const dni = ref("");
    const resultado = ref(null);
}


watch(dni, ( newValue, oldValue ) => { 
    if(newValue.length == 8){ 
        getPuntaje();
    } 
})


</script>
<style scoped>.titulo-form {
    margin-top: 20px;
    font-size: 1.2rem;
    color: #000000c4;
    font-weight: bold;
}

.selected {
    background-color: #e0e0e0;
}

.deshabilitado {
    opacity: 0.5;
    pointer-events: none;
    cursor: not-allowed;
}</style>