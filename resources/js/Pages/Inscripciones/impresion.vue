<template>
<Head title="Inscripciones-impresión"/>
<AuthenticatedLayout>
<div>
<a-tabs v-model:activeKey="tabactive" type="card">
  <a-tab-pane key="1" tab="Inscripcion" >
    <div style="padding: 0px 15px;">
      <!-- {{ dniseleccionado }} {{ postulante }} -->
      <div class="flex justify-end" >
        <!-- <div>
          <label style="margin-right: 10px;"> Dni:</label>
          <a-input
            style="width: 300px;"
            placeholder="Dni"
            v-model:value="dniseleccionado"
            @keypress="handleKeyPress"
          />  
        </div> -->
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
            placeholder="Buscar"
            v-model:value="dni"
            @keypress="handleKeyPress"
          />
          <template #option="{ value: val, label:lab }" style="background-color: blue;">
            <div style="height: 34px;">
              <div><span style="font-weight: 700; color: black; font-size: .7rem;">{{ val }}</span></div>
              <div style="margin-top: -10px;"><span style="font-size: .8rem; text-transform: uppercase;">{{ lab }}</span></div>
            </div>
          </template>
          </a-auto-complete>
        </div>
      </div>
    
       <div style=" width:100%; border: solid 1px #f3f3f3; margin: 20px 0px">
      <!--  <a-row type="flex">
          <a-col :span="6" :xs="{ order: 3 }" :sm="{ order: 4 }" :md="{ order: 2 }" :lg="{ order: 2 }" style="background: blue;">
            3 col-order-responsive
          </a-col>
          <a-col :span="6" :xs="{ order: 4 }" :sm="{ order: 3 }" :md="{ order: 1 }" :lg="{ order: 1 }" style="background: green;">
            4 col-order-responsive
          </a-col>
        </a-row> -->
      
        <div class="container-principal">

          <a-col flex="1 1">
            <div class="container-postulante">
                  <div class="mr-3 container-imagen">
                    <img v-if="postulante.primer_apellido !== ''" :src="baseUrl+'/fotos/inscripcion/'+postulante.dni_temp+'.jpg'"/>
                    <img v-else :src="baseUrl+'/fotos/postulantex.jpg'"/>
                  </div>
                  <!-- {{ postulante }} -->
                  <div class="datos-postulante"> 
                    <div class="mb-2">
                      <a-label>Primer apellido</a-label>
                      <a-input v-model:value="postulante.primer_apellido" placeholder="Primer apellido" />
                    </div>

                    <div class="mb-2">
                      <a-label>Segundo apellido</a-label>
                      <a-input v-model:value="postulante.segundo_apellido" placeholder="Segundo apellido" />
                    </div>

                    <div class="mb-2">
                      <a-label>Prenombres</a-label>
                      <a-input v-model:value="postulante.nombres" placeholder="Pre_nombres" />
                    </div>

                    <div class="flex">
                      <div class="mr-3">
                        <a-label>Fecha Nac</a-label>
                        <div>
                          <a-date-picker v-model:value="fecha"/>
                        </div>
                      </div>
                      <div style="width: 100%;">
                        <a-label>Sexo</a-label>
                        <a-select
                          ref="select"
                          v-model:value="postulante.sexo"
                          style="width: 100%"
                        >
                          <a-select-option value="1">Masculino</a-select-option>
                          <a-select-option value="0">Femenino</a-select-option>
                        </a-select>      
                      </div>
                    </div>
                  </div>

            </div>

            <div>
                <div class="container-postulante mb-2">
                  <div style="width: 100%;">
                    <a-label>Colegio</a-label>
                     <a-input v-model:value="postulante.colegio" placeholder="Basic usage" />
                  </div>
                </div>
                <div class="container-postulante mb-2">
                  <div style="width: 100%;">
                    <a-label>Procedencia</a-label>
                     <a-input v-model:value="postulante.procedencia" placeholder="Basic usage" />
                  </div>
                </div>
                <div class="container-postulante">
                  <div style="width: 100%;">
                    <a-label>Proceso</a-label>
                     <a-input v-model:value="postulante.proceso" placeholder="Basic usage" />
                  </div>
                </div>
            </div>
            
          </a-col>
          <a-col flex="0 1 400px">
            <div>
                <div class="mb-2">
                  <a-label>Modalidad</a-label>
                  <a-select
                    ref="select"
                    v-model:value="postulante.modalidad"
                    style="width: 100%"
                  >
                    <a-select-option :value="1">Titulados y graduados</a-select-option>
                    <a-select-option :value="2">Traslados Internos</a-select-option>
                    <a-select-option :value="3">Traslados Externos</a-select-option>
                    <a-select-option :value="4">Primeros Puestos y Coar</a-select-option>
                    <a-select-option :value="5">Deportistas calificados</a-select-option>
                    <a-select-option :value="6">Becas</a-select-option>
                    <a-select-option :value="7">Personas con dispacidad</a-select-option>
                    <a-select-option :value="8">Examen general</a-select-option>
                    <a-select-option :value="9">Cepreuna</a-select-option>
                  </a-select>
                </div>
                <div>
                  <a-label>Programa de estudios</a-label>
                  <a-select
                    ref="select"
                    v-model:value="postulante.programa"
                    style="width: 100%"
                  >
                    <a-select-option :value="1">Administración</a-select-option>
                    <a-select-option :value="2">Antropología</a-select-option>
                    <a-select-option :value="3">Arquitectura y Urbanismo</a-select-option>
                    <a-select-option :value="4">Arte: Artes plásticas</a-select-option>
                    <a-select-option :value="5">Arte: Danza</a-select-option>
                    <a-select-option :value="6">Arte: Musica</a-select-option>
                    <a-select-option :value="7">Biología: Ecología</a-select-option>
                    <a-select-option :value="8">Biología: Microbiología y laboratorio clínico</a-select-option>
                    <a-select-option :value="9">Biología: Pesquería</a-select-option>
                    <a-select-option :value="1">Ciencias contables</a-select-option>
                    <a-select-option :value="2">Ciencias de la comunicación</a-select-option>
                    <a-select-option :value="3">Ciencias físisco matemáticas: Física</a-select-option>
                    <a-select-option :value="4">Ciencias físisco matemáticas: Matemática</a-select-option>
                    <a-select-option :value="5">Derecho</a-select-option>
                    <a-select-option :value="6">Educación física</a-select-option>
                    <a-select-option :value="7">Educación Inicial</a-select-option>
                    <a-select-option :value="8">Educación primaria</a-select-option>
                    <a-select-option :value="9">Educ. Sec. de la espeialidad de ciencia, tecnología y ambiente</a-select-option>
                    <a-select-option :value="9">Educ. Sec. de la espeialidad de Ciencias Sociales</a-select-option>
                    <a-select-option :value="9">Educ. Sec. de la espeialidad de Lengua, Literatura, psicología y filosofía</a-select-option>
                    <a-select-option :value="9">Educ. Sec. de la espeialidad de Matemática, física, computación e informática</a-select-option>
                    <a-select-option :value="1">Enfermería</a-select-option>
                    <a-select-option :value="2">Ingeniería Agrícola</a-select-option>
                    <a-select-option :value="3">Ingeniería Agroindustrial</a-select-option>
                    <a-select-option :value="4">Ingeniería Civil</a-select-option>
                    <a-select-option :value="5">Ingeniería de Minas</a-select-option>
                    <a-select-option :value="6">Ingeniería de Sistemas</a-select-option>
                    <a-select-option :value="7">Ingeniería Económica</a-select-option>
                    <a-select-option :value="8">Ingeniería Electrónica</a-select-option>
                    <a-select-option :value="9">Ingeniería Estadística e informática</a-select-option>
                    <a-select-option :value="1">Ingeniería Geológica</a-select-option>
                    <a-select-option :value="2">Ingeniería Mecánica eléctrica</a-select-option>
                    <a-select-option :value="3">Ingeniería Metalúrgica</a-select-option>
                    <a-select-option :value="4">Ingeniería Química</a-select-option>
                    <a-select-option :value="5">Ingeniería Topográfica y Agrimensura</a-select-option>
                    <a-select-option :value="6">Medicina Humana</a-select-option>
                    <a-select-option :value="7">Medicina Veterinaria y zootecnia</a-select-option>
                    <a-select-option :value="8">Nutrición Humana</a-select-option>
                    <a-select-option :value="9">Odontología</a-select-option>
                    <a-select-option :value="1">Sociología</a-select-option>
                    <a-select-option :value="2">Trabajo Social</a-select-option>
                    <a-select-option :value="3">Turismo</a-select-option>
                  </a-select>
                </div>
            </div>
            <!-- {{ baseUrl+'/huellas/inscripcion/'+dniseleccionado+'.jpg' }} -->
            <div class="mt-4 container-huellas">
              <div class="mr-1" style="border: solid 1px #F4f4f4; width: 100%; height: 100%; overflow-y: hidden;">
                <img v-if="postulante.primer_apellido !== ''" :src="baseUrl+'/huellas/inscripcion/'+postulante.dni_temp+'.jpg'"/>
                <img v-else :src="baseUrl+'/huellas/huella.jpg'"/>
              </div>
              <div class="mr-1" style="border: solid 1px #F4f4f4; width: 100%; height: 100%;">
                <img v-if="postulante.primer_apellido !== ''" :src="baseUrl+'/huellas/inscripcion/'+postulante.dni_temp+'x.jpg'"/>
                <img v-else :src="baseUrl+'/huellas/huella.jpg'"/>
              </div>
            </div>
          </a-col>

        </div>
      </div>

      <div style="display: flex; justify-content: flex-end; margin-top:-10px;">
        <a-button type="primary" @click="Inscribir">Imprimir</a-button>
      </div>
    </div>

  </a-tab-pane>
  <a-tab-pane key="2" tab="Apoderados">
    <a-table :dataSource="apoderados" :columns="colApoderados">
      <template #bodyCell="{ column, index, record }">
        <template v-if="column.dataIndex === 'parentesco'">
          <div v-if="record.parentesco == 'Padre'">
            <a-tag color="blue">Padre</a-tag>            
          </div>
          <div v-if="record.parentesco == 'Madre'">
            <a-tag color="pink">Madre</a-tag>            
          </div>
        </template>
 
        <template v-if="column.dataIndex === 'acciones'">
          <a-button type="primary" disabled @click="abrirEditar(filiales[index])" size="small">
          <template #icon><form-outlined/></template>
          </a-button>
          <a-divider type="vertical" />
          <a-button type="danger" disabled @click="eliminar(filiales[index])" shape="" size="small">
          <template #icon><delete-outlined/></template>
          </a-button>
        </template>

      </template>    
    
    </a-table>
    <!-- <pre>{{apoderados}}</pre> -->
  </a-tab-pane>
  <a-tab-pane key="3"  style="">
    <template #tab >
      <div class="flex" style="align-items:center;">
        <div v-if="vouchers.length === 0 && postulante.dni_temp !== ''" style="color:red; margin-top:-8px">
          <span><exclamation-circle-outlined/></span>
        </div>
          vouchers
      </div>
    </template>
    <a-table :dataSource="vouchers" :columns="colVouchers"> 
      <template #bodyCell="{ column, index, record }">
        <template v-if="column.dataIndex === 'codigo'">
          <div v-if="record.codigo == 26">
            <a-tag color="green"> Derechos de admisión </a-tag>            
          </div>
          <div v-if="record.codigo == 39">
            <a-tag color="pink"> Examen médico </a-tag>            
          </div>
        </template>

        <template v-if="column.dataIndex === 'monto'">
          <div>
            <span>S/ {{ record.monto.toFixed(2) }}</span>
          </div>
        </template>
      </template>
    </a-table>    <!-- <pre>{{vouchers}}</pre> -->
  </a-tab-pane>
  <a-tab-pane key="4">
    <template #tab >
      <div class="flex" style="align-items:center;">
        <div v-if="documentos.length < 3  && postulante.dni_temp !== ''" style="color:red; margin-top:-8px">
          <span><exclamation-circle-outlined/></span>
        </div>
          Documentos
      </div>
    </template>

    <a-table :dataSource="documentos" :columns="colDocumentos" size="small">
      <template #bodyCell="{ column, index, record}">
        <template v-if="column.dataIndex === 'estado'">
          <div v-if="record.estado == 1" type="primary" ghost>
            <a-tag color="green"> verificado </a-tag>            
          </div>
          <div v-else type="danger" ghost>
            <a-tag color="pink"> No verificado </a-tag>            
          </div>
        </template>

        <template v-if="column.dataIndex === 'acciones'">
          <a-button type="primary" disabled @click="abrirEditar(filiales[index])" size="small">
          <template #icon><form-outlined/></template>
          </a-button>
          <a-divider type="vertical" />
            <a :href="'../../'+record.url" target="_blank">
              <a-button type="primary"  @click="eliminar(filiales[index])" shape="" size="small">
                    <template #icon><eye-outlined/></template>
              </a-button>
            </a>  
        </template>

      </template>    
    </a-table>
    <!-- <pre>{{documentos}}</pre> -->
  </a-tab-pane>
  <a-tab-pane key="5" tab="Inscripciones">
    <pre>{{ inscripciones }}</pre>
  </a-tab-pane>
  <a-tab-pane key="6" tab="Pre inscripciones">
    <!-- {{ preinscripciones }} -->
    <a-table :dataSource="preinscripciones" :columns="colPreinscripciones" size="small">
      <template #bodyCell="{ column, index, record}">
        <template v-if="column.dataIndex === 'estado'">
            <div v-if="record.estado == 1" type="primary" ghost>
              <a-tag color="green"> Disponible </a-tag>            
            </div>
            <div v-else type="danger" ghost>
              <a-tag color="pink"> Bloqueado </a-tag>            
            </div>
        </template>

        <template v-if="column.dataIndex === 'acciones'">
          <a-button type="primary" disabled @click="abrirEditar(filiales[index])" size="small">
            <template #icon><eye-outlined/></template>
          </a-button>
        </template>

      </template>
    </a-table>
  </a-tab-pane>
