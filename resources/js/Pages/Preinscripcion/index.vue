<template>
<Head title="Procesos"/>
<AuthenticatedLayout>
  <div class="p-4" style="">
    <div>
      <a-steps v-model:current="current" direction="vertical">
        <a-step title="Step 1"> 
          <div>
            sdafasdf
          </div>
        </a-step>
        <a-step title="Step 2" description="This is a description." />
        <a-step title="Step 3" description="This is a description." />
      </a-steps>
    </div>
    <div class="flex">
      <a-button v-if="current > 0" @click="prev()" type="primary">Anterior</a-button>
      <a-button v-if="current < 2" @click="next()" >Siguiente</a-button>    
    </div>
  </div>
</AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { watch, computed, ref, unref } from 'vue';
import { UserOutlined, SolutionOutlined, LoadingOutlined, SmileOutlined, } from '@ant-design/icons-vue';

const current = ref(0);
const next = () => { current.value++; };
const prev = () => { current.value--; };



  // watch(buscar, ( newValue, oldValue ) => {
  //   getProcesos();
  // })

    
</script>