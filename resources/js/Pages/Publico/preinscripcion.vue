<template style="background:pink;">
<Head title="Preinscipción"/>
<Layout v-if="examen === 0">	
	<div style="width: 100%; min-height: calc(100vh - 220px); padding: 20px 20px; margin-top: 0px;">
<!-- 
    {{ nc }} -->
    <div v-if="nc == 1"><pre> {{ presionado = 1 }} {{ nc = 0 }} </pre> </div>

    <div class="flex mt-3 justify-center align-center" style=" width: 100%; min-height: calc(100vh - 215px)">
    <!-- INICIO -->
			<a-card v-if="pagina_pre === 0"  style="width: 100%;  max-width: 350px; max-height:360px" class="pl-3 pr-3 cardInicio" > 
		
        <div>
          <h1 style="font-size: 1.1rem;">Datos de validación</h1>
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
              :rules="[{ required: true, message: 'Por favor ingresa tu DNI', trigger: 'change' }]"
            >
            <a-input v-model:value="formState.dni" @input="dniInput" :maxlength="12" placeholder="N° Documento"/>
          </a-form-item>


          <div class="mb-4" v-if="datospersonales.tipo_doc === 1">
            <div class="mt-3"><label>N° Ubigeo</label></div>
            <a-form-item
              name="ubigeo"
              :rules="[{ required: true, message: 'Por favor ingresa tu ubigeo', trigger: 'change' }]"
              >
              <a-input v-model:value="formState.ubigeo" @input="ubigeoInput" :maxlength="6" placeholder="Ubigeo"/>
            </a-form-item>
          </div>
          <a-card class="mt-3"> 
            <div class="mt-2" ></div>
          </a-card>
          <div style="display: flex; justify-content: center; margin-top: 20px;">
            <a-button type="primary" @click="getDatosPersonales()">Iniciar Postulación</a-button>
          </div>
        </a-form>
		  </a-card>
  	<!-- END INICIO-->



      <div v-if="pagina_pre === 1">
        <!-- {{ datospersonales }} -->

        <div style="width: 100%; margin-top: 5px; margin-bottom: -30px; ">

        <a-card style="padding-top: -5px; padding-bottom:0px;" class="cardInicio">
          <a-form
            ref="formDatosPersonales" :model="datospersonales"
            name="datosPersonales"
            @finish="onFinish"
            @finishFailed="onFinishFailed"
          >
            <div>
            
              <div style="margin-bottom: 0px; margin-top: 0px;">
                <h1 style="font-size: 1.1rem;"> Datos de personales</h1>
              </div>

              <div class="flex align-center justify-end mb-1">
                <div>
                  <div class="mb-1"> Sexo </div>
                  <a-radio-group v-model:value="datospersonales.tipo_doc" class="flex justify-end" name="radioGroup">
                    <a-radio :value="1">M</a-radio>
                    <a-radio :value="2">F</a-radio>
                  </a-radio-group>
                </div>
                <div style="height: 50px; border-right: solid 1px #d4d0d0 ;"></div>
                <div class="ml-3" >
                  <div> Estado civil </div>
                  <a-select
                    ref="select"
                    v-model:value="datospersonales.estado_civil"
                    style="width: 90px"
                  >
                    <a-select-option :value="1">Soltero</a-select-option>
                    <a-select-option :value="2">Casado</a-select-option>
                    <a-select-option :value="3">Viudo</a-select-option>
                    <a-select-option :value="4">Divorciado</a-select-option>
                  </a-select>
                </div>

              </div>

              <a-row :gutter="[16, 0]" class="form-row mb-0" >
                <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                    <div><label>Primer apellido:</label></div>
                    <a-form-item
                      name="primerapellido"
                      :rules="[{ required: true, message: 'Ingresa tu Primer Apellido', trigger: 'change'}]"
                    >
                      <a-input @input="pimerapellidoInput" v-model:value="datospersonales.primerapellido"/>
                    </a-form-item>
                </a-col>
                <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                  <a-form-item>
                    <div><label>Segundo apellido:</label></div>
                    <a-input v-model:value="datospersonales.segundo_apellido" />
                  </a-form-item>
                </a-col>
                <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                  <a-form-item>
                    <div><label>Pre nombres:</label></div>
                    <a-form-item
                      name="nombres"
                      :rules="[{ required: true, message: 'Ingresa tus Prenombres', trigger: 'change'}]"
                    >
                      <a-input @input="nombresInput" v-model:value="datospersonales.nombres" />
                    </a-form-item>
                  </a-form-item>
                </a-col>
              </a-row>

              <a-row :gutter="[16, 0]" class="form-row">
                <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
                  <a-form-item
                      name="correo"
                      :rules="[
                        { required: true, message: 'Ingresa un correo valido', trigger: 'change'},
                        { type: 'email', message: 'Ingresa un correo valido' },
                      ]"
                    >
                    <div><label>Correo:</label></div>
                    <a-input type="email" @input="correoInput"  v-model:value="datospersonales.correo" />
                  </a-form-item>
                </a-col>
              </a-row>

              <a-row :gutter="[16, 0]" class="form-row">
                <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                  <a-form-item
                      name="celular"
                      :rules="[
                        { required: true, message: 'Ingresa tu celular', trigger: 'change'},
                      ]"
                    >
                    <div><label>Numero de celular:</label></div>
                    <a-input @input="celularInput" :maxlength="9" v-model:value="datospersonales.celular" />
                  </a-form-item>
                </a-col>
                <a-col :span="24" :md="16" :lg="18  " :xl="16" :xxl="6">
                  <a-form-item>
                    <div><label>Fec. Nacimiento:</label></div>
                      <a-date-picker placeholder="Selecciona tu fecha de nacimiento" style="width: 100%" v-model:value="datospersonales.fec_nacimiento" format='DD/MM/YYYY' />
                  </a-form-item>
                </a-col>
              </a-row>

            </div>
          </a-form>
        </a-card>
      </div>
      </div>


      <div v-if="pagina_pre === 2">


        <div style="width: 100%; margin-top: 5px; ">

        <a-card style="padding-top: -5px; padding-bottom:0px;" class="cardInicio">
          <div>
          
            <!-- //DATOS DE RESIDENCIA -->

            <div style="margin-bottom: 25px; margin-top: 10px; ">
              <h1 style="font-size: 1.1rem;"> Datos de residencia</h1>
            </div>

            <!-- {{ datospersonales.ubigeo_residencia }}
            dep {{ depseleccionado }}
            {{ provseleccionada }}
            {{ distseleccionado }} -->

            <a-row :gutter="[16, 0]" class="form-row">
              <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                <a-form-item>
                  <div><label>Departamento: {{ dep }}</label></div>
     

                  <a-auto-complete
                      v-model:value="dep"                
                      :options="departamentos"
                      @select="onSelectDepartamentos"
                  >
                      <a-input
                          placeholder="Departamento"
                          v-model:value="buscarDep"
                          @keypress="handleKeyPress"
                      >
                      <template #suffix>
                      <a-tooltip title="Extra information">
                        <down-outlined />
                      </a-tooltip>
                    </template>
                    </a-input>
                  </a-auto-complete>



                </a-form-item>
              </a-col>
              <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                <a-form-item>
                  <div><label>Provincia:</label></div>
                  <a-auto-complete
                      v-model:value="prov"                
                      :options="provincias"
                      @select="onSelectProvincias"
                  >
                      <a-input
                          placeholder="Provincia"
                          v-model:value="buscarProv"
                          @keypress="handleKeyPress"
                      >
                      <template #suffix>
                      <a-tooltip title="Extra information">
                        <down-outlined />
                      </a-tooltip>
                    </template>
                    </a-input>
                  </a-auto-complete>
                </a-form-item>
              </a-col>
              <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                <a-form-item>
                  <div><label>Distrito:</label></div>

                  <a-auto-complete
                      v-model:value="dist"                
                      :options="distritos"
                      @select="onSelectDistritos"
                  >
                      <a-input
                          placeholder="Provincia"
                          v-model:value="buscarDist"
                          @keypress="handleKeyPress"
                      >
                      <template #suffix>
                      <a-tooltip title="Extra information">
                        <down-outlined />
                      </a-tooltip>
                    </template>
                    </a-input>
                  </a-auto-complete>

                </a-form-item>
              </a-col>
            </a-row>


            <a-row :gutter="[16, 0]" class="form-row">
              <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
                <a-form-item>
                  <div><label>Dirección:</label></div>
                  <a-input v-model:value="datospersonales.direccion" />
                </a-form-item>
              </a-col>
            </a-row>

            <a-row :gutter="[16, 0]" class="form-row" style="margin-top: 15px; ">
              <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
                <div class="flex" style="justify-content: space-between;">
                  <a-button v-if="current > 0" @click="prev()" >Anterior</a-button>
                  <a-button v-if="current < 2 " @click="saveDatosPersonales()" type="primary" >Siguiente</a-button>    
                </div>
              </a-col>
            </a-row>

          </div>

        </a-card>
      </div>
      </div>

      <div v-if="pagina_pre === 3">

        <Colegio v-if="avance_current < 48"  ref="hijoComponent" :id_postulante="datospersonales.id" :actualiza="'si'" />
        <Colegio v-if="avance_current >= 48" ref="hijoComponent" :id_postulante="datospersonales.id" :actualiza="'no'"/>
        <div style="display:none;">{{  pagina_pre = pagina_pre_temp }}</div>
        <div style="display:none;">{{  pagina_pre_temp = 3 }}</div>


      </div>

      <div v-if="pagina_pre === 4">

        <div style="width: 100%; margin-top: 5px; ">
          <Apoderado v-if="avance_current < 65" ref="padreComponent" :id_postulante="datospersonales.id" :tipex="1" :actualiza="'si'"/>
          <Apoderado v-if="avance_current >= 65" ref="padreComponent" :id_postulante="datospersonales.id" :tipex="1" :actualiza="'no'"/>
            <div style="display:none;">{{ pagina_pre = pagina_pre_temp_padre }}</div>
            <div style="display:none;">{{ pagina_pre_temp_padre = 4 }}</div>

        </div>
      </div>

      <div v-if="pagina_pre === 5">
        <div style="width: 100%; margin-top: 5px; ">
          <Apoderado v-if="avance_current < 80" ref="madreComponent" :id_postulante="datospersonales.id" :tipex="2" :actualiza="'si'"/>
          <Apoderado v-if="avance_current >= 80" ref="madreComponent" :id_postulante="datospersonales.id" :tipex="2" :actualiza="'no'"/>
            <div style="display:none;">{{ pagina_pre = pagina_pre_temp_madre }}</div>
            <div style="display:none;">{{ pagina_pre_temp_madre = 5 }}</div>
        </div>
      </div>

      <div v-if="pagina_pre === 6">
        <div style="width: 100%; margin-top: 5px; ">

          <a-card style="padding-top: -5px; padding-bottom:0px;" class="cardInicio">
            <div>

              <div>


              <div class="justify-between datos-postulacion" >
                  <div style="margin-bottom: 25px; margin-top: 10px; ">
                    <h1 style="font-size: 1.1rem;"> Datos Postulación</h1>
                  </div>
                  <div class="mb-3">
                    <div><label>Modalidad</label></div>
                     <a-select
                        ref="select"
                        v-model:value="datos_preinscripcion.modalidad"
                        placeholder="Seleccionar modalidad"
                        class="selector-modalidad"
                      >
                        <a-select-option :value="9">CEPREUNA</a-select-option>
                      </a-select>
                  </div>
                </div>
              </div>


              <a-row :gutter="[16, 0]" class="form-row">
                <a-col :span="24" :md="16" :lg="12" :xl="24" :xxl="6">
                  <a-form-item>
                    <div><label>Programa de estudios</label></div>  
                    <a-select
                        ref="select"
                        v-model:value="datos_preinscripcion.programa"
                        placeholder="Seleccionar programa"
                        class="selector-modalidad"
                      >
                        <a-select-option :value='1'>ADMINISTRACIÓN</a-select-option>
                        <a-select-option :value='2'>ANTROPOLOGÍA</a-select-option>
                        <a-select-option :value='3'>ARQUITECTURA Y URBANISMO</a-select-option>
                        <a-select-option :value='4'>ARTE: ARTES PLÁSTICAS</a-select-option>
                        <a-select-option :value='5'>ARTE: DANZA</a-select-option>
                        <a-select-option :value='6'>ARTE: MÚSICA</a-select-option>
                        <a-select-option :value='8'>BIOLOGÍA: ECOLOGÍA</a-select-option>
                        <a-select-option :value='9'>BIOLOGÍA: MICROBIOLOGÍA Y LABORATORIO CLÍNICO</a-select-option>
                        <a-select-option :value='10'>BIOLOGÍA: PESQUERÍA</a-select-option>
                        <a-select-option :value='11'>CIENCIAS CONTABLES</a-select-option>
                        <a-select-option :value='12'>CIENCIAS DE LA COMUNICACIÓN SOCIAL</a-select-option>
                        <a-select-option :value='13'>CIENCIAS FÍSICO MATEMÁTICAS: FÍSICA</a-select-option>
                        <a-select-option :value='14'>CIENCIAS FÍSICO MATEMÁTICAS: MATEMÁTICAS</a-select-option>
                        <a-select-option :value='15'>DERECHO</a-select-option>
                        <a-select-option :value='16'>EDUCACIÓN FÍSICA</a-select-option>
                        <a-select-option :value='17'>EDUCACIÓN INICIAL</a-select-option>
                        <a-select-option :value='18'>EDUCACIÓN PRIMARIA</a-select-option>
                        <a-select-option :value='19'>EDUCACIÓN SECUNDARIA DE LA ESPECIALIDAD DE CIENCIA, TECNOLOGÍA Y AMBIENTE</a-select-option>
                        <a-select-option :value='20'>EDUCACIÓN SECUNDARIA DE LA ESPECIALIDAD DE CIENCIAS SOCIALES</a-select-option>
                        <a-select-option :value='21'>EDUCACIÓN SECUNDARIA DE LA ESPECIALIDAD DE LENGUA, LITERATURA, PSICOLOGÍA Y FILOSOFÍA</a-select-option>
                        <a-select-option :value='22'>EDUCACIÓN SECUNDARIA DE LA ESPECIALIDAD DE MATEMÁTICA, FÍSICA, COMPUTACIÓN E INFORMÁTICA</a-select-option>
                        <a-select-option :value='23'>ENFERMERÍA</a-select-option>
                        <a-select-option :value='24'>INGENIERÍA AGRÍCOLA</a-select-option>
                        <a-select-option :value='25'>INGENIERÍA AGROINDUSTRIAL</a-select-option>
                        <a-select-option :value='26'>INGENIERÍA AGRONÓMICA</a-select-option>
                        <a-select-option :value='27'>INGENIERÍA CIVIL</a-select-option>
                        <a-select-option :value='28'>INGENIERÍA DE MINAS</a-select-option>
                        <a-select-option :value='29'>INGENIERÍA DE SISTEMAS</a-select-option>
                        <a-select-option :value='30'>INGENIERÍA ECONÓMICA</a-select-option>
                        <a-select-option :value='31'>INGENIERÍA ELECTRÓNICA</a-select-option>
                        <a-select-option :value='32'>INGENIERÍA ESTADÍSTICA E INFORMÁTICA</a-select-option>
                        <a-select-option :value='33'>INGENIERÍA GEOLÓGICA</a-select-option>
                        <a-select-option :value='34'>INGENIERÍA MECÁNICA ELÉCTRICA</a-select-option>
                        <a-select-option :value='35'>INGENIERÍA METALÚRGICA</a-select-option>
                        <a-select-option :value='36'>INGENIERÍA QUÍMICA</a-select-option>
                        <a-select-option :value='37'>INGENIERÍA TOPOGRÁFICA Y AGRIMENSURA</a-select-option>
                        <a-select-option :value='38'>MEDICINA HUMANA</a-select-option>
                        <a-select-option :value='39'>MEDICINA VETERINARIA Y ZOOTECNIA</a-select-option>
                        <a-select-option :value='40'>NUTRICIÓN HUMANA</a-select-option>
                        <a-select-option :value='41'>ODONTOLOGÍA</a-select-option>
                        <a-select-option :value='42'>SOCIOLOGÍA</a-select-option>
                        <a-select-option :value='43'>TRABAJO SOCIAL</a-select-option>
                        <a-select-option :value='44'>TURISMO</a-select-option>
                    </a-select>

                  </a-form-item>
                </a-col>
                
                <a-col v-if="datos_preinscripcion.programa === 38 || datos_preinscripcion.programa === 16" :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                  <a-form-item>
                    <div><label>Cod. de examen médico:</label></div>
                    <a-input placeholder="Cod cert Examen médico" v-model:value="datos_preinscripcion.codigo_medico"/>
                  </a-form-item>
                </a-col>
                <a-col :span="24" :md="24" :lg="24" :xl="12" :xxl="6">
                  <a-form-item>
                    <div><label>Tipo de certificado:</label></div>
                    <a-select
                        ref="select"
                        v-model:value="datos_preinscripcion.tipo_certificado"
                        placeholder="Seleccionar tipo de certificado"
                        class="selector-modalidad" >
                        <a-select-option value='CERTIFICADO BLANCO'>CERTIFICADO BLANCO</a-select-option>
                        <a-select-option value='CERTIFICADO AMARILLO'>CERTIFICADO AMARILLO</a-select-option>
                        <a-select-option value='CONSTANCIA DE ESTUDIOS'>CONSTANCIA DE ESTUDIOS</a-select-option>
                    </a-select>
                  </a-form-item>
                </a-col>
                <a-col :span="24" :md="24" :lg="24" :xl="12" :xxl="6">
                  <a-form-item>
                    <div><label>Codigo de certificado:</label></div>
                    <a-input v-model:value="datos_preinscripcion.codigo_certificado" />
                  </a-form-item>
                </a-col>
              </a-row>

              <a-row :gutter="[16, 0]" class="form-row">
                <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="6">
                  <form @submit.prevent="preInscribir">
                    <div class="flex justify-between">
                      <input type="file" @change="onChange"/>

                    </div>
                  </form>
                </a-col>


              </a-row>

            </div>

          </a-card>
          </div>
      </div>

      <div v-if="pagina_pre === 7">
        <div style="width: 100%; ">
          <a-card style="padding-top: 5px; padding-bottom:0px; background: #24c1ff25;" >
            <div class="">
              <div class="flex justify-center"  >
                <img src="../../../assets/imagenes/check.png" width="160"/>
              </div>
              <div class="flex justify-center">
                <div  style="text-align:center; max-width: 350px;" >
                  Felicidades sus datos han sido regisrados con exito, complete el examen vocacional y obtenga 
                  su constancia vocacional su hoja de preinscripción
                </div>
              </div>
              <div class="flex justify-center mt-4 mb-4">
                <a-button @click="examen = 1" style="background: #020b61;" type="primary"> Iniciar examen vocacional</a-button>
              </div>
            </div>
          </a-card>
          </div>
      </div>


      <div v-if="pagina_pre === 8">
        <div style="width: 100%; height:calc(100vh - 300px); display:flex; align-items:center; ">
          <a-card style="padding-top: 5px; padding-bottom:0px; background: #24c1ff25;">
            <div class="">
              <div class="flex justify-center">
                <div  style="text-align:center; max-width: 350px;" >
                  <div>FINALIZASTE CON EXITO</div>
                  DESCARGA TUS DOCUMENTOS PRESIONANDO EN DESCARGAR 
                </div>
              </div>
              <div class="flex justify-center mt-4 mb-4">
                <a-button @click="getDocs()" style="background: #020b61;" type="primary"> DESCARGAR </a-button>
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
      <div v-if="pagina_pre !== 7 && pagina_pre !== 8">
        <a-progress :percent="avance"/>
      </div>

      <div class="flex" style="justify-content: space-between;" v-if="pagina_pre === 1">
        <a-button @click="prev()" class="boton-anterior">Anterior</a-button>
        <a-button @click="saveDatosPersonales()" class="boton-siguiente" type="primary" >Siguiente</a-button>    
      </div>

      <div class="flex" style="justify-content: space-between;" v-if="pagina_pre === 2">
        <a-button @click="prev()" class="boton-anterior">Anterior</a-button>
        <a-button @click="saveDatosResidencia()" class="boton-siguiente" type="primary" >Siguiente</a-button>    
      </div>

      <div class="flex" style="justify-content: space-between;" v-if="pagina_pre === 3">
        <a-button @click="prev()" class="boton-anterior">Anterior</a-button>
        <a-button @click="guardarColegio()" class="boton-siguiente" type="primary" >Siguiente</a-button>
      </div>

      <div class="flex" style="justify-content: space-between;" v-if="pagina_pre === 4">
        <a-button @click="prev()" class="boton-anterior">Anterior</a-button>
        <a-button @click="guardarApoderadoPadre()" class="boton-siguiente" type="primary" >Siguiente</a-button>    
      </div>
      <div class="flex" style="justify-content: space-between;" v-if="pagina_pre === 5">
        <a-button @click="prev()" class="boton-anterior">Anterior</a-button>
        <a-button @click="guardarApoderadoMadre()" class="boton-siguiente" type="primary" >Siguiente</a-button>    
      </div>
      <div class="flex" style="justify-content: space-between;" v-if="pagina_pre === 6">
        <a-button @click="prev()" class="boton-anterior">Anterior</a-button>
        <a-button html-type="submit" @click="submit" type="primary" class="boton-siguiente">Finalizar</a-button>    
      </div>
    </a-affix>
	
