<template>
    <div>
        <div class="mt-0 mb-4">
            <div class="class flex justify-between">
                <div class="mt-0 mb-4">
                    <a-button @click="visible = true">Calificar</a-button>
                </div>   
                <div v-if="resultados != []" class="mt-0 mb-4">
                    <a-button style="width: 140px; background:green; border:none; color:white;" @click="descargar()">Descargar</a-button>
                </div>  
                <div v-else class="mt-0 mb-4">
                    <a-button style="width: 140px; background:green; border:none; color:white;" @click="visible = true" disabled>Descargar</a-button>
                </div>  
            </div>  
        </div>   

        

        <a-table :dataSource="resultados" :columns="columns" size="small" :pagination="false">
            <template #bodyCell="{ column, index, record }">
                <template v-if="column.dataIndex === 'nro'">
                    <span>{{ index + 1 }}</span>
                </template>
                <template v-if="column.dataIndex === 'puntaje'">
                    <div v-if="record.puntaje !== null && record.puntaje !== undefined">
                        <span>{{ formatPuntaje(record.puntaje) }}</span>
                    </div>

                </template>
            </template>   
            
        </a-table> 
        

        <a-modal v-model:visible="visible" :footer="false">

            <div class="mt-4">
                <label>Area</label>
                <a-select ref="select" v-model:value="area" style="width: 100%">
                    <a-select-option :value="1">BIOMEDICAS</a-select-option>
                    <a-select-option :value="2">INGENIERIAS</a-select-option>
                    <a-select-option :value="3">SOCIALES</a-select-option>
                </a-select>
            </div>

            <div class="mt-3">
                <label>Ponderacion</label>
                <div>
                    <a-auto-complete
                        v-model:value="ponderacion"                
                        :options="ponderaciones"
                        @select="onSelectPonderacion"
                        style="width:100%;"
                        >
                        <a-input 
                            placeholder="Ponderación ..."
                            v-model:value="buscarPonderacion"
                        >
                        <template #suffix>
                            <DownOutlined/>
                        </template>
                        </a-input>
                    </a-auto-complete>
                </div>
            
            </div>

            <!-- <div>{{ props.proceso }}</div> -->

            <div class="flex justify-end">
                <div class="mr-2">
                    <a-button style="margin-left: 6px; border-radius: 4px;">Cancelar s</a-button>
                </div>
                <div>
                    <a-button type="primary" @click="califar()" style="background: #476175; border:none; border-radius: 4px;">Calificddar</a-button>
                </div>
            </div>
        </a-modal>

    </div>
</template>
        
<script setup>
import { watch, computed, defineProps, ref, unref } from 'vue';
import { DownOutlined, SearchOutlined, EyeOutlined, FormOutlined, DeleteOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';

const props = defineProps(['proceso']);
const area = ref(1);
const ponderacion = ref(null);
const buscarPonderacion = ref("");
const resultados = ref([]);

const onSelectPonderacion = (value, option) => { ponderacion.value = option; };
const notificacion = (type, titulo, mensaje) => { notification[type]({ message: titulo, description: mensaje, }); };

watch(buscarPonderacion, ( newValue, oldValue ) => {  getPonderaciones(); })

const ponderaciones = ref([]);
const totalRegistros = ref(0);
const pagina = ref(1);
const paginasize = ref(10);
const buscar = ref("");


const getPonderaciones =  async () => {
    let res = await axios.post("get-ponderaciones-select?page=" + pagina.value, { term: buscarPonderacion.value, paginasize: paginasize.value } );
    ponderaciones.value = res.data.datos.data;
    totalRegistros.value = res.data.datos.total;
}

const califar =  async () => {
    let res = await axios.post("/calificar-examen", { id_area: area.value, id_simulacro: props.proceso, id_ponderacion: ponderacion.value.key  } );
    visible.value = false;
    getPuntajes();
}

const getPuntajes =  async () => {
    let res = await axios.post("/get-puntajes-examen", { id_simulacro: props.proceso } );
    resultados.value = res.data.datos;
}


getPuntajes();
const visible = ref(false);     
getPonderaciones();

const formatPuntaje = (puntaje) =>  {
    return Number(puntaje).toFixed(2);
}


const columns = ref([
    { title: 'N°', dataIndex: 'nro', align:'center'},
    { title: 'DNI',dataIndex: 'dni'},
    { title: 'Ap. Paterno', dataIndex: 'paterno'},
    { title: 'Ap. Materno', dataIndex: 'materno'},
    { title: 'Nombres', dataIndex: 'nombres' },
    { title: 'Puntaje', dataIndex: 'puntaje', align:'center'},    
]);


const descargar =  async () => {
    axios.get('/get-pdf-resultados/'+props.proceso)
    .then(response => {
        const archivos = response.data.archivos;
        archivos.forEach(archivo => {
            const link = document.createElement('a');
            link.href = archivo;
            link.download = archivo.split('/').pop();
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    })
    .catch(error => {
        console.error('Error al generar los PDFs:', error);
    });
};
</script> 