<template>
<Head title="Revisión de documentos"/>
<AuthenticatedLayout>
  <div>
    <a-card style="background: white;" class="mb-0 p-0" >
      <a-row :gutter="16" class="mb-3">
        <a-col :span="24" :sm="24" :md="24" :lg="24" style="display:flex; justify-content: space-between;">

            <div>
                <a-input
                  placeholder="Ingrese el código secreto"
                  v-model:value="codigo"
                />
            </div>

            <div>
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

                        <a-form-item>
                          <div class="flex">
                            <div style="width:calc(100% - 100px);">
                              <label>Prenombres</label>
                              <a-input v-model:value="ingresante.nombres"/>
                            </div>
                            <div class="ml-3" style="width:100px;">
                              <label>Puesto</label>
                              <a-input v-model:value="ingresante.puesto"/>
                            </div>
                        </div>
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
                          <img :src="baseUrl+'/documentos/6/control_biometrico/huellas/'+ingresante.nro_doc+'.jpg'" height="80"/>
                          <div class="flex justify-center"> H. Der.</div>
                        </div>

                        <div>
                          <img :src="baseUrl+'/documentos/6/control_biometrico/huellas/'+ingresante.nro_doc+'x.jpg'" height="80" />
                          <div class="flex justify-center"> H. Izq.</div>
                        </div>
                      </a-col>
                      <a-col :xs="24" :sm="12" :md="12" :lg="5" :xl="5">
                        <img :src="baseUrl+'/documentos/6/control_biometrico/fotos/'+ingresante.nro_doc+'.jpg'" width="250" />

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

                  <!--
                  {{ anteriores }} -->
                  <div v-if="anteriores[0]" class="flex mb-3 mt-3" :style="anteriores[0] ? 'align-items:center; justify-content: center; width: 100%; height: 40px; background: red; border-radius: 7px;' : 'align-items:center; justify-content: center; width: 100%; height: 40px; background: #cdcdcd4F; border-radius: 7px;'">
                    <span style="font-weight: bold; font-size: 1.2rem;">Datos de ingreso anterior</span>
                  </div>
                  <!-- {{ anteriores }} -->
                  <div v-if="anteriores[0]">
                    <a-card>
                      <div v-for="(ant,index) in anteriores" :key="index" >
                        <Anterior :item="ant" />
                      </div>
                    </a-card>
                  </div>

                    <div class="flex mb-3 mt-3" style="align-items:center; justify-content: center; width: 100%; height: 40px; background: #cdcdcd4F; border-radius: 7px;">
                      <span style="font-weight: bold; font-size: 1.2rem;">Datos de ingreso</span>
                    </div>

                    <a-card>
                    <a-row :gutter="16">

                      <a-col :xs="24" :sm="24" :md="24" :lg="12" :xl="12">
                        <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                          <label>Proceso</label>
                          <a-input v-model:value="ingresante.proceso" />
                        </a-form-item>
                      </a-col>

                      <a-col :xs="24" :sm="24" :md="24" :lg="12" :xl="12">
                        <a-form-item :rules="[{ required: true, message: 'El nombre es obligatorio' }]">
                          <label>Modalidad</label>
                          <a-input v-model:value="ingresante.modalidad"/>
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

                    <div v-if="anteriores[0]" class="flex mb-3 mt-3" style="align-items:center; justify-content: center; width: 100%; height: 40px; background: #cdcdcd4F; border-radius: 7px;">
                      <span style="font-weight: bold; font-size: 1.2rem;">Segunda Carrera</span>
                    </div>

                    <a-card class="mb-3" style="margin-bottom: 10px;">

                      <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
                          <a-form-item>
                            <div style="text-align:center;">
                              <!-- <label>INDICAR QUE USE EL CORREO INSTITUCIONAL DE SU ANTERIOR INGRESO</label> -->
                            </div>
                          </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
                          <a-form-item>
                            <div class="mb-2">
                              <a-label>SEGUNDA CARRERA</a-label>
                              <a-select
                                ref="select"
                                v-model:value="n_carrera"
                                style="width: 100%"
                              >
                                <a-select-option :value="1">SI</a-select-option>
                                <a-select-option :value="0">NO</a-select-option>
                              </a-select>
                            </div>
                          </a-form-item>
                        </a-col>
                      </a-row>
                    </a-card>

                  <!-- {{ ingresante }} -->
                </div>
              </a-tab-pane>

              <!-- <a-tab-pane key="2" tab="DNI" class="pl-2 pr-2">

              </a-tab-pane> -->
              <a-tab-pane key="1" tab="Solicitud" class="pl-2 pr-2">
                <div>
                  <div style="width:100%; height:380px; position:relative; overflow:hidden">
                    <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
                      <iframe :src="baseUrl+'/documentos/6/preinscripcion/solicitudes/'+dniseleccionado+'.pdf'" style="top:-54px; position:absolute" width="100%" height="100%" scrolling="yes" frameborder="1" ></iframe>
                    </div>
                </div>
                </div>
              </a-tab-pane>
              <a-tab-pane key="4" tab="Const. Inscripcion">
                <div>
                  <div style="width:100%; height:380px; position:relative; overflow:hidden">
                    <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
                      <iframe :src="baseUrl+'/documentos/6/inscripciones/constancias/'+dniseleccionado+'.pdf'" style="top:-54px; position:absolute" width="100%" height="470px"   scrolling="yes" frameborder="1" ></iframe>
                    </div>
                  </div>
                </div>
              </a-tab-pane>
              
              <a-tab-pane key="6" tab="D. Biométricos">
              <div>
                <div class="flex justify-center mb-6" style="width: 100%;">
                  <div class="flex justify-center " style="text-align:center;">
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="12" :md="8" :lg="12">
                          <div class="p-6">
                            <img :src="baseUrl+'/documentos/6/inscripciones/fotos/'+dniseleccionado+'.jpg'"/>
                            <div class="flex justify-center"> Foto Inscripción.</div>
                          </div>
                        </a-col>                        
                        <a-col :xs="24" :sm="12" :md="8" :lg="12">
                          <div class="p-6">
                            <img :src="baseUrl+'/documentos/6/control_biometrico/fotos/'+dniseleccionado+'.jpg'"/>
                            <div class="flex justify-center"> Foto Biometrico.</div>
                          </div>
                        </a-col>
                    </a-row>
                  </div>
                </div>



                <div class="flex justify-center mb-6" style="width: 100%;">
                  <div class="flex justify-center " style="text-align:center;">
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="12" :md="8" :lg="5">
                          <div>
                            <img :src="baseUrl+'/documentos/6/inscripciones/huellas/'+dniseleccionado+'.jpg'"/>
                            <div class="flex justify-center"> H. inscripción</div>
                          </div>
                        </a-col>                        
                        <a-col :xs="24" :sm="12" :md="8" :lg="5">
                          <div>
                            <img :src="baseUrl+'/documentos/6/inscripciones/huellas/'+dniseleccionado+'x.jpg'"/>
                            <div class="flex justify-center"> H. inscripción</div>
                          </div>
                        </a-col>
                        <a-col :xs="24" :sm="12" :md="8" :lg="4">
                          <div>
                            <img :src="baseUrl+'/documentos/6/examen/huellas/'+dniseleccionado+'.jpg'"/>
                            <div class="flex justify-center"> H. Examen</div>
                          </div>
                        </a-col>
                        <a-col :xs="24" :sm="12" :md="8" :lg="5">
                          <div>
                            <img :src="baseUrl+'/documentos/6/control_biometrico/huellas/'+dniseleccionado+'.jpg'"/>
                            <div class="flex justify-center"> H. Biometrico</div>
                          </div>
                        </a-col>
                        <a-col :xs="24" :sm="12" :md="8" :lg="5">
                          <div>
                            <img :src="baseUrl+'/documentos/6/control_biometrico/huellas/'+dniseleccionado+'x.jpg'"/>
                            <div class="flex justify-center"> H. Biometrico</div>
                          </div>
                        </a-col>
                    </a-row>
                  </div>
                </div>

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

    <div style="max-width:100%;">
      <div style="max-width:1000px">

      </div>
    </div>
    <a-modal v-model:visible="modal" :closable="false" :maskClosable="false" style="width:900px;" centered >

      <div class="flex justify-center">
        <span style="font-size:1.4rem; font-weight:bold;">Información del postulante</span>
      </div>

      <div style="margin-top:20px;">
        <h2>DNI</h2>
      </div>
      <div>
        <div style="width:100%; height:380px; position:relative; overflow:hidden">
          <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
            <iframe :src="baseUrl+'/documentos/6/inscripciones/dnis/'+codigo+dniseleccionado+'.pdf'" style="top:-54px; position:absolute" width="100%" height="470px"   scrolling="yes" frameborder="1" ></iframe>
          </div>
        </div>
      </div>

      <div style="margin-top:20px;">
        <h2>Certificado de estudios</h2>
      </div>
      <div>
        <div style="width:100%; height:380px; position:relative; overflow:hidden">
          <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
            <iframe :src="baseUrl+'/documentos/6/inscripciones/certificados/'+dniseleccionado+codigo+'.pdf'" style="top:-54px; position:absolute" width="100%" height="470px"   scrolling="yes" frameborder="1" ></iframe>
          </div>
        </div>
      </div>


      <div style="margin-top:20px;">
        <h2>Solicitud de inscripción</h2>
      </div>
      <div>
          <div style="width:100%; height:400px; position:relative; overflow:hidden">
            <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
              <iframe :src="baseUrl+'/documentos/6/preinscripcion/solicitudes/'+dniseleccionado+'.pdf'" style="top:-54px; position:absolute" width="100%" height="100%" scrolling="yes" frameborder="1" ></iframe>
            </div>
          </div>
      </div>

      <div style="margin-top:-20px;">
        <h2>Constancia de inscripción</h2>
      </div>
      <div>
        <div style="width:100%; height:380px; position:relative; overflow:hidden">
          <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
            <iframe :src="baseUrl+'/documentos/6/inscripciones/constancias/'+dniseleccionado+'.pdf'" style="top:-54px; position:absolute" width="100%" height="470px"   scrolling="yes" frameborder="1" ></iframe>
          </div>
        </div>
      </div>

      <div class="mt-12">
        <h2>Huellas y fotos del postulante</h2>
      </div>
      <div>
        <div class="flex justify-center mb-6" style="width: 100%;">
          <div class="flex justify-center " style="text-align:center;">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="12" :md="8" :lg="12">
                  <div class="p-6">
                    <img :src="baseUrl+'/documentos/6/inscripciones/fotos/'+dniseleccionado+'.jpg'"/>
                    <div class="flex justify-center"> Foto Inscripción.</div>
                  </div>
                </a-col>                        
                <a-col :xs="24" :sm="12" :md="8" :lg="12">
                  <div class="p-6">
                    <img :src="baseUrl+'/documentos/6/control_biometrico/fotos/'+dniseleccionado+'.jpg'"/>
                    <div class="flex justify-center"> Foto Biometrico.</div>
                  </div>
                </a-col>
            </a-row>
          </div>
        </div>
      </div>


      <div class="flex justify-center mb-6" style="width: 100%;">
        <div class="flex justify-center " style="text-align:center;">
          <a-row :gutter="16">
              <a-col :xs="24" :sm="12" :md="8" :lg="5">
                <div>
                  <img :src="baseUrl+'/documentos/6/inscripciones/huellas/'+dniseleccionado+'.jpg'"/>
                  <div class="flex justify-center"> H. inscripción</div>
                </div>
              </a-col>                        
              <a-col :xs="24" :sm="12" :md="8" :lg="5">
                <div>
                  <img :src="baseUrl+'/documentos/6/inscripciones/huellas/'+dniseleccionado+'x.jpg'"/>
                  <div class="flex justify-center"> H. inscripción</div>
                </div>
              </a-col>
              <a-col :xs="24" :sm="12" :md="8" :lg="4">
                <div>
                  <img :src="baseUrl+'/documentos/6/examen/huellas/'+dniseleccionado+'.jpg'"/>
                  <div class="flex justify-center"> H. Examen</div>
                </div>
              </a-col>
              <a-col :xs="24" :sm="12" :md="8" :lg="5">
                <div>
                  <img :src="baseUrl+'/documentos/6/control_biometrico/huellas/'+dniseleccionado+'.jpg'"/>
                  <div class="flex justify-center"> H. Biometrico</div>
                </div>
              </a-col>
              <a-col :xs="24" :sm="12" :md="8" :lg="5">
                <div>
                  <img :src="baseUrl+'/documentos/6/control_biometrico/huellas/'+dniseleccionado+'x.jpg'"/>
                  <div class="flex justify-center"> H. Biometrico</div>
                </div>
              </a-col>
          </a-row>
        </div>
      </div>    

      <template #footer>
        <div class="flex justify-end mr-2 mb-3">
          <a-button type="primary" style="width:140px; background:#0a3d5a" @click="modal = false">Acpetar</a-button>
        </div>

      </template>

    </a-modal>
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
import Anterior from './components/anteriores.vue'
import dayjs from 'dayjs';
import { format, parseISO } from 'date-fns';
import { es } from 'date-fns/locale';

