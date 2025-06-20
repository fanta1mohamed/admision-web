<template>
    <div 
        title="Cargar fichas de identificación" 
        @ok="okey" 
        :centered="true" 
        style="max-height: calc(100vh - 100px); overflow-x: scroll; cursor: pointer;">

        <a-upload-dragger
        v-model:fileList="fileList"
        name="file"
        :multiple="true"
        :action="baseUrl + '/calificacion/carga-ide/'+proceso+'/'+area"
        @change="handleChange"
        @drop="handleDrop"
        list-type="picture"
        >
        <p class="ant-upload-drag-icon ">
            <inbox-outlined></inbox-outlined>
        </p>
        <p class="ant-upload-text" style="width: 100%;">Haz clic o arrastra archivos a esta área para cargar</p>
        <p class="ant-upload-hint">
            Soporte para carga única o múltiple. Prohibido subir datos de la empresa u otros archivos prohibidos.
        </p>
        </a-upload-dragger>  
</div>

<div class="mb-4 pl-4 flex justify-between" style="align-items: center; width:calc( 100% + 48px); height: 37px; background: #0059ff; margin: -24px; margin-bottom: 0px;">
    <div><span style="color:white; font-size: 1.1rem;">Archivos del ingresante</span></div>
    <div class="button-div" style="cursor: pointer; height: 30px; width: 30px; margin-right: 5px; text-align: center; "><span style="color:white; font-size: 1.1rem;">x</span></div>
</div>

<div class="p-3 mt-5" style="background: #e3e3e33d; border-radius: 5px;">
    <div class="mb-3">
        <span style="font-size: 1rem;"> Seleección de certificado en PDF </span>
    </div>
    <a-space class="mb-2" direction="vertical" style="width: 100%" size="large">
        <a-upload
            v-model:file-list="fileList"
            list-type="picture"
            :max-count="1"  
            :action="'/calificacion/carga-ide/6/'+dni.value"
            >
            <a-button style="background: #cccccc8d;">
                <upload-outlined></upload-outlined>
                Seleccionar certificado en PDF
            </a-button>
        </a-upload>
    </a-space>
</div>

</template>
      
<script setup>
import { defineProps, watch, ref } from 'vue';
import axios from 'axios';
import { FolderOutlined, HomeOutlined, EnvironmentOutlined, DownOutlined, FormOutlined, DeleteOutlined, SaveOutlined, SearchOutlined } from '@ant-design/icons-vue';
import { message } from 'ant-design-vue';
const baseUrl = window.location.origin;
const fileList = ref([]);
const tabPosition = ref('contenido')
import { notification } from 'ant-design-vue';

const props = defineProps(['proceso']);

const visible = ref(false);

const handleChange = (info) => {
    const status = info.file.status;
    if (status !== 'uploading') { console.log(info.file, fileList.value); }
    if (status === 'done') {
    message.success(`${info.file.name} archivo(s) subido(s) exitosamente.`);
    getArchivos();
    } else if (status === 'error') {
    message.error(`${info.file.name} falló al subir.`);
    }
};

const okey = () => { fileList.value = null;};

const archivos = ref([]);
const identificaciones = ref([]);
const buscar = ref("");

const getIdes = async () => {
    axios.post("/get-ides",{"term": buscar.value, "proceso": props.proceso})
    .then((response) => {
        identificaciones.value = response.data.datos.data;
    })
    .catch((error) => {
        if (error.response) {
            console.error('Error de servidor:', error.response.data);
        } else if (error.request) {
            console.error('Error de red:', error.request);
            } else { console.error('Error de configuración:', error.message); }
    });
}

//const notificacion = (type, titulo, mensaje) => { notification[type]({ message: titulo, description: mensaje, }); };

</script>
      
