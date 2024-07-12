<template>
    <Head title="Ponderacion"/>
    <AuthenticatedLayout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4" style="border-radius: 10px; width: 100%; min-height: calc(100vh - 95px);">
    
    <div class="flex justify-between mb-2" >
        <div class="mr-3">
        <a-button type="primary" @click="showModalFilial" style="background: #476175; border: none; border-radius: 5px;">Nuevo</a-button>
        </div>
        <div class="flex justify-between" style="position: relative;" >
            <a-input type="text" placeholder="Buscar" v-model:value="buscar" style="max-width: 300px; border-radius:6px; padding-left: 30px;"/>
        <div class="mr-2" style="position: absolute; left: 8px; top: 3px; "><search-outlined /></div>
        </div>
    </div>

    <a-table 
        :columns="columnsFiliales" 
        :data-source="ponderaciones"
        :key="id"
        size="small"
        :pagination="false"
        style="scale: .7rem;"
        > 
        <template #bodyCell="{ column, index, record }">
    
            <template v-if="column.dataIndex === 'nro'">
                <div class="flex" style="justify-content: center;">
                    <span style="font-weight: bold;">{{ index + 1 }}</span>
                </div>
            </template>
    
            <template v-if="column.dataIndex === 'acciones'">
                <a-button type="success" class="mr-1" style="color: #476175;" @click="abrirDetallePonderacion(record.id)" size="small">
                    <template #icon><EyeOutlined/></template>
                </a-button>
                <a-button @click="abrirEditar(record)" class="mr-1" style="color: blue;" size="small">
                    <template #icon><form-outlined/></template>
                </a-button>
                <a-popconfirm
                    title="¿Estas seguro de eliminar?"
                    @confirm="eliminar(record)"
                    >
                    <a-button shape="" size="small" style="color: crimson;">
                        <template #icon><delete-outlined/></template>
                    </a-button>
                </a-popconfirm>
    
            </template> 
        </template>
    </a-table> 
    
    <div class="flex justify-between mt-2 pr-4" style="margin-bottom: -5px;">
            <div>
                <a-pagination v-model:current="pagina" simple :total="totalRegistros"  v-model:pageSize="paginasize" show-less-items />
            </div>
            <div clas="" style="scale: 0.9; margin-right: -20px;"> 
                <a-select
                    v-model:value="paginasize"
                    style="width: 90px;">
                    <a-select-option :value="10">10 Reg.</a-select-option>
                    <a-select-option :value="20">20 Reg.</a-select-option>    
                    <a-select-option :value="50">50 Reg.</a-select-option>    
                    <a-select-option :value="100">100 Reg.</a-select-option>    
                </a-select>
            </div>
        </div>
    
    </div>
    
    
    </AuthenticatedLayout>
    
    <div>
        <a-modal v-model:visible="visible" style="margin-top: -40px; margin-bottom: -40px;" :closable="false">
            <div style="background: #476175; height: 36px; margin-left:-24px; margin-right:-24px; margin-top:-24px; margin-bottom: 28px;">
                <div class="flex justify-between ml-3 mr-1" style="height:36px; align-items: center;">
                    <div><span style="font-weight: bold; color:white; font-size:1rem;"> Ponderacion </span></div>
                    <div><span ><a-button @click="cerramodal()" style="background:none; border:none; color:white;">X</a-button></span></div>
                </div>
            </div>
    
            <a-form
                ref="formPonderacion"
                name="form"
                :model="ponderacion"
                v-bind="layout"
                style="margin-bottom: -30px;"
                >
                <a-form-item 
                    label="Nombre" 
                    :rules="[{ required: true, message: 'Ingrese el nombre', trigger: 'change' },]"
                    name="nombre">
                    <a-input style="border-radius:6px;" type="text" placeholder="Ingrese el nombre" v-model:value="ponderacion.nombre" autocomplete="off" />
                </a-form-item>

                <!-- <a-form-item label="Simulacro" name="simulacro">
                    <a-auto-complete
                        v-model:value="ponderacion.simulacro"                
                        :options="simulacros"
                        @select="onSelect"
                        >
                        <a-input 
                            placeholder="Buscar ..."
                            v-model:value="buscarSimulacro"
                        >
                        </a-input>
                    </a-auto-complete>
                </a-form-item> -->

                <a-form-item name="area" label="Area" :rules="[{ required: true, message: 'Seleccione un area', trigger: 'change' },]">
                    <a-select ref="select" v-model:value="ponderacion.area" style="width: 100%">
                        <a-select-option :value="1">BIOMEDICAS</a-select-option>
                        <a-select-option :value="2">INGENIERIAS</a-select-option>
                        <a-select-option :value="3">SOCIALES</a-select-option>
                    </a-select>
                </a-form-item>
        
            </a-form>
    
        <template #footer>
            <a-button style="margin-left: 6px; border-radius: 4px;" @click="resetForm">Cancelar</a-button>
            <a-button type="primary" style="background: #476175; border:none; border-radius: 4px;" @click="guardar()">Guardar</a-button>
        </template>
        </a-modal>
    </div>



    <div>
        <a-modal v-model:visible="modalDetallePonderacion" style="margin-top: -40px; margin-bottom: -40px; width:900px;"  :closable="false">
            <div style="background: #476175; height: 36px; margin-left:-24px; margin-right:-24px; margin-top:-24px; margin-bottom: 28px;">
                <div class="flex justify-between ml-3 mr-1" style="height:36px; align-items: center;">
                    <div><span style="font-weight: bold; color:white; font-size:1rem;"> Detalle Ponderacion </span></div>
                    <div><span ><a-button @click="cerramodal()" style="background:none; border:none; color:white;">X</a-button></span></div>
                </div>
            </div>
    
            <div style="width:100%;">
            <a-form
                ref="formPesos"
                name="formpesos"
                :model="pesos"
                style="margin-bottom: -30px;"
    
                >
                    <div v-for="(i,index) in nroItems">
                        <a-row :gutter="[16, 10]">
                            <a-col :xs="24" :sm="12" :md="8" :lg="1">
                                <div class="mr-2">
                                    <label><br></label>
                                    <div class="flex justify-center">
                                        <a-button>{{ index + 1 }}</a-button>
                                    </div>
                                </div>
                            </a-col>
                            <a-col :xs="24" :sm="12" :md="8" :lg="15">
                                <div class="mr-2">
                                    <label>Asignatura</label>
                                    <a-form-item name="nombre" >
                                        <a-input v-model:value="pesos[index].nombre"/>
                                    </a-form-item>
                                </div>
                            </a-col>
                            <a-col :xs="24" :sm="12" :md="8" :lg="4">
                                <div>
                                    <label>N° preguntas</label>
                                    <a-form-item name="npreguntas">
                                        <a-input v-model:value="pesos[index].n_preguntas"/>
                                    </a-form-item>
                                </div>
                            </a-col>
                            <a-col :xs="24" :sm="12" :md="8" :lg="4">
                                <div class="mr-2">
                                    <label>Ponderacion</label>
                                    <a-form-item name="ponderacion">
                                        <a-input v-model:value="pesos[index].ponderacion"/>
                                    </a-form-item>
                                </div>
                            </a-col>
                            
                        </a-row>
                    </div>


                <div class="flex justify-end">
                    <a-button @click="agregarFila()">1 más</a-button>
                </div>
        
            </a-form>
            </div>
        <template #footer>
            <a-button style="margin-left: 6px; border-radius: 4px;" @click="resetForm">Cancelar</a-button>
            <a-button type="primary" style="background: #476175; border:none; border-radius: 4px;" @click="saveDetalle()">Guardar</a-button>
        </template>
        </a-modal>
    </div>
    
    </template>
        
    <script setup>
    import { Head } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/LayoutCalificador.vue'
    import { watch, computed, ref, onMounted, reactive } from 'vue';
    import { FormOutlined, DeleteOutlined, SearchOutlined, DownOutlined, EyeOutlined } from '@ant-design/icons-vue';
    import { notification } from 'ant-design-vue';
    
    import axios from 'axios';
    const loading = ref(false);
    const buscar = ref("");
    const filiales= ref([]);
    const visible = ref(false);
    const formFilial = ref();
    
    const buscarResidencia = ref("")

    const pagina = ref(1);
    const paginasize = ref(10);
    const totalRegistros = ref(1);
    

    
    const showModalFilial = () => { visible.value = true; };
    let timeoutId;
    watch(buscar, ( newValue, oldValue ) => { 
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            getFiliales(); 
        }, 500);    
    })
    watch(pagina, ( newValue, oldValue ) => { getFiliales(); })
    watch(paginasize, ( newValue, oldValue ) => { getFiliales(); })
    
    let timeout2;
    watch(buscarResidencia, ( newValue, oldValue ) => {  
        clearTimeout(timeout2);
        timeout2 = setTimeout(() => {
            getUbigeosResidencia() 
        }, 500); 
    })
    
    
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
    



