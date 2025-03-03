<template style="background:pink;">
    <Head title="Preinscipción"/>
    <Layout :nombre="props.procceso_seleccionado.nombre">

      <a-modal v-model:open="open" centered style="width: 100%; max-width:1200px;" :footer="false" >
        <div>
          <h1 style="font-weight:bold; font-size:1.2rem;">Datos personales</h1>
          <hr>
  
          <div class="datos-container" style="margin-bottom: 10px;">
            <div class="datos-column">
              <label for="name">Tipo doc: <span></span>  </label>
              <input type="text" disabled :value="tipo_docs[datospersonales.tipo_doc]"/>
            </div>
  
            <div class="datos-column">
              <label for="name">N° Documento: <span></span>  </label>
              <input type="text" disabled :value="formState.dni"  />
            </div>
  
            <div class="datos-column">
              <label for="name">Primer apellido: <span></span>  </label>
              <input type="text" disabled :value="datospersonales.primerapellido"  />
            </div>
  
            <div class="datos-column">
              <label for="name">Segundo apellido: <span></span>  </label>
              <input type="text" disabled :value="datospersonales.segundo_apellido"  />
            </div>
  
            <div class="datos-column">
              <label for="name">Pre Nombres: <span></span>  </label>
              <input type="text" disabled :value="datospersonales.nombres"  />
            </div>
  
            <div class="datos-column">
              <label for="name">Estado civil: <span></span>  </label>
              <input type="text" disabled :value="estados_civil[datospersonales.estado_civil]"  />
            </div>
  
            <div class="datos-column">
              <label for="name">Sexo: <span></span> </label>
              <input type="text" disabled :value="sexos[datospersonales.sexo]"  />
            </div>
  
            <div class="datos-column">
              <label for="name">Correo: <span></span>  </label>
              <input type="text" disabled :value="datospersonales.correo"  />
            </div>
  
            <div class="datos-column">
              <label for="name">Celular: <span></span>  </label>
              <input type="text" disabled :value="datospersonales.celular"  />
            </div>
  
            <div class="datos-column">
              <label for="name">Fec. nacimiento: <span></span>  </label>
              <input type="text" disabled :value="datospersonales.fec_nacimiento"/>
            </div>
  
            <div class="datos-column">
              <label for="name">Ubigeo de nacimiento: <span></span>  </label>
              <input type="text" disabled :value="datospersonales.ubigeo_nacimiento"/>
            </div>

            <div class="datos-column">
                <label for="name">Ubigeo de residencia: <span></span>  </label>
                <input type="text" disabled :value="datospersonales.ubigeo_residencia"/>
              </div>
            <div class="datos-column">
          </div>
  
          </div>
        </div>
  
        <div>
          <h1 style="font-weight:bold; font-size:1.2rem;">Datos de postulación</h1>
          <hr>

          <div class="datos-container" style="margin-bottom: 10px;">

  
            <div class="datos-column">
              <label for="name">Programa:</label>
              <select v-model="datos_preinscripcion.programa">
                <option disabled value="">Seleccione una especialidad</option>
                <option 
                  v-for="item in especialidades" 
                  :key="item.id" 
                  :value="item.id">
                  {{ item.nombre }}
                </option>
              </select>
            </div>
        </div>
        <div class="datos-column" style="margin-top:-20px; display:flex; width:100%;">
          <div class="flex">
            <input class="checkbox mr-2" type="checkbox" :value="checkbox1" v-model="checkbox1"/>
            <span style="font-size: 1.2rem; font-weight:bold;"> Certifico que la información brindada es correcta</span>
          </div>
        </div>
  
  
      </div>
      <div>
          <div class="datos-column" style="width:100%;">
              <div v-if="checkbox1 == true" class="flex justify-end">
                <a-button html-type="submit" @click="submit" type="primary" class="boton-siguiente">GUARDAR DATOS</a-button>
              </div>
              <div v-else class="flex justify-end">
                <a-button html-type="submit" @click="submit" type="primary" disabled class="boton-siguiente">GUARDAR DATOS</a-button>
              </div>
          </div>
        </div>
  
      </a-modal>
  
      <div style="width: 100%; min-height: calc(100vh - 390px); padding: 20px 20px; margin-top: 0px;"> 
        <div class="flex mt-3 justify-center align-center" style=" width: 100%; min-height: calc(100vh - 300px)">
          <a-card v-if="pagina_pre === 0"  style="width: 100%;  max-width: 350px; max-height:380px" class="pl-3 pr-3 cardInicio" >
            <div>
              <h1 style="font-size: 1.1rem;">Datos de validación </h1>
            </div>
            <a-form
                ref="formRef" :model="formState"
                name="inicio_dni"
                @finish="onFinish"
                @finishFailed="onFinishFailed"
              >
              <a-radio-group v-model:value="datospersonales.tipo_doc" class="flex justify-end" style="display: flex; width: yellow;" name="radioGroup">
                <a-radio :value="1">Dni</a-radio>
                <a-radio :value="3">Carnet Ext.</a-radio>
              </a-radio-group>
  
              <div style="margin-bottom: 7px;"><label>N° Documento</label></div>
              <a-form-item
                  name="dni"
                  :rules="[{ required: true, message: 'Por favor ingresa tu DNI', trigger: 'change' },
                  { min: 8, message: 'El dni debe tener 8 digitos', trigger: 'blur',},]"
                >
                <a-input v-if="datospersonales.tipo_doc === 1" v-model:value="formState.dni" @input="dniInput" :maxlength="8" placeholder="N° Documento">
                    <template #prefix>
                        <user-outlined />
                    </template>
                </a-input>
                <a-input v-else v-model:value="formState.dni" @input="dniInput" :maxlength="12" placeholder="N° Documento">
                    <template #prefix>
                        <user-outlined />
                    </template>
                </a-input>
              </a-form-item>
  
              <a-card class="mt-3">
                <div class="flex justify-center no-select" style="margin: -20px; cursor: none; pointer-events:none;">
                  <span style="text-decoration:line-through; font-family:helvetica; font-weight:bold; font-size:2.2rem; letter-spacing:1rem;"> {{ codigo_aleatorio }} </span>
                </div>
              </a-card>
              <div class="mb-4">
                <div class="mt-3"><label>Código de seguridad</label></div>
                  <a-form-item
                    name="codigo_secreto"
                    :rules="[{ required: true, message: 'Ingresa el código del cuadro', trigger: 'change' }, 
                      { min: 6, message: 'El ubigeo debe tener 6 caracteres', trigger: 'blur',},
                      { validator: validateCodigoSecreto, trigger: 'change' }
                      ]"
                    >
                    <a-input v-model:value="formState.codigo_secreto" :maxlength="6" placeholder="Ingresa el codigo">
                        <template #prefix>
                            <user-outlined />
                        </template>
                    </a-input>
                  </a-form-item>
                </div>
              <div style="display: flex; justify-content: center; margin-top: 20px;">
                <a-button type="primary" v-if="participa == 0 || codigo_aleatorio !== formState.codigo_secreto || modalcarrerasprevias == true"  disabled>Iniciar Postulación</a-button>
                <a-button type="primary" v-if="participa == 1 && formState.codigo_secreto === codigo_aleatorio && modalcarrerasprevias == false" @click="getDatosPersonales()">Iniciar Postulación</a-button>
              </div>
            </a-form>
          </a-card>
        <!-- END INICIO-->
  
          <div v-if="pagina_pre === 1" class="container">
  
            <div style="width: 100%; margin-top: 5px; margin-bottom: 30px; ">
  
                <a-card class="cardInicio max-w-full lg:max-w-screen-xxl w-full mx-auto">
                    <a-form
                      ref="formDatosPersonales" 
                      :model="datospersonales"
                      name="datosPersonales"
                      @finish="onFinish"
                      @finishFailed="onFinishFailed"
                    >

                      <div class="px-2 py-2">
                        <div class="mb-0 mt-0">
                          <h1 class="text-lg font-semibold"> Datos de personales</h1>
                        </div>
                  
                        <div class="flex items-center justify-end mb-4">
                          <div>
                            <div class="mb-2">Sexo</div>
                            <a-form-item
                              name="sexo"
                              :rules="[{ required: true, message: 'Elige el sexo', trigger: 'change' }]">
                              <a-radio-group v-model:value="datospersonales.sexo" class="flex justify-end" name="radioGroup">
                                <a-radio value="1">M</a-radio>
                                <a-radio value="2">F</a-radio>
                              </a-radio-group>
                            </a-form-item>
                          </div>
                  
                          <div class="border-r border-gray-300 h-12 mx-4"></div>
                  
                          <div class="ml-3">
                            <div>Estado civil</div>
                            <a-form-item
                              name="estado_civil"
                              :rules="[{ required: true, message: 'Elije tu estado civil', trigger: 'change' }]">
                              <a-select
                                ref="select"
                                v-model:value="datospersonales.estado_civil"
                                class="w-32">
                                <a-select-option value="1">Soltero</a-select-option>
                                <a-select-option value="2">Casado</a-select-option>
                                <a-select-option value="3">Viudo</a-select-option>
                                <a-select-option value="4">Divorciado</a-select-option>
                              </a-select>
                            </a-form-item>
                          </div>
                        </div>
                  

                    <a-row  :gutter="[16, 0]">
                        <a-col :span="24" :md="12" :lg="8" class="mb-4">
                            <div><label>Primer apellido:</label></div>
                            <a-form-item
                              name="primerapellido"
                              :rules="[{ required: true, message: 'Ingresa tu Primer Apellido', trigger: 'change' }]">
                              <a-input @input="pimerapellidoInput" v-model:value="datospersonales.primerapellido">
                                <template #prefix><user-outlined /></template>
                              </a-input>      
                            </a-form-item>
                          </a-col>
                  
                          <a-col :span="24" :md="12" :lg="8" class="mb-4">
                            <div><label>Segundo apellido:</label></div>
                            <a-input v-model:value="datospersonales.segundo_apellido">
                                <template #prefix><user-outlined /></template>
                            </a-input>  
                          </a-col>
                  
                          <a-col :span="24" :md="12" :lg="8" class="mb-4">
                            <div><label>Pre Nombres:</label></div>
                            <a-form-item
                              name="nombres"
                              :rules="[{ required: true, message: 'Ingresa tu nombre(s)', trigger: 'change' }]">
                              <a-input @input="nombresInput" v-model:value="datospersonales.nombres">
                                <template #prefix><user-outlined /></template>
                              </a-input> 
                            </a-form-item>
                          </a-col>
                  

                          <a-col :span="24" :md="12" :lg="8" class="mb-4">
                            <a-form-item
                              name="celular"
                              :rules="[
                                { required: true, message: 'Ingresa tu celular', trigger: 'change' },
                                { min: 9, message: 'El Celular debe tener 9 digitos', trigger: 'blur' },
                                { validator: validateCelular, trigger:'blur'}
                              ]">
                              <div><label>Numero de celular:</label></div>
                              <a-input :maxlength="9" v-model:value="datospersonales.celular">
                                <template #prefix><user-outlined /></template>
                              </a-input> 
                            </a-form-item>
                          </a-col>


                          <a-col :span="24" :md="16" :lg="16" :xl="16">
                            <a-form-item
                              name="correo"
                              :rules="[
                                { required: true, message: 'Ingresa un correo valido', trigger: 'change' },
                                { type: 'email', message: 'Ingresa un correo valido' },
                                { validator: validateCorreo, trigger:'blur'}
                              ]">
                              <div><label>Correo:</label></div>
                              <a-input type="email" @input="correoInput" v-model:value="datospersonales.correo">
                                <template #prefix><user-outlined /></template>
                              </a-input> 
                            </a-form-item>
                          </a-col>
                  
                          <a-col :span="24" :md="12" :lg="8" class="mb-4">
                            <a-form-item
                              name="direccion"
                              :rules="[
                                { required: true, message: 'Ingresa tu dirección actual', trigger: 'change' },
                              ]">
                              <div><label>Dirección actual:</label></div>
                              <a-input v-model:value="datospersonales.direccion">
                                <template #prefix><user-outlined /></template>
                              </a-input> 
                            </a-form-item>
                          </a-col>

                          <a-col :span="24" :md="8" :lg="8" :xl="8">
                            <a-form-item
                              name="egreso"
                              :rules="[{ required: true, message: 'Ingresa año de egreso', trigger: 'change' }]">
                              <div><label>Año de egreso de I.E. Superior:</label></div>
                              <a-input v-model:value="datospersonales.egreso">
                                <template #prefix><user-outlined /></template>
                              </a-input> 
                            </a-form-item>
                          </a-col>

                          <a-col :span="24" :md="12" :lg="8" class="mb-4">
                            <a-form-item
                              name="fec_nacimiento"
                              :rules="[
                                { required: true, message: 'Ingresa tu fecha de nacimiento', trigger: 'change' },
                                { validator: validateFechaNacimiento, trigger: 'change' }
                              ]">
                              <div><label>Fec. Nacimiento: "DD/MM/AAAA"</label></div>
                              <a-date-picker
                                placeholder="Selecciona tu fecha de nacimiento"
                                style="width: 100%"
                                v-model:value="datospersonales.fec_nacimiento"
                                format="DD/MM/YYYY"
                              />
                            </a-form-item>
                          </a-col>

                          <a-col :xs="24" :sm="24" :md="24" :lg="12">
                            <label>Ubigeo Nacimiento: Dep/Prov/Dist <span style="color:red;">*</span></label>
                            <a-form-item name="ubigeo_nacimiento">
                                <a-auto-complete
                                    v-model:value="nacimiento"                
                                    :options="ubigeosNacimiento"
                                    @select="onSelectNacimiento"
                                    allowClear
                                >
                                    <a-input
                                        placeholder="Lugar"
                                        v-model:value="buscarNacimiento"
                                        @keypress="handleKeyPress"
                                    >
                                        <template #suffix>
                                            <a-tooltip title="Extra information">
                                            <down-outlined/>
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                </a-auto-complete>
                            </a-form-item>
                        </a-col>

                        <a-col :xs="24" :sm="24" :md="24" :lg="12">
                            <label>Ubigeo Residencia: Dep/Prov/Dist <span style="color:red;">*</span></label>
                            <a-form-item name="ubigeo">
                                <a-auto-complete
                                    v-model:value="residencia"                
                                    :options="residencias"
                                    @select="onSelectResidencias"
                                    :allowClear="true"
                                >
                                    <a-input
                                        placeholder="Lugar"
                                        v-model:value="buscarResidencia"
                                        @keypress="handleKeyPress"
                                    >
                                        <template #suffix>
                                            <a-tooltip title="Extra information">
                                            <down-outlined/>
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                </a-auto-complete>
                            </a-form-item>
                        </a-col>
    
                  

                        </a-row>
                  
                      </div>
                    </a-form>
                  </a-card>
                  
                  
          </div>
          </div>
  
          <div v-if="pagina_pre === 6">

              
            <div style="width: 100%; margin-top: 5px; ">
              <a-card style="padding-top: -5px; max-width:900px; padding-bottom:0px;" class="cardInicio">
  
                <a-form
                  ref="formPreinscripcion" 
                  :model="datos_preinscripcion"
                  name="preinscripcion"
                  @finish="onFinish"
                  @finishFailed="onFinishFailed"
                >

                <a-row  :gutter="[16, 0]">

                    <div class="ml-2" style="margin-bottom: 15px; margin-top: 0px;">
                        <h1 style="font-size: 1.1rem;"> Datos Postulación</h1>
                    </div>

                    <a-col :span="24" :md="12" :lg="24" class="mb-4">
                        <a-form-item
                        name="programa"
                        :rules="[{ required: true, message: 'Seleccine la modalidad', trigger: 'change' },]"
                        >
                            <div><label>Programa de estudios</label></div>
                            <a-select
                            ref="select"
                            v-model:value="datos_preinscripcion.programa"
                            style="width: 100%"
                            :options="programas"
                            @focus="focus"
                            @change="handleChange"
                            ></a-select>
                        </a-form-item>
                    </a-col>
                    <a-col :span="24" :md="12" :lg="12" class="mb-4">
                        <Foto :id_proceso="props.procceso_seleccionado.id" :dni="formState.dni"/>
                    </a-col>
                    <a-col :span="24" :md="12" :lg="12" class="mb-4">
                        <Titulo :id_proceso="props.procceso_seleccionado.id" :dni="formState.dni"/>
                    </a-col>

                </a-row>
  
                </a-form>
              </a-card>
              </div>
          </div>
  
          <div v-if="pagina_pre === 7 || postulante_inscrito === 1">
            <div style="width: 100%; height:calc(100vh - 300px); display:flex; align-items:center; ">
              <a-card style="padding-top: 5px; padding-bottom:0px; background: #24c1ff25;">
                <div class="">
                  <div class="flex justify-center">
                    <div  style="text-align:center; max-width: 300px;" >
                      <div>¡PREINSCRIPCIÓN EXITOSA! DESCARGA LOS ANEXOS PARA CONTINUAR CON TU PROCESO PRESENCIAL</div>
                    </div>
                  </div>
                  <!-- <div class="flex justify-center mt-4 mb-4">
                    <a-button @click="getDocs()" style="background: #020b61;" type="primary"> DESCARGAR SOLICITUD </a-button>
                  </div> -->

                  <div class="flex justify-center mt-4 mb-4 mr-2">
                    <a-button @click="descargaReglamento()" class="custom-button" style="background: teaL; color:white; border:none;" shape="round">
                            <div>DESCARGAR ANEXOS</div>
                      </a-button>
                  </div>
                </div>
              </a-card>
              </div>
          </div>
  
          </div>
        </div>
        <div>
  
        </div>
        <a-affix v-if="pagina_pre !== 0" :offset-bottom="bottom" style="margin-top: 0px;">
          <div v-if="pagina_pre !== 8">
            <a-progress :percent="avance"/>
          </div>
  
          <div class="flex" style="justify-content: space-between;" v-if="pagina_pre === 1">
            <a-button @click="prev()" class="boton-anterior">Anterior</a-button>
            <a-button @click="saveDatosPersonales()" class="boton-siguiente" type="primary" >Siguiente</a-button>
          </div>
          <div class="flex" style="justify-content: space-between;" v-if="pagina_pre === 6">
            <a-button @click="pagina_pre = 1" class="boton-anterior">Anterior</a-button>
            <a-button @click="abrirModalDatos()" class="boton-siguiente" type="primary" >VERIFICAR DATOS</a-button>
            <!-- <a-button html-type="submit" @click="submit" type="primary" class="boton-siguiente">Finalizar</a-button>     -->
          </div>
        </a-affix>  
  
    <a-modal v-model:open="modalcarrerasprevias" centered :keyboard="false" :footer="false" :closable="false" :maskClosable="false" >
      <div v-if="loading === true" class="flex justify-center" style="min-height:300px; align-items:center;">
        <div>
          <div class="flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader rotating-svg">
              <line x1="12" y1="2" x2="12" y2="6"></line>
              <line x1="12" y1="18" x2="12" y2="22"></line>
              <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
              <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
              <line x1="2" y1="12" x2="6" y2="12"></line>
              <line x1="18" y1="12" x2="22" y2="12"></line>
              <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
              <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
            </svg>
          </div>
          <div class="flex justify-center mt-3">
            <div style="text-align: center;">
              <div><span style="font-size:1.3rem;">Estamos revisando su información</span></div>
              <div><span style="font-size:1rem;">Tomará poco tiempo</span></div>
              <div><span></span></div>
            </div>
          </div>
        </div>  
      </div>
      
      <div v-if="loading === false"  class="flex justify-center" style="">
  
        <div v-if="modalSancionado === true" :class="modalsancionado === true ? 'aparecer-div':'aparecer-div-mostrar'">
            <div class="flex justify-center mt-6">
              <img src="../../../../assets/imagenes/alert.png" width="125"/>
            </div>
            <div class="mt-4">
              <h2 class="text-center" style="font-size:1.4rem;">¡Importante!</h2>
              <p class="text-center mx-4" style="font-size:1.1rem;">El participante no reúne las condiciones para participar en este proceso.</p>
            </div>  
            <div class="flex justify-center">
              <a-button @click="modalcarrerasprevias = false;" style="background:#454554; color:white; font-weight:bold; height:40px; width:110px; border-radius:8px; border:none;">
                Aceptar
              </a-button>
            </div>
        </div>
  
  
        <div v-if="anteriores.length === 0 && confirmacion === false" style="width: 100%; max-width: 1000px; margin-top:20px;">    
            <div class="flex justify-center">
              <div>
                <div class="mt-0 mb-3 flex justify-center" style="text-align:center;">
                  <div><span style="font-size:1.4rem;">
                    VERIFICACIÓN FINALIZADA  
                  </span></div>
                </div>
                <div class="mt-3 mb-3 flex justify-center" style="text-align:justify;">
                  <div><span style="font-size:1rem;">
                    Hemos revisado tu información y cumples con los requisitos 
                    para postular
                    en este Proceso. 
                    Para continuar con el proceso de postulación, sigue estos pasos:</span></div>
                </div>
  
                <div>
                  <div> 1. Presiona en "CONTINUAR". </div>
                  <div> 2. Ingresa el código secreto proporcionado. </div>
                  <div> 3. Presiona en "Iniciar Postulación". </div>
                </div>
  <!-- 
                {{datacepre}} -->
  
                <div class="mt-4 mb-4">
                  <a-alert message="!Importante! los datos compatibles con el sistema del ADMISIÓN  c se cargarán automáticamente" type="info" show-icon />
                </div>
                <div class="flex justify-center mt-4">
                    <a-button type="primary" style="color:white; width:120px; height:42px;" @click="modalcarrerasprevias = false">Continuar</a-button>
                </div>
              </div>
            </div>
        </div>       
        
    </div>
    </a-modal>
  </Layout>
