<template>
<div>
    <a-row :gutter="16" class="mb-3">
      <div class="flex pr-6" style="width: 100%; justify-content: end; margin-top: -30px; margin-bottom: 16px;"> 
        <a-button @click="modalPagos = true" style="background: #133466; border:none; color: white;">Agregar Pagos</a-button>
      </div>
        <a-table :dataSource="pagos" :columns="colcomprobantes" :pagination="false" size="small" style="width: 100%;" > 
            <template #bodyCell="{ column, record }">
                <template v-if="column.dataIndex === 'proceso'">
                    <a-tag v-if="record.id_modalidad_proceso === 3" color="pink">{{ record.proceso }}</a-tag>
                    <a-tag v-if="record.id_modalidad_proceso === 2" color="orange">{{ record.proceso }}</a-tag>
                    <a-tag v-if="record.id_modalidad_proceso === 1" color="blue">{{ record.proceso }}</a-tag>
                </template>
            </template> 
        </a-table> 

        <div v-if="pagos">
          <div>{{   }}</div>
        </div>

        <a-modal v-model:visible="modalPagos" width="900px">
            <div>
                <a-table :dataSource="comprobantesBN" :columns="colVouchers" :pagination="false" size="small" style="width: 100%;" > 
                  <template #bodyCell="{ column, record }">
                      <template v-if="column.dataIndex === 'opcion'">
                        <template v-if="column.dataIndex === 'opcion'">
                          <div v-if="record.status">

                            <div v-if="record.status === 0"> 
                                <a-button @click="verificarBN(record)" style="background: #133466; border:none; color: white;"> seleccionar </a-button> 
                            </div>
                            <div v-if="record.status === 1"> 
                                <a-button @click="verificarBN(record)" style="background: crimson; border-radius: 5px; border:none; color:white;"> seleccionado </a-button> 
                            </div>
                          </div>
                          <div v-else>
                            <div> 
                                <a-button @click="verificarBN(record)" style="background: #133466; border:none; color: white;"> seleccionar </a-button> 
                            </div>
                          </div>
                      </template>

                      </template>
                  </template> 
              </a-table> 
            </div>

          <div class="pt-5" style="width: 100%;">     
            <a-table :dataSource="comprobantesCaja" :columns="colVoucherCaja" :pagination="false" size="small" style="width: 100%;" > 
                <template #bodyCell="{ column, record }">
                    <template v-if="column.dataIndex === 'paymentAmount'">
                      <div><strong>S/ {{ record.paymentAmount }} </strong></div>
                    </template>
                    <template v-if="column.dataIndex === 'opcion'">
                        <div v-if="record.estado">
                          <div v-if="record.estado === 0"> 
                              <a-button @click="verificarCaja(record)" style="background: #133466; border:none; color: white;"> seleccionar </a-button> 
                          </div>
                          <div v-if="record.estado === 1"> 
                              <a-button @click="verificarCaja(record)" style="background: crimson; border-radius: 5px; border:none; color:white;"> seleccionado </a-button> 
                          </div>
                        </div>
                        <div v-else>
                          <div> 
                              <a-button @click="verificarCaja(record)" style="background: #133466; border:none; color: white;"> seleccionar </a-button> 
                          </div>
                        </div>
                    </template>
                </template> 
            </a-table> 
          </div>
          <template #footer>
            <div>
              <a-button style="background: #133466; color:white; border: none;" @click="modalPagos = false">Aceptar</a-button>
            </div>
          </template>
        </a-modal>


    </a-row>
</div>
</template> 

<script setup>
import { ref, defineProps, watch } from 'vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';

const modalPagos = ref(false);
const comprobantes = ref([]);
const props = defineProps(['dni', 'proceso']);

const pagos = ref([]);

//const getComprobantes = async () => {
  //let res = await axios.post('get-pagos-banco', { dni: props.dni });
  //comprobantes.value = res.data.datos;
