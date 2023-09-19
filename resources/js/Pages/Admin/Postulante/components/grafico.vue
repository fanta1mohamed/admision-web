<template>
    <div v-if="data != null">
        <Radar :data="data" :options="options" />
    </div>
</template>
<script setup>
import { DownOutlined, DownloadOutlined } from '@ant-design/icons-vue';
import {ref} from 'vue';
import {Chart as ChartJS,RadialLinearScale,PointElement,LineElement,Filler,Tooltip,Legend} from 'chart.js'
ChartJS.register(RadialLinearScale,PointElement,LineElement,Filler,Tooltip,Legend)
import { Radar } from 'vue-chartjs'

const data = {
  labels: ['Civica','Historia','Lenguaje','Física y Q.','Matemática','Educ. trabajo'],
  datasets: [
    {
      label: 'CEPREUNA 2023-II',
      backgroundColor: 'rgba(179,181,198,0.2)',
      borderColor: 'rgba(179,181,198,1)',
      pointBackgroundColor: 'rgba(179,181,198,1)',
      pointBorderColor: '#fff',
      pointHoverBackgroundColor: '#fff',
      pointHoverBorderColor: 'rgba(179,181,198,1)',
      data: [20, 10, 14, 5, 6, 10 ]
    },
    {
      label: 'GENERAL 2023-II',
      backgroundColor: 'rgba(135,255,132,0.2)',
      borderColor: 'rgba(155,255,132,1)',
      pointBackgroundColor: 'rgba(155,255,232,1)',
      pointBorderColor: '#fff',
      pointHoverBackgroundColor: '#fff',
      pointHoverBorderColor: 'rgba(255,255,132,1)',
      data: [16, 14, 14, 9, 16, 10]
    }
  ]
}

const options = {
  responsive: true,
  maintainAspectRatio: false
}
</script>
  
  