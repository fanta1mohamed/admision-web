<template>
<Head title="Preinscipción"/>
<Layout>	

	<div style="width: 100%; min-height: calc(100vh - 220px); padding: 20px 20px; margin-top: 0px;">

    <div class="flex mt-3 justify-center align-center" style=" width: 100%; min-height: calc(100vh - 215px)">
    <!-- INICIO -->
			<a-card v-if="pagina_pre === 0"  style="width: 100%;  max-width: 350px; max-height:360px" class="pl-3 pr-3 cardInicio" > 
			
        <div>
          <h1 style="font-size: 1.1rem;">Datos de validación</h1>
        </div>
        <a-radio-group v-model:value="datos_personales.tipo_doc" class="flex justify-end" style="display: flex; width: yellow;" name="radioGroup">
          <a-radio :value="1">Dni</a-radio>
          <a-radio :value="3">Carnet Ext.</a-radio>
        </a-radio-group>
        
        <div style="margin-bottom: 7px;"><label>N° Documento</label></div>
        <a-input v-model:value="dni" placeholder="N° Documento"/>

        <div class="mb-4" v-if="datos_personales.tipo_doc === 1">
          <div class="mt-3"><label>N° Ubigeo</label></div>
          <a-input v-model:value="datos_personales.ubigeo" placeholder="Ubigeo"/>
        </div>
        <a-card class="mt-3"> 
          <div class="mt-2" ></div>
        </a-card>
        <div style="display: flex; justify-content: center; margin-top: 20px;">
          <a-button type="primary" @click="getDatosPersonales()">Iniciar Postulación</a-button>
        </div>

		  </a-card>
  	<!-- END INICIO-->

      <div v-if="pagina_pre === 1">
        <!-- {{ datos_personales }} -->

        <div style="width: 100%; margin-top: 5px; margin-bottom: -30px; ">

        <a-card style="padding-top: -5px; padding-bottom:0px;" class="cardInicio">
          <div>
          
            <div style="margin-bottom: 0px; margin-top: 0px;">
              <h1 style="font-size: 1.1rem;"> Datos de personales</h1>
            </div>

            <div class="flex align-center justify-end mb-2">
              <div>
                <div class="mb-1"> Sexo </div>
                <a-radio-group v-model:value="datos_personales.tipo_doc" class="flex justify-end" name="radioGroup">
                  <a-radio :value="1">M</a-radio>
                  <a-radio :value="2">F</a-radio>
                </a-radio-group>
              </div>
              <div style="height: 50px; border-right: solid 1px #d4d0d0 ;"></div>
              <div class="ml-3" >
                <div> Estado civil </div>
                <a-select
                  ref="select"
                  v-model:value="datos_personales.estado_civil"
                  style="width: 90px"
                >
                  <a-select-option :value="1">Soltero</a-select-option>
                  <a-select-option :value="2">Casado</a-select-option>
                  <a-select-option :value="3">Viudo</a-select-option>
                  <a-select-option :value="4">Divorciado</a-select-option>
                </a-select>
              </div>

            </div>

            <a-row :gutter="[16, 0]" class="form-row">
              <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                <a-form-item>

                  <div><label>Primer apellido:</label></div>
                  <a-input v-model:value="datos_personales.primer_apellido" />
                </a-form-item>
              </a-col>
              <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                <a-form-item>
                  <div><label>Segundo apellido:</label></div>
                  <a-input v-model:value="datos_personales.segundo_apellido" />
                </a-form-item>
              </a-col>
              <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                <a-form-item>
                  <div><label>Pre nombres:</label></div>
                  <a-input v-model:value="datos_personales.nombres" />
                </a-form-item>
              </a-col>
            </a-row>

            <a-row :gutter="[16, 0]" class="form-row">
              <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
                <a-form-item>
                  <div><label>Correo:</label></div>
                  <a-input v-model:value="datos_personales.correo" />
                </a-form-item>
              </a-col>
            </a-row>

            <a-row :gutter="[16, 0]" class="form-row">
              <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                <a-form-item>
                  <div><label>Numero de celular:</label></div>
                  <a-input v-model:value="datos_personales.celular" />
                </a-form-item>
              </a-col>
              <a-col :span="24" :md="16" :lg="18  " :xl="16" :xxl="6">
                <a-form-item>
                  <div><label>Fec. Nacimiento:</label></div>
                    <a-date-picker style="width: 100%" v-model:value="datos_personales.fec_nacimiento" format='DD/MM/YYYY' />
                </a-form-item>
              </a-col>
            </a-row>

          </div>

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

            {{ datos_personales.ubigeo_residencia }}
            dep {{ depseleccionado }}
            {{ provseleccionada }}
            {{ distseleccionado }}

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
                  <a-input v-model:value="datos_personales.direccion" />
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

        <Colegio  ref="hijoComponent" :id_postulante="datos_personales.id"/>

        <div>{{  pagina_pre = pagina_pre_temp }}</div>
        <div>{{  pagina_pre_temp = 3 }}</div>

        <!-- <div style="width: 100%; margin-top: 5px; ">

        <a-card style="padding-top: -5px; padding-bottom:0px;" class="cardInicio">
          <div>

            <div style="margin-bottom: 25px; margin-top: 10px; ">
              <h1 style="font-size: 1.1rem;"> Datos del colegio</h1>
            </div>

            <a-row :gutter="[16, 0]" class="form-row">
              <a-col :span="24" :md="16" :lg="12" :xl="16" :xxl="6">
                <a-form-item>
                  <div><label>Año de egreso: {{ dep }}</label></div>
     
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
                  <div><label>Pais:</label></div>
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
            </a-row>

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
                  <div><label>Nombre de colegio:</label></div>
                  <a-input v-model:value="datos_personales.direccion" />
                </a-form-item>
              </a-col>
            </a-row>

          </div>

        </a-card>
        </div> -->
      
      </div>


      <div v-if="pagina_pre === 4">

        <div style="width: 100%; margin-top: 5px; ">

        <Apoderado ref="padreComponent" :id_postulante="datos_personales.id" :tipex="1"/>
        <!-- <a-card style="padding-top: -5px; padding-bottom:0px;" class="cardInicio">
          <div>

            <div style="margin-bottom: 25px; margin-top: 10px; ">
              <h1 style="font-size: 1.1rem;"> Datos del padre</h1>
            </div>
            <a-radio-group v-model:value="datos_personales.tipo_doc" class="flex justify-end" style="display: flex; width: yellow;" name="radioGroup">
              <a-radio :value="1">Padre</a-radio>
              <a-radio :value="2">Tutor</a-radio>
            </a-radio-group>


            <a-row :gutter="[16, 0]" class="form-row">
              <a-col :span="24" :md="16" :lg="12" :xl="12" :xxl="6">
                <a-form-item>
                  <div><label>N° Documento</label></div>

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
              <a-col :span="24" :md="16" :lg="12" :xl="12" :xxl="6">
                <a-form-item>
                  <div><label>Pre nombres:</label></div>
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

              <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
                <a-form-item>
                  <div><label>Primer apellido:</label></div>
                  <a-input v-model:value="datos_personales.direccion" />
                </a-form-item>
              </a-col>
            </a-row>

            <a-row :gutter="[16, 0]" class="form-row">
              <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
                <a-form-item>
                  <div><label>Segundo Apellido:</label></div>
                  <a-input v-model:value="datos_personales.direccion" />
                </a-form-item>
              </a-col>
            </a-row>

          </div>

        </a-card> -->
        </div>
      </div>

      <div v-if="pagina_pre === 5">

        <div style="width: 100%; margin-top: 5px; ">

        <Apoderado ref="madreComponent" :id_postulante="datos_personales.id" :tipex="2"/>

        <!-- <a-card style="padding-top: -5px; padding-bottom:0px;" class="cardInicio">
          <div>

            
            <div style="margin-bottom: 25px; margin-top: 10px; ">
              <h1 style="font-size: 1.1rem;"> Datos de la madre</h1>
            </div>

            <a-row :gutter="[16, 0]" class="form-row">
              <a-col :span="24" :md="16" :lg="12" :xl="12" :xxl="6">
                <a-form-item>
                  <div><label>N° Documento</label></div>

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
              <a-col :span="24" :md="16" :lg="12" :xl="12" :xxl="6">
                <a-form-item>
                  <div><label>Pre nombres:</label></div>
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
  
              <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
                <a-form-item>
                  <div><label>Primer apellido:</label></div>
                  <a-input v-model:value="datos_personales.direccion" />
                </a-form-item>
              </a-col>
            </a-row>

            <a-row :gutter="[16, 0]" class="form-row">
              <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
                <a-form-item>
                  <div><label>Segundo Apellido:</label></div>
                  <a-input v-model:value="datos_personales.direccion" />
                </a-form-item>
              </a-col>
            </a-row>

          </div>

        </a-card> -->
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
                        v-model:value="datos_personales.estado_civil"
                        class="selector-modalidad"
                      >
                        <a-select-option :value="7">Examen CEPREUNA</a-select-option>
                        <a-select-option :value="8">Examen General</a-select-option>
                        <a-select-option :value="9">CONADIS</a-select-option>
                        <a-select-option :value="1">Primeros puestos y Coar</a-select-option>
                        <a-select-option :value="2">Traslado interno</a-select-option>
                        <a-select-option :value="3">Traslado externo</a-select-option>
                        <a-select-option :value="4">Graduados o titulados</a-select-option>
                        <a-select-option :value="5">Plan de reparaciones (Ley 28592)</a-select-option>
                        <a-select-option :value="6">Programa de Becas (Pronabec)</a-select-option>
                      </a-select>
                  </div>
                </div>
              </div>


              <a-row :gutter="[16, 0]" class="form-row">
                <a-col :span="24" :md="16" :lg="12" :xl="24" :xxl="6">
                  <a-form-item>
                    <div><label>Programa de estudios</label></div>

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
                
                <a-col style="display:none;" :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                  <a-form-item>
                    <div><label>Cod. de examen médico:</label></div>
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
                <a-col :span="24" :md="24" :lg="24" :xl="12" :xxl="6">
                  <a-form-item>
                    <div><label>Tipo de certificado:</label></div>
                    <a-input v-model:value="datos_personales.direccion" />
                  </a-form-item>
                </a-col>
                <a-col :span="24" :md="24" :lg="24" :xl="12" :xxl="6">
                  <a-form-item>
                    <div><label>Codigo de certificado:</label></div>
                    <a-input v-model:value="datos_personales.direccion" />
                  </a-form-item>
                </a-col>
              </a-row>

              <a-row :gutter="[16, 0]" class="form-row">
                <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="6">
                  <a-form-item>
                    <div><label>Subir certificado:</label></div>
                    <a-input v-model:value="datos_personales.direccion" />
                  </a-form-item>
                </a-col>
              </a-row>

            </div>

          </a-card>
          </div>
      </div>

      </div>


    </div>
    <div>

    </div>
    <a-affix v-if="pagina_pre !== 0" :offset-bottom="bottom" style="margin-top: 0px;">
      <div>
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
        <a-button @click="ejecutarMetodoHijo()" class="boton-siguiente" type="primary" >Siguiente</a-button>
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
        <a-button @click="next()" class="boton-siguiente" type="primary" >Finalizar</a-button>    
      </div>
    </a-affix>

	
