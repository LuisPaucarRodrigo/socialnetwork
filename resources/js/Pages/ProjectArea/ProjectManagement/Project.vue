<template>
    <Head title="Proyectos"/>
    <AuthenticatedLayout>
        <template #header>
            Proyectos
        </template>
        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <button @click="add_project" type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                + Agregar
            </button>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

const add_project = () => {
    router.get(route('providersmanagement.information'));
}
</script>