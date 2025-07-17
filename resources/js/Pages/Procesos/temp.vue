<template>
  <a-button
    type="primary"
    :loading="isPreparing"
    :disabled="isPreparing"
    @click="handleDownload"
  >
    {{ buttonText }}
  </a-button>
</template>

<script setup>
import { ref, computed } from 'vue';
import { message } from 'ant-design-vue';
import axios from 'axios';

const isPreparing = ref(false);
const downloadStatus = ref(null);
const downloadUrl = ref(null);

const buttonText = computed(() => {
  if (isPreparing.value) return 'Preparando archivo...';
  if (downloadStatus.value === 'ready') return 'Descargar ahora';
  return 'Descargar documentos';
});

const handleDownload = async () => {
  try {
    isPreparing.value = true;
    message.info('Preparando archivo, esto puede tomar unos minutos...');

    // 1. Iniciar la preparación del ZIP
    const prepareResponse = await axios.post(route('download.prepare'), {}, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
      }
    });

    // 2. Verificar estado periódicamente
    const checkInterval = setInterval(async () => {
      try {
        const statusResponse = await axios.get(route('download.status'));
        const statusData = statusResponse.data;

        if (statusData.status === 'ready') {
          clearInterval(checkInterval);
          downloadStatus.value = 'ready';
          downloadUrl.value = statusData.download_url;
          isPreparing.value = false;
          message.success('Archivo listo para descargar');

          // 3. Iniciar la descarga automáticamente
          startDownload(statusData.download_url);
        } else if (statusData.status === 'failed') {
          clearInterval(checkInterval);
          isPreparing.value = false;
          message.error('Error al preparar el archivo');
        }
      } catch (error) {
        clearInterval(checkInterval);
        isPreparing.value = false;
        message.error('Error al verificar el estado');
      }
    }, 5000); // Verificar cada 5 segundos

  } catch (error) {
    isPreparing.value = false;
    message.error('Error al iniciar la descarga');
  }
};

const startDownload = (url) => {
  // Crear un enlace temporal y hacer click para iniciar la descarga
  const link = document.createElement('a');
  link.href = url;
  link.target = '_blank';
  link.download = '';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};
</script>
