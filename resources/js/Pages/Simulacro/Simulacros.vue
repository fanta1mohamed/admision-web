<template>
    <AuthenticatedLayout>
    <div v-if="examen === 0">
      <a-card style="background: white;" class="m-0 p-0">
        <div class="mb-2 flex justify-between">
          <div>
            <a-button type="primary" @click="openModal">Nuevo</a-button>
          </div>  
          <a-input placeholder="Buscar" v-model:value="buscar"  style="max-width: 300px;">
            <template #suffix>
              <search-outlined />
            </template>
          </a-input>
        </div>
       
        <div>   
          <!-- {{ totalpaginas }} -->
          <a-table size="small" :dataSource="certificados" :columns="columns" :pagination="false">
            <template #bodyCell="{ column, text, record }">
              <template v-if="column.dataIndex === 'ver'" >
                  <eye-outlined @click="abrirmodal(record)" class="custom-icon"/>
              </template>
              <template v-if="column.dataIndex === 'cod'" >
                  <div><span style="font-weight: bold; font-size: 0.9rem;"> {{ record.cod }}  </span></div>
              </template>
    
              <template v-if="column.dataIndex === 'nombre'"  >
                <div class="flex">
                  <!-- <div style="margin-bottom: -5px;" class="mr-2"><span style="font-weight: bold; font-size: .9rem;"> {{ record.dni }}  </span></div> -->
                  <span @click="examen = 1"  style="cursor:pointer; text-transform: uppercase; font-size: .9rem;">  {{ record.nombre }}</span>
                </div>
              </template>
    
              <template v-if="column.dataIndex === 'estado'">
                <div class="flex" style="justify-content: center;">
                    <div v-if="1 == record.estado">
                        <a-tag color="green">VIGENTE</a-tag>
                    </div>
                    <div v-if="record.estado == 3">
                        <a-tag color="blue">FINALIZADO</a-tag>
                    </div>
                </div>
              </template>
              <template v-if="column.dataIndex === 'operation'">
                <div class="flex" style="justify-content: space-between;">
                    <a-button type="primary" @click="abrirEditar(record.id)" size="small">
                    <template #icon><form-outlined/></template>
                    </a-button>
                    <a-button type="danger" @click="eliminar(record.id)" shape="" size="small">
                    <template #icon><delete-outlined/></template>
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
    
    <a-modal v-model:visible="visible" title="Nuevo simulacro" :ok="false" style="margin-top: -70px;"
    :footer="false"
    >
        <a-form :model="formState" :label-col="labelCol" :wrapper-col="wrapperCol">
            <div class="flex justify-between">
                <a-form-item label="Año" >
                    <a-input v-model:value="anio" style="width: 80px; margin-right: 20px;" />
                </a-form-item>
                <a-form-item label="Estado">
                    <a-switch  v-model:checked="estado"/>
                </a-form-item>
            </div>

            <a-form-item>
                <div><label>Nombre: </label></div>
            <a-input  v-model:value="nombre"/>
            </a-form-item>

            <a-row :gutter="[16, 0]" class="form-row">
                <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
                <a-form-item>
                    <div><label>Departamento: </label></div>
        
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
                <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
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
                <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
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
            
            <div class="flex justify-end">
                <a-button type="primary" @click="save()">Guardar</a-button>
            </div> 
          
        </a-form>
    </a-modal>
    </AuthenticatedLayout>
    </template>
    
<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/simulacro/LayoutSimulacro.vue'
import { watch, computed, ref, unref } from 'vue';
import { DownOutlined, SearchOutlined, EyeOutlined, FormOutlined, DeleteOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';

    

const examen = ref(0);
    
    
    
//---------------------------------------------
const openModal = () => {
    visible.value = true;
}

const anio = ref("2023")
const nombre = ref(null)
const ubigeo = ref(null)
const estado = ref(true)


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
        title: 'Nombre',
        dataIndex: 'nombre',
        key: 'name',
        align: 'left'
      },
      {
        title: 'Fecha',
        dataIndex: 'fecha',
        key: 'date',
      },
      {
        title: 'Estado',
        dataIndex: 'estado',
        key: 'verificado',
        align: 'center'
      },
      {
        title: 'operation',
        dataIndex: 'operation',
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
// END DELE
    
    
    
    
    
    const certificados = ref([])
    const visible = ref(false);
    const verurl = ref("");
    
    const buscar = ref("")
    
    
    const totalpaginas = ref(0) 
    const pagina = ref(1)
    const paginasize = ref(2)
    
    

    
    const actualizarEstado = async (item) => {
      let estado = 0; 
      if(item.verificado === 0) { estado = 1 }else { estado = 0}
      let res = await axios.post(`cambiar-estado`,{ id: item.id, estado: estado });
      notificacion('success',res.data.titulo, res.data.mensaje);
      getCertificados()
    }
    
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

    
    getSimulacros()
    getDepartamentos();
    




</script> 
    
        <style scope>
    .custom-icon {
      color: var(--primary-color);
      cursor: pointer;
      border: none;
      border-radius: 4px;
      transition: all 0.3s ease;
    }
    
    .custom-icon:hover {
      box-shadow: 0 10px 20px #4806ff59;
      transform: translateY(-2px);
    }
    .custom-icon:active {
      color: #0099ff;
      transform: scale(0.99);
    }
    
    .custom-button {
      background-color: var(--primary-color);
      color: white;
      border: none;
      border-radius: 4px;
      box-shadow: 0 0 0 rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }
    
    .custom-button:hover {
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
      transform: translateY(-2px);
    }
    
    .custom-button:active {
      transform: scale(0.99);
    }
    
    
    
    </style>