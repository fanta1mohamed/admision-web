<template>
<Head title="Inicio"/>    
<Layout>
<div class="mb-4" style="width: 100%;">
    <a-row :gutter="[16, 8]">
      <a-col :xs="24" :sm="12" :md="8" :lg="8">
        <div class="p-4" style="background: white; border-radius: 12px;" >
          <div class="flex justify-between">
            <div><span style="font-weight: bold; font-size: 1.1rem;">Participantes</span></div>
            <div class="p-1 pl-2 pr-2" style="background: #6db6e753; border-radius: 50%;">
              <div style="margin-top: -5px;">
                <span style="color: var(--primary-color); font-size: 1.15em;"><team-outlined/></span>
              </div>
            </div>
          </div>
          <div style="margin-top: 20px;">
            <div v-if="nParticpantes"> 
              <span style="font-size: 2.5rem; font-weight: bold;">
                {{ nParticpantes }}
              </span>
            </div>
          </div>
          <div class="flex justify-start">
            <div v-if="uDiaParticipantes">
              <span style="color: #00af00; font-weight:bold; "> {{ uDiaParticipantes.count }} Particpantes <span style="color: gray;">el {{ formatearFecha(uDiaParticipantes.fecha) }} </span> </span>
              <!-- <span style="color: #00af00;"> preinscritos  {{ (ultimopreinscrito.count / preinscritos ).toFixed(2) }}% <span style="color: gray;">el {{ ultimopreinscrito.date }}</span> </span> -->
            </div>
          </div>

        </div>
      </a-col>

      <a-col :xs="24" :sm="12" :md="8" :lg="8">        
        <div class="p-4" style="background: white; border-radius: 12px;">
          <div class="flex justify-between">
            <div><span style="font-weight: bold; font-size: 1.1rem;">Inscritos</span></div>
            <div class="p-1 pl-2 pr-2" style="background: #6db6e753; border-radius: 50%;">
              <div style="margin-top: -5px;">
                <span style="color: var(--primary-color); font-size: 1.15em;"><team-outlined /></span>
              </div>
            </div>
          </div>
          <div style="margin-top: 20px;">
            <div> 
              <span style="font-size: 2.5rem; font-weight: bold;">
                {{ nInscritos }}
              </span>
            </div>
          </div>
          <div class="flex justify-start">
            <div v-if="uInscritos"> 
              <span style="color: #00af00;  font-weight:bold; "> {{ uInscritos.count }} inscritos <span style="color: gray;">el {{ formatearFecha(uInscritos.fecha) }}</span> </span>
            </div>
          </div>

        </div>
      </a-col>


      <a-col :xs="24" :sm="12" :md="8" :lg="8">

        <div class="p-4" style="background: white; border-radius: 12px;">
          <div class="flex justify-between">
            <div><span style="font-weight: bold; font-size: 1.1rem;">Pagos</span></div>
            <div class="p-1 pl-2 pr-2" style="background: #6db6e753; border-radius: 50%;">
              <div style="margin-top: -5px;">
                <span style="color: var(--primary-color); font-size: 1.15em;"><team-outlined /></span>
              </div>
            </div>
          </div>
          <div style="margin-top: 20px;">
            <div v-if="nPagos"> 
              <span style="font-size: 2.5rem; font-weight: bold;">
                {{ nPagos }}
              </span>
            </div>
          </div>
          <div class="flex justify-start">
            <div v-if="uPagos">
              <span style="color: #00af00; font-weight:bold; "> {{ uPagos.count }} pagos reg. <span style="color: gray;">el {{ formatearFecha(uPagos.fecha) }}</span> </span>
            </div>
          </div>

        </div>
        
      </a-col>
    </a-row>

    <a-row :gutter="[16, 8]" class="mt-4">
      <a-col :xs="24" :sm="12" :md="12" :lg="16">
        <div class="flex mb-4" style="justify-content: space-between;">
            <div v-for="(item, index) in areas" :key="index"  :style="{'background':areasColores[index] }" style="border-radius: 12px; height: 100px; width: 32%; padding:20px; padding-left: 10px; padding-top: 15px; color: white; text-align: left;">
              <div style="text-align: left;"><span style="font-weight: bold; font-weight: bold; font-size: 1rem;;"> {{  item.areas }}</span></div>
              <div class="flex" style="justify-content: flex-end;"><span style="font-size:2.5rem;"> {{ item.cant }} </span></div>
            </div>
        </div>
        <div class="p-4" style="background: white; border-radius: 12px;" >
          <Reportes2/>
        </div>
      </a-col>

      <a-col :xs="24" :sm="12" :md="12" :lg="8" style="height: 100%;">        
        <div class="p-4" style="background: white; border-radius: 12px; height: calc(200% - 100px);">
          <div style="background: yellow;" class="p-3">
            <Reportes/>
          </div>

        </div>
      </a-col>

    </a-row>


    <a-row :gutter="[16, 8]" class="mt-4">
      <a-col :xs="24" :sm="24" :md="24" :lg="24">
        <div class="p-4" style="background: white; border-radius: 12px;" >
          <div class="flex justify-between">
            <div><span style="font-weight: bold; font-size: 1.1rem;">Participantes</span></div>
            <div class="p-1 pl-2 pr-2" style="background: #6db6e753; border-radius: 50%;">
              <div style="margin-top: -5px;">
                <span style="color: var(--primary-color); font-size: 1.15em;"><team-outlined/></span>
              </div>
            </div>
          </div>
          <div style="margin-top: 10px;">
            <a-table :columns="columns" :data-source="participantes" :pagination="false" size="small">
            </a-table>
          </div>
        </div>
      </a-col>
    </a-row>