</a-tabs>
</div>
</AuthenticatedLayout>

</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'    
import {ref, watch} from 'vue'
import { ExclamationCircleOutlined, FormOutlined, DeleteOutlined, EyeOutlined } from '@ant-design/icons-vue';

const props = defineProps({
  baseUrl: String,
});


const dni = ref("")
const tabactive = ref('1')

const dniseleccionado = ref(null)
// const postulante = ref({dni:"", nombres:"", primer_apellido:"",segundo_apellido:"", sexo:"", fec_nacimiento:"" })
const preinscripciones = ref(null)
const inscripciones = ref(null)

const postulantes = ref([]) 
const postulante = ref({ 
  id:"",
  nombres:"", 
  primer_apellido:"",
  segundo_apellido:"", 
  sexo:'Masculino', 
  fec_nacimiento:"",
  colegio: "",
  procedencia: "",
  proceso: "",
  id_proceso:"",
  modalidad: "",
  id_modalidad:"",
  programa:"",
  id_programa:"",
  dni_temp:""
})

const apoderados = ref([])
const vouchers = ref([])
const documentos = ref ([])

const onSelect = () => {
  console.log('onSelect', dniseleccionado);
};

const handleKeyPress = (ev) => {
  console.log('handleKeyPress', ev);
};

const getPostulantes =  async (term = "", page = 1) => {
  let res = await axios.post(
      "/admin/inscripciones/get-postulantes?page=" + page,
      { term: dni.value }
  );
  postulantes.value = res.data.datos.data;
}

