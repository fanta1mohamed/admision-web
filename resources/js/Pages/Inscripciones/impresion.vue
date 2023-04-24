<template>
<Head title="Inscripciones-impresión"/>
<AuthenticatedLayout>
<div>
<a-tabs v-model:activeKey="tabactive" type="card">
  <a-tab-pane key="1" tab="Inscripcion" >
    <div style="padding: 0px 15px;">
      <!-- {{ dniseleccionado }} -->
      <div class="inputImpresion">
        <div>
          <label style="margin-right: 10px;"> Dni:</label>
          <a-input
            style="width: 300px;"
            placeholder="Dni"
            v-model:value="dniseleccionado"
            @keypress="handleKeyPress"
          />  
        </div>
        
        <div>
            <label style="margin-right: 10px;"> Buscar:</label>
            <a-auto-complete
            v-model:value="dniseleccionado"
            :options="postulantes"
            style="width: 300px"
            @select="onSelect"
            @search="onSearch"
          >
          <a-input
            placeholder="Buscar"
            v-model:value="dni"
            @keypress="handleKeyPress"
          />
          <template #option="{ value: val, label:lab }" style="background-color: blue;">
            <div style="height: 34px;">
              <div><span style="font-weight: 700; color: black; font-size: .7rem;">{{ val }}</span></div>
              <div style="margin-top: -10px;"><span style="font-size: .8rem; text-transform: uppercase;">{{ lab }}</span></div>
            </div>
          </template>
          </a-auto-complete>
        </div>
      </div>
    
       <div style=" width:100%; border: solid 1px #f3f3f3; margin: 20px 0px">
      <!--  <a-row type="flex">
          <a-col :span="6" :xs="{ order: 3 }" :sm="{ order: 4 }" :md="{ order: 2 }" :lg="{ order: 2 }" style="background: blue;">
            3 col-order-responsive
          </a-col>
          <a-col :span="6" :xs="{ order: 4 }" :sm="{ order: 3 }" :md="{ order: 1 }" :lg="{ order: 1 }" style="background: green;">
            4 col-order-responsive
          </a-col>
        </a-row> -->
      
        <div class="container-principal">

          <a-col flex="1 1">
            <div class="container-postulante">
                  <div class="mr-3 container-imagen">
                     <img src="https://www.bumeran.com.pe/candidate/static/media/marketplace-persona-1.e1606a8d.svg"> 
                  </div>

                  <div class="datos-postulante"> 
                    <div class="mb-2">
                      <a-label>Primer apellido</a-label>
                      <a-input v-model:value="postulante.primer_apellido" placeholder="Basic usage" />
                    </div>

                    <div class="mb-2">
                      <a-label>Segundo apellido</a-label>
                      <a-input v-model:value="postulante.segundo_apellido" placeholder="Basic usage" />
                    </div>

                    <div class="mb-2">
                      <a-label>Prenombres</a-label>
                      <a-input v-model:value="postulante.nombres" placeholder="Basic usage" />
                    </div>

                    <div class="flex">
                      <div class="mr-3">
                        <a-label>Fec. Nacimiento</a-label>
                        <div>
                          <a-date-picker v-model:value="fecha"/>
                        </div>
                      </div>
                      <div style="width: 100%;">
                        <a-label>Sexo</a-label>
                        <a-input v-model:value="postulante.sexo" placeholder="Basic usage" />
                      </div>
                    </div>
                  </div>

            </div>

            <div>
                <div class="container-postulante mb-2">
                  <div style="width: 100%;">
                    <a-label>Colegio</a-label>
                     <a-input v-model:value="value" placeholder="Basic usage" />
                  </div>
                </div>
                <div class="container-postulante mb-2">
                  <div style="width: 100%;">
                    <a-label>Procedencia</a-label>
                     <a-input v-model:value="value" placeholder="Basic usage" />
                  </div>
                </div>
                <div class="container-postulante">
                  <div style="width: 100%;">
                    <a-label>Proceso</a-label>
                     <a-input v-model:value="value" placeholder="Basic usage" />
                  </div>
                </div>
            </div>
            
          </a-col>
          <a-col flex="0 1 400px">
            <div>
                <div class="mb-2">
                  <a-label>Modalidad</a-label>
                  <a-input v-model:value="value" placeholder="Basic usage" />
                </div>
                <div>
                  <a-label>Programa de estudios</a-label>
                  <a-input v-model:value="value" placeholder="Basic usage" />
                </div>
            </div>
            <div class="mt-4 container-huellas">
              <div class="mr-1" style="border: solid 1px #F4f4f4; width: 100%; height: 100%; overflow-y: hidden;">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRH1Lh3Xxo4rLaSAwb29zUkmYeYgeK--XX7gRzwOpdX2I7FKXUwcbf6f8wgr2Aed5s76Rk&usqp=CAU"/>
              </div>
              <div class="mr-1" style="border: solid 1px #F4f4f4; width: 100%; height: 100%;">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRH1Lh3Xxo4rLaSAwb29zUkmYeYgeK--XX7gRzwOpdX2I7FKXUwcbf6f8wgr2Aed5s76Rk&usqp=CAU"/>
              </div>
            </div>
          </a-col>

        </div>
      </div>

      <div style="display: flex; justify-content: flex-end;">
        <a-button type="primary">Imprimir</a-button>
      </div>
    </div>

  </a-tab-pane>
  <a-tab-pane key="2" tab="Apoderados">
    <a-table :dataSource="apoderados" :columns="colApoderados" />
    <!-- <pre>{{apoderados}}</pre> -->
  </a-tab-pane>
  <a-tab-pane key="3" tab="Vouchers">
    <a-table :dataSource="vouchers" :columns="colVouchers" />
    <!-- <pre>{{vouchers}}</pre> -->
  </a-tab-pane>
  <a-tab-pane key="4" tab="Documentos">
    <a-table :dataSource="documentos" :columns="colDocumentos" size="small">

      <template #bodyCell="{ column, index }">
        <template v-if="column.dataIndex === 'estado'">
          <a-button v-if="documentos[index].estado == 1" type="primary" ghost>
            EN USO
          </a-button>
          <a-button v-else type="danger" ghost>
            USADO
          </a-button>
        </template>
      </template>    
    </a-table>
    <!-- <pre>{{documentos}}</pre> -->
  </a-tab-pane>
  <a-tab-pane key="5" tab="Inscripciones">
    <pre>{{ inscripciones }}</pre>
  </a-tab-pane>
  <a-tab-pane key="6" tab="Pre inscripciones">
    <pre>{{ preinscripciones }}</pre>
  </a-tab-pane>
