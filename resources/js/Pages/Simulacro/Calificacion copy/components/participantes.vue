<template>
<Head title="Inicio"/>    

<div class="p-4" style="width:100%; background:white; border-radius:8px;">
    <form @submit.prevent="submit">
        <div class="flex justify-between">
            <label for="fileInput" class="" style="padding-top: 14px; border: solid 2px var(--primary-color); border-radius: 6px; cursor: pointer; padding: 8px 14px; ">
                Seleccionar
            </label>
            <input id="fileInput" type="file" @change="handleFileUpload" style="display:none;" />

         <div style="display:flex; justify-content:flex-end;">
            <a-button class="mr-2" style="border-radius: 5px; height:38px; border: solid 1px var(--primary-color); color:var(--primary-color); " @click="descargar">Plantilla</a-button>              
            <a-button style="height:38px; border-radius: 5px; border:none; color:white; background: var(--primary-color)" @click="subirResultados"> Subir</a-button>              
        </div>
        </div>
    </form> 

    <div style="display:flex;">
        <a-progress :percent="progress" :status="estado"/>
    </div>

    <div>
        <a-table :dataSource="excelData" :columns="columns" size="small" :pagination="false">
            <template #bodyCell="{ column, index, record }">
                <template v-if="column.dataIndex === 'nro'">
                    <span>{{ index + 1 }}</span>
                </template>
            </template>     
        </a-table> 
    </div>


    <div class="flex justify-end mt-2 mb-4" style="margin-right: -20px;">
      <a-input v-model:value="buscar" style="max-width: 260px; border-radius: 6px; height: 32px;" placeholder="Buscar">
          <template #prefix>
              <span style="color: #0000009d; margin-top: -6px;"><SearchOutlined/></span>
          </template>
      </a-input>
    </div>
    <div style="margin-right: -20px;">
        <a-table :dataSource="participantes" :columns="columnsParticipante" size="small" :pagination="false">
            <template #bodyCell="{ column, index, record }">
                <template v-if="column.dataIndex === 'nro'">
                    <span>{{ index + 1 }}</span>
                </template>
                <template v-if="column.dataIndex === 'id_ide'">
                    <div style="scale: 0.8rem;">
                        <a-tag v-if="record.id_ide == 0" color="red" class="small-text" style="border-radius: 14px;" > sin ide </a-tag>
                        <a-tag v-if="record.area == null" color="purple"> sin area </a-tag>
                        <span v-else> </span>
                    </div>
                </template>
            </template>     
        </a-table> 
    </div>
</div>
</template>
    
<script setup>
import { Head } from '@inertiajs/vue3';
import Layout from '@/Layouts/LayoutCalificador.vue'
import { defineProps,computed, ref, watch } from 'vue';
import * as XLSX from 'xlsx';
import axios from 'axios';

const props = defineProps(['proceso']);
const excelData = ref([]);
const progress = ref(0);
const estado = ref("");
const buscar = ref("");

const subirResultados = async () => {
    progress.value = 0; 
    try {
        const response = await axios.post('subir-participantes-simulacro', { data: excelData.value, proceso: props.proceso }, {
            onUploadProgress: (progressEvent) => {
            if (progressEvent.lengthComputable) {
                progress.value = Math.round((progressEvent.loaded / progressEvent.total) * 100);
            }
            },
        });
        console.log('Respuesta del servidor:', response.data);
        progress.value = 100; 
        getParticipantes();
    } catch (error) {
        progress.value = 70;
        estado.value = "exception"
        console.error('Error:', error);
    }
};


const handleFileUpload = (event) => {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = (e) => {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const firstSheetName = workbook.SheetNames[0];
        const worksheet = workbook.Sheets[firstSheetName];
        const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

        const headers = jsonData[0]; 

        const arrayOfObjects = jsonData.slice(1).map((row) => {
        const obj = {};
        headers.forEach((header, index) => {
            obj[header] = row[index];
        });
        return obj;
        });

        excelData.value = arrayOfObjects;
    };

    reader.readAsArrayBuffer(file);
}
    
const participantes = ref([])

const getParticipantes = async () => {
    axios.post("/get-participantes-externo",{"term": buscar.value, "proceso": props.proceso})
    .then((response) => {
        participantes.value = response.data.datos.data;
    })
    .catch((error) => {
        if (error.response) {
            console.error('Error de servidor:', error.response.data);
        } else if (error.request) {
            console.error('Error de red:', error.request);
            } else { console.error('Error de configuración:', error.message); }
  });
}

let timeoutId;

watch(buscar, ( newValue, oldValue ) => { 
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
        getParticipantes() 
    }, 300);    
})

getParticipantes();

const columns = ref([
    {
        title: 'N°',
        dataIndex: 'nro',
        align:'center'
    },
    {
        title: 'DNI',
        dataIndex: 'dni'
    },
    {
        title: 'Ap. Paterno',
        dataIndex: 'paterno'
    },
    {
        title: 'Ap. Materno',
        dataIndex: 'materno'
    },
    {
        title: 'Mombres',
        dataIndex: 'nombres'
    },
    {
        title: 'Colegio',
        dataIndex: 'colegio'
    },
    {
        title: 'distito',
        dataIndex: 'distrito'
    },    
    {
        title: 'Area',
        dataIndex: 'area'
    },    
]);

const columnsParticipante = ref([
    {
        title: 'N°',
        dataIndex: 'nro',
        align:'center'
    },
    {
        title: 'DNI',
        dataIndex: 'dni'
    },
    {
        title: 'Ap. Paterno',
        dataIndex: 'paterno'
    },
    {
        title: 'Ap. Materno',
        dataIndex: 'materno'
    },
    {
        title: 'Mombres',
        dataIndex: 'nombres'
    },
    {
        title: 'Colegio',
        dataIndex: 'colegio'
    },
    {
        title: 'distito',
        dataIndex: 'distrito'
    },    
    {
        title: 'Area',
        dataIndex: 'area'
    },
    {
        title: 'Observaciones',
        dataIndex: 'id_ide',
        align:'center'
    },    
]);


</script>

<style scoped>

input[type=file]::file-selector-button {
    margin-right: 10px;
    border: none;
    background: var(--primary-color);
    padding: 9px 20px;
    border-radius: 5px;
    color: #fff;
    cursor: pointer;
    transition: background .2s ease-in-out;
}

input[type=file]::file-selector-button:hover { background: #143253; }

.custom-file-input span {
  display: inline-block;
}
</style>