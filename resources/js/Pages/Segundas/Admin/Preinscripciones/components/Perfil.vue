<template>
    <div class="flex  overflow-hidden shadow-sm sm:rounded-lg  justify-center">
        <div class="bg-white p-5 container">

            {{ info }}

            <div class="bg-white p-5 container">
                {{ props }}
            </div>



            <!-- <div class="p-2">
                <a-row class="p-4" :gutter="[16, 8]" style="border: solid #d9d9d9 1px; border-radius: 9px;">
                    <a-col :xs="24" :sm="12" :md="8" :lg="6">
                        <div style="height: 100%; overflow:hidden;">
                            <div style="margin-bottom: 0px; margin-top:0px">
                                    <img :src="foto" :preview="false" />
                            </div>
                        </div>
                    </a-col>
                    <a-col :xs="24" :sm="12" :md="16" :lg="18">
                        <div class="flex justify-between">
                            <div class="ml-4">
                                <div class="flex mt-5">
                                    <span style="font-weight: bold; font-size: 2.0rem;">{{ info.primer_apellido }} {{ info.segundo_apellido }} {{ info.nombres }}</span>
                                </div>
                                <div style="margin-top:0px;">
                                    <span style="font-size:1.1rem; color:gray; ">{{ info.email }}</span>
                                </div>
                                <div style="margin-top:5px;">
                                    <USerOutlined />
                                    <span style="font-size:1.0rem; font-weight:light; color:gray; ">{{ info.celular }}</span>
                                </div>
                                <div style="margin-top:5px;">
                                    <USerOutlined />
                                </div>

                                <div style="margin-top:5px;">
                                    <USerOutlined />
                                    <span style="font-size:1.0rem; font-weight:light; color:gray; ">{{ info.departamento }} - {{ info.provincia }} - {{ info.distrito }}</span>
                                </div>

                                <div class="flex" style="margin-top:5px;">
                                    <div class="mr-3" style="display:flex; align-items:center;">
                                        <FormOutlined /> {{ preinscripciones }}
                                    </div>
                                    <div class="mr-3" style="display:flex; align-items:center;">
                                        <DeleteOutlined /> {{ inscripciones }}
                                    </div>
                                    <div class="mr-3" style="display:flex; align-items:center;">
                                        <UserOutlined /> {{ control_biometrico }}
                                    </div>
                                    <div class="mr-3" style="display:flex; align-items:center;">
                                        <DownloadOutlined /> 
                                    </div>
                                </div>

                            </div>
                            <div class="mt-6">
                                <a-button type="primary" @click="abrir()" disabled>
                                    Editar
                                    <template #icon>
                                        <DownloadOutlined />
                                    </template>
                                </a-button>

                            </div>
                        </div>
                        <div>

                        </div>
                    </a-col>
                </a-row>
            </div> -->

            <div style="">
                <a-row :gutter="[16, 8]">
                    <a-col :xs="24" :sm="24" :md="16" :lg="24">
                        <a-row :gutter="[16, 8]" class="mt-4">
                            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <a-card class="item-area">
                                    <div class="flex justify-between" style="height: 30px; align-content: center;">
                                        <div class="flex justify-center" style="align-items: center;">
                                            <div style="margin-top: 0px;">
                                                <div style="border-radius: 50%; overflow: hidden; width:40px;">
                                                    <img
                                                        src="https://www.atzegi.eus/modulos/usuariosFtp/conexion/imagenes14254a.jpg">
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div><span style="font-size: 1.5rem; font-weight: bold;">{{
                                                    inscripciones }}</span></div>
                                                <div style="margin-top:-10px;">
                                                    <span style="color:gray;">Inscripciones</span>
                                                </div>

                                            </div>
                                        </div>
                                        <div>
                                            <!-- <RightOutlined /> -->
                                        </div>
                                    </div>
                                </a-card>
                            </a-col>

                            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <a-card class="item-area">
                                    <div class="flex justify-between" style="height: 30px; align-content: center;">
                                        <div class="flex justify-center" style="align-items: center;">
                                            <div style="margin-top: 0px;">
                                                <div style="border-radius: 50%; overflow: hidden; width:40px;">
                                                    <img
                                                        src="https://w7.pngwing.com/pngs/607/452/png-transparent-woman-support-chat-icon.png">
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div><span style="font-size: 1.5rem; font-weight: bold;"> {{
                                                    preinscripciones }} </span></div>
                                                <div style="margin-top:-10px;">
                                                    <span style="color:gray;">Preinscripciones</span>
                                                </div>

                                            </div>
                                        </div>
                                        <div>
                                            <!-- <RightOutlined /> -->
                                        </div>
                                    </div>
                                </a-card>
                            </a-col>

                            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <a-card class="item-area">
                                    <div class="flex justify-between" style="height: 30px; align-content: center;">
                                        <div class="flex justify-center" style="align-items: center;">
                                            <div style="margin-top: 0px;">
                                                <div style="border-radius: 50%; overflow: hidden; width:40px;">
                                                    <img src="https://cdn-icons-png.flaticon.com/512/1083/1083522.png">
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div><span style="font-size: 1.5rem; font-weight: bold;"> {{
                                                    control_biometrico }} </span></div>
                                                <div style="margin-top:-10px;">
                                                    <span style="color:gray;">Ingresos</span>
                                                </div>

                                            </div>
                                        </div>
                                        <div>
                                            <!-- <RightOutlined /> -->
                                        </div>
                                    </div>
                                </a-card>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <a-card class="item-area">
                                    <div class="flex justify-between" style="height: 30px; align-content: center;">
                                        <div class="flex justify-center" style="align-items: center;">
                                            <div style="margin-top: 0px;">
                                                <div style="border-radius: 50%; overflow: hidden; width:40px;">
                                                    <img
                                                        src="https://c8.alamy.com/compes/2ah7akx/la-educacion-icono-en-color-la-graduacion-grado-academico-persona-academica-en-la-tapa-ilustracion-vectorial-aislado-2ah7akx.jpg">
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div><span style="font-size: 1.5rem; font-weight: bold;">0</span></div>
                                                <div style="margin-top:-10px;">
                                                    <span style="color:gray;">Carreras terminadas</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <!-- <RightOutlined /> -->
                                        </div>
                                    </div>
                                </a-card>
                            </a-col>

                        </a-row>
                    </a-col>
                </a-row>
                <a-row>
                <a-col :xs="24" :sm="24" :md="8" :lg="24">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 mt-4"
                style="border: solid #d9d9d9 1px; min-height: 600px;">
                <iframe :src="titulo" frameborder="0" width="100%" style="min-height: 600px;" ></iframe>
                </div>
                    </a-col>
                </a-row>
            </div>

            </div>

    </div>

    <a-modal v-model:open="open" title="Editar Datos">

    </a-modal>


    <a-modal v-model:open="open" title="Modal">
        sdfasdf
    </a-modal>
