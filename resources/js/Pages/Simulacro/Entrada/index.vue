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
                                <span style="font-size: 1.2rem; font-weight: bold;"> Ingresaron: 101</span>
                            </div>
                            <div>
                                <span style="font-size: 1.2rem; font-weight: bold;"> Observados: 0</span>
                            </div>
                        </div>
                        <div> 
                            <div>
                                Avance de usuario
                            </div>
                            <div>
                                <span style="font-size: 1.4rem; font-weight: bold;"> 72 </span>
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
                <div class="flex justify-center mb-4">
                    <span style="font-size: 1.4rem; font-weight: bold;">Jhon Ariel Luque Cusacani</span>
                </div>

                <div class="flex justify-center mb-4">
                    <div style="max-width: 580px; text-align: center;">
                        <span style="font-size: 1.1rem; text-transform: capitalize;">
                            {{ programa.toLowerCase() }}
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
                                    <div><span style="font-size: 1.4rem; font-weight: bold;">201</span></div>
                                    <div>Aula</div>
                                </div>
                            </div>
                        </a-col>
                        <a-col  :xs="8" :sm="8" :md="8" :lg="8">
                            <div class="py-2" style="border-radius: 12px; border: 1px solid #d9d9d9;">
                                <div style="text-align: center;">
                                    <div><span style="font-size: 1.4rem; font-weight: bold;">15</span></div>
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
                                        <span style="font-size: 1.2rem; ">
                                            <strong style="font-size: 1.2rem;">Dependencia:</strong> Escuela profesional de ingeniería mecanica elecetrica y sisytemas de los andes del perus
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a-col>
                    </a-row>

                </div>

            </a-col>
            </a-row>
        </a-col>
    </a-row>
</template>

<script setup>
import { ref } from 'vue';

const dni = ref("");
//const programa = ref("EDUCACIÓN SECUNDARIA DE LA ESPECIALIDAD DE LENGUA, LITERATURA, PSICOLOGÍA Y FILOSOFÍA")
//const programa = ref("EDUCACIÓN SECUNDARIA DE LA ESPECIALIDAD DE LENGUA, LITERATURA, PSICOLOGÍA Y FILOSOFÍA")
//const programa = ref("EDUCACIÓN SECUNDARIA DE LA ESPECIALIDAD DE LENGUA, LITERATURA, PSICOLOGÍA Y FILOSOFÍA")
const programa = ref("INGENIRÍA DE SISTEMAS")


</script>