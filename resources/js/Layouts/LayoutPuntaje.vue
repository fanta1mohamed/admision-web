<template>
  <a-layout style="min-height: 100vh;">
    <a-layout>
      <div class="headPre">
        <div class="flex justify-between mb-2 logo-container">
          <div class="logoPre">
            <img src="./../../assets/imagenes/logotiny.png" class="logo-img" />
            <div class="x">
              <div class="container-pre">
                <span class="puntajex">DIRECCIÓN DE</span>
              </div>
              <h1 class="logoPreAD">ADMISIÓN</h1>
            </div>
            <img src="./../../assets/imagenes/logoDAD.png" class="logo-img" />
          </div>
        </div>
        <div class="flex justify-center title-proceso-container">
          <span class="title-proceso text-center">EXAMEN {{ nombre }}</span>
        </div>
        <div class="flex justify-center titulo-pre">
          <div>
            <div class="flex justify-center" style="height: 40px; ">
              <hr class="line-pre mr-2"/>
              <span style="font-size: 1.4rem;">Resultados</span>
              <hr class="line-pre ml-2"/>
            </div>
          </div>
        </div>
      </div>
      <div style="background: #267ec5ea; height: 3px; width: 100%;"> </div>
      <a-layout-content class="content flex justify-center">
        <div class="container">
          <slot />
        </div>

      </a-layout-content>
    </a-layout>
  </a-layout>
</template>

<script setup>
import { defineProps } from 'vue';
const props = defineProps(['nombre']);

</script>
  
<style scoped>
  .headPre {
    background-image: linear-gradient(#008cdd71, #0b429667),
    url('./../../assets/imagenes/fondo-resultados.png');
    background-size: cover;
    height: 190px;
    margin-top: 0;
    padding: 10px;
  }
  
  .logoPre {
    display: flex;
    align-items: center;
    color: white;
  }
  
  .logo-img {
    width: 45px;
    margin-top: -10px;
  }
  
  .logoPreAD {
    font-weight: 700;
    color: white;
    font-size: 1.6rem;
  }
  
  .container-pre {
    margin-bottom: -8px;
    margin-top: 10px;
  }
  
  .titulo-pre div span {
    color: #e7e7e7;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 2.7rem;
  }
  
  .line-pre {
    margin-top: 15px;
    width: 25px;
    border: solid 2px crimson;
  }
  
  .title-proceso {
    font-size: 2.6rem;
    color: white;
    font-weight: bold;
  }
  
  .title-proceso-container {
    margin-top: -30px;
  }
  
  .header-bar {
    background: #267ec5ea;
    height: 30px;
    width: 100%;
  }
  
  .puntajex {
    font-size: 1.1rem;
  }
  .content {
    border-radius: 10px 10px 0 0;
    box-shadow: 1px 1px 1px 2px #a5a5a53d;
    min-height: calc(100vh - 200px);
    background: #e1e1e1;
  }
  
  @media (max-width: 768px) {
    .logo-img {
      width: 36px;
    }
  
    .logoPreAD {
      font-size: 1.3rem;
    }
  
    .puntajex {
        font-size: 0.9rem;
    }
    .title-proceso {
      font-size: 1.4rem;
    }
  
    .line-pre {
      width: 30px;
      border: solid 3px #ff0015e8;
    }
  
    .title-proceso-container {
      margin-top: 10px;
      margin-bottom: 0;
    }
  }
  
  @media (min-width: 1380px) {
    .title-proceso {
      font-size: 3.1rem;
    }
    .logo-img {
      width: 60px;
    }
    .puntajex {
        font-size: 1.1rem;
        letter-spacing: 0.13rem;
    }
    .logoPreAD {
        font-size: 1.9rem;
    }

  
  }
  </style>