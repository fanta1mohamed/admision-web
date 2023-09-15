<template>

    <!-- <a-card class="ml-4 mr-5" style="border: 1px solid #d9d9d9; border-radius: 12px;"> -->
        <a-card class="" style="margin: -15px; border: 0px solid #d9d9d9; border-radius: 12px;">
        <!-- Head -->
        <div class="flex justify-between pl-0 pr-3 items-center" style="height: 48px; width: 100%; margin-left: -8px;"> 
            <div><h1 style="font-weight: bold; font-size: 1.2rem;">{{ seleccionado }} de postulantes</h1></div>
            <div class="flex">
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
            <Pie :data="dataConvertida" :options="options" />
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
//import jsonToExcel from 'json-as-xlsx';
//import  json2excel  from 'json-as-xlsx'
//import xlsx from 'json-as-xlsx'
//import headerImage from '../../../../../assets/imagenes/logotiny.png'; // Importa la imagen como un módulo

import * as XLSX from 'xlsx';

// Ruta de la imagen para el encabezado
const headerImage = '../../../../../../imagenes/logotiny.png';

// Datos de ejemplo
// const data = [
//   { nombre: 'Producto 1', cantidad: 10 },
//   { nombre: 'Producto 2', cantidad: 20 },
//   { nombre: 'Producto 3', cantidad: 15 },
// ];

// Función para exportar a Excel
const exportToExcel = () => {
  // Crear un libro de Excel
  const workbook = XLSX.utils.book_new();

  // Agregar una hoja al libro
  const worksheet = XLSX.utils.json_to_sheet(datos.value);

  // Agregar una imagen al encabezado
  const img = new Image();
  img.src = headerImage;
  const canvas = document.createElement('canvas');
  canvas.width = 100; // Ancho de la imagen
  canvas.height = 50; // Altura de la imagen
  const ctx = canvas.getContext('2d');
  ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
  const headerImageBase64 = canvas.toDataURL('image/png');
  worksheet['A1'].l = { Target: headerImageBase64, Tooltip: 'Imagen' };

  // Agregar la hoja al libro
  XLSX.utils.book_append_sheet(workbook, worksheet, 'Productos');

  // Crear un archivo Excel
  XLSX.writeFile(workbook, 'productos.xlsx');
};



ChartJS.register(CategoryScale, LinearScale, BarElement, Title, ArcElement, Tooltip, Legend, PointElement, LineElement)

const seleccionado = ref("Genero"); 
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



// const exportToExcel = () => {
//   const headerImages = {
//     // Aquí defines las imágenes y sus ubicaciones en tu proyecto
//     logo: '../../../../../assets/imagenes/logotiny.png',
//   };

//   const header = [
//     {
//       // Definimos un estilo para la celda que contendrá la imagen
//       style: {
//         alignment: {
//           horizontal: 'center',
//           vertical: 'center',
//         },
//       },
//       image: headerImages.logo, // Ruta de la imagen
//       width: 100, // Ancho de la celda
//       height: 50, // Altura de la celda
//     },
//     // Los demás encabezados
//     { label: 'DNI', value: 'dni' },
//     { label: 'Nombre', value: 'dep' },
//     { label: 'Paterno', value: 'prov' },
//     { label: 'Materno', value: 'dist' },
//   ];

//   const data = [
//     {
//       sheet: 'Participantes',
//       columns: header,
//       content: datos.value,
//     },
//   ];

//   const settings = {
//     fileName: 'Lista',
//     extraLength: 3,
//     writeMode: 'writeFile',
//     writeOptions: {},
//     RTL: false,
//   };

//   jsonToExcel(data, settings);
// };










const getGenero = () => { 
    seleccionado.value = 'Genero'
    axios.get('get-inscritos-genero-reporte')
    .then((response) => { 
        datos.value = response.data.datos;
        dataConvertida.value = {
            labels: ['Hombres', 'Mujeres'],
            datasets: [
                {
                    backgroundColor: ['#666666','#20254B'],
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
  maintainAspectRatio: false
}


getResidencia();
</script>
  
  