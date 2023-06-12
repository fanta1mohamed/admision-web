<template>
    <AuthenticatedLayout>
    <div style="">
      <a-card style="background: white;" class="m-0 p-0">
        <div class="mb-2 flex justify-between">
          <div>
            <a-select
              ref="select"
              v-model:value="examen"
              style="width: 120px">
              <a-select-option :value="4">BIOMEDICAS</a-select-option>
              <a-select-option :value="6">INGENIERIAS</a-select-option>
              <a-select-option :value="5">SOCIALES</a-select-option>
            </a-select>
            <a-button type="primary" @click="abrir()" size="small">
              <template #icon><form-outlined/></template>
            </a-button>
            <!-- <a-button type="primary" @click="openModal">Agregar Respuestas</a-button> -->
          </div>  
          <a-input placeholder="Buscar" v-model:value="buscar"  style="max-width: 300px;">
            <template #suffix>
              <search-outlined />
            </template>
          </a-input>
        </div>
       
        <div>   
          <a-button @click="downloadFile">Descargar XLSX</a-button>
          <!-- {{ participantes.data }} -->
          <!-- {{ totalpaginas }} -->
          <a-table size="small" :dataSource="participantes.data" :columns="columns" :pagination="false">
            <template #bodyCell="{ column, text, record, index }">

              <template v-if="column.dataIndex === 'nro'" >
                  <div><span style="font-weight: bold; font-size: 0.9rem;"> {{ index + 1}}  </span></div>
              </template>

              <template v-if="column.dataIndex === 'cod'" >
                  <div><span style="font-weight: bold; font-size: 0.9rem;"> {{ record.dni }}  </span></div>
              </template>
    
              <template v-if="column.dataIndex === 'nombres'" >
                <div class="flex">
                  <!-- <div style="margin-bottom: -5px;" class="mr-2"><span style="font-weight: bold; font-size: .9rem;"> {{ record.dni }}  </span></div> -->
                  <span style="text-transform: uppercase; font-size: .9rem;"> {{ record.nombres }} {{ record.primer_apellido }} {{ record.segundo_apellido }}</span>
                </div>
              </template>
   
              <template v-if="column.dataIndex === 'nota'">
                <div class="flex" style="justify-content: center;">
                  <span style="font-weight:bold;">{{  record.nota }}</span>
                </div>
              </template>
   
             <template v-if="column.dataIndex === 'accion'">
                <div class="flex" style="justify-content: space-between;">
                    <a-button type="primary" @click="abriModal(record)" size="small">
                    <template #icon><form-outlined/></template>
                    </a-button>
                 </div>
                </template>
            </template>
          </a-table>
          <div class="flex justify-end">
            <a-pagination 
            v-model:current="pagina" 
            v-model:pageSize="paginasize" 
            :total="totalpaginas"
            show-size-changer 
            show-less-items />
          </div>
    
        </div>
      </a-card>
    </div> 
    
    <a-modal 
      v-model:visible="visible" 
      :header="false"
      width="100vw"
      wrap-class-name="full-modal"
    >
    <div class="flex justify-center" style="">
  <div class="flex justify-center p-3" style="width: 700px; background: white; border: solid #d9d9d9 1px; border-radius: 10px;">

      <div style="width:200px; margin-right: 10px;">
        <div style="margin-right:10px ; text-align:center; font-weight: bold;">
          <div style="font-weight: bold;"> HOJA DE </div>
          IDENTIFICACIÓN
        </div>
        <div class="ml-3 pr-4" style="">

        <div class="mt-3">
          <a-select
            ref="select"
            v-model:value="area_examen"
            style="width: 175px">
            <a-select-option :value="4">BIOMEDICAS</a-select-option>
            <a-select-option :value="6">INGENIERIAS</a-select-option>
            <a-select-option :value="5">SOCIALES</a-select-option>
          </a-select>

        </div>


        <div class="mt-3">
          <label>N° Documento</label>
          <a-input v-model:value="dni"/>
        </div>
        <div class="mt-3">
          <label >Nombres</label>
          <a-input v-model:value="nombreP" />
        </div>
        <div class="mt-3">
          <label>Apellido Paterno</label>
          <a-input v-model:value="paterno"/>
        </div>
        <div class="mt-3">
          <label>Apellido Materno</label>
          <a-input v-model:value="materno"/>
        </div>
        </div>
      </div>

      <hr style="border: solid 1px #00000007; height: 570px; margin-right: 20px;" />

      <div>
        <div>
          <div class="flex justify-center" style=" height: 60px; width: 400px;">
            <div>
              <img src="../../../assets/imagenes/logotiny.png" width="50"/>        
            </div>
            <div class="pl-3 pr-3" style="text-align: center;">
              <div >Universidad Nacional del Altiplano</div>
              <div style="margin-top: -5px;">Vicerrectorado Académico</div>
              <div style="margin-top: -5px;">Dirección de Admisión</div>
            </div>
            <div>
              <img src="../../../assets/imagenes/logoDAD.png" width="60"/>        
            </div>
          </div>
          <div class="flex justify-center" style="height: 30px; width: 400px; font-weight: bold;">
            HOJA DE RESPUESTAS
          </div>
        </div>
        <div class="flex">
          <div style="margin-right: 20px;">
            <div v-for="number in 30" :key="number" style="padding: 0px; height: 16px;"> 
                <a-checkbox-group v-model:value="marcadas[number-1]" style="width: 200px; height: 15px;" :style="number%2 == 0? { backgroundColor: '#00000007'}:''">
                  <div style="padding: 0px; height: 15px; margin-bottom: -20px; display: flex; align-items: center; overflow: hidden;" >
                    <a-col :span="4" style="margin-right: -20px;">
                      <div  style="width: 20px; font-size: .7rem;">{{ number }}</div>
                    </a-col>
                    <a-col :span="4">
                      <a-checkbox value="A" class="small-checkbox">A</a-checkbox>
                    </a-col>
                    <a-col :span="4">
                        <a-checkbox value="B" class="small-checkbox">B</a-checkbox>
                    </a-col>
                    <a-col :span="4">
                        <a-checkbox value="C" class="small-checkbox">C</a-checkbox>
                    </a-col>
                    <a-col :span="4">
                        <a-checkbox value="D" class="small-checkbox">D</a-checkbox>
                    </a-col>
                    <a-col :span="4">
                        <a-checkbox value="E" class="small-checkbox">E</a-checkbox>
                    </a-col>  
                  </div>
                </a-checkbox-group>
            </div>
          </div>

      <!-- {{ marcadas }} -->

      <div style=" width: 200px;">
        <div v-for="number in 30" :key="number" style="padding: 0px; height: 16px;"> 
          <a-checkbox-group v-model:value="marcadas[number+29]" style="width: 200px; height: 15px;" :style="number%2 == 0? { backgroundColor: '#00000007'}:''">
              <div style="padding: 0px; height: 15px; margin-bottom: -20px; display: flex; align-items: center; overflow: hidden;" >
                <a-col :span="4" style="margin-right: -20px;">
                  <div  style="width: 20px; font-size: .7rem;">{{ number+30 }}</div>
                </a-col>
                <a-col :span="4">
                  <a-checkbox value="A" class="small-checkbox">A</a-checkbox>
                </a-col>
                <a-col :span="4">
                    <a-checkbox value="B" class="small-checkbox">B</a-checkbox>
                </a-col>
                <a-col :span="4">
                    <a-checkbox value="C" class="small-checkbox">C</a-checkbox>
                </a-col>
                <a-col :span="4">
                    <a-checkbox value="D" class="small-checkbox">D</a-checkbox>
                </a-col>
                <a-col :span="4">
                    <a-checkbox value="E" class="small-checkbox">E</a-checkbox>
                </a-col>  
              </div>
            </a-checkbox-group>
        </div>
      </div>

        </div>
      </div>

    </div>
    </div>

  <template v-slot:footer>
    <div style="text-align: right; margin-top:-40px; ">
      <a-button type="primary" @click="saveResp()">Guardar</a-button>
    </div>
  </template>
