<template>

<a-card style="padding-top: -5px; padding-bottom:0px;" class="cardInicio">
<div>

<div style="margin-bottom: 25px; margin-top: 10px;">
    <h1 style="font-size: 1.1rem;" v-if="tipex === 1" > Datos del padre</h1>
    <h1 style="font-size: 1.1rem;" v-else> Datos de la madre</h1>
</div>

<a-radio-group v-if="tipex === 1"  v-model:value="tipo_apoderado" class="flex justify-end" style="display: flex; width: yellow;" name="radioGroup">
    <a-radio :value="1">Padre</a-radio>
    <a-radio :value="3">Tutor</a-radio>
</a-radio-group>


<a-row :gutter="[16, 0]" class="form-row">
    <a-col :span="24" :md="16" :lg="12" :xl="12" :xxl="6">
    <a-form-item>
        <div><label>N° Documento</label></div>
        <a-input v-model:value="dni" placeholder="Basic usage" />
    </a-form-item>
    </a-col>
    <a-col :span="24" :md="16" :lg="12" :xl="12" :xxl="6">
    <a-form-item>
        <div><label>Pre nombres:</label></div>
        <a-input v-model:value="nombres"/>
    </a-form-item>
    </a-col>

    <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
    <a-form-item>
        <div><label>Primer apellido:</label></div>
        <a-input v-model:value="paterno" />
    </a-form-item>
    </a-col>
</a-row>

<a-row :gutter="[16, 0]" class="form-row">
    <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
    <a-form-item>
        <div><label>Segundo Apellido:</label></div>
        <a-input v-model:value="materno" />
    </a-form-item>
    </a-col>
</a-row>

</div>

</a-card>

</template>

<script>
import { ref, watch } from 'vue';
import { DownOutlined } from '@ant-design/icons-vue';

export default {
  props: ['id_postulante','tipex','actualiza'],
  
  data() {
    return {
      id: null,
      tipo_apoderado: null,
      dni: null,
      nombres: null,
      paterno: null,
      materno: null,

      actualizar: this.actualiza,
      proceso: 4
    };
  },
  
  mounted() {

    this.getApoderado();
  },
  
  methods: {    
    async getApoderado() {
      const res = await axios.post("get-apoderado", { id_postulante: this.id_postulante, tipo: this.tipex });
      this.id = res.data.datos[0].id;
      this.tipo_apoderado = res.data.datos[0].tipo_apoderado;
      this.dni = res.data.datos[0].nro_documento;
      this.nombres = res.data.datos[0].nombres;
      this.paterno = res.data.datos[0].paterno;
      this.materno = res.data.datos[0].materno;
    },


    async saveApoderado() {
      let avn  = 65;
      if (this.tipo_apoderado === 3){
        avn = 80;
      }

      try {
        const response = await axios.post('save-postulante-apoderado', {
            id: this.id ,
            tipo_apoderado :this.tipo_apoderado,
            dni: this.dni,
            nombres: this.nombres,
            paterno: this.paterno,
            materno: this.materno,
            id_postulante: this.id_postulante,
            actualizar: this.actualiza,
            proceso: 4,
            name:"Registro de datos del padre o tutor",
            nro:4,
            avance: avn
        });
        if (this.actualiza === 'si') {
          return  1 ;
        } else {
          return 0 ;
        }
    
      } catch (error) {
        // Manejar el error en caso de que la solicitud falle
        //console.error(error);
      }
    },

    async saveApoderadoMadre() {

      try {
        const response = await axios.post('save-postulante-apoderado', {
            id: this.id ,
            tipo_apoderado: this.tipex,
            dni: this.dni,
            nombres: this.nombres,
            paterno: this.paterno,
            materno: this.materno,
            id_postulante: this.id_postulante,
            actualizar: this.actualiza,
            proceso: 4,
            name:"Registro de datos de la madre",
            nro:5,
            avance: 80
        });
        if (this.actualiza === 'si') {
          return  1 ;
        } else {
          return 0 ;
        }

      } catch (error) {
        // Manejar el error en caso de que la solicitud falle
        //console.error(error);
      }
    },

  }
};
</script>