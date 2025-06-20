<template>
    <Head title="Documentos"/>
    <Layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">    
    <row class="flex justify-between mb-4" >
        <div class="mr-3">
            <a-select
                ref="select"
                v-model:value="programa"
                placeholder="Seleccionar programa de estudios"
                :options="programasselect"
                allowClear>
            </a-select>
        </div>
        <div class="flex justify-between" style="position: relative;" >
            <a-input type="text" placeholder="Escribe para buscar..!!" v-model:value="buscar" style="max-width: 300px; padding-left: 10px;">
                <template #prefix> <search-outlined /></template>
            </a-input>
        </div>
    </row>

    <a-table 
        :columns="columnsInscripcion" 
        :data-source="inscripciones"
        :pagination="false"
        size="small"
        > 
        <template #bodyCell="{ column, index, record }">

            <template v-if="column.dataIndex === 'dni'" >
                <a-tag color="#476175" style="padding-top: 3px;">
                    <span style="font-size: 1rem; font-weight: bold;">{{ record.dni }}</span>
                </a-tag>
            </template>

            <template v-if="column.dataIndex === 'postulante'" >
                <span style="font-size: 0.95rem;">{{ record.paterno }} {{ record.materno }}, {{ record.nombres }}</span>
            </template>

            <template v-if="column.dataIndex === 'programa'" >
                {{ record.programa }}
            </template>

            <template v-if="column.dataIndex === 'sexo'" >
                <a-select
                ref="select"
                v-model:value="record.sexo"
                placeholder="Seleccionar"
                style="width: 60px;"
                >
                <a-select-option value='1'><span style="color:blue">M</span></a-select-option>
                <a-select-option value='2'><span style="color:red">F</span></a-select-option>
                </a-select>
                <!-- <a-tag v-if="record.sexo === '1'" color="blue">M</a-tag>
                <a-tag v-if="record.sexo === '2'" color="pink">F</a-tag> -->
            </template>

            <template v-if="column.dataIndex === 'estado'" >
                <a-tag v-if="record.estado === 0" color="#476175">INSCRITO</a-tag>
                <a-tag v-else color="#b01030">SIN INSCRIPCIÓN</a-tag>
            </template>

            <template v-if="column.dataIndex === 'acciones'">
                <a-button class="mr-1" type="success" style="color: #476175;" @click="cambiarSexo(record.id_postulante, record.sexo )" size="small">
                    <template #icon><SaveOutlined/></template>
                </a-button>
                <a-button class="mr-1" @click="abrirEditar(record)" style="color: blue;" size="small">
                    <template #icon><form-outlined/></template>
                </a-button>
                <a-button @click="eliminar(record)" size="small" style="color: crimson;">
                    <template #icon><delete-outlined/></template>
                </a-button>  
            </template>
        </template>
  
    </a-table> 
    <a-pagination v-model:current="pagina" :total="totalRegistros"  v-model:pageSize="pageSize" show-less-items />
    
    </div>
    
    </Layout>
    
    <div>
        <a-modal v-model:open="visible" title="Modificar Pre inscripción" style="margin-top: -40px;">
            <a-form
                ref="formRef"
                name="custom-validation"
                :model="formState"
                v-bind="layout"
                @finish="handleFinish"
                @validate="handleValidate"
                @finishFailed="handleFinishFailed"
                >
                <a-form-item has-feedback name="nombre">
                    <label>Postulante</label>
                    <a-input type="text" v-model:value="postulante.nombre"/>
                </a-form-item>
                <a-form-item has-feedback name="postulante">
                    <label>Programa</label>
                    <div class="">
                        <a-select
                            ref="select"
                            v-model:value="inscripcion.id_programa"
                            placeholder="Seleccionar programa"
                            class="selector-modalidad"
                            style="width: 100%;"
                            :options="programasselect"
                            >
                        </a-select>
                    </div>
                </a-form-item>
                <a-form-item has-feedback name="tipo">
                    <label>Modalidad</label>
                    <a-select
                            ref="select"
                            v-model:value="inscripcion.id_modalidad"
                            placeholder="Seleccionar programa"
                            class="selector-modalidad"
                            style="width: 100%;"
                            >
                            <a-select-option :value='9'>CEPREUNA</a-select-option>
                            <a-select-option :value='8'>EXAMEN GENERAL</a-select-option>
                            <a-select-option :value='7'>CONADIS</a-select-option>
                        </a-select>
                </a-form-item>
                <a-form-item has-feedback name="nombre">
                    <label>Observaciones</label>
                    <a-textarea type="text" v-model:value="inscripcion.observacion" autocomplete="off" />
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
import Layout from '@/Layouts/AuthenticatedLayout.vue'
import { watch, computed, ref, unref } from 'vue';
import { FormOutlined, PrinterOutlined, DeleteOutlined, SearchOutlined, SaveOutlined} from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';
const baseUrl = window.location.origin;


