<template>
    <canvas id="myChart"></canvas>
</template>

<script setup>
import { onMounted } from 'vue';
import Chart from 'chart.js/auto';
const labels = [
  'Ene',
  'Feb',
  'Mar',
  'Abr',
  'May'
]
const data = {
  labels: labels,
  datasets:[
    {
      label: "Prueba 1",
      backgroundColor: '#f4cdFF',
      data:[80,30,40,50, 54],
      borderWidth: 2,
      pointRadius:0,
      tension: 0.5,
      borderColor: 'blue',
    },
    {
      label: "Prueba 2",
      backgroundColor: '#00aae4',
      data:[10,40,40,80, 94],
      borderWidth: 2,
      pointRadius:0,
      tension: 0.5,
      borderColor: '#00aae4',
    }
  ]
}

const config = {
  type: "line",
  data: data,
  options: {}
}

onMounted(() => {
  const myChart = new Chart(
    document.getElementById('myChart'),
    config
  );  
});

</script>