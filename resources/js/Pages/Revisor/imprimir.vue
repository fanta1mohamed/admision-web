<template>
<Head title="Revisión de documentos"/>
<AuthenticatedLayout>
  <div>
    <a-card style="background: white;" class="mb-0 p-0" >
      <a-row :gutter="16" class="mb-3">
        <a-col :span="24" :sm="24" :md="24" :lg="24" style="display:flex; justify-content: end; align-items: end;" >
          
          <div>

            <!-- <div> {{ datosOTI }} </div> -->
          <!-- <div> {{ datosOTI }} </div>
          {{ ingresante }} -->

          <label style="margin-right: 10px;"> Buscar:</label>
          <a-auto-complete
            v-model:value="dniseleccionado"
            :options="postulantes"
            style="width: 300px"
            @select="onSelect"
            @search="onSearch"
          >
          <a-input
            ref="dniInput"
            placeholder="Buscar"
            v-model:value="dni"
            @keypress="handleKeyPress"
          />
          <template #suffix>
            <credit-card-outlined />
          </template>
          <template #option="{ value: val, label:lab }" style="background-color: blue;">
            <div style="height: 34px;">
              <div><span style="font-weight: 700; color: black; font-size: .7rem;">{{ val }}</span></div>
              <div style="margin-top: -10px;"><span style="font-size: .8rem; text-transform: uppercase;">{{ lab }}</span></div>
            </div>
          </template>
          </a-auto-complete>
        </div>
        </a-col>
      </a-row>

      <a-row >
        <a-col :span="24" :sm="24" :md="24" :lg="24">
          <div style="height: 40px; margin-left: -10px;">
            <a-checkbox-group v-model:value="checkedList" >
              <a-checkbox v-for="(option, index) in requisitos" :key="option.value" :value="option.value" :class="{ 'first-item': index === 0 }" class="checkbox-item">
               <span style="font-weight: bold;">{{ option.label }}</span> 
              </a-checkbox>
            </a-checkbox-group>
          </div>
        </a-col>
      </a-row>
      <a-row :gutter="16">
        <a-col :span="24" :sm="24" :md="24" :lg="24" style="border: 1px solid #d9d9d9; min-width: 600px;" class="m-0 p-0">
          <div style="margin-right: -8px; margin-left: -8px; min-width: 600px;">

            <a-tabs v-model:activeKey="activeKey" type="card" style="">

              <a-tab-pane key="7" tab="Datos" class="pl-2 pr-2">
                <div class="flex mb-3" style="align-items:center; justify-content: center; width: 100%; height: 40px; background: #cdcdcd4F; border-radius: 7px;">
                  <span style="font-weight: bold; font-size: 1.2rem;">Datos personales</span>
                </div>
                <div v-if="ingresante">
                  <a-card >
                  <a-row :gutter="16">
                      <!-- Columna izquierda -->
                      <a-col :xs="24" :sm="12" :md="12" :lg="12" :xl="12">
                        <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                          <label>Primer Apellido</label>
                          <a-input v-model:value="ingresante.primer_apellido" />
                        </a-form-item>

                        <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                          <label>Segundo Apellido</label>
                          <a-input v-model:value="ingresante.segundo_apellido" />
                        </a-form-item>

                        <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                          <label>Prenombres</label>
                          <a-input v-model:value="ingresante.nombres"/>
                        </a-form-item>
                      </a-col>

                      <!-- Columna derecha -->
                      <a-col :xs="24" :sm="12" :md="12" :lg="5" :xl="5">
                        <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                          <label>Tipo documento</label>
                          <div>
                            <a-select
                              ref="tipodocumento"
                              v-model:value="ingresante.tipo_doc"
                              style="width: 100%"
                            >
                              <a-select-option :value="1">DNI</a-select-option>
                              <a-select-option :value="2">Carné Ext.</a-select-option>
                            </a-select>
                          </div>

                        </a-form-item>

                        <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                          <label>Sexo</label>
                          <div>
                            <a-select
                              ref="sexoigresante"
                              v-model:value="ingresante.sexo"
                              style="width: 100%"
                            >
                              <a-select-option value="1">MASCULINO</a-select-option>
                              <a-select-option value="2">FEMENINO</a-select-option>
                            </a-select>
                          </div>
                        </a-form-item>

                        <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                          <label>Fecha de nacimiento</label>
                          <a-date-picker placeholder="Selecciona tu fecha de nacimiento" style="width: 100%" v-model:value="ingresante.fec_nacimiento" format='DD/MM/YYYY' />
                        </a-form-item>
                      </a-col>

                      <a-col :xs="24" :sm="12" :md="12" :lg="2" :xl="2">
                        <div class="mb-3">
                          <img :src="'https://inscripciones.admision.unap.edu.pe/fotos/huella/'+ingresante.nro_doc+'x.jpg'" height="80"/>
                          <div class="flex justify-center"> H. Der.</div>
                        </div>

                        <div>
                          <img :src="'https://inscripciones.admision.unap.edu.pe/fotos/huella/'+ingresante.nro_doc+'.jpg'" height="80" />
                          <div class="flex justify-center"> H. Izq.</div>
                        </div>
                      </a-col>
                      <a-col :xs="24" :sm="12" :md="12" :lg="5" :xl="5">
                        <img :src="'https://inscripciones.admision.unap.edu.pe/fotos/inscripcion/'+ingresante.nro_doc+'.jpg'" width="250" />
          
                      </a-col>

                      <a-col :xs="24" :sm="12" :md="12" :lg="5" :xl="17">
                        <div class="flex justify-end mb-0">
                          <a-button class="btn-actualizar" @click="actualizar()"><span style="font-weight:bold;"> Actualizar Datos </span> </a-button>
                        </div>
                      </a-col>

                      <a-col :xs="24" :sm="12" :md="12" :lg="8" :xl="7">
                        <div class="flex justify-center" style="margin-top: -15px; "> <span style="padding: 0px 2px; font-size: 2.6rem; margin-top:2px; font-weight: bold;">DNI {{ ingresante.nro_doc }}</span></div>
                      </a-col>
                      
                    </a-row>

                  </a-card>

                  <div v-if="datosOTI !== null" class="flex mb-3 mt-3" style="align-items:center; justify-content: center; width: 100%; height: 40px; background: #cdcdcd4F; border-radius: 7px;">
                    <span v-if="datosOTI.carrera !== ''" style="font-weight: bold; font-size: 1.2rem;">Datos de ingreso anterior</span>
                  </div>
                  <a-card v-if="datosOTI !== null">
                    <a-row :gutter="16" v-if="datosOTI.carrera !== ''" >

                      <a-col :xs="24" :sm="24" :md="24" :lg="12" :xl="12">
                        <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                          <label>Fecha Ingreso</label>
                          <a-input v-model:value="datosOTI.fecha_ingreso" />
                        </a-form-item>
                      </a-col>

                      <a-col :xs="24" :sm="24" :md="24" :lg="12" :xl="12">
                        <a-form-item>
                          <label>Carrera</label>
                          <a-input v-model:value="datosOTI.estado" />
                        </a-form-item>
                      </a-col>
                      
                      <a-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
                        <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                          <label>Programa</label>
                          <a-input v-model:value="datosOTI.carrera" />
                        </a-form-item>
                      </a-col>

                      <!-- <a-col :xs="24" :sm="24" :md="24" :lg="12" :xl="12">
                        <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                          <label>Programa</label>
                          <a-input v-model:value="ingresante.programa" />
                        </a-form-item>
                      </a-col>

                      <a-col :xs="24" :sm="24" :md="24" :lg="12" :xl="12">
                        <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                          <label>Programa</label>
                          <a-input v-model:value="ingresante.programa" />
                        </a-form-item>
                      </a-col> -->

                     </a-row> 
                    </a-card>

                    <div class="flex mb-3 mt-3" style="align-items:center; justify-content: center; width: 100%; height: 40px; background: #cdcdcd4F; border-radius: 7px;">
                      <span style="font-weight: bold; font-size: 1.2rem;">Datos de ingreso</span>
                    </div>

                    <a-card>
                    <a-row :gutter="16">

                      <a-col :xs="24" :sm="24" :md="24" :lg="12" :xl="12">
                        <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                          <label>Proceso</label>
                          <a-input v-model:value="ingresante.programa" />
                        </a-form-item>
                      </a-col>

                      <a-col :xs="24" :sm="24" :md="24" :lg="12" :xl="12">
                        <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                          <label>Modalidad</label>
                          <a-input v-model:value="ingresante.proceso"/>
                        </a-form-item>
                      </a-col>
                      
                      <a-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
                        <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                          <label>Programa</label>
                          <a-input v-model:value="ingresante.programa" />
                        </a-form-item>
                      </a-col>

                      <a-col :xs="24" :sm="24" :md="24" :lg="12" :xl="12">
                        <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                          <label>Puntaje</label>
                          <a-input v-model:value="ingresante.puntaje" />
                        </a-form-item>
                      </a-col>

                      <a-col :xs="24" :sm="24" :md="24" :lg="12" :xl="12">
                        <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                          <label>Fecha ingreso</label>
                          <a-input v-model:value="ingresante.fecha" />
                        </a-form-item>
                      </a-col>
                    
                    </a-row>  
                    </a-card>


                    <div class="flex mb-3 mt-3" style="align-items:center; justify-content: center; width: 100%; height: 40px; background: #cdcdcd4F; border-radius: 7px;">
                      <span style="font-weight: bold; font-size: 1.2rem;">Correo y codigo</span>
                    </div>

                    <a-card>
                      <a-row :gutter="16">

                        <a-col :xs="24" :sm="24" :md="24" :lg="16" :xl="16">
                          <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                            <label>Correo</label>
                            <a-input v-model:value="datosOTI.correo" />
                          </a-form-item>
                        </a-col>

                        <a-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8">
                          <a-form-item>
                            <label>Codigo</label>
                            <a-input v-model:value="datosOTI.codigo"/>
                          </a-form-item>
                        </a-col>



                      </a-row>
                    </a-card>

                  <!-- {{ ingresante }} -->
                </div>
              </a-tab-pane>

              <a-tab-pane key="2" tab="Voucher" class="pl-2 pr-2">
                <div class="" style="width: 100%; height: 380px;">
                  <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
                    <Vouchers :dni="dniseleccionado"/>
                  </div>
                </div>
              </a-tab-pane>
              <a-tab-pane key="1" tab="Solicitud" class="pl-2 pr-2">
                <div>
                  <div style="width:100%; height:380px; position:relative; overflow:hidden">
                    <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
                      <iframe :src="baseUrl+'/documentos/cepre2023-II/'+dniseleccionado+'/solicitud-1.pdf'" style="top:-54px; position:absolute" width="100%" height="100%" scrolling="yes" frameborder="1" ></iframe>
                    </div>
                </div>
                </div>
              </a-tab-pane>
              <a-tab-pane key="3" tab="Certificado">
                <div style="height:380px;">
                  <div style="width:100%; height:380px; position:relative; overflow:hidden">
                    <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
                      <iframe :src="baseUrl+'/documentos/cepre2023-II/'+dniseleccionado+'/certificado-1.pdf'" style="top:-54px; position:absolute" width="100%" height="470px"   scrolling="yes" frameborder="1" ></iframe>
                    </div>
                  </div>
                </div>
              </a-tab-pane>
              <a-tab-pane key="4" tab="Ex vocacional">
                <div>
                  <div style="width:100%; height:380px; position:relative; overflow:hidden">
                    <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
                      <iframe :src="baseUrl+'/documentos/cepre2023-II/'+dniseleccionado+'/constancia%20vocacional-1.pdf'" style="top:-54px; position:absolute" width="100%" height="470px"   scrolling="yes" frameborder="1" ></iframe>
                    </div>
                  </div>
                </div>
              </a-tab-pane>
              <a-tab-pane key="6" tab="D. Biométricos">
                <div>
                </div>
              </a-tab-pane>
            </a-tabs>

          </div>
        </a-col>
      </a-row>
      <div class="mt-4 flex justify-end" style="margin-right: -10px;">
        <a-button type="primary"  @click="abrirVentana()">Imprimir</a-button>
      </div>

    </a-card>
  </div>
</AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/LayoutDocente.vue'
import { watch, computed, ref, unref } from 'vue';
import { FormOutlined, DeleteOutlined, CreditCardOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';
import Vouchers from './components/voucher.vue'
import dayjs from 'dayjs';
import { format, parseISO } from 'date-fns';
import { es } from 'date-fns/locale';

const baseUrl = window.location.origin;

const dni = ref(null);
const dniseleccionado = ref(null)

const postulantes = ref([])

const numerorandom = ref();

const generateRandomNumber = () => {
 numerorandom.value = Math.floor(Math.random() * 100) + 1;
}

function focusInput() { save() }
const checkedList = ref([]);
const options = [
  { label: 'Solicitud', value: 1 },
  { label: 'Vouchers', value: 2 },
  { label: 'Certificado', value: 3 }
]

const checkAll = ref(true);

const onCheckAllChange = (e) => {
  checkAll.value = e.target.checked;
  checkedList.value = e.target.checked ? options.map((option) => option.value) : [];
};

const onCheckboxChange = (checkedValues) => {
  checkedList.value = checkedValues;
  checkAll.value = checkedValues.length === options.length;
};

const requisitos = ref([]);
const getRequisitos = async () => {
  let res = await axios.get('get-requisitos');
  requisitos.value = res.data.datos;
}

const dniInput = ref(null)
const save = async () => {
  dniInput.value.focus()
  let res = await axios.post('save-requisito',{
    dni: dniseleccionado.value, requisitos: checkedList.value
  });
  dniseleccionado.value = null
  checkedList.value = []
}

const ingresante = ref({
  id:null,
  nro_doc: "",
  tipo_doc: null,
  nombres:null,
  sexo: null,
  fec_nacimiento: null,
  primer_apellido:"",
  segundo_apellido:"",
  proceso:"",
  puntaje:"",
  programa:"",
  fecha:""
})



const getIngresante =  async ( ) => {
  let res = await axios.get(
      "get-ingresante-general/"+dni.value
  );
  ingresante.value.id = res.data.datos.id
  ingresante.value.nro_doc = res.data.datos.nro_doc
  ingresante.value.tipo_doc = res.data.datos.tipo_doc
  ingresante.value.sexo = res.data.datos.sexo
  if(res.data.datos.fec_nacimiento){ ingresante.value.fec_nacimiento = dayjs(res.data.datos.fec_nacimiento) }
  ingresante.value.nombres = res.data.datos.nombres
  ingresante.value.primer_apellido = res.data.datos.primer_apellido
  ingresante.value.segundo_apellido = res.data.datos.segundo_apellido
  ingresante.value.proceso = res.data.datos.proceso
  ingresante.value.puntaje = res.data.datos.puntaje
  ingresante.value.programa = res.data.datos.programa
  if(res.data.datos.fecha){ ingresante.value.fecha = res.data.datos.fecha }
}

const actualizar = async ( ) => { 
  let res = await axios.post(
    "actualizar-ingresante",{
      id: ingresante.value.id,
      tipo_doc: ingresante.value.tipo_doc,
      sexo: ingresante.value.sexo,
      fec_nacimiento: format(new Date(ingresante.value.fec_nacimiento), 'yyyy-MM-dd'),
      nombres: ingresante.value.nombres,
      paterno: ingresante.value.primer_apellido,
      materno: ingresante.value.segundo_apellido
    }
  );
  notificacion(res.data.tipo, res.data.titulo, res.data.mensaje)
  getPostulantesByDni();
} 

const getPostulantes =  async (term = "", page = 1) => {
  let res = await axios.post(
      "get-postulantes?page=" + page,
      { term: dni.value }
  );
  postulantes.value = res.data.datos.data;
}

const getPostulanteRequisitos = async () => {
  checkedList.value = [];
  let res = await axios.post("get-postulante-requisitos",{ dni: dniseleccionado.value });
  if(res.data.estado === true ){
    checkedList.value = JSON.parse(res.data.datos.requisitos);
  }
}

getPostulanteRequisitos()

const getPostulantesByDni = async () => {
  generateRandomNumber()
  let res = await axios.post("get-postulante-dni",{ dni: dniseleccionado.value });
  postulante.value.id = res.data.datos.id_postulante;
  postulante.value.dni_temp = res.data.datos.dni
}

watch(dni, (newValue, oldValue ) => {
  getPostulantes();
})

watch(dniseleccionado, (newValue, oldValue ) => {
    getPostulanteRequisitos();
    getIngresante();
    getCodigo()
})

const abrirVentana = async () => {
  let res = await axios.post("control-biometrico",
  { dni: dniseleccionado.value, codigo: datosOTI.value.codigo, correo: datosOTI.value.correo });
  imprimirPDF(res.data.datos);
}

const datosOTI = ref({
  correo:"",
  codigo:""
});

const getCodigo = async () => {
  let res = await axios.get("get-codigo/"+ dniseleccionado.value);
  datosOTI.value = res.data.datos;
}


const imprimirPDF =  (dnni) => {
    var iframe = document.createElement('iframe');
    iframe.style.display = "none";
    iframe.src = baseUrl+'/documentos/control-biometrico-general/'+dnni+'.pdf';
    document.body.appendChild(iframe);
    iframe.contentWindow.focus();
    iframe.contentWindow.print();
}

getRequisitos()



const notificacion = (type, titulo, mensaje) => {
  notification[type]({
    message: titulo,
    description: mensaje,
  });
};
</script>


<style scoped>
.btn-actualizar{
  background:#224464; 
  color:white; 
  width:100%; 
  height: 38px; 
  /* color:#1c1c8a;  */
  border-radius:5px;
  border:none;
}
.btn-actualizar:active{
  border:none;
  animation-duration: 1.5s;
  background:#2e5c85c9; 
  width:100%; 
  height: 38px; 
  color:white; 
  border-radius:5px;
}
</style>