</Layout>

<div v-if="examen === 1">
  <div v-if="pagina_pre === 8" style="display:none;">{{  pagina_pre }} {{  examen = 0 }} </div>
  <div v-if="nc == 1"><pre> {{ presionado = 1 }} {{ nc = 0 }} </pre> </div>
  <div class="headVocalional" v-if="pagina_pre === 7" >
        <div class="logoVocalional">  
            <div> <img src="../../../assets/imagenes/logotiny.png" width="45"/> </div>
            <div class="x"> 
                <div class="container-pre"><span style="letter-spacing: 0.1rem;">DIRECCIÓN DE</span>
            </div> 
            <h1 class="logoVocalionalAD" > ADMISIÓN </h1> </div>
            <div> <img src="../../../assets/imagenes/logoDAD.png" width="45" /> </div>
        </div>
        <div class="flex justify-center titulo-pre ">
            <div>  
                <span> Examen Vocacional </span>
                <div class="flex justify-center items-center"> <hr class="line-preV"><div style="font-weight: bold; color: white; font-size: 1.1rem"> CEPREUNA </div>  <hr class="line-preV"> </div>
            </div>
        </div>

        <div style="background: white; border-radius: 20px 20px 0px 0px; margin-top:20px; margin-bottom:-15px;  padding: 20px; padding-top:20px; padding-right:0px;">
          
    <!-- {{ nc }} -->
          <div class="vocacional">
            <Vocacional v-if="avance_current < 110" ref="vocacionalComponent" :id_postulante="datospersonales.id" :dni="dni.value" :actualiza="'si'"/>
            <Vocacional v-if="avance_current >= 110" ref="vocacionalComponent" :id_postulante="datospersonales.id" :dni="dni.value" :actualiza="'no'"/>
            <div style="display:none;">{{ pagina_pre = pagina_pre_temp_vocacional }}</div>
            <div style="display:none;">{{ pagina_pre_temp_vocacional = 7 }}</div>
          </div>
        </div>

        <div class="flex" style="justify-content: flex-end;">
          <a-button class="btn-vocacional" @click=" guardarVocacional()"  >Terminar examen</a-button>
        </div>  
        
  </div>
    <!-- <div>
    <a-button @click="guardarColegio()" class="boton-siguiente" type="primary" >Terminar examen</a-button>
  </div> -->

