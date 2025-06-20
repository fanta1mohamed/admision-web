<template>
  <Layout>
    <Head name="Subir Ides"/>
    <div class="p-4" style="background: white; width: 100%; min-height: calc(100vh - 90px); border-radius: 12px;">
      <!-- <div class="flex justify-between">
        <div><a-button type="primary" @click="visible = true" style="background: #476175; border: none; border-radius: 5px;">subir Ides</a-button></div>
      </div> -->

    <div class="mt-4" style="height: 100%; min-height: calc(100vh - 130px); position: relative;">
      <div>
        <a-steps :current="current" >
          <a-step key="uno" status="finish">
            <template #icon>
              <div class="mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-archive"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg>
              </div>
            </template>
            <template #title>
              <span style="color:green">Proceso</span>
            </template>
          </a-step>
          <a-step key="dos" title="segundo" status=""/>
          <a-step key="tres" title="tercero" status=""/>
          <!-- <a-step v-for="item in steps" :key="item.title" :title="item.title" status="error"/> -->
        </a-steps>
      </div>

      <div class="steps-content mt-4" >
        <div v-if="current === 0" style=" display: flex;height: calc(100vh - 210px);  border-radius: 12px; align-items: center; justify-content: center;">
            <a-card class="" style="min-width:320px; border-radius: 12px;">
              <div class="flex pb-4" style="margin-top: -10px; margin-bottom: 0px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="#476175" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                <span class="ml-2" style="font-size: 1rem; font-weight: bold; color: #476175;">Nuevo Proceso</span></div>
              <div>
                <div>
                  <label class="">Nombre Proceso</label>
                </div>
                <div class="mt-2">
                  <a-input v-model:value="value" placeholder="Nombre del proceso" />
                </div>
              </div>
              <div class="mt-5" style="width: 100%;">
                <a-button v-if="current === 0" @click="prev" style="color: white; width: 100%; background: #466175; border-radius: 5px;">Continuar</a-button>
              </div>
            </a-card>
        </div>
        <div v-if="current === 1">
          uno
          {{ steps[current].content }}
        </div>
        <div v-if="current === 2">
          dos
          {{ steps[current].content }}
        </div>

      </div>


      <div class="steps-action flex justify-end" style="position: absolute; bottom: 0px; width: 100%;">
        <div class="flex" style="width: 199%; justify-content: space-between;">
          <a-button v-if="current > 0" @click="prev" style="color: #476175; border: 1px solid #466175; border-radius: 5px; width: 100px;">Anterior</a-button>
          <a-button v-if="current < steps.length - 1"  @click="next" type="primary" style="background: #476175; border: none; border-radius: 5px; width: 100px;">Siguiente</a-button>
          <a-button
            v-if="current == steps.length - 1"
            type="primary"
            style="background: #476175; border: none; border-radius: 5px; width: 100px;"
            @click="message.success('Processing complete!')"
          >
            Terminar
          </a-button>
        </div>
      </div>
    </div>




    <a-modal v-model:visible="visible" title="Subir Ides" @ok="okey" :centered="true" style="max-height: calc(100vh - 100px); overflow-x: scroll; cursor: pointer;">
      <div class="justify-end">
        <a-select
            v-model:value="area">
            <a-select-option :value="1">Biomédicas</a-select-option>
            <a-select-option :value="2">Ingenierías</a-select-option>    
            <a-select-option :value="3">Sociales</a-select-option>    
        </a-select>
      </div>
      <a-upload-dragger
        v-model:fileList="fileList"
        name="file"
        :multiple="true"
        :action="baseUrl + '/calificacion/carga-ide/'"
        @change="handleChange"
        @drop="handleDrop"
        list-type="picture"
      >
        <p class="ant-upload-drag-icon ">
          <inbox-outlined></inbox-outlined>
        </p>
        <p class="ant-upload-text" style="width: 100%;">Haz clic o arrastra archivos a esta área para cargar</p>
        <p class="ant-upload-hint">
          Soporte para carga única o múltiple. Prohibido subir datos de la empresa u otros archivos prohibidos.
        </p>
      </a-upload-dragger>  
    </a-modal>
    </div>


  </Layout>  
</template>
  
<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import Layout from '@/Layouts/LayoutCalificador.vue'
import { InboxOutlined, FolderOutlined } from '@ant-design/icons-vue';
import { message } from 'ant-design-vue';
const baseUrl = window.location.origin;
const fileList = ref([]);

const visible = ref(false);
// const area = ref(null); 

const handleChange = (info) => {
  const status = info.file.status;
  if (status !== 'uploading') { console.log(info.file, fileList.value); }
  if (status === 'done') {
    message.success(`${info.file.name} archivo(s) subido(s) exitosamente.`);
  } else if (status === 'error') {
    message.error(`${info.file.name} falló al subir.`);
  }
};

const okey = () => { fileList.value = null;};

const current = ref(0);
const next = () => { current.value++; };
const prev = () => { current.value--; };

const steps = ref([
  { title: 'First', content: 'First-content', }, 
  { title: 'Second', content: 'Second-content',}, 
  { title: 'Last', content: 'Last-content',}
])


</script>
  