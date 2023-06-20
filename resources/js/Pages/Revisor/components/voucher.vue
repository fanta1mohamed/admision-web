<template>
<div>
    <!-- <a-card v-for="item in comprobantes" :key="item.id" style="width: 100%; margin-bottom: 10px;" > -->
        <!-- <div> {{item}}</div> -->
    <a-row :gutter="16" class="mb-3">
        <a-col :span="8" v-for="item in comprobantes" :key="item.id">
            <a-card class="">
                <div class="flex justify-between mb-4">
                    <div>
                        <div class="flex justify-center align-center" style="width: 40px; height:40px; border: 1px solid #d9d9d9;">
                            <div style="margin-top: -3px;">
                                <span style="font-weight: bold; font-size: 1.9rem;">{{ item.codigo }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div>{{ item.fecha.split("-").reverse().join("-") }}</div> 
                        <div class="flex justify-end">
                            <span style="font-weight: bold; font-size: 1.2rem;"> {{ item.nro_operacion }} </span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between">
                    <div @click="verificar(item.id, item.verificado)" style="cursor: pointer;" >        
                        <icon :class="item.verificado === 1? 'verde':'rojo'"    >
                            <template #component>
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                            </template>
                        </icon>
                    
                    
                    </div>
                    <div>
                        <span style="font-size: 1.5rem; font-weight: bold;"> S/ {{ item.monto.toFixed(2) }} </span>
                    </div>
                </div>

                <div>
                    <div class="flex justify-start">
                        <div>
                            <div style="margin-bottom: -8px;">
                                <span style="font-weight: bold; font-size: .8rem;"> {{ item.ndoc_postulante }} </span>
                            </div>
                            <span style="font-size: .8rem;"> {{ item.nombres }} {{ item.primer_apellido }} {{ item.segundo_apellido }} </span>
                        </div>
                    </div>
                </div>


            </a-card>
        </a-col>
    </a-row>
    <!-- </a-card> -->
</div>
</template>
<script setup>
import { ref } from 'vue';
import Icon,{ SolutionOutlined } from '@ant-design/icons-vue';

const comprobantes = ref([]);

const c = ref(0)
const props = defineProps({
  dni: {
    type: String,
    default: '',
  }
});

const getComprobantes = async () => {
  let res = await axios.post('get-comprobantes',
  { dni: props.dni });
  comprobantes.value = res.data.datos;
}

const verificar = async (id, estado) => {
  let res = await axios.post('verificar-comprobante',
  { id: id, estado: !estado });
  getComprobantes()
}

getComprobantes()

</script>

<style scoped>
.rojo{
    color: gray;
}
.verde {
    color: #00b900;
}
</style>