</Layout>
</template>
<script setup>
import Layout from '@/Layouts/LayoutPreinscripcion.vue'    
import { watch, getCurrentInstance, provide, computed, ref, unref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { format } from 'date-fns';
import { notification } from 'ant-design-vue';
//import Colegio from "./components/datos_colegio.vue"

const avance = ref(0)
const bottom = ref(2)

const pagina_pre = ref(0)
const next = () => { pagina_pre.value++; avance.value = avance.value + 15 }
const prev = () => { pagina_pre.value--; avance.value = avance.value - 15 }
const dni = ref("70757838")


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
  let res = await axios.post( "get-postulante-datos-personales", {nro_doc: dni.value});
  datos_personales.value.id = res.data.datos[0].id
  datos_personales.value.primer_apellido = res.data.datos[0].primer_apellido
  datos_personales.value.segundo_apellido = res.data.datos[0].segundo_apellido
  datos_personales.value.nombres = res.data.datos[0].nombres
  //datos_personales.value.estado_civil = res.data.datos[0].estado_civil
  //datos_personales.value.sexo = res.data.datos[0].sexo
  datos_personales.value.correo = res.data.datos[0].correo
  datos_personales.value.celular = res.data.datos[0].celular
  datos_personales.value.fec_nacimiento = dayjs(res.data.datos[0].fec_nacimiento)
  datos_personales.value.ubigeo = res.data.datos[0].ubigeo
  datos_personales.value.direccion = res.data.datos[0].direccion
  depseleccionado.value = res.data.datos[0].dep;
  dep.value = res.data.datos[0].departamento
  provseleccionada.value = res.data.datos[0].prov;
  prov.value = res.data.datos[0].provincia
  distseleccionado.value = res.data.datos[0].dist;
  dist.value = res.data.datos[0].distrito
  datos_personales.value.ubigeo_residencia = res.data.datos[0].ubigeo_residencia
  datos_personales.value.direccion = res.data.datos[0].direccion
  next()
} 


