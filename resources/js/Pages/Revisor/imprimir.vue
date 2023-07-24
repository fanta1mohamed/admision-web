<template>
<Head title="Revisión de documentos"/>
<AuthenticatedLayout>
  <div>
    <a-card style="background: white; height: calc(100vh - 90px); overflow: hidden;" class="mb-0 p-0" >
      <a-row :gutter="16" class="mb-3">
        <a-col :span="24" :sm="24" :md="24" :lg="24" style="display:flex; justify-content: end; align-items: end;" >
          <div>
            <!-- {{ dniseleccionado }}
            {{ postulante }} -->
          <label style="margin-right: 10px;"> Buscar:</label>
          <a-auto-complete
            v-model:value="dniseleccionado"
            :options="postulantes"
            style="width: 300px"
            @select="onSelect"
            @search="onSearch"
          >
          <a-input
            ref="dniInput"
            placeholder="Buscar"
            v-model:value="dni"
            @keypress="handleKeyPress"
          />
          <template #suffix>
            <credit-card-outlined />
          </template>
          <template #option="{ value: val, label:lab }" style="background-color: blue;">
            <div style="height: 34px;">
              <div><span style="font-weight: 700; color: black; font-size: .7rem;">{{ val }}</span></div>
              <div style="margin-top: -10px;"><span style="font-size: .8rem; text-transform: uppercase;">{{ lab }}</span></div>
            </div>
          </template>
          </a-auto-complete>
        </div>
        </a-col>
      </a-row>

      <!-- <a-row :gutter="16">
        <a-col :span="24" :sm="24" :md="24" :lg="24" style="display:flex; justify-content: end; align-items: end;" >
          <div class="flex justify-between" style="background: #d9d9d9; width: 100%; margin-right: -8px;">
            <div v-for="(option, index) in options" :key="option.value">
              <div class="flex justify-center" style="font-size: 44px;">
                <img src="data:image/png;base64,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" width="60"/>
              </div>
              {{ option.label }}
            </div>

          </div>
        </a-col>
      </a-row> -->
      <a-row :gutter="16">
        <a-col :span="24" :sm="24" :md="8" :lg="6">
          <div style="height: 240px;">
            <!-- {{ dniseleccionado }} -->
            <h1 style="font-weight: bold;">Requisitos</h1>
            <a-checkbox v-model:checked="checkAll" class="first-item" @change="onCheckAllChange">Todo</a-checkbox>
            <a-checkbox-group v-model:value="checkedList" class="checkbox-group-vertical">
              <a-checkbox v-for="(option, index) in requisitos" :key="option.value" :value="option.value" :class="{ 'first-item': index === 0 }" class="checkbox-item">
                {{ option.label }}
              </a-checkbox>
            </a-checkbox-group>            
          </div>
          <!-- <div>
            <h1 style="font-weight: bold;">Observación</h1>
            <a-textarea type="text" style="height: 180px;" />
          </div> -->
        </a-col>
        <a-col :span="24" :sm="24" :md="16" :lg="18" style="border: 1px solid #d9d9d9; min-width: 600px;" class="m-0 p-0">
          <div style="margin-right: -8px; margin-left: -8px; min-width: 600px;">

            <a-tabs v-model:activeKey="activeKey" type="card" style="">
              <a-tab-pane key="2" tab="Voucher" class="pl-2 pr-2">
                <div class="" style="width: 100%; height: 380px;">
                  <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
                    <Vouchers :dni="dniseleccionado"/>
                  </div>
                </div>
              </a-tab-pane>
              <a-tab-pane key="1" tab="Solicitud" class="pl-2 pr-2">
                <div>
                  <div style="width:100%; height:380px; position:relative; overflow:hidden">
                    <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
                      <iframe :src="baseUrl+'/documentos/cepre2023-II/'+dniseleccionado+'/solicitud-1.pdf'" style="top:-54px; position:absolute" width="100%" height="100%" scrolling="yes" frameborder="1" ></iframe>
                    </div>
                </div>
                </div>
              </a-tab-pane>
              <a-tab-pane key="3" tab="Certificado">
                <div style="height:380px;">
                  <div style="width:100%; height:380px; position:relative; overflow:hidden">
                    <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
                      <iframe :src="baseUrl+'/documentos/cepre2023-II/'+dniseleccionado+'/certificado-1.pdf'" style="top:-54px; position:absolute" width="100%" height="470px"   scrolling="yes" frameborder="1" ></iframe>
                    </div>
                  </div>
                </div>
              </a-tab-pane>
              <a-tab-pane key="4" tab="Ex vocacional">
                <div>
                  <div style="width:100%; height:380px; position:relative; overflow:hidden">
                    <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
                      <iframe :src="baseUrl+'/documentos/cepre2023-II/'+dniseleccionado+'/constancia%20vocacional-1.pdf'" style="top:-54px; position:absolute" width="100%" height="470px"   scrolling="yes" frameborder="1" ></iframe>
                    </div>
                  </div>
                </div>
              </a-tab-pane>
              <a-tab-pane key="5" tab="Cert Cepre">
                <div>
                  <div style="width:100%; height:380px; position:relative; overflow:hidden">
                    <div v-if="dniseleccionado !== null && dniseleccionado.length === 8">
                      <!-- <iframe :src="baseUrl+'/documentos/cepre2023-II/'+dniseleccionado+'/constancia%20vocacional-1.pdf'" style="top:-54px; position:absolute" width="100%" height="470px"   scrolling="yes" frameborder="1" ></iframe> -->
                    </div>
                  </div>
                </div>  
              </a-tab-pane>

              <a-tab-pane key="6" tab="D. Biométricos">
                <div>
                </div>  
              </a-tab-pane>
            </a-tabs>

          </div>
        </a-col>
      </a-row>
      <div class="mt-4 flex justify-end" style="margin-right: -10px;">
        <a-button type="primary"  @click="abrirVentana()">Imprimir</a-button>
      </div>

    </a-card>
  </div>
</AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/LayoutDocente.vue'
import { watch, computed, ref, unref } from 'vue';
import { FormOutlined, DeleteOutlined, CreditCardOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';
import Vouchers from './components/voucher.vue'
const baseUrl = window.location.origin;

const dni = ref(null);
const dniseleccionado = ref(null)

const postulantes = ref([]) 

const numerorandom = ref();

const generateRandomNumber = () => {
 numerorandom.value = Math.floor(Math.random() * 100) + 1;
}

function focusInput() { save() }
const checkedList = ref([]);
const options = [
  { label: 'Solicitud', value: 1 },
  { label: 'Vouchers', value: 2 },
  { label: 'Certificado', value: 3 },
  { label: 'Ex vocacional', value: 4 },
  { label: 'C. Cepreuna', value: 5 },
];

const checkAll = ref(false);

const onCheckAllChange = (e) => {
  checkAll.value = e.target.checked;
  checkedList.value = e.target.checked ? options.map((option) => option.value) : [];
};

const onCheckboxChange = (checkedValues) => {
  checkedList.value = checkedValues;
  checkAll.value = checkedValues.length === options.length;
};

const requisitos = ref([]);
const getRequisitos = async () => {
  let res = await axios.get('get-requisitos');
  requisitos.value = res.data.datos;
}

const dniInput = ref(null)
const save = async () => {
  dniInput.value.focus()
  let res = await axios.post('save-requisito',{
    dni: dniseleccionado.value, requisitos: checkedList.value 
  });
  dniseleccionado.value = null
  checkedList.value = []
}


const getPostulantes =  async (term = "", page = 1) => {
  let res = await axios.post(
      "get-postulantes?page=" + page,
      { term: dni.value }
  );
  postulantes.value = res.data.datos.data;
}

const getPostulanteRequisitos = async () => {
  checkedList.value = [];
  let res = await axios.post("get-postulante-requisitos",{ dni: dniseleccionado.value });
  if(res.data.estado === true ){
    checkedList.value = JSON.parse(res.data.datos.requisitos);
  }
}

getPostulanteRequisitos()

const getPostulantesByDni = async () => {
  generateRandomNumber()
  let res = await axios.post("get-postulante-dni",{ dni: dniseleccionado.value });
  postulante.value.id = res.data.datos.id_postulante;   
  postulante.value.dni_temp = res.data.datos.dni
}

watch(dni, (newValue, oldValue ) => {
  getPostulantes();
})

watch(dniseleccionado, (newValue, oldValue ) => {
    getPostulanteRequisitos();
})

const abrirVentana = async () => {
  let res = await axios.post("control-biometrico",{ dni: dniseleccionado.value });
  imprimirPDF(res.data.datos);

  // postulante.value.id = res.data.datos.id_postulante;   
  // postulante.value.dni_temp = res.data.datos.dni
  // const url = 'https://admision-web.test/pdf-ingreso/'+dniseleccionado.value;
  // window.open(url, '_blank');
}



const imprimirPDF =  (dnni) => {
    var iframe = document.createElement('iframe');
    iframe.style.display = "none";
    iframe.src = baseUrl+'/documentos/cepre2023-II/'+dnni+'/control-biometrico-unido.pdf';
    document.body.appendChild(iframe);
    iframe.contentWindow.focus();
    iframe.contentWindow.print();
}

getRequisitos()
</script>


<style scoped>
.checkbox-group-vertical {
  display: flex;
  flex-wrap: wrap;
}

.checkbox-item {
  flex: 0 0 100%;
  margin-bottom: 8px;
}

.first-item {
  margin-left: 8px;
  margin-bottom: 8px;
}
</style>