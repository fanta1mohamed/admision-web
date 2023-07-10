<template>
<AuthenticatedLayout>
    <div class="p-5">
        <a-button @click="downloadFile">Descargar XLSX</a-button>
    </div>

<div style="display: none;">
  {{ datos = participantes }}
</div>

</AuthenticatedLayout>
</template>
    
<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { watch, computed, ref, unref } from 'vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';
import xlsx from 'json-as-xlsx';
const datos = ref([])

const downloadFile = () => {
    let data = [
        {
            sheet: "Participantes",
            columns: [
            { label: "dni", value: 'dni'}, // Custom format
            { label: "Nombre", value:"nombres" }, // Run functions
            { label: "Paterno", value:"primer_apellido" },
            { label: "Materno", value:"segundo_apellido" },
            { label: "Modalidad", value:"modalidad" },
            { label: "Programa", value:"programa" },
            { label: "Nota Ex Vocacional", value:"nota" },
            ],
            content: datos.value
        },
    ]

    let settings = {
        fileName: "Lista", // Name of the resulting spreadsheet
        extraLength: 3, // A bigger number means that columns will be wider
        writeMode: "writeFile", // The available parameters are 'WriteFile' and 'write'. This setting is optional. Useful in such cases https://docs.sheetjs.com/docs/solutions/output#example-remote-file
        writeOptions: {}, // Style options from https://docs.sheetjs.com/docs/api/write-options
        RTL: false, // Display the columns from right-to-left (the default value is false)
    }

    xlsx(data, settings)
}

const participantes = ref("")
const getParticipantes = async () => {
  const res = await axios.get("resultados-vocacional");
  participantes.value = res.data.datos;
}

getParticipantes();

//---------------------------------------------

</script> 
    
<style scope>

.small-checkbox {
  margin-top: -30px;
  font-size: 1.2rem; /* Tamaño del texto del checkbox */
  transform: scale(0.7); /* Reducción del tamaño del checkbox */
}
.ant-modal {
  max-width: 100%;
  top: 0;

  padding-bottom: 0;
  margin: 0;
}
.ant-modal-content {
  display: flex;
  background: #f3f3f3;
  flex-direction: column;
  height: calc(100vh);
}
.ant-modal-body {
  flex: 1;
}    

.circular-checkbox {
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  font-size: 14px;
  border: 1px solid #ccc;
}

.circular-checkbox.checked {
  background-color: #1890ff;
  color: #fff;
  border-color: #1890ff;
}

</style>