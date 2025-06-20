<template>
    <div>
        <a-button @click="visible = true">Calificar</a-button>

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

            <div>{{ props.proceso }}</div>

            <div class="flex justify-end">
                <div class="mr-2">
                    <a-button style="margin-left: 6px; border-radius: 4px;">Cancelar</a-button>
                </div>
                <div>
                    <a-button type="primary" style="background: #476175; border:none; border-radius: 4px;">Calificar</a-button>
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


const visible = ref(false);     
getPonderaciones();

</script> 