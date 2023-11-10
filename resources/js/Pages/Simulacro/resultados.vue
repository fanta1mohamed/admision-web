<template>
    <Head title="Formulario de inscripción" />
    <AuthenticatedLayout>
        <div class="flex justify-center" style="">
            <div style="width: 100%; background: #cdcdcdc; max-width: 1000px; margin-top:20px; margin-bottom:-0px; background:white; border:solid 1px #d9d9d99d;">
                <div class="flex justify-center;" style="align-items:center; min-height: calc(100vh - 270px);">
                    <div style="width:100%">
                        <div class="flex pb-4" style="justify-content:center; ">
                            <span style="font-weight:bold; font-size:1.1rem;">
                                Consultar puntaje
                            </span>

                        </div>
                        <div class="flex mb-6" style="justify-content:center;">
                            <div class="flex" style="width:100%; max-width: 700px;">
                                <a-input v-model:value="nro_doc" @input="dniInput" :maxlength="8" style="width: 100%;"
                                    placeholder="Ingresar DNI"/>
                            </div>
                        </div>

                        <div class="flex justify-center" >
                            <div class="p-4" style="border:1px solid #d9d9d9; border-radius: 8px; min-width: 380px">
                                <div class="flex justify-between">
                                    <div><span style="font-size: 1.2rem; font-weight: bold;">Examen simulacro</span></div> 
                                    <div><span style="font-size: 1.2rem; font-weight: bold; ">11-11-23</span></div> 
                                </div>
                                <div class="flex justify-center">
                                    <span style="font-size: 3.3rem; font-weight: bold;"> 2500.001 </span>
                                    
                                </div>
                                <div class="flex justify-center">
                                    <span style="font-size: 1.5rem; font-weight: bold; ">
                                        Puesto: 120
                                    </span>
                                </div>
                            </div>
                        </div>

                        


                        <a-row>
                            <!-- <div class="flex justify-center" style="width:100%; ">
                                <a-button type="primary" :loading="pagosloading"
                                    style="width:180px; background:#340691; border-radius:4px; border: none;"
                                    @click="enviarPago()"> Continuar </a-button>
                            </div> -->
                        </a-row>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
        
