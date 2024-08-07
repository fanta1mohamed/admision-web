<template style="background:pink;">
<Head title="Resultados"/>
<Layout :nombre="props.procceso_seleccionado.nombre">

  <!-- <a-breadcrumb>
    <a-breadcrumb-item >Inicio</a-breadcrumb-item>
    <a-breadcrumb-item><a style="color: #0992e1;" href="">Resultados</a></a-breadcrumb-item>
  </a-breadcrumb> -->

  <a-card>
    <div>
      <h1 style="font-size: 1.7rem;">Resultados del examen</h1>
      <p style="text-align: justify; font-size: 1em;">
        Para consultar la relación de ingresantes del EXAMEN GENERAL 2024-II, 
        haga clic en el botón "Descargar" correspondiente a la fecha de su interés. 
        El archivo se descargará automáticamente, y podrá abrirlo para visualizar el 
        listado de ingresantes ordenado por mérito.
      </p>
    </div>

    <div v-for="documento in documentos" :key="documento" class="flex justify-between p-2" style="border-bottom: solid 1px #d9d9d9;">
      <div class="flex" style="min-height: 30px; align-items: center; ">
        <div class="mr-1" style="width: 30px; min-width: 30px;">
          <img src="../../../../../public/imagenes/iconos/image.png" alt="">
        </div>
        <div>
          <span>{{ documento.nombre }}</span>
        </div>
      </div>

      <div>
        <a-button @click="downloadFile(baseUrl+'/'+documento.url)" style=" height: 36px; background: #088dcf; color: white; border: none;">
          <div class="flex">
            <div class="mr-1"> Descargar</div>
            <div style="margin-top: -2px;"> 
              <DownloadOutlined />
            </div>
          </div>          
        </a-button>
      </div>
    </div>


    <div class="mt-6"></div>
    <h1 style="font-size: 1.7rem;">Consultar puntaje</h1>
    <div class="mt-6" style="">
      <div style="margin-top: -10px; text-align: left;">
        <div class="ml-1 mb-2">
          Para consultar el puntaje del EXAMEN GENERAL 2024-II, siga estos pasos:
        </div>
        <div class="ml-4">
          <div class="mb-2">1. Ingrese su DNI en el campo de texto proporcionado.</div>
          <div class="mb-2">2. Ingrese el código mostrado en pantalla.</div>
          <div>3. Haga clic en el botón "Consultar Puntaje".</div>
        </div>
      </div>

      <div class=" flex mt-6 justify-left" style="">
        <div style="width: 100%;">
          <div style="min-width: 360px; max-width: 420px;">
            <a-form
                  ref="formRef" :model="formState"
                  name="inicio_dni"
                >
                <div style="margin-bottom: 7px;"><label>N° Documento</label></div>
                <a-form-item
                    name="dni"
                    :rules="[{ required: true, message: 'Por favor ingresa tu DNI', trigger: 'change' },
                    { min: 8, message: 'El dni debe tener 8 digitos', trigger: 'blur',},]"
                  >
                  <a-input v-model:value="formState.dni" @input="dniInput" :disabled="ingresante" :maxlength="8" placeholder="N° Documento"/>
                </a-form-item>
                <div class="texto-imagen">
                  {{ codigo_aleatorio }}
                </div>
                <div class="overlay"></div>
                <div class="mb-4">
                  <div class="mt-3"><label>Código</label></div>
                    <a-form-item
                      name="codigo_secreto"
                      :rules="[{ required: true, message: 'Ingresa el código del cuadro', trigger: 'change' }, 
                        { min: 6, message: 'El ubigeo debe tener 6 caracteres', trigger: 'blur',},
                        { validator: validateCodigoSecreto, trigger: 'change' }
                        ]"
                      >
                      <a-input v-model:value="formState.codigo_secreto" :maxlength="6" placeholder="Ingresa el codigo"/>
                    </a-form-item>
                  </div>
                <div class="mt-4" style="display: flex; justify-content: left;">
                  <a-button type="primary" v-if="codigo_aleatorio !== formState.codigo_secreto"  disabled>CONSULTAR PUNTAJE</a-button>
                  <a-button @click="getPuntaje()" style="background: goldenrod; border: none; color: white;" v-if="formState.codigo_secreto === codigo_aleatorio">CONSULTAR PUNTAJE</a-button>
                </div>
              </a-form>
          </div>
        </div>
      </div>

    </div>

    <div v-if="resultados.length > 0 " class="mt-6">
        <a-table 
        :columns="columnsResultados" 
        :data-source="resultados"
        :pagination="false"
        size="small"
        > 
        <template #bodyCell="{ column, index, record }" >
            <template v-if="column.dataIndex === 'puntaje'">
                <div>
                    {{ record.puntaje }}
                    <!-- {{  record.puntaje.toFixed(3) }} -->
                </div>
            </template>
            <template v-if="column.dataIndex === 'fecha'">
                <div>
                    {{  voltear(record.fecha) }}
                </div>
            </template>

            <template v-if="column.dataIndex === 'condicion'">
                <div v-if="record.condicion == 'SI'">
                    <a-tag color="blue"> Ingresó </a-tag>
                </div>
                <div v-if="record.condicion == 'NO'">
                    <div class="flex justify-center">
                        <a-tag color="orange"> No ingresó </a-tag>
                    </div>
                </div>
                <div v-if="record.condicion == 'CL'">
                    <div class="flex justify-center">
                        <a-tag color="yellow"> Clasificado </a-tag>
                    </div>
                </div>
            </template>
        </template>
      </a-table> 

    </div>

    <div v-if="ingresante === 1" class="mt-6">
      <a-alert
          message="! MUY IMPORTANTE !"
          description="Los postulantes que hayan alcanzado una vacante deberán subir su Certificado de Estudios y DNI en formato PDF con un peso max. de 2mb"
          type="info">
      </a-alert>
      <div class="mt-6"></div>
      <h1 style="font-size: 1.7rem;">Subir archivos </h1>
      <div>
        <Certificado :id_proceso="props.procceso_seleccionado.id" :dni="formState.dni"/>
      </div>
      <div class="mt-2">
        <Dni :id_proceso="props.procceso_seleccionado.id" :dni="formState.dni"/>
      </div>
    </div>

  </a-card>

