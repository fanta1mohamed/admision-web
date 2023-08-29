<template>
<Head title="Dashboard" />
<AuthenticatedLayout> 
<div class="flex" style="background: #f3f3f3;">
  <div style="width: calc(100% - 300px);">
    <!-- CARDS-->
    <div class="flex justify-between">
        <div class="p-4 card-dash" >
          <div class="flex justify-between">
            <div><span style="font-weight: bold;">Preinscritos</span></div>
            <div class="p-1 pl-2 pr-2" style="background: #6db6e753; border-radius: 50%;">
              <div style="margin-top: -5px;">
                <span style="color: var(--primary-color); font-size: 1.15em;"><team-outlined /></span>
              </div>
            </div>
          </div>
          <div style="margin-top: 50px;">
            <div v-if="preinscritos"> 
              <span style="font-size: 1.5rem; font-weight: bold;">
                {{ preinscritos }}
              </span>
            </div>
          </div>
          <div class="flex justify-start">
            <div>
              <span style="color: #00af00; font-weight:bold; "> {{ ultimopreinscrito.count }} preinscritos <span style="color: gray;">el {{ ultimopreinscrito.date }}</span> </span>
              <!-- <span style="color: #00af00;"> preinscritos  {{ (ultimopreinscrito.count / preinscritos ).toFixed(2) }}% <span style="color: gray;">el {{ ultimopreinscrito.date }}</span> </span> -->
            </div>
          </div>

        </div>

        <div class="p-4 card-dash">
          <div class="flex justify-between">
            <div><span style="font-weight: bold;">Documento</span></div>
            <div class="p-1 pl-2 pr-2" style="background: #6db6e753; border-radius: 50%;">
              <div style="margin-top: -5px;">
                <span style="color: var(--primary-color); font-size: 1.15em;"><team-outlined /></span>
              </div>
            </div>
          </div>
          <div style="margin-top: 50px;">
            <div> 
              <span style="font-size: 1.5rem; font-weight: bold;">
                1369
              </span>
            </div>
          </div>
          <div class="flex justify-start">
            <div> 
              <span style="color: #00af00;"> icon  16% <span style="color: gray;">Since las week</span> </span>
            </div>
          </div>

        </div>
        <div class="p-4 card-dash" style="background: white; border-radius: 9px; height: 160px;">
          <div class="flex justify-between">
            <div><span style="font-weight: bold;">Inscritos</span></div>
            <div class="p-1 pl-2 pr-2" style="background: #6db6e753; border-radius: 50%;">
              <div style="margin-top: -5px;">
                <span style="color: var(--primary-color); font-size: 1.15em;"><team-outlined /></span>
              </div>
            </div>
          </div>
          <div style="margin-top: 50px;">
            <div> 
              <span style="font-size: 1.5rem; font-weight: bold;">
                {{ inscritos }}
              </span>
            </div>
          </div>
          <div class="flex justify-start">
            <div> 
              <span style="color: #00af00; font-weight:bold; "> {{ ultimoinscrito.count }} inscritos <span style="color: gray;">el {{ ultimoinscrito.date }}</span> </span>
              <!-- <span style="color: #00af00;"> Inscritos  {{ (ultimoinscrito.count / inscritos ).toFixed(2) }}% <span style="color: gray;">el último día</span> </span> -->
            </div>
          </div>

        </div>
    </div>
    <!-- END CARD -->
    <div style="height: 16px;" ></div>

    <div class="flex justify-between">
        <div class="p-4 card-dash" style="background: white; width: 49%; height: 240px;">
          <div class="flex justify-between">
            <div><span style="font-weight: bold;">Documento</span></div>
            <div class="p-1 pl-2 pr-2" style="background: #6db6e753; border-radius: 50%;">
              <div style="margin-top: -5px;">
                <span style="color: var(--primary-color); font-size: 1.15em;"><team-outlined /></span>
              </div>
            </div>
          </div>
          <div style="margin-top: 0px;">
            <Bar
              id="my-chart-id"
              :options="barrasOpciones"
              :data="barras"
            />
          </div>
        </div>

        <div class="p-4 card-dash" style="background: white; width: 49%; height: 240px;"  >
          <div class="flex justify-between">
            <div><span style="font-weight: bold;">Documento</span></div>
            </div>
          <div style="margin-top: 0px;">
            <ChartComponent/>
          </div>
        </div>
    </div>
    <div class="flex" style="height: 16px;"></div>
    <div style="width: 100%; height: 300px; border-radius: 9px; background: white;">

    </div>
  </div>
  <div style="width: 300px;  height: 560px; padding-left: 15px;">
    <div class="p-4" style="background:white; border-radius: 9px; height: 560px;">
      <div class="mb-5">
        <div class="mb-3 flex justify-between">  
          <h1 style="font-weight: bold;">Mejores inscriptores</h1>
          <div style="margin-top: -5px;"> <span style="color: var(--primary-color);"><eye-outlined/></span></div>
        </div>

        <div v-if="minscriptores != null">
          <div v-for="(inscriptor,index) in minscriptores" :key="inscriptor.id" class="flex mb-2" style="height: 38px; width: 100%;">
            <div style="border-radius: 50%; height: 38px; overflow: hidden;">
                <div v-if="inscriptor.url">
                  <img src="https://images.pexels.com/photos/614810/pexels-photo-614810.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
                    width="38" height="38"> 
                </div>             

                <div v-else style="width: 38px; height: 38px;" :style="'background:'+colores[index]"> 
                  <!-- {{ colores[index] }} -->
                  <div class="flex justify-center pt-0">
                    <span style="color: white; font-size: 1.7rem;">
                      {{ inscriptor.paterno[0].toUpperCase() }} 
                    </span> 
                    <!-- <span style="color: white; font-weight: bold; font-size:1.5rem;">
                      {{ inscriptor.paterno[0].toUpperCase() }} 
                    </span>  -->
                  </div>
                </div>


            </div>
            <div class="ml-2">
              <div style="margin-top: 2px;"><span style="font-weight: bold;">{{ inscriptor.name }} {{ inscriptor.paterno }}</span></div>
              <div style="margin-top: -7px;"><span style="color: gray; font-size: .8rem;">{{ inscriptor.cant }} Inscritos</span></div>
            </div>
          </div>
        </div>
      </div>
      <div>
        <div class="mb-3">  
          <h1 style="font-weight: bold;">Mejores Revisores del día</h1>
        </div>

        <div v-if="minscriptoresD != null"  >
          <div v-for="(inscriptor,index) in minscriptoresD" :key="inscriptor.id" class="flex mb-2" style="height: 38px; width: 100%;">
            <div style="border-radius: 50%; height: 38px; overflow: hidden;">
                <div v-if="inscriptor.url">
                  <img src="https://images.pexels.com/photos/614810/pexels-photo-614810.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
                    width="38" height="38"> 
                </div>             

                <div v-else style="width: 38px; height: 38px;" :style="'background:'+colores[index]"> 
                  <!-- {{ colores[index] }} -->
                  <div class="flex justify-center pt-0">
                    <span style="color: white; font-size: 1.7rem;">
                      {{ inscriptor.paterno[0].toUpperCase() }} 
                    </span> 
                  </div>
                </div>


            </div>
            <div class="ml-2">
              <div style="margin-top: 2px;"><span style="font-weight: bold;">{{ inscriptor.name }} {{ inscriptor.paterno }}</span></div>
              <div style="margin-top: -7px;"><span style="color: gray; font-size: .8rem;">{{ inscriptor.cant }} Inscritos</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3';
