<template>
<Layout>
<div style="">
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
          <div style="margin-top: 50px;">
            <div v-if="nParticpantes"> 
              <span style="font-size: 1.5rem; font-weight: bold;">
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
            <div><span style="font-weight: bold;">Inscritos</span></div>
            <div class="p-1 pl-2 pr-2" style="background: #6db6e753; border-radius: 50%;">
              <div style="margin-top: -5px;">
                <span style="color: var(--primary-color); font-size: 1.15em;"><team-outlined /></span>
              </div>
            </div>
          </div>
          <div style="margin-top: 50px;">
            <div> 
              <span style="font-size: 1.5rem; font-weight: bold;">
                {{ nInscritos }}
              </span>
            </div>
          </div>
          <div class="flex justify-start">
            <div v-if="uInscritos"> 
              <span style="color: #00af00; font-weight:bold; "> {{ uInscritos.count }} inscritos <span style="color: gray;">el {{ formatearFecha(uInscritos.fecha) }}</span> </span>
            </div>
          </div>

        </div>
      </a-col>


      <a-col :xs="24" :sm="12" :md="8" :lg="8">

        <div class="p-4" style="background: pink; border-radius: 12px;">
          <div class="flex justify-between">
            <div><span style="font-weight: bold;">Pagos</span></div>
            <div class="p-1 pl-2 pr-2" style="background: #6db6e753; border-radius: 50%;">
              <div style="margin-top: -5px;">
                <span style="color: var(--primary-color); font-size: 1.15em;"><team-outlined /></span>
              </div>
            </div>
          </div>
          <div style="margin-top: 50px;">
            <div v-if="nPagos"> 
              <span style="font-size: 1.5rem; font-weight: bold;">
                {{ nPagos }}
              </span>
            </div>
          </div>
          <div class="flex justify-start">
            <div v-if="uPagos">
              <span style="color: #00af00; font-weight:bold; "> {{ uPagos.count }} pagos registrados <span style="color: gray;">el {{ formatearFecha(uPagos.fecha) }}</span> </span>
            </div>
          </div>

        </div>
        
      </a-col>
    </a-row>

    <a-row :gutter="[16, 8]" class="mt-4">
      <a-col :xs="24" :sm="12" :md="12" :lg="16">
        <div class="flex mb-4" style="justify-content: space-between;">
            <div style="background: white; border-radius: 12px; height: 100px; width: 32%; padding:20px;">
              <div><span style="font-weight: bold; font-weight: bold; font-size: 1.2rem;;"> Bio</span></div>
              <div class="flex" style="justify-content: flex-end;"><span style="font-size:2.2rem;">918</span></div>
            </div>
            <div style="background: white; border-radius: 12px; height: 100px; width: 32%; padding:20px;">
              <div><span style="font-weight: bold; font-weight: bold; font-size: 1.2rem;;"> Ing</span></div>
              <div class="flex" style="justify-content: flex-end;"><span style="font-size:2.2rem;">918</span></div>
            </div>
            <div style="background: white; border-radius: 12px; height: 100px; width: 32%; padding:20px;">
              <div><span style="font-weight: bold; font-weight: bold; font-size: 1.2rem;;"> Soc</span></div>
              <div class="flex" style="justify-content: flex-end;"><span style="font-size:2.2rem;">918</span></div>
            </div>
        </div>
        <div class="p-4" style="background: white; border-radius: 12px;" >
          <Reportes2/>
        </div>
      </a-col>

      <a-col :xs="24" :sm="12" :md="12" :lg="8">        
        <div class="p-4" style="background: white; border-radius: 12px;">
          <Reportes/>
        </div>
      </a-col>

    </a-row>


    <a-row :gutter="[16, 8]" class="mt-4">
      <a-col :xs="24" :sm="12" :md="12" :lg="12">
        <div class="p-4" style="background: white; border-radius: 12px;" >
          <div class="flex justify-between">
            <div><span style="font-weight: bold; font-size: 1.1rem;">Participantes</span></div>
            <div class="p-1 pl-2 pr-2" style="background: #6db6e753; border-radius: 50%;">
              <div style="margin-top: -5px;">
                <span style="color: var(--primary-color); font-size: 1.15em;"><team-outlined/></span>
              </div>
            </div>
          </div>
          <div style="margin-top: 50px;">
            <div v-if="nParticpantes"> 
              <span style="font-size: 1.5rem; font-weight: bold;">
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

      <a-col :xs="24" :sm="12" :md="12" :lg="12">        
        <div class="p-4" style="background: white; border-radius: 12px;">
          <div class="flex justify-between">
            <div><span style="font-weight: bold;">Inscritos</span></div>
            <div class="p-1 pl-2 pr-2" style="background: #6db6e753; border-radius: 50%;">
              <div style="margin-top: -5px;">
                <span style="color: var(--primary-color); font-size: 1.15em;"><team-outlined /></span>
              </div>
            </div>
          </div>
          <div style="margin-top: 50px;">
            <div> 
              <span style="font-size: 1.5rem; font-weight: bold;">
                {{ nInscritos }}
              </span>
            </div>
          </div>
          <div class="flex justify-start">
            <div v-if="uInscritos"> 
              <span style="color: #00af00; font-weight:bold; "> {{ uInscritos.count }} inscritos <span style="color: gray;">el {{ formatearFecha(uInscritos.fecha) }}</span> </span>
            </div>
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




const formatearFecha = (fecha) => {
  const fechaParts = fecha.split('-');
  return `${fechaParts[2]}-${fechaParts[1]}-${fechaParts[0]}`;
};
getNroParticipantes()
getNroPagos()
getNroInscritos()

</script>