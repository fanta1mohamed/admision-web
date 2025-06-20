<template>
    <div class="flex overflow-hidden shadow-sm sm:rounded-lg justify-center">
        <div class="bg-white p-5 container">
            <div class="p-2">
                <a-row class="p-4" :gutter="[16, 8]" style="border: solid #d9d9d9 1px; border-radius: 9px;">
                    <a-col :xs="24" :sm="12" :md="8" :lg="6">
                        <div style="height: 100%; overflow:hidden;">
                            <div style="margin-bottom: 0px; margin-top:0px">
                                <img :src="foto" :preview="false" />
                            </div>
                        </div>
                    </a-col>
                    <a-col :xs="24" :sm="12" :md="16" :lg="18">
                        <div class="flex justify-between" v-if="info">
                            <div class="ml-4">
                                <div class="flex mt-5">
                                    <span style="font-weight: bold; font-size: 2.0rem;">{{ info.primer_apellido }} {{ info.segundo_apellido }} {{ info.nombres }}</span>
                                </div>
                                <div style="margin-top:0px;">
                                    <span style="font-size:1.1rem; color:gray;">{{ info.email }}</span>
                                </div>
                                <div style="margin-top:5px;">
                                    <UserOutlined />
                                    <span style="font-size:1.0rem; font-weight:light; color:gray;">{{ info.celular }}</span>
                                </div>
                                <div style="margin-top:5px;">
                                    <UserOutlined />
                                </div>
                                <div style="margin-top:5px;">
                                    <UserOutlined />
                                    <span style="font-size:1.0rem; font-weight:light; color:gray;">{{ info.departamento }} - {{ info.provincia }} - {{ info.distrito }}</span>
                                </div>
                                <div class="flex" style="margin-top:5px;">
                                    <div class="mr-3" style="display:flex; align-items:center;">
                                        <FormOutlined /> {{ preinscripciones }}
                                    </div>
                                    <div class="mr-3" style="display:flex; align-items:center;">
                                        <DeleteOutlined /> {{ inscripciones }}
                                    </div>
                                    <div class="mr-3" style="display:flex; align-items:center;">
                                        <UserOutlined /> {{ control_biometrico }}
                                    </div>
                                    <div class="mr-3" style="display:flex; align-items:center;">
                                        <DownloadOutlined />
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6">
                                <a-button type="primary" @click="abrir()" disabled>
                                    Editar
                                    <template #icon>
                                        <DownloadOutlined />
                                    </template>
                                </a-button>
                            </div>
                        </div>
                    </a-col>
                </a-row>
            </div>
            <div>
                <a-row>
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 mt-4"
                            style="border: solid #d9d9d9 1px; min-height: 600px;">
                            <iframe :src="titulo" frameborder="0" width="100%" style="min-height: 600px;"></iframe>
                        </div>
                    </a-col>
                </a-row>
            </div>
        </div>
    </div>

    <a-modal v-model:open="open" title="Editar Datos"></a-modal>
    <a-modal v-model:open="open" title="Modal">sdfasdf</a-modal>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { FormOutlined, DeleteOutlined, DownloadOutlined, UserOutlined } from '@ant-design/icons-vue';
import axios from 'axios';

const props = defineProps(['dni']);

const info = ref(null);
const foto = ref("--");
const titulo = ref("");
const infoColegio = ref("");
const preinscripciones = ref("");
const inscripciones = ref("");
const control_biometrico = ref("");
const open = ref(false);

const abrir = () => {
    open.value = true;
    console.log("Esta funcionando");
};

const getPostulante = async () => {
    try {
        let res = await axios.get("/segundas/get-postulante-datos/" + props.dni);
        info.value = res.data.info;
        titulo.value = res.data.titulo;
        infoColegio.value = res.data.infoColegio;
        foto.value = res.data.foto;
        preinscripciones.value = res.data.preinscripciones;
        inscripciones.value = res.data.inscripciones;
        control_biometrico.value = res.data.control_biometrico;
    } catch (error) {
        console.error("Error al obtener los datos del postulante:", error);
    }
};

// Ejecutar getPostulante al montar el componente
onMounted(() => {
    getPostulante();
});

// Observar cambios en props.dni y volver a ejecutar getPostulante
watch(() => props.dni, (newDni) => {
    if (newDni) {
        getPostulante();
    }
});
</script>

<style scoped>
.item-inscripciones {
    cursor: pointer;
    background: white;
    align-items: center;
    border-bottom: 1px solid #d9d9d9;
    border-radius: 8px;
}

.item-inscripciones-seleccionado {
    cursor: pointer;
    background: #cdcdcd92;
    color: black;
    border-radius: 7px;
}

.item-area {
    cursor: pointer;
    background: white;
    align-items: center;
    border: 1px solid #d9d9d9;
    border-radius: 8px;
}

.item-area-seleccionado {
    cursor: pointer;
    background: red;
    color: white;
    border-radius: 7px;
}

.container {
    max-width: 1200px;
}
</style>