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
        <div class="ml-6" style="margin-top: 40px; margin-bottom: 10px;"> <span style="font-weight: 700; letter-spacing: normal;"> INICIO </span> </div>

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

        <li class="relative px-0" :class="[route().current('simulacro-inscritos')? 'activado':'']" style="margin-bottom: -15px;">
          <NavLink :href="route('simulacro-inscritos')" :active="route().current('simulacro-inscritos')" style="padding: 10px 20px;">
            <template #icon>
              <span style="margin-left:2px; margin-right:-10px;">
                <!-- <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg> -->
                <svg fill="#FFFFFF" width="27" height="27" viewBox="0 0 24 24" id="register-16px" xmlns="http://www.w3.org/2000/svg">
                    <path id="Path_184" data-name="Path 184" d="M57.5,41a.5.5,0,0,0-.5.5V43H47V31h2v.5a.5.5,0,0,0,.5.5h5a.5.5,0,0,0,.5-.5V31h2v.5a.5.5,0,0,0,1,0v-1a.5.5,0,0,0-.5-.5H55v-.5A1.5,1.5,0,0,0,53.5,28h-3A1.5,1.5,0,0,0,49,29.5V30H46.5a.5.5,0,0,0-.5.5v13a.5.5,0,0,0,.5.5h11a.5.5,0,0,0,.5-.5v-2A.5.5,0,0,0,57.5,41ZM50,29.5a.5.5,0,0,1,.5-.5h3a.5.5,0,0,1,.5.5V31H50Zm11.854,4.646-2-2a.5.5,0,0,0-.708,0l-6,6A.5.5,0,0,0,53,38.5v2a.5.5,0,0,0,.5.5h2a.5.5,0,0,0,.354-.146l6-6A.5.5,0,0,0,61.854,34.146ZM54,40V38.707l5.5-5.5L60.793,34.5l-5.5,5.5Zm-2,.5a.5.5,0,0,1-.5.5h-2a.5.5,0,0,1,0-1h2A.5.5,0,0,1,52,40.5Zm0-3a.5.5,0,0,1-.5.5h-2a.5.5,0,0,1,0-1h2A.5.5,0,0,1,52,37.5ZM54.5,35h-5a.5.5,0,0,1,0-1h5a.5.5,0,0,1,0,1Z" transform="translate(-46 -28)"/>
                </svg>
              </span>
            </template>
            <span>
              Inscritos
            </span>
          </NavLink>
        </li>

        <li class="relative px-0" :class="[route().current('simulacro-participantes')? 'activado':'']" style="margin-bottom: -15px;">
          <NavLink :href="route('simulacro-participantes')" :active="route().current('simulacro-participantes')" style="padding: 10px 20px;">
            <template #icon>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
              </span>
            </template>
            <span>
              Participantes
            </span>
          </NavLink>
        </li>
        <li class="relative px-0" :class="[route().current('simulacro-pagos')? 'activado':'']" style="margin-bottom: -15px;">
          <NavLink :href="route('simulacro-pagos')" :active="route().current('simulacro-pagos')" style="padding: 10px 20px;">
            <template #icon>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
              </span>
            </template>
            <span>
              Pagos
            </span>
          </NavLink>
        </li>

        <div style="border-bottom: 1px solid #f4f4f433; margin-top: 20px;"></div>

        <div class="ml-6" style="margin-top: 20px; margin-bottom: 10px;"> <span style="font-weight: 700; letter-spacing: normal;"> MANTENIMIENTO </span> </div>

        <li class="relative px-0" :class="[route().current('simulacro-colegios')? 'activado':'']" style="margin-bottom: -15px;">
          <NavLink :href="route('simulacro-colegios')" :active="route().current('simulacro-colegios')" style="padding: 10px 20px;">
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
        
        <li class="relative px-0" :class="[route().current('simulacro-consulta-pagos')? 'activado':'']" style="margin-bottom: -15px;">
          <NavLink :href="route('simulacro-consulta-pagos')" :active="route().current('simulacro-consulta-pagos')" style="padding: 10px 20px;">
            <template #icon>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
              </span>
            </template>
            <span>
              Consultar pagos
            </span>
          </NavLink>
        </li>
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
  /* border-left: 3px solid #dd02b9; */
  /* background-color: #3f484d; */
}

.activado span{ color: #ffffff; }
/* .borderlef{ border-left: 3px solid #dd02b9; } */
</style>