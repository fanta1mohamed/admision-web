<template>
<Head title="Procesos"/>
<AuthenticatedLayout>
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">

{{ buscar }}
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
    <template v-if="column.dataIndex === 'acciones'">
      <a-button type="primary" @click="abrirEditar(procesos[index])" size="small">
        <template #icon><form-outlined/></template>
      </a-button>
      <a-divider type="vertical" />
      <a-button type="danger" @click="eliminar(procesos[index])" shape="" size="small">
        <template #icon><delete-outlined/></template>
      </a-button>
    </template>
  </template>
</a-table> 

</div>

</AuthenticatedLayout>

<div>

  <a-modal v-model:visible="visible" title="Nuevo Proceso" style="margin-top: -40px;">
      <pre> {{ proceso }} </pre> 
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
          <a-input type="text" v-model:value="proceso.nombre" autocomplete="off" />
        </a-form-item>
        <a-form-item has-feedback label="Sede" name="sede">
          <a-input type="text" v-model:value="proceso.sede" autocomplete="off" />
        </a-form-item>

        <a-form-item has-feedback label="Tipo" name="tipo">
          <a-select
            ref="Tipo"
            style="width: 120px"
            @focus="focus"
            @change="handleChange"
            v-model:value="proceso.tipo"
          >
            <a-select-option value="PRESENCIAL">Presencial</a-select-option>
            <a-select-option value="VIRTUAL">Virtual</a-select-option>
          </a-select>
        </a-form-item>
        <a-form-item has-feedback label="Año" name="anio">
          <a-input-number v-model:value="proceso.anio" />
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
  const procesos= ref([]);
  const visible = ref(false);
  const proceso = ref({
    id:null,
    nombre:"",
    sede:"",
    tipo:"Presencial",
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
      proceso.value.sede = null;
      proceso.value.tipo = 'Presencial';
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
      proceso.value.sede = item.sede;
      proceso.value.tipo = item.tipo;
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
    
    const guardar = () => {
      let post = {
        id: proceso.value.id,
        nombre: proceso.value.nombre ,
        sede: proceso.value.sede,
        tipo_proceso: proceso.value.tipo,
        anio: proceso.value.anio,
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
      { title: 'Nombre', dataIndex: 'nombre', sorter :true },
      { title: 'Sede', dataIndex: 'sede', sorter :true },
      { title: 'Tipo', dataIndex: 'tipo', sorter :true },
      { title: 'Año', dataIndex: 'anio'},
      { title: 'Estado', dataIndex: 'estado'},
      { title: 'Acciones', dataIndex: 'acciones'},
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
    
    
    </script>