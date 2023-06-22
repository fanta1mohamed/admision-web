<template>
  <a-layout style="min-height: 100vh">
    <a-layout-sider v-model:collapsed="collapsed" width="230px" style="background:#476175; width: 230px; border-right: 1px solid #00000010;"  :trigger="null" :triggerSubMenuAcción="true" collapsible collapsed-width="0">
      <div style="display: flex; align-items: center; justify-content: left; margin-top: 6px; margin-left: 20px; margin-bottom: 6px;">
        <div>
            <img src="../../assets/imagenes/logotiny.png" width="35"/>
        </div> 
        <div style="border-left: 1px solid #d9d9d9; height: 30px; margin-left: 10px; margin-right: 5px; "></div>
        <div style="margin: 0px 6px; color: #f3f3f3; margin-top: -5px;">
            <div style="display: flex; justify-content: center; margin-top: 10px;"><span style="letter-spacing: .1rem; font-size:.90rem; font-weight: 500;">DIRECCIÓN DE</span></div>
            <div style="display: flex; justify-content: center; margin-top: -8px;"><span style="letter-spacing: .1rem; font-size: 1.38rem; font-weight: 600;">ADMISIÓN</span></div>
        </div>
    </div>

    <a-menu 
        v-model:selectedKeys="selectedKeys" 
        v-model:openKeys="openKeys"
        theme="dark" mode="inline" style="background: none; margin-left: -5px; font-weight: 500; font-size: .8rem; ">
        <div style="margin-top: 30px; margin-left: 25px; margin-bottom: 25px;">
          <span>Administración</span>
        </div>
        
        <a-menu-item key="1">
          <div style="display: flex; align-items: center;">
            <appstore-filled style=""/>
            <div style="margin-left: 10px;"><span>Dashboard</span></div>  
          </div>

        </a-menu-item>

        <a-sub-menu key="sub3" style="">
          <template #title>
            <span>
              <setting-filled/>
              <span>Configuración</span>
            </span>
          </template>
          <a-menu-item key="2" style="margin-left: -30px; width: 265px; background:#476175; margin-top: -0px;"> 
            <div v-if="route().current('proceso-index') && openKeys !== null" style="display: none;">{{ titleClick('sub3') }}</div>
            <nav-link :href="route('proceso-index')" :active="route().current('proceso-index')" style="margin: 0px; margin-left: -25px; ">
              <div class="flex" style="align-items: center;">
                <setting-filled/>
                <span>Procesos</span>
              </div>
            </nav-link>
          </a-menu-item>

          <a-menu-item key="3" style="margin-left: -30px; width: 265px; background:#476175; margin-top: -10px;"> 
            <div v-if="route().current('programa-index')" style="display: none;">{{ titleClick('sub3') }}</div>
            <nav-link :href="route('programa-index')" :active="route().current('programa-index')" style="margin: 0px; margin-left: -25px; ">
              <div class="flex" style="align-items: center;">
                <setting-filled/>
                <span>programas</span>
              </div>
            </nav-link>
          </a-menu-item>

          <a-menu-item key="4" style="margin-left: -30px; width: 265px; background:#476175; margin-top: -10px;">
            <div v-if="route().current('modalidad-index')" style="display: none;">{{ titleClick('sub3') }}</div>
            <nav-link :href="route('modalidad-index')" :active="route().current('modalidad-index')" style="margin: 0px; margin-left: -25px; ">
              <div class="flex" style="align-items: center;">
                <setting-filled/>
                <span>modalidades</span>
              </div>
            </nav-link>
          </a-menu-item>

          <a-menu-item key="5" style="margin-left: -30px; width: 265px; background:#476175; margin-top: -10px; margin-bottom: -5px;"> 
            <div v-if="route().current('filial-index')" style="display: none;">{{ titleClick('sub3') }}</div>
            <nav-link :href="route('filial-index')" :active="route().current('filial-index')" style="margin: 0px; margin-left: -25px; ">
              <div class="flex" style="align-items: center;">
                <setting-filled/>
                <span>Filiales</span>
              </div>
            </nav-link>
          </a-menu-item>
        </a-sub-menu>

        <a-sub-menu key="sub1" >
          <template #title>
            <span>
              <user-outlined />
              <span>Administrador</span>
            </span>
          </template>
          <a-menu-item key="2" style="margin-left: -30px; width: 265px; background:#476175; margin-top: -0px;"> 
            <div v-if="route().current('roles-index') && openKeys !== null" style="display: none;">{{ titleClick('sub1') }}</div>
            <nav-link :href="route('roles-index')" :active="route().current('roles-index')" style="margin: 0px; margin-left: -25px; ">
              <div class="flex" style="align-items: center;">
                <setting-filled/>
                <span>Roles</span>
              </div>
            </nav-link>
          </a-menu-item>

          <a-menu-item key="3" style="margin-left: -30px; width: 265px; background:#476175; margin-top: -10px; margin-bottom:-5px;"> 
            <div v-if="route().current('usuarios-index')" style="display: none;">{{ titleClick('sub1') }}</div>
            <nav-link :href="route('usuarios-index')" :active="route().current('usuarios-index')" style="margin: 0px; margin-left: -25px; ">
              <div class="flex" style="align-items: center;">
                <setting-filled/>
                <span>usuarios</span>
              </div>
            </nav-link>
          </a-menu-item> 

        </a-sub-menu>

        <hr style="border-top: 1px solid #FFFFFF23;">

          <div style="margin-top: 30px; margin-left: 25px; margin-bottom: 25px;">
            <span>Inscripcion</span>
          </div>

          <a-menu-item key="incripciones" style="margin-left: -10px; width: 250px;">
            <nav-link :href="route('impresion-cepre')" :active="route().current('impresion-cepre')" style="margin: 0px; margin-left: -25px; ">
              <appstore-filled style=""/>
              <span>Inscripción</span>
            </nav-link>
          </a-menu-item>
          
          <hr style="border-top: 1px solid #FFFFFF23;">

        <div style="margin-top: 30px; margin-left: 25px; margin-bottom: 25px;">
          <span>Soporte</span>
        </div>

        <a-sub-menu key="sub2">
          <template #title>
            <span>
              <team-outlined />
              <span>Team</span>
            </span>
          </template>
          <a-menu-item key="10">Team 1</a-menu-item>
          <a-menu-item key="11">Team 2</a-menu-item>
        </a-sub-menu>
        <a-menu-item key="12">
          <file-outlined />
          <span>File</span>
        </a-menu-item>
        <hr style="border-top: 1px solid #FFFFFF23;">
      </a-menu>

    </a-layout-sider>
    <a-layout>

      <a-layout-header style="background: #fff; padding: 0; position: relative;" >
      <div style="border-bottom: 1px solid #00000013;">
        <menu-fold-outlined style="font-size: 1.3rem; margin-left: 20px;" class="trigger" @click="() => (collapsed = !collapsed)" />
        <div style=" display: flex; align-items: center; justify-content: right; position: absolute; right: 20px; top: 0px; margin-bottom: -130px !important;">
          <Header/>
        </div>
      </div>

    </a-layout-header>
    <a-layout-content
      :style="{ margin: '14px 14px', padding: '0px', background: '#fff', minHeight: '280px' }">
      <slot />
    </a-layout-content>
  </a-layout>
</a-layout>
</template>
<script>
import NavLink from '@/Components/NavLink.vue';
import { Link } from '@inertiajs/vue3'
import Header from '@/Layouts/Header.vue';
import { 
  PieChartOutlined, DesktopOutlined, UserOutlined, AppstoreFilled, TeamOutlined, FileOutlined,
  MenuUnfoldOutlined, PieChartTwoTone, SettingFilled,
  MenuFoldOutlined,
} from '@ant-design/icons-vue';
import { defineComponent, ref } from 'vue';
export default defineComponent({
  components: {
    Header,
    PieChartOutlined,
    DesktopOutlined,
    UserOutlined,
    TeamOutlined,
    FileOutlined,
    PieChartTwoTone,
    AppstoreFilled,
    MenuUnfoldOutlined,
    MenuFoldOutlined,
    NavLink,
    Link,
    SettingFilled
  },
  data() {
    return {
      key: 'sub1',
      collapsed: ref(false),
      openKeys: ['']
    };
  },
  methods:{
    titleClick (open) {
      let item = this.openKeys.includes(open);
      if(item == false){
        this.openKeys.push(open)
      }
    }
  },



  watch:{
    openKeys (open) {
      // console.log('openKeys', val);
    }
  } 
});
</script>

