<template>
  <div>
    <a-row :gutter="16">
      <a-col :span="6" v-for="image in galleryImages" :key="image.id">
        <a-card :cover="image.imageUrl">
          <a-card-meta title="Imagen" />
        </a-card>
      </a-col>
    </a-row>
  </div>
</template>

<script setup>
import { reactive } from 'vue';

const galleryImages = reactive([
  {
    id: 1,
    imageUrl: 'https://example.com/image1.jpg',
  },
  {
    id: 2,
    imageUrl: 'https://example.com/image2.jpg',
  },
  {
    id: 3,
    imageUrl: 'https://example.com/image3.jpg',
  },
  // Agrega más imágenes según tus necesidades
]);
</script>
<!-- <template>
  <div>
    <h1>Componente Padre</h1>
    <Hijo ref="hijoComponent" />
    <button @click="ejecutarMetodoHijo">Ejecutar Método del Hijo</button>
  </div>
</template>

<script>
import Hijo from './components/hijo.vue';

export default {
  components: {
    Hijo
  },
  methods: {
    ejecutarMetodoHijo() {
      this.$refs.hijoComponent.ejecutarMetodo();
    }
  }
}
</script> -->