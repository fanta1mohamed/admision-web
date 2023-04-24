<template>
    <Head title="Procesos"/>
    <AuthenticatedLayout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
    
    <!-- {{ buscar }} -->
    <row class="flex justify-between mb-4" >
        <div class="mr-3">
        <a-button type="primary" @click="showModalPrograma">Nuevo</a-button>
        </div>
        <div class="flex justify-between" style="position: relative;" >
        <a-input type="text" placeholder="Buscar" v-model:value="buscar" style="max-width: 300px; padding-left: 30px;"/>
        <div class="mr-2" style="position: absolute; left: 8px; top: 3px; "><search-outlined /></div>
        </div>
    </row>

    <a-table 
        :columns="columnsProgramas" 
        :data-source="programas"
        :pagination="false"
        size="small"
        > 
        <template #bodyCell="{ column, index }">


            <template v-if="column.dataIndex === 'area'">
                <div class="flex" style="justify-content: center;">
                    <a-tag color="#8B0000" v-if=" programas[index].area == 'BIOMÉDICAS'">{{ programas[index].area }}</a-tag>
                    <a-tag color="#252850" v-if=" programas[index].area == 'SOCIALES'">{{ programas[index].area }}</a-tag>
                    <a-tag color="#EFB810" v-if=" programas[index].area == 'INGENIERÍAS'">{{ programas[index].area }}</a-tag>
                </div>
            </template>
            
            <template v-if="column.dataIndex === 'estado'">
                <div class="flex" style="justify-content: center;">
                    <div v-if="1 == programas[index].estado">
                        <a-tag color="green">Vigente</a-tag>
                    </div>
                    <div v-if="programas[index].estado == 0">
                        <a-tag color="red">No Vigente</a-tag>
                    </div>
                </div>
            </template>

            <template v-if="column.dataIndex === 'acciones'">
                <a-button type="primary" @click="abrirEditar(programas[index])" size="small">
                <template #icon><form-outlined/></template>
                </a-button>
                <a-divider type="vertical" />
                <a-button type="danger" @click="eliminar(programas[index])" shape="" size="small">
                <template #icon><delete-outlined/></template>
                </a-button>
            </template>
        </template>

       
    </a-table> 
    <div class="flex" style="justify-content: flex-end;">
        <a-pagination v-model:current="pagina" :total="totalpaginas" show-less-items />
    </div>

    </div>
    
    </AuthenticatedLayout>
    
    <div>
        <a-modal v-model:visible="visible" title="Nuevo Programa" style="margin-top: -40px;">
            <!-- <pre>{{ programa}}</pre> -->
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
                <a-input type="text" v-model:value="programa.codigo" autocomplete="off" />
            </a-form-item>
            <a-form-item has-feedback label="Nombre" name="nombre">
                <a-input type="text" v-model:value="programa.nombre" autocomplete="off" />
            </a-form-item>
        
            <a-form-item has-feedback label="Nivel Académico" name="nivel_academico">
                <a-select
                    ref="Tipo"
                    style="width: 100%"
                    @focus="focus"
                    @change="handleChange"
                    v-model:value="programa.nivel_academico"
                    >
                    <a-select-option value="CARRERA PROFESIONAL">
                        CARRERA PROFESIONAL
                    </a-select-option>
                </a-select>
            </a-form-item>
            <a-form-item has-feedback label="Tipo autorizacion" name="tipo_autorizacion">
                <a-select
                    ref="Tipo"
                    style="width: 100%"
                    @focus="focus"
                    @change="handleChange"
                    v-model:value="programa.tipo_autorizacion"
                    >
                    <a-select-option value="RECONOCIDO POR LIC.">
                        RECONOCIDO POR LIC.
                    </a-select-option>
                </a-select>
            </a-form-item>
            <a-form-item has-feedback label="Facultad" name="facultad">
                <a-select
                    :options="facultades"
                    ref="Tipo"
                    style="width: 100%"
                    @focus="focus"
                    @change="handleChange"
                    v-model:value="programa.id_facultad"
                    >
                </a-select>
            </a-form-item>

            <a-form-item has-feedback label="Area" name="area">
                <a-select
                    style="width: 100%"
                    @focus="focus"
                    @change="handleChange"
                    v-model:value="programa.area"
                    >
                    <a-select-option value="BIOMEDICAS">
                        BIOMEDICAS
                    </a-select-option>
                    <a-select-option value="INGRENIERÍAS">
                        INGENIERÍAS
                    </a-select-option>
                    <a-select-option value="SOCIALES">
                        SOCIALES
                    </a-select-option>
                </a-select>
            </a-form-item>
            
            <a-form-item has-feedback label="Vigente" name="estado">
                <a-switch v-model:checked="programa.estado"/>
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
    const pagina = ref(1)
    const totalpaginas = ref(null)
    const dep = ref("PUNO")
    const facultades = ref([])

    const visible = ref(false);
    const buscarDep = ref("")
    const programas = ref([])
    const programa = ref({
        id:null,
        codigo:"",
        nombre:"",
        nivel_academico:"CARRERA PROFESIONAL",
        tipo_autorizacion:"RECONOCIDO POR LIC.",
        id_facultad:1,
        estado:true,
        area:"BIOMEDICAS"
    })
    
    const showModalPrograma = () => {
        visible.value = true;
    };
    
    watch(buscar, ( newValue, oldValue ) => {
        getProgramas()
    })
    
    
    watch(buscarDep, ( newValue, oldValue ) => {
        getDepartamentos()
    })
    
    watch(visible, ( newValue, oldValue ) => {
    if(visible.value == false && programa.value.id != null ){
        programa.value.id = null;
        programa.value.codigo = null;
        programa.value.nombre = null;
        programa.value.estado = true;
    }
    })

    watch(pagina, ( newValue, oldValue ) => {
        getProgramas()
    })
    
    const onSelect = (value, option) => {
        filial.value.departamento = option.key; 
        getProvinciasXdepartamento()
    };
    
    
    const layout = {
        labelCol: {
        span: 7
        },
        wrapperCol: {
        span: 14,
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
    
    nivel_academico: [{
        required: true,
        validator: validateDepartamento,
        trigger: 'change',
    }],
    tipo_autorizacion: [{
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
        programa.value.id = item.id;
        programa.value.codigo = item.codigo;
        programa.value.nombre = item.nombre;
        programa.value.nivel_academico = item.nivel_academico;
        programa.value.tipo_autorizacion = item.tipo_autorizacion;
        programa.value.id_facultad = item.id_fac;
        if(item.estado == 1){ programa.value.estado = true }
        else { programa.value.estado = false}
        programa.value.area = item.area
        // getProvinciasXdepartamento2();
        // dep.value = item.departamento;
        // filial.value.provincia = item.id_prov;
    
        // if(item.estado == 1){ filial.value.estado = true }
        // else { filial.value.estado = false}
    }

        
    const getFacultades =  async ( ) => {
        let res = await axios.get(
        "get-facultades");
        facultades.value = res.data.datos;
    }
    
    
    const getProgramas =  async (term = "") => {
        let res = await axios.post(
        "programas/get-programas?page=" + pagina.value,
        { term: buscar.value }
        );
        programas.value = res.data.datos.data;
        totalpaginas.value = res.data.datos.total;
    }
    
    
    const guardar = () => {
        let post = {
        id: programa.value.id,
        codigo: programa.value.codigo,
        nombre: programa.value.nombre,
        nivel_academico: programa.value.nivel_academico,
        tipo_autorizacion: programa.value.tipo_autorizacion,
        estado: programa.value.estado,
        id_facultad: programa.value.id_facultad,
        area: programa.value.area,
        };
        axios.post("save-programa", post).then((result) => {
        getProgramas()
        notificacion('success',result.data.titulo, result.data.mensaje);
        visible.value = false;
        programa.value.codigo = null,
        programa.value.id = null,
        programa.value.nombre = ""
        });
    }
    
    const eliminar = (item) => {
        axios.get("eliminar-programa/"+item.id).then((result) => {
        getProgramas();
        notificacion('warning', 'PROGRAMA ELIMINADO', result.data.mensaje );
        });
    }
    
    const columnsProgramas = [
        { title: 'Cod', dataIndex: 'codigo' },
        { title: 'Nombre', dataIndex: 'nombre'},
        { title: 'Facultad', dataIndex: 'facultad'},
        { title: 'Area', dataIndex: 'area'},
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
    
    getFacultades()
    getProgramas()

    
    </script>