<template>
<Head title="Procesos"/>
<AuthenticatedLayout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4" style="height: calc(100vh - 98px);">
    <row class="flex justify-between mb-4">
        <div class="mr-3">
        <a-select ref="select" v-model:value="modalidad" :options="modalidades" @focus="focus" @change="handleChange"></a-select>
        </div>
        <div class="flex justify-between" style="position: relative;">
        <a-input type="text" placeholder="Buscar" v-model:value="buscar" style="max-width: 300px;">
            <template #prefix>
            <search-outlined />
            </template>
        </a-input>
        </div>
    </row>

    <div>
        <a-table :columns="columnsProgramas" :data-source="programas" :pagination="false" size="small" :scroll="{ x: 380, y: 'calc(100vh - 240px)' }">
        <template #bodyCell="{ column, index, record }">
            <template v-if="column.dataIndex === 'vacantes'">
            <div class="editable-cell">
                <div v-if="editableData[record.id_programa]" class="editable-cell-input-wrapper">
                <a-input v-model:value="record.vacantes" @pressEnter="save(record)">
                    <template #prefix>
                    <div></div>
                    </template>
                </a-input>
                <CheckOutlined class="editable-cell-icon-check" @click="save(record)" />
                </div>
                <div v-else class="editable-cell-text-wrapper">
                {{ record.vacantes || ' ' }}
                <EditOutlined class="editable-cell-icon" @click="edit(record.id_programa)" />
                </div>
            </div>
            </template>

            <template v-if="column.dataIndex === 'estado'">
            <div class="flex" style="justify-content: center;">
                <div v-if="1 == record.estado">
                <a-tag color="blue">Activo</a-tag>
                </div>
                <div v-if="record.estado == 0">
                <a-tag color="red">Inactivo</a-tag>
                </div>
            </div>
            </template>

            <template v-if="column.dataIndex === 'acciones'">
            <div class="flex">
                <div class="mr-1">
                <a-button type="" @click="abrirEditar(record)" style="border-radius:4px; margin-right: 4px; width: 26px; border:solid 1px #9d9d9d; background: #f2f3f5; color: #1890ff;" size="small">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                </a-button>
                </div>
                <div>
                <a-button type="" class="" @click="eliminar(record)" style="border-radius:4px; background: #f3f3f3; width: 28px; border:solid 1px #9d9d9d; color:red;" shape="" size="small">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a-button>
                </div>
            </div>
            </template>
        </template>
        </a-table>
    </div>
    <div class="flex" style="justify-content: flex-end;">
        <a-pagination v-model:current="pagina" simple page-size="50" :total="totalpaginas" />
    </div>
    </div>
</AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/segundas-especialidades/LayoutDirector.vue';
import { watch, computed, ref, unref } from 'vue';
import { EyeOutlined, FormOutlined, EditOutlined, DeleteOutlined, SearchOutlined, CheckOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';

const buscar = ref("");
const pagina = ref(1);
const totalpaginas = ref(null);
const modalidades = ref([]);
const modalidad = ref(1);

const visible = ref(false);
const buscarDep = ref("");
const programas = ref([]);
const programa = ref({ id: null, codigo: "", nombre: "", vacantes: "", estado: true });

watch(buscar, () => { 
    getProgramas();
});

watch(visible, () => {
if (visible.value == false && programa.value.id != null) {
    programa.value.id = null;
    programa.value.codigo = null;
    programa.value.nombre = null;
    programa.value.estado = true;
}
});

watch(pagina, () => {
    getProgramas();
});

const abrirEditar = (item) => {
    visible.value = true;
    programa.value.id = item.id;
    programa.value.codigo = item.codigo;
    programa.value.nombre = item.nombre;
    programa.value.nivel_academico = item.nivel_academico;
    programa.value.tipo_autorizacion = item.tipo_autorizacion;
    programa.value.id_facultad = item.id_fac;
    programa.value.estado = (item.estado == 1);
    programa.value.area = item.area;
};

const getModalidades = async () => {
let res = await axios.get("/segundas/get-modalidades-segundas-activas");
modalidades.value = res.data.datos;
};

const getProgramas = async () => {
    let res = await axios.post("/segundas/get-vacantes-segundas-admin?page=" + pagina.value, { term: buscar.value });
    programas.value = res.data.datos.data;
    totalpaginas.value = res.data.datos.total;
};

const eliminar = (item) => {
    axios.post("delete-vacante-segundas", { id_vacante: item.id_vacante }).then((result) => {
        getProgramas();
        notificacion('warning', 'PROGRAMA ELIMINADO', result.data.mensaje);
    });
};

const columnsProgramas = [
{ title: 'Cod', dataIndex: 'codigo_sunedu', width: '60px', align: 'center', responsive: ['md'] },
{ title: 'Nombre', dataIndex: 'programa' },
{ title: 'Vacantes', dataIndex: 'vacantes', width: '160px', align: 'center', responsive: ['md'] },
{ title: 'Estado', dataIndex: 'estado', align: 'center', width: '60px', responsive: ['md'] },
{ title: '', dataIndex: 'acciones', width: "70px", align: 'center' },
];

const selectedRowKeys = ref([]);
const onSelectChange = (changableRowKeys) => { selectedRowKeys.value = changableRowKeys; };  
const rowSelection = computed(() => ({
    selectedRowKeys: unref(selectedRowKeys),
    onChange: onSelectChange,
    hideDefaultSelections: true,
}));

const notificacion = (type, titulo, mensaje) => { notification[type]({ message: titulo, description: mensaje, }); };

getModalidades();
getProgramas();

const editableData = ref({});
const edit = (key) => {
    editableData.value[key] = { ...programas.value.find(item => item.id_programa === key) };
};

const form = ref({ id_modalidad: modalidad.value, id_programa: null, estado: null, vacantes: null, });

const save = (item) => {
form.value = item;
    delete editableData.value[item.id_programa];
    axios.post("save-numero-vacantes-segundas", {
        id_vacante: item.id_vacante,
        id_programa: item.id_programa,
        vacantes: item.vacantes,
        id_modalidad: modalidad.value
    })
    .then(() => {
        notification.success({ message: "Registro actualizado" });
        getProgramas();
    })
    .catch(() => {
        notification.error({ message: "Error al actualizar" });
    });
};
</script>

<style>
::-webkit-scrollbar { width: 9px; height: 12px; }   
::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
::-webkit-scrollbar-thumb { background: #888; border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: #555; }
.scroll-container { overflow-y: auto; scrollbar-width: thin; scrollbar-color: #888 #f1f1f1; }
.scroll-container::-webkit-scrollbar { width: 12px; height: 12px; }
.scroll-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
.scroll-container::-webkit-scrollbar-thumb { background: #888; border-radius: 10px; }
.scroll-container::-webkit-scrollbar-thumb:hover { background: #555; }

.editable-cell { 
position: relative; 
}
.editable-cell-input-wrapper,
.editable-cell-text-wrapper { 
padding-right: 24px;
}
.editable-cell-text-wrapper { 
padding: 5px 24px 5px 5px; 
}
.editable-cell-icon,
.editable-cell-icon-check { 
position: absolute; 
right: 0; 
width: 20px; 
cursor: pointer; 
}
.editable-cell-icon { 
margin-top: 4px; 
display: none; 
}
.editable-cell-icon-check { 
line-height: 28px; 
}
.editable-cell-icon:hover,
.editable-cell-icon-check:hover { 
color: #108ee9; 
}
.editable-add-btn {
margin-bottom: 8px; 
}
.editable-cell:hover .editable-cell-icon { 
display: inline-block; 
}
</style>
