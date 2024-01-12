<template>
    <div class="flex justify-center mt-6">
      <img src="../../../../assets/imagenes/alert.png" width="150"/>
    </div>
    <div>
      <h2 class="text-center" style="font-size:1.8rem;">¡Importante!</h2>
      <p class="text-center mx-4" style="font-size:1.2rem;">El participante no reúne las condiciones para participar en este proceso.</p>
      <p class="text-center mx-4" style="font-size:1.2rem;">Aproximese al CEPREUNA.</p>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps(['titulo', 'mensaje']);


</script>