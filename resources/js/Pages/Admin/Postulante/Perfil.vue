<template>
    <Head title="Postulantes" />
    <AuthenticatedLayout style="">

        <div class="flex  overflow-hidden shadow-sm sm:rounded-lg  justify-center">
            <div class="bg-white p-5 container">
                <div class="p-2">
                    <a-row class="p-4" :gutter="[16, 8]" style="border: solid #d9d9d9 1px; border-radius: 9px;">
                        <a-col :xs="24" :sm="12" :md="8" :lg="6">
                            <div style="border-radius:100%; width: 100%; overflow:hidden;">
                                <div style="margin-bottom: -30px; margin-top:-30px">
                                    <!-- <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQKTXDwTTnsPx88rP_7Ljrt1SShsD9xbdnmn0prjfe1Ww&s"> -->
                                    <a-image :src="foto" :preview="false" />
                                </div>
                            </div>
                        </a-col>
                        <a-col :xs="24" :sm="12" :md="16" :lg="18">
                            <div class="flex justify-between">
                                <div class="ml-4">
                                    <div class="flex mt-5">
                                        <span style="font-weight: bold; font-size: 2.0rem;">{{ info.nombres }}</span>
                                    </div>
                                    <div style="margin-top:0px;">
                                        <span style="font-size:1.1rem; color:gray; ">{{ info.email }}</span>
                                    </div>
                                    <div style="margin-top:5px;">
                                        <USerOutlined />
                                        <span style="font-size:1.0rem; font-weight:light; color:gray; ">{{ info.celular
                                        }}</span>
                                    </div>
                                    <div style="margin-top:5px;">
                                        <USerOutlined />
                                        <span style="font-size:1.0rem; font-weight:light; color:gray; "> {{
                                            infoColegio.colegio }} - {{ infoColegio.distrito }} </span>
                                        <USerOutlined />
                                        <span style="font-size:1.0rem; font-weight:light; color:gray; "></span>
                                    </div>

                                    <div style="margin-top:5px;">
                                        <USerOutlined />
                                        <span style="font-size:1.0rem; font-weight:light; color:gray; ">{{ info.departamento
                                        }} - {{ info.provincia }} - {{ info.distrito }}</span>
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
                                            <DownloadOutlined /> 0
                                        </div>
                                    </div>

                                </div>
                                <div class="mt-6">
                                    <a-button type="primary" @click="abrir()">
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
                </div>

                <div style="">
                    <a-row :gutter="[16, 8]">
                        <a-col :xs="24" :sm="24" :md="16" :lg="18">
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
                            <div class=" bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 mt-4 "
                                style="border: solid #d9d9d9 1px;">
                                <Grafico />
                            </div>
                        </a-col>

                        <a-col :xs="24" :sm="24" :md="8" :lg="6">
                            <div class=" bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 mt-4"
                                style="border: solid 1px #d9d9d9; height: 524px;">
                                <div>
                                    <a-card size="small" v-for="item in pro" :key="item.id_proceso"
                                        :class="seleccionado == item.id_proceso ? 'item-inscripciones-seleccionado' : 'item-inscripciones'"
                                        @click="seleccionado = item.id_proceso">
                                        <div class="flex justify-between" style="height: 30px; align-content: center;">
                                            <div class="flex justify-center" style="align-items: center;">
                                                <div style="margin-top: -5px;">
                                                    <BookOutlined />
                                                </div>
                                                <div class="ml-3">
                                                    <span>{{ item.proceso }}</span>
                                                    <!-- <div style="margin-top: -10px; color:gray;"><span style="font-size: .7rem;"> {{ item.codigo }} </span></div> -->
                                                </div>
                                            </div>
                                            <div>
                                                <RightOutlined />
                                            </div>
                                        </div>
                                    </a-card>
                                </div>

                            </div>
                        </a-col>
                    </a-row>
                    <!-- <div style="display:none;" v-if="seleccionado == null">
            {{ seleccionado = pro[0].id_proceso }}
        </div> -->
                </div>

                <div class="p-2">
                    <a-row class="p-4" :gutter="[16, 8]" style="border: solid #d9d9d9 1px; border-radius: 9px;">
                        <div>
                            <h1 style="font-weight: bold; font-size: 1.1rem;">Actividad Reciente</h1>
                        </div>
                        <a-table :dataSource="dataSource" :columns="columns" style="width: 100%;">
                            <template #bodyCell="{ column, index, record }">
                                <template v-if="column.dataIndex === 'acciones'">
                                    <div class="" style="justify-content: flex-start;">
                                        <span class="mr-2">{{ record.usuario }} </span>
                                            <span> <a-tag v-if="record.acciones == 'insertó'" color="green"> {{ record.acciones }} </a-tag></span>  
                                            <span> <a-tag v-if="record.acciones == 'actualizó'" color="blue"> {{ record.acciones }} </a-tag> </span>
                                            <span> <a-tag v-if="record.acciones == 'eliminó'" color="red"> {{ record.acciones }} </a-tag> </span>
                                            el registro <span class="mr-2 ml-2" style="font-weight: bold;"> N° {{ record.registro }} </span> en la tabla {{ record.tabla }}
                                    </div>
                                </template>
                            </template>
                        </a-table>
                    </a-row>    
                </div>

             </div>






        </div>

        <a-modal v-model:visible="open" title="Editar Datos">
            
        </a-modal>


        <a-modal v-model:visible="open" title="Modal">
            sdfasdf
        </a-modal>

    </AuthenticatedLayout>
</template>    
    
<script setup>
import { reactive, ref, onMounted, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { FormOutlined, DeleteOutlined, SearchOutlined, DownloadOutlined, UserOutlined, RightOutlined, BookOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import dayjs from 'dayjs';
import { format } from 'date-fns';
import axios from 'axios';
import Grafico from './components/grafico.vue';

const open = ref(false);

const abrir = () => {
    open.value = true;
    console.log("Esta funcionando");
}

const props = defineProps({
    foto: String,
    info: Object,
    infoColegio: Object,
    preinscripciones: Number,
    inscripciones: Number,
    control_biometrico: Number,
    pro: Array
})

const seleccionado = ref(null);


const dataSource = ref([
    {
        usuario_id: 1,
        usuario: 'Ariel Luque',
        acciones: 'insertó',
        registro:2322,
        tabla: 'filial',
        direccion: '127.168.12.1',
        fecha:'2023-11-03 08:55:20'
    },
    {
        usuario_id: 1,
        usuario: 'Luque King',
        acciones: 'actualizó',
        registro:233,
        tabla: 'filial',
        direccion: '127.85.0.1',
        fecha:'2023-11-03 10:55:20'
    },

    {
        usuario_id: 1,
        usuario: 'Madara',
        acciones: 'eliminó',
        registro:126,
        tabla: 'filial',
        direccion: '10.10.120.1',
        fecha:'2023-11-07 10:15:20'
    },
    {
        usuario_id: 1,
        usuario: 'king',
        acciones: 'eliminó',
        registro:219,
        tabla: 'filial',
        direccion: '127.140.192.1',
        fecha:'2023-11-12 08:55:20'
    },


]);

const columns = ref([
    {
        title: 'Actividad',
        dataIndex: 'acciones',
        key: 'age',
        align: 'left'
    },
    {
        title: 'Dirección',
        dataIndex: 'direccion',
        key: 'address',
    },

    {
        title: 'Fecha y hora',
        dataIndex: 'fecha',
        key: 'address',
    },
]);


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
    
    
    



