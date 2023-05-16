<template>
<Head title="Procesos"/>
<AuthenticatedLayout>
  <div class="p-4" style="">
    <div> 
      <div style="margin-top: 0px; margin-bottom: -20px;">
        <a-steps progress-dot v-model:current="current">
          <a-step title="Paso 1" description="Validación de voucher." />
          <a-step title="Paso 2" description="Datos personales." />
          <a-step title="Paso 3" description="Datos de apoderados." />
          <a-step title="Paso 4" description="Datos del colegio." />
          <a-step title="Paso 5" description="Datos de Postulación." />
        </a-steps>
        <a-divider />
      </div>

      <!-- PASO 0 -->

      <div v-if="current===0" style="width: 100%; margin-top: -25px;">
        <div class="flex" style="justify-content: center; align-items: center; width: 100%; margin-top: 80px; margin-bottom: 50px;">
          <a-card style="min-width: 300px; ">
            
            <div>
              <h1 style="font-size: 1.2;">Pre Inscripción</h1>
            </div>
            <a-radio-group v-model:value="datos_personales.tipo_doc" class="flex justify-end" style="display: flex; width: yellow;" name="radioGroup">
              <a-radio :value="1">Dni</a-radio>
              <a-radio :value="2">Carnet Ext.</a-radio>
            </a-radio-group>
            
            <div style="margin-bottom: 7px;"><label>N° Documento</label></div>
            <a-input v-model:value="dni" placeholder="Ingrese N° Documento"/>

            <div style="display: flex; justify-content: center; margin-top: 10px;">
              <a-button type="primary" @click="getDatos()">Iniciar Postulación</a-button>
            </div>

            {{ tipo }}
            <div>
              <div v-for="item in comprobantes" :key="item.id">
                  <a-card class="mb-3" @click="value = 2" :class="[value === 2? 'cards' : '' ]">
                    <div style="width: 220px;">
                      {{ item }}
                    </div>
                  </a-card>

                    <!-- <a-card @click="value = 3" :class="[value === 3? 'cards' : '' ]">
                      <div style="width: 220px;">
                        d
                      </div>
                    </a-card> -->
              </div>
            </div>
          </a-card>
        </div>
        
      </div>
      <!--- END PASO 0 -->


      <!-- PASO 1 DATOS PERSONALES -->
      <div v-if="current===1" style="width: 100%; margin-top: -25px;">
        {{ departamentos }}

        <a-card style="padding-top: -25px">
          <div>
            
            <div style="margin-bottom: 25px; margin-top: -10px;">
              <h1 style="font-size: 1.1rem;"> Datos de personales</h1>
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
              <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                <a-form-item>
                  <div><label>Fec. Nacimiento: {{ datos_personales.fec_nacimiento }}</label></div>
                  <a-space direction="vertical" :size="24">
                    <a-date-picker v-model:value="datos_personales.fec_nacimiento" format='DD/MM/YYYY' />
                  </a-space>
                </a-form-item>
              </a-col>
              <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                <a-form-item>
                  <div><label>Ubigeo:</label></div>
                  <a-input v-model:value="datos_personales.ubigeo" />
                </a-form-item>
              </a-col>
            </a-row>


            <!-- //DATOS DE RESIDENCIA -->

            <div style="margin-bottom: 25px; margin-top: 10px; ">
              <h1 style="font-size: 1.1rem;"> Datos de residencia</h1>
            </div>

            <a-row :gutter="[16, 0]" class="form-row">
              <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                <a-form-item>
                  <div><label>Departamento:</label></div>
     








                  <a-auto-complete
                    v-model:value="test"
                    :options="departamentos"
                    style="width: 200px"
                    @select="onSelect"
                  >
                  </a-auto-complete>







    
                </a-form-item>
              </a-col>
              <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                <a-form-item>
                  <div><label>Provincia:</label></div>
                  <a-input v-model="campo2" />
                </a-form-item>
              </a-col>
              <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
                <a-form-item>
                  <div><label>Distrito:</label></div>
                  <a-input v-model="campo3" />
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

            <a-row :gutter="[16, 0]" class="form-row" style="margin-top: 15px;">
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
      <div v-if="current===2" style="width: 100%;">
        Paso 3
      </div>
    </div>

    <!--- END PASO 1 -->

    <!-- {{ comprobantes }} -->

    <!-- <div class="flex" style="justify-content: space-between;">
      <a-button v-if="current > 0" @click="prev()" >Anterior</a-button>
      <a-button v-if="current < 2 " @click="next()" type="primary" >Siguiente</a-button>    
    </div> -->

  </div>
</AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { watch, computed, ref, unref } from 'vue';
import { UserOutlined, SolutionOutlined, LoadingOutlined, SmileOutlined, } from '@ant-design/icons-vue';
import { format } from 'date-fns';
import dayjs from 'dayjs';
import axios from 'axios';

