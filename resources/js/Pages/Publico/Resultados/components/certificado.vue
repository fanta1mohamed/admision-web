<template>
<a-card class="mx-2 my-2 mt-2" >
<div>
    <div class="flex justify-between border-b-2" style="border-bottom: solid 2px #009DD1; padding-bottom: 8px;">
        <div><span style="font-weight: bold; font-size: 1rem; color:#009DD1;">CERTIFIACDO DE ESTUDIOS</span></div>
        <div style="margin-top: -5px;"><a-button @click="abrirModal()">Agregar</a-button></div>
    </div>


    <div class="mt-3">
        {{ props}}

        <div class="mb-2" v-for="titulo in titulos" :key="titulo">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="4" :md="4" :lg="2">
                    <div>{{ voltearFecha(titulo.fec_expedicion) }}</div>
                </a-col>
                <a-col :xs="24" :sm="20" :md="20" :lg="22">
                    <div class="flex justify-between" style="">
                        <div class="">
                            <div style="margin-bottom: -5px; margin-top: -2px;"><span style="font-size: .8rem;"> {{ titulo.tipo_nombre }} </span></div>
                            <div> <span class="font-bold" style="font-size:1rem;"> {{  titulo.denominacion }}</span></div>
                            <div> {{ titulo.institucion }} </div>
                            <div> <span style="font-style: italic; color:#009DD1;">{{ titulo.reg_sunedu == 0?"Sin registro":"Registrado en SUNEDU" }} </span></div>
                        </div>
                        <div class="flex" style="margin-top: 0px;">
                            <a-button @click="abriPDf(titulo.url)" class="mr-2" style="width: 20px; height: 20px; padding-left: 3px; border: solid #1a2843 1px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </a-button>
                            <a-button @click="abrirEditar(titulo)" class="mr-2" style="width: 20px; height: 20px; padding-left: 3px; border: solid #1a2843 1px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#1a2843" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </a-button>
                            <a-button danger @click="eliminarTitulo(titulo.id)" style="width: 20px; height: 20px; padding-left: 3px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </a-button>
                        </div>
                    </div>
                </a-col>
            </a-row>            
        </div>

    </div>
</div>
</a-card>



<div class="">
    <a-modal v-model:visible="modaltitulo" width="800px" class="w-full md:w-3/4" title="Registro de certificado"  @ok="handleOk">
        <a-form
            ref="formDatos"
            name="form"
            :model="form" 
            :rules="formRules"   
        >
            <a-row :gutter="16">

                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <label>Tipo<span style="color:red;">*</span></label>
                    <a-form-item name="tipo" :rules="[{ required: true, message: 'Este campo es obligatorio' }]">
                        <a-select
                            ref="select"
                            v-model:value="form.tipo"
                            style="width: 100%"
                            :options="tipos"
                            @focus="focus"
                            @change="selecionarTipo"
                        >
                        </a-select>
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <label>Observación<span style="color:red;">*</span></label>
                    <a-form-item name="observacion">
                        <a-input v-model:value="form.observacion" style="height: 32px;">
                            <template #suffix>
                            </template>
                        </a-input>
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <label>Archivo PDF (Max. 2mb)<span style="color:red;">*</span></label>
                    <a-form-item name="fileList" :rules="[{ required: true, message: 'Este campo es obligatorio' }]">
                        <a-upload :file-list="form.fileList" :before-upload="beforeUpload" @remove="handleRemove">
                            <a-button>
                                <upload-outlined></upload-outlined>
                                Seleccionar archivo PDF
                            </a-button>
                        </a-upload>
                        <div class="mt-2" v-if="pdfUrl">
                            <iframe :src="pdfUrl" width="100%" height="300px"></iframe>
                        </div>
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <div class="flex justify-end">
                <a-button
                    type="primary"
                    :disabled="!pdfUrl"
                    :loading="loading"
                    style="margin-top: 16px"
                    @click="save"
                >
                    {{ loading ? 'Subiendo...' : 'Guardar datos' }}
                </a-button>
            </div>
        </template>
    </a-modal>


    <a-modal v-model:visible="modalPDF" title="Registro de título" style="min-width: 1000px;">
        <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <div class="mt-2" v-if="pdfItem">
                    <iframe :src="baseUrl+'/'+pdfItem" width="100%" height="500px"></iframe>
                </div>
            </a-col>
        </a-row>
        <template #footer>
            <div class="flex justify-end">
                <a-button @click="modalPDF == false">
                    Aceptar
                </a-button>
            </div>
            </template>
    </a-modal>
