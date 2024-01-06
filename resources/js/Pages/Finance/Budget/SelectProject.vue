<template>
    <Head title="Selección del Proyecto" />
    <AuthenticatedLayout>
        <template #header>
            Selección del Proyecto
        </template>
        <div class="relative inline-block">
            <!-- Tu código para el select -->
            <select v-model="selectedProjectId"
                class="block w-full sm:w-48 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring focus:border-blue-300">
                <option value="null" selected disabled>Seleccione un proyecto</option>
                <option v-for="project in projects" :key="project.id" :value="project.id">
                    {{ project.name }}
                </option>
            </select>
        </div>
        <div class="flex items-center justify-center space-x-4 mt-5">
            <button @click="goToBudget" type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                Seleccionar
            </button>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, defineProps } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';

const props = defineProps({
    projects: Object,
    project_id: String,
});

const selectedProjectId = ref(props.project_id);

const goToBudget = () => {
    console.log(selectedProjectId.value);
    if (selectedProjectId.value) {
        router.get(route('initialbudget.index', { project: selectedProjectId.value }));
    } else {
        console.error('Por favor, seleccione un proyecto.');
        return '#'; // Otra opción podría ser usar una URL válida o simplemente '#'
    }
};

</script>
