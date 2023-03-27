<template>
    <h1> Page Roles </h1>
    <pre> {{ props.roles }} </pre>
</template>

<script setup>
const props = defineProps(['roles'])

</script>