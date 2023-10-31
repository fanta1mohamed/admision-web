<template>
<Head title="Pagos"/>    
<Layout>
<div class="mb-4" style="width:100%;">

<div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: rgba(0, 0, 0, 0.1) 0px 20px 25px -5px, rgba(0, 0, 0, 0.03) 0px 10px 10px -5px;">
    <div class="flex justify-end mt-5 mb-4 mr-4">
        <div class="flex justify-between" style="position: relative;" >
            <a-input type="text" placeholder="Buscar" v-model:value="buscar" style="max-width: 340px; padding-left: 30px; border-radius: 6px;"/>
            <div class="mr-2" style="position: absolute; left: 8px; top: 3px; "><search-outlined /></div>
        </div>
    </div>

    <div>
        <a-table :dataSource="inscritos" size="" :columns="columns" :pagination="false">
                <template #bodyCell="{ column, index, record }">
                <template v-if="column.dataIndex === 'nro_doc'">                    
                    <a-tag color="#4f4f4f" style="width:80px;">{{  record.nro_doc }}</a-tag>
                </template>
                <template v-if="column.dataIndex === 'nombre'">
                    <div>
                        <span>{{ record.nombre }}</span>
                    </div>
                    <div>
                        <span style="color: gray;">{{ record.email }}</span>
                    </div>
                </template>
                <template v-if="column.dataIndex === 'acciones'">
                    <a-button type="success" class="mr-1" style="color: #476175;" size="small" disabled>
                        <template #icon><EyeOutlined/></template>
                    </a-button>
                    <a-button class="mr-1" @click="abrirEditar(record)" style="color: blue;" size="small" disabled>
                        <template #icon><form-outlined/></template>
                    </a-button>
                    <a-popconfirm
                        title="¿Estas seguro de eliminar?"
                        @confirm="eliminar(record)"
                        disabled
                        >
                        <a-button  size="small" style="color: crimson;" disabled>
                            <template #icon><delete-outlined disabled/></template>
                            </a-button>
                    </a-popconfirm>
    
                </template>    
                <template v-if="column.dataIndex === 'estado'">                    
                    <a-tag v-if="record.estado === 1" color="purple" style="width:80px;"> inscrito </a-tag>
                    <a-tag v-else color="red" style="width:80px;">No inscrito </a-tag>
                </template>
            </template>
        </a-table>
    </div>
    <div class="flex justify-between mb-6 mt-2 pr-4">
        <div>
            <a-pagination v-model:current="pagina" :page-size="pageSize" simple :total="totalRegistros" show-less-items />
        </div>
        <div clas="" style="scale: 0.9;"> 
            <a-select
                v-model:value="pageSize"
                style="width: 90px;">
                <a-select-option :value="10">10 Reg.</a-select-option>
                <a-select-option :value="20">20 Reg.</a-select-option>    
                <a-select-option :value="50">50 Reg.</a-select-option>    
                <a-select-option :value="100">100 Reg.</a-select-option>    
            </a-select>
        </div>
    </div>
</div>
</div>
</Layout>
</template>
    
