<template>
<Head title="Puntajes Unap" />

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="background: #f2f2f200; width: 100dvw; height: 100vh; display: flex; justify-content: center; align-items: center;">
  <div style="width: 330px;">


  <a-card :loading="loading" title="Ver Puntaje" style="width: 100%; height: 100%;">

    <a-label>Dni </a-label>
    <a-input v-model:value="dni" placeholder="Dni" />
    <div style="width: 100%; display: flex; justify-content: flex-end;">
      <a-button style="margin-top: 16px" @click="getPuntaje">Ver Puntaje</a-button>
    </div>

    <a-modal v-model:visible="visible" title="MIS PUNTAJES" @ok="handleOk" style="width: 340px; ">
      <div v-if="res != null">
        <div v-for="item in res.data.datos ">
            <Cardpuntje :datos="item"/>
        </div>
      </div>
    </a-modal>

  </a-card>

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

const visible = ref(false);

const handleOk = e => {
  console.log(e);
  visible.value = false;
};

const getPuntaje = async () =>{  
  res.value = await axios.get(`/get-puntajes/`+dni.value);
  visible.value = true;
}

</script>