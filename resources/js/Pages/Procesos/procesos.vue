<template>
<Head title="Procesos"/>
<AuthenticatedLayout>
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">

<div class="flex justify-between mb-4" >
  <div class="mr-3">
    <a-button type="primary" style="background: #476175; border: none; border-radius: 5px;" @click="showModalProceso">Nuevo</a-button>
  </div>
  <div class="flex justify-between" style="position: relative;" >
    <a-input type="text" placeholder="Buscar" v-model:value="buscar" style="max-width: 300px; padding-left: 30px;"/>
  <div class="mr-2" style="position: absolute; left: 8px; top: 3px;"><search-outlined /></div>
  </div>
</div>

<a-table 
  :columns="columnsProcesos" 
  :data-source="procesos"
  :pagination="{ pageSize: 10}"
  size="small"
  > 
  <template #bodyCell="{ column, index, record }">

  <template v-if="column.dataIndex === 'modalidad'">
    <div class="flex" style="justify-content: center;">
      <a-tag color="cyan" v-if="procesos[index].modalidad === 'CEPREUNA'" >CEPREUNA</a-tag>
      <a-tag color="orange" v-if="procesos[index].modalidad === 'GENERAL'" >GENERAL</a-tag>
      <a-tag color="pink" v-if="procesos[index].modalidad === 'EXTRAORDINARIO'"> EXTRAORDINARIO</a-tag>
    </div>
  </template>

  <template v-if="column.dataIndex === 'estado'">
    <div class="flex" style="justify-content: center;">
      <div v-if="1 == procesos[index].estado">
          <a-tag color="green">VIGENTE</a-tag>
      </div>  
      <div v-if="procesos[index].estado == 0">
          <a-tag color="red">NO VIGENTE</a-tag>
      </div>
    </div>
  </template>

    <template v-if="column.dataIndex === 'acciones'">
      <div class="flex" style="justify-content: space-between;">
        <a-button type="" @click="irLink(record)" style="color:blue;" size="small">
          <template #icon><link-outlined/></template>
        </a-button> 
        <a-button  @click="abrirEditar(record)" style="color:#476175;" size="small">
          <template #icon><form-outlined/></template>
        </a-button>
        <a-button type="" @click="eliminar(record)" style="color:crimson;" shape="" size="small">
          <template #icon><delete-outlined/></template>
        </a-button>
      </div>
    </template>
  </template>
</a-table> 

</div>


