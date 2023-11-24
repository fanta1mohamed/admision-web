<template>
<Head title="Filiales"/>
<AuthenticatedLayout>
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4" style="height: calc(100vh - 92px); border-radius: 10px;">

<!-- {{ buscar }} -->
<row class="flex justify-between mb-4" >
    <div class="mr-3">
    <a-button type="primary" @click="showModalFilial" style="background: #476175; border: none; border-radius: 5px;">Nuevo</a-button>
    </div>
    <div class="flex justify-between" style="position: relative;" >
        <a-input type="text" placeholder="Buscar" v-model:value="buscar" style="max-width: 300px; border-radius:6px; padding-left: 30px;"/>
    <div class="mr-2" style="position: absolute; left: 8px; top: 3px; "><search-outlined /></div>
    </div>
</row>
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
    <a-modal v-model:visible="visible" style="margin-top: -40px; margin-bottom: -40px;" :closable="false">
        <div style="background: #476175; height: 36px; margin-left:-24px; margin-right:-24px; margin-top:-24px; margin-bottom: 28px;">
            <div class="flex justify-between ml-3 mr-1" style="height:36px; align-items: center;">
                <div><span style="font-weight: bold; color:white; font-size:1rem;">Nueva filial</span></div>
                <div><span ><a-button @click="cerramodal()" style="background:none; border:none; color:white;">X</a-button></span></div>
            </div>
        </div>
        <a-form
        ref="forFilial"
        name="custom-validation"
        :model="formState"
        :rules="formRules"
        v-bind="layout"
        style="margin-bottom: -30px;"
        >
        <a-form-item 
            has-feedback 
            label="Codigo" 
            name="codigo"
            :rules="[{ required: true, message: 'Ingrese el codigo', trigger: 'change' },]"
            >
            <a-input style="border-radius:6px;" type="text" v-model:value="filial.codigo" autocomplete="off" />
        </a-form-item>
        <a-form-item has-feedback label="Nombre" name="nombre">
            <a-input style="border-radius:6px;" type="text" v-model:value="filial.nombre" autocomplete="off" />
        </a-form-item>

        <!-- <a-form-item has-feedback label="Departamento" name="departamento">

            <a-auto-complete
                v-model:value="dep"                
                :options="departamentos"
                style="width: 100%"
                @select="onSelect"
            >
                <a-input
                    style="border-radius:6px;"
                    placeholder="Buscar"
                    v-model:value="buscarDep"
                    @keypress="handleKeyPress"
                />
            </a-auto-complete>
        </a-form-item> -->

        <a-form-item label="Lugar" name="lugar">
                <a-auto-complete
                    v-model:value="residencia"                
                    :options="residencias"
                    @select="onSelectResidencias"
                    style="border-radius:6px; overflow:hidden; border:none;"
                >
                    <a-input 
                        placeholder="Departamento"
                        v-model:value="buscarResidencia"
                        style="border-radius:6px; "
                    >
                        <template #suffix>
                            <a-tooltip title="Extra information">
                            <down-outlined/>
                            </a-tooltip>
                        </template>
                    </a-input>
                </a-auto-complete>
        </a-form-item>

        <!-- <a-form-item has-feedback label="Provincia" name="provincia">
                      
            <a-select
                :options="provincias"
                ref="Tipo"
                style="width: 100%; border-radius:6px;"
                @focus="focus"
                @change="handleChange"
                v-model:value="filial.provincia"
                >
            </a-select>
        </a-form-item> -->
        <a-form-item has-feedback label="Vigente" name="estado">
            <a-switch v-model:checked="filial.estado"/>
        </a-form-item>

        </a-form>

    <template #footer>
        <a-button style="margin-left: 6px; border-radius: 4px;" @click="resetForm">Cancelar</a-button>
        <a-button type="primary" style="background: #476175; border:none; border-radius: 4px;" @click="guardar()">Guardar</a-button>
    </template>
    </a-modal>
</div>

</template>
    
<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { watch, computed, ref, unref, reactive } from 'vue';
import { 
    FormOutlined, DeleteOutlined, SearchOutlined,
    DownOutlined, ExclamationCircleOutlined, PrinterOutlined, SaveOutlined, EyeOutlined
} from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';

import axios from 'axios';
const loading = ref(false);
const buscar = ref("");
const dep = ref("PUNO")
const filiales= ref([]);
const visible = ref(false);
const departamentos = ref([]);
const provincias = ref([]);
const buscarDep = ref("")
const formFilial = ref();
const filial = reactive({
    id:null,
    codigo:"",
    nombre:"",
    lugar:"",
    estado: null,
})

const residencia = ref(null)
const residencias = ref([])
const buscarResidencia = ref(null)
const redseleccionado = ref(null)

const onSelectResidencias = (value, option) => { redseleccionado.value = option.key; };

const showModalFilial = () => {
    visible.value = true;
};

watch(buscar, ( newValue, oldValue ) => { getFiliales(); })
watch(buscarResidencia, ( newValue, oldValue ) => {  if(newValue.length >= 3){ getUbigeosResidencia() }})

const guardar = async () => {
    loading.value = true;
    try {
        const values = await formFilial.value.validateFields();

        axios.post("save-filial", filiar).then((result) => {
        notificacion('success',result.data.titulo, result.data.mensaje);
        getFiliales();
        visible.value = false;
        });
        
    } catch (error) {
        console.error(error);
    }

}

const layout = { labelCol: { span: 6, }, wrapperCol: { span: 18, } };

const getUbigeosResidencia = async () => {

    axios.post("/get-ubigeo",{"term": buscarResidencia.value})
    .then((response) => {
        residencias.value = response.data.datos.data;
        console.log('Datos recibidos:', residencias);
    })
    .catch((error) => {
        if (error.response) {
            console.error('Error de servidor:', error.response.data);
        } else if (error.request) {
            console.error('Error de red:', error.request);
            } else { console.error('Error de configuración:', error.message); }
  });
}

const abrirEditar = (item) => {

    visible.value = true;
    filial.id = item.id;
    filial.codigo = item.codigo;
    filial.nombre = item.nombre;
    filial.departamento = item.id_dep;
    filial.provincia = item.id_prov;

    if(item.estado == 1){ filial.estado = true }
    else { filial.estado = false}
}

const getFiliales =  async (term = "", page = 1) => {
    let res = await axios.post(
    "filiales/get-filiales?page=" + page,
    { term: buscar.value }
    );
    filiales.value = res.data.datos.data;
}

const eliminar = (item) => {
    axios.get("eliminar-filial/"+item.id).then((result) => {
        getFiliales();
        notificacion('warning', 'PROCESO ELIMINADO', result.data.mensaje );
    });
}



const notificacion = (type, titulo, mensaje) => {
    notification[type]({
    message: titulo,
    description: mensaje,
    });
};

const cerramodal = () => { visible.value = false; }

getFiliales()
getUbigeosResidencia()


const columnsFiliales = [
    { title: 'Codigo', dataIndex: 'codigo', sorter :true },
    { title: 'Nombre', dataIndex: 'nombre', sorter :true },
    { title: 'Departamento', dataIndex: 'departamento', sorter :true },
    { title: 'Provincia', dataIndex: 'provincia'},
    { title: 'Vigente', dataIndex: 'estado', align:'center', width:'100px'},
    { title: 'Acciones', dataIndex: 'acciones'},
];

</script>
<style scoped>

</style>