</div>
</template>
    
<script setup>
import { ref, reactive } from 'vue';
import { message, Upload, Button } from 'ant-design-vue';
import axios from 'axios';
const baseUrl = window.location.origin;
import { format, parse } from 'date-fns';
import dayjs from 'dayjs';
import 'dayjs/locale/es';
dayjs.locale('es');

const props = defineProps(['id_proceso','dni']);

const abrirModal = () => { modaltitulo.value = true; }

const abrirEditar = (item) => {
    form.id = item.id;
    form.observacion=  item.observacion;
    pdfUrl.value = baseUrl+'/'+item.url;
    form.tipo = item.id_tipo;
    modaltitulo.value = true;
}

const modaltitulo = ref(false);
const titulos = ref([]);
const tipos = ref([
    { value:1, label:"CERTIFICADO BLANCO" },
    { value:2, label:"CERTIFICADO AMARILLO" }
]);
const modalPDF = ref(false);
const pdfItem = ref(null);

const abriPDf = (pdf) => {
    pdfItem.value = pdf;
    modalPDF.value = true;
}

const formDatos = ref();
const form = reactive({  
    id: null,
    observacion: "",
    tipo: null,
    fileList: []
});

const selecionarTipo = value => {
    console.log(`selected ${value}`);
};

const eliminarTitulo = async (id) => {
    let res = await axios.get("/eliminar-titulo/"+id)
    if (res.data.estado == true) {
        getTitulos()
    } else {
        console.log("Ocurrió un error");
    }
};

const loading = ref(false);
const pdfUrl = ref(null);
const uploadProgress = ref(0);

const beforeUpload = (file) => {
    if (file.type === 'application/pdf') {
        const reader = new FileReader();
        reader.onload = (e) => {
            pdfUrl.value = e.target.result;
        };
        reader.readAsDataURL(file);
        form.fileList = [file];
    } else {
        message.error('¡Solo puedes cargar archivos PDF!');
    }
    return false;
};

const getTitulos = async () => {
    const response = await axios.get('/get-titulos-usuario');
    if (response.data.estado == true){
        titulos.value = response.data.datos;
    }else{
        console.log("No se encontraron datos");
    }
}

const handleRemove = () => {
    pdfUrl.value = null;
    form.fileList = [];
};

const save = async () => {

    console.log("PROCESO ID ", props.id_proceso);
    const values = await formDatos.value.validateFields();
    const formData = new FormData();
    if(form.fileList[0]){ formData.append('file', form.fileList[0]); }
    if(form.id != null ){ formData.append('id', form.id)};
    formData.append('id_proceso', props.id_proceso);    
    formData.append('dni', props.dni);    
    formData.append('observacion', form.observacion);
    formData.append('tipo', form.tipo);

    try {
        loading.value = true;
        uploadProgress.value = 0;

        const response = await axios.post("/save-certificado", formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                Authorization: 'Bearer your-auth-token',
            },
            onUploadProgress: progressEvent => {
                uploadProgress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total);
            }
        });
        modaltitulo.value = false;
        loading.value = false;
        form.value = false;
        message.success('¡Archivo PDF cargado exitosamente!');
        await getTitulos();
    } catch (error) {
        loading.value = false;
        message.error('Error al cargar el archivo.');
        console.error('File upload failed:', error);
    }
};

// getTitulos();
// getTipos()

</script>