const programasselect = ref([]);
const programa = ref(null);
const buscar = ref("");
const inscripciones = ref([])
const visible = ref(false)
const pagina = ref(1)
const totalRegistros = ref(null)
const pageSize = ref(20)
const inscripcion = ref({ 
    id:null, 
    codigo:"", 
    id_posulante:"", 
    id_programa:"", 
    id_modalidad:"",
    estado: true, 
    observacion:"",
})
const postulante = ref({ id:"", nombre:"", dni:""})

const showModalPrograma = () => { visible.value = true; };

watch(buscar, ( newValue, oldValue ) => { getInscripciones() })
watch(pageSize, ( newValue, oldValue ) => { getInscripciones() })
watch(programa, ( newValue, oldValue ) => 
{ 
    if(newValue == 0) {
        programa.value = null;
    } 
    pagina.value = 1;
    getInscripciones() 
})

watch(visible, ( newValue, oldValue ) => {
    if(visible.value == false &&inscripcion.value.id != null ){
        inscripcion.value.id = null, 
        inscripcion.value.id_posulante = "", 
        inscripcion.value.id_programa = "", 
        inscripcion.value.id_modalidad = "",
        inscripcion.value.estado = true, 
        inscripcion.value.observacion = "",
        postulante.value.nombre = null,
        postulante.value.dni = null,
        postulante.value.id = null 
    }
})

watch(pagina, ( newValue, oldValue ) => { getInscripciones(); })

const abrirEditar = (item) => {
    visible.value = true;
    inscripcion.value.id = item.id;
    inscripcion.value.codigo = item.codigo;
    inscripcion.value.id_programa = item.id_programa;
    inscripcion.value.id_modalidad = item.id_modalidad;
    inscripcion.value.observacion = item.observaciones;
    postulante.value.id = item.id_postulante;
    postulante.value.dni = item.dni;
    postulante.value.nombre = item.dni+" - "+item.nombres +" "+ item.paterno +" "+ item.materno;
}


const getProgramasSelect =  async ( ) => {
    let res = await axios.post( "/segundas/select-programas-segundas?page="+pagina.value , { term: buscar.value, paginashoja: pageSize.value, programa: programa.value } );
    programasselect.value = res.data.datos;
}
getProgramasSelect();

const getInscripciones =  async ( ) => {
    let res = await axios.post( "/segundas/get-preinscripciones-segundas?page="+pagina.value , { term: buscar.value, paginashoja: pageSize.value, programa: programa.value } );
    inscripciones.value = res.data.datos.data;
    totalRegistros.value = res.data.datos.total;
}

const guardar = () => {
    let post = {
        id:inscripcion.value.id,
        id_postulante: postulante.value.id,
        id_programa: inscripcion.value.id_programa,
        id_modalidad: inscripcion.value.id_modalidad,
        observacion: inscripcion.value.observacion,
        dni: postulante.value.dni
    };
    axios.post("/segundas/actualizar-preinscripciones-segundas", post).then((result) => {
        getInscripciones()
        notificacion('success',result.data.titulo, result.data.mensaje);
        visible.value = false;
    });
}

const cambiarSexo = (postulante, sexo) => {
    let post = {
        id_postulante: postulante,
        sexo: sexo
    };
    axios.post("actualizar-sexo-postulante", post).then((result) => {
        getInscripciones()
        notificacion('success',result.data.titulo, result.data.mensaje);
        visible.value = false;
    });
}

const eliminar = (item) => {
    console.log("eliminar");
    axios.post("eliminar-preinscripcion/",{id: item.id}).then((result) => {
    getInscripciones();
    notificacion('warning', result.data.titulo, result.data.mensaje );
    });
}

const columnsInscripcion = [
    // { title: 'ID', dataIndex: 'id' },
    { title: 'DNI', dataIndex: 'dni', align:'center'},
    { title: 'Postulante', dataIndex: 'postulante'},
    { title: 'Sexo', dataIndex: 'sexo', align:'center' },
    { title: 'Programa', dataIndex:'programa'},
    { title: 'Modalidad', dataIndex:'modalidad', align:'center'},
    { title: 'Estado', dataIndex: 'estado', align:'center'},
    { title: 'Observación', dataIndex: 'observacion'},
    { title: 'Acciones', dataIndex: 'acciones', width:'140px', align:'center'},
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

const notificacion = (type, titulo, mensaje) => { notification[type]({ message: titulo, description: mensaje, }); };
getInscripciones()

</script>
