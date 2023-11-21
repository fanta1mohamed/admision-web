<template>
    <div>
      <a-upload-dragger
        v-model:fileList="fileList"
        name="file"
        :multiple="true"
        :action="baseUrl + '/calificacion/carga-archivo'"
        @change="handleChange"
        @drop="handleDrop"
      >
        <p class="ant-upload-drag-icon">
          <inbox-outlined></inbox-outlined>
        </p>
        <p class="ant-upload-text">Haz clic o arrastra archivos a esta área para cargar</p>
        <p class="ant-upload-hint">
          Soporte para carga única o múltiple. Prohibido subir datos de la empresa u otros archivos prohibidos.
        </p>
      </a-upload-dragger>
  
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { InboxOutlined } from '@ant-design/icons-vue';
  import { message } from 'ant-design-vue';
  
  const baseUrl = window.location.origin;
  const fileList = ref([]);
  
  const handleChange = (info) => {
    const status = info.file.status;
  
    if (status !== 'uploading') {
      console.log(info.file, fileList.value);
    }
  
    if (status === 'done') {
      message.success(`${info.file.name} archivo(s) subido(s) exitosamente.`);
    } else if (status === 'error') {
      message.error(`${info.file.name} falló al subir.`);
    }
  };
  
  const handleDrop = (e) => {
    console.log(e);
  };
  
  const uploadCurrentFile = async () => {
    try {
      if (fileList.value.length === 0) {
        message.error('No hay archivos para cargar.');
        return;
      }
  
      const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
      const formData = new FormData();
      formData.append('file[]', fileList.value[0].originFileObj); // Sube solo el primer archivo
  
      const headers = {
        'X-CSRF-TOKEN': csrfToken,
        'Content-Type': 'multipart/form-data',
      };
  
      await axios.post(baseUrl + '/calificacion/carga-archivo', formData, { headers });
  
      message.success('Archivo subido exitosamente.');
    } catch (error) {
      console.error('Error en la solicitud Axios:', error);
    }
  };
  </script>
  