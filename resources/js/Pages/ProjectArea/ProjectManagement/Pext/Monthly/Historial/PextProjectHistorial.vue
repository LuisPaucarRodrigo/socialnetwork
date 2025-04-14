<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="'projectmanagement.pext.index'">
        <template #header>
            Proyectos Pext Culminados
        </template>
        <div class="min-w-full">
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <div class="flex space-x-4">
                    <TextInput data-tooltip-target="search_fields" type="text" @input="search($event.target.value)"
                        placeholder="Buscar..." />
                    <div id="search_fields" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Nombre
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <ProjectCard v-for="item in projects.data || projects" :key="item.id" :item="item"
                    :user-permissions="userPermissions" :auth="auth" />
            </div>
            <br>
            <div v-if="projects.data" class="flex flex-col items-center px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="projects.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import axios from 'axios';
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import ProjectCard from '@/Pages/ProjectArea/ProjectManagement/Pext/Monthly/components/ProjectCard.vue';

const { project, userPermissions, auth } = defineProps({
    project: Object,
    userPermissions: Array,
    auth: Object,
})

const projects = ref(project);

const search = async (search) => {
    try {
        const response = await axios.post(route('projectmanagement.pext.historial'), { searchQuery: search });
        projects.value = response.data;
    } catch (error) {
        console.error('Error searching:', error);
    }
};


</script>
