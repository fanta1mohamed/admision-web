<template>
    <div class="scroll-container" ref="scrollContainer">
      <div class="pr-3" style="text-align: justify;">
        <p>
          Este examen de simulacro de admisión tiene como objetivo proporcionar a los estudiantes de quinto 
          año de secundaria una valiosa oportunidad de preparación y práctica, en un formato similar al 
          examen de admisión real de la Universidad Nacional del Altiplano (UNA PUNO). 
          Sin embargo, es fundamental destacar lo siguiente:

        </p>
        <p>
          No Garantiza el Ingreso a la Universidad: La participación en este simulacro de admisión no garantiza 
          de ninguna manera el ingreso a la UNA PUNO. Este proceso de evaluación está diseñado exclusivamente 
          para que los estudiantes se familiaricen con el tipo de preguntas y el entorno de un examen de admisión, 
          pero no otorga vacantes para la universidad.
        </p>

        <p>
          Este simulacro de admisión se brinda como una herramienta de entrenamiento y práctica para los 
          estudiantes que se preparan para el examen de admisión de la UNA PUNO. Es importante comprender que sus 
          resultados no tienen un peso determinante en el proceso de admisión oficial de la universidad y que la admisión 
          real sigue un procedimiento distinto. Los participantes deben utilizar esta oportunidad para mejorar sus 
          habilidades y evaluarse a sí mismos en un entorno simulado.

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
  if (el.scrollTop + el.clientHeight >= el.scrollHeight - 50) {

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