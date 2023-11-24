<template>
<Head title="Filiales"/>
<AuthenticatedLayout>
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4" style="height: calc(100vh - 92px); border-radius: 10px;">

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
    <template #bodyCell="{ column, index, record }">

    <template v-if="column.dataIndex === 'codigo'">
        <div class="flex" style="justify-content: center;">
            <div class="px-2" style="background: #e3e3e3; border-radius: 4px;"><span style="font-weight: bold;">{{ record.codigo }}</span></div>
        </div>
    </template>

    <template v-if="column.dataIndex === 'estado'">
        <div class="flex" style="justify-content: center;">
            <div v-if="1 === record.estado">
                <a-tag color="green">Vigente</a-tag>
            </div>
            <div v-else>
                <a-tag color="red">No Vigente</a-tag>
            </div>
        </div>
    </template>
    <template v-if="column.dataIndex === 'acciones'">
        <a-button type="success" class="mr-1" style="color: #476175;" @click="cambiarSexo(record.id_postulante, record.sexo )" size="small">
            <template #icon><SaveOutlined/></template>
        </a-button>
        <a-button @click="abrirEditar(record)" class="mr-1" style="color: blue;" size="small">
            <template #icon><form-outlined/></template>
        </a-button>
        <a-popconfirm
            title="¿Estas seguro de eliminar?"
            disabled
            @confirm="eliminar(record)"
            >
            <a-button shape="" size="small" style="color: crimson;">
                <template #icon><delete-outlined/></template>
            </a-button>
        </a-popconfirm>

    </template>
    <!-- <template v-if="column.dataIndex === 'acciones'">
        <a-button type="primary" @click="abrirEditar(filiales[index])" size="small">
        <template #icon><form-outlined/></template>
        </a-button>
        <a-divider type="vertical" />
        <a-button type="danger" @click="eliminar(filiales[index])" shape="" size="small">
        <template #icon><delete-outlined/></template>
        </a-button>
    </template> -->
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
        ref="formFilial"
        name="filial"
        :model="filial" :rules="formRules"
        v-bind="layout"
        style="margin-bottom: -30px;"
        >
        <a-form-item 
            label="Codigo" 
            name="codigo"
            :rules="[{ required: true, message: 'Ingrese el codigo', trigger: 'change' },]"
            >
            <a-input style="border-radius:6px;" type="text" placeholder="Ingrese el codigo" v-model:value="filial.codigo" autocomplete="off" />
        </a-form-item>
        <a-form-item 
            label="Nombre" 
            :rules="[{ required: true, message: 'Ingrese el nombre', trigger: 'change' },]"
            name="nombre">
            <a-input style="border-radius:6px;" type="text" placeholder="Ingrese el nombre" v-model:value="filial.nombre" autocomplete="off" />
        </a-form-item>


        <a-form-item 
            label="lugar" 
            name="lugar"
            :rules="[{ required: true, message: 'Seleccione el lugar', trigger:'blur' }]"
            >
                <a-auto-complete
                    v-model:value="residencia"                
                    :options="residencias"
                    @select="onSelectResidencias"
                    style="border-radius:6px; overflow:hidden; border:none;"
                >
                    <a-input 
                        placeholder="Dep/prov/dist"
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

        <a-form-item 
            label="Dirección" 
            :rules="[{ required: true, message: 'Ingrese la dirección', trigger: 'change' },]"
            name="direccion">
            <a-input style="border-radius:6px;" type="text" placeholder="Ingrese el nombre" v-model:value="filial.direccion" autocomplete="off" />
        </a-form-item>

        <a-form-item 
            label="Vigente" 
            name="estado"
            
            >
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
import { watch, computed, ref, onMounted, reactive } from 'vue';
import { 
    FormOutlined, DeleteOutlined, SearchOutlined,
    DownOutlined, ExclamationCircleOutlined, PrinterOutlined, SaveOutlined, EyeOutlined
} from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';

import axios from 'axios';
const loading = ref(false);
const buscar = ref("");
const filiales= ref([]);
const visible = ref(false);
const formFilial = ref();
const filial = reactive({
    id:null,
    codigo:"",
    nombre:"",
    lugar:null,
    direccion:"",
    estado: null,
})

const residencia = ref(null)
const residencias = ref([])
const buscarResidencia = ref(null)
const redseleccionado = ref(null)

const onSelectResidencias = (value, option) => { 
    residencia.value = option.value; 
    filial.lugar = option.key  
};

const showModalFilial = () => {
    visible.value = true;
};

watch(buscar, ( newValue, oldValue ) => { getFiliales(); })
watch(buscarResidencia, ( newValue, oldValue ) => {  if(newValue.length >= 3){ getUbigeosResidencia() }})

const guardar = async () => {
    loading.value = true;
    try {
        const values = await formFilial.value.validateFields();
        axios.post("save-filial", filial).then((result) => {
            notificacion('success',result.data.titulo, result.data.mensaje);
            getFiliales();
            visible.value = false;
            resetForm()
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
    filial.lugar = item.ubigeo;
    filial.direccion = item.direccion;
    residencia.value = item.lugar;
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
        notificacion('error', 'PROCESO ELIMINADO', result.data.mensaje );
    });
}

const resetForm = () => {

    filial.id = null;
    filial.codigo = "";
    filial.nombre = "";
    filial.lugar = null;
    filial.direccion = "";
    filial.estado =  "";
    residencia.value = "";
    redseleccionado.value = ""
    cerramodal();

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
    { title: 'Codigo', dataIndex: 'codigo',},
    { title: 'Nombre', dataIndex: 'nombre',},
    { title: 'Lugar', dataIndex: 'lugar',},
    { title: 'Dirección', dataIndex: 'direccion',},
    { title: 'Vigente', dataIndex: 'estado', align:'center', width:'100px'},
    { title: 'Acciones', dataIndex: 'acciones'},
];

</script>
<style scoped>

</style>