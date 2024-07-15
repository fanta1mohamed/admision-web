<template>
    <a-dropdown :trigger="['click']">
    <a @click.prevent style="text-decoration: none; color: black;">
      {{ $page.props.auth.user.name }} &nbsp;
      <DownOutlined />
    </a>
    <template #overlay>
      <a-menu>
        <a-menu-item key="0">
          <button @click="dropdownOpen = ! dropdownOpen" class="relative block overflow-hidden">                   
              </button>   
        </a-menu-item>
          <dropdown-link :href="route('profile.edit')">
              Configurar perfil
          </dropdown-link>
          <a-menu-divider />
          <dropdown-link class="w-full text-left" :href="route('logout')" method="post" as="button">
              Salir del sistema
          </dropdown-link>
      </a-menu>
    </template>
  </a-dropdown>
</template>
<script>
import { defineComponent } from 'vue';
import { DownOutlined } from '@ant-design/icons-vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
  export default defineComponent({
    components: {
      DownOutlined,
      Dropdown,
      DropdownLink
    },
  });
</script>
