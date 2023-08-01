<template>
<Head title="Puntajes Unap"/>

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="background: #f2f2f200; width: 100dvw; height: 100vh; display: flex; justify-content: center; align-items: center;">
  <div style="min-width: 380px;">


  <a-card :loading="loading" style="width: 100%; height: 100%;">

    <a-label>Dni </a-label>
    <a-input v-model:value="dni" placeholder="Dni" />
    <div class="mt-0">
      <!-- <a-label>Codigo de examen vocacional </a-label>
        <a-input v-model:value="dni" placeholder="Dni" /> -->
      <div style="width: 100%; display: flex; justify-content: flex-end;">
        <a-button style="margin-top: 16px" @click="getPuntaje">Ver Puntaje</a-button>
      </div>
    </div>


    <a-modal v-model:visible="visible" title="MIS PUNTAJES" :footer="false">
      <div v-if="res != null" class="scrollable-container">
        <div v-if="varios == true">
          <div v-for="item in res.data.datos ">
              <Cardpuntje :datos="item"/>
          </div>
        </div>
        <div v-if="varios == false">
          <div v-for="(item, index) in res.data.datos">
              <div v-if="index === 0">
                <Cardpuntje :datos="item"/>
              </div>            
          </div>
        </div>
        <div v-if="varios == false" class="flex justify-end"> 
          <a-button @click="varios = true" >ver más</a-button>
        </div>
        <div v-else class="flex justify-end"> 
          <a-button @click="varios = false" >ver menos</a-button>
        </div>

      </div>
    </a-modal>

  </a-card>


  <!-- <div v-if="res != null" style="min-width: 380px;">
    <div v-for="item in res.data.datos ">
        <Cardpuntje :datos="item"/>
    </div>
  </div> -->

  </div>  
</div>
 

</template>
 
<script setup>
import Cardpuntje from './cardpuntaje.vue';
import { Head } from '@inertiajs/vue3';
import { computed, ref, unref } from 'vue';
import axios from 'axios';

const res = ref(null);
const dni = ref(null);
const varios = ref(false);

const visible = ref(false);

const handleOk = e => {
  console.log(e);
  visible.value = false;
};

const getPuntaje = async () =>{  
  res.value = await axios.get(`/get-puntajes/`+dni.value);
  visible.value = true;
  dni.value = "";
}

</script>