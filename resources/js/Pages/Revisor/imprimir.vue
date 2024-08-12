<template>
<Head title="Revisión Posterior"/>
<AuthenticatedLayout>
  <div>
    <a-card style="background: white;" class="mb-0 p-0" >
      <a-row :gutter="16" class="mb-3">
        <a-col :span="24" :sm="24" :md="24" :lg="24" style="display:flex; justify-content: end;">
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
                  <template #option="{ value: val, label:lab }">
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
                          <img :src="baseUrl+'/documentos/'+id_proceso+'/control_biometrico/huellas/'+ingresante.nro_doc+'.jpg'" height="80"/>
                          <div class="flex justify-center"> H. Der.</div>
                        </div>

                        <div>
                          <img :src="baseUrl+'/documentos/'+id_proceso+'/control_biometrico/huellas/'+ingresante.nro_doc+'x.jpg'" height="80" />
                          <div class="flex justify-center"> H. Izq.</div>
                        </div>
                      </a-col>
                      <a-col :xs="24" :sm="12" :md="12" :lg="5" :xl="5">
                        <img :src="baseUrl+'/documentos/'+id_proceso+'/control_biometrico/fotos/'+ingresante.nro_doc+'.jpg'" width="250" />

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


                  <div v-if="anteriores[0]" class="flex mb-3 mt-3" :style="anteriores[0] ? 'align-items:center; justify-content: center; width: 100%; height: 40px; background: red; border-radius: 7px;' : 'align-items:center; justify-content: center; width: 100%; height: 40px; background: #cdcdcd4F; border-radius: 7px;'">
                    <span style="font-weight: bold; font-size: 1.2rem;">Datos de ingreso anterior</span>
                  </div>

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
                          <a-input v-model:value="ingresante.proceso"/>
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

                </div>
              </a-tab-pane>


              <a-tab-pane key="1" tab="Solicitud" class="pl-2 pr-2">
                <div>
                  <div style="width:100%; height:380px; position:relative; overflow:hidden">
                    <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
                      <iframe :src="baseUrl+'/documentos/'+id_proceso+'/preinscripcion/solicitudes/'+dniseleccionado+'.pdf'" style="top:-54px; position:absolute" width="100%" height="100%" scrolling="yes" frameborder="1" ></iframe>
                    </div>
                </div>
                </div>
              </a-tab-pane>
              <a-tab-pane key="4" tab="Const. Inscripcion">
                <div>
                  <div style="width:100%; height:380px; position:relative; overflow:hidden">
                    <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
                      <iframe :src="baseUrl+'/documentos/'+id_proceso+'/inscripciones/constancias/'+dniseleccionado+'.pdf'" style="top:-54px; position:absolute" width="100%" height="470px"   scrolling="yes" frameborder="1" ></iframe>
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
                            <img :src="baseUrl+'/documentos/'+id_proceso+'/inscripciones/fotos/'+dniseleccionado+'.jpg'"/>
                            <div class="flex justify-center"> Foto Inscripción.</div>
                          </div>
                        </a-col>                        
                        <a-col :xs="24" :sm="12" :md="8" :lg="12">
                          <div class="p-6">
                            <img :src="baseUrl+'/documentos/'+id_proceso+'/control_biometrico/fotos/'+dniseleccionado+'.jpg'"/>
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
                            <img :src="baseUrl+'/documentos/'+id_proceso+'/inscripciones/huellas/'+dniseleccionado+'.jpg'"/>
                            <div class="flex justify-center"> H. inscripción</div>
                          </div>
                        </a-col>                        
                        <a-col :xs="24" :sm="12" :md="8" :lg="5">
                          <div>
                            <img :src="baseUrl+'/documentos/'+id_proceso+'/inscripciones/huellas/'+dniseleccionado+'x.jpg'"/>
                            <div class="flex justify-center"> H. inscripción</div>
                          </div>
                        </a-col>
                        <a-col :xs="24" :sm="12" :md="8" :lg="4">
                          <div>
                            <img :src="baseUrl+'/documentos/'+id_proceso+'/examen/huellas/'+dniseleccionado+'.jpg'"/>
                            <div class="flex justify-center"> H. Examen</div>
                          </div>
                        </a-col>
                        <a-col :xs="24" :sm="12" :md="8" :lg="5">
                          <div>
                            <img :src="baseUrl+'/documentos/'+id_proceso+'/control_biometrico/huellas/'+dniseleccionado+'.jpg'"/>
                            <div class="flex justify-center"> H. Biometrico</div>
                          </div>
                        </a-col>
                        <a-col :xs="24" :sm="12" :md="8" :lg="5">
                          <div>
                            <img :src="baseUrl+'/documentos/'+id_proceso+'/control_biometrico/huellas/'+dniseleccionado+'x.jpg'"/>
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
        <a-button type="primary"  @click="abrirVentana()">Registrar</a-button>
      </div>
    </a-card>

    <div style="max-width:100%;">
      <div style="max-width:1000px">

      </div>
    </div>
    <a-modal v-model:visible="modal" :closable="false" :maskClosable="false" style="width:1200px;" centered >

      <div class="flex justify-center">
        <span style="font-size:1.4rem; font-weight:bold;">Información del postulante</span>
      </div>

      <div style="margin-top:20px;">
        <h2>DNI</h2>
      </div>
      <div>
        <div style="width:100%; height:380px; position:relative; overflow:hidden">
          <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
            <iframe :src="baseUrl+'/documentos/'+id_proceso+'/biometrico/dnis/'+dniseleccionado+'.pdf'" style="top:-54px; position:absolute" width="100%" height="470px"   scrolling="yes" frameborder="1" ></iframe>
          </div>
        </div>
      </div>

      <div style="margin-top:20px;">
        <h2>Certificado de estudios</h2>
      </div>
      <div>
        <div style="width:100%; height:380px; position:relative; overflow:hidden">
          <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
            <iframe :src="baseUrl+'/documentos/'+id_proceso+'/biometrico/certificados/'+dniseleccionado+'.pdf'" style="top:-54px; position:absolute" width="100%" height="470px"   scrolling="yes" frameborder="1" ></iframe>
          </div>
        </div>
      </div>

      <div style="margin-top:20px;">
        <h2>Solicitud de inscripción</h2>
      </div>
      <div>
          <div style="width:100%; height:400px; position:relative; overflow:hidden">
            <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
              <iframe :src="baseUrl+'/documentos/'+id_proceso+'/preinscripcion/solicitudes/'+dniseleccionado+'.pdf'" style="top:-54px; position:absolute" width="100%" height="100%" scrolling="yes" frameborder="1" ></iframe>
            </div>
          </div>
      </div>

      <div style="margin-top:-20px;">
        <h2>Constancia de inscripción</h2>
      </div>
      <div>
        <div style="width:100%; height:380px; position:relative; overflow:hidden">
          <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
            <iframe :src="baseUrl+'/documentos/'+id_proceso+'/inscripciones/constancias/'+dniseleccionado+'.pdf'" style="top:-54px; position:absolute" width="100%" height="470px"   scrolling="yes" frameborder="1" ></iframe>
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
                    <img :src="baseUrl+'/documentos/'+id_proceso+'/inscripciones/fotos/'+dniseleccionado+'.jpg'"/>
                    <div class="flex justify-center"> Foto Inscripción.</div>
                  </div>
                </a-col>                        
                <a-col :xs="24" :sm="12" :md="8" :lg="12">
                  <div class="p-6">
                    <img :src="baseUrl+'/documentos/'+id_proceso+'/control_biometrico/fotos/'+dniseleccionado+'.jpg'"/>
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
                  <img :src="baseUrl+'/documentos/'+id_proceso+'/inscripciones/huellas/'+dniseleccionado+'.jpg'"/>
                  <div class="flex justify-center"> H. inscripción</div>
                </div>
              </a-col>                        
              <a-col :xs="24" :sm="12" :md="8" :lg="5">
                <div>
                  <img :src="baseUrl+'/documentos/'+id_proceso+'/inscripciones/huellas/'+dniseleccionado+'x.jpg'"/>
                  <div class="flex justify-center"> H. inscripción</div>
                </div>
              </a-col>
              <a-col :xs="24" :sm="12" :md="8" :lg="4">
                <div>
                  <img :src="baseUrl+'/documentos/'+id_proceso+'/examen/huellas/'+dniseleccionado+'.jpg'"/>
                  <div class="flex justify-center"> H. Examen</div>
                </div>
              </a-col>
              <a-col :xs="24" :sm="12" :md="8" :lg="5">
                <div>
                  <img :src="baseUrl+'/documentos/'+id_proceso+'/control_biometrico/huellas/'+dniseleccionado+'.jpg'"/>
                  <div class="flex justify-center"> H. Biometrico</div>
                </div>
              </a-col>
              <a-col :xs="24" :sm="12" :md="8" :lg="5">
                <div>
                  <img :src="baseUrl+'/documentos/'+id_proceso+'/control_biometrico/huellas/'+dniseleccionado+'x.jpg'"/>
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
import { FormOutlined, DeleteOutlined, PrinterOutlined, CreditCardOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';
import { defineProps } from 'vue';
import Vouchers from './components/voucher.vue'
import Anterior from './components/anteriores.vue'
import dayjs from 'dayjs';
import { format, parseISO } from 'date-fns';
import { es } from 'date-fns/locale';

const props = defineProps({ id_proceso: { type: Number, required: true }, });
const baseUrl = window.location.origin;

const dni = ref(null);
const dniseleccionado = ref("")
const modal = ref(false);
const codigo = ref("");
const postulante = ref("");
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

const dniInput = ref(null)
const save = async () => {
  dniInput.value.focus()
  let res = await axios.post('save-requisito',{
    dni: dniseleccionado.value, requisitos: checkedList.value
  });
  dniseleccionado.value = null
  checkedList.value = []
}
const buscar = ref("");

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

const getPostulantesBiometrico =  async (term = "", page = 1) => {
  let res = await axios.post(
      "get-postulantes-biometrico?page=" + page,
      { term: buscar.value }
  );
  postulantes.value = res.data.datos.data;
}

const getPostulantesByDni = async () => {

  let res = await axios.post("get-postulante-dni",{ dni: dniseleccionado.value });
  if (res.data.datos && res.data.datos.id_postulante) {
      modal.value = true;
      postulante.value.id = res.data.datos.id_postulante;
      postulante.value.dni_temp = res.data.datos.dni;

    } else {
      ingresante.value.id = null
      ingresante.value.nro_doc= ""
      ingresante.value.tipo_doc= null
      ingresante.value.nombres = null
      ingresante.value.sexo= null
      ingresante.value.fec_nacimiento = null
      ingresante.value.primer_apellido = ""
      ingresante.value.segundo_apellido =""
      ingresante.value.proceso= ""
      ingresante.value.modalidad = ""
      ingresante.value.puntaje= ""
      ingresante.value.programa = ""
      ingresante.value.fecha = ""
      ingresante.value.puesto =""
      notificacion("error","No se han encontrado datos");
      // Puedes agregar lógica adicional aquí, como mostrar un mensaje de error
    }

}

watch(dni, (newValue, oldValue ) => {
  if(newValue.length >= 8){
    // getPostulantes();
    getPostulantesByDni()
  
  } 
})

let timeoutId;
watch(buscar, ( newValue, oldValue ) => { 
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
        getPostulantesBiometrico(); 
    }, 500);    
})

