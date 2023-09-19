<template>
    <Head title="Postulantes"/>
    <AuthenticatedLayout style="">
    <div class=" bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">    
        <a-row :gutter="[16, 8]">
            <a-col :xs="24" :sm="12" :md="8" :lg="6">
                <div style="border-radius:100%; width: 100%; overflow:hidden;">
                    <div style="margin-bottom: -30px; margin-top:-30px">
                    <!-- <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQKTXDwTTnsPx88rP_7Ljrt1SShsD9xbdnmn0prjfe1Ww&s"> -->
                    <a-image
                        src="https://inscripciones.admision.unap.edu.pe/fotos/biometrico/60066068.jpg"                            
                    />
                    </div>
                </div>
            </a-col>
            <a-col :xs="24" :sm="12" :md="16" :lg="18">
                <div class="flex justify-between">
                    <div>
                        <div class="flex mt-5">
                            <span style="font-weight: bold; font-size: 2.0rem;">JHON ARIEL</span>
                        </div>
                        <div style="margin-top:0px;">
                            <span style="font-size:1.1rem; color:gray; ">jhonar.theking@gmail.com</span>
                        </div>
                        <div style="margin-top:5px;">
                            <USerOutlined/>
                            <span style="font-size:1.0rem; font-weight:light; color:gray; ">966637192</span>
                        </div>
                        <div style="margin-top:5px;">
                            <USerOutlined/>
                            <span style="font-size:1.0rem; font-weight:light; color:gray; ">Nuestra Señora del Carmen - Ilave</span>
                            <span> / </span>
                            <USerOutlined/>
                            <span style="font-size:1.0rem; font-weight:light; color:gray; "></span>
                        </div>

                        <div style="margin-top:5px;">
                            <USerOutlined/>
                            <span style="font-size:1.0rem; font-weight:light; color:gray; ">Puno - El collao - Ilave</span>
                        </div>

                        <div class="flex" style="margin-top:5px;">
                            <div class="mr-3" style="display:flex; align-items:center;"><FormOutlined/> 1 </div>
                            <div class="mr-3" style="display:flex; align-items:center;"><DeleteOutlined/> 0 </div>
                            <div class="mr-3" style="display:flex; align-items:center;"><UserOutlined/> 0 </div>
                            <div class="mr-3" style="display:flex; align-items:center;"><DownloadOutlined/> 0 </div>
                        </div>

                    </div>
                    <div class="mt-6">
                        <a-button type="primary">
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

    <div>
        <a-row :gutter="[16, 8]">
            <a-col :xs="24" :sm="12" :md="16" :lg="18">
                <a-row :gutter="[16, 8]" class="mt-4">
                    <a-col :xs="24" :sm="12" :md="12" :lg="12">
                        <a-card>
                            <div class="flex justify-between" style="height: 30px; align-content: center;">
                                <div class="flex justify-center" style="align-items: center;">
                                    <div style="margin-top: 0px;">
                                        <div style="border-radius: 50%; overflow: hidden; width:40px;">
                                            <img src="https://www.atzegi.eus/modulos/usuariosFtp/conexion/imagenes14254a.jpg">
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div><span style="font-size: 1.5rem; font-weight: bold;">3</span></div>
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

                    <a-col :xs="24" :sm="12" :md="12" :lg="12">
                        <a-card>
                            <div class="flex justify-between" style="height: 30px; align-content: center;">
                                <div class="flex justify-center" style="align-items: center;">
                                    <div style="margin-top: 0px;">
                                        <div style="border-radius: 50%; overflow: hidden; width:40px;">
                                            <img src="https://w7.pngwing.com/pngs/607/452/png-transparent-woman-support-chat-icon.png">
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div><span style="font-size: 1.5rem; font-weight: bold;">5</span></div>
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

                    <a-col :xs="24" :sm="12" :md="12" :lg="12">
                        <a-card>
                            <div class="flex justify-between" style="height: 30px; align-content: center;">
                                <div class="flex justify-center" style="align-items: center;">
                                    <div style="margin-top: 0px;">
                                        <div style="border-radius: 50%; overflow: hidden; width:40px;">
                                            <img src="https://cdn-icons-png.flaticon.com/512/1083/1083522.png"> 
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div><span style="font-size: 1.5rem; font-weight: bold;">1</span></div>
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
                    <a-col :xs="24" :sm="12" :md="12" :lg="12">
                        <a-card>
                            <div class="flex justify-between" style="height: 30px; align-content: center;">
                                <div class="flex justify-center" style="align-items: center;">
                                    <div style="margin-top: 0px;">
                                        <div style="border-radius: 50%; overflow: hidden; width:40px;">
                                            <img src="https://c8.alamy.com/compes/2ah7akx/la-educacion-icono-en-color-la-graduacion-grado-academico-persona-academica-en-la-tapa-ilustracion-vectorial-aislado-2ah7akx.jpg"> 
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
                <div class=" bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 mt-4">
                    <Grafico/>
                </div>
            </a-col>
            <a-col :xs="24" :sm="12" :md="8" :lg="6">
                
                <div class=" bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 mt-4">
                    <div >
                        <a-card size="small" @click="seleccionar" :class="seleccionado === 0? 'item-inscripciones':'item-inscripciones-seleccionado'">
                            <div class="flex justify-between" style="height: 30px; align-content: center;">
                                <div class="flex justify-center" style="align-items: center;">
                                    <div style="margin-top: -5px;"><BookOutlined /></div>
                                    <div class="ml-3"><span>GENERAL</span></div>
                                </div>
                                <div>
                                    <RightOutlined />
                                </div>
                            </div>
                        </a-card>
                    </div>

                    <div class="mt-2">
                        <a-card size="small" @click="seleccionar" :class="seleccionado === 0? 'item-inscripciones':'item-inscripciones-seleccionado'">
                            <div class="flex justify-between" style="height: 30px; align-content: center;">
                                <div class="flex justify-center" style="align-items: center;">
                                    <div style="margin-top: -5px;"><BookOutlined /></div>
                                    <div class="ml-3"><span>GENERAL</span></div>
                                </div>
                                <div>
                                    <RightOutlined />
                                </div>
                            </div>
                        </a-card>
                    </div>

                    <div class="mt-2" >
                        <a-card size="small" @click="seleccionar" :class="seleccionado === 0? 'item-inscripciones':'item-inscripciones-seleccionado'">
                            <div class="flex justify-between" style="height: 30px; align-content: center;">
                                <div class="flex justify-center" style="align-items: center;">
                                    <div style="margin-top: -5px;"><BookOutlined /></div>
                                    <div class="ml-3"><span>GENERAL</span></div>
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
    </div>        
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

const seleccionado = ref(0);

const seleccionar = () => {
    if( seleccionado.value == 1 ) { seleccionado.value = 0; } else { seleccionado.value = 1; }

}
    
</script>

<style scoped>
.item-inscripciones{
    cursor: pointer;
    background: white;
    border:none;
    align-items: center;
    border-bottom: 1px solid #d9d9d964;
}
.item-inscripciones-seleccionado{
    cursor: pointer;
    background: #24255779;
    color: white;
    border-radius: 7px;
}
</style>
    
    
    



