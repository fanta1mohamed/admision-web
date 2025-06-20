<template>
    <div :class="$page.props.showingMobileMenu ? 'block' : 'hidden'" @click="$page.props.showingMobileMenu = false"
         class="fixed inset-0 z-20 bg-black opacity-50 transition-opacity lg:hidden"></div>

    <div :class="$page.props.showingMobileMenu ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
         class="overflow-y-auto fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 transition duration-200 transform lg:translate-x-0 lg:static lg:inset-0" 
         style="width: 250px; margin-top: -10px;">
        <div class="flex justify-center items-start mt-2" style="align-items: center; justify-content: flex-start; height: 58px; margin-left: 25px;">
            <div class="flex items-center">                
                <div style="width: 33px; height: 33; border-radius: 0%; overflow: hidden;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Logo_UNAP.png/800px-Logo_UNAP.png">
                </div>

                <span class="mx-2 text-xl font-bold text-white">Admisión 2023</span>
            </div>
        </div>
        <hr style=" border-top: 0.5px solid #FFFFFF2D;">

        <div class="flex justify-center items-start mt-2" style="justify-content: flex-start; margin-left: 26px; padding: 10px 0px;" >
            <div class="flex items-center" style="align-items: center; margin-top: -10px;">
                <div style="width: 30px; height: 30; border-radius: 50%; overflow: hidden;">
                    <img src="https://definicion.de/wp-content/uploads/2019/07/perfil-de-usuario.png">
                </div>
                <div>
                    <div style="margin-bottom: -10px;">
                        <span class="mx-2 text-white" style="font-size: 0.9rem;">Ariel Luque Cusacani</span>
                    </div>
                    <span class="mx-2 text-white" style="font-size: .7rem; margin-top: -35px; color: #FFFFFFBD;">Administrador</span>
                </div>

            </div>
        </div>
        <hr style=" border-top: 0.5px solid #FFFFFF2D;">


        <!-- <div style="margin-top: 30px; margin-bottom: -10px;">
            <span class="mx-2 text-white" style="font-size: 1rem; margin-left: 25px; font-weight: bold; margin-top: -35px; color: #FFFFFFBD;">Administrador</span>
        </div> -->
        <nav class="mt-2 ml-2" x-data="{ isMultiLevelMenuOpen: false }" style="font-size: 0.9rem;">
            <nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                <template #icon>
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                </template>
                Dashboard
            </nav-link>

            <nav-link :href="route('users.index')" :active="route().current('users.index')" style="margin-top: 0px;">
                <template #icon>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </template>
                Users asdfsd
            </nav-link>

            <nav-link :href="route('about')" :active="route().current('about')" style="margin-top: 0;">
                <template #icon>
                    <svg class="w-4 h-4" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                         stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                </template>
                About us
            </nav-link>

            <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#" @click="showingTwoLevelMenu = !showingTwoLevelMenu" style="margin-top: 0;">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"></path>
                </svg>
                <span class="mx-3">Two-level menu</span>
            </a>
            <transition
                enter-to-class="transition-all duration-300 ease-in-out"
                enter-from-class="max-h-0 opacity-25"
                leave-from-class="opacity-100 max-h-xl"
                leave-to-class="max-h-0 opacity-0"
                style="margin-top:0px;"
                >
                <div v-show="showingTwoLevelMenu">
                    <ul class="overflow-hidden p-2 mx-4 mt-2 space-y-2 text-sm font-medium text-white bg-gray-700 bg-opacity-50 rounded-md shadow-inner"
                        aria-label="submenu">
                        
                        <li class="px-2 py-1 transition-colors duration-150">
                            <div style="display: flex;">
                                <div style="margin-right: 10px;">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <Link class="w-full" :href="route('dashboard')">Child menu</Link>
                            </div>
                        </li>                
                    </ul>

                    <ul class="overflow-hidden p-2 mx-4 mt-2 space-y-2 text-sm font-medium text-white bg-gray-700 bg-opacity-50 rounded-md shadow-inner"
                        aria-label="submenu">
                        
                        <li class="px-2 py-1 transition-colors duration-150">
                            <div style="display: flex;">
                                <div style="margin-right: 10px;">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <Link class="w-full" :href="route('dashboard')">Child menu</Link>
                            </div>
                        </li>                
                    </ul>
                </div>
            </transition>
        </nav>
    </div>

</template>

<script>
import NavLink from '@/Components/NavLink.vue';
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

::-webkit-scrollbar {
  width: 9px;
  height: 12px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1; 
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #888; 
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555; 
}

/* Estilo para un scroll específico */
.scroll-container {
  overflow-y: auto;
  scrollbar-width: thin; /* Firefox */
  scrollbar-color: #888 #f1f1f1; /* Firefox */
}

/* Estilo para el scroll específico en Webkit (Chrome, Safari) */
.scroll-container::-webkit-scrollbar {
  width: 12px;
  height: 12px;
}

.scroll-container::-webkit-scrollbar-track {
  background: #f1f1f1; 
  border-radius: 10px;
}

.scroll-container::-webkit-scrollbar-thumb {
  background: #888; 
  border-radius: 10px;
}

.scroll-container::-webkit-scrollbar-thumb:hover {
  background: #555; 
}

</style>
