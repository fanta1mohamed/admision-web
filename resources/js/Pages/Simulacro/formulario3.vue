<template>
    <div class="scroll-container" ref="scrollContainer">
      <div class="pr-3" style="text-align: justify;">
        <p>
          Este examen de simulacro de admisión tiene como objetivo la preparación y práctica
          similar a un examen de admisión real, para estudiantes de quinto año de secundaria.
          No garantiza ingreso a la universidad ni otorga vacantes. Los resultados aquí
          obtenidos carecen de validez oficial y no tienen relación con el proceso de admisión
          real.

          Este examen de simulacro de admisión tiene como objetivo la preparación y práctica
          similar a un examen de admisión real, para estudiantes de quinto año de secundaria.
          No garantiza ingreso a la universidad ni otorga vacantes. Los resultados aquí
          obtenidos carecen de validez oficial y no tienen relación con el proceso de admisión
          real.

          Este examen de simulacro de admisión tiene como objetivo la preparación y práctica
          similar a un examen de admisión real, para estudiantes de quinto año de secundaria.
          No garantiza ingreso a la universidad ni otorga vacantes. Los resultados aquí
          obtenidos carecen de validez oficial y no tienen relación con el proceso de admisión
          real.
        </p>
      </div>
    </div>
  </template>
  
<script setup>
import { ref, onMounted, defineProps, defineEmits } from 'vue';  

const emit  = defineEmits();
const final = ref(false)
const items = ref([]);
const scrollContainer = ref(null);
const { parentData } = defineProps(['parentData']); // Propiedad pasada desde el padre
const localData = ref(parentData);

onMounted(() => { scrollContainer.value.addEventListener('scroll', checkScrollPosition); });
  
const checkScrollPosition = () => {
  const el = scrollContainer.value;
  if (el.scrollTop + el.clientHeight >= el.scrollHeight) {
    console.log('¡Has llegado al final del scroll!');
    final.value = true;
    localData.value = true;
    emit('update-parent-data', localData.value);
  }
};
</script>
  
<style scoped>
.scroll-container {
  height: 300px;
  overflow-y: scroll;
}

.scroll-container::-webkit-scrollbar {
  width: 12px; /* Ancho de la barra de desplazamiento */
}

.scroll-container::-webkit-scrollbar-thumb {
  background-color: #888; /* Color del pulgar de la barra de desplazamiento */
}

.scroll-container::-webkit-scrollbar-track {
  background-color: #f1f1f1; /* Color de la pista de la barra de desplazamiento */
}

</style>