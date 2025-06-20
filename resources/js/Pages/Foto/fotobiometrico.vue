<template>
<Head title="Inscripciones-impresión"/>
<AuthenticatedLayout>
    <div class="flex" style="padding: 20px;">
      <div ref="captureContainer" style="position: relative;">
        <video ref="videoElement" autoplay></video>
        <div
          v-if="cargado === true"
          ref="cropArea"
          :style="`position: absolute; top: ${cropAreaTop}px; left: ${cropAreaLeft}px; width: ${cropAreaWidth}px; height: ${cropAreaHeight}px; border: 1px dashed gray;`"
        ></div>
        <div v-if="cargado === true" class="flex justify-center" style="margin-top: -50px;" >
            <a-button type="primary" @click="capturePhoto">Capturar Foto</a-button>
        </div>

      </div>
      <div v-if="capturedPhoto" style="margin-bottom: -40px;">
        <img :src="capturedPhoto" alt="Foto Capturada">
      </div>
    </div>
    <div class="flex justify-between mt-3" style="padding: 0px 20px;">
        <div v-if="cargado === true" style="width: 300px;">
            <a-input v-model:value="dni" placeholder="Ingrese DNI" />
        </div>
        <!-- <div>
            <a-button v-if="capturedPhoto" @click="saveCroppedPhoto()">Guardar</a-button>
            <a-button type="primary" v-else @click="saveCroppedPhoto()" disabled>Guardar</a-button>
        </div> -->
    </div>


</AuthenticatedLayout>
</template>
<script setup>
    import { Head } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/LayoutDocente.vue'    
    import {ref, watch} from 'vue'
    import { ExclamationCircleOutlined, FormOutlined, DeleteOutlined, EyeOutlined } from '@ant-design/icons-vue';
</script>
<script>
  import axios from 'axios';
  export default {
    data() {
      return {
        dni:null,
        capturedPhoto: null,
        cropAreaTop: 0, // Posición vertical del área de recorte
        cropAreaLeft: 120, // Posición horizontal del área de recorte
        cropAreaWidth: 400, // Ancho del área de recorte
        cropAreaHeight: 480, // Alto del área de recorte
        cargado: false,
      };
    },
    watch: {
        dni(newValue, oldValue) {
            if(this.dni.length === 8){
                this.capturePhoto()
            }
        }
    },
    mounted() {
      this.initializeCamera();
    },
    beforeUnmount() {
      this.stopCamera();
    },
    methods: {
      async initializeCamera() {
        try {
          const constraints = { video: true };
          const stream = await navigator.mediaDevices.getUserMedia(constraints);
          this.$refs.videoElement.srcObject = stream;
          this.cargado = true;
        } catch (error) {
          console.error('Error al acceder a la cámara: ', error);
        }
      },
      stopCamera() {
        const stream = this.$refs.videoElement.srcObject;
        if (stream) {
          stream.getTracks().forEach(track => track.stop());
        }
      },
      capturePhoto() {
        const video = this.$refs.videoElement;
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        const cropArea = this.$refs.cropArea;
        const width = cropArea.offsetWidth;
        const height = cropArea.offsetHeight;
        const x = cropArea.offsetLeft - video.offsetLeft;
        const y = cropArea.offsetTop - video.offsetTop;
        canvas.width = width;
        canvas.height = height;
        context.drawImage(video, x, y, width, height, 0, 0, width, height);
  
        const capturedPhotoURL = canvas.toDataURL();
        this.capturedPhoto = capturedPhotoURL;
        this.saveCroppedPhotoBiometrico()
      },

        saveCroppedPhotoBiometrico() {
            if (this.capturedPhoto) {
            axios.post('guardar-foto-biometrico', { dni:this.dni, photo: this.capturedPhoto })
                .then(response => {
                console.log('Foto recortada guardada correctamente');
                // Realiza cualquier acción adicional después de guardar la foto recortada
                })
                .catch(error => {
                console.error('Error al guardar la foto recortada: ', error);
                });
            }
        }

    }
  };
  </script>
  