</template>
<script setup>
import Layout from '@/Layouts/LayoutPreinscripcionSegundas.vue'
import { defineProps, watch, reactive, ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { format, parseISO } from 'date-fns';
import { es } from 'date-fns/locale';
import { notification } from 'ant-design-vue';
import { DownOutlined } from '@ant-design/icons-vue';
import Foto from "./components/foto-preinscripcion.vue"
import Titulo from "./components/titulo.vue"
import { message } from 'ant-design-vue';

const loading = ref(true);
const modalcepreaviso = ref(false);
const nombrecolegiox = ref("");
const ejemplo = ref(false);
const modalDatos = ref(true);
const open = ref(false);
const abrirModalDatos = async () => {
  try {
    const values = await formPreinscripcion.value.validateFields();
    console.log("Valores validados:", values);

    const validDocs = await validateDocuments();
    console.log("Resultado de validDocs:", validDocs);
    
    if (!validDocs) {
      return;
    }
    
    open.value = true;
  } catch (error) {
    console.error("Error al abrir el modal de datos:", error);
    message.error("Por favor, corrige los errores del formulario antes de continuar.");
  }
};

const checkbox1 = ref(false)
const activeKey = ref('1');

const examen = ref(0);
const avance = ref(0)
const bottom = ref(2)

const pagina_pre = ref(0)
const next = () => { pagina_pre.value++; }
const prev = () => { pagina_pre.value--; }
const dni = ref("")

const formRef = ref();
const formState = reactive({ dni: '', codigo_secreto: '', });

const formDatosPersonales = ref();
const datospersonales = reactive({
    id: null,
    tipo_doc:1,
    primerapellido: "",
    segundo_apellido: "",
    nombres:"",
    estado_civil:null,
    sexo:null,
    correo:"",
    celular:'',
    fec_nacimiento:"",
    direccion:"",
    ubigeo_nacimiento:"",
    ubigeo_residencia:"",
});
const formDatosResidencia = ref();
const datosresidencia = reactive({
    dep: null,
    prov: null,
    dist: null,
    direccion:''
});
const formDatosColegio = ref();
const datoscolegio = reactive({
    egreso: null,
    pais: 125,
    dep: null,
    prov: null,
    dist: null,
    colegio:'',
});

const formPreinscripcion = ref();
const datos_preinscripcion = reactive({
modalidad: null,
programa:null,
tipo_certificado:null,
codigo_medico: null,
codigo_certificado:null,
})

const dniInput = (event) => { formState.dni = event.target.value.replace(/\D/g, ''); };
const ubigeoInput = (event) => { formState.ubigeo = event.target.value.replace(/\D/g, ''); };
const nombresInput = (event) => { datospersonales.nombres = event.target.value.replace(/[^A-Za-z\s]/g, '');};
const pimerapellidoInput = (event) => { datospersonales.primerapellido = event.target.value.replace(/[^A-Za-z]/g, '');};
const celularInput = (event) => { datospersonales.celular = event.target.value.replace(/\D/g, ''); };

const departamentos = ref([])
const departamentoscolegio = ref([])

const residencia = ref(null)
const buscarResidencia = ref(null)
const residencias = ref([])

const getUbigeosResidencia = async () => {
    axios.post("/get-ubigeo",{"term": buscarResidencia.value})
    .then((response) => {
        residencias.value = response.data.datos.data;
    })
    .catch((error) => {
        if (error.response) {
            console.error('Error de servidor:', error.response.data);
        } else if (error.request) {
            console.error('Error de red:', error.request);
            } else { console.error('Error de configuración:', error.message); }
  });
}
watch(buscarResidencia, ( newValue, oldValue ) => {  if(newValue.length >= 3){ getUbigeosResidencia() }})

const nacimiento = ref(null)
const buscarNacimiento = ref(null)
const ubigeosNacimiento = ref([])

const getUbigeosNacimiento = async () => {
    axios.post("/get-ubigeo",{"term": buscarNacimiento.value})
    .then((response) => {
        ubigeosNacimiento.value = response.data.datos.data;
    })
    .catch((error) => {
        if (error.response) {
            console.error('Error de servidor:', error.response.data);
        } else if (error.request) {
            console.error('Error de red:', error.request);
            } else { console.error('Error de configuración:', error.message); }
  });
}
watch(buscarNacimiento, ( newValue, oldValue ) => {  if(newValue.length >= 3){ getUbigeosNacimiento() }});

const onSelectNacimiento = (value, option) => { datospersonales.ubigeo_nacimiento = option.key; };
const onSelectResidencias = (value, option) => { datospersonales.ubigeo_residencia = option.key; };


const codigo_secreto = ref(null);

watch(() => formState.dni, (newValue, oldValue) => {
if(newValue.length == 8){
    getPasoRegistrado();
    selectedItems.value = [];
}
});



const participa = ref(0);

const datareniec = ref(null)

const getDatosPersonales = async () => {
    if(pagina_pre.value == 0 ){
    const values = await formRef.value.validateFields();
    }

    let res = await axios.post( "/get-postulante-datos-personales", {nro_doc: formState.dni, ubigeo: formState.ubigeo});
    if (res.data.datos && res.data.datos.length > 0) {
        
        datospersonales.id = res.   data.datos[0].id
        datospersonales.primerapellido = res.data.datos[0].primer_apellido
        datospersonales.segundo_apellido = res.data.datos[0].segundo_apellido
        datospersonales.nombres = res.data.datos[0].nombres
        datospersonales.estado_civil = res.data.datos[0].estado_civil
        datospersonales.sexo = res.data.datos[0].sexo
        datospersonales.correo = res.data.datos[0].correo
        datospersonales.celular = res.data.datos[0].celular
        datospersonales.direccion = res.data.datos[0].direccion
        if(res.data.datos[0].fec_nacimiento){ datospersonales.fec_nacimiento = dayjs(res.data.datos[0].fec_nacimiento) }
        datospersonales.ubigeo = res.data.datos[0].ubigeo
        datospersonales.ubigeo_nacimiento = res.data.datos[0].ubigeo
        datosresidencia.direccion = res.data.datos[0].direccion
        datospersonales.ubigeo_residencia = res.data.datos[0].ubigeo_residencia
        datosresidencia.direccion = res.data.datos[0].direccion
        pagina_pre.value = 1;
    }
    else {
        pagina_pre.value = 1;
    }
    getUbigeosResidencia();
    getUbigeosNacimiento();
}



const getDatosPersonales2 = async () => {

let res = await axios.post( "/get-postulante-datos-personales2", {nro_doc: formState.dni});
    if(res.data.datos.length > 0) {
    datospersonales.id = res.data.datos[0].id
    datospersonales.primerapellido = res.data.datos[0].primer_apellido
    datospersonales.segundo_apellido = res.data.datos[0].segundo_apellido
    datospersonales.nombres = res.data.datos[0].nombres
    datospersonales.estado_civil = res.data.datos[0].estado_civil
    datospersonales.sexo = res.data.datos[0].sexo
    datospersonales.correo = res.data.datos[0].correo
    datospersonales.celular = res.data.datos[0].celular
    if (res.data.datos[0].fec_nacimiento) {
        const fechaStr = res.data.datos[0].fec_nacimiento.trim();
        const fechaParsed = dayjs(fechaStr, 'YYYY-MM-DD', true);
        if (!fechaParsed.isValid()) {
            console.error("Fecha inválida:", fechaStr);
        } else {
            datospersonales.fec_nacimiento = fechaParsed;
        }
    }
    formState.ubigeo = res.data.datos[0].ubigeo
    datospersonales.ubigeo_nacimiento = res.data.datos[0].ubigeo
    datospersonales.ubigeo_residencia = res.data.datos[0].ubigeo_residencia
    datosresidencia.direccion = res.data.datos[0].direccion
    }

}

function cambiarFormato(fecha) {

  const d = dayjs.isDayjs(fecha) ? fecha : dayjs(fecha, 'YYYY-MM-DD', true);
  if (!d.isValid()) {
    console.error("Fecha inválida en cambiarFormato:", fecha);
    return "";
  }
  return d.format('DD/MM/YYYY');
}

const ultimopaso = ref(null);
const getPasoRegistrado = async () => {
    ultimopaso.value = null;
    let res = await axios.get( "/get-paso-registrado/"+props.procceso_seleccionado.id+"/"+formState.dni);
    if(res.data.estado == true){
        getDatosPersonales2()
        if(res.data.datos.nro == 6){
            consultaInscripcion2()
        }else{
            pagina_pre.value = res.data.datos.nro + 1;
            console.log("PASO::::", res.data.datos);
        }  
    }
    else{
        modalcarrerasprevias.value = true;
        loading.value = true;
        getSancionado();
    }
}


const savePasos =  async (namex, num, avan ) => {
let res = await axios.post(
    "/save-pasos-preinscripcion",
    {
    id: id_pasos.value,
    nombre: namex,
    nro: num,
    avance: avan,
    postulante: datospersonales.id,
    proceso: props.procceso_seleccionado.id
    }
);
getPasos()
}

const saveDNI =  async () => {

let res = await axios.post( "/save-postulante-dni",
    {
        tipo_doc: datospersonales.tipo_doc,
        nro_doc: formState.dni,
        ubigeo_nacimiento: formState.ubigeo_nacimiento,
        paterno: datospersonales.primerapellido,
        materno:datospersonales.segundo_apellido,
        nombres: datospersonales.nombres,
    });
    getDatosPersonales2()
}

const saveDatosPersonales =  async () => {
const values = await formDatosPersonales.value.validateFields();
    let res = await axios.post(
        "/save-postulante-segundas",
        {
        tipo_doc: datospersonales.tipo_doc,
        nro_doc: formState.dni,
        id: datospersonales.id,
        primer_apellido: datospersonales.primerapellido,
        segundo_apellido: datospersonales.segundo_apellido,
        nombres: datospersonales.nombres,
        egreso: datospersonales.egreso,
        correo: datospersonales.correo,
        celular: datospersonales.celular,
        sexo: datospersonales.sexo,
        estado_civil: datospersonales.estado_civil,
        fec_nacimiento: format(new Date(datospersonales.fec_nacimiento), 'yyyy-MM-dd'),
        ubigeo_nacimiento: datospersonales.ubigeo_nacimiento,
        ubigeo_residencia: datospersonales.ubigeo_residencia,
        direccion: datospersonales.direccion,
        proceso: props.procceso_seleccionado.id
        }
    );
    pagina_pre.value = 6;
    if(res.data.estado === true ){
        notificacion(res.data.tipo, res.data.titulo, res.data.mensaje);
        pagina_pre.value = 6;
    }
}

watch(pagina_pre, ( newValue, oldValue ) => {

if(pagina_pre === 1 ){
  getDatosPersonales();
  getDepartamentos();
}
if(newValue === 3 ){
    getDepartamentosColegio();
    getUbigeo();
}
if(newValue === 4 ){
  getApoderado();

}
if(newValue === 5 ){
  if( datospersonales.id ){
    getApoderadoM();
  }
}
if(newValue === 6){
    getProgramas();
}
})

function validateFechaNacimiento(rule, value) {
return new Promise((resolve, reject) => {
    if (!value) {
    reject(new Error('Por favor, selecciona tu fecha de nacimiento.'));
    } else {
    const fechaNacimiento = new Date(value);
    const fechaMinima = new Date();
    fechaMinima.setFullYear(fechaMinima.getFullYear() - 20);

    if (fechaNacimiento > fechaMinima) {
        reject(new Error('Debes tener al menos 20 años.'));
    } else {
        resolve();
    }
    }
});
}

function validateCodigoSecreto(rule, value) {
return new Promise((resolve, reject) => {
    if (!value) {
    reject(new Error('Por favor, ingresa el código secreto.'));
    } else if (codigo_aleatorio.value !== formState.codigo_secreto) {
    reject(new Error('El código ingresado no coincide.'));
    } else {
    resolve();
    }
});
}

const id_pasos = ref(null)
const avance_current = ref(null)

const getPasos = async ( ) => {
    let res = await axios.post( "/get-pasos-proceso",
    { postulante: datospersonales.id,
        proceso: props.procceso_seleccionado.id
    });
    if (res.data.datos.length > 0){
        avance_current.value = res.data.datos[0].avance
        id_pasos.value = null
        pagina_pre.value = res.data.datos[0].nro + 1
        avance.value = res.data.datos[0].avance
        modalcarrerasprevias.value = false;
        loading.value = false;
    }

}

const notificacion = (type, titulo, mensaje) => {  notification[type]({ message: titulo, description: mensaje, });};
const imagen = ref(null);

const submit = async () => {
let fd = new FormData();
fd.append('dni', formState.dni)
fd.append('modalidad', 1)
fd.append('programa', datos_preinscripcion.programa)
fd.append('tipo_certificado', datos_preinscripcion.tipo_certificado)
fd.append('codigo_certificado', datos_preinscripcion.codigo_certificado)
fd.append('codigo_medico', datos_preinscripcion.codigo_medico)
fd.append('id_postulante', datospersonales.id)
fd.append('id_proceso', props.procceso_seleccionado.id)
await axios.post("/save-pre-inscripcion", fd).then(res=>{
    if( avance_current.value < 100){ 
        savePasos("Registro de datos preinscripcion", 6, 110) 
    } else { 
        next() 
    }
    showToast("success","2",res.data.menssje);
}).catch(err=>console.log(err))
open.value = false
}


const presionado = ref(0);
const getDocs = async () => {
    window.open("/pdf-solicitud/"+props.procceso_seleccionado.id+"/"+formState.dni, '_blank');
}

const tipo_docs = { 1: 'DNI', 2: 'PASAPORTE' }
const estados_civil = { 1: 'SOLTERO', 2: 'CASADO', 3: 'VIUDO' }
const sexos = { 1: 'MASCULINO', 2: 'FEMENINO' }

const temp_date = ref(null);

// const cambiarFormato = () => {
//     const fecha = datospersonales.fec_nacimiento.$d;
//     const formattedDate = format(fecha, "dd 'de' MMMM 'del' yyyy", { locale: es }); temp_date.value = formattedDate; 
// };

const props = defineProps(['procceso_seleccionado']);

const sancionado = ref(null)
const modalSancionado = ref(false);

const getSancionado =  async () => {
    participa.value = 0;
    try {
        let res = await axios.get("/get-sancionado/" + formState.dni + "/" + props.procceso_seleccionado.id);
        sancionado.value = res.data.datos;
        if(sancionado.value != null){
            loading.value = false;
            modalSancionado.value = true;
            return;
        }else{
            consultaInscripcion()
        }
    } catch (error) {
        console.error("Error al obtener datos de sancionado", error);
    }
}

const codigo_aleatorio = ref(null);

const getCodigoAleatorio = async ( ) => {
    let res = await axios.get("/generar-captcha");
    codigo_aleatorio.value = res.data.captcha;
}

const postulante_inscrito = ref(0);

const consultaInscripcion = async () => {
    postulante_inscrito.value = 0;
    try {
        let res = await axios.get("/participa-proceso/"+props.procceso_seleccionado.id+"/"+formState.dni);
        if (res.data.estado === true) {
            postulante_inscrito.value = 1;
            modalcarrerasprevias.value = false;
            loading.value = false; 
            pagina_pre.value = 7;
        } else {
            getDataPrisma();
            //getDatosPersonales2()
            participa.value = 1;
        }
    } catch (error) {
        console.error("Error al obtener datos del participante", error);
    } 
}

const consultaInscripcion2 = async () => {
postulante_inscrito.value = 0;
try {
    let res = await axios.get("/participa-proceso/"+props.procceso_seleccionado.id+"/"+formState.dni);
    if (res.data.estado === true) {
        pagina_pre.value = 7;
    }else{
        pagina_pre.value = 6;
    } 
} catch (error) {
    console.error("Error al obtener datos del participante", error);
}
}

getCodigoAleatorio();

const modalcarrerasprevias = ref(false);
const participante = ref(null);

const anteriores = ref([]);

const getDataPrisma = async () => {
    participante.value = null;
    const response = await axios.get('/get-data-prisma/'+formState.dni);
    if(response.data.estado === true){
        participante.value = response.data.datos;
        formState.dni = response.data.datos.dni;
        datospersonales.primerapellido = response.data.datos.paterno;
        datospersonales.segundo_apellido = response.data.datos.materno;
        datospersonales.nombres = response.data.datos.nombre;
    }
    loading.value = false;
    modalSancionado.value = false;
    confirmacion.value = false;
}

const confirmacion = ref(null);

const registrarPrevias = async () => {
    confirmacion.value = null;
    axios.post("/registrar-carreras-previas",{"carreras": selectedItems.value, dni:formState.dni })
    .then((response) => {
        confirmacion.value = response.data.estado;
        modalcarrerasprevias.value = false;
    })
    .catch((error) => {
        if (error.response) {
            console.error('Error de servidor:', error.response.data);
        } else if (error.request) {
            console.error('Error de red:', error.request);
            } else { console.error('Error de configuración:', error.message); }
    });
}

const toggleSelection = (item) => {
    item.selected = !item.selected;
};

const selectedItems = computed(() => {
    if(anteriores.value){
        return anteriores.value.filter((item) => item.selected);
    }
});

const cancelarInscripcion = () => {
    modalcarrerasprevias.value = false;
    location.reload(true);
}


function validateCelular(rule, value) {
  return new Promise((resolve, reject) => {
    if (!value) {
      reject(new Error('Por favor, ingresa tu número de celular.'));
    } else {
      axios.post('/existe-celular',{ celular: value, dni:formState.dni})
        .then(response => {
          if (response.data == true) {
            reject(new Error('Este número de celular ya está registrado.'));
          } else {
            resolve();
          }
        })
        .catch(error => {
          console.error('Error al verificar el número de celular:', error);
          reject(new Error('Error al verificar el número de celular.'));
        });
    }
  });
}

function validateCorreo(rule, value) {
  return new Promise((resolve, reject) => {
    if (!value) {
      reject(new Error('Por favor, ingresa tu correo.'));
    } else {
      axios.post('/existe-correo',{ email: value, dni:formState.dni})
        .then(response => {
          if (response.data == true) {
            reject(new Error('Este correo ya está registrado.'));
          } else {
            resolve();
          }
        })
        .catch(error => {
          console.error('Error al verificar el correo:', error);
          reject(new Error('Error al verificar correo.'));
        });
    }
  });
}

const programas = ref([]);
const getProgramas =  async () => {
  let res = await axios.post( "/get-select-programas-proceso-segundas",{ "id_modalidad":1, "id_proceso": props.procceso_seleccionado.id });
  programas.value = res.data.datos;
}

// const modalidades = ref([]);

// const getModalidades =  async () => {
//   let res = await axios.get( "/get-select-modalidad-proceso-segundas/"+ props.procceso_seleccionado.id);
//   modalidades.value = res.data.datos;
// }


const validationMessage = ref('');

const validateDocuments = async () => {
  try {
    const response = await axios.get('/verificar-documentos-preinscripcion/'+formState.dni+'/'+props.procceso_seleccionado.id);
    const data = response.data;
    
    if (data.estado) {
      validationMessage.value = 'Todos los documentos están completos.';
      return true;  // Se retorna true si está todo bien
    } else {
      if (data.missing.length === 2) {
        validationMessage.value = 'Faltan ambos documentos: foto y título.';
        message.error('Faltan 2 documentos.');
        return false;
      } else if (data.missing.includes(8)) {
        validationMessage.value = 'Falta subir foto.';
        message.error('Falta subir foto.');
        return false;
      } else if (data.missing.includes(7)) {
        validationMessage.value = 'Falta subir título.';
        message.error('Falta subir título.');
        return false;
      }
    }
  } catch (error) {
    console.error('Error en la validación:', error);
    validationMessage.value = 'Error al validar documentos.';
    message.error('Error al validar documentos.');
    return false;
  }
};

const especialidades = [
  { id: 52, nombre: "Segunda Especialidad en Banco de Sangre Hemoterapia" },
  { id: 53, nombre: "Segunda Especialidad en Laboratorio Clínico y Biológicos" },
  { id: 54, nombre: "Segunda Especialidad en Actividades Acuáticas y Entrenamiento en Natación" },
  { id: 55, nombre: "Segunda Especialidad en Ciencias Sociales" },
  { id: 56, nombre: "Segunda Especialidad en Didáctica Universitaria" },
  { id: 57, nombre: "Segunda Especialidad en Docencia en Idioma Extranjero Inglés" },
  { id: 58, nombre: "Segunda Especialidad en Educación Básica Alternativa" },
  { id: 59, nombre: "Segunda Especialidad en Educación Inicial" },
  { id: 60, nombre: "Segunda Especialidad en Educación Intercultural Bilingüe, Aimara y Quechua" },
  { id: 61, nombre: "Segunda Especialidad en Gestión y Administración Educativa" },
  { id: 62, nombre: "Segunda Especialidad en Investigación Educativa" },
  { id: 63, nombre: "Segunda Especialidad en Psicología Educativa" },
  { id: 64, nombre: "Segunda Especialidad en Psicomotricidad" },
  { id: 65, nombre: "Segunda Especialidad en Tecnología Computacional e Informática Educativa" },
  { id: 66, nombre: "Segunda Especialidad en Función Jurisdiccional y Procesal" },
  { id: 67, nombre: "Segunda Especialidad en Cultura y Resolución de Conflictos Sociales" },
  { id: 68, nombre: "Segunda Especialidad en Emergencias y Desastres" },
  { id: 69, nombre: "Segunda Especialidad en Enfermería en Centro Quirúrgico" },
  { id: 70, nombre: "Segunda Especialidad en Enfermería en Crecimiento, Desarrollo y Estimulación Temprana del Niño" },
  { id: 71, nombre: "Segunda Especialidad en Enfermería en Cuidados Intensivos y Urgencias" },
  { id: 72, nombre: "Segunda Especialidad en Enfermería en Gineco Obstetricia" },
  { id: 73, nombre: "Segunda Especialidad en Enfermería en Pediatría y Neonatología" },
  { id: 74, nombre: "Segunda Especialidad en Gerencia y Gestión de Servicios de Salud" },
  { id: 75, nombre: "Segunda Especialidad en Medicina Complementaria" },
  { id: 76, nombre: "Segunda Especialidad en Promoción de la Salud" },
  { id: 77, nombre: "Segunda Especialidad en Formulación y Evaluación de Proyectos de Inversión" },
  { id: 78, nombre: "Segunda Especialidad en Ingeniería Informática" },
  { id: 79, nombre: "Segunda Especialidad en Seguridad, Salud Ocupacional y Medio Ambiente" },
  { id: 80, nombre: "Segunda Especialidad en Ingeniería Sanitaria Ambiental" },
  { id: 81, nombre: "Segunda Especialidad en Anestesiología" },
  { id: 82, nombre: "Segunda Especialidad en Cirugía General" },
  { id: 83, nombre: "Segunda Especialidad en Ginecología y Obstetricia" },
  { id: 84, nombre: "Segunda Especialidad en Medicina de Emergencias y Desastres" },
  { id: 85, nombre: "Segunda Especialidad en Medicina Familiar y Comunitaria" },
  { id: 86, nombre: "Segunda Especialidad en Medicina Interna" },
  { id: 87, nombre: "Segunda Especialidad en Ortopedia y Traumatología" },
  { id: 88, nombre: "Segunda Especialidad en Pediatría" },
  { id: 89, nombre: "Segunda Especialidad en Radiología" },
  { id: 90, nombre: "Segunda Especialidad en Biotecnología de la Reproducción Animal" },
  { id: 91, nombre: "Segunda Especialidad en Camélidos Sudamericanos Domésticos" },
  { id: 92, nombre: "Segunda Especialidad en Salud Pública y Epidemiología" }
];

const descargaReglamento = async () => {
  try {
        const response = await axios.get('/descargar-reglamento/'+props.procceso_seleccionado.id, {
          responseType: 'blob',
        });
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'reglamento.pdf');
        document.body.appendChild(link);
        link.click();
      } catch (error) {
        console.error('Error al descargar el PDF', error);
      }

}