</a-modal>
<div style="display:none;">
  {{ datos = participantes.data }}
</div>

</AuthenticatedLayout>
</template>
    
<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/simulacro/LayoutSimulacro.vue'
import { watch, computed, ref, unref } from 'vue';
import { DownOutlined, SearchOutlined, EyeOutlined, FormOutlined, DeleteOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';
import xlsx from 'json-as-xlsx'

const datos = ref([])


const downloadFile = () => {
  let data = [
  {
    sheet: "Participantes",
    columns: [
      { label: "dni", value: 'dni'}, // Custom format
      { label: "Nombre", value:"nombres" }, // Run functions
      { label: "Paterno", value:"primer_apellido" },
      { label: "Materno", value:"segundo_apellido" },
      { label: "Area", value:"area" }, // Run functions
      { label: "Nota", value:"nota" } // Run functions
    ],
    content: datos.value
  },
  // {
  //   sheet: "Children",
  //   columns: [
  //     { label: "User", value: "user" }, // Top level data
  //     { label: "Age", value: "age", format: '# "years"' }, // Column format
  //     { label: "Phone", value: "more.phone", format: "(###) ###-####" }, // Deep props and column format
  //   ],
  //   content: [
  //     { user: "Manuel", age: 16, more: { phone: 9999999900 } },
  //     { user: "Ana", age: 17, more: { phone: 8765432135 } },
  //   ],
  // },
]

let settings = {
  fileName: "Lista", // Name of the resulting spreadsheet
  extraLength: 3, // A bigger number means that columns will be wider
  writeMode: "writeFile", // The available parameters are 'WriteFile' and 'write'. This setting is optional. Useful in such cases https://docs.sheetjs.com/docs/solutions/output#example-remote-file
  writeOptions: {}, // Style options from https://docs.sheetjs.com/docs/api/write-options
  RTL: false, // Display the columns from right-to-left (the default value is false)
}

xlsx(data, settings)
}






























const examen = ref(4);
const marcadas = ref([]);

const selectedOptions = ref([]);

const abrir = () => {
  visible.value = true
  dni.value = ""
  idP.value = ""
  nombreP.value = ""
  paterno.value = ""
  materno.value = ""
  area_examen.value = 4
}

const dni = ref("")
const idP = ref(null)
const nombreP = ref("JHON ARIEL")
const paterno = ref("LUQUE")
const materno = ref("CUSACANI")
const area_examen = ref(4)

//---------------------------------------------
const openModal = () => {
    visible.value = true;
}

const anio = ref("2023")
const nombre = ref(null)
const ubigeo = ref(null)
const estado = ref(true)
const id_area = ref(true)


const getSimulacros = async () => {
    let res = await axios.post(`get-simulacros?page=`+pagina.value ,
    { term: buscar.value, paginasize: paginasize.value });
    certificados.value = res.data.datos.data;
    totalpaginas.value = res.data.datos.total
}

const save =  async ( ) => {
let res = await axios.post(
  "save-simulacro",
  { 
    nombre: nombre.value,
    estado: estado.value, 
    ubigeo: depseleccionado.value + provseleccionada.value + distseleccionado.value, 
    anio: anio.value
  }
);

notificacion(res.data.tipo, res.data.titulo, res.data.mensaje)
limpiar()
}

const saveRespuestas =  async ( ) => {
let res = await axios.post(
  "save-respuestas",
  { 
    id_examen_simulacro: area_examen.value,
    id_participante: idP.value,
    respuestas: marcadas.value
  }
);

notificacion(res.data.tipo, res.data.titulo, res.data.mensaje)
limpiar()
}

const saveResp =  async ( ) => {
let res = await axios.post(
  "save-respuestas",
  { 
    dni: dni.value,
    nombres: nombreP.value,
    paterno: paterno.value,
    materno: materno.value,
    area: area_examen.value,    
    respuestas: marcadas.value
  }
  // visible.value = false
);
visible.value = false
getParticipantes()
notificacion(res.data.tipo, res.data.titulo, res.data.mensaje)
limpiar()

}


const notificacion = (type, titulo, mensaje) => {
  notification[type]({
  message: titulo,
  description: mensaje,
  });
};

const limpiar = (type, titulo, mensaje) => {
    nombre.value = null
    ubigeo.value = null
    depseleccionado.value = null
    provseleccionada.value = null
    distseleccionado.value = null
    visible.value = null
};



const columns = [
{
  title: 'Nro',
  dataIndex: 'nro',
  align: 'center',
  width:'50px'
},
{
  title: 'DNI',
  dataIndex: 'dni',
  key: 'documento',
  align: 'left'
},
{
  title: 'Nombre',
  dataIndex: 'nombres',
  key: 'name',
  align: 'left'
},
{
  title: 'Area',
  dataIndex: 'area',
  key: 'date',
},

{
  title: 'Nota',
  dataIndex: 'nota',
  width:'100px',
  align:'center'
},
{
  title: '',
  dataIndex: 'accion',
  width:'50px'
}
];

//---------------------------------------------
    
    
    
//UBIGEO

const departamentos = ref([])
const dep = ref(null)
const buscarDep = ref("")
const depseleccionado= ref ('')
const provincias = ref([])
const prov = ref(null)
const buscarProv = ref ("")
const provseleccionada = ref(null)
const distritos = ref([])
const dist = ref(null)
const buscarDist = ref("")
const distseleccionado = ref('') 
const colegios = ref([])  

const onSelectDepartamentos  = (value, option) => {
    depseleccionado.value = option.key;
    getProvincias()
}

const onSelectProvincias =  (value, option) => { 
    provseleccionada.value = option.key;
    getDistritos()
}

const onSelectDistritos = (value, option) => {
    distseleccionado.value = option.key;
} 


watch(examen, (newValue, oldValue) => {
  getParticipantes()
});

const abriModal = (item) => {
  visible.value = true
  idP.value = item.idP
  dni.value = item.dni
  nombreP.value = item.nombres
  paterno.value = item.primer_apellido
  materno.value = item.segundo_apellido
}


// END DELE    


    
const certificados = ref([])
const visible = ref(false);
const verurl = ref("");

const buscar = ref("")


const totalpaginas = ref(0) 
const pagina = ref(1)
const paginasize = ref(100)
    
    
    
watch(buscar, (newValue, oldValue) => {
  getCertificados()
});
watch(pagina, (newValue, oldValue) => {
  getCertificados()
});
watch(paginasize, (newValue, oldValue) => {
  getCertificados()
});
    
    
    

    

const getDepartamentos = async () => {
  const res = await axios.post("/get-departamentos-codigo?page=", { term: buscarDep.value });
  departamentos.value = res.data.datos.data;
}

const getProvincias = async () => {
  const res = await axios.post("/get-provincias-codigo?page=", { departamento: depseleccionado.value });
  provincias.value = res.data.datos;
}

const getDistritos = async () => {
  const res = await axios.post("/get-distritos-codigo?page=", { departamento: depseleccionado.value, provincia: provseleccionada.value });
  distritos.value = res.data.datos;
}

const participantes = ref("")

const getParticipantes = async () => {
  const res = await axios.post("get-participantes-simulacro?page=", { term: "", examen: examen.value });
  participantes.value = res.data.datos;
}

getParticipantes()
getSimulacros()
getDepartamentos();
    



</script> 
    
<style scope>

.small-checkbox {
  margin-top: -30px;
  font-size: 1.2rem; /* Tamaño del texto del checkbox */
  transform: scale(0.7); /* Reducción del tamaño del checkbox */
}
.ant-modal {
  max-width: 100%;
  top: 0;

  padding-bottom: 0;
  margin: 0;
}
.ant-modal-content {
  display: flex;
  background: #f3f3f3;
  flex-direction: column;
  height: calc(100vh);
}
.ant-modal-body {
  flex: 1;
}    

.circular-checkbox {
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  font-size: 14px;
  border: 1px solid #ccc;
}

.circular-checkbox.checked {
  background-color: #1890ff;
  color: #fff;
  border-color: #1890ff;
}

</style>