watch(codigo, (newValue, oldValue ) => {
  if(dniseleccionado.value.length == 8 ){
    modal.value = true;
  }   
})

watch(dniseleccionado, (newValue, oldValue ) => {
    if(newValue.length >= 8){
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
    iframe.src = baseUrl+'/documentos/'+id_proceso+'/control_biometrico/constancias/'+dnni+'.pdf';
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

const value = ref([]);
const codigos =ref([]);

const handleChange = (newValue) => {
  console.log('Valor seleccionado:', newValue);
};

getPostulantesBiometrico()

const notificacion = (type, titulo, mensaje) => {
  notification[type]({
    message: titulo,
    description: mensaje,
  });
};


const dataSource = ref([
  { key: '1', name: 'Derechos de admisión', age: '20-23-2024', address: '150.00', },
  { key: '2', name: 'Examen médico', age: '20-23-2024', address: '200.00', }
]);


const columns = ref([
  { title: 'Banco', dataIndex: 'banco', width:'110px',},
  { title: 'Concepto', dataIndex: 'name', key: 'name',},
  { title: 'Fecha', dataIndex: 'age', key: 'age', width:'190px', align:'center' },
  { title: 'Monto S/', dataIndex: 'address', key: 'address', width:'130px', align:'center' },
  { title: '', dataIndex: 'option', width:'80px', }
])

const colpostulantes = ref([
  { title: 'DNI', dataIndex: 'dni', width:'110px',},
  { title: 'Nombres', dataIndex: 'nombres'},
  { title: 'Programa', dataIndex: 'programa', key: 'name',},
  { title: 'Modalidad', dataIndex: 'modalidad', align:'center'},
  { title: 'Area', dataIndex: 'area', align:'center'},
  { title: 'Codigo', dataIndex: 'codigo', align:'center'},
  { title: '', dataIndex: 'acciones', width:'120px',}
]);
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
.fondo-biometrico{
  background-image: url("../../../assets/imagenes/fondo-biometrico.jpg");
  background-repeat: no-repeat;
  background-size: cover;
  height:320px; width: 100%; position: relative; border:solid #d9d9d9 1px;
}

.header-biometrico-nombre{
  font-size: 3rem; 
  font-family: 'Helvetica'; 
  color:white; 
  font-weight: bold;
}
.header-biometrico-2da{
  font-size: 1.2rem; 
  font-family: 'Helvetica'; 
  color:white; 
  letter-spacing: .22rem;
}
.header-biometrico-programa{
  font-size: 1.2rem; 
  font-family: 'Helvetica'; 
  color:#0a3d5a ; 
  font-weight: bold; 
  letter-spacing: .12rem;
}
.header-biometrico-modalidad{
  font-size: .9rem; 
  font-family: 'Helvetica'; 
  color:black; 
  letter-spacing: .22rem;
}

.header-biometrico-container-foto{
  position: absolute; 
  top:60px; 
  left: 20px; 
  border: solid 5px #e7e7e7;
}
.biometrico-foto-imagen{
  width: 180px;
}
.header-biometrico-letras-top{
  position: absolute; bottom:30px; left: 230px;
}

.header-biometrico-letras-bot{
  position: absolute; top:10px; left: 230px;
}

.header-modalidad{
  color: black;
}


@media screen and (max-width: 600px) {
  .header-biometrico-nombre{ font-size: 1.5rem; }
  .header-biometrico-2da{
    font-size: .7rem; 
  }
  .header-biometrico-programa{
    font-size: .7rem; 
  }
  .header-biometrico-modalidad{
    font-size: .5rem; 
  }
  .header-biometrico-container-foto{
    top:60px; left: 10px;  border: solid 2px #e7e7e7;
  }
  .biometrico-foto-imagen{
    width: 100px;
  }

  .fondo-biometrico{
    height:200px; width: 100%; position: relative; border:solid #d9d9d9 1px;
  }
  .header-biometrico-letras-top{
    position: absolute; bottom:10px; left: 125px;
  }
  .header-biometrico-letras-bot{
    position: absolute; top:5px; left: 125px;
  }
  .header-modalidad{
    display: none;
  }

}
</style>