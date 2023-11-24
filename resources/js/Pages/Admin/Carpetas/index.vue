<template>


    <div class="ml-3 flex mb-2 mt-5">
        <div class="flex">
            <div style="margin-top: -4px;"><HomeOutlined/></div>
            <div  v-for="it in breadcrumb" :key="it.id" @click="entrarCarpeta(it)">
                <div class="flex" style="height:20px; align-items:center;"> <div class="carpetas-select px-1"> {{ it.nombre }} </div> / </div>
            </div>

        </div>
    </div>

    <div class="flex" @contextmenu.prevent="clickderecho()">
        <div class="carpetas-select" v-for="item in carpetas" :key="item.id" @dblclick="entrarCarpeta(item)" >
            <div>
                <svg width="80px" height="60px" viewBox="0 0 32 32" enable-background="new 0 0 32 32" version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g><path d="M27,9v5c0,0.55-0.45,1-1,1H6.74L2.96,27.29C2.83,27.72,2.43,28,2,28c-0.05,0-0.1,0-0.15-0.01    C1.36,27.92,1,27.5,1,27V5c0-0.55,0.45-1,1-1h7c0.31,0,0.61,0.15,0.8,0.4L12.5,8H26C26.55,8,27,8.45,27,9z" fill="#476175"/></g>
                    <g><path d="M30.96,14.29l-4,13C26.83,27.71,26.44,28,26,28H2c-0.32,0-0.62-0.15-0.8-0.41    c-0.19-0.25-0.25-0.58-0.16-0.88l4-13C5.17,13.29,5.56,13,6,13h24c0.32,0,0.62,0.15,0.8,0.41C30.99,13.66,31.05,13.99,30.96,14.29    z" fill="#4761ac"/></g>
                </svg>
            </div>
            <div>{{ item.nombre }}</div>
    </div>
    </div>







    <div @contextmenu.prevent="handleContextMenu" style="height: 600px; background-color: #eee; position: relative;">
        <!-- Contenido del div -->
        <p>Haz clic derecho dentro de este div para mostrar el menú contextual.</p>

        <!-- Menú contextual -->
        <!-- <div>{{ contextMenuTop }} {{ contextMenuLeft }}</div> -->
        <div style=" position: absolute;" v-if="showContextMenu" :style="{ top: `${contextMenuTop - 115}px`, left: `${contextMenuLeft+10}px` }">
            <a-menu size="small">
                <a-menu-item key="1" class="selec-menu" @click="handleMenuItemClick('1')">
                    <div style="margin-top: 0px;">
                        Nueva Carpeta
                    </div>
                </a-menu-item>
                <a-menu-item key="2" class="selec-menu" @click="handleMenuItemClick('2')">
                    <div style="margin-top: 0px;">
                        Subir archivo
                    </div>
                </a-menu-item>
            </a-menu>
        </div>

    </div>

    <a-modal v-model:visible="modalNuevo" :closable="false" style="max-width: 320px;" :footer="false">
        <div style="background: #476175; height: 36px; margin-left:-24px; margin-right:-24px; margin-top:-24px;">
            <div class="flex justify-between ml-3 mr-1" style="height:36px; align-items: center;">
                <div><span style="font-weight: bold; color:white; font-size:1rem;">Nueva carpeta</span></div>
                <div><span ><a-button @click="cerramodal()" style="background:none; border:none; color:white; font-size: 1rem;">x</a-button></span></div>
            </div>

        </div> 

        <div class="flex" style="height: 100px; align-items: center;">
            <div style="width: 100%;" class="">
                <a-input v-model:value="nombre" style="width:100%;" placeholder="Nombre carpeta">
                    <template #prefix>
                        <FolderOutlined/>
                    </template>
                </a-input>
            </div>
        </div>

        <div>
            <div style="margin:-24px; margin-top: -5px;" class="px-6 pb-3 flex justify-end">
                <a-button @click="creatCarpeta()" style="background: #476175; border:none; color:white; width:100px; border-radius:4px;"> Guardar </a-button>
            </div>
        </div>

    </a-modal>
    

</template>

<script setup>
import {ref, watch, reactive} from 'vue';
import axios from 'axios';
import { FolderOutlined, HomeOutlined } from '@ant-design/icons-vue';


const breadcrumb = ref([{ "id": 31, "nombre": "home"}]);

const archivos = ref([]);
const carpeta = ref(null);
const carpetas = ref([]);
const carpetaId = ref(31);
const modalNuevo = ref(false);
const nombre = ref("");

const getCarpetas = async ( ) => {
    try {
        const response = await axios.get("/carpetas/ver-contenido-carpeta/"+carpetaId.value);
        carpetas.value = response.data.carpetas;
        carpeta.value = response.data.carpeta;
    } catch (error) {
        if (error.response) {
            console.error('Error de servidor:', error.response.data);
        } else if (error.request) {
            console.error('Error de red:', error.request);
        } else {
            console.error('Error de configuración:', error.message);
        }
    }
};

const creatCarpeta = async ( ) => {
    try {
        const response = await axios.post("/carpetas/crear-carpeta",{nombre: nombre.value, carpeta_padre_id: carpetaId.value });
        getCarpetas();
        modalNuevo.value = false;
    } catch (error) {
        if (error.response) {
            console.error('Error de servidor:', error.response.data);
        } else if (error.request) {
            console.error('Error de red:', error.request);
        } else {
            console.error('Error de configuración:', error.message);
        }
    }
};


const entrarCarpeta = async (elemento) => {
    const indice = breadcrumb.value.indexOf(elemento);
    
    if (indice !== -1) {
        breadcrumb.value.splice(indice + 1, breadcrumb.value.length - indice);
    } else {
        breadcrumb.value.push(elemento);
    }

    carpetaId.value = elemento.id;
} 

watch(carpetaId, ( newValue, oldValue ) => { getCarpetas() })


const clickderecho = (event) => {
    console.log('Clic derecho detectado', event);
    // event.preventDefault();
}

const cerramodal = () => {
    modalNuevo.value = false;
}




getCarpetas();
  






const showContextMenu = ref(false);
const contextMenuTop = ref(0);
const contextMenuLeft = ref(0);


const handleContextMenu = (event) => {
  showContextMenu.value = true;
  contextMenuTop.value = event.clientY;
  contextMenuLeft.value = event.clientX;

  // Evita que se muestre el menú contextual del navegador
  event.preventDefault();
};

const handleMenuItemClick = ( opcion ) => {
    if(opcion === '1'){
        modalNuevo.value = true;
        showContextMenu.value = false;
    }else{
        console.log("2");
    }

};








</script>
<style>
.carpetas-select{
    cursor:pointer;
    text-align: center;
    background: none;
}
.carpetas-select:hover{
    text-align: center;
    background: #0f0f002c;
    border-radius: 5px;
}
.selec-menu{
    height:26px; 
}
.selec-menu:hover{
    background:#cdf3f3; 
}

</style>