<template>
    <Head title="Gestion de Aprobaciones" />
    <AuthenticatedLayout redirectRoute="fleet.cars.index">
        <Toaster richColors />
        <template #header> Aprobaciones de cambios de documentos </template>
        <ApprovalsTable :change="change"/>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Toaster } from 'vue-sonner';
import ApprovalsTable from './components/ApprovalsTable.vue';

const { change } = defineProps({
    change: Object
})

</script>