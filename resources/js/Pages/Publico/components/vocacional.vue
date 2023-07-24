<template>
            <div class="align-center justify-center mt-7" style="background: #00000008; border-radius: 15px; padding: 20px;">
            <div class="mb-3">
                <div class="flex justify-end" style="margin-bottom: -25px; ">
                    <h1 v-if="tiempo > 16" style="font-size: 2.2rem; font-weight: bold;">00:00:{{ tiempo  }} </h1>
                    <h1 v-else style="font-size: 2.2rem; font-weight: bold;">00:00:0{{ tiempo  }} </h1>
                </div>
                <a-progress :percent="(tiempo * 1.666666666667).toFixed(0)" :strokeWidth="15" :showInfo="false" strokeColor="navy" />
            </div>
            <div class="mb-3" v-for="(item, index) in preguntas" :key="item.id">
                <h3 >{{ index + 1 }}. {{  item.pregunta }}</h3>
                <div v-for="respuesta in item.respuestas" :key="respuesta.ide">
                    <a-radio-group v-if="tiempoRestante > 0" v-model:value="respuestas[index]">
                        <a-radio :value="respuesta">{{ respuesta.respuesta }}</a-radio>
                    </a-radio-group>            
                    <a-radio-group v-else v-model:value="respuestas[index]" disabled>
                        <a-radio :value="respuesta">{{ respuesta.respuesta }}</a-radio>
                    </a-radio-group>
                </div>
            </div>
            <div class="pl-6 pr-6 pt-4 pb-4" style="border-radius: 5px; color: white; 
                font-size: 1.2rem; box-shadow: 1px 2px 2px 1px #a3a1a143; background: navy; max-width: 800px;">
                ¿Qué piensas de la variedad de danzas 
                autóctonas que practican las poblaciones o 
                comunidades andinas? 
            </div>

            <div class="mt-4 p-3" style="border-radius: 5px; background: white; color: gray; 
                font-size: 1rem; border: #a3a1a143 1px solid; max-width: 800px;">
        :       ¿Qué piensas de la variedad de danzas 
                autóctonas que practican las poblaciones o 
                comunidades andinas? 
            </div>
            <div class="mt-4 p-3" style="border-radius: 5px; background: white; color: gray; 
                font-size: 1rem;  border: #a3a1a143 1px solid; max-width: 800px;">
                ¿Qué piensas de la variedad de danzas 
                autóctonas que practican las poblaciones o 
                comunidades andinas? 
            </div>

            <div class="mt-4 p-3" style="border-radius: 5px; background: white; color: gray; 
                font-size: 1rem;  border: #a3a1a143 1px solid; max-width: 800px;">
                ¿Qué piensas de la variedad de danzas 
                autóctonas que practican las poblaciones o 
                comunidades andinas? 
            </div>

            <div class="mt-4 p-3" style="border-radius: 5px; background: white; color: gray; 
                font-size: 1rem;  border: #a3a1a143 1px solid; max-width: 800px;">
                ¿Qué piensas de la variedad de danzas 
                autóctonas que practican las poblaciones o 
                comunidades andinas? 
            </div>

            <div class="mt-4 p-3" style="border-radius: 5px; background: white; color: gray; 
                font-size: 1rem;  border: #a3a1a143 1px solid; max-width: 800px;">
                ¿Qué piensas de la variedad de danzas 
                autóctonas que practican las poblaciones o 
                comunidades andinas? 
            </div>

        </div>
</template>

<script setup>
import {ref} from 'vue';

const avanzar = () => {
  const percent = defaultPercent.value + 10;
  defaultPercent.value = percent > 100 ? 100 : percent;
};
const disminuir = () => {
  const percent = defaultPercent.value - 10;
  defaultPercent.value = percent < 0 ? 0 : percent;
};

const tiempo = ref(60);

const iniciarContador = () => {
  const intervalo = setInterval(() => {
    if (tiempo.value > 0) {
      tiempo.value--;
    } else {
      clearInterval(intervalo);
    }
  }, 1000);
};

iniciarContador()

</script>