const datos_personales = ref({
  id: null,
  tipo_doc:1,
  primer_apellido: "",
  segundo_apellido: "",
  nombres:"",
  estado_civil:1,
  sexo:1,
  correo:"",
  celular:"",
  fec_nacimiento:"",
  ubigeo:"",
  ubigeo_residencia:"",
  direccion:""
});


const saveDatosPersonales =  async () => {

  // if(depseleccionado.value !== null && provseleccionada.value !== null && distseleccionado.value !== null ) {
  //   //console.log(depseleccionado.value + provseleccionada.value + distseleccionado.value)
  //   datos_personales.value.ubigeo_residencia = depseleccionado.value + provseleccionada.value + distseleccionado.value
  // }

  let res = await axios.post(
    "save-postulante",
    {  
      tipo_doc: datos_personales.value.tipo_doc,
      nro_doc: dni.value,
      id: datos_personales.value.id,
      primer_apellido: datos_personales.value.primer_apellido, 
      segundo_apellido: datos_personales.value.segundo_apellido,  
      nombres: datos_personales.value.nombres, 
      correo: datos_personales.value.correo, 
      celular: datos_personales.value.celular, 
      sexo: datos_personales.value.sexo,
      estado_civil: datos_personales.value.estado_civil,  
      fec_nacimiento: format(new Date(datos_personales.value.fec_nacimiento), 'yyyy-MM-dd'),
      ubigeo_nacimiento: datos_personales.value.ubigeo,
      // ubigeo_residencia: datos_personales.value.ubigeo_residencia,
      direccion: datos_personales.value.direccion 
    }
  );
  if(res.data.estado === true ){  
    notificacion(res.data.tipo, res.data.titulo, res.data.mensaje)
  }
  next()
// roles.value = res.data.datos.data;
}