</a-tabs>
</div>
</AuthenticatedLayout>

</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'    
import {ref, watch} from 'vue'

const dni = ref("")
const tabactive = ref('1')
const postulantes = ref([])
const dniseleccionado = ref(null)
const postulante = ref({dni:"", nombres:"", primer_apellido:"",segundo_apellido:"", sexo:"", fec_nacimiento:"" })
const preinscripciones = ref(null)
const inscripciones = ref(null)


const apoderados = ref([])
const vouchers = ref([])
const documentos = ref ([])

const onSelect = () => {
  console.log('onSelect', dniseleccionado);
};

const handleKeyPress = (ev) => {
  console.log('handleKeyPress', ev);
};

const getPostulantes =  async (term = "", page = 1) => {
  let res = await axios.post(
      "/admin/inscripciones/get-postulantes?page=" + page,
      { term: dni.value }
  );
  postulantes.value = res.data.datos.data;
}

const getPostulantesByDni =  async () => {
  let res = await axios.get(
      "/admin/inscripciones/get-postulante-dni/" + dniseleccionado.value
  );
  postulante.value = res.data.datos;
}

const getApoderados =  async () => {
  let res = await axios.get(
      "/admin/inscripciones/get-apoderados-postulante/" + dniseleccionado.value
  );
  apoderados.value = res.data.datos;
}

const getVouchers =  async () => {
  let res = await axios.get(
      "/admin/inscripciones/get-vouchers-postulante/" + dniseleccionado.value
  );
  vouchers.value = res.data.datos;
}

