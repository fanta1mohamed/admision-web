<template>
  <a-layout style="min-height: 100vh">
    <a-layout-sider v-model:collapsed="collapsed" :trigger="null" collapsible collapsed-width="0" style="background: #000200003; background: #FFFFFF;">
      <div class="logo" />
      <a-menu v-model:selectedKeys="selectedKeys" theme="light" mode="inline" style="background: none;">
        <a-menu-item key="1">
          <pie-chart-outlined />
          <span>Option 1</span>
        </a-menu-item>
        <a-menu-item key="2">
          <desktop-outlined />
          <span>Option 2</span>
        </a-menu-item>
        <a-sub-menu key="sub1">
          <template #title>
            <span>
              <user-outlined />
              <span>User</span>
            </span>
          </template>
          <a-menu-item key="3">Tom</a-menu-item>
          <a-menu-item key="4">Bill</a-menu-item>
          <a-menu-item key="5">Alex</a-menu-item>
        </a-sub-menu>
        <a-sub-menu key="sub2">
          <template #title>
            <span>
              <team-outlined />
              <span>Team</span>
            </span>
          </template>
          <a-menu-item key="6">Team 1</a-menu-item>
          <a-menu-item key="8">Team 2</a-menu-item>
        </a-sub-menu>
        <a-menu-item key="9">
          <file-outlined />
          <span>File</span>
        </a-menu-item>
      </a-menu>
    </a-layout-sider>
    <a-layout>

      <a-layout-header style="background: #fff; padding: 0; position: relative;" >
      <div style="border-bottom: 1px solid #00000013;">
          <menu-fold-outlined style="font-size: 1.3rem; margin-left: 20px;" class="trigger" @click="() => (collapsed = !collapsed)" />
          <div style="width: 150px; display: flex; align-items: center; justify-content: right; position: absolute; right: 20px; top: 0px; margin-bottom: -130px !important;">
            <Header/>
          </div>
      </div>

    </a-layout-header>
    <a-layout-content
      :style="{ margin: '24px 16px', padding: '24px', background: '#fff', minHeight: '280px' }">
      <slot />
    </a-layout-content>
  </a-layout>
</a-layout>
</template>
<script>
import Header from '@/Layouts/Header.vue';
import { PieChartOutlined, DesktopOutlined, UserOutlined, TeamOutlined, FileOutlined,
  MenuUnfoldOutlined,
  MenuFoldOutlined,} from '@ant-design/icons-vue';
import { defineComponent, ref } from 'vue';
export default defineComponent({
  components: {
    Header,
    PieChartOutlined,
    DesktopOutlined,
    UserOutlined,
    TeamOutlined,
    FileOutlined,
      MenuUnfoldOutlined,
      MenuFoldOutlined,
  },
  data() {
    return {
      collapsed: ref(false),
      selectedKeys: ref(['1']),
    };
  },
});
</script>