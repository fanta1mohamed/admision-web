<template>
<Head title="Inicio"/>    
<Layout>
    <div class="p-4" style="width:100%; max-width:1300px; background:white; border-radius:8px;">
        <form @submit.prevent="submit">
            <div class="flex justify-between">
            <!-- <input type="file" @change="onChange"/> -->
            <input type="file" @change="handleFileUpload" />
            <div style="display:flex; justify-content:flex-end;">
                <a-button class="mr-2" style="border-radius: 5px; height:38px; border: solid 1px var(--primary-color); color:var(--primary-color); " @click="subirResultados">Ejemplo</a-button>              
                <a-button style="height:38px; border-radius: 5px; border:none; color:white; background: var(--primary-color)" @click="subirResultados"> Subir resultado</a-button>              
            </div>
            </div>
        </form> 

        <div style="display:flex;   ">
            <a-progress :percent="progress" :status="estado"/>
        </div>

        <div>
            <a-table :dataSource="excelData" :columns="columns" />
        </div>
    </div>
</Layout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import Layout from '@/Layouts/LayoutCalificador.vue'

import { ref } from 'vue';
import * as XLSX from 'xlsx';
import axios from 'axios';

const excelData = ref([]);
const progress = ref(0);
const estado = ref("");

const subirResultados = async () => {
  progress.value = 0; 
  
  try {
    const response = await axios.post('subir-excel-simulacro', { data: excelData.value }, {
      onUploadProgress: (progressEvent) => {
        if (progressEvent.lengthComputable) {
          progress.value = Math.round((progressEvent.loaded / progressEvent.total) * 100);
        }
      },
    });
    console.log('Respuesta del servidor:', response.data);
    progress.value = 100; 

  } catch (error) {
    progress.value = 70;
    estado.value = "exception"
    // Manejar errores aquí, por ejemplo:
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
 
const columns = ref([
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
        title: 'Puntaje',
        dataIndex: 'puntaje'
    },
    {
        title: 'Fecha',
        dataIndex: 'fecha'
    },    
    {
        title: 'Puesto Programa',
        dataIndex: 'puesto_programa'
    },    
    {
        title: 'Puesto General',
        dataIndex: 'puesto_general'
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
  
  input[type=file]::file-selector-button:hover {
    background: #143253;
  }
</style>