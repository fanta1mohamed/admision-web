<template>
  <div class="camera-container">
    <div class="camera-view">
      <div class="camera-wrapper" :style="cameraWrapperStyle">
        <video ref="videoElement" autoplay :style="videoStyle" @loadedmetadata="initializeCropArea"></video>
        <div v-if="cargando" ref="cropArea" class="crop-area" :style="cropAreaStyle"></div>
      </div>
      <div class="zoom-control">
        <label for="zoomSlider">Zoom:</label>
        <input
          type="range"
          id="zoomSlider"
          v-model="zoomValue"
          min="1"
          max="2"
          step="0.1"
          @input="applyZoom"
        />
      </div>
    </div>
    <div class="controls">
      <a-input v-model:value="dni" class="dni-input" placeholder="Ingrese DNI" />
      <a-button v-if="!capturedPhoto" type="primary" @click="capturePhoto">Capturar Foto</a-button>
      <a-button v-else type="primary" @click="cancelCapture">Cancelar Captura</a-button>
    </div>
    <div v-if="capturedPhoto" class="captured-photo">
      <img :src="capturedPhoto" alt="Foto Capturada">
      <div class="actions">
        <a-button type="primary" @click="saveCroppedPhoto">Guardar Foto</a-button>
        <a-button @click="cancelCapture">Cancelar</a-button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';

const zoomValue = ref(1);
const capturedPhoto = ref(null);
const cropAreaStyle = ref({});
const cargando = ref(false);
const dni = ref('');
const videoElement = ref(null);
const cropAreaElement = ref(null);
const videoStyle = ref('');
const cameraWrapperStyle = ref('');

onMounted(() => {
  // Solicitar permisos de cámara al cargar la página
  if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices
      .getUserMedia({ video: true })
      .then((stream) => {
        videoElement.value.srcObject = stream;
        videoElement.value.play();
      })
      .catch((error) => {
        console.error('Error al acceder a la cámara: ', error);
      });
  }
});

function initializeCropArea() {
  // Ajustar el tamaño del área de recorte al cargar el video
  const video = videoElement.value;
  const aspectRatio = video.videoWidth / video.videoHeight;
  const height = video.clientHeight;
  const width = height * aspectRatio;

  cropAreaElement.value.style.width = `${width}px`;
  cropAreaElement.value.style.height = `${height}px`;
  cropAreaElement.value.style.left = `${(video.clientWidth - width) / 2}px`;

  cropAreaStyle.value = {
    position: 'absolute',
    top: '0',
    left: `${(video.clientWidth - width) / 2}px`,
    width: `${width}px`,
    height: `${height}px`,
    border: '1px dashed blue',
  };
}

function applyZoom() {
  const video = videoElement.value;
  const zoom = zoomValue.value;

  // Aplicar transformación de zoom en el video
  video.style.transform = `scale(${zoom})`;
}

</script>

<style>
/* Estilos adicionales para asegurar que el video se muestre correctamente */
video {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Resto de los estilos sigue siendo el mismo */
</style>
