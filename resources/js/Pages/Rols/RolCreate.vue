<template>
    <Head title="Crear Roles" />
    <AuthenticatedLayout>
        <template #header>
            Roles
        </template>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    permission: Object
})
</script>