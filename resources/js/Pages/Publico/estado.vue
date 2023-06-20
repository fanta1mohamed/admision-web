<template>
<Head title="Mi pasos"/>
<Layout>
<div class="flex " style="height: calc(100vh - 180px); margin: auto; background: none; align-items: center;" >
    <div v-if="postulante === null" style="width: 100%;" >
        <div class="flex align-center justify-center">           
          <a-card style="border-radius: 10px;">
              <div style="width: 300px;">
                <h1>
                  Ingresa dni y tu numero para ver tu avance en el Proceso de Admsión
                </h1>
              </div>
                <div class="mt-2">
                  <label for="dni">DNI:</label>
                  <a-input type="text" id="dni" placeholder="Ingrese dni" v-model:value="dni" :maxlength="dniMaxLength" inputmode="numeric" required/>
                </div>
            <div class="mt-2">
              <label for="ubigeo">Fecha de Ubigeo:</label>
              <a-input type="text" id="ubigeo" placeholder="Ingrese ubigeo" v-model:value="ubigeo" :maxlength="ubigeoMaxLength" :pattern="ubigeoPattern" required/>
            </div>
            <div class="flex justify-center mt-3">
              <a-button type="primary" @click="getDatos()" style="border-radius: 4px;" block>Enviar</a-button>
            </div>
          </a-card>
        </div>    
    </div>
    <div v-if="postulante != null" style="width: 100%;">
        <div v-if="observacion !== ''" class="flex justify-center observacion"> 
          <div style="text-align: center;">
            <h1 style="font-weight: bold; font-size:1.2rem;">!IMPORTANTE!</h1>
            <span>Sr(a). {{datos.nombres}} {{ observacion }} </span>
          </div> 
        </div>      
        <div  class="tracking">
            <div class="timeline">
                <div
                v-for="(step, index) in steps"
                :key="index"
                class="timeline-item"
                :class="{
                    'active': index === currentStep,
                    'completed': index < currentStep,
                    'disabled': index > currentStep
                }"
                @click="jumpStep(index)"
                >
                <div class="step-circle">
                    <div class="step-description" style="height: 200px; width: 170px; font-weight: bold;">
                        {{ step.description }}
                    </div>
                    <div v-if="observacion !== '' && currentStep === index" 
                      class="step-number" 
                      :class="{'jump': index === currentStep && jumping }"
                      style="background: red;"
                      @click="modal = true"
                          >
                      <span v-if="index <= currentStep">
                          <CheckOutlined/>
                      </span>
                      <span v-else>{{ index + 1 }}</span>
                    </div>
                    
                    <div v-else
                      class="step-number"
                      :class="{
                          'jump': index === currentStep && jumping
                      }"
                    >
                    
                    <span v-if="index <= currentStep">
                        <CheckOutlined/>
        
                    </span>
                    <span v-else>{{ index + 1 }}</span>
                    </div>
                    <div v-if="observacion !== '' && index === currentStep " class="step-description">
                        <div>!Observado!</div>  
                    </div>
                    <div v-else class="step-description" style="margin-top: 5px;">
                        <div>!Completado!</div>
                    </div>
                    
                </div>
                </div>
            </div>

        </div>

        <div class="flex justify-center mt-3">
          <a-button type="primary" @click="postulante = null " style="  border-radius: 4px; width: 200px;">Nueva Consulta</a-button>
        </div>

    </div>
  </div>

  <a-modal v-model:visible="modal" :footer="false" style="background:">
      <div>
          <div class="flex justify-center"><span style="font-size: 1.3rem; font-weight: bold;">!ATENCIÓN!</span></div>
          <div class="mt-2" style="text-align: center;"> 
            <span style="font-weight: 1.1rem; "> 
                {{ observacion }}
            </span> 
          </div>
          <div class="flex justify-center mt-3">
              <a-button @click="modal = false" style="border-radius: 4px; width: 150px;">Aceptar</a-button>
          </div>
      </div>
  </a-modal>
