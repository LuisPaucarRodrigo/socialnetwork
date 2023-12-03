<template>
    <Head title="Ordenes de Compra"/>
    <AuthenticatedLayout>
        <template #header>
            Ordenes
        </template>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>