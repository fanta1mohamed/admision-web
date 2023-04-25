<template>
<Head title="Procesos"/>
<AuthenticatedLayout>
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">

<row class="flex justify-between mb-4" >
  <div class="mr-3">
    <a-button type="primary" @click="showModalProceso">Nuevo</a-button>
  </div>
  <div class="flex justify-between" style="position: relative;" >
    <a-input type="text" placeholder="Buscar" v-model:value="buscar" style="max-width: 300px; padding-left: 30px;"/>
    <div class="mr-2" style="position: absolute; left: 8px; top: 3px;"><search-outlined /></div>
  </div>
</row>
    
<a-table 
  :columns="columnsProcesos" 
  :data-source="procesos"
  :pagination="{ pageSize: 10}"
  size="small"
  > 
  <template #bodyCell="{ column, index }">

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
        <a-button type="primary" @click="abrirEditar(procesos[index])" size="small">
          <template #icon><form-outlined/></template>
        </a-button>
        <a-button type="danger" @click="eliminar(procesos[index])" shape="" size="small">
          <template #icon><delete-outlined/></template>
        </a-button>
      </div>
    </template>
  </template>
</a-table> 

</div>

</AuthenticatedLayout>

<div>

  <a-modal v-model:visible="visible" title="Nuevo Proceso" style="margin-top: -40px;">
      <!-- <pre> {{ proceso }} </pre>  -->
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
        <a-form-item has-feedback label="Nombre" name="nombre">
          <a-input type="text" style="width: 100%;" v-model:value="proceso.nombre" autocomplete="off" />
        </a-form-item>
        <a-form-item has-feedback label="Sede" name="sede">
          <a-select
            ref="Tipo"
            style="width: 100%"
            @focus="focus"
            @change="handleChange"
            :options="sedes"
            v-model:value="proceso.sede"
          />

        </a-form-item>
        <a-form-item has-feedback label="Tipo" name="tipo">
          <a-select
            ref="Tipo"
            style="width: 100%"
            :options="tipoProcesos"
            @focus="focus"
            @change="handleChange"
            v-model:value="proceso.tipo"
          />
        </a-form-item>
        <a-form-item has-feedback label="Modalidad" name="modalidad">
          <a-select
            ref="Tipo"
            style="width: 100%"
            :options="modalidades"
            @focus="focus"
            @change="handleChange"
            v-model:value="proceso.modalidad"
          />
        </a-form-item>
        <a-form-item has-feedback label="Año" name="anio">
          <a-input-number style="width: 100%;"  v-model:value="proceso.anio" />
        </a-form-item>
        <a-form-item has-feedback label="Estado" name="estado">
          <a-switch v-model:checked="proceso.estado"/>
        </a-form-item>

        <!-- <a-form-item has-feedback label="F. inicio" name="estado">
          <a-date-picker v-model:value="proceso.f_inicio" format="DD-MM-YYYY"/>
        </a-form-item>
        <a-form-item has-feedback label="F. fin" name="estado">
          <a-date-picker v-model:value="proceso.f_fin" />
        </a-form-item> -->
      </a-form>

    <template #footer>
      <a-button style="margin-left: 10px;" @click="resetForm">Cancelar</a-button>
      <a-button type="primary" @click="guardar()">Guardar</a-button>
    </template>
  </a-modal>
</div>

</template>
    
<script setup>
  import { Head } from '@inertiajs/vue3';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
  import { watch, computed, ref, unref } from 'vue';
  import { FormOutlined, DeleteOutlined, SearchOutlined } from '@ant-design/icons-vue';
  import { notification } from 'ant-design-vue';
  import axios from 'axios';

  const buscar = ref("");
  const sedes = ref([]);
  const tipoProcesos = ref([]);
  const procesos= ref([]);
  const visible = ref(false);
  const modalidades = ref([])
  const proceso = ref({
    id:null,
    nombre:"",
    sede:null,
    tipo:null,
    modalidad: null,
    anio:new Date().getFullYear(),
    estado: true,
    f_inicio : '',
    f_fin:''
  })

  const showModalProceso = () => {
      visible.value = true;
  };
    

  watch(buscar, ( newValue, oldValue ) => {
    getProcesos();
  })

  watch(visible, ( newValue, oldValue ) => {
    if(visible.value == false && proceso.value.id != null ){
      proceso.value.id = null;
      proceso.value.nombre = null;
      proceso.value.sede = sedes.value[0].value;
      proceso.value.tipo = tipoProcesos.value[0].value;
      proceso.value.modalidad = modalidades.value[0].value;
      proceso.value.anio = new Date().getFullYear();
      proceso.value.estado = true;

    }
  })

  const layout = {
      labelCol: {
        span: 4,
      },
      wrapperCol: {
        span: 14,
      },
  };

  let validateNombre = async (_rule, value) => {
    if (value === '') {
      return Promise.reject('Ingrese su el nombre del proceso');
    } else {
      return Promise.resolve();
    }
  };

  let validateSede = async (_rule, value) => {
    if (value === '') {
      return Promise.reject('Ingrese la sede del proceso');
    } else {
      return Promise.resolve();
    }
  };

  const rules = {
    nombre: [{
      required: true,
      validator: validateNombre,
      trigger: 'change',
    }],
    sede: [{
      required: true,
      validator: validateSede,
      trigger: 'change',
    }],

  };
    
  const roles = ref([])
  const permisos = ref([]);
    


    const handleOk = e => {
      console.log(e);
      visible.value = false;
    };
    const getPermisos = async () => {  
      let res = await axios.get(`get-permission`);
      permisos.value = res.data.permisos;
    }
 
    const abrirEditar = (item) => {
      visible.value = true;
      proceso.value.id = item.id;
      proceso.value.nombre = item.nombre;
      proceso.value.sede = item.id_sede;
      proceso.value.tipo = item.id_tipo;
      proceso.value.modalidad = item.id_modalidad;
      proceso.value.anio = item.anio;
      if(item.estado == 1){ proceso.value.estado = true }
      else { proceso.value.estado = false}
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
        "procesos/get-sedes?page=" + page,
      );
      sedes.value = res.data.datos.data;
      proceso.value.sede = res.data.datos.data[0].value;
    }

    const getTipos =  async (term = "", page = 1) => {
      let res = await axios.get(
        "procesos/get-tipos?page=" + page,
      );
      tipoProcesos.value = res.data.datos;
      proceso.value.tipo = res.data.datos[0].value;
    }

    const getModalidades =  async (term = "", page = 1) => {
      let res = await axios.get(
        "procesos/get-modalidades?page=" + page,
      );
      modalidades.value = res.data.datos;
      proceso.value.modalidad = res.data.datos[0].value;
    }

    const guardar = () => {
      let post = {
        id: proceso.value.id,
        nombre: proceso.value.nombre ,
        sede: proceso.value.sede,
        tipo: proceso.value.tipo,
        anio: proceso.value.anio,
        modalidad: proceso.value.modalidad,
        estado: proceso.value.estado,
        f_inicio: proceso.value.f_inicio,
        f_fin: proceso.value.f_fin,
      };
      axios.post("save-proceso", post).then((result) => {
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




    getProcesos()
    getSedes()
    getTipos()
    getModalidades()
    
    
    </script>