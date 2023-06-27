<template>


  <div style="width: 100%; margin-top: 5px; ">
  
  <a-card style="padding-top: -5px; padding-bottom:0px;" class="cardInicio">
    <div>
  
      <div style="margin-bottom: 25px; margin-top: 10px; ">
        <h1 style="font-size: 1.1rem;"> Datos del colegio</h1>
      </div>
  
      <a-row :gutter="[16, 0]" class="form-row">
        <a-col :span="24" :md="16" :lg="12" :xl="16" :xxl="6">
          <a-form-item>
            <div><label>Año de egreso: </label></div>
            <a-input v-model:value="anio_egreso" placeholder="Año egreso" />
          </a-form-item>
        </a-col>
        <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
          <a-form-item>
            <div><label>Pais:</label></div>
              <a-select
                  ref="select"
                  v-model:value="pais"
                  style="width: 100%" >
                  <a-select-option :value="125">PERÚ</a-select-option>
                  <a-select-option :value="23">BOLIVIA</a-select-option>
                  <a-select-option :value="184">VENEZUELA</a-select-option>
                  <a-select-option :value="11">ARGENTINA</a-select-option>
              </a-select>  
          </a-form-item>
        </a-col>
      </a-row>
  
      <a-row :gutter="[16, 0]" class="form-row">
        <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
          <a-form-item>
            <div><label>Departamento: </label></div>
  
            <a-auto-complete
                v-model:value="dep"                
                :options="departamentos"
                @select="onSelectDepartamentos"
                >
                <a-input
                    placeholder="Departamento"
                    v-model:value="buscarDep"
                    @keypress="handleKeyPress"
                    >
                <template #suffix>
                <a-tooltip title="Extra information">
                  <down-outlined />
                </a-tooltip>
              </template>
              </a-input>
            </a-auto-complete>
  
          </a-form-item>
        </a-col>
        <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
          <a-form-item>
            <div><label>Provincia:</label></div>
            <a-auto-complete
                v-model:value="prov"                
                :options="provincias"
                @select="onSelectProvincias"
            >
                <a-input
                    placeholder="Provincia"
                    v-model:value="buscarProv"
                    @keypress="handleKeyPress"
                >
                <template #suffix>
                <a-tooltip title="Extra information">
                  <down-outlined />
                </a-tooltip>
              </template>
              </a-input>
            </a-auto-complete>
          </a-form-item>
        </a-col>
        <a-col :span="24" :md="16" :lg="12" :xl="8" :xxl="6">
          <a-form-item>
            <div><label>Distrito:</label></div>
  
            <a-auto-complete
                v-model:value="dist"                
                :options="distritos"
                @select="onSelectDistritos"
            >
                <a-input
                    placeholder="Provincia"
                    v-model:value="buscarDist"
                    @keypress="handleKeyPress"
                >
                <template #suffix>
                <a-tooltip title="Extra information">
                  <down-outlined />
                </a-tooltip>
              </template>
              </a-input>
            </a-auto-complete>
  
          </a-form-item>
        </a-col>
      </a-row>
  
      <a-row :gutter="[16, 0]" class="form-row">
        <a-col :span="24" :md="24" :lg="24" :xl="24" :xxl="24">
          <a-form-item>
            <div><label>Nombre de colegio: {{  colegio  }}</label></div>

              <a-select
                v-model:value="colegio"
                style="min-width: 300px"
                :options="colegios"
                @change="handleChangeCole"
              ></a-select>
              
          </a-form-item>
        </a-col>
      </a-row>
  
    </div>
  
  </a-card>
  </div>
</template>
  
<script>
import { ref, watch } from 'vue';
import { DownOutlined } from '@ant-design/icons-vue';

export default {
  props: ['id_postulante', 'actualiza'],
  
  data() {
    return {
      anio_egreso: null,
      colegio: null,
      direccion: "",
      pais: 125,
      departamentos: [],
      dep: null,
      buscarDep: "",
      depseleccionado: '',
      provincias: [],
      prov: null,
      buscarProv: "",
      provseleccionada: null,
      distritos: [],
      dist: null,
      buscarDist: "",
      distseleccionado: '', 
      colegios: [],  
    
      actualizar: this.actualiza,
      proceso: 4

    };
  },
  
  watch: {
    buscarDep(newValue, oldValue) {
      this.getDepartamentos();
    },
    
    buscarProv(newValue, oldValue) {
      this.getProvincias();
    },
    distseleccionado(){
      this.getColegios()
    }

  },
  
  mounted() {
    this.getUbigeo();
    this.getDepartamentos();
  },
  
  methods: {
    async getDepartamentos() {
      const res = await axios.post("get-departamentos-codigo?page=", { term: this.buscarDep });
      this.departamentos = res.data.datos.data;
    },
    
    async getProvincias() {
      const res = await axios.post("get-provincias-codigo?page=", { departamento: this.depseleccionado });
      this.provincias = res.data.datos;
    },
    
    async getDistritos() {
      const res = await axios.post("get-distritos-codigo?page=", { departamento: this.depseleccionado, provincia: this.provseleccionada });
      this.distritos = res.data.datos;
    },
    
    async getColegios() {
      const res = await axios.post("get-colegio-distrito", { 
        ubigeo_cole: this.depseleccionado+this.provseleccionada+this.distseleccionado  
       });
      this.colegios = res.data.datos;
    },

    
    async getUbigeo() {
      const res = await axios.post("get-ubigeo-colegio", { id_postulante: this.id_postulante });
      this.anio_egreso = res.data.datos[0].egreso;
      this.colegio = res.data.datos[0].id;
      this.dep = res.data.datos[0].departamento;
      this.depseleccionado = res.data.datos[0].dep;
      this.prov = res.data.datos[0].provincia;
      this.provseleccionada = res.data.datos[0].prov;
      this.dist = res.data.datos[0].distrito;
      this.distseleccionado = res.data.datos[0].dist;
      // this.dist = res.data.datos[0].value;
    },

    onSelectDepartamentos (value, option) {
      this.depseleccionado = option.key;
      this.getProvincias()
    },

    onSelectProvincias (value, option) { 
      this.provseleccionada = option.key;
      this.getDistritos()
    },

    onSelectDistritos (value, option){
      this.distseleccionado = option.key;
      this.getColegios()
    }, 

    async ejecutarMetodo() {
      try {
        const response = await axios.post('save-postulante-colegio', {
          id:  this.id_postulante,
          anio_egreso: this.anio_egreso,
          colegio: this.colegio,
          
          actualizar: this.actualiza,
          proceso: 4
          // Datos a enviar en el cuerpo de la solicitud POST
        });

        if (this.actualiza === 'si') {
          return  1 ;
        } else {
          return 0 ;
        }
    
      } catch (error) {
        
        console.error(error);
      }


    },

  }
};
</script>