const residencias = ref([])

//SIMULACROS
const ponderaciones = ref([])

const getPonderaciones =  async () => {
    let res = await axios.post("get-ponderaciones?page=" + pagina.value, { term: buscar.value, paginasize: paginasize.value } );
    ponderaciones.value = res.data.datos.data;
    totalRegistros.value = res.data.datos.total;
}


const formPonderacion = ref()
const ponderacion = reactive({ id:null, nombre:"", area:null, simulacro:"" })
const buscarSimulacro = ref("")
const simulacro = ref(null)
const simulacros = ref([])

const area = ref(1)

const modalDetallePonderacion = ref(false)

const getSimulacros = async () => {
    axios.post("/get-simulacros",{"term": buscarSimulacro.value})
    .then((response) => {
        simulacros.value = response.data.datos.data;
    })
    .catch((error) => {
        if (error.response) {
            console.error('Error de servidor:', error.response.data);
        } else if (error.request) {
            console.error('Error de red:', error.request);
            } else { console.error('Error de configuración:', error.message); }
    });
}

const onSelect = (value, option) => { simulacro.value = option; };
let timeoutIde;
watch(buscarSimulacro, ( newValue, oldValue ) => { 
    clearTimeout(timeoutIde);
    timeoutIde = setTimeout(() => {
        getSimulacros();
    }, 500);    
})


