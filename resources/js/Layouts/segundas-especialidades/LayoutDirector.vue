<template>
  <a-layout class="min-h-screen">
    <!-- Sidebar -->
    <a-layout-sider
      v-model:collapsed="collapsed"
      :width="270"
      :collapsed-width="0"
      class="custom-sider"
    >
      <div class="sider-header">
        <img 
          src="../../assets/imagenes/logotiny.png" 
          class="sider-logo"
          alt="Logo"
        />
        <div class="sider-title">
          Segunda especialidad
        </div>
      </div>

      <a-menu
        v-model:selectedKeys="selectedKeys"
        v-model:openKeys="openKeys"
        theme="dark"
        mode="inline"
        class="custom-menu"
      >
        <template v-for="item in menuItems" :key="item.key">
          <a-menu-item 
            v-if="!item.children" 
            :key="item.key"
            :class="{ 'menu-item-active': route().current(item.route) }"
          >
            <Link :href="route(item.route)">
              <component :is="item.icon" class="menu-icon" />
              <span class="menu-text">{{ item.label }}</span>
            </Link>
          </a-menu-item>
          
          <a-sub-menu 
            v-else 
            :key="item.key"
            :title="item.label"
            class="submenu"
          >
            <template #icon>
              <component :is="item.icon" />
            </template>
            
            <a-menu-item
              v-for="child in item.children"
              :key="child.key"
              :class="{ 'menu-item-active': route().current(child.route) }"
            >
              <Link :href="route(child.route)">
                <component :is="child.icon" class="child-icon" />
                <span class="child-text">{{ child.label }}</span>
              </Link>
            </a-menu-item>
          </a-sub-menu>
        </template>
      </a-menu>
    </a-layout-sider>

    <!-- Contenido principal -->
    <a-layout>
      <a-layout-header class="main-header" style="background: white;">
        <menu-fold-outlined 
          class="collapse-trigger"
          @click="collapsed = !collapsed"
        />
        <Header class="header-content" />
      </a-layout-header>

      <a-layout-content class="main-content">
        <div class="content-container">
          <slot />
        </div>
      </a-layout-content>
    </a-layout>
  </a-layout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { 
  AppstoreFilled,
  SettingFilled,
  MenuFoldOutlined
} from '@ant-design/icons-vue';

const collapsed = ref(false);
const selectedKeys = ref([]);
const openKeys = ref([]);

const menuItems = [
  {
    key: 'dashboard',
    icon: AppstoreFilled,
    label: 'Dashboard',
    route: 'admin-dashboard'
  },
  {
    key: 'configuracion',
    icon: SettingFilled,
    label: 'Configuración',
    children: [
      {
        key: 'procesos',
        icon: SettingFilled,
        label: 'Procesos',
        route: 'proceso-index'
      },
      {
        key: 'programas',
        icon: SettingFilled,
        label: 'Programas',
        route: 'programa-index'
      },
      {
        key: 'vacantes',
        icon: SettingFilled,
        label: 'Vacantes',
        route: 'admin-vacantes'
      }
    ]
  },
  {
    key: 'mantenimiento',
    icon: SettingFilled,
    label: 'Mantenimiento',
    children: [
      {
        key: 'preinscripciones',
        icon: SettingFilled,
        label: 'Preinscripciones',
        route: 'proceso-index'
      },
      {
        key: 'Postulantes',
        icon: SettingFilled,
        label: 'Programas',
        route: 'programa-index'
      },
      {
        key: 'pagos',
        icon: SettingFilled,
        label: 'Pagos',
        route: 'programa-index'
      },



    ]
  }
];

// Función para encontrar el menú padre de una ruta activa
const findParentKey = (routeName) => {
  for (const item of menuItems) {
    if (item.children) {
      const found = item.children.find(child => route().current(child.route));
      if (found) return item.key;
    }
  }
  return null;
};

// Actualizar selectedKeys y openKeys cuando cambia la URL
watch(() => router.page.url, () => {
  const activeItem = menuItems
    .flatMap(item => item.children ? item.children : item)
    .find(item => route().current(item.route));

  selectedKeys.value = activeItem ? [activeItem.key] : [];
  
  const parentKey = findParentKey(router.page.url);
  openKeys.value = parentKey ? [parentKey] : [];
}, { immediate: true });
</script>

<style>
:root {
  --sider-bg: #2d3748;
  --primary-color: #1890ff;
  --hover-bg: #4a5568;
  --text-color: #cdcdcd;
}

.custom-sider {
  background: var(--sider-bg);
  border-right: 1px solid #0000000f;
}

.sider-header {
  display: flex;
  align-items: center;
  padding: 16px;
}

.sider-logo {
  width: 25px;
  height: 25px;
}

.sider-title {
  margin-left: 12px;
  color: var(--text-color);
  font-size: 1.1rem;
  font-weight: 500;
}

.custom-menu .ant-menu-item,
.custom-menu .ant-menu-submenu-title {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.custom-menu .menu-item-active {
  background: linear-gradient(
    90deg,
    rgba(24, 144, 255, 0.3) 0%,
    rgba(24, 144, 255, 0.1) 100%
  ) !important;
  border-left: 3px solid var(--primary-color) !important;
  /* margin-left: 46px !important; */

  /* padding-left: 38px !important; */
}

.custom-menu .menu-item-active .menu-text,
.custom-menu .menu-item-active .anticon {
  color: white !important;
  font-weight: 600;
}

.custom-menu .ant-menu-item:hover {
  background: var(--hover-bg) !important;
}

.main-header {
  background: white;
  display: flex;
  align-items: center;
  padding: 0 16px;
  border-bottom: 1px solid #00000014;
}

.collapse-trigger {
  font-size: 1.3rem;
  cursor: pointer;
  transition: color 0.2s;
}

.collapse-trigger:hover {
  color: var(--primary-color);
}

.header-content {
  margin-left: auto;
}

.main-content {
  height: calc(100vh - 94px);
  margin-top: 14px;
}

.content-container {
  padding: 0 14px 14px 14px;
}
</style>
