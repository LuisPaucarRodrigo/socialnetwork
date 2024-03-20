<template>
    <Head title="Selección del Proyecto" />
    <AuthenticatedLayout>
        <template #header>
            Selección del Proyecto
        </template>
        <div class="grid sm:grid-cols-5">
            <div class="col-span-full flex flex-col space-y-5">
                <select v-model="selectedProjectId"
                    class="block w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring focus:border-blue-300">
                    <option value="" selected disabled>Seleccione un proyecto</option>
                    <option v-for="project in projects" :key="project.id" :value="project.id">
                        {{ project.name }}
                    </option>
                </select>
                <div class="flex justify-end">
                    <button @click="goToBudget" type="button"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                        Seleccionar
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    projects: Object,
});

const selectedProjectId = ref("");

const goToBudget = () => {
    if (selectedProjectId.value) {
        router.get(route('initialbudget.index', { project: selectedProjectId.value }));
    } else {
        return '#';
    }
};
</script>
