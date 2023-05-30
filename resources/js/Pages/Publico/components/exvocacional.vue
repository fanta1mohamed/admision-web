<template>
<div>
    <div class="headVocalional">
        <div class="logoVocalional">  
            <div> <img src="../../../../assets/imagenes/logotiny.png" width="45"/> </div>
            <div class="x"> 
                <div class="container-pre"><span style="letter-spacing: 0.1rem;">DIRECCIÓN DE</span>
            </div> 
            <h1 class="logoVocalionalAD" > ADMISIÓN </h1> </div>
            <div> <img src="../../../../assets/imagenes/logoDAD.png" width="45" /> </div>
        </div>
        <div class="flex justify-center titulo-pre ">
            <div>  
                <span> Examen Vocacional </span>
                <div class="flex justify-center items-center"> <hr class="line-preV"><div style="font-weight: bold; color: white; font-size: 1.1rem"> CEPREUNA </div>  <hr class="line-preV"> </div>
            </div>
        </div>



        <div style="background:white; border-radius: 20px 20px 0px 0px; margin-top:20px;  padding: 20px; padding-top:30px;">
            <div class="mb-3" v-for="(item, index) in preguntas" :key="item.id">
                <h3 >{{ index + 1 }}. {{  item.pregunta }}</h3>

                <div v-for="respuesta in item.respuestas" :key="respuesta.ide">
                    <a-radio-group v-model:value="respuestas[index]">
                        <a-radio :value="respuesta">{{ respuesta.respuesta }}</a-radio>
                    </a-radio-group>
                </div>

            </div>

            <div> 
                <a-button type="primary" @click="saveVocacional()"> Terminsar examen vocacional </a-button>
            </div>
        </div>



        <!-- {{ respuestas }}

        {{ datos }} -->


    </div>
</div>
</template>


<script>
import { ref, watch } from 'vue';
import { DownOutlined } from '@ant-design/icons-vue';

export default {
  props: ['id_postulante'],
  
  data() {
    return {
      preguntas: null,
      respuestas: [],
      datos: null
    };
  },
  
  mounted() {
    this.getDatos();
  },
  
  methods: {    
    async getPreguntas() {
      const res = await axios.post("get-preguntas", { id_postulante: this.id_postulante, id_programa: this.datos[0].id_vocacional });
      this.preguntas = res.data.datos;
    },

    async getDatos() {
      const res = await axios.post("get-datos-examen", { id_postulante: this.id_postulante  });
      this.datos = res.data.datos;
      this.getPreguntas()
    },

    async saveVocacional() {
      let avn  = 110;
      try {
        const response = await axios.post('save-vocacional', {
            id_postulante: this.id_postulante,
            id_examen: this.datos[0].id_vocacional,
            respuestas: this.respuestas,
            actualizar: this.actualiza,
            proceso: 4,
            name:"Registro de datos del padre o tutor",
            nro:7,
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
  