const guardar = async () => {
    loading.value = true;
    try {
        const values = await formPonderacion.value.validateFields();
        axios.post("save-ponderacion", ponderacion).then((result) => {
            notificacion('success',result.data.titulo, result.data.mensaje);
            //resetForm()
            getPonderaciones()
            visible.value = false;
        });
        
    } catch (error) {
        console.error(error);
    }

}


const saveDetalle = async () => {
    loading.value = true;
    try {
        // const values = await formPonderacion.value.validateFields();
        axios.post("save-ponderacion-detalle", {"pesos":pesos.value, "id_ponderacion": id_ponderacion.value }).then((result) => {
            notificacion('success',result.data.titulo, result.data.mensaje);
            //resetForm()
            getPonderaciones()
            visible.value = false;
        });
        
    } catch (error) {
        console.error(error);
    }

}

getSimulacros()
getPonderaciones()

const id_ponderacion = ref(null)
const nroItems = ref(1);
const pesos = ref([
    {"nombre":null, "ponderacion":null, "n_preguntas":null}
]);
const abrirDetallePonderacion = (id) => {
    modalDetallePonderacion.value = true;
    id_ponderacion.value = id;
}

const agregarFila = () => { 
    pesos.value.push({"nombre":null, "ponderacion":null, "n_preguntas":null});
    nroItems.value += 1; 
}


    
const layout = { labelCol: { span: 4, }, wrapperCol: { span: 20, } };
const columnsFiliales = [
    { title: 'N°', dataIndex: 'nro',},
    { title: 'Nombre', dataIndex: 'nombre',},
    { title: 'Area', dataIndex: 'area',},
    { title: 'Acciones', dataIndex: 'acciones', align:'center', width:'96px'},
];

</script>
<style scoped>

</style>