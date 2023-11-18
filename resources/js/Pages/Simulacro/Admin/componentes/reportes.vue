<template>
    <a-card class="" style="margin: -25px; border: 0px solid #d9d9d9; border-radius: 12px;">
        <!-- Head -->
        <div class="flex justify-between pl-0" style="height: 38px; width: 100%; margin-left: 0px;"> 
            <div style=""><h1 style="font-weight: bold; font-size: 1.2rem;">Rep. {{ seleccionado }}</h1></div>
            <div class="flex justify-end">
                <a-dropdown>
                    <template #overlay>
                        <a-menu>
                        <a-menu-item @click="getGenero()">Genero</a-menu-item>
                        <a-menu-item @click="getEdad()" >Edad</a-menu-item>
                        <a-menu-item @click="getResidencia()">Residencia</a-menu-item>
                        <a-menu-item @click="getProcedencia()">Procedencia</a-menu-item>
                        <a-menu-item @click="getEgreso()">Año Egreso</a-menu-item>
                        <a-menu-item @click="getDiscapacidad()">Discapacidad</a-menu-item>
                        <a-menu-item @click="getDocumento()">Tipo Documento</a-menu-item>
                        <a-sub-menu key="sub1" title="Colegio">
                            <a-menu-item @click="getColegio()">Colegio</a-menu-item>
                            <a-menu-item @click="getProcedenciaColegio()">Procedencia</a-menu-item>
                            <a-menu-item @click="getTipoColegio()">Tipo de colegio</a-menu-item>
                        </a-sub-menu>
                        </a-menu>
                    </template>
                    <a-button>
                        {{ seleccionado }}
                        <DownOutlined />
                    </a-button>
                </a-dropdown>
                <div>
                    <a-button type="" style="background: green; color:white;" @click="exportToExcel" :size="small">
                        <template #icon>
                        <DownloadOutlined />
                        </template>
                    </a-button>
                </div>
            </div>
        </div>

        <!-- Body -->
        <!-- <div>
            <Pie :data="datax" :options="options" />
        -->
        <!-- <pre>
            {{ data }}
        </pre> -->

        <div v-if="dataConvertida != null && seleccionado =='Genero'">
            <Pie :data="dataConvertida" :options="options" style="width: 185%; height: 185%;" />
        </div>

        <div v-if=" dataEdades != null && seleccionado =='Edad'">
            <Bar :data="dataEdades" :options="options" />
        </div>
        <!-- {{ data }} -->

        <div v-if="dataEgreso != null && seleccionado =='Egreso'">
            <Bar :data="dataEgreso" :options="options" />
        </div>

        <div v-if="dataDocumentos != null && seleccionado =='Tipo Documento'">
            <Pie :data="dataDocumentos" :options="options" />
        </div>


        <div v-if="dataResidencia != null && seleccionado =='Residencia'">
            <Line :data="dataResidencia" :options="options" />
        </div>


        <div v-if="dataProcedencia != null && seleccionado =='Procedencia'">
            <Line :data="dataProcedencia" :options="options" />
        </div>

        <div v-if="dataDiscapacidad != null && seleccionado =='Discapacidad'">
            <Pie :data="dataDiscapacidad" :options="options" />
        </div>

        <div v-if="dataColegios != null && seleccionado =='Colegio'">
            <Bar :data="dataColegios" :options="options" />
        </div>

        <div v-if="dataTipoColegio != null && seleccionado =='Tipo Colegio'">
            <Bar :data="dataTipoColegio" :options="options" />
        </div>
        
        <div v-if="dataProcedenciaColegio != null && seleccionado =='Procedencia Colegio'">
            <Bar :data="dataProcedenciaColegio" :options="options" />
        </div>

        <!-- <pre>
            {{ dataResidencia }}
        </pre> -->

    </a-card>

</template>
<script setup>
import { DownOutlined, DownloadOutlined } from '@ant-design/icons-vue';
import {ref} from 'vue';
import { Chart as ChartJS, ArcElement, Tooltip, Legend, BarElement, CategoryScale, Title, LinearScale, PointElement, LineElement } from 'chart.js'
import { Pie, Bar, Line } from 'vue-chartjs'
import * as XLSX from 'xlsx';
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, ArcElement, Tooltip, Legend, PointElement, LineElement)


const seleccionado = ref("Genero");     

const exportToExcel = () => {
  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.json_to_sheet(datos.value);
  XLSX.utils.book_append_sheet(workbook, worksheet, seleccionado.value);
  XLSX.writeFile(workbook, seleccionado.value+'.xlsx');
};




const datos = ref(null);
const dataEdades = ref(null);
const dataEgreso = ref(null);
const dataResidencia = ref(null);
const dataProcedencia = ref(null);
const dataDocumentos = ref(null);
const dataDiscapacidad = ref(null);
const dataColegios = ref(null);
const dataTipoColegio = ref(null);
const dataProcedenciaColegio = ref(null);


const header = ['nombre', 'cantidad'];
const footer = [ { Fecha: '2023-09-14', 'Ariel luque': '' },];

const excelData = [
//   { ...data },
  { ...header },
  { ...footer },
];

const getGenero = () => { 
    seleccionado.value = 'Genero'
    axios.get('/simulacro/get-inscritos-genero-reporte')
    .then((response) => { 
        datos.value = response.data.datos;
        dataConvertida.value = {
            labels: ['Hombres', 'Mujeres'],
            datasets: [
                {
                    backgroundColor: ['#A2DED0','#FFD3E4'],
                    data: response.data.datos.map(item => item.cant)
                }
            ]
        };
    })
    .catch((error) => {  console.error('Error al realizar la solicitud:', error); });
}