</Layout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import Layout from '@/Layouts/LayoutPasos.vue'    
import { ref, reactive, onMounted } from 'vue';
import { CheckOutlined, CloseOutlined, ExclamationCircleOutlined } from '@ant-design/icons-vue';

const steps = reactive([
  { description: 'Preinscripción' },
  { description: 'Examen Vocacional' },
  { description: 'Inscripción' },
  { description: 'Examen' },
  { description: 'Resultados' },
]);

const postulante = ref(null)

const modal = ref(false);
const observado = ref(true);

const currentStep = ref(0);
const observacion = ref("");
const jumping = ref(false);

onMounted(() => {
  setInterval(() => {
      jumping.value = true;
      setTimeout(() => {
        jumping.value = false;
      }, 500);
  }, 2500);
});

const dni = ref('');
const ubigeo = ref('');
const dniMaxLength = 12;
const dniPattern = /^\d{8}(\d{4})?$/;
const ubigeoMaxLength = 6;
const ubigeoPattern = /^\d{6}$/;


const test = ref(null)

const items = ref(null);
const datos = ref({
  dni_postulante: "",
  id_proceso: "",
  avance: "",
  id_usuario: 1
})
const getDatos = async () => {
  let res = await axios.post("get-avance-postulante",{ dni: dni.value, ubigeo: ubigeo.value });
  datos.value = res.data.datos;   
  currentStep.value = res.data.datos.avance - 1;
  observacion.value = res.data.datos.observacion;
  postulante.value = 's'
}

</script>
  
<style scope>
  .tracking {
    position: relative;
    display: flex;
    background: #2587e432;
    justify-content: center;
    border-radius: 20px;
    margin: 20px;
    align-items: center;
    height: 300px;
    width:calc(100% - 40px);
    scale: 0.8;
    height: calc(260px);
  }
  
  .timeline {    
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    max-width: 80%;
    margin: 0 auto;
  }
  
  .timeline-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    cursor: pointer;
  }
  
  .step-circle {
    position: relative;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #fff;
    background: none;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: background-color 0.3s ease;
  }
  
  .step-number {
    color: #fff;
    opacity: 1;
    transition: transform 0.3s ease;
    background-color: #2eb339;
    width: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
  }
  .observacion{}
  
  .step-number.jump {
    animation: jumpAnimation 0.5s ease-in-out;
    animation-iteration-count: 1;
  }
  
  @keyframes jumpAnimation {
    0% {
      transform: translateY(0);
    }
    50% {
      transform: translateY(-10px);
    }
    100% {
      transform: translateY(0);
    }
  }
  
  .step-number span.check-icon {
    display: none;
  }
  
  .step.completed .step-number {
    opacity: 1;
  }
  
  .step.completed span.check-icon {
    display: block;
  }
  
  .step-description {
    text-align: center;
    margin-top: 10px;
    background: white;
    background: none;
    font-size: 16px;
    color: #666;
  }
  
  .timeline-item.disabled {
    pointer-events: 100px;
    opacity: 0.5;
  }
  
  .timeline-item.disabled .step-circle {
    background-color: white;
  }
  
  .timeline-item.disabled .step-number {
    background-color: #ccc;
  }
  
  @media (max-width: 768px) {
    .tracking {
        height: 100%;
        height: calc(100vh - 200px);
        scale: .9;
    }
    .timeline {
      flex-direction: column;
      align-items: center;
    }
    .observacion{
      margin-top: 50px;
      padding-top: 120px;
    }
    .timeline-item {
      margin-bottom: 70px;
    }
}

.header {
  display: flex;
  align-items: center;
  padding: 16px;
  background-color: #001529;
}

.logo {
  margin-right: 32px;
}

.logo img {
  height: 80px;
  width: auto;
}

.subtitle {
  color: #fff;
}

.title {
  color: white;
  font-size: 22px;
  margin-bottom: 8px;
}

.description {
  font-size: 16px;
}

@media (max-width: 768px) {
  .header {
    flex-direction: column;
    align-items: center;
    height: 70px;
  }
  .title {font-size: 1.4rem; margin-top: 0px; color:white;}
  .subtitle {opacity:0.6;}
}

</style>