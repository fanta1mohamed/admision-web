<template>
<Head title="Proceso - Configuracion - programas"/>
<AuthenticatedLayout>
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4" style="height: calc(100vh - 98px);">
  <row class="flex justify-between mb-4">
      <div class="mr-3">
          <a-button type="primary" style="border-radius: 5px; background: #476175" @click="showModalPrograma">Modificar</a-button>
      </div>
      <div class="flex justify-between" style="position: relative;">
      <a-input type="text" placeholder="Buscar" v-model:value="buscar" style="max-width: 300px; padding-left: 30px;"/>
      <div class="mr-2" style="position: absolute; left: 8px; top: 3px; "><search-outlined/></div>
      </div>
  </row>

  <!-- <a-table 
    :columns="modalidades" 
    :data-source="programas"
    :pagination="false"
    size="small"
    :scroll="{ x: 380, y: 'calc(100vh - 240px)' }"
  >
  <template #bodyCell="{ column, index, record }">
    <template v-for="col in modalidades" :key="col.dataIndex">
      <template v-if="column.dataIndex === col.dataIndex">
        <template v-if="col.dataIndex === 0">
          {{ record.label }}
        </template>
        <template v-else>
          {{ record.value }} {{ column.dataIndex}}
        </template>
      </template>
    </template>
  </template>
  </a-table> -->

  <a-table 
          :columns="columns" 
          :data-source="programasProceso"
          :pagination="false"
          size="small"
          bordered
          :scroll="{ x: 380, y: 'calc(100vh - 240px)' }"
          >
          <template #bodyCell="{ column, index, record }">              
              <template v-if="column.dataIndex === 'estado'">
                  <div class="flex" style="justify-content: center;">
                      <div v-if="1 == programas[index].estado">
                          <a-tag color="green">Si</a-tag>
                      </div>
                      <div v-if="programas[index].estado == 0">
                          <a-tag color="red">No</a-tag>
                      </div>
                  </div>
              </template>

              <template v-if="column.dataIndex === 'acciones'">
                  <a-button type="" @click="verDetalle(record)" style="border-radius:4px; background: none; color: green" size="small">
                      <template #icon><eye-outlined/></template>
                  </a-button>
                  <a-button type="" @click="abrirEditar(record)" style="border-radius:4px; background: none; color: gray" size="small">
                      <template #icon><form-outlined/></template>
                  </a-button>
                  <a-button class="" @click="eliminar(record)" style="border-radius:4px; background: none; color: red;" shape="" size="small">
                  <template #icon><delete-outlined/></template>
                  </a-button>
              </template>
          </template>
      </a-table> 


  <div style="display:none;">
    <a-table 
          :columns="columnsProgramas" 
          :data-source="programas"
          :pagination="false"
          size="small"
          :scroll="{ x: 380, y: 'calc(100vh - 290px)' }"
          >
          <template #bodyCell="{ column, index, record }">
              <template v-if="column.dataIndex === 'codigo'">
                  <div><span style="font-size: .9rem">{{ record.codigo }}</span></div>                
              </template>

              <template v-if="column.dataIndex === 'nombre'">
                  <div><span style="font-size: .9rem;">{{ record.nombre }}</span></div>                
              </template>
              <template v-if="column.dataIndex === 'facultad'">
                  <div><span style="font-size: .9rem;">{{ record.facultad }}</span></div>                
              </template>
              <template v-if="column.dataIndex === 'area'">
                  <div class="flex" style="justify-content: center;">
                      <a-tag style="font-size: .8rem;" color="cyan" v-if=" programas[index].area == 'BIOMÉDICAS'">{{ programas[index].area }}</a-tag>
                      <a-tag style="font-size: .8rem;" color="purple" v-if=" programas[index].area == 'SOCIALES'">{{ programas[index].area }}</a-tag>
                      <a-tag style="font-size: .8rem;" color="blue" v-if=" programas[index].area == 'INGENIERÍAS'">{{ programas[index].area }}</a-tag>
                  </div>
              </template>
              
              <template v-if="column.dataIndex === 'estado'">
                  <div class="flex" style="justify-content: center;">
                      <div v-if="1 == programas[index].estado">
                          <a-tag color="green">Si</a-tag>
                      </div>
                      <div v-if="programas[index].estado == 0">
                          <a-tag color="red">No</a-tag>
                      </div>
                  </div>
              </template>

              <template v-if="column.dataIndex === 'acciones'">
                  <a-button type="" @click="verDetalle(record)" style="border-radius:4px; background: none; color: green" size="small">
                      <template #icon><eye-outlined/></template>
                  </a-button>
                  <a-button type="" @click="abrirEditar(record)" style="border-radius:4px; background: none; color: gray" size="small">
                      <template #icon><form-outlined/></template>
                  </a-button>
                  <a-button class="" @click="eliminar(record)" style="border-radius:4px; background: none; color: red;" shape="" size="small">
                  <template #icon><delete-outlined/></template>
                  </a-button>
              </template>
          </template>
      </a-table> 
  </div>
  <div class="flex" style="justify-content: flex-end;">
      <a-pagination v-model:current="pagina" simple page-size="50" :total="totalpaginas" />
  </div>

  </div>
  
