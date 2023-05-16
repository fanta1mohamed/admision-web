<template>
  {{ inputValue }}
  <a-select
    v-model:value="selectedOption"
    show-search
    style="width: 200px"
    placeholder="Select a person"
    @change="handleChange"
    @input="handleInput"
  >
    <a-select-option v-for="option in options" :key="option.value" :value="option.value">
      {{ option.label }}
    </a-select-option>
  </a-select>
</template>

<script setup>
import { ref, watch } from 'vue';

const options = ref([
  { value: 'jack', label: 'Jack' },
  { value: 'lucy', label: 'Lucy' },
  { value: 'tom', label: 'Tom' },
]);

const selectedOption = ref(null);
const inputValue = ref('');

const handleChange = (value) => {
  console.log(`Selected: ${value}`);
};

const handleInput = (event) => {
  inputValue.value = event.target.value;
};

watch(inputValue, (value) => {
  console.log(`Input value: ${value}`);
});
</script>