<script setup>
import { Head } from '@inertiajs/vue3';
import Layout from '@/Layouts/LayoutSimulacro.vue'
import { watch, computed, ref, reactive } from 'vue';
import { TeamOutlined, FormOutlined, DownOutlined, PrinterOutlined, DeleteOutlined, SearchOutlined, EyeOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';
    
const colegios = ref([])
const buscar = ref("")
const pagina = ref(1)
const totalRegistros = ref(null)
const pageSize = ref(10)
const modal = ref(false)

const residencia = ref(null)
const buscarResidencia = ref(null)
const residencias = ref([])
const gestion = ref(null)
const loading = ref(false)

const departamentos = ref();
const buscarDep = ref();
const dep = ref()
const provincias = ref();
const buscarProv = ref();
const prov = ref()
const distritos = ref();
const buscarDist = ref();
const dist = ref()

const codInput = (event) => { form.cod_modular = event.target.value.replace(/\D/g, ''); };
const form = reactive({  
    cod_modular: '', 
    nombre:'',
    cod_local:'',
    nivel:'', 
    gestion: null, 
    ubigeo:'',
    direccion:'',
    observacion:''
});
const formDatos = ref(null)

const save = async () => {
    loading.value = true;
    setTimeout(() => {
        console.log("Este código se ejecutará después de 2 segundos");
    }, 1500);
    try {        
        const values = await formDatos.value.validateFields();
        const response = await axios.post('save', form);
        if (response.status === 202) {
            console.log(response.data.errors);
                loading.value = false;
        } else {
            resetForm();
            modal.value = false;
            notificacion('success', response.data.titulo, response.data.mensaje);
            loading.value = false;
            // getColegios();
        }
    } catch (error) {
        console.error(error);
        loading.value = false;
    }

}

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

const inscritos = ref([]);
const getInscritos = async () => { 
    let res = await axios.post( "get-pagos-simulacro",{"term": buscar.value, paginashoja: pageSize.value});
    inscritos.value = res.data.datos.data
    totalRegistros.value = res.data.datos.total;
}
getInscritos()

const getDepartamentos = async () => { 
    let res = await axios.post( "/get-departamentos-codigo", { term: buscarDep.value});
    departamentos.value = res.data.datos.data
}

const getProvincias = async () => {
    let res = await axios.post( "/get-provincias-codigo?page=", {departamento: dep.value });
    provincias.value = res.data.datos
}

const getDistritos = async (depp) => {
    let res = await axios.post( "/get-distritos-codigo?page=", { departamento: dep.value, provincia: prov.value });
    distritos.value = res.data.datos
}

watch(pagina, ( newValue, oldValue ) => { getInscritos(); })
watch(pageSize, ( newValue, oldValue ) => { getInscritos(); })
watch(buscar, ( newValue, oldValue ) => { getInscritos(); })
watch(buscarResidencia, ( newValue, oldValue ) => {  if(newValue.length >= 3){ getUbigeosResidencia() }})
watch(buscarDep, ( newValue, oldValue ) => {  if(newValue.length >= 3){ getDepartamentos() }})
watch(buscarProv, ( newValue, oldValue ) => {  if(newValue.length >= 3){ getProvincias() }})
watch(buscarDist, ( newValue, oldValue ) => {  if(newValue.length >= 3){ getDistritos() }})
watch(gestion, ( newValue, oldValue ) => {  if(newValue.length >= 3){ getColegios() }})


const onSelectResidencias = (value, option) => { form.ubigeo = option.key; };
const onSelectDepartamentos = (value, option) => { dep.value = option.key; getColegios(); getProvincias(); };
const onSelectProvincias = (value, option) => { prov.value = option.key; getColegios(); getDistritos(); };
const onSelectDistritos = (value, option) => { dist.value = option.key; getColegios() };

getDepartamentos()
getUbigeosResidencia()

const abrirEditar = (item) => {
    modal.value = true;
    form.id = item.id
    form.cod_modular = item.cod_modular
    form.nombre = item.nombre
    form.cod_local = item.cod_local
    form.gestion = item.gestion
    form.nivel = item.nivel
    form.ubigeo = item.ubigeo
    residencia.value = item.lugar
    form.direccion = item.direccion
    form.observacion = item.observacion    
}

const resetForm = () => {
    for (const key in form) {
    form[key] = null;
    }
};

const notificacion = (type, titulo, mensaje) => {
    notification[type]({
    message: titulo,
    description: mensaje,
    });
};

const columns= ref([
    {
        title: 'Nro_Doc',
        dataIndex: 'nro_doc',
    },
    {
        title: 'Nombres',
        dataIndex: 'nombre',
        responsive: ['xs','sm','md','lg'],
    },
    {
        title: 'Concepto',
        dataIndex: 'concepto',
        responsive: ['md'],
    },
    {
        title: 'F. Confirmacion',
        dataIndex: 'fec_confirmacion',
        responsive: ['lg'],
    },
    {
        title: 'autorizacion',
        dataIndex: 'cod_autorizacion',
        responsive: ['lg'],
    },
    {
        title:'Total',
        dataIndex: 'total',
        width:'120px',
        align:'center'
    },
    {
        title:'Comision',
        dataIndex: 'comision',
        width:'120px',
        align:'center'
    },
    {
        title:'Tipo',
        dataIndex: 'type',
        width:'120px',
        align:'center'
    },
    {
        title:'Estado',
        dataIndex: 'estado',
        width:'120px',
        align:'center'
    }
],
);


</script>