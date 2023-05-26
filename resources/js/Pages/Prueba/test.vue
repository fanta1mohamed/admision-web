<template>
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
</script>