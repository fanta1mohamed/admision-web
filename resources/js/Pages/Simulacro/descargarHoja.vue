<template>
    <Head title="Formulario de inscripción" />
    <AuthenticatedLayout>
        <div class="flex justify-center" style="">
            <div style="width: 100%; background: #cdcdcdc; max-width: 1000px; margin-top:20px; margin-bottom:-0px; background:white; border:solid 1px #d9d9d99d;">
                <div class="flex justify-center;" style="align-items:center; min-height: calc(100vh - 270px);">
                    <div style="width:100%">
                        <div class="flex pb-4" style="justify-content:center; ">
                            <span style="font-weight:bold; font-size:1.1rem;">
                                Consultar constancia
                            </span>

                        </div>
                        <div class="flex mb-6 mx-4" style="justify-content:center;">
                            <div class="flex" style="width:100%; max-width: 700px;">
                                <a-input v-model:value="dni" :maxlength="8" style="width: 100%;"
                                    placeholder="Ingresar DNI"/>
                            </div>
                        </div>

                        <a-row>
                            <div class="flex justify-center" style="width:100%; ">
                                <a-button type="primary" :loading="pagosloading"
                                    style="width:180px; background:#340691; border-radius:4px; border: none;"
                                    @click="imprimir()"> Descargar </a-button>
                            </div>
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
 
const dni = ref("");

watch(dni, ( newValue, oldValue ) => { 
    if(newValue.length == 8){ 
        console.log("get resultado");
        // getResultados();
    } 
})


const imprimir = async () => {
    imp();
    await new Promise(resolve => setTimeout(resolve, 9000));
}


const imp = () => {
  const pdfUrl = 'https://inscripciones.admision.unap.edu.pe/pdf-simulacro-inscripcion/' + dni.value;
  const link = document.createElement('a');
  link.href = pdfUrl;
  link.target = '_blank';
  link.download = 'ci'+dni.value+'.pdf';
  link.click();
};




</script>
<style scoped>
.titulo-form {
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