<template>
    <Head title="Modalidades"/>
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
        :data-source="modalidades"
        :pagination="false"
        size="small"
        > 
        <template #bodyCell="{ column, index }">
            <template v-if="column.dataIndex === 'acciones'">
                <a-button type="primary" @click="abrirEditar(modalidades[index])" size="small">
                <template #icon><form-outlined/></template>
                </a-button>
                <a-divider type="vertical" />

                <a-popconfirm
                    v-if="modalidades.length"
                    title="¿Estas seguro de eliminar?"
                    @confirm="eliminar(modalidades[index])"
                    >
                    <a-button type="danger" shape="" size="small">
                        <template #icon><delete-outlined/></template>
                    </a-button>
                </a-popconfirm>

            </template>
        </template>

    </a-table> 
    <a-pagination v-model:current="pagina" :total="totalRegistros" show-less-items />
    
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
                    <a-input type="text" v-model:value="modalidad.codigo" autocomplete="off" />
                </a-form-item>
                <a-form-item has-feedback label="Nombre" name="nombre">
                    <a-input type="text" v-model:value="modalidad.nombre" autocomplete="off" />
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
    const modalidades = ref([])
    const modalidadestemp = ref([])
    const visible = ref(false)
    const pagina = ref(1)
    const totalRegistros = ref(null)
    const modalidad = ref({ id:null, codigo:"", nombre:""})
    
    const showModalPrograma = () => { visible.value = true; };
    
    watch(buscar, ( newValue, oldValue ) => { getModalidades() })
    

    watch(visible, ( newValue, oldValue ) => {
    if(visible.value == false && modalidad.value.id != null ){
        modalidad.value.id = null;
        modalidad.value.codigo = null;
        modalidad.value.nombre = null;
    }
    })

    watch(pagina, ( newValue, oldValue ) => { getModalidades(); })
    
    
    const layout = {
        labelCol: {
        span: 7
        },
        wrapperCol: {
        span: 14,
        },
    };

    let validateCodigo = async (_rule, value) => {
        if (value === '') {
            return Promise.reject('Ingrese la sede del filial');
        } else {
            return Promise.resolve();
        }
    };  
    
    let validateNombre = async (_rule, value) => {
        if (value === '') {
            return Promise.reject('Ingrese su el nombre del filial');
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
        modalidad.value.id = item.id;
        modalidad.value.codigo = item.codigo;
        modalidad.value.nombre = item.nombre;
    
    }
    
    const getModalidades =  async (term = "") => {
        let res = await axios.post(
        "modalidad/get-modalidades?page="+pagina.value ,
        { term: buscar.value }
        );
        modalidades.value = res.data.datos.data;
        totalRegistros.value = res.data.datos.total;
    }

    const guardar = () => {
        let post = {
            id: modalidad.value.id,
            codigo: modalidad.value.codigo,
            nombre: modalidad.value.nombre,
            };
        axios.post("save-modalidad", post).then((result) => {
        getModalidades()
        notificacion('success',result.data.titulo, result.data.mensaje);
        visible.value = false;
        modalidad.value.codigo = null,
        modalidad.value.id = null,
        modalidad.value.nombre = ""
        });
    }
    
    const eliminar = (item) => {
        axios.get("eliminar-modalidad/"+item.id).then((result) => {
        getModalidades();
        notificacion('warning', result.data.titulo, result.data.mensaje );
        });
    }
    
    const columnsProgramas = [
        { title: 'Cod', dataIndex: 'codigo' },
        { title: 'Nombre', dataIndex: 'nombre'},
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
    
    getModalidades()

    
    </script>