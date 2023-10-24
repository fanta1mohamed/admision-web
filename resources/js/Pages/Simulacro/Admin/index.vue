<template>
<Layout>
<div style="">
    <a-row :gutter="[16, 8]">
      <a-col :xs="24" :sm="12" :md="6" :lg="6">
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

      <!-- <a-col :xs="24" :sm="12" :md="6" :lg="6">        
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
                {{ inscritos }}
              </span>
            </div>
          </div>
          <div class="flex justify-start">
            <div> 
              <span style="color: #00af00; font-weight:bold; "> {{ ultimoinscrito.count }} inscritos <span style="color: gray;">el {{ ultimoinscrito.date }}</span> </span>
            </div>
          </div>

        </div>
      </a-col>

      <a-col :xs="24" :sm="12" :md="6" :lg="6">

        <div class="p-4" style="background: white; border-radius: 12px;">
          <div class="flex justify-between">
            <div><span style="font-weight: bold;">Preinscritos</span></div>
            <div class="p-1 pl-2 pr-2" style="background: #6db6e753; border-radius: 50%;">
              <div style="margin-top: -5px;">
                <span style="color: var(--primary-color); font-size: 1.15em;"><team-outlined /></span>
              </div>
            </div>
          </div>
          <div style="margin-top: 50px;">
            <div v-if="preinscritos"> 
              <span style="font-size: 1.5rem; font-weight: bold;">
                {{ preinscritos }}
              </span>
            </div>
          </div>
          <div class="flex justify-start">
            <div>
              <span style="color: #00af00; font-weight:bold; "> {{ ultimopreinscrito.count }} preinscritos <span style="color: gray;">el {{ ultimopreinscrito.date }}</span> </span>
            </div>
          </div>

        </div>
        
      </a-col>

      <a-col :xs="24" :sm="12" :md="6" :lg="6">
        <div class="p-4" style="background: white; border-radius: 12px;">
          <div class="flex justify-between">
            <div><span style="font-weight: bold;">Preinscritos</span></div>
            <div class="p-1 pl-2 pr-2" style="background: #6db6e753; border-radius: 50%;">
              <div style="margin-top: -5px;">
                <span style="color: var(--primary-color); font-size: 1.15em;"><team-outlined /></span>
              </div>
            </div>
          </div>
          <div style="margin-top: 50px;">
            <div v-if="preinscritos"> 
              <span style="font-size: 1.5rem; font-weight: bold;">
                {{ preinscritos }}
              </span>
            </div>
          </div>
          <div class="flex justify-start">
            <div>
              <span style="color: #00af00; font-weight:bold; "> {{ ultimopreinscrito.count }} preinscritos <span style="color: gray;">el {{ ultimopreinscrito.date }}</span> </span>
            </div>
          </div>

        </div>
      </a-col>  -->
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
import axios from 'axios';

const nParticpantes = ref(0);
const uDiaParticipantes = ref(0);



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



const formatearFecha = (fecha) => {
  const fechaParts = fecha.split('-');
  return `${fechaParts[2]}-${fechaParts[1]}-${fechaParts[0]}`;
};
getNroParticipantes()

</script>