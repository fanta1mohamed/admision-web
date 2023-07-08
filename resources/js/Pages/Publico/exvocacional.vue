<template>
  <LayoutExVocacional>
  <div v-if="ex == 1" class="p-5" style="padding: 30px 60px ;">
      <div v-if="datos.length > 0" class="">
        <h1>DNI: {{ datos[0].nro_doc }} </h1>  
        <h1>Nombre: {{ datos[0].nombres }} {{ datos[0].primer_apellido }} {{ datos[0].segundo_apellido }} </h1> 
        <h1>Programa: {{ datos[0].nombre }} </h1> 
      </div>
  
      <!-- {{ preguntasperfiles }} -->

      <div class="mb-3" v-for="(item, index) in preguntas" :key="item.id">
          <h3 >{{ index + 1 }}. {{  item.pregunta }}</h3>
  
          <div v-for="respuesta in item.respuestas" :key="respuesta.ide">
              <a-radio-group v-model:value="respuestas[index]">
                  <a-radio :value="respuesta">{{ respuesta.respuesta }}</a-radio>
              </a-radio-group>
          </div>
      </div>
      <div class="mb-3" v-for="(item, index) in preguntasperfiles" :key="item.id">
          <h3 >{{ index + 11 }}. {{  item.pregunta }}</h3>
  
          <div v-for="respuesta in item.respuestas" :key="respuesta.ide">
              <a-radio-group v-model:value="respuestas[index+10]">
                  <a-radio :value="respuesta">{{ respuesta.respuesta }}</a-radio>
              </a-radio-group>
          </div>
      </div>
      <div> 
          <a-button type="primary" @click="saveVocacional()"> Terminsar examen vocacional </a-button>
      </div>
  </div>
  
  <div v-else>
    <div style="margin:auto; min-height: calc(100vh - 160px); display: flex; justify-content: center; align-items: center;">
      <a-card style="min-width: 320px;">
          <div style="margin-bottom: 7px;"><label>N° Documento</label></div>
          <a-form-item>
            <a-input v-model:value="dni" :maxlength="12" placeholder="N° Documento"/>
          </a-form-item>

          <div style="margin-bottom: 7px;"><label>Codigo</label></div>
          <a-form-item>
            <a-input v-model:value="codigo" :maxlength="12" placeholder="codigo"/>
          </a-form-item>

          <div style="display: flex; justify-content: center; margin-top: 20px;">
            <a-button type="primary" @click="getDatos()">Iniciar Examen</a-button>
          </div>
      </a-card>
    </div>
  </div>

    <a-modal v-model:visible="visible" @ok="handleOk">
      <h1>FELICITACIONES FINALIZÓ SU EXAMEN VOCACIIONALCON EXITO</h1>
      <a-button @click="visible = false">OK</a-button>
    </a-modal>

  </LayoutExVocacional>
  </template>
  
<script setup>
  import LayoutExVocacional from '@/Layouts/LayoutExVocacional.vue';
</script>
  
  <script>
  import { ref, watch } from 'vue';
  import { DownOutlined } from '@ant-design/icons-vue';

  
  export default {
    props: ['id_postulante', 'actualiza', 'dni'],
    
    data() {
      return {
        visible: false,
        preguntas: null,
        preguntasperfiles: null,
        respuestas: [],
        datos: [],
        ex: 0,
        dni:'',
        codigo:'',
      };
    },
    
    mounted() {
      // this.getDatos();
    },
    
    methods: {    
      async getPreguntas() {
        const res = await axios.post("get-preguntas", { id_postulante: this.datos[0].id, id_programa: this.datos[0].id_vocacional});
        this.preguntas = res.data.datos;
      },
      async getPreguntasPerfiles() {
        const res = await axios.post("get-preguntas-perfiles", { id_postulante: this.datos[0].id });
        this.preguntasperfiles = res.data.datos;
      },
  
      async getDatos() {
        const res = await axios.post("get-datos-examen2", { dni: this.dni  });
        this.datos = res.data.datos;
        this.getPreguntas();
        this.getPreguntasPerfiles()
        this.ex = 1;
      },

      async saveVocacional() {
        let avn  = 110;
        try {
          const response = await axios.post('save-vocacional', {
              id_postulante: this.datos[0].id,
              id_examen: this.datos[0].id_vocacional,
              respuestas: this.respuestas,
              actualizar: this.actualiza,
              proceso: 4,
              name:"Examen vocacional completado",
              nro:7,
              dni: this.datos[0].nro_doc,
              avance: avn
          });
          
          this.respuestas = [];
          this.ex = 0;
          this.visible = true;
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
  
  
  <style scope>
  .headVocalional{
      height: 200px;
      background-repeat: no-repeat;
      background-size: cover;
      background-image: linear-gradient(#174dff38, #174dff38), url('../../../../assets/imagenes/administración.JPG');
  }
  .logoVocalional{
      display: flex;
      padding-left: 20px;
      padding-right: 20px;
      justify-content: left;
      align-items: center;
      color: white;
  }
  .logoVocalionalAD {
      font-weight: 700; color: white; font-size: 1.36rem;
  }
  .container-pre{
      margin-bottom: -8px; margin-top: 10px;
  }
  
  .titulo-pre span{
      color: white;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 2.1rem;
  }
  
  .line-preV{
      width: 20px; border: solid 2px #ff0015e8;
      margin: 0px 10px;
  }
  
  
  
  @media (min-height: 680px), screen and (orientation: portrait) {
      .logoVocalional img{ width: 26px; }
      .logoVocalional div{margin-top: -10px;}
      .logoVocalional span{ font-size: 0.6rem; letter-spacing: 0.001rem !important; }
      .logoVocalionalAD{ font-size: 0.76rem; }
      .logoVocalional .container-pre{ margin-bottom: -8px; margin-top: 5px; }
      .titulo-pre{ margin-top: 30px;}
      .titulo-pre span{ font-size: 1.3rem;  }
      .line-pre {         width: 50px; border: solid 1px #ff0015e8; }
  
  }
  
  </style>
    