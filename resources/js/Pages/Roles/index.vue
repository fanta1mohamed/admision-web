<template>
<Head title="Roles"/>
<AuthenticatedLayout>
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<h1> Roles </h1>
<a-button type="primary" @click="showModalRol">Nuevo</a-button>
<a-table 
  :columns="columnsRoles" 
  :data-source="roles"
  :pagination="{ pageSize: 5 }"
  size="small"
  > 
  <template #bodyCell="{ column, index }">
    <template v-if="column.dataIndex === 'acciones'">
      <a-button type="primary" @click="abrirEditar(roles[index])" size="small">
        <template #icon><form-outlined/></template>
      </a-button>
      <a-divider type="vertical" />
      <a-button type="danger" shape="" size="small">
        <template #icon><delete-outlined /></template>
      </a-button>
    </template>
  </template>
</a-table> 

</div>

</AuthenticatedLayout>

<div>
  <a-modal v-model:visible="visible" title="Crear Roles">
    <a-form-item name="email">
      <a-label>Rol</a-label>
      <a-input v-model:value="rolname" type="text" autocomplete="off" />
    </a-form-item>
    <a-table 
      :row-selection="rowSelection" 
      :columns="columns" 
      :data-source="permisos"
      :pagination="{ pageSize: 10 }"
      size="small"
      /> 
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
import { computed, ref, unref } from 'vue';
import { FormOutlined, DeleteOutlined } from '@ant-design/icons-vue';
import axios from 'axios';

const rolname = ref("");


const roles = ref([])
const permisos = ref([]);

const visible = ref(false);
const showModalRol = () => {
  getPermisos()
  visible.value = true;
};
const handleOk = e => {
  console.log(e);
  visible.value = false;
};
const getPermisos = async () => {  
  let res = await axios.get(`get-permission`);
  permisos.value = res.data.permisos;
}

const getRoles = async () => {  
  let res = await axios.get(`get-roles`);
  roles.value = res.data.datos.data;
}

const abrirEditar = (item) => {
  visible.value = true;
  rolname.value = item.name;
}

const guardar = () => {
  let post = {
    name: rolname.value,
    permisos: rowSelection.value.selectedRowKeys,
  };
  axios.post("save-rol", post).then((result) => {
    console.log(result);
    getRoles();
    visible.value = false;
  });
}

const columnsRoles = [
  { title: 'Rol', dataIndex: 'name', sorter :true },
  { title: 'Tipo permisos', dataIndex: 'guard_name', },
  { title: 'Acciones', dataIndex: 'acciones',  }
];

const columns = [
  { title: 'Nombre', dataIndex: 'name', sorter :true },
  { title: 'Permisos', dataIndex: 'guard_name'},
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



getRoles()


</script>