const getPostulantesByDni =  async () => {
  let res = await axios.get(
      "/admin/inscripciones/get-postulante-dni/" + dniseleccionado.value
  );
    postulante.value.id = res.data.datos.id_postulante;    
    postulante.value.nombres = res.data.datos.nombres;
    postulante.value.primer_apellido = res.data.datos.primer_apellido;
    postulante.value.segundo_apellido = res.data.datos.segundo_apellido;
    postulante.value.sexo = res.data.datos.sexo;
    postulante.value.colegio = res.data.datos.colegio;
    postulante.value.procedencia = res.data.datos.departamento +' / '+res.data.datos.provincia + ' / '+ res.data.datos.distrito;
    postulante.value.proceso = res.data.datos.proceso;
    postulante.value.modalidad = res.data.datos.modalidad;
    postulante.value.programa = res.data.datos.programa;
    postulante.value.id_programa = res.data.datos.id_programa;
    postulante.value.id_proceso = res.data.datos.id_proceso;
    postulante.value.id_modalidad = res.data.datos.id_modalidad;
    postulante.value.dni_temp = res.data.datos.dni;
}

const getApoderados =  async () => {
  let res = await axios.get(
    "/admin/inscripciones/get-apoderados-postulante/" + dniseleccionado.value
  );
  apoderados.value = res.data.datos;
}

