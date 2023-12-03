<template>
    <Head title="Calendario de Proyectos"/>
    <AuthenticatedLayout>
        <template #header>
            Calendario
        </template>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>