<template>
    <div>
        <div class="flex justify-center mt-6 mb-4" style="">
            <div class="flex justify-center p-3"
                style="width: 820px; background: white; border: solid #d9d9d9 1px; border-radius: 10px;">

                {{ respuestas}}
                <div style="width:300px; margin-right: 10px;">
                    <div class="flex justify-center mb-2">
                        <img src="../../../../../assets/imagenes/logotiny.png" width="60" />
                    </div>
                    <div class="flex justify-center" style=" height: 60px; width: 300px;">
                            <div class="pl-1 pr-1" style="font-size: 1.0rem; text-align: center;">
                                <div>Universidad Nacional del Altiplano</div>
                                <div style="margin-top: -5px;">Vicerrectorado Académico</div>
                                <div style="margin-top: -5px;">Dirección de Admisión</div>
                            </div>
                        </div>
                        <div class="flex justify-center" style="height: 30px; width: 300px; font-weight: bold;">
                            HOJA DE IDENTIFICACIÓN
                    </div>

                    <div class="flex justify-center">
                        <a-card class="pl-4" style="width: 300px;">
                            <a-radio-group v-model:value="tipo">
                                <a-radio value="P">P</a-radio>
                                <a-radio value="Q">Q</a-radio>
                                <a-radio value="R">R</a-radio>
                                <a-radio value="S">S</a-radio>
                                <a-radio value="T">T</a-radio>
                            </a-radio-group>
                        </a-card>
                    </div>

                    <div class="ml-3 pr-4" style="">

                        <div class="mt-3">
                            <a-select ref="select" v-model:value="area_examen" style="width: 175px">
                                <a-select-option :value="4">BIOMEDICAS</a-select-option>
                                <a-select-option :value="6">INGENIERIAS</a-select-option>
                                <a-select-option :value="5">SOCIALES</a-select-option>
                            </a-select>

                        </div>


                        <div class="mt-3">
                            <label>N° Documento</label>
                            <a-input v-model:value="dni" />
                        </div>
                        <div class="mt-3">
                            <label>Nombres</label>
                            <a-input v-model:value="nombreP" />
                        </div>
                        <div class="mt-3">
                            <label>Apellido Paterno</label>
                            <a-input v-model:value="paterno" />
                        </div>
                        <div class="mt-3">
                            <label>Apellido Materno</label>
                            <a-input v-model:value="materno" />
                        </div>
                    </div>
                </div>

                <hr style="border: solid 1px #00000007; height: 600px; margin-right: 20px;" />

                <div>
                    <div>
                        <div class="flex justify-center" style=" height: 60px; width: 460px;">
                            <div>
                                <img src="../../../../../assets/imagenes/logotiny.png" width="50" />
                            </div>
                            <div class="pl-3 pr-3" style="text-align: center;">
                                <div>Universidad Nacional del Altiplano</div>
                                <div style="margin-top: -5px;">Vicerrectorado Académico</div>
                                <div style="margin-top: -5px;">Dirección de Admisión</div>
                            </div>
                            <div>
                                <img src="../../../../../assets/imagenes/logoDAD.png" width="60" />
                            </div>
                        </div>
                        <div class="flex justify-center" style="height: 30px; width: 400px; font-weight: bold;">
                            HOJA DE RESPUESTAS
                        </div>
                    </div>
                    <div class="flex justify-center mb-4">
                        <a-card style="width: 300px; height: 50px;">
                            <div style="margin-top: -10px">
                                <a-radio-group v-model:value="tipo">
                                    <a-radio value="P">P</a-radio>
                                    <a-radio value="Q">Q</a-radio>
                                    <a-radio value="R">R</a-radio>
                                    <a-radio value="S">S</a-radio>
                                    <a-radio value="T">T</a-radio>
                                </a-radio-group>
                            </div>
                        </a-card>
                    </div>
                    <div class="flex justify-center">
                        <div style="margin-right: 30px;">
                            <div v-for="number in 30" :key="number" style="padding: 0px; height: 17px;">
                                <a-checkbox-group v-model:value="marcadas[number - 1]" style="width: 200px; height: 20px;"
                                    :style="number % 2 == 0 ? { backgroundColor: '#00000007' } : ''">
                                    <div
                                        style="padding: 0px; height: 20px; margin-bottom: -20px; display: flex; align-items: center; overflow: hidden;">
                                        <a-col :span="4" style="margin-right: -10px;">
                                            <div style="width: 20px; font-size: .7rem;">{{ number }}</div>
                                        </a-col>
                                        <a-col :span="4" class="">
                                            <a-checkbox value="A" class="small-checkbox">A</a-checkbox>
                                        </a-col>
                                        <a-col :span="4">
                                            <a-checkbox value="B" class="small-checkbox">B</a-checkbox>
                                        </a-col>
                                        <a-col :span="4">
                                            <a-checkbox value="C" class="small-checkbox">C</a-checkbox>
                                        </a-col>
                                        <a-col :span="4">
                                            <a-checkbox value="D" class="small-checkbox">D</a-checkbox>
                                        </a-col>
                                        <a-col :span="4">
                                            <a-checkbox value="E" class="small-checkbox">E</a-checkbox>
                                        </a-col>
                                    </div>
                                </a-checkbox-group>
                            </div>
                        </div>

                        <div class="ml-6 pb-4">
                            <div v-for="number in 30" :key="number" style="padding: 0px; height: 17px;">
                                <a-checkbox-group v-model:value="marcadas[number + 29]" style="width: 200px; height: 18px;"
                                    :style="number % 2 == 0 ? { backgroundColor: '#00000007' } : ''">
                                    <div
                                        style="padding: 0px; height: 20px; margin-bottom: -20px; display: flex; align-items: center; overflow: hidden;">
                                        <a-col :span="4" style="margin-right: -10px;">
                                            <div style="width: 20px; font-size: .8rem;">{{ number + 30 }}</div>
                                        </a-col>
                                        <a-col :span="4">
                                            <a-checkbox value="A" class="small-checkbox">A</a-checkbox>
                                        </a-col>
                                        <a-col :span="4">
                                            <a-checkbox value="B" class="small-checkbox">B</a-checkbox>
                                        </a-col>
                                        <a-col :span="4">
                                            <a-checkbox value="C" class="small-checkbox">C</a-checkbox>
                                        </a-col>
                                        <a-col :span="4">
                                            <a-checkbox value="D" class="small-checkbox">D</a-checkbox>
                                        </a-col>
                                        <a-col :span="4">
                                            <a-checkbox value="E" class="small-checkbox">E</a-checkbox>
                                        </a-col>
                                    </div>
                                </a-checkbox-group>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
        
