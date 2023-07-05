<template>

<a-card style="padding-top: -5px; padding-bottom:0px;" class="cardInicio">
  <a-form
    ref="form" :model="form"
    name="formapoderado"
    @finish="onFinish"
    @finishFailed="onFinishFailed"
  >
    <div>
      <div style="margin-bottom: 25px; margin-top: 10px;">
          <h1 style="font-size: 1.1rem;" v-if="tipex === 1" > Datos del padre</h1>
          <h1 style="font-size: 1.1rem;" v-else> Datos de la madre</h1>
      </div>

      <a-radio-group v-if="tipex === 1"  v-model:value="form.tipo_apoderado" class="flex justify-end" style="display: flex; width: yellow;" name="radioGroup">
          <a-radio :value="1">Padre</a-radio>
          <a-radio :value="3">Tutor</a-radio>
      </a-radio-group>

      <a-row :gutter="[16, 0]" class="form-row">
          <a-col :span="24" :md="16" :lg="12" :xl="12" :xxl="6">
            <a-form-item
              name="dni"
              :rules="[{ required: true, message: 'Ingresa el DNI', trigger: 'change' },
              { min: 8, message: 'El dni debe tener 8 digitos', trigger: 'blur',},]"
              >
              <div><label>N° Documento</label></div>
              <a-input ref="myDni" v-model:value="form.dni" :maxlength="12" placeholder="" />
          </a-form-item>
          </a-col>
          <a-col :span="24" :md="16" :lg="12" :xl="12" :xxl="6">
            <a-form-item
              name="nombres"
              :rules="[{ required: true, message: 'Ingresa los nombres', trigger: 'blur' }]"
              >
              <div><label>Pre nombres:</label></div>
              <a-input v-model:value="form.nombres"/>
            </a-form-item>
          </a-col>

          <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
            <a-form-item
              name="paterno"
              :rules="[{ required: true, message: 'Ingresa el primer apellido', trigger: 'blur' }]"
              >
              <div><label>Primer apellido:</label></div>
              <a-input v-model:value="form.paterno" />
          </a-form-item>
          </a-col>
      </a-row>

      <a-row :gutter="[16, 0]" class="form-row">
          <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
            <a-form-item>
              <div><label>Segundo Apellido:</label></div>
              <a-input v-model:value="form.materno" />
          </a-form-item>
          </a-col>
      </a-row>

    </div>

  </a-form>

</a-card>

</template>

<script>
export default {
  props: ['id_postulante','tipex','actualiza'],
  
  data() {
    return {
      form: {
        id: null,
        tipo_apoderado: 1,
        dni: null,
        nombres: null,
        paterno: null,
        materno: null,
        actualizar: this.actualiza,
        proceso: 4
      },
    };
  },
  
  mounted() {
    this.getApoderado();
  },
  
  methods: {    
    async getApoderado() {
      const res = await axios.post("get-apoderado", { id_postulante: this.id_postulante, tipo: this.tipex });
      if( res[0]){
        this.form.id = res.data.datos[0].id;
        this.form.tipo_apoderado = res.data.datos[0].tipo_apoderado;
        this.form.dni = res.data.datos[0].nro_documento;
        this.form.nombres = res.data.datos[0].nombres;
        this.form.paterno = res.data.datos[0].paterno;
        this.form.materno = res.data.datos[0].materno;
      }
    },

    async saveApoderado() {
      if(this.dni === null){
        this.$refs.myDni.focus();
        console.log('VACIIO');
        return;
      }

      let avn  = 65;
      if (this.tipo_apoderado === 3){
        avn = 80;
      }

      try {
        const response = await axios.post('save-postulante-apoderado', {
            id: this.form.id ,
            tipo_apoderado :this.form.tipo_apoderado,
            dni: this.form.dni,
            nombres: this.form.nombres,
            paterno: this.form.paterno,
            materno: this.form.materno,
            id_postulante: this.form.id_postulante,
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