const getVouchers =  async () => {
  let res = await axios.get(
      "/admin/inscripciones/get-vouchers-postulante/" + dniseleccionado.value
  );
  vouchers.value = res.data.datos;
}

const getDocumentos =  async () => {
  let res = await axios.get(
    "/admin/inscripciones/get-documentos-postulante/" + dniseleccionado.value
  );
  documentos.value = res.data.datos;
}

const getPreinscripciones =  async () => {
  let res = await axios.get(
      "/admin/inscripciones/get-preinscripciones-postulante/" + dniseleccionado.value
  );
  preinscripciones.value = res.data.datos;
}

const getInscripciones =  async () => {
  let res = await axios.get(
      "/admin/inscripciones/get-inscripciones-postulante/" + dniseleccionado.value
  );
  inscripciones.value = res.data.datos;
}


const Inscribir =  async () => {
  let res = await axios.post( "/admin/inscripciones/inscribir", { postulante: postulante.value });
  //postulantes.value = res.data.datos.data;
}

watch(dni, ( newValue, oldValue ) => {
  // console.log("new:", newValue);
  // console.log("old:", oldValue);
  getPostulantes();
})

watch(dniseleccionado, ( newValue, oldValue ) => {
    getPostulantesByDni()
    getVouchers()
    getDocumentos()
})

