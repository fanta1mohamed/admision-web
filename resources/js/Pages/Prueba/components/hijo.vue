<template>
  <div>
    <button @click="ejecutarMetodo">Ejecutar Método</button>
  </div>
</template>

<script>
export default {
  name: 'Hijo',
  methods: {
    ejecutarMetodo() {
      console.log("Método del Hijo ejecutado");
    }
  }
}
</script>