</AuthenticatedLayout>  

<a-modal v-model:visible="visible" :title="'Titulo Modal'" style="margin-top: -40px;">

          <a-form
              ref="formRef"
              name="custom-validation"
              :model="formState"
              :rules="rules"
              v-bind="layout"
              @finish="handleFinish"
              @validate="handleValidate"
              @finishFailed="handleFinishFailed"
          >
          <a-form-item has-feedback label="Codigo" name="codigo">
              <a-input type="text" v-model:value="programa.codigo" autocomplete="off" />
          </a-form-item>
          <a-form-item has-feedback label="Nombre" name="nombre">
              <a-input type="text" v-model:value="programa.nombre" autocomplete="off" />
          </a-form-item>
      
          <a-form-item has-feedback label="Facultad" name="facultad">
              <a-select
                  :options="facultades"
                  ref="Tipo"
                  style="width: 100%"
                  @focus="focus"
                  @change="handleChange"
                  v-model:value="programa.id_facultad"
                  >
              </a-select>
          </a-form-item>

          <a-form-item has-feedback label="Area" name="area">
              <a-select
                  style="width: 100%"
                  @focus="focus"
                  @change="handleChange"
                  v-model:value="programa.area"
                  >
                  <a-select-option value="BIOMÉDICAS">
                      BIOMEDICAS
                  </a-select-option>
                  <a-select-option value="INGENIERÍAS">
                      INGENIERÍAS
                  </a-select-option>
                  <a-select-option value="SOCIALES">
                      SOCIALES
                  </a-select-option>
              </a-select>
          </a-form-item>
          
          <a-form-item has-feedback label="Vigente" name="estado">
              <a-switch v-model:checked="programa.estado"/>
          </a-form-item>
  
          </a-form>
  
      <template #footer>
          <a-button style="margin-left: 10px;" @click="resetForm">Cancelar</a-button>
          <a-button type="primary" @click="guardar()">Guardar</a-button>
      </template>
</a-modal>
</template>
          
