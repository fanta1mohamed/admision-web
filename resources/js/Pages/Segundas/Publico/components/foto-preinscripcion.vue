<template>
    <div>
      <!-- Listado de TÍTULO PROFESIONAL -->
      <div class="mt-0">
        <div class="flex justify-between border-b-2" style="border-bottom: solid 1px #d9d9d9; padding-bottom: 8px;">
            <div><span style="font-weight: bold; font-size: 1rem; color:teal;">FOTO POSTULANTE</span></div>
            <div style="margin-top: -5px;"><a-button @click="abrirModal()">Agregar</a-button></div>
        </div>
        <div class="mt-3">
            <div class="mb-2" v-for="item in dnis" :key="item.id">
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <div class="flex justify-between">
                            <div>
                                <div v-if="item.id_tipo == 8">
                                    <span class="font-bold" style="font-size:1rem;">FOTO DEL POSTULANTE</span>
                                </div>
                            </div>
                            <div class="flex" style="margin-top: 0px;">
                                <a-button @click="abriPDf(item.url)" class="mr-2" style="width: 20px; height: 20px; padding-left: 3px; border: solid #1a2843 1px;">
                                    <!-- Icono ver -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </a-button>
                                <a-button @click="abrirEditar(item)" class="mr-2" style="width: 20px; height: 20px; padding-left: 3px; border: solid #1a2843 1px;">
                                    <!-- Icono editar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#1a2843" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                    </svg>
                                </a-button>
                                <a-button danger @click="eliminarTitulo(item.id)" style="width: 20px; height: 20px; padding-left: 3px;">
                                    <!-- Icono eliminar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </a-button>
                            </div>
                        </div>
                    </a-col>
                </a-row>            
            </div>
        </div>
      </div>
  
      <!-- Modal principal para registro (similar al de PDF pero para imágenes) -->
      <a-modal v-model:visible="modaltitulo" width="700px" class="w-full md:w-3/4" title="Registro de título" @ok="handleOk">
        <a-form ref="formDatos" name="form" :model="form" :rules="formRules">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <label>Tipo<span style="color:red;">*</span></label>
                    <a-form-item name="tipo" :rules="[{ required: true, message: 'Este campo es obligatorio' }]">
                        <a-select ref="select" v-model:value="form.tipo" style="width: 100%" :options="tipos" @focus="focus" @change="selecionarTipo" />
                    </a-form-item>
                </a-col>
  
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <label>Observación<span style="color:red;"></span></label>
                    <a-form-item name="observacion">
                        <a-input v-model:value="form.observacion" style="height: 32px;" />
                    </a-form-item>
                </a-col>
  
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <label>Archivo de imagen (Max. 2mb)<span style="color:red;">*</span></label>
                    <!-- En este caso se usa un input file que dispara el recorte -->
                    <a-form-item v-if="form.fileList.length === 0" name="fileList" :rules="[{ required: true, message: 'Este campo es obligatorio' }]">
                        <input type="file" accept="image/*" @change="onFileChange" />
                        <div class="mt-2" v-if="croppedImage">
                            <img :src="croppedImage" alt="Vista previa" style="max-width: 100%;" />
                        </div>
                    </a-form-item>
                    <a-form-item v-else name="fileList">
                        <input type="file" accept="image/*" @change="onFileChange" />
                        <div class="mt-2" v-if="croppedImage">
                            <img :src="croppedImage" alt="Vista previa" style="max-width: 100%;" />
                        </div>
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <div class="flex justify-end">
                <a-button type="primary" :disabled="!croppedImage" :loading="loading" style="margin-top: 16px" @click="save">
                    {{ loading ? 'Subiendo...' : 'Guardar datos' }}
                </a-button>
            </div>
        </template>
      </a-modal>
  
      <!-- Modal de recorte de imagen -->
      <a-modal v-model:open="openCropperModal" title="Recortar foto" :footer="false">
        <cropper
            v-if="imageSource"
            :src="imageSource"
            :stencil-props="stencilProps"
            :default-size="defaultSize"
            @change="onCrop"
        />
        <div style="margin-top: 10px; text-align: right;">
            <a-button type="primary" @click="confirmCrop">Confirmar recorte</a-button>
        </div>
      </a-modal>
  
      <!-- Modal para ver la imagen (opcional) -->
      <a-modal v-model:visible="modalPDF" title="Registro de título" style="min-width: 400px;">
        <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <div class="mt-2" v-if="pdfItem">
                    <img :src="baseUrl+'/'+pdfItem" alt="Imagen" style="width: 100%;" />
                </div>
            </a-col>
        </a-row>
        <template #footer>
            <div class="flex justify-end">
                <a-button @click="modalPDF = false">Aceptar</a-button>
            </div>
        </template>
      </a-modal>
    </div>
  </template>
  
  <script setup>
  import { ref, reactive } from 'vue';
  import { message } from 'ant-design-vue';
  import axios from 'axios';
  import { Cropper } from 'vue-advanced-cropper';
  import 'vue-advanced-cropper/dist/style.css';
  const baseUrl = window.location.origin;
  
  const props = defineProps(['id_proceso', 'dni']);
  
  // Variables para el listado de títulos
  const dnis = ref([]);
  const tipos = ref([{ value: 8, label: "FOTO DEL POSTULANTE" }]);
  const modaltitulo = ref(false);
  const modalPDF = ref(false);
  const pdfItem = ref(null);
  const loading = ref(false);
  
  // Variables del formulario
  const formDatos = ref();
  const form = reactive({  
    id: null,
    observacion: "",
    tipo: 8,
    fileList: [] // Se almacenará el Blob del archivo de imagen recortado
  });
  
  // Variables para el recorte de imagen
  const openCropperModal = ref(false);
  const imageSource = ref(null);
  const croppedImage = ref(null); // Almacenará la imagen recortada (dataURL)
  const stencilProps = { aspectRatio: 5 / 6 };
  const defaultSize = ({ imageSize }) => ({ width: 240, height: 288 });
  
  // Funciones para abrir y editar registros
  const abrirModal = () => { 
    modaltitulo.value = true;
  };
  
  const abrirEditar = (item) => {
    form.id = item.id;
    form.observacion = item.observacion;
    // Asumimos que item.url contiene la ruta de la imagen recortada
    croppedImage.value = baseUrl + '/' + item.url;
    // Si se requiere, se podría asignar un valor a form.fileList, pero usualmente no es posible reconstruir el archivo desde la URL.
    modaltitulo.value = true;
  };

  const eliminarTitulo = async (id) => {
    let res = await axios.get("/eliminar-documentos-segundas/" + id);
    if (res.data.estado === true) {
        getdnis();
    } else {
        console.log("Ocurrió un error");
    }
  };
  
  const abriPDf = (pdf) => {
    pdfItem.value = pdf;
    modalPDF.value = true;
  };
  
  // Función para obtener el listado de registros
  const getdnis = async () => {
    const response = await axios.post('/get-documentos-segundas-postulante-fotos', { dni: props.dni, id_proceso: props.id_proceso });
    if (response.data.estado === true){
        dnis.value = response.data.datos;
    } else { 
        console.log("No se encontraron datos"); 
    }
  };
  
  getdnis();
  
  // Función que se dispara al seleccionar un archivo de imagen
  const onFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imageSource.value = e.target.result;
            openCropperModal.value = true;
        };
        reader.readAsDataURL(file);
    }
  };
  
  // Evento del cropper que actualiza la imagen recortada
  const onCrop = ({ canvas }) => {
    if (canvas) {
        croppedImage.value = canvas.toDataURL('image/jpeg');
    }
  };
  
  // Al confirmar el recorte se convierte la imagen en Blob y se asigna al formulario
  const confirmCrop = () => {
    const blob = dataURLtoBlob(croppedImage.value);
    form.fileList = [blob];
    openCropperModal.value = false;
  };
  
  // Función auxiliar para convertir dataURL a Blob
  function dataURLtoBlob(dataurl) {
    var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
    while(n--){
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new Blob([u8arr], { type: mime });
  }
  
  // Función para guardar los datos y enviar el archivo recortado al servidor
  const save = async () => {
    // Se puede agregar validación del formulario aquí
    const formData = new FormData();
    if(form.fileList[0]) { 
      formData.append('file', form.fileList[0]); 
    }
    if(form.id != null) { 
      formData.append('id', form.id);
    }
    formData.append('id_proceso', props.id_proceso);    
    formData.append('dni', props.dni);    
    formData.append('observacion', form.observacion);
    formData.append('tipo', form.tipo);
  
    try {
        loading.value = true;
        await axios.post("/save-documentos-segundas", formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                Authorization: 'Bearer your-auth-token',
            },
            onUploadProgress: progressEvent => {
                // Se puede actualizar la barra de progreso aquí si se desea
            }
        });
        modaltitulo.value = false;
        loading.value = false;
        message.success('¡Archivo cargado exitosamente!');
        getdnis();
    } catch (error) {
        loading.value = false;
        message.error('Error al cargar el archivo.');
        console.error('File upload failed:', error);
    }
  };
  
  // Función dummy para el botón "OK" del modal (si se usa)
  const handleOk = () => {};
  
  const selecionarTipo = value => {
    console.log(`selected ${value}`);
  };
  
  const focus = () => {};
  
  const handleRemove = () => {
    croppedImage.value = null;
    form.fileList = [];
  };
  </script>
  
  <style scoped>
  img {
    max-width: 100%;
  }
  </style>
  