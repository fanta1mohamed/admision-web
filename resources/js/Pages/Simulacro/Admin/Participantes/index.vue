<template>
    <Head title="participantes"/>    
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
            <a-table :dataSource="participantes" :columns="columns" :pagination="false">
                    <template #bodyCell="{ column, index, record }">
                    <template v-if="column.dataIndex === 'nro_doc'">                    
                        <a-tag color="#4f4f4f" style="width:80px;">{{  record.nro_doc }}</a-tag>
                    </template>
                    <template v-if="column.dataIndex === 'nombres'">
                        <div>
                            <span>{{ record.nombres }} {{ record.paterno }} {{ record.materno }}</span>
                        </div>
                    </template>
                    
                    <template v-if="column.dataIndex === 'instruccion'" >
                        <a-select
                            ref="select"
                            v-model:value="record.instruccion"
                            placeholder="Seleccionar"
                            style="width: 100px;"
                            >
                            <a-select-option :value='1'><span>SEC TERMINADA</span></a-select-option>
                            <a-select-option :value='2'><span>5TO AÑO</span></a-select-option>
                            <a-select-option :value='3'><span>4TO AÑO</span></a-select-option>
                            <a-select-option :value='4'><span>3ER AÑO</span></a-select-option>
                        </a-select>
                        <!-- <a-tag v-if="record.sexo === '1'" color="blue">M</a-tag>
                        <a-tag v-if="record.sexo === '2'" color="pink">F</a-tag> -->
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
                    


                    <template v-if="column.dataIndex === 'acciones'">
                        <a-button type="success" class="mr-1" style="color: #476175;" size="small" disabled>
                            <template #icon><EyeOutlined/></template>
                        </a-button>
                        <a-button class="mr-1" @click="abrirEditar(record)"  style="color: blue;" size="small">
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
                    <a-select-option :value="3">3 Reg.</a-select-option>
                    <a-select-option :value="10">10 Reg.</a-select-option>
                    <a-select-option :value="20">20 Reg.</a-select-option>    
                    <a-select-option :value="50">50 Reg.</a-select-option>    
                    <a-select-option :value="100">100 Reg.</a-select-option>    
                </a-select>
            </div>
        </div>
    </div>
    </div>
    
    <a-modal v-model:visible="modalEditar" :footer="false" centered style="width:100%; max-width: 900px;">
        <!-- <div><pre>{{ form }}</pre></div> -->
        <div class="flex justify-center">
                <a-row style="display:flex; justify-content:center;">
                    <a-col :span="24">
                        <a-form
                            ref="formDatos"
                            name="form"
                            :model="form" :rules="formRules">
                            <a-row>
                                <div style="margin-bottom: -10px;">
                                    <h1 class="titulo-form">Datos Personales</h1>
                                </div>  
                            </a-row>
                            <a-row :gutter="16">
                            <a-col :xs="24" :sm="12" :md="24" :lg="24">
                                <div class="flex justify-end" style="">
                                    <div>
                                        <label>Sexo</label>
                                        <a-form-item name="sexo" :rules="[{ required: true, message: 'Seleccione el sexo' }]">
                                            <div>
                                                <a-radio-group v-model:value="form.sexo" name="radioGroup">
                                                    <a-radio value="1">M</a-radio>
                                                    <a-radio value="2">F</a-radio>
                                                </a-radio-group>
                                            </div>
                                        </a-form-item>    
                                    </div>
                                    <a-divider type="vertical" style="height:60px" />
                                    <div>
                                        <label>Tipo Doc</label>
                                        <a-form-item
                                            name="tipo_doc"
                                            :rules="[{ required: true, message: 'Escoja el tipo', trigger: 'change' },]">
                                            <div>
                                                <a-select
                                                    v-model:value="form.tipo_doc"
                                                    style="width: 120px">
                                                    <a-select-option :value="1">DNI</a-select-option>
                                                    <a-select-option :value="2">Carnet. Ext</a-select-option>    
                                                </a-select>
                                            </div>
                                        </a-form-item>
                                    </div>                                
                                </div>

                            </a-col>                        
                            <a-col :xs="24" :sm="12" :md="8" :lg="8">
                                <label v-if="form.tipo_doc === 1">DNI <span style="color:red;">*</span></label>
                                <label v-else>N° Carnet Ext.</label>
                                <a-form-item 
                                    name="nro_doc" 
                                    :rules="[{ required: true, message: 'Ingrese el N° de documento'},                                            
                                    { min: 8, message: 'El dni debe tener 8 digitos', trigger: 'blur'}]"
                                >
                                <a-input v-if="form.tipo_doc === 1" v-model:value="form.nro_doc" @input="dniInput" :maxlength="8" :disabled="form.terminos"/>
                                <a-input v-else v-model:value="form.nro_doc" @input="dniInput" :maxlength="12" :disabled="form.terminos"/>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="12" :md="8" :lg="8">
                                <label>Nombres<span style="color:red;">*</span></label>
                                <a-form-item name="nombres" :rules="[{ required: true, message: 'Ingrese los nombres' }]">
                                <a-input v-model:value="form.nombres"/>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="12" :md="8" :lg="8">
                                <label>Primer apellido<span style="color:red;">*</span></label>
                                <a-form-item name="paterno" :rules="[{ required: true, message: 'Ingrese su primer apellido' }]">
                                <a-input v-model:value="form.paterno"/>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="12" :md="8" :lg="8">
                                <label>Segundo apellido <span style="color:red;">*</span></label>
                                <a-form-item name="materno" :rules="[{ required: true, message: 'Ingrese su segundo apellido' }]">
                                <a-input v-model:value="form.materno"/>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="12" :md="8" :lg="8">
                                <label>Celular</label>
                                <a-form-item 
                                    name="celular" 
                                    :rules="[
                                        { required: true, message: 'Ingrese el N° de celular'},                                            
                                        { min: 8, message: 'El numero debe tener almenos 9 digitos', trigger: 'blur'}]"
                                >
                                <a-input v-model:value="form.celular" @input="celularInput" :maxlength="9"/>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="12" :md="8" :lg="8">
                                <label>Fec. Nacimiento</label>
                                <a-form-item
                                    name="fec_nac"
                                    :rules="[ { required: true, message: 'Ingresa tu fecha de nacimiento', trigger: 'change'},
                                    { validator: validateFechaNacimiento, trigger: 'change' }]"
                                >

                                <a-date-picker style="width:100%;" placeholder="Seleccionar fec. nacimiento" v-model:value="form.fec_nac" format="DD/MM/YYYY"/>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <label>Correo electrónico</label>
                                <a-form-item
                                    name="correo"
                                    :rules="[
                                        { required: true, message: 'Ingresa un correo valido', trigger: 'change'},
                                        { type: 'email', message: 'Ingresa un correo valido'}]"
                                >
                                    <a-input v-model:value="form.correo"/>
                                </a-form-item>
                            </a-col>
                        </a-row>

                        <a-row>
                            <div style="margin-top: -10px;">
                                <h1 class="titulo-form">Datos Residencia</h1>
                            </div>  
                        </a-row>

                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="12" :md="8" :lg="8">
                                <label>País</label>
                                <a-form-item name="pais">
                                    <a-select v-model:value="form.pais" placeholder="Seleccione el país">
                                        <a-select-option :value="1">PERÚ</a-select-option>
                                        <a-select-option :value="2">BOLIVIA</a-select-option>
                                        <a-select-option :value="3">CHILE</a-select-option>
                                        <a-select-option :value="4">VENEZUELA</a-select-option>
                                        <a-select-option :value="5">COLOMBIA</a-select-option>
                                        <a-select-option :value="6">ECUADOR</a-select-option>
                                        <a-select-option :value="7">ARGENTINA</a-select-option>
                                        <a-select-option :value="8">BRASIL</a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="24" :lg="16">
                                <label>Dep / Prov / Dist <span style="color:red;">*</span></label>
                                <a-form-item name="ubigeo_residencia">
                                    <a-auto-complete
                                        v-model:value="residencia"                
                                        :options="residencias"
                                        @select="onSelectResidencias"
                                    
                                    >
                                        <a-input 
                                            placeholder="Departamento"
                                            v-model:value="buscarResidencia"
                                            @keypress="handleKeyPress"
                                        
                                        >
                                            <template #suffix>
                                                <a-tooltip title="Extra information">
                                                <down-outlined/>
                                                </a-tooltip>
                                            </template>
                                        </a-input>
                                    </a-auto-complete>
                                </a-form-item>
                            </a-col>

                        </a-row>


                        <a-row>
                            <div style="margin-top: -10px;">
                                <h1 class="titulo-form">Datos de estudios</h1>
                            </div>  
                        </a-row>

                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="12" :md="8" :lg="8">
                                <label>Grado de instrucción<span style="color:red;">*</span></label>
                                <a-form-item name="grado">
                                    <a-select
                                        v-model:value="form.grado"
                                        style="width: 100%;">
                                        <a-select-option :value="1">SECUNDARIA CONCLUIDA</a-select-option>
                                        <a-select-option :value="2">SECUNDARIA 5TO AÑO</a-select-option>
                                        <a-select-option :value="3">SECUNDARIA 4T0 AÑO</a-select-option>
                                        <a-select-option :value="4">SECUNDARIA 3ER AÑO</a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="24" :lg="16">
                                <label>Ubic. colegio (dep/prov/dist)<span style="color:red;">*</span></label>
                                <a-form-item name="ubigeo_colegio">
                                    <a-auto-complete
                                        v-model:value="ubicolegio"                
                                        :options="ubicolegios"
                                        @select="onSelectUbiColegios"
                                    
                                    >
                                        <a-input
                                            placeholder="Procedencia del Colegio"
                                            v-model:value="buscarColegio"
                                            @keypress="handleKeyPress"
                                        >
                                            <template #suffix>
                                                <a-tooltip title="Extra information">
                                                <down-outlined/>
                                                </a-tooltip>
                                            </template>
                                        </a-input>
                                    </a-auto-complete>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <label>Nombre del colegio</label>
                                <a-form-item>
                                    <a-auto-complete
                                        v-model:value="colegio"                
                                        :options="colegios"
                                        @select="onSelectColegios"
                                    
                                    >
                                        <a-input
                                            placeholder="Procedencia del Colegio"
                                            v-model:value="buscarC"
                                            @keypress="handleKeyPress"
                                        >
                                            <template #suffix>
                                                <a-tooltip title="Extra information">
                                                <down-outlined/>
                                                </a-tooltip>
                                            </template>
                                        </a-input>
                                    </a-auto-complete>
                                </a-form-item>
                            </a-col>
                        </a-row>

                        <a-row>
                            <a-col :span="24">
                                <div class="flex justify-end">
                                    <a-button class="mr-4" @click="Cancelar()"> Cancelar </a-button>                          
                                    <a-button type="primary" style="width:90px; background:#340691; border-radius:4px; border: none;" @click="save()">Guardar</a-button>
                                </div>
                            </a-col>
                            </a-row>
                        </a-form>
                    </a-col>
                </a-row>
            </div> 
    </a-modal>
    
    </Layout>
    </template>
        