<a-modal v-model:visible="visible" :closable="false" style="margin-top: -50px" width="800px">
  <div style="background: #476175; height: 36px; margin-left:-24px; margin-right:-24px; margin-top:-24px; margin-bottom: 28px;">
        <div class="flex justify-between ml-3 mr-1" style="height:36px; align-items: center;">
            <div><span style="font-weight: bold; color:white; font-size:1rem;">Nuevo Proceso</span></div>
            <div><span ><a-button @click="cerrarmodal()" style="background:none; border:none; color:white;">X</a-button></span></div>
        </div>
  </div>

  <a-form
    ref="formProceso"
    name="proceso"
    :model="proceso"
    :rules="formRules"
    v-bind="layout"
    @finish="handleFinish"
    @validate="handleValidate"
    @finishFailed="handleFinishFailed"
  >

  <div class="flex justify-end" style="margin-top:-15px; margin-bottom: 0px;">
    <div class="mr-4">
      <a-form-item label="Estado" name="estado">
        <a-switch v-model:checked="proceso.estado"/>
      </a-form-item>
    </div>
    <a-form-item name="ciclo" label="ciclo" :rules="[{ required: true, message: 'campo obligatorio' }]">
        <div>
        <a-select
          ref="ciclo"
          style="min-width: 120px; width: 100%;"
          @focus="focus"
          @change="handleChange"
          :options="ciclos"
          v-model:value="proceso.ciclo"
        />
      </div>
      </a-form-item>
  </div>
  <a-row :gutter="[16, 0]" class="form-row">
    <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
      <a-form-item name="nombre"
      :rules="[{ required: true, message: 'Este campo es obligatorio' }]"
      >
        <div>
          <label for="">Nombre (<span style="color:red">*</span>)</label>
          <a-input type="text" style="width: 100%;" v-model:value="proceso.nombre" autocomplete="off" />
        </div>
      </a-form-item>
    </a-col>

    <a-col :span="24" :md="12" :lg="8" :xl="8" :xxl="8">
      <a-form-item name="convocatoria">
        <div>
          <label for="">N° convocatoria</label>
          <a-input type="text" style="width: 100%;" v-model:value="proceso.convocatoria" autocomplete="off" />
        </div>
      </a-form-item>
    </a-col>

    <a-col :span="24" :md="12" :lg="16" :xl="16" :xxl="16">
      <a-form-item name="slug" :rules="[{ required: true, message: 'campo obligatorio' }]">
        <div>
          <label for="">Slug(<span style="color:red">*</span>)</label>
          <a-input type="text" style="width: 100%;" v-model:value="proceso.slug" autocomplete="off" />
        </div>
      </a-form-item>
    </a-col>

    <a-col :span="24" :md="12" :lg="12" :xl="8" :xxl="24">
      <a-form-item name="sede" :rules="[{ required: true, message: 'campo obligatorio' }]">
        <div>
        <label for="">Sede (<span style="color:red">*</span>)</label>
        <a-select
          ref="Tipo"
          style="width: 100%"
          @focus="focus"
          @change="handleChange"
          :options="sedes"
          v-model:value="proceso.sede"
        />
      </div>
      </a-form-item>
    </a-col>

    <a-col :span="24" :md="12" :lg="12" :xl="8" :xxl="24">
      <div>
        <label for="">Tipo estudio (<span style="color:red">*</span>)</label>
        <a-form-item name="tipo" :rules="[{ required: true, message: 'campo obligatorio' }]">
          <a-select
            ref="Tipo"
            style="width: 100%"
            :options="tipoProcesos"
            @focus="focus"
            @change="handleChange"
            v-model:value="proceso.tipo"
          />
        </a-form-item>
      
      </div>
    </a-col>

    <a-col :span="24" :md="12" :lg="12" :xl="8" :xxl="24">
      <div>
        <label for="">Mod. Examen (<span style="color:red">*</span>)</label>
        <a-form-item name="modalidad" :rules="[{ required: true, message: 'campo obligatorio' }]">
          <a-select
            ref="Tipo"
            style="width: 100%"
            :options="modalidades"
            @focus="focus"
            @change="handleChange"
            v-model:value="proceso.modalidad"
          />
        </a-form-item>
      </div>
    </a-col>

    <a-col :span="24" :md="12" :lg="12" :xl="8" :xxl="24">
      <div>
        <label for="">Año (<span style="color:red">*</span>)</label>
        <a-form-item name="anio" :rules="[{ required: true, message: 'campo obligatorio' }]">
          <a-input-number style="width: 100%;"  v-model:value="proceso.anio" />
        </a-form-item>      
      </div>
    </a-col>

    <a-col :span="24" :md="12" :lg="12" :xl="8" :xxl="24">
      <div>
        <label for=""> Fec. inicio</label>
        <a-form-item name="estado">
          <a-date-picker v-model:value="proceso.f_inicio" format="DD/MM/YYYY" placeholder="fecha inicio" style="width: 100%;"/>
        </a-form-item>
      </div>
    </a-col>

    <a-col :span="24" :md="12" :lg="12" :xl="8" :xxl="24">
      <div>
        <label for=""> Fec. fin</label>
        <a-form-item name="estado">
          <a-date-picker v-model:value="proceso.f_fin" format="DD/MM/YYYY" placeholder="fecha fin" style="width: 100%;"/>
        </a-form-item>
      </div>
    </a-col>

    <a-col :span="24" :md="24" :lg="8" :xl="8" :xxl="8">
      <a-form-item name="fec_examen" :rules="[{ required: true, message: 'campo obligatorio' }]">
        <div>
          <label for="">Fec. examen</label>
          <a-input type="text" style="width: 100%;" v-model:value="proceso.fec_examen" autocomplete="off" />
        </div>
      </a-form-item>
    </a-col>

    <a-col :span="24" :md="24" :lg="16" :xl="16" :xxl="16">
      <a-form-item name="url">
        <div>
          <label for="">URL</label>
          <a-input v-if="proceso.slug != null" type="text" style="width: 100%;" :value="baseUrl+'/'+proceso.slug+'/preinscripcion'" autocomplete="off" disabled />
          <a-input v-else type="text" style="width: 100%;" :value="baseUrl+'/'+proceso.slug+'/preinscripcion'" autocomplete="off" disabled />
        </div>
      </a-form-item>
    </a-col>

    <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
      <a-form-item name="observacion">
        <div>
          <label for="">Observacion</label>
          <a-textarea type="text" style="width: 100%;" v-model:value="proceso.observacion" autocomplete="off"/>
        </div>
      </a-form-item>
    </a-col>

  </a-row>

  </a-form>

