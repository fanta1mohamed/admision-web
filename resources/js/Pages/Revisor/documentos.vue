<template>
<AuthenticatedLayout>
  <div>
    <a-card style="background: white;" class="m-0 p-0">
      <a-row :gutter="16">
        <a-col :span="24" :sm="24" :md="24" :lg="24" style="display:flex; justify-content: end; align-items: end;" >
            <a-input placeholder="Buscar" v-model:value="buscar"  style="max-width: 300px; margin-right: -8px; ">
              <template #suffix>
                <search-outlined />
              </template>
            </a-input>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="24" :sm="24" :md="24" :lg="24" style="display:flex; justify-content: end; align-items: end;" >
          <div class="flex justify-between" style="background: #d9d9d9; width: 100%; margin-right: -8px;">
            <div v-for="(option, index) in options" :key="option.value">
              <div class="flex justify-center" style="font-size: 44px;">
                <img src="data:image/png;base64,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" width="60"/>
              </div>
              {{ option.label }}
            </div>

          </div>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="24" :sm="24" :md="8" :lg="6">
          <div>
            <h1 style="font-weight: bold;">Requisitos</h1>
            <a-checkbox v-model:checked="checkAll" class="first-item" @change="onCheckAllChange">Todo</a-checkbox>
            <a-checkbox-group v-model:value="checkedList" class="checkbox-group-vertical">
              <a-checkbox v-for="(option, index) in options" :key="option.value" :value="option.value" :class="{ 'first-item': index === 0 }" class="checkbox-item">
                {{ option.label }}
              </a-checkbox>
            </a-checkbox-group>
            
          </div>
        </a-col>
        <a-col :span="24" :sm="24" :md="16" :lg="18" style="border: 1px solid #d9d9d9;">
          sd
        </a-col>
      </a-row>
    <a-form-item>
      <a-button type="primary" @click="submitForm">Enviar</a-button>
    </a-form-item>

    </a-card>
  </div>
</AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/LayoutDocente.vue'
import { watch, computed, ref, unref } from 'vue';
import { FormOutlined, DeleteOutlined, SearchOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';

const checkedList = ref([]);
const options = [
  { label: 'Solicitud', value: 1 },
  { label: 'Vouchers', value: 2 },
  { label: 'Certificado', value: 3 },
  { label: 'Ex vocacional', value: 4 },
  { label: 'C. Cepreuna', value: 5 },
];

const checkAll = ref(false);

const onCheckAllChange = (e) => {
  checkAll.value = e.target.checked;
  checkedList.value = e.target.checked ? options.map((option) => option.value) : [];
};

const onCheckboxChange = (checkedValues) => {
  checkedList.value = checkedValues;
  checkAll.value = checkedValues.length === options.length;
};

</script>


<style scoped>
.checkbox-group-vertical {
  display: flex;
  flex-wrap: wrap;
}

.checkbox-item {
  flex: 0 0 100%;
  margin-bottom: 8px;
}

.first-item {
  margin-left: 8px;
  margin-bottom: 8px;
}
</style>