const saveDatosResidencia =  async () => {

  if(depseleccionado.value !== null && provseleccionada.value !== null && distseleccionado.value !== null ) {
    datos_personales.value.ubigeo_residencia = depseleccionado.value + provseleccionada.value + distseleccionado.value
  }

  let res = await axios.post(
    "save-postulante-residencia",
    {  
      id: datos_personales.value.id,
      ubigeo_residencia: datos_personales.value.ubigeo_residencia,
      direccion: datos_personales.value.direccion 
    }
  );
  if(res.data.estado === true ){  
    notificacion(res.data.tipo, res.data.titulo, res.data.mensaje)
  }
  next()
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

</script>

<script>
import Colegio from "./components/datos_colegio.vue"
import Apoderado from "./components/apoderado.vue"
export default {
  components: {
    Colegio, Apoderado
  },
  data() {
    return { pagina_pre_temp: 3 }
  },
  methods: {
    ejecutarMetodoHijo() {
      this.$refs.hijoComponent.ejecutarMetodo();
      this.pagina_pre_temp = 4;
    },
    guardarApoderadoPadre() {
      this.$refs.padreComponent.saveApoderado();
      this.pagina_pre_temp = 5;
    },
    guardarApoderadoMadre() {
      this.$refs.madreComponent.saveApoderadoMadre();
      this.pagina_pre_temp = 5;
    }
  },
}
</script>


<style scope>
.boton-anterior { width: 175px; height: 38px; }
.boton-siguiente { width: 175px; height: 38px; }  
.datos-postulacion { display: flex}
.selector-modalidad { width: 250px }
@media (max-width: 480px), screen and (orientation: portrait) { 
  .cardInicio { margin-top: -10px; border:none; } 
  .boton-anterior { width: 50%; }
  .boton-siguiente { width: 50%; }  
  .datos-postulacion { display: block;}
  .selector-modalidad { width: 100%; margin-bottom: 10px }
}

</style>