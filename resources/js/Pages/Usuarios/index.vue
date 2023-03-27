<template>
<Head title="Usuarios" />
<AuthenticatedLayout>
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 border-b border-gray-200">
      {{ selectedRowKeys }}
      <a-table :row-selection="rowSelection" :columns="columns" :data-source="data" size="small"/>
    </div>
</div>


</AuthenticatedLayout>

</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { computed, ref, unref } from 'vue';

const props = defineProps(['usuarios'])

const columns = [
{ title: 'Name', dataIndex: 'name'}, 
{ title: 'Age', dataIndex: 'age' }, 
{ title: 'Address', dataIndex: 'address'}];

const data = [];
for (let i = 0; i < 26; i++) {
  data.push({
    key: i,
    name: `Edward King ${i}`,
    age: 32,
    address: `London, Park Lane no. ${i}`,
  });
}

const selectedRowKeys = ref([]); 

const onSelectChange = changableRowKeys => {
  console.log('selectedRowKeys changed: ', changableRowKeys);
  selectedRowKeys.value = changableRowKeys;
};
const rowSelection = computed(() => {
  return {
    selectedRowKeys: unref(selectedRowKeys),
    onChange: onSelectChange,
    hideDefaultSelections: true,
  };
});

</script>