<template #footer>
  <a-button style="margin-left: 10px;" @click="cancelar()">Cancelar</a-button>
  <a-button type="primary" style="background: #476175; border:none;" @click="guardar()">Guardar</a-button>
</template>
</a-modal>
</AuthenticatedLayout>



</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { watch, computed, ref, reactive } from 'vue';
import { FormOutlined, LinkOutlined, DeleteOutlined, SearchOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';
import dayjs from 'dayjs';
const baseUrl = window.location.origin;

const buscar = ref("");
const sedes = ref([]);
const tipoProcesos = ref([]);
const procesos= ref([]);
const visible = ref(false);
const modalidades = ref([])
const formProceso = ref(null);
const proceso = reactive({
  id:null,
  nombre:"",
  convocatoria:"",
  slug:"",
  sede:null,
  tipo:null,
  ciclo:null,
  modalidad: null,
  fec_examen:"",
  anio:new Date().getFullYear(),
  estado: true,
  f_inicio : '',
  f_fin:'',
  url:"",
  observacion:""
})

const showModalProceso = () => {
  visible.value = true;
};

let timeoutId;
watch(buscar, ( newValue, oldValue ) => {
  clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
      getProcesos();
    }, 500);  

})


const abrirEditar = (item) => {
  console.log(item);
  proceso.value = null;
  visible.value = true;
  proceso.id = item.id;
  proceso.nombre = item.nombre;
  proceso.slug = item.slug;
  proceso.sede = item.id_sede;
  proceso.convocatoria = item.convocatoria;
  proceso.url = item.url;
  proceso.ciclo = item.ciclo;
  proceso.fec_examen = item.fecha_examen;
  if(item.fec_inicio){ proceso.f_inicio = dayjs(item.fec_inicio) }
  if(item.fec_fin){ proceso.f_fin = dayjs(item.fec_fin) }
  proceso.tipo = item.id_tipo;
  proceso.observacion = item.observacion;
  proceso.modalidad = item.id_modalidad;
  proceso.anio = item.anio;
if(item.estado == 1){ proceso.estado = true }
  else { proceso.estado = false}
}

const getProcesos =  async (term = "", page = 1) => {
  let res = await axios.post(
    "/admin/procesos/get-procesos?page=" + page,
    { term: buscar.value }
  );
  procesos.value = res.data.datos.data;
}

const getSedes =  async (term = "", page = 1) => {
  let res = await axios.post(
    "get-sedes?page=" + page,
  );
  sedes.value = res.data.datos.data;
  //proceso.value.sede = res.data.datos.data[0].value;
}

const getTipos =  async (term = "", page = 1) => {
  let res = await axios.get(
    "procesos/get-tipos?page=" + page,
  );
  tipoProcesos.value = res.data.datos;
  //proceso.value.tipo = res.data.datos[0].value;
}

const getModalidades =  async (term = "", page = 1) => {
  let res = await axios.get(
    "procesos/get-modalidades?page=" + page,
  );
  modalidades.value = res.data.datos;
  //proceso.value.modalidad = res.data.datos[0].value;
}

const guardar = async ()  => {
  const values = await formProceso.value.validateFields();
  axios.post("save-proceso", proceso).then((result) => {
    notificacion('success',result.data.titulo, result.data.mensaje);
    getProcesos();
    visible.value = false;
  });
}

const eliminar = (item) => {
  axios.get("eliminar-proceso/"+item.id).then((result) => {
    getProcesos();
    notificacion('warning', 'PROCESO ELIMINADO', result.data.mensaje );
  });
}

const columnsProcesos = [
  { title: 'Nombre', dataIndex: 'nombre',  },
  { title: 'Sede', dataIndex: 'sede', align:'center'},
  { title: 'Tipo', dataIndex: 'tipo', align:'center', width:'120px' },
  { title: 'Modalidad', dataIndex: 'modalidad', align:'center', width:'120px' },
  { title: 'Año', dataIndex: 'anio', width:'40px'},
  { title: 'Estado', dataIndex: 'estado', align:'center', width:'60px' },
  { title: 'Acciones', dataIndex: 'acciones', width:'50px'},
];

const notificacion = (type, titulo, mensaje) => {
  notification[type]({
    message: titulo,
    description: mensaje,
  });
};

const cerrarmodal = () => {
  visible.value = false;
}

const cancelar = () => {
  visible.value = false;
}

const ciclos = ref([
  {value:"1", label:"I"},
  {value:"2", label:"II"},
]);


const irLink = (slug) => {
  window.open(baseUrl+"/"+slug.slug+'/preinscripcion', '_blank');
}


getProcesos()
getSedes()
getTipos()
getModalidades()
</script>