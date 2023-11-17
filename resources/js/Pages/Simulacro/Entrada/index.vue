<template>
    <a-row style="width: calc(100vw - 10px);">
        <a-col :span="24">
            <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="24" :lg="12" style="background: #f3f3f3;">
                <div class="flex" style="height: calc(100vh); align-items: center; justify-content: center;">
                    <div style="text-align: center;">
                        <div class="mb-4" >
                            <div>
                                <span style="font-size:1.1rem;">
                                    Total
                                </span>
                            </div>
                            <div>
                                {{ total }}
                            </div>
                            <div>
                                <span style="font-size: 1.2rem; font-weight: bold;"> Biomedicas: {{ totalI }}</span>
                            </div>
                            <div>
                                <span style="font-size: 1.2rem; font-weight: bold;"> Ingenierías: {{ totalB }}</span>
                            </div>

                            <div>
                                <span style="font-size: 1.2rem; font-weight: bold;"> Sociales: {{ totalS }}</span>
                            </div>
                        </div>
                        <div> 
                            <div>
                                Avance de usuario
                            </div>
                            <div>
                                <span style="font-size: 1.4rem; font-weight: bold;"> 0 </span>
                            </div>
                        </div>
                    </div>
                </div>
            </a-col>


            <a-col :xs="24" :sm="24" :md="24" :lg="12" style="background-color: none; overflow: hidden;">
                <div class="flex justify-end p-4 pt-5">
                    <div>
                    <!-- <label>Dni</label> -->
                    <a-form-item>
                        <a-input v-model:value="dni" placeholder="Ingresa DNI"/>
                    </a-form-item>
                    </div>
                </div>
                <div class="flex justify-center mb-4">
                    <!-- <img src="" width="80"> -->
                    <div style="border: currentColor solid 4px; border-radius: 50%; overflow: hidden;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="92" height="92" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                </div>
                <div class="flex justify-center mb-4" >
                    <span v-if="participante" style="font-size: 1.4rem; font-weight: bold;"> {{ participante.nombres}} {{ participante.paterno }} {{ participante.materno }}</span>
                </div>

                <div class="flex justify-center mb-4">
                    <div style="max-width: 580px; text-align: center;">
                        <span v-if="participante" style="font-size: 1.3rem; text-transform: capitalize;">
                            {{ participante.nombre.toLowerCase() }}
                        </span>
                    </div>
                </div>

                <div class="flex justify-center mb-4">
                    <div style="max-width: 580px; text-align: center;">
                        <span v-if="participante" style="font-size: 3.3rem; color: #7c0876; text-transform: capitalize;">
                            {{ participante.area.toLowerCase() }}
                        </span>
                    </div>
                </div>

                <hr class="mb-6 mt-1">

                <div class="mb-4">
                    <a-row :gutter="16">
                        <a-col :xs="8" :sm="8" :md="8" :lg="8" style="background:none;">
                            <div class="py-2" style="border-radius: 12px; border: 1px solid #d9d9d9;">
                                <div style="text-align: center;">
                                    <div><span style="font-size: 1.4rem; font-weight: bold;">1</span></div>
                                    <div>Piso</div>
                                </div>
                            </div>
                        </a-col>
                        <a-col :xs="8" :sm="8" :md="8" :lg="8">
                            <div class="py-2" style="border-radius: 12px; border: 1px solid #d9d9d9;">
                                <div style="text-align: center;">
                                    <div><span style="font-size: 1.4rem; font-weight: bold;"> - </span></div>
                                    <div>Aula</div>
                                </div>
                            </div>
                        </a-col>    
                        <a-col  :xs="8" :sm="8" :md="8" :lg="8">
                            <div class="py-2" style="border-radius: 12px; border: 1px solid #d9d9d9;">
                                <div style="text-align: center;">
                                    <div><span style="font-size: 1.4rem; font-weight: bold;"> - </span></div>
                                    <div>Posición</div>
                                </div>
                            </div>
                        </a-col>
                    </a-row>

                    <a-row :gutter="16" class="mt-4">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24" style="background:none;">
                            <div class="py-2" style="border-radius: 12px; border: 1px solid #d9d9d900;">
                                <div class="flex justify-center" style="text-align: center;">
                                    <div style="max-width: 500px;">
                                        <span style="font-size: 1.7rem; font-weight: bold;">
                                            <strong style="font-size: 1.6rem; font-weight: 400; ">Dependencia:</strong> Salón de eventos
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a-col>
                    </a-row>

                    <a-row :gutter="16" class="mt-4">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24" style="background:none;">
                            <div class="py-2" style="border-radius: 12px; border: 1px solid #d9d9d900;">
                                <div class="flex justify-center">
                                    <a-button v-on:click="saveEntrada()" style="width: 200px; background: #7c0876; border:none; color:white;"><span style="font-weight:bold;"> Confirmar ingreso </span></a-button>
                                </div>
                            </div>
                        </a-col>
                    </a-row>

                </div>

            </a-col>
            </a-row>
        </a-col>
    </a-row>

    <div>
        <a-modal v-model:visible="modal" title="Atención" @ok="okey" @cancel="handleCancel">
            <div class="flex">
                <span style="color: red;"> El Postulante no fue encontrado </span>
            </div>
        </a-modal>
    </div>

</template>

<script setup>
import { ref, watch } from 'vue';
import { notification } from 'ant-design-vue';

const dni = ref("");
const total = ref(0);
const totalI = ref(0);
const totalB = ref(0);
const totalS = ref(0);
const modal = ref(false);

const participante = ref({
    id: null,
    nro_doc:"",
    nombre:"",
    nombres:"",
    paterno:"",
    materno:"",
    area:""
});

watch(dni, ( newValue, oldValue ) => { 
    if(newValue.length === 8){
        getParticipante() 
    }
    if(newValue.length === 7){
        participante.value.nombre = "";
        participante.value.nombres = "";
        participante.value.paterno = "";
        participante.value.materno = "";
        participante.value.area = "";
    }
})

const getParticipante = async () => {
    axios.post("get-participante",{"dni": dni.value})
    .then((response) => {
        if(response.data.estado === true){
            participante.value = response.data.datos
        }
        else{
            noencontrado()
            console.log("NO ENCONTRADO")
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

const saveEntrada = async () => {
    axios.post("save-entrada",{ "data": participante.value })
    .then((response) => {
        participante.value = response.data.datos
        notificacion('success', response.data.titulo,'');
        getTotal();
    })
    .catch((error) => {
        if (error.response) {
            console.error('Error de servidor:', error.response.data);
        } else if (error.request) {
            console.error('Error de red:', error.request);
            } else { console.error('Error de configuración:', error.message); }
  });
}

const getTotal = async () => {
    axios.post("get-total-entrada")
    .then((response) => {
        total.value = response.data.data
        totalI.value = response.data.dataI
        totalB.value = response.data.dataB
        totalS.value = response.data.dataS

    })
    .catch((error) => {
        if (error.response) {
            console.error('Error de servidor:', error.response.data);
        } else if (error.request) {
            console.error('Error de red:', error.request);
            } else { console.error('Error de configuración:', error.message); }
  });
}

getTotal();

const limpiar = () => {
  participante.value.nombre = "";
  participante.value.nombres = "";
  participante.value.paterno = "";
  participante.value.materno = "";
  participante.value.area = "";
}

const notificacion = (type, titulo, mensaje) => {
    notification[type]({
    message: titulo,
    description: mensaje,
    });
};

const noencontrado = () => {
    modal.value = true;
    // limpiar();
}   

const okey = () => {
    modal.value = false;
}



</script>