watch(tabactive, ( newValue, oldValue ) => {
  if (tabactive.value == 2){
    getApoderados()
  }
  if (tabactive.value == 3){
    getVouchers()
  }
  if (tabactive.value == 4){
    getDocumentos()
  }
  if (tabactive.value == 5){
    getInscripciones()
  }
  if (tabactive.value == 6){
    getPreinscripciones()
  }
})

const colApoderados = [
  { title: 'DNI', dataIndex: 'nro_documento', key: 'nro_documento',},
  { title: 'Nombres', dataIndex: 'nombres', key: 'nombres',},
  { title: 'Paterno', dataIndex: 'paterno', key: 'paterno',},
  { title: 'Materno', dataIndex: 'materno', key: 'materno', },
  { title: 'Parentesco', dataIndex: 'parentesco' },
  { title: 'Acciones', dataIndex: 'acciones'}  
]

const colVouchers =  [
  { title: 'N° Operación', dataIndex: 'operacion', key: 'operacion',},
  { title: 'Fecha', dataIndex: 'fecha', key: 'fecha',},
  { title: 'Hora', dataIndex: 'hora', key: 'hora', },
  { title: 'Concepto', dataIndex: 'codigo', key: 'codigo', },
  { title: 'Monto', dataIndex: 'monto', key: 'monto', },
]

const colDocumentos =  [
  { title: 'Codigo', dataIndex: 'codigo', key: 'codigo',},
  { title: 'Documento', dataIndex: 'nombre', key: 'nombre',},
  { title: 'Tipo', dataIndex: 'tipo', key: 'tipo',},
  { title: 'estado', dataIndex: 'estado', key: 'estado',},
  { title: 'Acciones', dataIndex: 'acciones'}  
]

// const colInscripciones =  [
//   { title: 'Name', dataIndex: 'name', key: 'name',},
//   { title: 'Age', dataIndex: 'age', key: 'age',},
//   { title: 'Address', dataIndex: 'address', key: 'address', },
// ]

const colPreinscripciones =  [
  { title: 'Programa', dataIndex: 'programa', key: 'programa',},
  { title: 'Proceso', dataIndex: 'proceso', key: 'proceso',},
  { title: 'Modalidad', dataIndex: 'modalidad', key: 'modalidad', },
  { title: 'Estado', dataIndex: 'estado', key: 'estado', },
  { title: 'Ver', dataIndex: 'acciones', },
]

getPostulantes()
</script>

<style scoped>
.container-imagen{
  border: 1px solid #d9d9d954;
  height: 215px; width: 210px; 
  min-width:160px;
  overflow: hidden; 
}
.container-imagen img{
  height: 100%;
}
.container-principal{
  display: flex;
}

.datos-postulante{
  width: 100%;
  min-width: 200px;
}
.container-postulante{
    display: flex;
    margin-right: 12px;
}
.container-huellas {
  display: flex; width: 400px;
  justify-content: space-between;
  min-height: 20px;
}

@media (max-width: 380px){
  .container-imagen{
    display: flex;
    width: 100%;
    height: 340px;
  }
}

@media (max-width: 600px){
  .container-imagen{
    display: flex;
    width: 100%;
    justify-content: center;
  }
  .inputImpresion{
    display: block;
  }
  .container-postulante{
    display: block;
    margin-right: 0px;
  }

  .container-huellas img{
    width: 100%;
  }
}

@media (max-width: 1060px){
  .container-principal{
    display: block;
  }
  .container-huellas{
    width: 100%;
    height: 100%;
    justify-content: space-between;
  }
  .container-huellas img{
    width: 100%;
    transform: scale(0.7);
  }
}
</style>