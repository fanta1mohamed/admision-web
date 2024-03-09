<template>
<AuthenticatedLayout>
<div class="p-4" style="background: white;">
  <div class="flex justify-end">
        <div> 
            <!-- <a-button type="primary" @click="modal = true" style="border-radius: 6px; background: #476175; border: none;">Nuevo</a-button> -->
        </div>
        <div class="flex justify-between" style="position: relative;" >
            <a-input type="text" placeholder="Buscar" v-model:value="buscar" style="max-width: 300px; padding-left: 30px; border-radius: 6px;"/>
            <div class="mr-2" style="position: absolute; left: 8px; top: 3px; "><search-outlined /></div>
        </div>
  </div>


  <div class="mt-3">
    <a-table 
        :columns="columnsVocacional" 
        :data-source="participantes"
        :pagination="false"
        size="small"
        > 
        <template #bodyCell="{ column, index, record}">
             <template v-if="column.dataIndex === 'nombres'">
                {{ record.nombres }}
            </template>

            <template v-if="column.dataIndex === 'acciones'">
                <a-button type="primary" @click="abrirEditar(record)" size="small">
                <template #icon><form-outlined/></template>
                </a-button>
                <a-divider type="vertical" />

                <a-popconfirm
                    v-if="modalidades.length"
                    title="¿Estas seguro de eliminar?"
                    @confirm="eliminar(modalidades[index])"
                    >
                    <a-button type="danger" shape="" size="small">
                        <template #icon><delete-outlined/></template>
                    </a-button>
                </a-popconfirm>
            </template>
        </template>
    </a-table> 
    <a-pagination v-model:current="pagina" :total="totalRegistros" show-less-items />
  </div>

</div>
</AuthenticatedLayout>
</template>
    
<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { watch, computed, ref, unref } from 'vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';

const participantes = ref("")

const getParticipantes =  async ( ) => {
    let res = await axios.post(
    "get-postulantes-admin?page="+pagina.value ,
    { term: buscar.value }
    );
    participantes.value = res.data.datos.data;
    totalRegistros.value = res.data.datos.total;
}

const notificacion = (type, titulo, mensaje) => { notification[type]({ message: titulo, description: mensaje, }); };


const columnsVocacional = [
  { title: 'DNI', dataIndex: 'nro_doc' },
  { title: 'Nombre', dataIndex: 'nombres'},
  { title: 'Respuestas', dataIndex: 'cant' },
  { title: 'Acciones', dataIndex: 'acciones'},
];

getParticipantes();

</script> 
    
<style scope>

</style>