</Layout>

</template>
<script setup> 
import Layout from '@/Layouts/LayoutPuntaje.vue'
import { defineProps, watch, reactive, ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import { notification } from 'ant-design-vue';
import Certificado from './components/certificado.vue';
import Dni from './components/dni.vue';
import { DownloadOutlined } from '@ant-design/icons-vue';
const baseUrl = window.location.origin;

const props = defineProps(['procceso_seleccionado']);
const codigo_secreto = ref("");

const formRef = ref();
const formState = reactive({ dni: '', codigo_secreto: '', });
const dniInput = (event) => { formState.dni = event.target.value.replace(/\D/g, ''); };


const getPuntaje = async () => {
  
  ingresante.value = 0;
  try {
    const res = await axios.post(`/get-puntajes-proceso/`, {
      dni: formState.dni,
      id_proceso: props.procceso_seleccionado.id
    });

    if (res.data.estado == false) {
        notificacion("error", "No se han encontrado datos en este proceso", "");
        formState.dni = '';
        resultados.value = [];
    }else{
      if (res.data && res.data.datos) {
        resultados.value = res.data.datos;
        const ingreso = resultados.value.some(item => item.condicion === 'SI');
        ingresante.value = ingreso ? 1 : 0;
        if(ingresante.value == 0){
          formState.dni = '';
        }
        getCodigoAleatorio();

      } else {
        resultados.value = [];
      }
    }



  } catch (error) {
    console.error('Error al obtener puntajes:', error);
    resultados.value = [];
  }
};

function validateCodigoSecreto(rule, value) {
  return new Promise((resolve, reject) => {
    if (!value) {
      reject(new Error('Por favor, ingresa el código secreto.'));
    } else if (codigo_aleatorio.value !== codigo_secreto.value) {
      reject(new Error('El código ingresado no coincide.'));
    } else {
      resolve();
    }
  });
} 

const maximos = ref([]);
const getMaximos = async () => {
  let res = await axios.get( "/get-puntajes-maximos-proceso/"+props.procceso_seleccionado.id);
  if(res.data.estado == true){
     maximos.value = res.data.datos;
  }
}

const documentos = ref([]);
const getDocumentos = async () => {
  let res = await axios.post( "/get-documentos-resultados",{id_proceso: props.procceso_seleccionado.id});
  if(res.data.estado == true){
     documentos.value = res.data.datos;
  }
}

const downloadFile = async (path) => {
  try {
    const response = await axios({
      url: path,
      method: 'GET',
      responseType: 'blob',
    });

    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', path.split('/').pop()); // Extraer el nombre del archivo del path
    document.body.appendChild(link);
    link.click();
    link.remove();
  } catch (error) {
    console.error('Error descargando archivo:', error);
  }
};

getMaximos();
getDocumentos();

const codigo_aleatorio = ref(null);

const getCodigoAleatorio = async ( ) => {
  let res = await axios.get("/generar-captcha");
  codigo_aleatorio.value = res.data.captcha;
}


const notificacion = (type, titulo, mensaje) => {
  notification[type]({
    message: titulo,
    description: mensaje,
  });
};

const resultados = ref([]);
const ingresante = ref(null);

const columnsResultados = [
    { title: 'Nombre', dataIndex: 'nombres'},    
    { title: 'Programa', dataIndex:'programa', responsive: ['sm'], },
    { title: 'Puntaje', dataIndex:'puntaje', align:'center'},
    { title: 'Vocacional', dataIndex:'puntaje_vocacional', align:'center'},
    { title: 'Fecha', dataIndex: 'fecha', align:'center'},
    { title: 'Condición', dataIndex: 'condicion', align:'center'},
];

function voltear(fecha) {
  return fecha.split('-').reverse().join('-');
}





getCodigoAleatorio();

</script>

<style>
.texto-imagen {
    font-size: 2em;
    color: gray;
    font-family: 'Consolas', monospace;
    letter-spacing: 1rem;
    background-size: cover;
    background-clip: text;
    -webkit-background-clip: text;
    display: inline-block;
    user-select: none;
    -webkit-user-select: none; 
    -moz-user-select: none;
    -ms-user-select: none;
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #ffffff00; 
    pointer-events: none;
}
.example{
  color: #088dcf;
  color: #0c428d;
  color: #0e500e;
  color: #2e2e2e;
}
</style>