const baseUrl = window.location.origin;

const dni = ref(null);
const dniseleccionado = ref("")
const modal = ref(false);
const codigo = ref("");

const postulantes = ref([])
const anteriores = ref([]);
const n_carrera = ref(0)

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
  modalidad:"",
  puntaje:"",
  programa:"",
  fecha:"",
  puesto:""
})

const getIngresante =  async ( ) => {

  let res = await axios.get( "get-ingresante-general/"+dni.value );
  ingresante.value.id = res.data.datos.id
  ingresante.value.nro_doc = res.data.datos.nro_doc
  ingresante.value.tipo_doc = res.data.datos.tipo_doc
  ingresante.value.sexo = res.data.datos.sexo
  if(res.data.datos.fec_nacimiento){ ingresante.value.fec_nacimiento = dayjs(res.data.datos.fec_nacimiento) }
  ingresante.value.nombres = res.data.datos.nombres
  ingresante.value.primer_apellido = res.data.datos.primer_apellido
  ingresante.value.segundo_apellido = res.data.datos.segundo_apellido
  ingresante.value.proceso = res.data.datos.proceso
  ingresante.value.modalidad = res.data.datos.modalidad
  ingresante.value.puntaje = res.data.datos.puntaje
  ingresante.value.programa = res.data.datos.programa
  ingresante.value.puesto= res.data.datos.puesto
  if(res.data.datos.fecha){ ingresante.value.fecha = res.data.datos.fecha }
  getCarrerasPrevias()
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

//getPostulanteRequisitos()

const getPostulantesByDni = async () => {
  let res = await axios.post("get-postulante-dni",{ dni: dniseleccionado.value });
  postulante.value.id = res.data.datos.id_postulante;
  postulante.value.dni_temp = res.data.datos.dni
}

watch(dni, (newValue, oldValue ) => {
  if(newValue.length >= 0){
    // getPostulantes();
    getPostulantesByDni()
  } 
})

watch(codigo, (newValue, oldValue ) => {
  if(newValue.length == 6 &&  dniseleccionado.value.length == 8 ){
    modal.value = true;
  } 
})

watch(dniseleccionado, (newValue, oldValue ) => {
    if(newValue.length >= 8){
      if(codigo.value.length == 6){
        modal.value = true;
      }
      getIngresante();
    }
})

const abrirVentana = async () => {
  let res = await axios.post("control-biometrico",
  { dni: dniseleccionado.value, n_carrera: n_carrera.value });
  imprimirPDF(res.data.datos);
  dniseleccionado.value = null
}

const imprimirPDF =  (dnni) => {
    var iframe = document.createElement('iframe');
    iframe.style.display = "none";
    iframe.src = baseUrl+'/documentos/control-biometrico-general/'+dnni+'.pdf';
    document.body.appendChild(iframe);
    iframe.contentWindow.focus();
    iframe.contentWindow.print();
}

const getCarrerasPrevias = async() => {
  anteriores.value = []
  n_carrera.value = 0

  try {
    if(ingresante.value.dni != null){
      const response = await axios.post('https://service2.unap.edu.pe/TieneCarrerasPrevias/',  {
        doc_:ingresante.value.nro_doc,
        nom_:ingresante.value.nombres,
        app_:ingresante.value.primer_apellido,
        apm_:ingresante.value.segundo_apellido
      }, { headers: { 'Content-Type': 'application/json'}  });
      anteriores.value = response.data;
      if( anteriores.value[0]){
        n_carrera.value = 1;
      }
    }
    else{
      const response = await axios.post('https://service2.unap.edu.pe/TieneCarrerasPrevias/',  {
        doc_: dniseleccionado.value,
        nom_: "SDSFASD",
        app_: "SDSFASD",
        apm_: "SDSFASD"
      }, { headers: { 'Content-Type': 'application/json'}  });
      anteriores.value = response.data;
      if( anteriores.value[0]){
        n_carrera.value = 1;
      }
    }

  } catch (error) {
    console.error('Error:', error);
  }
};


// const getEstadoEstudiante = async() => {
//   try {
//     const response = await axios.get('https://service2.unap.edu.pe/DEBTS/?w='+anteriores,
//     { headers: { 'Content-Type': 'application/json'}  });
//     anteriores.value = response.data;
//   } catch (error) {
//     console.error('Error:', error);
//   }
// };

// const getCarrerasPrevias = async ( ) => {
//   try {
//     const response = await fetch("https://service2.unap.edu.pe/TieneCarrerasPrevias/", {
//       method: "POST", // or 'PUT'
//       headers: {
//         "Content-Type": "application/json",
//       },
//       body: JSON.stringify({
//         doc_:ingresante.value.nro_doc,
//         nom_:ingresante.value.nombres,
//         app_:ingresante.value.primer_apellido,
//         apm_:ingresante.value.segundo_apellido
//       }),
//     });

//     const result = await response.json();
//     console.log("Success:", result);
//   } catch (error) {
//     console.error("Error:", error);
//   }
// }

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