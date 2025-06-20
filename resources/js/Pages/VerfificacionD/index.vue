<template>
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4" style="border-radius: 10px; min-height: calc(100vh - 92px);">    
    <div class="flex justify-between mb-2" >
        <div class="mr-3"></div>
        <div class="flex justify-between" style="position: relative;" >
            <a-input type="text" placeholder="Buscar" v-model:value="buscar" style="max-width: 300px; border-radius:6px; padding-left: 30px;"/>
            <div class="mr-2" style="position: absolute; left: 8px; top: 3px; "><search-outlined /></div>
        </div>
    </div>
    <a-table 
        :columns="columnsFotos" 
        :data-source="fotoses"
        :key="id"
        size="small"
        :pagination="false"
        style="scale: .7rem;"
        > 
        <template #bodyCell="{ column, record }">
            <template v-if="column.dataIndex === 'nombres'">
                <div class="flex" style="justify-content: left;">
                    <div class="px-2"><span style="font-size: 0.8rem;">{{ record.primer_apellido }} {{ record.segundo_apellido }} {{ record.nombres }}</span></div>
                </div>
            </template>


            <template v-if="column.dataIndex === 'gen_juli_2024_II'">
                <div style="width: 150px;">
                    <img :src="'https://inscripciones.admision.unap.edu.pe/documentos/11/inscripciones/fotos/'+record.dni+'.jpg'" alt="">
                </div>
            </template>

            <template v-if="column.dataIndex === 'gen_2024_II'">
                <div style="width: 150px;">
                    <img :src="'https://inscripciones.admision.unap.edu.pe/documentos/9/inscripciones/fotos/'+record.dni+'.jpg'" alt="">
                </div>
            </template>
            <template v-if="column.dataIndex === 'cep_2024_II'">
                <div style="width: 150px;">
                    <img :src="'https://inscripciones.admision.unap.edu.pe/documentos/10/inscripciones/fotos/'+record.dni+'.jpg'" alt="">
                </div>
            </template>
            <template v-if="column.dataIndex === 'gen_2024_I'">
                <div style="width: 150px;">
                    <img :src="'https://inscripciones.admision.unap.edu.pe/documentos/8/inscripciones/fotos/'+record.dni+'.jpg'" alt="">
                </div>
            </template>
            <template v-if="column.dataIndex === 'cep_2024_I'">
                <div style="width: 150px;">
                    <img :src="'https://inscripciones.admision.unap.edu.pe/documentos/6/inscripciones/fotos/'+record.dni+'.jpg'" alt="">
                </div>
            </template>
            <template v-if="column.dataIndex === 'extra_2024'">
                <div style="width: 150px;">
                    <img :src="'https://inscripciones.admision.unap.edu.pe/documentos/7/inscripciones/fotos/'+record.dni+'.jpg'" alt="">
                </div>
            </template>
            <template v-if="column.dataIndex === 'gen_2023_II'">
                <div style="width: 150px;">
                    <img :src="'https://inscripciones.admision.unap.edu.pe/documentos/5/inscripciones/fotos/inscripcion/'+record.dni+'.jpg'" alt="">
                </div>
            </template>
            <template v-if="column.dataIndex === 'cep_2023_II'">
                <div style="width: 150px;">
                    <img :src="'https://inscripciones.admision.unap.edu.pe/documentos/4/inscripciones/fotos/'+record.dni+'.jpg'" alt="">
                </div>
            </template>
            <template v-if="column.dataIndex === 'gen_2023_I'">
                <div style="width: 150px;">
                    <img :src="'https://inscripciones.admision.unap.edu.pe/documentos/3/inscripciones/fotos/'+record.dni+'.JPG'" alt="">
                </div>
            </template>
            <!-- <template v-if="column.dataIndex === 'cep_2023_I'">
                <div style="width: 150px;">
                    <img :src="'https://inscripciones.admision.unap.edu.pe/documentos/2/inscripciones/fotos/'+record.dni+'.jpg'" alt="">
                </div>
            </template> -->

            <template v-if="column.dataIndex === 'estado'">
                <div style="width: 100px;">
                    <div v-if="record.estado == 1"> <a-tag color="cyan"> VERIFICADO </a-tag></div>
                    <div v-if="record.estado == 2"> <a-tag color="orange"> SIN COMPARAR </a-tag></div>
                    <div v-if="record.estado == 3"> <a-tag color="red"> OTRA PERSONA </a-tag></div>
                </div>
            </template>
            <template v-if="column.dataIndex === 'acciones'">
                <a-button type="success" class="mr-1" style="color: #476175;" @click="actualizar1(record)" size="small">
                    <template #icon><CheckCircleOutlined/></template>
                </a-button>
                <a-button @click="actualizar2(record)" class="mr-1" style="color: blue;" size="small">
                    <template #icon><IssuesCloseOutlined /></template>
                </a-button>
                <a-popconfirm
                    title="¿Estas seguro que es falso?"
                    @confirm="actualizar3(record)">
                    <a-button shape="" size="small" style="color: crimson;">
                        <template #icon><CloseSquareOutlined/></template>
                    </a-button>
                </a-popconfirm>
            </template> 
        </template>
    </a-table> 
    
    <div class="flex justify-between mt-2 pr-4" style="margin-bottom: -5px;">
            <div><a-pagination v-model:current="pagina" simple :total="totalRegistros"  v-model:pageSize="paginasize" show-less-items /></div>
            <div clas="" style="scale: 0.9; margin-right: -20px;"> 
                <a-select v-model:value="paginasize" style="width: 90px;">
                    <a-select-option :value="10">10 Reg.</a-select-option>
                    <a-select-option :value="50">50 Reg.</a-select-option>    
                    <a-select-option :value="100">100 Reg.</a-select-option>    
                    <a-select-option :value="500">500 Reg.</a-select-option>
                </a-select>
            </div>
        </div>
    </div>
       
