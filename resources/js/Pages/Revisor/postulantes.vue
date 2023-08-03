<template>
<Head title="Postulantes"/>
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
      <!-- {{ postulantes }} -->
      <!-- {{ totalpaginas }} -->
      <a-table size="small" :dataSource="postulantes" :columns="columns" :pagination="false">
        <template #bodyCell="{ column, text, record }">

          <template v-if="column.dataIndex === 'nombres'" >
            <div class="flex">
              <!-- <div style="margin-bottom: -5px;" class="mr-2"><span style="font-weight: bold; font-size: .9rem;"> {{ record.dni }}  </span></div> -->
              <span style="text-transform: uppercase; font-size: .9rem;"> {{ record.dni}} {{ record.nombres }} {{ record.paterno }} {{ record.materno }}</span>
            </div>
          </template>


          <template v-if="column.dataIndex === 'requisitos'">
            
            <div style="height: 20px;">
                <div style="display: none;">
                  {{ valores = JSON.parse(record.requisitos) }}
                </div>

              <a-checkbox-group v-model:value="valores" class="checkbox-group-vertical">
                <a-checkbox v-for="(option, index) in requisitos" :key="option.value" :value="option.value" :class="{ 'first-item': index === 0 }" class="checkbox-item">
                  <div style="height: 20px; border-right: 1px #d9d9d9 solid; margin-right: 10px; padding-right: 20px;">
                    {{ option.label }}
                  </div>
                </a-checkbox>
              </a-checkbox-group>            
            </div>
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
import { SearchOutlined, EyeOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';

const certificados = ref([])
const visible = ref(false);
const verurl = ref("");

const buscar = ref("")


//----------------------------------------------

const totalpaginas = ref(0) 
const pagina = ref(1)
const paginasize = ref(20)

const requisitos = ref([])
const valores = ref([])

const getPostulantes = async () => {
  let res = await axios.post(`get-postulantes-requisitos?page=`+pagina.value ,
  { term: buscar.value, paginasize: paginasize.value });
  postulantes.value = res.data.datos.data;
  totalpaginas.value = res.data.datos.total;
}

const getRequisitos = async () => {
  let res = await axios.get('get-requisitos');
  requisitos.value = res.data.datos;
}

watch(buscar, (newValue, oldValue) => {
  getPostulantes()
});

watch(pagina, (newValue, oldValue) => {
  getPostulantes()
});
watch(paginasize, (newValue, oldValue) => {
  getPostulantes()
});


const postulantes = ref([])
getPostulantes()
getRequisitos()
//----------------------------------------------


const getCertificados = async () => {
  let res = await axios.post(`get-certificados-revision?page=`+pagina.value ,
  { term: buscar.value, paginasize: paginasize.value });
  certificados.value = res.data.datos.data;
  //totalpaginas.value = res.data.datos.total
}

const actualizarEstado = async (item) => {
  let estado = 0; 
  if(item.verificado === 0) { estado = 1 }else { estado = 0}
  let res = await axios.post(`cambiar-estado`,{ id: item.id, estado: estado });
  notificacion('success',res.data.titulo, res.data.mensaje);
  getCertificados()
}

const notificacion = (type, titulo, mensaje) => {
    notification[type]({
    message: titulo,
    description: mensaje,
    });
};

getCertificados()

const columns = [
  {
    title: 'Nombres',
    dataIndex: 'nombres',
    key: 'nombres',
    align: 'left',
    width: '400px'
  },
  {
    title: 'Requisitos',
    dataIndex: 'requisitos',
    key: 'requisitos',
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