<script setup>
import AuthenticatedLayout from '@/Layouts/LayoutSimulacros.vue'
import Terminos from './formulario3.vue'
import { watch, watchEffect, computed, ref, unref, reactive, onMounted } from 'vue';
import { DownOutlined, ExclamationCircleOutlined, FormOutlined, PrinterOutlined, DeleteOutlined, SearchOutlined, SaveOutlined, EyeOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import { Form } from 'ant-design-vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

const baseUrl = window.location.origin;
const residencia = ref(null)
const redseleccionado = ref(null)
const buscarResidencia = ref(null)
const residencias = ref([])
const ubicolegio = ref(null)
const ubicolegioseleccionado = ref(null)
const buscarColegio = ref(null)
const ubicolegios = ref([])
const modalAviso = ref(true);
const colegio = ref(null)
const colegioseleccionado = ref(null)
const buscarC = ref(null)
const colegios = ref([])
const confirmarcion = ref(false)
const activeKey = ref("1")
const formDatos = ref();
const loading = ref(false)
const loadingdowload = ref(false);
const pagos = ref([]);
const inscrito = ref(false);
const pagado = ref(false);
const form = reactive({
    tipo_doc: 1,
    nro_doc: '',
    paterno: '',
    materno: '',
    nombres: '',
    sexo: null,
    fec_nac: '',
    celular: '',
    correo: '',
    pais: 1,
    ubigeo_residencia: '',
    grado: 1,
    ubigeo_colegio: null,
    id_colegio: null,
    terminos: false,
    id_pago: null,
});

const dniInput = (event) => { form.nro_doc = event.target.value.replace(/\D/g, ''); };
const celularInput = (event) => { form.celular = event.target.value.replace(/\D/g, ''); };

const save = async () => {
    loading.value = true;
    try {
        const values = await formDatos.value.validateFields();
        const response = await axios.post('/save-simulacro-participante', form);
        if (response.status === 202) {
            console.log(response.data.errors);
        } else {
            inscrito.value = true;
            limpiar();
            notificacion('success', response.data.titulo, response.data.mensaje);
        }
    } catch (error) {
        console.error(error);
    }
    loading.value = false;
}

watch(buscarC, (newValue, oldValue) => { getColegios() })
watch(ubicolegioseleccionado, (newValue, oldValue) => { getColegios() })
watch(buscarResidencia, (newValue, oldValue) => { if (newValue.length >= 3) { getUbigeosResidencia() } })
watch(buscarColegio, (newValue, oldValue) => { if (newValue.length >= 3) { getUbigeosColegio() } })
watch(() => form.nro_doc, (newValue, oldValue) => {
    if (newValue.length == 8) {
        getInscrito();
        if (inscrito.value === false) {
            getPagado();
            if (pagado.value === false) {
                getPagosOnline()
            }
        }
    }
});

const onSelectResidencias = (value, option) => { redseleccionado.value = option.key; form.ubigeo_residencia = option.key };
const onSelectUbiColegios = (value, option) => { ubicolegioseleccionado.value = option.key; };
const onSelectColegios = (value, option) => { ubicolegioseleccionado.value = option.key; form.id_colegio = option.key };

const notificacion = (type, titulo, mensaje) => { notification[type]({ message: titulo, description: mensaje }); };

const getUbigeosResidencia = async () => {
    axios.post("/get-ubigeo", { "term": buscarResidencia.value })
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

const getUbigeosColegio = async () => {
    axios.post("/get-ubigeo", { "term": buscarColegio.value })
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




const getInscrito = async () => {
    axios.get("/get-inscrito-simulacro/" + form.nro_doc)
        .then((response) => {
            inscrito.value = response.data.estado;
        })
        .catch((error) => {
            if (error.response) {
                console.error('Error de servidor:', error.response.data);
            } else if (error.request) {
                console.error('Error de red:', error.request);
            } else { console.error('Error de configuración:', error.message); }
        });
}


const getPagado = async () => {
    axios.get("/get-pago-simulacro/" + form.nro_doc)
        .then((response) => {
            pagado.value = response.data.estado;
            form.id_pago = response.data.id_pago;
            if (response.data.estado === true) {
                activeKey.value = '2';
            }
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
    axios.post("/get-colegios-ubigeo", { "term": buscarC.value, ubigeo: ubicolegioseleccionado.value })
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


function validateFechaNacimiento(rule, value) {
    return new Promise((resolve, reject) => {
        if (!value) {
            reject(new Error(''));
        } else {
            const fechaNacimiento = new Date(value);
            const fechaMinima = new Date();
            const fechaMaxima = new Date();

            fechaMinima.setFullYear(fechaMinima.getFullYear() - 31);
            fechaMaxima.setFullYear(fechaMaxima.getFullYear() - 13);

            if (fechaNacimiento > fechaMaxima || fechaNacimiento < fechaMinima) {
                reject(new Error('Debes tener entre 13 y 20 años'));
            } else {
                resolve();
            }
        }
    });
}

const pagosloading = ref(false);
const cancelar = () => { modalAviso.value = false, childData.value = false; }
const aceptarT = () => { form.terminos = true; modalAviso.value = false; }
const childData = ref(false);

const handleUpdate = (newData) => {
    childData.value = newData;
};


const imprimir = async () => {
    loadingdowload.value = true;
    imp();
    await new Promise(resolve => setTimeout(resolve, 9000));
    loadingdowload.value = false;
}

const imp = () => {
    const pdfUrl = 'http://inscripciones.admision.unap.edu.pe/pdf-simulacro-inscripcion/' + form.nro_doc;
    const link = document.createElement('a');
    link.href = pdfUrl;
    link.target = '_blank';
    link.download = 'inscripcion-simulacro.pdf';
    link.click();
};

const estadoPago = ref(null);

const limpiar = () => {
    form.tipo_doc = 1;
    form.paterno = '';
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


const getPagosOnline = async () => {

    estadoPago.value = null;
    axios.get("/get-pagos-simulacro-online/" + form.nro_doc)
        .then((response) => {
            if (response.data.data.length === 0) {
                estadoPago.value = "No tiene registrado ningun para el este Simulacro de Admisión 2023"
                console.log(estadoPago.value);
            } else {
                pagos.value = response.data.data;
            }
        })
        .catch((error) => {
            if (error.response) {
                console.error('Error de servidor:', error.response.data);
            } else if (error.request) {
                console.error('Error de red:', error.request);
            } else { console.error('Error de configuración:', error.message); }
        });

}


const enviarPago = async () => {
    pagosloading.value = true;
    axios.post("/subir-pagos", { "pagos": selectedItems.value, "dni": form.nro_doc })
        .then((response) => {
            confirmarcion.value = response.data.estado;
            form.id_pago = response.data.id_pago;
            console.log(response.data.estado.estado)
            if (response.data.estado === true) {
                activeKey.value = '2'
            }
        })
        .catch((error) => {
            if (error.response) {
                console.error('Error de servidor:', error.response.data);
            } else if (error.request) {
                console.error('Error de red:', error.request);
            } else { console.error('Error de configuración:', error.message); }
        });
    pagosloading.value = false;
}


const toggleSelection = (item) => {
    item.selected = !item.selected;
};

const selectedItems = computed(() => {
    if (pagos.value) {
        return pagos.value.filter((item) => item.selected);
    }

});

getUbigeosResidencia()
getUbigeosColegio()

</script>
<style scoped>.titulo-form {
    margin-top: 20px;
    font-size: 1.2rem;
    color: #000000c4;
    font-weight: bold;
}

.selected {
    background-color: #e0e0e0;
}

.deshabilitado {
    opacity: 0.5;
    pointer-events: none;
    cursor: not-allowed;
}</style>