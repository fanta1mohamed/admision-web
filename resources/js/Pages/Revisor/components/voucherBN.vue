<template>
<div>
    <a-row :gutter="16" class="mb-3">
        <!-- <a-col :span="24" v-for="item in comprobantes" :key="item.id"> -->
        <a-table :dataSource="comprobantes" :columns="colVouchers" :pagination="false" size="small" style="width: 100%;" > 
            <template #bodyCell="{ column, index, record }">
                <template v-if="column.dataIndex === 'proceso'">
                    <a-tag v-if="record.id_modalidad_proceso === 3" color="pink">{{ record.proceso }}</a-tag>
                    <a-tag v-if="record.id_modalidad_proceso === 2" color="orange">{{ record.proceso }}</a-tag>
                    <a-tag v-if="record.id_modalidad_proceso === 1" color="blue">{{ record.proceso }}</a-tag>
                </template>
                <template v-if="column.dataIndex === 'opcion'">
                    <div v-if="record.estado === 2"> 
                        <a-button @click="verificar(record)" style="background: #133466; border:none; color: white;"> seleccionar </a-button> 
                    </div>
                    <div v-if="record.estado === 1"> 
                        <a-button @click="verificar(record)" style="background: crimson; border-radius: 5px; border:none; color:white;"> seleccionado </a-button> 
                    </div>
                </template>
            </template> 
        </a-table> 

        <!-- <a-card @click="verificar(item.id, item.verificado)" :class="item.verificado === 1? 'verde':'rojo'" style="border-radius: 12px; cursor:pointer;">
                <div class="flex justify-between mb-6">
                    <div>
                        <div class="flex justify-center align-center" style="width: 40px; height:40px; border: 1px solid #d9d9d9;">
                            <div style="margin-top: -3px;">
                                <span style="font-weight: bold; font-size: 1.9rem;">{{ item.codigo.slice(-2) }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div>{{ item.fecha.split("-").reverse().join("-") }}</div> 
                        <div class="flex justify-end">
                            <span style="font-weight: bold; font-size: 1.2rem;"> {{ item.nro_operacion }} </span>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex justify-between">
                        <div>
                            <div style="margin-bottom: -4px;">
                                <span style="font-weight: bold; font-size: .9rem;"> {{ item.ndoc_postulante }} </span>
                            </div>
                            <span style="font-size: .9rem;"> {{ item.nombres }} {{ item.primer_apellido }} {{ item.segundo_apellido }} </span>
                        </div>

                        <div>
                        <span style="font-size: 1.5rem; font-weight: bold;"> S/ {{ item.monto.toFixed(2) }} </span>
                    </div>
                    </div>
                </div>
            </a-card> -->
        <!-- </a-col> -->
    </a-row>
</div>
</template>

<script setup>
import { ref, defineProps, watch } from 'vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';


const comprobantes = ref([]);
const props = defineProps(['dni', 'proceso']);

const getComprobantes = async () => {
  let res = await axios.post('get-pagos-banco', { dni: props.dni });
  comprobantes.value = res.data.datos;
};

const verificar = async (comp) => {
  let res = await axios.post('verificar-comprobante-proceso', { comp });
  notificacion(res.data.type, res.data.titulo, res.data.mensaje);
  getComprobantes();
};

watch(() => {
  getComprobantes();
});

const colVouchers =  [
  { title: 'N° Operación', dataIndex: 'secuencia'},
  { title: 'Nombres', dataIndex: 'nom_cli'},
  { title: 'Fecha', dataIndex: 'fch_pag'},
  { title: 'Concepto', dataIndex: 'concepto' },
  { title: 'Monto', dataIndex: 'imp_pag' },
  { title: 'Examen', dataIndex: 'proceso', align:'center' },
  { title: '', dataIndex: 'opcion', align:'center' },
];

const notificacion = (type, titulo, mensaje) => { notification[type]({ message: titulo, description: mensaje, }); };

getComprobantes();
</script>


<style scoped>
.rojo{ color: #525252; background: white;}
.verde { background: #e3e3e3;}
</style>
