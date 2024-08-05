<template style="background:pink;">
<Head title="Resultados"/>
<Layout v-if="examen === 0" :nombre="props.procceso_seleccionado.nombre">

  {{ props.procceso_seleccionado.id }}
  <div class="container">
    <a-row :gutter="[16, 0]" class="form-row mb-0" >
      <a-col v-for="item in maximos" :key="item" :span="24" :md="24" :lg="12" :xl="8" :xxl="8">
          {{ item }}
      </a-col>
    </a-row>
  </div>

  <Certificado :id_proceso="props.procceso_seleccionado.id" dni="70757838"/>

</Layout>

</template>
<script setup> 
import Layout from '@/Layouts/LayoutPuntaje.vue'
import { defineProps, watch, reactive, ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { format, parseISO } from 'date-fns';
import { es } from 'date-fns/locale';
import { notification } from 'ant-design-vue';
import certificado from './components/certificado.vue';
import Certificado from './components/certificado.vue';

const maximos = ref([]);

const getMaximos = async () => {
  let res = await axios.get( "/get-puntajes-maximos-proceso/"+props.procceso_seleccionado.id);
  if(res.data.estado == true){
     maximos.value = res.data.datos;
  }
}


getMaximos();









const loading = ref(true);
const modalcepreaviso = ref(false);
const nombrecolegiox = ref("");
const ejemplo = ref(false);
const modalDatos = ref(true);
const open = ref(false);
const abrirModalDatos = async () => {
  const values = await formPreinscripcion.value.validateFields();
  open.value = true
  cambiarFormato()
  getUbigeo();
  getApoderado()
  getApoderadoM()
  getColegios()
  getColegioSeleccionado()
}

const checkbox1 = ref(false)
const activeKey = ref('1');

const examen = ref(0);
const avance = ref(0)
const bottom = ref(2)

const pagina_pre = ref(0)
const next = () => { pagina_pre.value++; }
const prev = () => { pagina_pre.value--; }
const dni = ref("")

const formRef = ref();
const formState = reactive({ dni: '', codigo_secreto: '', });

const formDatosPersonales = ref();
const datospersonales = reactive({
  id: null,
  tipo_doc:1,
  primerapellido: "",
  segundo_apellido: "",
  nombres:"",
  estado_civil:null,
  sexo:null,
  correo:"",
  celular:'',
  fec_nacimiento:"",
  ubigeo:"",
  ubigeo_residencia:"",
});

const formDatosResidencia = ref();
const datosresidencia = reactive({
  pais:"",
  dep: null,
  prov: null,
  dist: null,
  direccion:''
});

const formDatosColegio = ref();
const datoscolegio = reactive({
  id_colegio:null,
  egreso: null,
  pais: 125,
  dep: null,
  prov: null,
  dist: null,
  colegio:'',
});

const formDatosPadre = ref();
const datospadre = reactive({
  id:null,
  tipo_apoderado: 1,
  dni: null,
  nombres: null,
  paterno: null,
  materno: null,
});

const formDatosMadre = ref();
const datosmadre = reactive({
  tipo_apoderado: 2,
  dni: null,
  nombres: null,
  paterno: null,
  materno: null,
});

const formPreinscripcion = ref();
const datos_preinscripcion = reactive({
  modalidad: null,
  programa:null,
  tipo_certificado:null,
  codigo_medico: null,
  codigo_certificado:null,
})

const dniInput = (event) => { formState.dni = event.target.value.replace(/\D/g, ''); };
const ubigeoInput = (event) => { formState.ubigeo = event.target.value.replace(/\D/g, ''); };
const nombresInput = (event) => { datospersonales.nombres = event.target.value.replace(/[^A-Za-zÑñ\s]/g, '');};
const pimerapellidoInput = (event) => { datospersonales.primerapellido = event.target.value.replace(/[^A-Za-zÑñ]/g, '');};
const celularInput = (event) => { datospersonales.celular = event.target.value.replace(/\D/g, ''); };

const departamentos = ref([])
const departamentoscolegio = ref([])
const buscarDep = ref("")
const buscarDepC = ref("")
const depseleccionado = ref('')
const depseleccionadoC = ref('')
watch(buscarDep, ( newValue, oldValue ) => { getDepartamentos() })
watch(buscarDepC, ( newValue, oldValue ) => { getDepartamentosColegio() })
const onSelectDepartamentos = (value, option) => {
    depseleccionado.value = option.key;
    getProvincias();
};

const onSelectDepartamentosC = (value, option) => {
    depseleccionadoC.value = option.key;
    getProvinciasColegio();
    provseleccionadaC.value = null;
    datoscolegio.prov = null;
    datoscolegio.dist = null;
    distritosC.value = [];
    datoscolegio.colegio = null;
    colegios.value = [];
};

const provincias = ref([])
const provinciasC = ref([])
const buscarProv = ref("")
const buscarProvC = ref("")
const provseleccionada = ref(null)
const provseleccionadaC = ref(null)
watch(buscarProv, ( newValue, oldValue ) => { getProvincias() })
watch(buscarProvC, ( newValue, oldValue ) => { getProvinciasColegio() })
const onSelectProvincias = (value, option) => {
    provseleccionada.value = option.key;
    getDistritos();
};
const onSelectProvinciasC = (value, option) => {
    provseleccionadaC.value = option.key;
    datoscolegio.dist = null;
    getDistritosColegio();
    datoscolegio.colegio = null;
    colegios.value = [];
};

const distritos = ref([])
const distritosC = ref([])
const buscarDist = ref("")
const buscarDistC = ref("")
const distseleccionado = ref('')
const distseleccionadoC = ref('')
const onSelectDistritos = (value, option) => { distseleccionado.value = option.key;  };
const onSelectDistritosC = (value, option) => { distseleccionadoC.value = option.key; datoscolegio.colegio = null; getColegios(); };
const colegios = ref([]);

const getPadreApi = () => {
  const token = '70ab1cd1b9b452982370381fd0be605c85ddc8795aed972afca143fee05fde43';

  axios.get(`https://apiperu.dev/api/dni/${datospadre.dni}`, {
    headers: {
      'Accept': 'application/json',
      'Authorization': `Bearer ${token}`,
    },
  })
  .then(response => {
    const data = response.data;
    datospadre.paterno = data.data.apellido_paterno
    datospadre.materno = data.data.apellido_materno
    datospadre.nombres = data.data.nombres
  })
  .catch(error => {
    console.error(error);
  });
};

const getMadreApi = () => {
  const token = '70ab1cd1b9b452982370381fd0be605c85ddc8795aed972afca143fee05fde43';

  axios.get(`https://apiperu.dev/api/dni/${datosmadre.dni}`, {
    headers: {
      'Accept': 'application/json',
      'Authorization': `Bearer ${token}`,
    },
  })
  .then(response => {
    const data = response.data;
    datosmadre.paterno = data.data.apellido_paterno
    datosmadre.materno = data.data.apellido_materno
    datosmadre.nombres = data.data.nombres
  })
  .catch(error => {
    console.error(error);
  });
};

const desactivar = () => {

  modalcarrerasprevias.value = false;
  window.location.reload();
}



watch(() => datospadre.dni, (newValue, oldValue) => {
  if(newValue.length == 8){ 
    getApoderadoDNI(1); 
  }
});
watch(() => datosmadre.dni, (newValue, oldValue) => {
  if(newValue.length == 8){ 
    getApoderadoDNI(2); 
  }
});

watch(() => datos_preinscripcion.tipo_certificado, (newValue, oldValue) => {
  if(newValue === 'CERTIFICADO AMARILLO'){ activeKey.value = "1"; }
  if(newValue === 'CERTIFICADO BLANCO'){ activeKey.value = "2";  }
  if(newValue === 'CONSTANCIA DE ESTUDIOS'){ activeKey.value = "3"; }
  ejemplo.value = true; 
});



watch(() => formState.dni, (newValue, oldValue) => {
  if(newValue){
    if(newValue.length == 8){
      getPasoRegistrado();
      if(selectedItems){selectedItems.value = [];}
      datacepre.value = [];
      anteriores.value = [];
    }
  }

});
const traslado_interno = ref(false);
const modalidades = ref([]);

const getModalidades =  async () => {
  let res = await axios.get( "/get-select-modalidad-proceso/"+ props.procceso_seleccionado.id);
  modalidades.value = res.data.datos;
}


watch(() => datos_preinscripcion.modalidad, (newValue, oldValue) => {
  getProgramas();
});

const programas = ref([]);
const getProgramas =  async () => {
  let res = await axios.post( "/get-select-programas-proceso",{ "id_modalidad": datos_preinscripcion.modalidad, "id_proceso": props.procceso_seleccionado.id });
  programas.value = res.data.datos;
}

const participa = ref(0);
const getDatosApi = () => {
  const token = '70ab1cd1b9b452982370381fd0be605c85ddc8795aed972afca143fee05fde43';
  axios.get(`https://apiperu.dev/api/dni/${formState.dni}`, {
    headers: {
      'Accept': 'application/json',
      'Authorization': `Bearer ${token}`,
    },
  })
  .then(response => {
    const data = response.data;
    datospersonales.primerapellido = data.data.apellido_paterno
    datospersonales.segundo_apellido = data.data.apellido_materno
    datospersonales.nombres = data.data.nombres
  })
  .catch(error => {
    console.error(error);
  });

  saveDNI();

};

const getDatosPersonales = async () => {
    if(pagina_pre.value == 0 ){
      const values = await formRef.value.validateFields();
    }

    let res = await axios.post( "/get-postulante-datos-personales", {nro_doc: formState.dni, ubigeo: formState.ubigeo});
      if (res.data.datos && res.data.datos.length > 0) {
        datospersonales.id = res.data.datos[0].id
        datospersonales.primerapellido = res.data.datos[0].primer_apellido
        datospersonales.segundo_apellido = res.data.datos[0].segundo_apellido
        datospersonales.nombres = res.data.datos[0].nombres
        datospersonales.estado_civil = res.data.datos[0].estado_civil
        datospersonales.sexo = res.data.datos[0].sexo
        datospersonales.correo = res.data.datos[0].correo
        datospersonales.celular = res.data.datos[0].celular
        if(res.data.datos[0].fec_nacimiento){ datospersonales.fec_nacimiento = dayjs(res.data.datos[0].fec_nacimiento) }
        datospersonales.ubigeo = res.data.datos[0].ubigeo
        datosresidencia.direccion = res.data.datos[0].direccion
        depseleccionado.value = res.data.datos[0].dep;
        datosresidencia.dep = res.data.datos[0].departamento
        provseleccionada.value = res.data.datos[0].prov;
        datosresidencia.prov = res.data.datos[0].provincia
        distseleccionado.value = res.data.datos[0].dist;
        datosresidencia.dist = res.data.datos[0].distrito
        datosresidencia.pais = res.data.datos[0].id_pais
        datospersonales.ubigeo_residencia = res.data.datos[0].ubigeo_residencia
        datospersonales.ubigeo = res.data.datos[0].ubigeo
        datosresidencia.direccion = res.data.datos[0].direccion
        pagina_pre.value = 1;
      }
      else {
        getDatosApi();
        pagina_pre.value = 1;
      }
}


const getDatosPersonales2 = async () => {

  let res = await axios.post( "/get-postulante-datos-personales2", {nro_doc: formState.dni});
    if(res.data.datos.length > 0) {
      datospersonales.id = res.data.datos[0].id
      datospersonales.tipo_doc = res.data.datos[0].tipo_doc
      datospersonales.primerapellido = res.data.datos[0].primer_apellido
      datospersonales.segundo_apellido = res.data.datos[0].segundo_apellido
      datospersonales.nombres = res.data.datos[0].nombres
      datospersonales.estado_civil = res.data.datos[0].estado_civil
      datospersonales.sexo = res.data.datos[0].sexo
      datospersonales.correo = res.data.datos[0].correo
      datospersonales.celular = res.data.datos[0].celular
      if(res.data.datos[0].fec_nacimiento){ datospersonales.fec_nacimiento = dayjs(res.data.datos[0].fec_nacimiento) }
      formState.ubigeo = res.data.datos[0].ubigeo
      datosresidencia.direccion = res.data.datos[0].direccion
      depseleccionado.value = res.data.datos[0].dep;
      datosresidencia.dep = res.data.datos[0].departamento
      provseleccionada.value = res.data.datos[0].prov;
      datosresidencia.prov = res.data.datos[0].provincia
      distseleccionado.value = res.data.datos[0].dist;
      datosresidencia.pais = res.data.datos[0].id_pais;      
      datosresidencia.dist = res.data.datos[0].distrito
      datospersonales.ubigeo_residencia = res.data.datos[0].ubigeo_residencia
      datospersonales.ubigeo = res.data.datos[0].ubigeo
      datosresidencia.direccion = res.data.datos[0].direccion
    }

}

const ultimopaso = ref(null);
const getPasoRegistrado = async () => {
  ultimopaso.value = null;
  let res = await axios.get( "/get-paso-registrado/"+props.procceso_seleccionado.id+"/"+formState.dni);
  if(res.data.estado == true){
    ultimopaso.value = res.data.datos
    await getDatosPersonales2()
    if(res.data.datos.nro == 6){
      consultaInscripcion2()
    }else{
      pagina_pre.value = res.data.datos.nro;
    }
  }
  else{
    modalcarrerasprevias.value = true;
    loading.value = true;
    getSancionado();
  }
}


const savePasos =  async (namex, num, avan ) => {
  let res = await axios.post(
    "/save-pasos-preinscripcion",
    {
      id: id_pasos.value,
      nombre: namex,
      nro: num,
      avance: avan,
      postulante: datospersonales.id,
      proceso: props.procceso_seleccionado.id
    }
  );
  getPasos()
}

const saveDNI =  async () => {
  
  let res = await axios.post( "/save-postulante-dni", {
    tipo_doc: datospersonales.tipo_doc,
    nro_doc: formState.dni,
    ubigeo_nacimiento: formState.ubigeo,
    paterno: datospersonales.primerapellido,
    materno:datospersonales.segundo_apellido,
    nombres: datospersonales.nombres,
  });
  getDatosPersonales2()
}

const saveDatosPersonales =  async () => {
  const values = await formDatosPersonales.value.validateFields();
  let res = await axios.post(
    "/save-postulante",
    {
      tipo_doc: datospersonales.tipo_doc,
      nro_doc: formState.dni,
      id: datospersonales.id,
      primer_apellido: datospersonales.primerapellido,
      segundo_apellido: datospersonales.segundo_apellido,
      nombres: datospersonales.nombres,
      correo: datospersonales.correo,
      celular: datospersonales.celular,
      sexo: datospersonales.sexo,
      estado_civil: datospersonales.estado_civil,
      fec_nacimiento: format(new Date(datospersonales.fec_nacimiento), 'yyyy-MM-dd'),
      ubigeo_nacimiento: datospersonales.ubigeo,
      //ubigeo_residencia: datospersonales.ubigeo_residencia,
      direccion: datosresidencia.direccion
    }
  );
  if( avance_current.value < 16){ savePasos("Registro de datos personales", 1, 16) } else{ next() }
  if(res.data.estado === true ){
    notificacion(res.data.tipo, res.data.titulo, res.data.mensaje)
  }
}

const saveDatosResidencia =  async () => {
  const values = await formDatosResidencia.value.validateFields();
  if(depseleccionado.value !== null && provseleccionada.value !== null && distseleccionado.value !== null ) {
    datospersonales.ubigeo_residencia = depseleccionado.value + provseleccionada.value + distseleccionado.value
  }

  let res = await axios.post(
    "/save-postulante-residencia",
    {
      id: datospersonales.id,
      ubigeo_residencia: datospersonales.ubigeo_residencia,
      direccion: datosresidencia.direccion
    }
  );
  if(res.data.estado === true ){
    notificacion(res.data.tipo, res.data.titulo, res.data.mensaje)
  }
  if( avance_current.value < 32){ savePasos("Registro de datos de residencia", 2, 32) } else{ next() }
}


watch(pagina_pre, ( newValue, oldValue ) => {
  
  if(pagina_pre === 2 ){
    getDatosPersonales();
    getDepartamentos();
  }
  if(newValue === 3 ){
      getDepartamentosColegio();
      getUbigeo();
  }
  if(newValue === 4 ){
    getApoderado();

  }
  if(newValue === 5 ){
    if( datospersonales.id ){
      getApoderadoM();
    }
  }
  if(newValue === 6){
    getModalidades();
  }
})

const getDepartamentos = async () => {
  let res = await axios.post( "/get-departamentos-codigo?page=", { term: buscarDep.value});
  departamentos.value = res.data.datos.data
}
const getDepartamentosColegio = async () => {
  let res = await axios.post( "/get-departamentos-codigo?page=", { term: buscarDepC.value});
  departamentoscolegio.value = res.data.datos.data
}
const getProvincias = async (depp) => {
  let res = await axios.post( "/get-provincias-codigo?page=", {departamento: depseleccionado.value });
  provincias.value = res.data.datos
}
const getProvinciasColegio = async (depp) => {
  let res = await axios.post( "/get-provincias-codigo?page=", {departamento: depseleccionadoC.value });
  provinciasC.value = res.data.datos
}
const getDistritos = async (depp) => {
  let res = await axios.post( "/get-distritos-codigo?page=",
    { departamento: depseleccionado.value, provincia: provseleccionada.value }
  );
  distritos.value = res.data.datos
}
const getDistritosColegio = async (depp) => {
  let res = await axios.post( "/get-distritos-codigo?page=",  {  departamento: depseleccionadoC.value, provincia: provseleccionadaC.value });
  distritosC.value = res.data.datos
}
const getColegios = async () => {
  const res = await axios.post("/get-colegio-distrito", {  ubigeo_cole: depseleccionadoC.value + provseleccionadaC.value + distseleccionadoC.value });
  colegios.value = res.data.datos;
}

const getApoderadoDNI = async (tipo) => {

  if(tipo == 1){
    let res = await axios.post( "/get-apoderado-dni", {dni: datospadre.dni });
    if (res.data.estado === true ){  
      datospadre.dni = res.data.datos.dni
      datospadre.nombres = res.data.datos.nombres
      datospadre.paterno = res.data.datos.paterno
      datospadre.materno = res.data.datos.materno
    } else{
      getpadreApi()
    }
  }else{
    let res = await axios.post( "/get-apoderado-dni", {dni: datosmadre.dni });
    if (res.data.estado === true ){  
      datosmadre.dni = res.data.datos.dni
      datosmadre.nombres = res.data.datos.nombres
      datosmadre.paterno = res.data.datos.paterno
      datosmadre.materno = res.data.datos.materno
    } else{
      getmadreApi()
    }
  }
}
const getUbigeo = async () => {
  const res = await axios.post("/get-ubigeo-colegio", { id_postulante: datospersonales.id });
  console.log(":::", res.data);
  if(res.data){
    datoscolegio.egreso = res.data[0].egreso;
    datoscolegio.id_colegio = res.data[0].value;
    nombrecolegiox.value = res.data[0].label;
    datoscolegio.dep = res.data[0].departamento;
    depseleccionadoC.value = res.data[0].dep;
    datoscolegio.prov = res.data[0].provincia;
    provseleccionadaC.value = res.data[0].prov;
    datoscolegio.dist = res.data[0].distrito;
    distseleccionadoC.value = res.data[0].dist;
  }
}

const savecolegio = async () => {
  const values = await formDatosColegio.value.validateFields();
  let ac = 'si';
  if (avance_current.value >= 48){ ac = 'no'; }
  try {
    const response = await axios.post('/save-postulante-colegio', {
      id:  datospersonales.id,
      anio_egreso: datoscolegio.egreso,
      colegio: datoscolegio.id_colegio, 
      actualizar: ac,
      proceso: props.procceso_seleccionado.id
    },);
    getPasos()
  } catch (error) {
    console.error(error);
  }
}

function validateFechaNacimiento(rule, value) {
  return new Promise((resolve, reject) => {
    if (!value) {
      reject(new Error('Por favor, selecciona tu fecha de nacimiento.'));
    } else {
      const fechaNacimiento = new Date(value);
      const fechaMinima = new Date();
      fechaMinima.setFullYear(fechaMinima.getFullYear() - 16);

      if (fechaNacimiento > fechaMinima) {
        reject(new Error('Debes tener al menos 16 años.'));
      } else {
        resolve();
      }
    }
  });
}

function validateCelular(rule, value) {
  return new Promise((resolve, reject) => {
    if (!value) {
      reject(new Error('Por favor, ingresa tu número de celular.'));
    } else {
      axios.post('/existe-celular',{ celular: value, dni:formState.dni})
        .then(response => {
          if (response.data == true) {
            reject(new Error('Este número de celular ya está registrado.'));
          } else {
            resolve();
          }
        })
        .catch(error => {
          console.error('Error al verificar el número de celular:', error);
          reject(new Error('Error al verificar el número de celular.'));
        });
    }
  });
}

function validateCorreo(rule, value) {
  return new Promise((resolve, reject) => {
    if (!value) {
      reject(new Error('Por favor, ingresa tu correo.'));
    } else {
      axios.post('/existe-correo',{ email: value, dni:formState.dni})
        .then(response => {
          if (response.data == true) {
            reject(new Error('Este correo ya está registrado.'));
          } else {
            resolve();
          }
        })
        .catch(error => {
          console.error('Error al verificar el correo:', error);
          reject(new Error('Error al verificar correo.'));
        });
    }
  });
}

function validateCodigoSecreto(rule, value) {
  return new Promise((resolve, reject) => {
    if (!value) {
      reject(new Error('Por favor, ingresa el código secreto.'));
    } else if (codigo_aleatorio.value !== formState.codigo_secreto) {
      reject(new Error('El código ingresado no coincide.'));
    } else {
      resolve();
    }
  });
}

const getApoderado = async () => {
  const res = await axios.post("/get-apoderado", { id_postulante: datospersonales.id, tipo: 1 });
  if(res.data.datos.length !== 0){
    datospadre.id = res.data.datos[0].id;
    datospadre.tipo_apoderado = res.data.datos[0].tipo_apoderado;
    datospadre.dni = res.data.datos[0].nro_documento;
    datospadre.nombres = res.data.datos[0].nombres;
    datospadre.paterno = res.data.datos[0].paterno;
    datospadre.materno = res.data.datos[0].materno;
  }
}
const getApoderadoM = async () => {
  const res = await axios.post("/get-apoderado", { id_postulante: datospersonales.id, tipo: 2 });
  if(res.data.datos.length !== 0){
    datosmadre.id = res.data.datos[0].id;
    datosmadre.dni = res.data.datos[0].nro_documento;
    datosmadre.nombres = res.data.datos[0].nombres;
    datosmadre.paterno = res.data.datos[0].paterno;
    datosmadre.materno = res.data.datos[0].materno;
  }
}
const saveApoderado = async () => {
  const values = await formDatosPadre.value.validateFields();
  let avn  = 65;
  let ac = 'si';
  if (avance_current.value >= 65){ ac = 'no'; }
  if (datospadre.tipo_apoderado === 3){ avn = 80; }
  try {
    const response = await axios.post('/save-postulante-apoderado', {
        id: datospadre.id,
        tipo_apoderado: datospadre.tipo_apoderado,
        dni: datospadre.dni,
        nombres: datospadre.nombres,
        paterno: datospadre.paterno,
        materno: datospadre.materno,
        id_postulante: datospersonales.id,
        actualizar: ac,
        proceso: props.procceso_seleccionado.id,
        name:"Registro de datos del padre o tutor",
        nro:4,
        avance: avn
    });
    getPasos();
  } catch (error) {
    // Manejar el error en caso de que la solicitud falle
    console.error(error);
  }
}

const saveApoderadoM = async () => {
  const values = await formDatosMadre.value.validateFields();
  let avn  = 80;
  let ac = 'si';
  if (avance_current.value >= 80){ ac = 'no'; }
  try {
    const response = await axios.post('/save-postulante-apoderado', {
        id: datosmadre.id,
        tipo_apoderado: datosmadre.tipo_apoderado,
        dni: datosmadre.dni,
        nombres: datosmadre.nombres,
        paterno: datosmadre.paterno,
        materno: datosmadre.materno,
        id_postulante: datospersonales.id,
        actualizar: ac,
        proceso: props.procceso_seleccionado.id,
        name:"Registro de datos de la madre",
        nro:5,
        avance: avn
    });
    getPasos();
  } catch (error) {
    console.error(error);
  }
}

const id_pasos = ref(null)
const avance_current = ref(null)

const getPasos = async ( ) => {

    let res = await axios.post( "/get-pasos-proceso",
      { postulante: datospersonales.id,
        proceso: props.procceso_seleccionado.id
      });
      if (res.data.datos.length > 0){
        avance_current.value = res.data.datos[0].avance
        id_pasos.value = null
        pagina_pre.value = res.data.datos[0].nro + 1
        avance.value = res.data.datos[0].avance
        modalcarrerasprevias.value = false;
        loading.value = false;
      }else{
        getSancionado();
        //pagina_pre.value = 1;
      }

}

const notificacion = (type, titulo, mensaje) => {
  notification[type]({
    message: titulo,
    description: mensaje,
  });
};

const emits = defineEmits(['ejecutarFuncionHijo']);


const imagen = ref(null);

const submit = async () => {
  let fd = new FormData();
  fd.append('dni', formState.dni)
  fd.append('modalidad', datos_preinscripcion.modalidad)
  fd.append('programa', datos_preinscripcion.programa)
  fd.append('tipo_certificado', datos_preinscripcion.tipo_certificado)
  fd.append('codigo_certificado', datos_preinscripcion.codigo_certificado)
  fd.append('codigo_medico', datos_preinscripcion.codigo_medico)
  fd.append('id_postulante', datospersonales.id)
  fd.append('id_proceso', props.procceso_seleccionado.id)
  fd.append('id_anterior', id_anterior.value )
  await axios.post("/save-pre-inscripcion", fd).then(res=>{
      if( avance_current.value < 100){ 
        savePasos("Registro de datos preinscripcion", 6, 110) 
      } else { 
        next() 
      }
      showToast("success","2",res.data.menssje);
  }).catch(err=>console.log(err))
  open.value = false
}

const presionado = ref(0);

const getDocs = async () => {
  if(datospersonales.tipo_doc === 1 ){
    window.open("/pdf-solicitud/"+props.procceso_seleccionado.id+"/"+formState.dni, '_blank');
  }else{
    window.open("/pdf-solicitud-extranjeros/"+props.procceso_seleccionado.id+"/"+formState.dni, '_blank');
  }
  


}

const tipo_docs = { 1: 'DNI', 2: 'PASAPORTE' }
const estados_civil = { 1: 'SOLTERO', 2: 'CASADO', 3: 'VIUDO' }
const sexos = { 1: 'MASCULINO', 2: 'FEMENINO' }

const temp_date = ref(null);

const cambiarFormato = () => {
  const fecha = datospersonales.fec_nacimiento.$d;
  const formattedDate = format(fecha, "dd 'de' MMMM 'del' yyyy", { locale: es });
  temp_date.value = formattedDate;
};

const college = ref(null)

const getColegioSeleccionado = () => {
  college.value = colegios.value.find(item => item.value === datoscolegio.id_colegio);
}

const props = defineProps(['procceso_seleccionado']);

const sancionado = ref(null)
const modalSancionado = ref(false);
const id_anterior = ref(null);
const carreras_previas = ref([]);

const getSancionado =  async () => {
  participa.value = 0;
  try {
    let res = await axios.get("/get-sancionado/" + formState.dni + "/" + props.procceso_seleccionado.id);
    sancionado.value = res.data.datos;
    if(sancionado.value != null){
      datacepre.value = [];
      loading.value = false;
      modalSancionado.value = true;
      anteriores.value = []
      return;
    }else{
      consultaInscripcion()
    }
  } catch (error) {
    console.error("Error al obtener datos de sancionado", error);
  }
}

const datacepre = ref(null)
const getParticipanteCepre =  async () => {
  datacepre.value = [];
  try {
    let res = await axios.get("/get-participante-cepre/" + formState.dni);
      if (res.data.estado === true ) {
        datacepre.value = res.data.datos;
        datospersonales.tipo_doc = 1;
        datospersonales.nombres = datacepre.value.nombres;
        datospersonales.primerapellido = datacepre.value.paterno;
        datospersonales.segundo_apellido = datacepre.value.materno;
        datospersonales.sexo = datacepre.value.sexo;
        datospersonales.ubigeo_residencia = datacepre.value.codigo_distrito;
        datoscolegio.egreso = datacepre.value.anio_egreso;
        participa.value = 1;
        getDataPrisma()
        getDatosPersonales2()
      } else {
        loading.value = false;
        modalSancionado.value = true;
        return;
    }
  } catch (error) {
    console.error("Error al obtener datos del participante", error);
}
}


const codigo_aleatorio = ref(null);

const getCodigoAleatorio = async ( ) => {
  let res = await axios.get("/generar-captcha");
  codigo_aleatorio.value = res.data.captcha;
}

const postulante_inscrito = ref(0);

const consultaInscripcion = async () => {
  postulante_inscrito.value = 0;
  try {
    let res = await axios.get("/participa-proceso/"+props.procceso_seleccionado.id+"/"+formState.dni);
      if (res.data.estado === true) {
        postulante_inscrito.value = 1;
        modalcarrerasprevias.value = false;
        loading.value = false; 
        pagina_pre.value = 7;
      } else {
        if(props.procceso_seleccionado.id_modalidad_proceso == 2){
          await getParticipanteCepre();
          participa.value = 1;
        }
        else{
          getDataPrisma();
          getDatosPersonales2()
          participa.value = 1;
        }
      }
  } catch (error) {
    console.error("Error al obtener datos del participante", error);
  } 
}

const consultaInscripcion2 = async () => {
  postulante_inscrito.value = 0;
  try {
    let res = await axios.get("/participa-proceso/"+props.procceso_seleccionado.id+"/"+formState.dni);
      if (res.data.estado === true) {
        pagina_pre.value = 7;
      }else{
        pagina_pre.value = 6;
      } 
  } catch (error) {
    console.error("Error al obtener datos del participante", error);
  }
}

getCodigoAleatorio();

const modalcarrerasprevias = ref(false);
const participante = ref(null);
const anteriores = ref([]);

const getDataPrisma = async () => {
    participante.value = null;
    const response = await axios.get('/get-data-prisma/'+formState.dni);
    if(response.data.estado === true){
        participante.value = response.data.datos;
    }
    getCarrerasPrevias();
}


const getCarrerasPrevias = async ( ) => {
    try {
        const response = await axios.post('/get-carreras-previas', {
            participante: participante.value,
            formState: formState.dni
        });
        if(response.data.mensaje === "No tiene carreras previas"){
          const data = response.data.anteriores;
          anteriores.value = [];
          loading.value = data.loading;
          modalSancionado.value = data.modalSancionado;
          confirmacion.value = data.confirmacion;
        } else{
          loading.value = false;
          anteriores.value = response.data.anteriores;
          modalSancionado.value = false;
          confirmacion.value = false;

        }
    } catch (error) {
        console.error("Error fetching data: ", error);
    }
};




const confirmacion = ref(null);

const registrarPrevias = async () => {
    confirmacion.value = null;
    axios.post("/registrar-carreras-previas",{"carreras": selectedItems.value, dni:formState.dni })
    .then((response) => {
        confirmacion.value = response.data.estado;
        modalcarrerasprevias.value = false;
    })
    .catch((error) => {
        if (error.response) {
            console.error('Error de servidor:', error.response.data);
        } else if (error.request) {
            console.error('Error de red:', error.request);
            } else { console.error('Error de configuración:', error.message); }
    });
}

const toggleSelection = (item) => {
    item.selected = !item.selected;
};

const selectedItems = computed(() => {
    if(anteriores.value){
        return anteriores.value.filter((item) => item.selected);
    }
});


const carrera_anterior = ref({});
const toggleSelection2 = (item) => {
    item.selected = !item.selected;
    if(item.selected == true){
      id_anterior.value = item.id;
      carrera_anterior.value = item;
    }else{
      id_anterior.value = null;
      carrera_anterior.value = {};
    }
};

const cancelarInscripcion = () => {
    modalcarrerasprevias.value = false;
    location.reload(true);
}


</script>