//};
const comprobantesCaja = ref([]);
const getCaja = async () => {
  let res = await axios.get('/get-pago-caja/'+props.dni);
  comprobantesCaja.value = res.data;
};

const comprobantesBN = ref([]);
const getBN = async () => {
  let res = await axios.get('/get-pago-BN/'+props.dni);
  comprobantesBN.value = res.data;
};


const verificar = async (comp) => {
  let res = await axios.post('verificar-comprobante-proceso', { comp });
  notificacion(res.data.type, res.data.titulo, res.data.mensaje);
  getComprobantes();
};

const verificarCaja = async (comp) => {
  let pag = {
    dni: props.dni,
    operacion:comp.paymentTitle,
    fecha: comp.paymentDatetime,
    monto: comp.paymentAmount
  }

  if(comp.status){
    if(comp.status == 1){ 
      let res = await axios.get('/eliminar-pago/'+pag.dni+"/"+pag.operacion);
      getPagosGeneral();
      getBN();
      comp.status = 0;
    }else{ comp.status = 1; }
  }else{
    let res = await axios.post('/insertar-pago', { pag });
    getPagosGeneral();
    getBN();
    notificacion("success", res.data.titulo, res.data.mensaje);
  }


};



const verificarBN = async (comp) => {
  let pag = {
    dni: props.dni,
    operacion:comp.paymentId,
    fecha: comp.date,
    monto: comp.amount
  }

  if(comp.status){
    if(comp.status == 1){ 
      let res = await axios.get('/eliminar-pago/'+pag.dni+"/"+pag.operacion);
      comp.status = 0; 
      getPagosGeneral();
    }else{ comp.status = 1; }
  }else{
    comp.status = 1;
    let res = await axios.post('/insertar-pago', { pag });
    getPagosGeneral();
    notificacion(res.data.type, res.data.titulo, res.data.mensaje);
  }

};

const getPagosGeneral = async (comp) => {
  let res = await axios.get('/get-pagos-dni/'+props.dni);
  pagos.value = res.data.data;
};

watch(() => props.dni, async (newDni) => {
  if (props.dni.length === 8 && /^[0-9]+$/.test(props.dni)) {
    // await getComprobantes();
    await getCaja();
    await getBN();
    await getPagosGeneral();    
  }
});

const colcomprobantes =  [
  { title: 'N° Operación', dataIndex: 'operacion'},
  { title: 'Nombres', dataIndex: 'nombres'},
  { title: 'Fecha', dataIndex: 'fecha'},
  { title: 'Concepto', dataIndex: 'concepto' },
  { title: 'Monto', dataIndex: 'monto' }
];

const colVouchers =  [
  { title: 'N° Operación', dataIndex: 'paymentId'},
  { title: 'Nombres', dataIndex: 'client'},
  { title: 'Fecha', dataIndex: 'date'},
  { title: 'Concepto', dataIndex: 'description' },
  { title: 'Monto', dataIndex: 'amount' },
  { title: '', dataIndex: 'opcion', align:'center' },
];

const colVoucherCaja =  [
  { title: 'N° Operación', dataIndex: 'paymentTitle'},
  { title: 'Fecha', dataIndex: 'paymentDatetime'},
  { title: 'Concepto', dataIndex: 'concepto' },
  { title: 'Monto', dataIndex: 'paymentAmount', align:'center' },
  { title: '', dataIndex: 'opcion', align:'center' },
];


const temp = ref([
  {
    "paymentId": "2177229",
    "document": "000000070080972",
    "code": "000000000000000",
    "client": "TICONA PONGO TANIA ROSARIO DEL",
    "universityId": "000000000000000",
    "description": "00000028",
    "amount": "21.00",
    "date": "2024-02-27 14:20",
    "status": "0"
  }]);

const notificacion = (type, titulo, mensaje) => { notification[type]({ message: titulo, description: mensaje, }); };
</script>


<style scoped>
.rojo{ color: #525252; background: white;}
.verde { background: #e3e3e3;}
</style>