</div>

</template>
<script setup>
import Layout from '@/Layouts/LayoutPreinscripcion.vue'    
import { watch, getCurrentInstance, provide, reactive, computed, ref, unref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { format } from 'date-fns';
import { notification } from 'ant-design-vue';


//import Colegio from "./components/datos_colegio.vue"

const examen = ref(0);
const modvocacional = true;
const avance = ref(0)
const bottom = ref(2)

const pagina_pre = ref(0)
const next = () => { pagina_pre.value++; }
const prev = () => { pagina_pre.value--; }
const dni = ref("70757838")



const formRef = ref();
const formState = reactive({
  dni: '',
  ubigeo: '',
});
const formDatosPersonales = ref();
const datospersonales = reactive({
  id: null,
  tipo_doc:1,
  primerapellido: "",
  segundo_apellido: "",
  nombres:"",
  estado_civil:1,
  sexo:1,
  correo:"",
  celular:'',
  fec_nacimiento:"",
  ubigeo:"",
  ubigeo_residencia:"",
  direccion:""
});

const dniInput = (event) => { formState.dni = event.target.value.replace(/\D/g, ''); };
const ubigeoInput = (event) => { formState.ubigeo = event.target.value.replace(/\D/g, ''); };
const nombresInput = (event) => { datospersonales.nombres = event.target.value.replace(/[^A-Za-z\s]/g, '');};
const pimerapellidoInput = (event) => { datospersonales.primerapellido = event.target.value.replace(/[^A-Za-z]/g, '');};
const celularInput = (event) => { datospersonales.celular = event.target.value.replace(/\D/g, ''); };


const departamentos = ref([])
const dep = ref(null);
const buscarDep = ref("")
const  depseleccionado = ref('')
watch(buscarDep, ( newValue, oldValue ) => {
    getDepartamentos()
})
const onSelectDepartamentos = (value, option) => {
    depseleccionado.value = option.key;
    getProvincias()
};

const provincias = ref([])
const prov = ref(null);
const buscarProv = ref("")
const provseleccionada = ref(null)
watch(buscarProv, ( newValue, oldValue ) => {
    getProvincias()
})
const onSelectProvincias = (value, option) => {
    provseleccionada.value = option.key;
    getDistritos()
};

const distritos = ref([])
const dist = ref(null);
const buscarDist = ref("")
const distseleccionado = ref('')
const onSelectDistritos = (value, option) => {
    distseleccionado.value = option.key;
    //getDistritos()
};

const getDatosPersonales = async () => {
  const values = await formRef.value.validateFields();
  let res = await axios.post( "get-postulante-datos-personales", {nro_doc: formState.dni});


  if(res.data.datos.length > 0 ) {
    datospersonales.id = res.data.datos[0].id
    datospersonales.primerapellido = res.data.datos[0].primer_apellido
    datospersonales.segundo_apellido = res.data.datos[0].segundo_apellido
    datospersonales.nombres = res.data.datos[0].nombres
    //datospersonales.estado_civil = res.data.datos[0].estado_civil
    //datospersonales.sexo = res.data.datos[0].sexo
    datospersonales.correo = res.data.datos[0].correo
    datospersonales.celular = res.data.datos[0].celular
    if(res.data.datos[0].fec_nacimiento){ datospersonales.fec_nacimiento = dayjs(res.data.datos[0].fec_nacimiento) }
    formState.ubigeo = res.data.datos[0].ubigeo
    datospersonales.direccion = res.data.datos[0].direccion
    depseleccionado.value = res.data.datos[0].dep;
    dep.value = res.data.datos[0].departamento
    provseleccionada.value = res.data.datos[0].prov;
    prov.value = res.data.datos[0].provincia
    distseleccionado.value = res.data.datos[0].dist;
    dist.value = res.data.datos[0].distrito
    datospersonales.ubigeo_residencia = res.data.datos[0].ubigeo_residencia
    datospersonales.direccion = res.data.datos[0].direccion
    getPasos();
  } else {
    saveDNI()
    pagina_pre.value = 1
  }

  

} 



const savePasos =  async (namex, num, avan ) => {

  let res = await axios.post(
    "save-pasos-preinscripcion",
    { 
      id: id_pasos.value,
      nombre: namex, 
      nro: num,
      avance: avan,
      postulante: datospersonales.id,
      proceso: 4
    }
  );
  getPasos()
}

const saveDNI =  async () => {
  let res = await axios.post( "save-postulante-dni", 
  {  
    tipo_doc: datospersonales.tipo_doc,
    nro_doc: formState.dni,
    id: datospersonales.id,
    ubigeo_nacimiento: datospersonales.ubigeo,
  });
  getDatosPersonales()
}

const saveDatosPersonales =  async () => {
  // if(depseleccionado.value !== null && provseleccionada.value !== null && distseleccionado.value !== null ) {
  //   //console.log(depseleccionado.value + provseleccionada.value + distseleccionado.value)
  //   datospersonales.value.ubigeo_residencia = depseleccionado.value + provseleccionada.value + distseleccionado.value
  // }
  const values = await formDatosPersonales.value.validateFields();

  let res = await axios.post(
    "save-postulante",
    {  
      tipo_doc: datospersonales.tipo_doc,
      nro_doc: formState.dni,
      id: datospersonales.id,
      primer_apellido: datospersonales.primerapellido, 
      segundo_apellido: datospersonales.segundo_apellido,  
      nombres: datospersonales.nombres, 
      correo: datospersonales.correo, 
      celular: datospersonales.celular, 
      sexo: datospersonales.sexo,
      estado_civil: datospersonales.estado_civil,  
      fec_nacimiento: format(new Date(datospersonales.fec_nacimiento), 'yyyy-MM-dd'),
      ubigeo_nacimiento: formState.ubigeo,
      // ubigeo_residencia: datospersonales.ubigeo_residencia,
      direccion: datospersonales.direccion 
    }
  );
  if( avance_current.value < 16){ savePasos("Registro de datos personales", 1, 16) } else{ next() }
  if(res.data.estado === true ){  
    notificacion(res.data.tipo, res.data.titulo, res.data.mensaje)
  }
// roles.value = res.data.datos.data;
}

const saveDatosResidencia =  async () => {

  if(depseleccionado.value !== null && provseleccionada.value !== null && distseleccionado.value !== null ) {
    datospersonales.value.ubigeo_residencia = depseleccionado.value + provseleccionada.value + distseleccionado.value
  }

  let res = await axios.post(
    "save-postulante-residencia",
    {  
      id: datospersonales.value.id,
      ubigeo_residencia: datospersonales.value.ubigeo_residencia,
      direccion: datospersonales.value.direccion 
    }
  );
  if(res.data.estado === true ){  
    notificacion(res.data.tipo, res.data.titulo, res.data.mensaje)
  }
  if( avance_current.value < 32){ savePasos("Registro de datos de residencia", 2, 32) } else{ next() }

}


watch(pagina_pre, ( newValue, oldValue ) => {

  if(pagina_pre === 2 ){
    getDatosPersonales();
    getDepartamentos();
  }
  else {
    return
  }

})


const getDepartamentos = async () => {
  let res = await axios.post( "get-departamentos-codigo?page=", { term: buscarDep.value});
  departamentos.value = res.data.datos.data
} 

const getProvincias = async (depp) => {
  let res = await axios.post( "get-provincias-codigo?page=", {departamento: depseleccionado.value });
  provincias.value = res.data.datos
} 

const getDistritos = async (depp) => {
  let res = await axios.post( "get-distritos-codigo?page=", 
    { 
      departamento: depseleccionado.value,
      provincia: provseleccionada.value    
    }
  );
  distritos.value = res.data.datos
}

const id_pasos = ref(null)
const avance_current = ref(null)
const getPasos = async (depp) => {
  let res = await axios.post( "get-pasos-proceso", 
    { postulante: datospersonales.id,
      proceso: 4
    });
    if (res.data.datos.length > 0){
      avance_current.value = res.data.datos[0].avance  
      id_pasos.value = null
      pagina_pre.value = res.data.datos[0].nro + 1
      avance.value = res.data.datos[0].avance 
    }else{
      pagina_pre.value = 1;
    }

}

const notificacion = (type, titulo, mensaje) => {
  notification[type]({
    message: titulo,
    description: mensaje,
  });
};

// Define los eventos que puede emitir el componente padre
// Define los eventos que puede emitir el componente padre
const emits = defineEmits(['ejecutarFuncionHijo']);

// Función del componente padre para manejar el evento emitido por el componente hijo
const funcionHijoEjecutada = () => {
  console.log('Función del componente hijo ejecutada en el componente padre');
};

const datos_preinscripcion = ref({
  modalidad: 9,
  programa:null,
  tipo_certificado:null,
  codigo_medico: null,
  codigo_certificado:null,
})


const certificado = ref(null);

const imagen = ref(null);
const onChange = (e) => {
  console.log("Selected file", e.target.files[0])
  imagen.value = e.target.files[0];
}
const submit = async () => {
  let fd = new FormData();
  fd.append('img', imagen.value)
  fd.append('dni', dni.value)
  fd.append('modalidad', datos_preinscripcion.value.modalidad)
  fd.append('programa', datos_preinscripcion.value.programa)
  fd.append('tipo_certificado', datos_preinscripcion.value.tipo_certificado)
  fd.append('codigo_certificado', datos_preinscripcion.value.codigo_certificado)
  fd.append('codigo_medico', datos_preinscripcion.value.codigo_medico)
  fd.append('id_postulante', datospersonales.value.id)
  await axios.post("save-pre-inscripcion", fd).then(res=>{
    if( avance_current.value < 100){ savePasos("Registro de datos preinscripcion", 6, 100) } else{ next() }
    showToast("success","2",res.data.menssje);
    getResoluciones()
  }).catch(err=>console.log(err))
}
const presionado = ref(0);
watch(presionado, ( newValue, oldValue ) => {
  if ( presionado.value === 1){
    getPasos()
    presionado.value = 0
  }
})

const getDocs = async () => {
  window.open("documentos-pdfs/"+formState.dni, '_blank');
}


</script>

<script>
import Colegio from "./components/datos_colegio.vue"
import Apoderado from "./components/apoderado.vue"
import Vocacional from "./components/exvocacional.vue"

export default {
  components: {
    Colegio, Apoderado, Vocacional  
  },
  data() {
    return { 
      pagina_pre_temp: 3,
      pagina_pre_temp_padre: 4,
      pagina_pre_temp_madre: 5,
      pagina_pre_temp_vocacional: 7,
      nc:0
    }
  },

  methods: {

    async guardarColegio() {
      this.nc = await this.$refs.hijoComponent.ejecutarMetodo();
      this.pagina_pre_temp = 4;
      console.log(this.nc);
    },
    async guardarApoderadoPadre() {
      this.nc = await this.$refs.padreComponent.saveApoderado();
      this.pagina_pre_temp_padre = 5;
    },
    async guardarApoderadoMadre() {
      this. nc = await this.$refs.madreComponent.saveApoderadoMadre();
      this.pagina_pre_temp_madre = 6;
    },

    async guardarVocacional() {
      this. nc = await this.$refs.vocacionalComponent.saveVocacional();
      this.pagina_pre_temp_vocacional = 8;
    }
  },
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

</style>