const getEdad = () => {
    seleccionado.value = 'Edad' 
    axios.get('get-inscritos-edad-reporte')
    .then((response) => {
        datos.value = response.data.datos;
        dataEdades.value = {
            labels:response.data.datos.map(item => item.edad),
            datasets: [
                {
                    label: 'Edad del postulante',
                    backgroundColor: ['#20254B'],
                    data: response.data.datos.map(item => item.cantidad)
                    }
            ]
        }
    })
    .catch((error) => {
        console.error('Error al realizar la solicitud:', error);
    });
}

const getResidencia = () => {
    seleccionado.value = 'Residencia' 
    axios.get('get-inscritos-residencia-reporte')
    .then((response) => {
        datos.value = response.data.datos;
        dataResidencia.value = {
            labels:response.data.datos.map(item => item.dist),
            datasets: [
                {
                    label: 'Distritos',
                    backgroundColor: ['#20254B'],
                    data: response.data.datos.map(item => item.cant)
                    }
            ]
        }
    })
    .catch((error) => {
        console.error('Error al realizar la solicitud:', error);
    });
}

const getProcedencia = () => { 
    seleccionado.value = 'Procedencia' 
    axios.get('get-inscritos-procedencia-reporte')
    .then((response) => {
        datos.value = response.data.datos;
        dataProcedencia.value = {
            labels:response.data.datos.map(item => item.dist),
            datasets: [
                {
                    label: 'Distritos',
                    backgroundColor: ['#20254B'],
                    data: response.data.datos.map(item => item.cant)
                    }
            ]
        }
    })
    .catch((error) => {
        console.error('Error al realizar la solicitud:', error);
    });
}

const getEgreso = () => {
    seleccionado.value = 'Egreso' 
    axios.get('get-inscritos-egreso-reporte')
    .then((response) => {
        datos.value = response.data.datos;
        dataEgreso.value = {
            labels:response.data.datos.map(item => item.egreso),
            datasets: [
                {
                    label: 'Año de Egreso',
                    backgroundColor: ['#20254B'],
                    data: response.data.datos.map(item => item.cant)
                }
            ]
        }
    })
    .catch((error) => {
        console.error('Error al realizar la solicitud:', error);
    });
}
const getDiscapacidad = () => { 
    seleccionado.value = 'Discapacidad'
    axios.get('get-inscritos-discapacidad-reporte')
    .then((response) => {
        datos.value = response.data.datos;
        dataDiscapacidad.value = {
            labels: ['No discapacitado', 'Discapacitado'],
            datasets: [
                {
                    backgroundColor: ['#c4c4c4','#20254B'],
                    data: response.data.datos.map(item => item.cant)
                }
            ]
        };
    })
    .catch((error) => {
        console.error('Error al realizar la solicitud:', error);
    });
}

const getDocumento = () => { 
    seleccionado.value = 'Tipo Documento' 
    axios.get('get-inscritos-tipo-documento-reporte')
    .then((response) => {
        datos.value = response.data.datos;
        dataDocumentos.value = {
            labels:response.data.datos.map(item => item.tipo_doc),
            datasets: [
                {
                    backgroundColor: ['#20254B','#f3f3f3','green'],
                    data: response.data.datos.map(item => item.cant)
                    }
            ]
        }
    })
    .catch((error) => {
        console.error('Error al realizar la solicitud:', error);
    });
}
const getProcedenciaColegio = () => { 
    seleccionado.value = 'Procedencia Colegio'
    axios.get('get-inscritos-procedencia-colegio-reporte')
    .then((response) => {
        datos.value = response.data.datos;
        dataProcedenciaColegio.value = {
            labels:response.data.datos.map(item => item.dist),
            datasets: [
                {
                    label: 'Distritos',
                    backgroundColor: ['#20254B'],
                    data: response.data.datos.map(item => item.cant)
                    }
            ]
        }
    })
    .catch((error) => {
        console.error('Error al realizar la solicitud:', error);
    });
}
const getColegio = () => { 
    seleccionado.value = 'Colegio' 
    axios.get('get-inscritos-colegio-reporte')
    .then((response) => {
        datos.value = response.data.datos;
        dataColegios.value = {
            labels:response.data.datos.map(item => item.cole),
            datasets: [
                {
                    label: 'Colegios',
                    backgroundColor: ['#CCCCCC','#20254B','#COCOCO','#6699CC','#66CCCC','#9966CC','#66CC99'],
                    data: response.data.datos.map(item => item.cant)
                    }
            ]
        }
    })
    .catch((error) => {
        console.error('Error al realizar la solicitud:', error);
    });
}

const getTipoColegio = () => { 
    seleccionado.value = 'Tipo Colegio'
    axios.get('get-inscritos-tipo-colegio-reporte')
    .then((response) => {
        datos.value = response.data.datos;
        dataTipoColegio.value = {
            labels:response.data.datos.map(item => item.tipo_colegio),
            datasets: [
                {
                    label: 'Tipo de Colegio',
                    backgroundColor: ['#CCCCCC','#20254B','#COCOCO','#6699CC','#66CCCC','#9966CC','#66CC99'],
                    data: response.data.datos.map(item => item.cant)
                    }
            ]
        }
    })
    .catch((error) => {
        console.error('Error al realizar la solicitud:', error);
    });
}

const dataConvertida = ref(null);

const options = {
  labels:"Reporte",
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false,
    },
    tooltip: {
      enabled: true,
      callbacks: {
        label: (context) => {
          const label = context.label || '';
          if (context.parsed && label) {
            const value = context.parsed;
            return `${label}: ${value}`;
          }
          return '';
        },
      },
    },
    segmentLabel: {
      render: 'percentage',
      fontSize: 16,
      fontStyle: 'bold',
      fontColor: 'white',
      arc: true,
    },
  },
}

getGenero()
// getResidencia();
</script>
  
  