const getDocumentos =  async () => {
  let res = await axios.get(
      "/admin/inscripciones/get-documentos-postulante/" + dniseleccionado.value
  );
  documentos.value = res.data.datos;
}

const getPreinscripciones =  async () => {
  let res = await axios.get(
      "/admin/inscripciones/get-preinscripciones-postulante/" + dniseleccionado.value
  );
  preinscripciones.value = res.data.datos;
}

const getInscripciones =  async () => {
  let res = await axios.get(
      "/admin/inscripciones/get-inscripciones-postulante/" + dniseleccionado.value
  );
  inscripciones.value = res.data.datos;
}


watch(dni, ( newValue, oldValue ) => {
  console.log("new:", newValue);
  console.log("old:", oldValue);
  getPostulantes();
})

watch(dniseleccionado, ( newValue, oldValue ) => {
  console.log(newValue)
  // postulantes.value = [];
  if (dniseleccionado.value.length === 8){
    getPostulantesByDni()
  }
})

watch(tabactive, ( newValue, oldValue ) => {
  if (tabactive.value == 2){
    getApoderados()
  }
  if (tabactive.value == 3){
    getVouchers()
  }
  if (tabactive.value == 4){
    getDocumentos()
  }
  if (tabactive.value == 5){
    getInscripciones()
  }
  if (tabactive.value == 6){
    getPreinscripciones()
  }
})



const colApoderados = [
  { title: 'DNI', dataIndex: 'nro_documento', key: 'nro_documento',},
  { title: 'Nombres', dataIndex: 'nombres', key: 'nombres',},
  { title: 'Paterno', dataIndex: 'paterno', key: 'paterno',},
  { title: 'Materno', dataIndex: 'materno', key: 'materno', },
]

const colVouchers =  [
  { title: 'N° Operación', dataIndex: 'operacion', key: 'operacion',},
  { title: 'Fecha', dataIndex: 'fecha', key: 'fecha',},
  { title: 'Hora', dataIndex: 'hora', key: 'hora', },
  { title: 'Concepto', dataIndex: 'codigo', key: 'codigo', },
  { title: 'Monto', dataIndex: 'monto', key: 'monto', },
]

const colDocumentos =  [
  { title: 'Codigo', dataIndex: 'codigo', key: 'codigo',},
  { title: 'Tipo', dataIndex: 'tipo', key: 'tipo',},
  { title: 'estado', dataIndex: 'estado', key: 'estado',}
]

// const colInscripciones =  [
//   { title: 'Name', dataIndex: 'name', key: 'name',},
//   { title: 'Age', dataIndex: 'age', key: 'age',},
//   { title: 'Address', dataIndex: 'address', key: 'address', },
// ]

// const colPreinscripciones =  [
//   { title: 'Name', dataIndex: 'name', key: 'name',},
//   { title: 'Age', dataIndex: 'age', key: 'age',},
//   { title: 'Address', dataIndex: 'address', key: 'address', },
// ]

getPostulantes()
</script>

<style scoped>
.inputImpresion{
  display: flex;
  justify-content: space-between;
}
.container-imagen{
  border: 1px solid red;
  height: 215px; width: 210px; 
  overflow: hidden; 
}
.container-imagen img{
  height: 100%;
}
.container-principal{
  display: flex;
}

.datos-postulante{
  width: 100%;
  min-width: 300px;
}
.container-postulante{
    display: flex;
    margin-right: 12px;
}
.container-huellas {
  display: flex; width: 400px;
  justify-content: space-between;
  height: 270px;
}

@media (max-width: 380px){
  .container-imagen{
    display: flex;
    width: 100%;
    height: 340px;
  }


}

@media (max-width: 600px){
  .container-imagen{
    display: flex;
    width: 100%;
    justify-content: center;
  }
  .inputImpresion{
    display: block;
  }
  .container-postulante{
    display: block;
    margin-right: 0px;
  }

  .container-huellas img{
    width: 100%;
  }

}

@media (max-width: 1060px){
  .container-principal{
    display: block;
  }
  .container-huellas{
    width: 100%;
    height: 100%;
    justify-content: space-between;
  }
  .container-huellas img{
    width: 100%;
    transform: scale(0.7);
  }
}
</style>