<template>
 <div style="margin-bottom: 10px; background: red; margin-left: -20px; margin-right: -20px; padding: 5px 20px; padding-bottom: 0px; border-radius: 7px;"> 
  <a-row :gutter="16">
    <a-col :xs="24" :sm="24" :md="24" :lg="12" :xl="12">
        <a-form-item>
        <label>Carrera</label>
        <a-input v-model:value="item.career" />
        </a-form-item>
    </a-col>
    <a-col :xs="24" :sm="24" :md="24" :lg="6" :xl="6">
        <a-form-item>
        <label>Código</label>
        <a-input v-model:value="item.code" />
        </a-form-item>
    </a-col>
    
    <!-- <a-col :xs="24" :sm="24" :md="24" :lg="18" :xl="18">
        <a-form-item>
        <label style="color: white;">Carrera</label>
        <a-input v-model:value="item.career" />
        </a-form-item>
    </a-col> -->

    <a-col :xs="24" :sm="24" :md="24" :lg="6" :xl="6">
        <a-form-item>
        <label style="color: white;"  >Condición</label>
        <a-input v-model:value="item.cond1tion" />
        </a-form-item>
    </a-col>
  </a-row>
</div> 
</template>


<script setup>
import { ref } from 'vue';
import Icon,{ SolutionOutlined } from '@ant-design/icons-vue';

const comprobantes = ref([]);

const c = ref(0)
const props = defineProps({
  item: {
    type: Object,
    default: '',
  }
});

// const getComprobantes = async () => {
//   let res = await axios.post('get-comprobantes',
//   { dni: props.dni });
//   comprobantes.value = res.data.datos;
// }
// const getEstadoEstudiante = async() => {
//   try {
//     const response = await axios.get('https://service2.unap.edu.pe/CONDITIONS/?w='+anteriores, 
//     { headers: { 'Content-Type': 'application/json'}  });
//     anteriores.value = response.data;
//   } catch (error) {
//     console.error('Error:', error);
//   }
// };

//getEstadoEstudiante()
</script>