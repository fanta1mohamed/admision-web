<template>
<AuthenticatedLayout>
<div style="">
  <a-card style="background: white;" class="m-0 p-0">
    <div class="mb-2 flex justify-end">
      <a-input placeholder="Buscar" v-model:value="buscar"  style="max-width: 300px;">
        <template #suffix>
          <search-outlined />
        </template>
      </a-input>
    </div>
   
    <div>   
      <!-- {{ totalpaginas }} -->
      <a-table size="small" :dataSource="certificados" :columns="columns" :pagination="false">
        <template #bodyCell="{ column, text, record }">
          <template v-if="column.dataIndex === 'ver'" >
              <eye-outlined @click="abrirmodal(record)" class="custom-icon"/>
          </template>
          <template v-if="column.dataIndex === 'cod'" >
              <div class="flex justify-between">
                <span style="font-weight: bold; font-size: 0.9rem; margin-right: 8px;"> {{ record.cod }}  </span>
                <a-tooltip title="copiar">
                  <a-button @click="toClipboard(record.cod)" style="height:22px; width: 20px;">
                    <template #icon><CopyOutlined/></template>
                  </a-button>
                </a-tooltip>
              </div>
          </template>

          <template v-if="column.dataIndex === 'dni'" >
            <div class="flex justify-between">
                <span style="font-size: 0.9rem; margin-right: 8px;"> {{ record.dni }}  </span>
                <a-tooltip title="Copiar DNI">
                  <a-button @click="toClipboard(record.dni)" style="height:22px; width: 20px;">
                    <template #icon><CopyOutlined /></template>
                  </a-button>
                </a-tooltip>
            </div>
          </template>

          <template v-if="column.dataIndex === 'nombres'" >
            <div class="flex">
              <span style="text-transform: uppercase; font-size: .9rem;"> {{ record.nombres }} {{ record.paterno }} {{ record.materno }}</span>
            </div>
          </template>

          <template v-if="column.dataIndex === 'verificado'" >
            <a-tag v-if="record.verificado === 0" color="error">no verificado</a-tag>
            <a-tag v-if="record.verificado === 1" color="success">verificado</a-tag>
          </template>

          <template v-if="column.dataIndex === 'operation'">
            <a-button class="custom-button" @click="actualizarEstado(record)" type="primary">Validar</a-button>
          </template>
        </template>
      </a-table>
      <div class="flex justify-end">
        <a-pagination 
        v-model:current="pagina" 
        v-model:pageSize="paginasize" 
        :total="totalpaginas"
        show-size-changer 
        show-less-items />
      </div>

    </div>
  </a-card>
</div>

<a-modal width="80%" height="90%" v-model:visible="visible" title="Certificado" style="margin-top: -70px;" @ok="handleOk">
  <iframe :src="verurl" width="100%" height="450px"></iframe>
</a-modal>
</AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/LayoutDocente.vue'
import { watch, computed, ref, unref } from 'vue';
import { CopyOutlined, SearchOutlined, EyeOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import { toClipboard } from '@soerenmartius/vue3-clipboard'

import axios from 'axios';

const certificados = ref([])
const visible = ref(false);
const verurl = ref("");

const buscar = ref("")


const totalpaginas =  ref(0) 
const pagina = ref(1)
const paginasize = ref(10)


const abrirmodal = ( url) => {
  console.log("http://admision-web.test/"+url.url)
  verurl.value = "http://admision-web.test/"+url.url;
  visible.value = true;
}

const getCertificados = async () => {
  let res = await axios.post(`get-certificados-revision?page=`+pagina.value ,
  { term: buscar.value, paginasize: paginasize.value });
  certificados.value = res.data.datos.data;
  totalpaginas.value = res.data.datos.total
}

const actualizarEstado = async (item) => {
  let estado = 0; 
  if(item.verificado === 0) { estado = 1 }else { estado = 0}
  let res = await axios.post(`cambiar-estado`,{ id: item.id, estado: estado });
  notificacion('success',res.data.titulo, res.data.mensaje);
  getCertificados()
}

watch(buscar, (newValue, oldValue) => {
  getCertificados()
});
watch(pagina, (newValue, oldValue) => {
  getCertificados()
});
watch(paginasize, (newValue, oldValue) => {
  getCertificados()
});



const notificacion = (type, titulo, mensaje) => {
    notification[type]({
    message: titulo,
    description: mensaje,
    });
};

getCertificados()

const columns = [
  {
    title: 'ver',
    dataIndex: 'ver',
    align: 'center'
  },
  {
    title: 'Codigo',
    dataIndex: 'cod',
    key: 'codigo',
    width:'100px'
  },
  {
    title: 'Tipo',
    dataIndex: 'tipo',
    key: 'tipo',
    align: 'center'
  },
  {
    title: 'DNI',
    dataIndex: 'dni',
    align: 'left',
    width:'110px'
  },
  {
    title: 'Postulante',
    dataIndex: 'nombres',
    key: 'nombres',
  },
  {
    title: 'Estado',
    dataIndex: 'verificado',
    key: 'verificado',
    align: 'center'
  },
  {
    title: 'operation',
    dataIndex: 'operation',
  }
];
</script> 

<style scope>
.custom-icon {
  color: var(--primary-color);
  cursor: pointer;
  border: none;
  border-radius: 4px;
  transition: all 0.3s ease;
}

.custom-icon:hover {
  box-shadow: 0 10px 20px #4806ff59;
  transform: translateY(-2px);
}
.custom-icon:active {
  color: #0099ff;
  transform: scale(0.99);
}

.custom-button {
  background-color: var(--primary-color);
  color: white;
  border: none;
  border-radius: 4px;
  box-shadow: 0 0 0 rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.custom-button:hover {
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
}

.custom-button:active {
  transform: scale(0.99);
}



</style>