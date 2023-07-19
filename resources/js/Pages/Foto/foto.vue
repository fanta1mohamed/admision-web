<template>
  <Head title="Inscripciones-impresión"/>
  <AuthenticatedLayout>
      <div class="flex" style="padding: 20px;">
        <div ref="captureContainer" style="position: relative;">
          <video ref="videoElement" autoplay></video>
          <div    
            v-if="cargando === true"
            ref="cropArea"
            :style="`position: absolute; top: ${cropAreaTop}px; left: ${cropAreaLeft}px; width: ${cropAreaWidth}px; height: ${cropAreaHeight}px; border: 1px solid red;`"
          ></div>

          <div v-if="cargando === true" class="flex" style="scale: 0.8; margin-top: -100px; margin-right: -60px; justify-content: right;">
            <div style="margin-right: 20px; ">
              <div> <a-button @mousedown="startMoving('A')" @mouseleave="stopMoving" @mouseup="stopMoving" @click="aumentar()"> <plus-outlined/> </a-button> </div>
              <div style="margin-top: 32px;"> <a-button @mousedown="startMoving('R')" @mouseleave="stopMoving" @mouseup="stopMoving" @click="reducir()"> --- </a-button> </div>
            </div>
            <div style="text-align: center;">
              <div> <a-button @mousedown="startMoving('T')" @mouseleave="stopMoving" @mouseup="stopMoving" @click="movTop()"><arrow-up-outlined/></a-button> </div>
              <div style="display: flex; width: 140px; justify-content: space-between;"> 
                <a-button @mousedown="startMoving('I')" @mouseleave="stopMoving" @mouseup="stopMoving" @click="movIzq()"><arrow-left-outlined /></a-button> 
                <a-button @mousedown="startMoving('D')" @mouseleave="stopMoving" @mouseup="stopMoving" @click="movDer()"> <arrow-right-outlined /> </a-button> 
              </div>              
              <div><a-button @mousedown="startMoving('B')" @mouseleave="stopMoving" @mouseup="stopMoving" @click="movBot()"> <arrow-down-outlined /> </a-button> </div>              
            </div>
          </div>

          <div v-if="cargando === true" class="flex justify-center" style="margin-top: -50px;" >
              <a-button @click="capturePhoto">Capturar Foto</a-button>
          </div>
  
        </div>
        <div v-if="capturedPhoto" style="margin-bottom: -40px;">
              <img :src="capturedPhoto" alt="Foto Capturada">
        </div>
        
      </div>
      <div v-if="cargando === true" class="flex justify-between mt-3" style="padding: 0px 20px;">
          <div style="width: 300px;">
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
      import { ArrowUpOutlined, ArrowDownOutlined, ArrowLeftOutlined, ArrowRightOutlined, PlusOutlined, MinusOutlined } from '@ant-design/icons-vue';
  </script>
<script>
  import axios from 'axios';
  export default {
      data() {
        return {
          dni:null,
          capturedPhoto: null,
          cropAreaTop: 0, // Posición vertical del área de recorte
          cropAreaLeft: 105, // Posición horizontal del área de recorte
          cropAreaWidth: 430, // Ancho del área de recorte
          cropAreaHeight: 480, // Alto del área de recorte
          cargando: false,
          vid:null,
          vidHeight:200,
          isMoving: false,
          interval: null,
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
        startMoving(letra) { 
          this.isMoving = true; 
          if(letra == 'I') {
            this.interval = setInterval(this.movIzq, 10);            
          } else if(letra == 'D') {
              this.interval = setInterval(this.movDer, 10);
          } else if(letra == 'T') {
              this.interval = setInterval(this.movTop, 10);
          } else if(letra == 'B'){
              this.interval = setInterval(this.movBot, 10);
          } else if(letra == 'A'){
              this.interval = setInterval(this.aumentar, 10);
          } else if(letra == 'R'){
              this.interval = setInterval(this.reducir, 10);
          }
        },

        stopMoving() { this.isMoving = false; clearInterval(this.interval); this.interval = null; },
        movIzq() { this.cropAreaLeft = this.cropAreaLeft -= 2; },
        movDer() { this.cropAreaLeft = this.cropAreaLeft += 2; },
        movTop() { this.cropAreaTop = this.cropAreaTop -= 2; },
        movBot() { this.cropAreaTop = this.cropAreaTop += 2; },
        aumentar(){
          if( this.cropAreaHeight < this.vidHeight){
            this.cropAreaWidth += 2.15;
            this.cropAreaHeight += 2.4;
          }else{
            this.cropAreaHeight = this.vidHeight;
            this.cropAreaWidth = this.cropAreaWidth;
          }
        },
        reducir(){
          if( this.cropAreaHeight <= 240){
            this.cropAreaWidth = this.cropAreaWidth;
            this.cropAreaHeight = this.cropAreaWidth;
          }else{
            this.cropAreaWidth -= 4.3;
            this.cropAreaHeight -= 4.8;
          }

        },

        async initializeCamera() {
          try {
              const constraints = { video: true };
              const stream = await navigator.mediaDevices.getUserMedia(constraints);
              this.$refs.videoElement.srcObject = stream;
              this.cargando = true;

              this.$refs.videoElement.addEventListener('loadedmetadata', () => {
              this.vidHeight = this.$refs.videoElement.videoHeight;
            });

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
          this.saveCroppedPhoto()
        },
  
          saveCroppedPhoto() {
              if (this.capturedPhoto) {
              axios.post('guardar-foto-inscripcion', { dni:this.dni, photo: this.capturedPhoto })
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