<template>
<Head title="Procesos"/>
<AuthenticatedLayout>
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">

<!-- {{ buscar }} -->
<row class="flex justify-between mb-4" >
    <div class="mr-3">
    <a-button type="primary" @click="showModalProceso">Nuevo</a-button>
    </div>
    <div class="flex justify-between" style="position: relative;" >
    <a-input type="text" placeholder="Buscar" v-model:value="buscar" style="max-width: 300px; padding-left: 30px;"/>
    <div class="mr-2" style="position: absolute; left: 8px; top: 3px; "><search-outlined /></div>
    </div>
</row>
<!-- <pre>{{ filiales}}</pre> -->
<!-- {{ filial.provincia }} -->
<a-table 
    :columns="columnsFiliales" 
    :data-source="filiales"
    :pagination="{ pageSize: 10}"
    :key="id"
    size="small"
    > 
    <template #bodyCell="{ column, index }">

    <template v-if="column.dataIndex === 'estado'">
        <div class="flex" style="justify-content: center;">
            <div v-if="1 == filiales[index].estado">
                <a-tag color="green">Vigente</a-tag>
            </div>
            <div v-if="filiales[index].estado == 0">
                <a-tag color="red">No Vigente</a-tag>
            </div>
        </div>
    </template>
    <template v-if="column.dataIndex === 'acciones'">
        <a-button type="primary" @click="abrirEditar(filiales[index])" size="small">
        <template #icon><form-outlined/></template>
        </a-button>
        <a-divider type="vertical" />
        <a-button type="danger" @click="eliminar(filiales[index])" shape="" size="small">
        <template #icon><delete-outlined/></template>
        </a-button>
    </template>
    </template>
</a-table> 

</div>

</AuthenticatedLayout>

<div>
    <a-modal v-model:visible="visible" title="Nueva Filial" style="margin-top: -40px;">
        <!-- <pre>{{ filial }}</pre> -->
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
            <a-input type="text" v-model:value="filial.codigo" autocomplete="off" />
        </a-form-item>
        <a-form-item has-feedback label="Nombre" name="nombre">
            <a-input type="text" v-model:value="filial.nombre" autocomplete="off" />
        </a-form-item>

        <a-form-item has-feedback label="Departamento" name="departamento">

            <a-auto-complete
                v-model:value="dep"                
                :options="departamentos"
                style="width: 300px"
                @select="onSelect"
            >
                <a-input
                    placeholder="Buscar"
                    v-model:value="buscarDep"
                    @keypress="handleKeyPress"
                />
            </a-auto-complete>
        </a-form-item>

        <a-form-item has-feedback label="Provincia" name="provincia">
                      
            <a-select
                :options="provincias"
                ref="Tipo"
                style="width: 100%"
                @focus="focus"
                @change="handleChange"
                v-model:value="filial.provincia"
                >
            </a-select>
        </a-form-item>
        <a-form-item has-feedback label="Vigente" name="estado">
            <a-switch v-model:checked="filial.estado"/>
        </a-form-item>

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
const dep = ref("PUNO")
const filiales= ref([]);
const visible = ref(false);
const departamentos = ref([]);
const provincias = ref([]);
const buscarDep = ref("")
const filial = ref({
    id:null,
    codigo:"",
    nombre:"",
    departamento:1,
    provincia:null,
    estado: true,
})

const showModalProceso = () => {
    visible.value = true;
};

watch(buscar, ( newValue, oldValue ) => {
    getFiliales()
})


watch(buscarDep, ( newValue, oldValue ) => {
    getDepartamentos()
})

watch(visible, ( newValue, oldValue ) => {
if(visible.value == false && filial.value.id != null ){
    filial.value.id = null;
    filial.value.codigo = null;
    filial.value.nombre = null;
    filial.value.estado = true;
}
})

const onSelect = (value, option) => {
    filial.value.departamento = option.key; 
    getProvinciasXdepartamento()
};


const layout = {
    labelCol: {
    span: 6,
    },
    wrapperCol: {
    span: 12,
    },
};

let validateNombre = async (_rule, value) => {
if (value === '') {
    return Promise.reject('Ingrese su el nombre del filial');
} else {
    return Promise.resolve();
}
};

let validateCodigo = async (_rule, value) => {
if (value === '') {
    return Promise.reject('Ingrese la sede del filial');
} else {
    return Promise.resolve();
}
};

let validateDepartamento = async (_rule, value) => {
if (value === '') {
    return Promise.reject('Seleccione un departamento');
} else {
    return Promise.resolve();
}
};

let validateProvincia = async (_rule, value) => {
if (value === '') {
    return Promise.reject('Seleccione una provincia');
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
codigo: [{
    required: true,
    validator: validateCodigo,
    trigger: 'change',
}],

departamento: [{
    required: true,
    validator: validateDepartamento,
    trigger: 'change',
}],
provincia: [{
    required: true,
    validator: validateProvincia,
    trigger: 'change',
}],

};

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
    filial.value.id = item.id;
    filial.value.codigo = item.codigo;
    filial.value.nombre = item.nombre;
    filial.value.departamento = item.id_dep;
    getProvinciasXdepartamento2();
    dep.value = item.departamento;
    filial.value.provincia = item.id_prov;

    if(item.estado == 1){ filial.value.estado = true }
    else { filial.value.estado = false}
}

const getFiliales =  async (term = "", page = 1) => {
    let res = await axios.post(
    "filiales/get-filiales?page=" + page,
    { term: buscar.value }
    );
    filiales.value = res.data.datos.data;

}

const getDepartamentos =  async (term = "", page = 1) => {
    let res = await axios.post(
    "get-departamentos?page=" + page,
    { term: buscarDep.value }
    );
    departamentos.value = res.data.datos.data;
    filial.value.departamento = departamentos.value[0].key;
}

const getProvinciasXdepartamento =  async (page = 1) => {
    let res = await axios.get(
    "get-provincia-x-departamento/"+ filial.value.departamento,
    );
    provincias.value = res.data.datos;
    filial.value.provincia = res.data.datos[0].value;
}

const getProvinciasXdepartamento2 =  async (page = 1) => {
    let res = await axios.get(
    "get-provincia-x-departamento/"+ filial.value.departamento,
    );
    provincias.value = res.data.datos;
}


const guardar = () => {
    let post = {
    id: filial.value.id,
    codigo: filial.value.codigo,
    nombre: filial.value.nombre,
    id_dep: filial.value.departamento,
    id_prov: filial.value.provincia,
    estado: filial.value.estado,
    };
    axios.post("save-filial", post).then((result) => {
    notificacion('success',result.data.titulo, result.data.mensaje);
    getFiliales();
    visible.value = false;
    filial.value.id = null
    filial.value.codigo = ""
    filial.value.nombre = ""
    filial.value.departamento = 1
    filial.estado = true
    });
}

const eliminar = (item) => {
    axios.get("eliminar-filial/"+item.id).then((result) => {
    getFiliales();
    notificacion('warning', 'PROCESO ELIMINADO', result.data.mensaje );
    });
}

const columnsFiliales = [
    { title: 'Codigo', dataIndex: 'codigo', sorter :true },
    { title: 'Nombre', dataIndex: 'nombre', sorter :true },
    { title: 'Departamento', dataIndex: 'departamento', sorter :true },
    { title: 'Provincia', dataIndex: 'provincia'},
    { title: 'Vigente', dataIndex: 'estado'},
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



getFiliales()
getDepartamentos()
getProvinciasXdepartamento()

</script>