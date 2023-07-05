<template>
<Head title="Dashboard" />
<AuthenticatedLayout> 
<div class="flex" style="background: #f3f3f3;">
  <div style="width: calc(100% - 300px);">
    <!-- CARDS-->
    <div class="flex justify-between">
        <div class="p-4 card-dash" >
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
  <div style="width: 300px;  height: 500px; padding-left: 15px;">
    <div class="p-4" style="background:white; border-radius: 9px; height: 500px;">
      <div class="mb-5">
        <div class="mb-3 flex justify-between">  
          <h1 style="font-weight: bold;">Mejores inscriptores</h1>
          <div style="margin-top: -5px;"> <span style="color: var(--primary-color);"><eye-outlined/></span></div>
        </div>

        <div v-for="index in 4" :key="index" class="flex mb-2" style="height: 38px; width: 100%;">
          <div style="border-radius: 50%; height: 38px; overflow: hidden; background: gray;">
              <img src="https://images.pexels.com/photos/614810/pexels-photo-614810.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
              width="38" height="38">
          </div>
          <div class="ml-2">
            <div style="margin-top: 2px;"><span style="font-weight: bold;">Ariel Luque Cusacani</span></div>
            <div style="margin-top: -7px;"><span style="color: gray; font-size: .8rem;">832 Inscritos</span></div>
          </div>
        </div>
      </div>
      <div>
        <div class="mb-3">  
          <h1 style="font-weight: bold;">Mejores Revisores</h1>
        </div>

        <div v-for="index in 5" :key="index" class="flex mb-2" style="height: 38px; width: 100%;">
          <div style="border-radius: 50%; height: 38px; overflow: hidden;">
              <img src="data:image/jpeg;base64,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"
              width="38" height="38">
          </div>
          <div class="ml-2">
            <div style="margin-top: 2px;"><span style="font-weight: bold;">Ariel Luque Cusacani</span></div>
            <div style="margin-top: -7px;"><span style="color: gray; font-size: .8rem;">832 Inscritos</span></div>
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


</script>
<style scoped>
.card-dash{
  background: white;  
  border-radius: 9px; 
  height: 160px;
  width: 260px;
}

</style>