import { TeamOutlined, EyeOutlined } from '@ant-design/icons-vue';
import { Bar } from 'vue-chartjs'
import ChartComponent from './Dashboard/components/ChartComponent.vue';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
import { ref } from 'vue';
import { toFixed } from 'ant-design-vue/lib/input-number/src/utils/MiniDecimal';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const barras = {
    labels: [ 'Lun', 'Mar', 'Mie', 'Jue', 'Vie' ],
    datasets: [ 
      { 
        label: 'Data 1',
        backgroundColor: '#00aae4',
        borderColor: '#00aae4',
        borderWidth: 2,
        fill: false,
        data: [20, 39, 50, 60, 70, 89],
        tension: 0.5,
        pointRadius: false,
      },
      { 
        label: 'Data 1',
        backgroundColor: 'blue',
        borderColor: 'blue',
        borderWidth: 2,
        fill: false,
        data: [70, 89, 10, 90, 35, 89],
        tension: 0.5,
        pointRadius: false,
      }, 
    ]
  }
const barrasOpciones = { responsive: true }

const preinscritos = ref(0);
const ultimopreinscrito = ref({ count:0, date:'0000-00-00' });
const inscritos = ref(0);
const ultimoinscrito = ref({ count:0, date:'0000-00-00' });
const minscriptores = ref(null);
const minscriptoresD = ref(null);


const getPreinscritos = async () => {
  try {
    const response = await axios.get("get-preinscritos", { dni: "dni" });
    preinscritos.value = response.data.preinscritos;
    ultimopreinscrito.value = response.data.fecha;
  } catch (error) {
    console.error("Error al obtener datos de preinscritos:", error);
    return null;
  }
};

const getInscritos = async () => {
  try {
    const response = await axios.get("get-inscritos", { dni: "dni" });
    inscritos.value = response.data.inscritos;
    ultimoinscrito.value = response.data.fecha;
  } catch (error) {
    console.error("Error al obtener datos de los inscritos:", error);
    return null;
  }
};

const getMinscriptores = async () => {
  try {
    const response = await axios.get("get-mejores-inscriptores", { dni: "dni" });
    minscriptores.value = response.data.inscriptores;
  } catch (error) {
    console.error("Error al obtener datos de los inscritos:", error);
    return null;
  }
};

const getMinscriptoresDia = async () => {
  try {
    const response = await axios.post("get-mejores-inscriptores-dia", { fecha: "2023-08-04" });
    minscriptoresD.value = response.data.inscriptores;
  } catch (error) {
    console.error("Error al obtener datos de los inscritos:", error);
    return null;
  }
};



getPreinscritos();
getInscritos();
getMinscriptores();
getMinscriptoresDia();


const colores = [
  "#f3b4c8", "#f9cb9c", "#f4d5ae", "#d6e4a3", "#92c7a3",
  "#a3cedc", "#d7aefb", "#f7aef8", "#f8a1d1", "#f0bad6",
  "#f9d6ac", "#fed6a3", "#f6b89d", "#fcc5ae", "#fee1a3",
  "#c9e8a3", "#a3e2c7", "#a3e1e0", "#a3cce1", "#c3a3e1",
  "#e8a3d0", "#e2a3a3", "#f29b82", "#efc085", "#ebd47f",
  "#d1d17a", "#87c289", "#87c2bd", "#8bb4cc", "#a48bcc",
  "#b58bcc", "#cb8bcc", "#cc8bb1", "#cf8b8b", "#d0a18b",
  "#d5a18b", "#d9a18b", "#dba88c", "#ddb593", "#deb6a6",
  "#95b0ac", "#9aaca7", "#a19e98", "#bba895", "#d2c0a7",
  "#d5c6a3", "#d6cfad", "#d9cfad", "#dbd4b2", "#ddd8b6"
];

</script>

<style scoped>
.card-dash{
  background: white;  
  border-radius: 9px; 
  height: 160px;
  width: 260px;
}

</style>