<div>
<a-modal v-model:visible="visible" style="margin-top: -40px; margin-bottom: -40px;" :closable="false">
    <div style="background: #476175; height: 36px; margin-left:-24px; margin-right:-24px; margin-top:-24px; margin-bottom: 28px;">
        <div class="flex justify-between ml-3 mr-1" style="height:36px; align-items: center;">
            <div><span style="font-weight: bold; color:white; font-size:1rem;">Nueva fotos</span></div>
            <div><span ><a-button @click="cerramodal()" style="background:none; border:none; color:white;">X</a-button></span></div>
        </div>
    </div>
    <a-form ref="form" name="fotos" :model="fotos" style="margin-bottom: -30px;">    
        <a-form-item :rules="[{ required: true, message: 'Ingrese la dirección', trigger: 'change' },]" name="observacion">
            <div>Observación</div>
            <a-textarea style="border-radius:6px;" type="text" placeholder="Ingrese el nombre" v-model:value="fotos.observacion" autocomplete="off" />
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
import { CheckCircleOutlined, IssuesCloseOutlined, CloseSquareOutlined,  FormOutlined, DeleteOutlined, SearchOutlined, DownOutlined, SaveOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';

import axios from 'axios';
import Fotos from './components/fotos.vue';
const loading = ref(false);
const buscar = ref("");
const fotoses= ref([]);
const visible = ref(false);
const form = ref();
const fotos = reactive({ id:null, observacion:"" })

const pagina = ref(1);
const paginasize = ref(10);
const totalRegistros = ref(1);

const showModalFilial = () => { visible.value = true; };
let timeoutId;
watch(buscar, ( newValue, oldValue ) => { 
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
        getFotos(); 
    }, 500);  
})
watch(pagina, ( newValue, oldValue ) => { getFotos(); })
watch(paginasize, ( newValue, oldValue ) => { getFotos(); })

//PERSONA CORRECTA
const actualizar1 = async (item) => {
    try {
        axios.post("actualizar-verificacion", {dni:item.dni, estado:1 }).then((result) => {
            notificacion('success',"ACTUALIZADO", "");
            getFotos();
        });
    } catch (error) {
        notificacion('error',"OCURRIO UN ERROR", "");
    }
}
//SIN FOTOS PARA COMPARAR
const actualizar2 = async (item) => {
    try {
        axios.post("actualizar-verificacion", {dni:item.dni, estado:2 }).then((result) => {
            notificacion('success',"ACTUALIZADO", "");
            getFotos();
        });
    } catch (error) {
        notificacion('error',"OCURRIO UN ERROR", "");
    }
}
//PERSONA DIFERENTE
const actualizar3 = async (item) => {
    try {
        axios.post("actualizar-verificacion", {dni:item.dni, estado:3 }).then((result) => {
            notificacion('success',"ACTUALIZADO", "");
            getFotos();
        });
    } catch (error) {
        notificacion('error',"OCURRIO UN ERROR", "");
    }
}



const abrirEditar = (item) => { visible.value = true; fotos.id = item.id; fotos.observacion = item.observacion; }

const getFotos =  async () => {
    let res = await axios.post("get-fotos-verificacion?page=" + pagina.value, { term: buscar.value, paginasize: paginasize.value } );
    fotoses.value = res.data.datos.data;
    totalRegistros.value = res.data.datos.total;
}

const eliminar = (item) => {
    axios.get("eliminar-fotos/"+item.id).then((result) => {
        getFotos();
        notificacion('error', 'PROCESO ELIMINADO', result.data.mensaje );
    });
}

const resetForm = () => { fotos.id = null; fotos.direccion = ""; fotos.estado =  ""; cerramodal();}

const notificacion = (type, titulo, mensaje) => { notification[type]({ message: titulo, description: mensaje, });};

const cerramodal = () => { visible.value = false; }

getFotos()

const columnsFotos = [
    { title: 'DNI', dataIndex: 'dni',},
    { title: 'Nombre', dataIndex: 'nombres',width:'96px' },
    { title: 'GEN JULI 2024-II', dataIndex: 'gen_juli_2024_II',},
    { title: 'GENERAL 2024-II', dataIndex: 'gen_2024_II',},
    { title: 'CEPRE 2024-II', dataIndex: 'cep_2024_II',},
    { title: 'GENERAL 2024-I', dataIndex: 'gen_2024_I'},
    { title: 'CEPRE 2024-I', dataIndex: 'cep_2024_I'},
    { title: 'EXTRA 2024', dataIndex: 'extra_2024'},
    { title: 'GENERAL 2023-II', dataIndex: 'gen_2023_II'},
    { title: 'CEPRE 2023-II', dataIndex: 'cep_2023_II'},
    { title: 'GENERAL 2023-I', dataIndex: 'gen_2023_I'},
    { title: 'Est', dataIndex: 'estado'},
    // { title: 'CEPRE 2023-I', dataIndex: 'cep_2023_I'},
    { title: 'Acciones', dataIndex: 'acciones', align:'center', width:'96px'},
];

</script>