<script setup>
import { Head } from '@inertiajs/vue3';
import { watch, computed, defineProps, ref, unref } from 'vue';
import { DownOutlined, SearchOutlined, EyeOutlined, FormOutlined, DeleteOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';

const id_resp = defineProps(['id_resp']);

const tipo = ref('P')
const dni = ref("70757838")
const nombreP = ref("JHON ARIEL")
const paterno = ref("LUQUE")
const materno = ref("CUSACANI")
const area_examen = ref(4)

const notificacion = (type, titulo, mensaje) => { notification[type]({ message: titulo, description: mensaje, }); };

//id_resp
const ficha_respuesta = ref({
    dni:null,
    nombre: null,
    paterno:null,
    materno:null,
    tipo_id:null,
    respuestas:null,
}); 

const getFichaRespuesta =  async () => {
    let res = await axios.get("/get-ficha-respuesta/" + id_resp.id_resp );
    ficha_respuesta.value = res.data.datos;
    respuestas.value = res.data.datos.respuestas;
    respp();
}

const marcadas = null;

const respuestas = ref("DDDBBAA   DD A DD AA")



const respp =  () => {
    let str = "ABBB C";
    let charsArray = str.split('');
    let resultArray = [];

    for (let char of respuestas.value) {
        if (char !== ' ' && char !== '*') {
            resultArray.push([char]);
        } else {
            if (char === ' ') {
                resultArray.push([]);
            } else {
                resultArray.push(['A', 'B', 'C', 'D', 'E']);
            }
        }
    }
    marcadas.value = resultArray;
}




const visible = ref(false);     
getFichaRespuesta();

</script> 
    
<style scope>
.small-checkbox {
    margin-top: -5px;
    font-size: 1.2rem;
    transform: scale(.8);
}


</style>