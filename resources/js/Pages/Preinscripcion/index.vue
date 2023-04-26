<template>
<Head title="Procesos"/>
<AuthenticatedLayout>
  <div class="p-4" style="">
    <div> 
      <div style="">
        <a-steps v-model:current="current" direction="horizontal">
          <template #progressDot="{ index, status, prefixCls }">
            <a-popover>
              <template #content>
                <span>step {{ index }} status: {{ status }}</span>
              </template>
              <span :class="`${prefixCls}-icon-dot`" />
            </a-popover>
          </template>
          <a-step title=""/>
          <a-step title=""/>
          <a-step title=""/>
          <a-step title=""/>
          <a-step title=""/>
          <a-step title=""/>
        </a-steps>
      </div>
      <a-divider type="vertical"/>
      <div v-if="current===0" style="width: 100%;">
        <div class="flex" style="justify-content: center; align-items: center; width: 100%;">
          <a-card style="min-width: 300px; height: 300px;">
            <div style="margin-bottom: 10px;"><label>Dni</label></div>
            <a-input v-model:value="value" placeholder="Basic usage"/>

            <div style="display: flex; justify-content: center; margin-top: 10px;">
              <a-button type="primary">Iniciar Postulación</a-button>
            </div>

            <div>
              <div>
                  <a-card class="mb-3" @click="value = 2" :class="[value === 2? 'cards' : '' ]">
                    <div style="width: 220px;">
                      d
                    </div>
                  </a-card>

                  <a-card @click="value = 3" :class="[value === 3? 'cards' : '' ]">
                    <div style="width: 220px;">
                      d
                    </div>
                  </a-card>
              </div>
            </div>
          </a-card>
        </div>
        
      </div>
      <div v-if="current===1" style="width: 100%;">
        Paso 2
      </div>
      <div v-if="current===2" style="width: 100%;">
        Paso 3
      </div>
    </div>
    <div class="flex">
      <!-- <a-button v-if="current > 0" @click="prev()" type="primary">Iniciar Postulación</a-button>
      <a-button v-if="current > 0" @click="prev()" type="primary">Anterior</a-button>
      <a-button v-if="current < 2" @click="next()" >Siguiente</a-button>     -->
    </div>
  </div>
</AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { watch, computed, ref, unref } from 'vue';
import { UserOutlined, SolutionOutlined, LoadingOutlined, SmileOutlined, } from '@ant-design/icons-vue';

const current = ref(0);
const next = () => { current.value++; };
const prev = () => { current.value--; };

const value = ref(1);

// watch(buscar, ( newValue, oldValue ) => {
//   getProcesos();
// })

    
</script>
<style scoped>
.cards{
  border: solid 1px blue;
}
</style>