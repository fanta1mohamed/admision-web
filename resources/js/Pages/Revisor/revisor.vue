<template>
    <AuthenticatedLayout>
    <h1>
        Revisores
    </h1>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/LayoutDocente.vue'
import { watch, computed, ref, unref } from 'vue';
import { FormOutlined, DeleteOutlined, SearchOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import axios from 'axios';



</script>