const test = ref("")


const current = ref(0);
const next = () => { current.value++; };
const prev = () => { current.value--; };

const fecha = ref('12-12-2022');
 
const datos_personales = ref({
  id: null,
  tipo_doc:1,
  primer_apellido: "",
  segundo_apellido: "",
  nombres:"",
  correo:"",
  celular:"",
  fec_nacimiento:"",
  ubigeo:"" 
});

const datos_residencia = ref({
  departamento: null,
  provincia:null,
  distrito: null,
  direccion: null,
})

const departamentos = ref([])

const tipo_doc = ref(1) 

const tipo = ref(null)
const token = ref('70ab1cd1b9b452982370381fd0be605c85ddc8795aed972afca143fee05fde43');

const voucher_verificado = ref(false)
const value = ref(1);
const dni = ref("70757838");
const comprobantes = ref([])

const getComprobantes = async (term = "", page = 1) => {
  let res = await axios.post( "pre-inscripcion/get-comprobantes?page=" + page, {dni:dni.value});
  comprobantes.value = res.data.datos;
  voucher_verificado.value = res.data.voucher;
}

const getDatosPersonales = async () => {
  let res = await axios.post( "get-postulante-datos-personales?page=", {nro_doc: dni.value});
  datos_personales.value.id = res.data.datos[0].id
  datos_personales.value.primer_apellido = res.data.datos[0].primer_apellido
  datos_personales.value.segundo_apellido = res.data.datos[0].segundo_apellido
  datos_personales.value.nombres = res.data.datos[0].nombres
  datos_personales.value.correo = res.data.datos[0].correo
  datos_personales.value.celular = res.data.datos[0].celular
  datos_personales.value.fec_nacimiento = dayjs(res.data.datos[0].fec_nacimiento)
  datos_personales.value.ubigeo = res.data.datos[0].ubigeo
  datos_personales.value.direccion = res.data.datos[0].direccion
} 

const getDatos = async () => {
  //let res = await axios.get( "https://apiperu.dev/api/dni/"+dni.value, { headers: {"Authorization" : `Bearer ${token.value}`}});
  //tipo.value = res.data.datos;
  next()
}

const getDepartamentos = async () => {
  let res = await axios.post( "get-departamentos-codigo?page=", {nro_doc: dni.value});
  departamentos.value = res.data.datos
} 

watch(dni, ( newValue, oldValue ) => {
  if(dni.value.length == 8 ){
    // console.log("size::::", dni.value.length)  
    getComprobantes();
  }

})
watch(current, ( newValue, oldValue ) => {
  if(current.value == 1 ){
    getDatosPersonales();
    getDepartamentos();
  }
})


// axios.post("save-proceso", post).then((result) => {
//   notificacion('success',result.data.titulo, result.data.mensaje);
//   getProcesos();
//   visible.value = false;

// });


const saveDatosPersonales =  async () => {
  let res = await axios.post(
    "save-datos-personales",
    { 
      tipo_doc: datos_personales.value.tipo_doc,
      nro_doc: dni.value,
      id: datos_personales.value.id,
      primer_apellido: datos_personales.value.primer_apellido, 
      segundo_apellido: datos_personales.value.segundo_apellido,  
      nombres: datos_personales.value.nombres, 
      correo: datos_personales.value.correo, 
      celular: datos_personales.value.celular, 
      fec_nacimeinto: format(new Date(datos_personales.value.fec_nacimiento), 'yyyy-MM-dd'),
      ubigeo_nacimiento: datos_personales.value.ubigeo,
//      ubigeo_residendia: datos_personales.value.ubigeo_residendia
    }
  );

  showToast(res.data.tipo, res.data.titulo, res.data.mensaje)
  getAlumnos()  
  visible.value = false
  limpiar()
  // roles.value = res.data.datos.data;
}

//CONVERTIR FECHA
//const fecha_nacimiento = format(new Date(fechaOriginal), 'yyyy-MM-dd');
//





// watch(buscar, ( newValue, oldValue ) => {
//   getProcesos();
// })



// getComprobantes()
    
</script>
<style scoped>
.form-row {
  margin: -20px -8px; /* Márgenes horizontales negativos para compensar el padding interno de las columnas */
}

.form-row .a-col {
  padding: 0 8px; /* Padding horizontal interno para las columnas */
}

@media (max-width: 767px) {
  /* Estilos para pantallas más pequeñas (mobile) */
  .form-row {
    margin: 0; /* Elimina los márgenes horizontales en pantallas pequeñas */
  }

  .form-row .a-col {
    padding: 0; /* Elimina el padding interno en pantallas pequeñas */
  }
}

.responsive-steps {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}

</style>