</template>

<script setup>
import { reactive, ref, onMounted, watch, defineProps } from 'vue';
import { FormOutlined, DeleteOutlined, SearchOutlined, DownloadOutlined, UserOutlined, RightOutlined, BookOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import dayjs from 'dayjs';
import { format } from 'date-fns';
import axios from 'axios';
const props = defineProps(['dni']);

const info = ref(null);

const foto = ref("");
const titulo = ref("");
const infoColegio = ref("");
const preinscripciones = ref("");
const inscripciones = ref("");
const control_biometrico = ref("");



const open = ref(false);

const abrir = () => {
    open.value = true;
    console.log("Esta funcionando");
}

const getPostulante =  async ( ) => {
    let res = await axios.get( "/segundas/get-postulante-datos/"+props.dni);
    info.value = res.data.info;
    titulo.value = res.data.titulo;
    infoColegio.value = res.data.infoColegio;
    preinscripciones.value = res.data.preinscripciones;
    inscripciones.value = res.data.inscripciones;
    control_biometrico.value = res.data.control_biometrico;
}

getPostulante();



const seleccionado = ref(null);

</script>
<style scoped>
.item-inscripciones {
    cursor: pointer;
    background: white;
    align-items: center;
    border-bottom: 1px solid #d9d9d9;
    border-radius: 8px;
}

.item-inscripciones-seleccionado {
    cursor: pointer;
    background: #cdcdcd92;
    color: black;
    border-radius: 7px;
}

.item-area {
    cursor: pointer;
    background: white;
    align-items: center;
    border: 1px solid #d9d9d9;
    border-radius: 8px;
}

.item-area-seleccionado {
    cursor: pointer;
    background: red;
    color: white;
    border-radius: 7px;
}

.container {
    max-width: 1200px;
}</style>