</script>

<style scope>
input[type=file]::file-selector-button {
margin-right: 10px;
border: none;
background: blue  ;
padding: 7px 20px;
border-radius: 5px;
color: #fff;
cursor: pointer;
transition: background .2s ease-in-out;
}

input[type=file]::file-selector-button:hover {
background: #13136b;
}
::-webkit-scrollbar {
width: 7px;
}

/* Track */
::-webkit-scrollbar-track {
background: #f1f1f100;
}

/* Handle */
::-webkit-scrollbar-thumb {
background: #bbbbbb;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
background: #aaaaaa;
}

.btn-vocacional{
background: #13136b;
color: white;
}

/* Handle on hover */
.btn-vocacional:hover {
background: #1f1faf;

}

.boton-anterior { width: 175px; height: 38px; }
.boton-siguiente { width: 175px; height: 38px; border-radius: 5px; background: #020b61 }
.datos-postulacion { display: flex}
.selector-modalidad { width: 250px }
.vocacional {height:calc(100vh - 227px); overflow-y:scroll;}
@media (max-width: 480px), screen and (orientation: portrait) {
.cardInicio { margin-top: -10px; border:none; }
.boton-anterior { width: 50%; }
.boton-siguiente { width: 50%; }
.datos-postulacion { display: block;}
.selector-modalidad { width: 100%; margin-bottom: 10px }
.vocacional {height:calc(100vh - 200px); overflow-y:scroll;}
.btn-vocacional { width:100%;}
}

.datos-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.datos-column {
    width: calc(33.33% - 10px);
    margin-bottom: 20px;
    padding: 10px;
}

.datos-column label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.datos-column input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
.datos-column select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
.datos-column .checkbox{
    width: 20px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}


@media screen and (max-width: 1200px) {
.datos-column {
    width: calc(50% - 10px);
}
}

@media screen and (max-width: 768px) {
.datos-column {
    width: 100%;
}
}

.selected { background-color: #e0e0e06d; }
.deshabilitado { opacity: 0.5;  pointer-events: none; cursor: not-allowed;}
@keyframes rotate {
from {
    transform: rotate(0deg);
}
to {
    transform: rotate(360deg);
}
}

.rotating-svg {
animation: rotate 2s linear infinite; /* Ajusta la duración y el tipo de animación según tus preferencias */
}

.aparecer-div {
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

.aparecer-div-mostrar {
opacity: 1;
}
.no-select {
user-select: none;
-webkit-user-select: none;
-moz-user-select: none;
-ms-user-select: none;
}
</style>