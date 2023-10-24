<template>
  <aside  class="z-20 hidden w-64 overflow-y-auto md:block flex-shrink-0" style="background:#476175; width: 230px; border-right: 1px solid #00000010;">
 
    <div class="text-gray-200" style="font-family: Arial, Helvetica, sans-serif; font-weight: 400; font-size: 10pt;">
      <Link class="text-lg font-bold text-gray-100" :href="route('simulacro-inicio')">
            <div style="display: flex; align-items: center; justify-content: left; margin-top: 6px; margin-left: 20px; margin-bottom: 6px;">
                <div>
                    <img src="../../assets/imagenes/logotiny.png" width="35"/>
                </div>
                <div style="width: 1px; background-color: gray; margin: 0px 10px; height: 30px; margin-top: 8px;"></div>
                <div style="margin: 0px 0px; color: #f3f3f3;">
                    <div style="display: flex; justify-content: center; margin-top: 5px;"><span style="font-size:.80rem; letter-spacing: .21rem; font-weight: 500;">DIRECCIÓN DE</span></div>
                    <div style="display: flex; justify-content: center; margin-top: -8px;"><span style="font-size: 1.5rem; letter-spacing:.12rem; font-weight: 600;">ADMISIÓN</span></div>
                </div>
            </div>
      </Link>



      <ul class="mt-6 ">
        <div class="ml-6" style="margin-top: 40px; margin-bottom: 10px;"> <span style="font-weight: 700; letter-spacing: normal; font-family: Arial, Helvetica, sans-serif;"> INICIO </span> </div>

        <li class="relative px-0" :class="[route().current('simulacro-inicio')? 'activado':'']" style="margin-bottom: -15px;">
          <NavLink :href="route('simulacro-inicio')" :active="route().current('revisor')" style="padding: 10px 20px;">
            <template #icon>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            </span>
            </template>
            <span>
              Dashboard
            </span>
          </NavLink>
        </li>

        <li class="relative px-0" :class="[route().current('revisor-validacion')? 'activado':'']" style="margin-bottom: -15px;">
          <NavLink :href="route('revisor-validacion')" :active="route().current('revisor-validacion')" style="padding: 10px 20px;">
            <template #icon>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
              </span>
            </template>
            <span>
              Colegios
            </span>
          </NavLink>
        </li>

        <!-- <div class="ml-6" style="margin-top: 40px; margin-bottom: 10px;"> <span style="font-weight: 700; letter-spacing: normal; font-family: Arial, Helvetica, sans-serif;"> INSCRIPCION </span> </div> -->

        <!-- <li class="relative px-0" :class="[route().current('revisor-documentos')? 'activado':'']"  style="margin-bottom: -15px;">
          <NavLink :href="route('revisor-documentos')" :active="route().current('revisor-documentos')" style="padding: 10px 20px;">
            <template #icon>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
              </span>
            </template>
            <span>
              Revisar documentos
            </span>
          </NavLink>
        </li>

        <li class="relative px-0" :class="[route().current('foto-inscripcion')? 'activado':'']"  style="margin-bottom: -15px;">
          <NavLink :href="route('foto-inscripcion')" :active="route().current('foto-inscripcion')" style="padding: 10px 20px;">
            <template #icon>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-camera"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
              </span>
            </template>
            <span>
              Foto inscripcion
            </span>
          </NavLink>
        </li>


        <li class="relative px-0" :class="[route().current('revisor-impresion-inscripcion')? 'activado':'']"  style="margin-bottom: -15px;">
          <NavLink :href="route('revisor-impresion-inscripcion')" :active="route().current('revisor-impresion-inscripcion')" style="padding: 10px 20px;">
            <template #icon>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
              </span>
            </template>
            <span>
              Imprimir constancia
            </span>
          </NavLink>
        </li>


        <div class="ml-6" style="margin-top: 40px; margin-bottom: 10px;"> <span style="font-weight: 700; letter-spacing: normal; font-family: Arial, Helvetica, sans-serif;"> CONTROL BIOMETRICO </span> </div>

        <li class="relative px-0" :class="[route().current('foto-biometrico')? 'activado':'']"  style="margin-bottom: -15px;">
          <NavLink :href="route('foto-biometrico')" :active="route().current('foto-biometrico')" style="padding: 10px 20px;">
            <template #icon>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-camera"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
              </span>
            </template>
            <span>
              Foto biometrico
            </span>
          </NavLink>
        </li>


        <li class="relative px-0" :class="[route().current('revisor-imprimir')? 'activado':'']"  style="margin-bottom: -15px;">
          <NavLink :href="route('revisor-imprimir')" :active="route().current('revisor-imprimir')" style="padding: 10px 20px;">
            <template #icon>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
              </span>
            </template>
            <span>
              Imprimir constancia
            </span>
          </NavLink>
        </li>        

        <li class="relative px-0" :class="[route().current('revisor-postulantes')? 'activado':'']"  style="margin-bottom: -15px;">
          <NavLink :href="route('revisor-postulantes')" :active="route().current('revisor-postulantes')" style="padding: 10px 20px;">
            <template #icon>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
              </span>
            </template>
            <span>
              Postulantes
            </span>
          </NavLink>
        </li> -->


        <div style="border-bottom: 1px solid #f4f4f433; margin-top: 20px; margin-bottom: 20PX;"></div>
        <!-- <div class="ml-6" style="margin-bottom: 10px;"> <span style="font-weight: 700; letter-spacing: normal; font-family: Arial, Helvetica, sans-serif;"> CONFIGURACIÓN </span> </div>
 
        <li class="relative px-6 py-3" :class="[route().current('about')? 'activado':'']">
          <NavLink :href="route('about')" :active="route().current('about')">
            <template #icon>
              <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                   stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                <path
                    d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
              </svg>
            </template>
            Revisar Documentos
          </NavLink>
        </li> -->
        
      </ul>
    </div>
  </aside>
</template>

<script>
import NavLink from '@/Components/NavLink.vue'
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue'

export default {
  components: {
    NavLink,
    Link,
  },

  setup() {
    let showingTwoLevelMenu = ref(false)

    return {
      showingTwoLevelMenu
    }
  },
}
</script>

<style scoped>
.activado{
  background: white;
  background-color: #00000028;
  /* background-color: #3f484d; */
}

.activado span{ color: #ffffff; }
.borderlef{ border-left: 3px solid #dd02b9; }
</style>