</div>

</Layout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import Layout from '@/Layouts/LayoutSimulacro.vue'
import { watch, computed, ref, unref } from 'vue';
import { TeamOutlined, FormOutlined, DeleteOutlined, SearchOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import Reportes from './componentes/reportes.vue';
import Reportes2 from './componentes/reportes2.vue';
import axios from 'axios';

const nParticpantes = ref(0);
const uDiaParticipantes = ref(0);
const nInscritos = ref(0);
const uInscritos = ref(0);
const nPagos = ref(0);
const uPagos = ref(0);



const getNroParticipantes = async () => {
  try {
    const response = await axios.get("/simulacro/get-nro-participantes");
    nParticpantes.value = response.data.particpantes;
    uDiaParticipantes.value = response.data.ultimoparticipante;    
  } catch (error) {
    console.error("Error al obtener datos de los inscritos:", error);
    return null;
  }
};

const getNroInscritos = async () => {
  try {
    const response = await axios.get("/simulacro/get-nro-inscritos");
    nInscritos.value = response.data.inscritos;
    uInscritos.value = response.data.ultimoinscrito;    
  } catch (error) {
    console.error("Error al obtener datos de los inscritos:", error);
    return null;
  }
};


const getNroPagos = async () => {
  try {
    const response = await axios.get("/simulacro/get-nro-pagos");
    nPagos.value = response.data.n_pagos;
    uPagos.value = response.data.ultimoregistro;    
  } catch (error) {
    console.error("Error al obtener datos de los inscritos:", error);
    return null;
  }
};

const areas = ref([]);
const participantes = ref([]);

const getAreas = () => { 
  axios.get('/simulacro/get-inscritos-areas-reporte')
  .then((response) => { 
      areas.value = response.data.datos;
  })
  .catch((error) => {  console.error('Error al realizar la solicitud:', error); });
}

const getParticipantes = () => { 
  axios.get('/simulacro/postulantes-por-programas')
  .then((response) => { 
      participantes.value = response.data.datos;
  })
  .catch((error) => {  console.error('Error al realizar la solicitud:', error); });
}

const areasColores = ref(['#82DED7','#FFB3E4','#c09cd8']); 

getParticipantes();

const formatearFecha = (fecha) => {
  const fechaParts = fecha.split('-');
  return `${fechaParts[2]}-${fechaParts[1]}-${fechaParts[0]}`;
};

const columns = [
  {
    title: 'Cant',
    dataIndex: 'cantidad'
  },
  {
    title: 'Programa',
    dataIndex: 'programa'
  },
  {
    title: 'Areas',
    dataIndex: 'area'
  }
];





getNroParticipantes()
getNroPagos()
getNroInscritos()
getAreas();

</script>
