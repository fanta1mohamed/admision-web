<template>

<a-card style="border-bottom:1px #418dff solid; border-radius:6px;">
  <div class="flex" style="justify-content:space-between;">
    <div style="font-size: 1rem;"><h1>{{ titulo }}</h1> </div>
    <a-radio-group v-model:value="tipo_doc" class="flex justify-end" style="display: flex; width: yellow;" name="radioGroup">
      <a-radio :value="1">Dni</a-radio>
      <a-radio :value="2">Carnet Ext.</a-radio>
    </a-radio-group>
    
  </div>

  <div>
    <div style="margin-top:0px">
    <a-form-item>
      <div><label>Dni:{{ dni }}</label></div>
      <a-input v-model:value="dni" />
    </a-form-item>
    </div>

    <div style="margin-top:-10px">
    <a-form-item>
    <div><label>Primer apellido:{{ primer_apellido }}</label></div>
    <a-input v-model:value="primer_apellido" />
    </a-form-item>
    </div>

    <div style="margin-top:-10px">
    <a-form-item>
    <div><label>segundo apellido: {{ segundo_apellido }} </label></div>
    <a-input v-model:value="segundo_apellido" />
    </a-form-item>
    </div>

    <div style="margin-top:-10px; margin-bottom: -10px;">
    <a-form-item>
    <div><label>Nombres: {{ nombres }} </label></div>
        <a-input v-model:value="nombres" />
    </a-form-item>
    </div>

    <div style="display:flex; justify-content:flex-end; margin-top:16px;">
      <a-button type="primary" @click="ejecutarEnHijo" block style="height:40px"> Confirmar {{ titulo }} </a-button>
    </div>

    <!-- <div style="display:flex; justify-content:flex-end; margin-bottom:-20px; margin-top:-10px;">
          <a-button type="primary"> Confirmar </a-button>
    </div> -->

  </div>
</a-card>
</template>

<script>
import { ref, defineEmits } from 'vue';


export default {
  props: ['titulo','tipo_apoderado'],
  setup(_, { emit }) {
    

    defineEmits(['miEventoPersonalizado']);

    const emitirEvento = () => {
      emit('miEventoPersonalizado', /* datos opcionales */);
    };

    return {
      emitirEvento,
      // Resto de las variables y funciones que desees exponer
    };
  },

  data( ) {

    return {

      tipo_doc: 1, 
      dni: "",
      primer_apellido: "",
      segundo_apellido: "",
      t_apoderado: this.tipo_apoderado,
      nombres: "",
      res: null 
    }
  },

  methods: {

    async guardar() {
      try {
        const response = await axios.post('/guardar-apoderado', { 
          tipo_doc: this.tipo_doc,
          dni: this.dni,
          tipo_apoderado: this.tipo_apoderado,
          primer_apellido: this.primer_apellido,
          segundo_apellido: this.segundo_apellido,
          nombres: this.nombres,
          observacion: this.observacion,
         });
         this.res = response.data;
      } catch (error) {
        // Manejo de errores
      }
    },

    ejecutarFuncion() {
      this.guardar();
      const datos = 'Datos del hijo';
      this.$emit('hijo-clicado', datos);
      console.log('Hijo Clicado')
    }
  }
};
</script>