<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { watch, computed, ref, unref } from 'vue';
import { EyeOutlined, FormOutlined, DeleteOutlined, SearchOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';

const programas = ref([])
const modalidades = ref([])
//-----------------  FIN -----------------//

const buscar = ref("");
const pagina = ref(1)
const totalpaginas = ref(null)

const programasProceso = ref([]);

const visible = ref(false);
const buscarDep = ref("")


const showModalPrograma = () => {
    visible.value = true;
};


watch(buscarDep, ( newValue, oldValue ) => {
    getDepartamentos()
})


watch(pagina, ( newValue, oldValue ) => {
    getProgramas()
})

const getProgramasProceso =  async (term = "") => {
    let res = await axios.get("/admin/get-programas-proceso");
    programasProceso.value = res.data.datos;
}

const abrirEditar = (item) => {

    visible.value = true;
    programa.value.id = item.id;
    programa.value.codigo = item.codigo;
    programa.value.nombre = item.nombre;
    programa.value.nivel_academico = item.nivel_academico;
    programa.value.tipo_autorizacion = item.tipo_autorizacion;
    programa.value.id_facultad = item.id_fac;
    if(item.estado == 1){ programa.value.estado = true }
    else { programa.value.estado = false}
    programa.value.area = item.area
}


const getProgramas =  async (term = "") => {
    let res = await axios.post("/select-programas");
    programas.value = res.data.datos;
}

const getModalidades =  async (term = "") => {
    let res = await axios.post("/select-modalidades");
    modalidades.value = res.data.datos.data;
}


const guardar = () => {
    let post = {
    id: programa.value.id,
    codigo: programa.value.codigo,
    nombre: programa.value.nombre,
    nivel_academico: programa.value.nivel_academico,
    tipo_autorizacion: programa.value.tipo_autorizacion,
    estado: programa.value.estado,
    id_facultad: programa.value.id_facultad,
    area: programa.value.area,
    };
    axios.post("save-programa", post).then((result) => {
    getProgramas()
    notificacion('success',result.data.titulo, result.data.mensaje);
    visible.value = false;
    programa.value.codigo = null,
    programa.value.id = null,
    programa.value.nombre = ""
    });
}

const eliminar = (item) => {
    axios.get("eliminar-programa/"+item.id).then((result) => {
    getProgramas();
    notificacion('warning', 'PROGRAMA ELIMINADO', result.data.mensaje );
    });
}

const columnsProgramas = [
    { title: 'Cod', dataIndex: 'codigo', width:'60px', align:'center', responsive: ['md'],},
    { title: 'Nombre', dataIndex: 'nombre'},
    { title: 'Area', dataIndex: 'area', align:'center', width:"100px", responsive: ['md'],},
    { title: 'Fun.', dataIndex: 'estado', align:'center', width:'60px', responsive: ['md'],},
    { title: 'Acciones', dataIndex: 'acciones', width:"90px", align:'center'},
];

const columns = [
    { title: 'PROGRAMA', dataIndex: 'programa', width:'360px',  align:'left', responsive: ['md'],},    
    { title: 'GENERAL', dataIndex: '8', align:'center', responsive: ['md'],},
    { title: 'CEPREUNA', dataIndex: '9', align:'center', responsive: ['md'],},
    { title: 'CONADIS', dataIndex: '7', align:'center', responsive: ['md'],},
    { title: 'GRAD. TITULADOS', dataIndex: '1', align:'center', responsive: ['md'],},
    { title: 'TRASLADO INTERNO', dataIndex: '2', align:'center', responsive: ['md'],},
    { title: 'TRASLADO EXTERNO', dataIndex: '3', align:'center', responsive: ['md'],},
    { title: 'PRIMEROS PUESTOS', dataIndex: '4', align:'center', responsive: ['md'],},
    { title: 'DEPORTISTAS', dataIndex: '5', align:'center', responsive: ['md'],}
]


const selectedRowKeys = ref([]); 

const onSelectChange = changableRowKeys => {
    console.log('selectedRowKeys changed: ', changableRowKeys);
    selectedRowKeys.value = changableRowKeys;
};
const rowSelection = computed(() => {
    return {
    selectedRowKeys: unref(selectedRowKeys),
    onChange: onSelectChange,
    hideDefaultSelections: true,
    };
});

const notificacion = (type, titulo, mensaje) => {
    notification[type]({
    message: titulo,
    description: mensaje,
    });
};

const verDetalle = (item) => {
    console.log("Detalle:", item);
};


getProgramas()
getModalidades()
getProgramasProceso()
  
</script>
  
  <style >
  ::-webkit-scrollbar {
    width: 9px;
    height: 12px;
  }
  
  ::-webkit-scrollbar-track {
    background: #f1f1f1; 
    border-radius: 10px;
  }
  
  ::-webkit-scrollbar-thumb {
    background: #888; 
    border-radius: 10px;
  }
  
  ::-webkit-scrollbar-thumb:hover {
    background: #555; 
  }
  
  /* Estilo para un scroll específico */
  .scroll-container {
    overflow-y: auto;
    scrollbar-width: thin; /* Firefox */
    scrollbar-color: #888 #f1f1f1; /* Firefox */
  }
  
  /* Estilo para el scroll específico en Webkit (Chrome, Safari) */
  .scroll-container::-webkit-scrollbar {
    width: 12px;
    height: 12px;
  }
  
  .scroll-container::-webkit-scrollbar-track {
    background: #f1f1f1; 
    border-radius: 10px;
  }
  
  .scroll-container::-webkit-scrollbar-thumb {
    background: #888; 
    border-radius: 10px;
  }
  
  .scroll-container::-webkit-scrollbar-thumb:hover {
    background: #555; 
  }
      
  </style>