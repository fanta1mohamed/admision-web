<template>
    <Head title="Documentos"/>
    <AuthenticatedLayout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">    
    <!-- {{ buscar }} -->
    <row class="flex justify-between mb-4" >
        <div class="mr-3">
        <a-button type="primary" disabled @click="showModalPrograma">Nuevo</a-button>
        </div>
        <div class="flex justify-between" style="position: relative;" >
        <a-input type="text" placeholder="Buscar" v-model:value="buscar" style="max-width: 300px; padding-left: 30px;"/>
        <div class="mr-2" style="position: absolute; left: 8px; top: 3px; "><search-outlined /></div>
        </div>
    </row>

    <!-- {{ inscripciones }} -->
    <a-table 
        :columns="columnsDocumentos" 
        :data-source="inscripciones"
        :pagination="false"
        size="small"
        > 
        <template #bodyCell="{ column, index, record }">

            <template v-if="column.dataIndex === 'postulante'" >
                <span>{{ record.paterno }} {{ record.materno }}, {{ record.nombres }}</span>
            </template>

            <template v-if="column.dataIndex === 'estado'" >
                <a-tag v-if="record.estado === 0" color="blue">HABILITADO</a-tag>
                <a-tag v-else color="error">ANULADO</a-tag>
            </template>

            <template v-if="column.dataIndex === 'verificado'" >
                <a-tag v-if="record.verificado === 0" color="error">no verificado</a-tag>
                <a-tag v-if="record.verificado === 1" color="success">verificado</a-tag>
            </template>

            <template v-if="column.dataIndex === 'acciones'">
                <a-button type="success" disabled style="background: orange; color: white;" @click="abrirEditar(record)" size="small">
                    <template #icon><eye-outlined/></template>
                </a-button>
                <a-divider type="vertical" />
                <a-button type="primary" @click="abrirEditar(record)" size="small">
                    <template #icon><form-outlined/></template>
                </a-button>
                <a-divider type="vertical" />
                <a-popconfirm
                    v-if="inscripciones.length"
                    title="¿Estas seguro de eliminar?"
                    @confirm="eliminar(record)"
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
        <a-modal v-model:visible="visible" title="Nuevo Documento" style="margin-top: -40px;">
            <!-- <pre>{{ programa}}</pre> -->
            <a-form
                ref="formRef"
                name="custom-validation"
                :model="formState"
                v-bind="layout"
                @finish="handleFinish"
                @validate="handleValidate"
                @finishFailed="handleFinishFailed"
                >
                <a-form-item has-feedback label="Codigo" name="codigo">
                    <a-input type="text" v-model:value="documento.codigo" autocomplete="off" />
                </a-form-item>
                <a-form-item has-feedback label="Nombre" name="nombre">
                    <a-input type="text" v-model:value="documento.nombre" autocomplete="off" />
                </a-form-item>
                <a-form-item has-feedback label="Postulante" name="postulante">
                    <a-input disabled type="text" v-model:value="documento.postulante" autocomplete="off" />
                </a-form-item>  
                <a-form-item has-feedback label="Tipo" name="tipo">
                    <a-input disabled type="text" v-model:value="documento.tipo" autocomplete="off" />
                </a-form-item>
                <a-form-item has-feedback label="Observacion" name="nombre">
                    <a-input type="text" v-model:value="documento.observacion" autocomplete="off" />
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
    import { FormOutlined, EyeOutlined, DeleteOutlined, SearchOutlined } from '@ant-design/icons-vue';
    import { notification } from 'ant-design-vue';
    import axios from 'axios';
    
    const buscar = ref("");
    const inscripciones = ref([])
    const visible = ref(false)
    const pagina = ref(1)
    const totalRegistros = ref(null)
    const documento = ref({ id:null, codigo:"", nombre:"", postulante:"", tipo:"", observacion:""})
    
    const showModalPrograma = () => { visible.value = true; };
    
    watch(buscar, ( newValue, oldValue ) => { getInscripciones() })
  
    watch(visible, ( newValue, oldValue ) => {
        if(visible.value == false && documento.value.id != null ){
            documento.value.id = null;
            documento.value.codigo = null;
            documento.value.nombre = null;
            documento.value.postulante = null;
            documento.value.tipo = null;
            documento.value.observacion = null;
        }
    })
  
    watch(pagina, ( newValue, oldValue ) => { getInscripciones(); })
    
    const layout = {
        labelCol: {
        span: 7
        },
        wrapperCol: {
        span: 14,
        },
    };  
    
    const handleOk = e => {
        console.log(e);
        visible.value = false;
    };
    
    const abrirEditar = (item) => {
        visible.value = true;
        documento.value.id = item.id;
        documento.value.codigo = item.codigo;
        documento.value.postulante = item.postulante;
        documento.value.tipo = item.tipo;
        documento.value.observacion = item.observacion;
    }
    
    const getInscripciones =  async (term = "") => {
        let res = await axios.post( "get-inscripciones-admin?page="+pagina.value , { term: buscar.value } );
        inscripciones.value = res.data.datos.data;
        totalRegistros.value = res.data.datos.total;
    }
  
    const guardar = () => {
        let post = {
            id: documento.value.id,
            codigo: documento.value.codigo,
            nombre: documento.value.nombre,
            observacion: documento.value.observacion,
            };
        axios.post("save-documento", post).then((result) => {
            getInscripciones()
            notificacion('success',result.data.titulo, result.data.mensaje);
            visible.value = false;
            documento.value.codigo = null,
            documento.value.id = null,
            documento.value.nombre = ""
        });
    }
    
    const eliminar = (item) => {
        axios.get("eliminar-modalidad/"+item.id).then((result) => {
        getInscripciones();
        notificacion('warning', result.data.titulo, result.data.mensaje );
        });
    }
    
    const columnsDocumentos = [
        { title: 'ID', dataIndex: 'id' },
        { title: 'Codigo', dataIndex: 'codigo'},
        { title: 'DNI', dataIndex: 'dni', align:'center'},
        { title: 'Postulante', dataIndex: 'postulante'},
        { title: 'Programa', dataIndex:'programa'},
        { title: 'Modalidad', dataIndex:'modalidad'},
        { title: 'Estado', dataIndex: 'estado'},
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
    
    const notificacion = (type, titulo, mensaje) => {
        notification[type]({
        message: titulo,
        description: mensaje,
        });
    };
    
    getInscripciones()
    </script>