<script setup>
import { Head } from '@inertiajs/vue3';
import Layout from '@/Layouts/LayoutSimulacro.vue'
import { watch, computed, ref, reactive } from 'vue';
import { TeamOutlined, FormOutlined, DownOutlined, PrinterOutlined, DeleteOutlined, SearchOutlined, EyeOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import dayjs from 'dayjs';
import axios from 'axios';
const baseUrl = window.location.origin;

const buscar = ref("")
const pagina = ref(1)
const totalRegistros = ref(0)
const pageSize = ref(10) 
const loading = ref(false)
const modalEditar = ref(false)

const residencia = ref(null)
const redseleccionado = ref(null)
const buscarResidencia = ref(null)
const residencias = ref([])
const ubicolegio = ref(null)
const ubicolegioseleccionado = ref(null)
const buscarColegio = ref(null)
const ubicolegios = ref([])
const colegio = ref(null)
const colegioseleccionado = ref(null)
const buscarC = ref(null) 
const colegios = ref([])
const formDatos = ref();



const form = reactive({  
    id: null,
    tipo_doc: 1, 
    nro_doc:'',
    paterno:'',
    materno:'', 
    nombres:'', 
    sexo: null, 
    fec_nac:'',
    celular:'',
    correo:'',
    pais:1,
    ubigeo_residencia:'',
    grado:1, 
    ubigeo_colegio:null, 
    id_colegio: null, 
    terminos:false,
    id_pago:null,
});

const dniInput = (event) => { form.nro_doc = event.target.value.replace(/\D/g, ''); };
const celularInput = (event) => { form.celular = event.target.value.replace(/\D/g, ''); };

const onSelectResidencias = (value, option) => { redseleccionado.value = option.key; form.ubigeo_residencia = option.key };
const onSelectUbiColegios = (value, option) => { ubicolegioseleccionado.value = option.key; };
const onSelectColegios = (value, option) => { ubicolegioseleccionado.value = option.key; form.id_colegio = option.key };


function validateFechaNacimiento(rule, value) {
  return new Promise((resolve, reject) => {
    if (!value) {
      reject(new Error(''));
    } else {
      const fechaNacimiento = new Date(value);
      const fechaMinima = new Date();
      const fechaMaxima = new Date();

      fechaMinima.setFullYear(fechaMinima.getFullYear() - 41);
      fechaMaxima.setFullYear(fechaMaxima.getFullYear() - 13);

      if (fechaNacimiento > fechaMaxima || fechaNacimiento < fechaMinima) {
        reject(new Error('Debes tener entre 13 y 30 años'));
      } else {
        resolve();
      }
    }
  });
}







const save = async () => {
    loading.value = true;  
    try {
        const values = await formDatos.value.validateFields();
        const response = await axios.post('save-simulacro-participante', form);
        if (response.status === 202) {
            console.log(response.data.errors);
        } else {
            modalEditar.value = false;
            if(response.data.estado === true){
                notificacion('success', response.data.titulo,'');
                getParticipantes();
                limpiar();
            }
        }
    } catch (error) {
        console.error(error);
    }
    loading.value = false;
}



       
    
const participantes = ref([]);
const getParticipantes = async () => { 
    let res = await axios.post( "get-participantes-simulacro?page="+pagina.value,
    {
        "term": buscar.value,
        paginashoja: pageSize.value, 
    });
    totalRegistros.value = res.data.datos.total;
    participantes.value = res.data.datos.data;
    if(res.data.datos.data[0].fec_nac){ form.fec_nac = dayjs(res.data.datos.data[0].fec_nac) }
}
getParticipantes()


watch(pagina, ( newValue, oldValue ) => { getParticipantes(); })
watch(pageSize, ( newValue, oldValue ) => { getParticipantes(); })
watch(buscar, ( newValue, oldValue ) => { getParticipantes(); })

watch(ubicolegioseleccionado, ( newValue, oldValue ) => { getColegios() })
watch(buscarResidencia, ( newValue, oldValue ) => {  if(newValue.length >= 3){ getUbigeosResidencia() }})
watch(buscarColegio, ( newValue, oldValue ) => { if(newValue.length >= 3){ getUbigeosColegio() } })


const abrirEditar = (item) => {
    modalEditar.value = true;
    form.id = item.id_participante;
    form.tipo_doc = 1; 
    form.nro_doc = item.nro_doc; 
    form.nombres = item.nombres; 
    form.paterno = item.paterno; 
    form.materno = item.materno; 
    form.sexo = item.sexo; 
    form.celular = item.celular;
    form.correo = item.correo;
    form.fec_nac = dayjs(item.fec_nac);
    form.grado = item.instruccion;
    form.ubigeo_residencia = item.ubigeo;
    form.ubigeo_colegio = item.ubigeocolegio;
    form.id_colegio = item.idcolegio;
    residencia.value = item.lugar;
    ubicolegio.value = item.lugarcolegio;
    colegio.value = item.colegio;
}




const getUbigeosResidencia = async () => {
    axios.post("/get-ubigeo",{"term": buscarResidencia.value})
    .then((response) => {
        residencias.value = response.data.datos.data;
    })
    .catch((error) => {
        if (error.response) {
            console.error('Error de servidor:', error.response.data);
        } else if (error.request) {
            console.error('Error de red:', error.request);
            } else { console.error('Error de configuración:', error.message); }
  });
}

const getUbigeosColegio = async () => {
    axios.post("/get-ubigeo",{"term": buscarColegio.value})
    .then((response) => {
        ubicolegios.value = response.data.datos.data;
    })
    .catch((error) => {
        if (error.response) {
            console.error('Error de servidor:', error.response.data);
        } else if (error.request) {
            console.error('Error de red:', error.request);
            } else { console.error('Error de configuración:', error.message); }
  });
}



const getColegios = async () => {
    axios.post("/get-colegios-ubigeo",{"term": buscarC.value, ubigeo: ubicolegioseleccionado.value })
    .then((response) => {
        colegios.value = response.data.datos.data;
    })
    .catch((error) => {
        if (error.response) {
            console.error('Error de servidor:', error.response.data);
        } else if (error.request) {
            console.error('Error de red:', error.request);
            } else { console.error('Error de configuración:', error.message); }
  });
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





const limpiar = () => {
    form.tipo_doc = 1; 
    form.paterno ='';
    form.materno = ''; 
    form.nombres = ''; 
    form.sexo = null; 
    form.fec_nac = '';
    form.celular = '';
    form.correo = '';
    form.pais = 1;
    form.ubigeo_residencia = '';
    form.grado = 1; 
    form.ubigeo_colegio = null; 
    form.id_colegio = null; 
    form.terminos = false;
    form.id_pago = null;
    redseleccionado.valu = true
    residencia.value = null
    redseleccionado.value = null
    buscarResidencia.value = null
}



















const columns= ref([
    {
        title: 'Nro_Doc',
        dataIndex: 'nro_doc',
    },
    {
        title: 'Nombres',
        dataIndex: 'nombres',
        responsive: ['xs','sm','md','lg'],
    },
    {
        title: 'Sexo',
        dataIndex: 'sexo',
        responsive: ['md'],
    },
    {
        title: 'F. Nac',
        dataIndex: 'fec_nacimiento',
        responsive: ['lg'],
    },
    {
        title:'Instruccion',
        dataIndex: 'instruccion',
        width:'120px',
        align:'center'
    },
    {
        title: 'Lugar',
        dataIndex: 'lugar',
        responsive: ['lg'],
    },
    {
        title:'Acciones',
        dataIndex: 'acciones',
        width:'120px',
        align:'center'
    }
],
);

getUbigeosResidencia()
getUbigeosColegio()


</script>

<style scoped>
    .titulo-form{
        font-size: 1.1rem;
    }
</style>